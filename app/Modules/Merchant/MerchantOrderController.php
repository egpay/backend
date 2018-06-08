<?php

namespace App\Modules\Merchant;

use App\Libs\WalletData;
use App\Models\AreaType;
use App\Models\AttributeValue;
use App\Models\MerchantBranch;
use App\Models\MerchantPlan;
use App\Models\MerchantProduct;
use App\Models\MerchantProductCategory;
use App\Models\MerchantStaff;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\WalletTransaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;
use App\Models\MerchantCategory;
use Illuminate\Support\Facades\Validator;
use Auth;

class MerchantOrderController extends MerchantController
{
    protected $viewData;

    public function index(Request $request){
        $merchant = Auth::user()->merchant();
        if($request->isDataTable){
            $user_branches = Auth::user()->staff_branches();

            $eloquentData = Order::viewData($this->systemLang);

            whereBetween($eloquentData,'orders.created_at',$request->created_at1,$request->created_at2);

            $eloquentData->where('merchant_branches.merchant_id','=',$merchant->id);
            $eloquentData->whereIn('merchant_branches.id',$user_branches);



            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('branch_name',function($data){
                    return $data->branch_name;
                })

                ->addColumn('Products',function($data){
                    return $data->count_order_items;
                })
                ->addColumn('paid',function($data){
                    if($data->is_paid == 'paid')
                        return "<span class='text-success'>".__('Paid')."</span>";
                    else
                        return "<span class='text-danger'>".__('UnPaid')."</span>";
                })

                ->addColumn('action',function($data){

                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('panel.merchant.order.show',$data->id)."\">".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('panel.merchant.order.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
                              </ul>
                            </div>";
                })
                ->make(true);
        }else{

            // View Data
            $this->viewData['tableColumns'] = [__('Order ID'),__('Branch name'),__('Products'),__('Status'),__('Action')];


            $this->viewData['pageTitle'] = __('Orders');

            return $this->view('order.index',$this->viewData);
        }
    }

    public function create(Request $request)
    {
        $merchant = Auth::user()->merchant();
        $user_branches = Auth::user()->staff_branches();
        if(!count($user_branches))
            return abort(404);
        if($request->type == 'productcategory'){
            if(isset($request->word)){
                return $merchant->merchant_product_categories()->Active()->get()->pluck('name_'.$this->systemLang,'id');
            }
            return $merchant->merchant_product_categories->pluck('name_'.$this->systemLang,'id');
        }
        $this->viewData['pageTitle'] = __('Create Merchant Order');
        $this->viewData['areaData'] = AreaType::getFirstArea($this->systemLang);

        $this->viewData['merchantData'] = $merchant;
        $this->viewData['merchantBranches'] = $merchant->merchant_branch()->Active()->whereIn('id',$user_branches)->get()->pluck('name_'.$this->systemLang,'id');
        $this->viewData['merchantcategory'] = $merchant->merchant_product_categories()->Active()->get()->pluck('name_'.$this->systemLang,'id');
        return $this->view('order.create',$this->viewData);
    }


    public function store(Request $request){
        $RequestData = $request->only(['merchant_branch_id','users','product']);
        $merchant = Auth::user()->merchant();

        Validator::make($RequestData, [
            'merchant_branch_id'        => 'required|integer',
            'users.*'                   => 'required|array:3',
            'users.*.id'                => 'required|integer',
            'users.*.amount'            => 'required|integer',
            'users.*.paytype'           => 'required|in:wallet,cash',
            'product.*.id'              => 'required|integer|in:'.implode(',',$merchant->merchant_products()->pluck('id')->toArray()),
        ])->validate();

        $RequestData['merchant_staff_id'] = Auth::id();

        $GLOBALS['status'] = false;
        $lang = $this->systemLang;
        try {
            DB::transaction(function () use ($RequestData, $request, $merchant, $lang) {
                $Order = Order::create([
                    'merchant_branch_id' => $RequestData['merchant_branch_id'],
                    'creatable_id' => Auth::id(),
                    'creatable_type' => get_class(Auth::user()),
                ]);

                $ProIds = recursiveFind($RequestData['product'], 'id');
                $Products = $merchant->merchant_products()->whereIn('id', $ProIds)->with('attribute')->get();

                $totalArr = [];
                foreach ($RequestData['product'] as $oneProduct) {
                    $productData = $Products->where('id','=', $oneProduct['id'])->first();
                    $productAttributes = $productData->attribute()->with('attribute')->get();
                    $orderItemData = [];
                    //Validate product Attributes
                    $orderItemAttribute = [];
                    if (count($productAttributes)) {
                        $attributeAddPrice = [];
                        if ($requiredAttr = $productAttributes->where('required', '=', '1')) {
                            foreach ($requiredAttr as $onerequiredAttr) {
                                if (!in_array($onerequiredAttr->attribute_id, array_keys($oneProduct['attribute']))) {
                                    //Required Attribute not provided, exit here
                                    return false;
                                }
                            }
                        }

                        //Validate the chosen attributes
                        foreach ($oneProduct['attribute'] as $attID => $AttVal) {
                            if (is_array($AttVal['val']))
                                $AttVal['val'] = end($AttVal['val']);
                            if (!in_array($productAttributes->where('attribute_id', $attID)->first()->attribute->type, ['text', 'textarea'])) {
                                if (!$attrValue = AttributeValue::where('attribute_id', '=', $attID)->where('text_' . $lang, '=', $AttVal['val'])->first()) {
                                    //Value inserted not from our attribute values
                                    return false;
                                } else {
                                    if ($plusPrice = $productAttributes->where('selected_attribute_value', $attrValue->id)->first()->plus_price) {
                                        $attributeAddPrice[] = $plusPrice;
                                    }
                                }
                            }

                            $orderItemAttribute[] = [
                                'attribute_id' => $attID,
                                'attribute_value' => $attrValue->id,
                                'attribute_data' => $AttVal['val'],
                            ];
                        }
                        //All prices Added, Lets add product price
                        $attributeAddPrice[] = $productData->product_price();
                        $productPrice = array_sum($attributeAddPrice);
                        $totalArr[] = $productPrice * $oneProduct['qty'];
                    } else {
                        $productPrice = $productData->product_price();
                        $totalArr[] = $productPrice * $oneProduct['qty'];
                    }

                    //Add to Order total
                    $Order->increment('total', $productPrice * $oneProduct['qty']);

                    // Insert Product
                    $oneorederitem = [
                        'order_id' => $Order->id,
                        'merchant_product_id' => $productData->id,
                        'qty' => $oneProduct['qty'],
                        'price' => $productPrice,
                    ];
                    $OrderItem = $Order->orderitems()->create($oneorederitem);

                    if (isset($orderItemAttribute) && count($orderItemAttribute)) {
                        //insert attributes if any
                        foreach ($orderItemAttribute as $oneorderitemAttribute) {
                            $OrderItem->orderItemAttribute()->create($oneorderitemAttribute);
                        }
                    }

                }

                if (array_sum($totalArr) != array_sum(recursiveFind($RequestData['users'], 'amount'))) {
                    return false;
                }


                if (isset($RequestData['users'])) {
                    foreach ($RequestData['users'] as $user) {
                        if ($userWalletId = User::where('id', $user['id'])->first()->eCommerceWallet->id) {
                            WalletData::makeTransaction(
                                $user['amount'],
                                $user['paytype'],
                                $userWalletId,
                                Auth::user()->eCommerceWallet->id,
                                'order',
                                $Order->id,
                                get_class(Auth::user()),
                                Auth::id(),
                                'pending'
                            );
                        }
                    }
                }

                $Order->statusable()->create(['status' => 'Requested']);

                $GLOBALS['status'] = true;
                $GLOBALS['orderId'] = $Order->id;
            });
        } catch (\Exception $e){
            return redirect()
                ->route('panel.merchant.order.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Order'));
        }


        if($GLOBALS['status']) {
            return redirect()
                ->route('panel.merchant.order.show',$GLOBALS['orderId'])
                ->with('status', 'success')
                ->with('msg', __('Data has been added successfully'));
        } else {
            return redirect()
                ->route('panel.merchant.order.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Order'));
        }
    }

    public function show(Order $order,Request $request){
        if($order->merchant_branch_id != $order->merchant_branch->id)
            return redirect()->route('panel.merchant.home');

        $user_branches = Auth::user()->staff_branches();
        if(!count($user_branches))
            return abort(404);

        if($request->input()){
            $RequestData = $request->only(['status','comment']);

            Validator::make($RequestData, [
                'status'       => 'required|in:requested,approved,disapproved',
            ])->validate();


            $lateststatus = $order->statusable()->latest('created_at')->limit(1)->first()->status;
            //TODO make it in permissions
            $can_change_status = false;
            switch($RequestData['status']){
                case 'requested':
                    if($lateststatus == 'requested')
                        $can_change_status = true;
                break;

                case 'approved':
                case 'disapproved':
                    $can_change_status = true;
                break;
            }

            if($can_change_status) {
                $newstatus = $order->statusable()->create([
                    'status' => $RequestData['status'],
                ]);
            }
            return $newstatus;
        }

        $this->viewData['pageTitle'] = __('View Order #').$order->id;
        $this->viewData['lang'] = $this->systemLang;

        $order->merchant_branch;
        $order->orderitems;
        $this->viewData['order'] = $order;
        $this->viewData['transactions'] = $order->trans;
        $this->viewData['status'] = $order->statusable->reverse();



        return $this->view('order.view',$this->viewData);
    }


    public function edit(Request $request,Order $order){
        $merchant = $request->user()->merchant();
        if($order->merchant_branch_id != $order->merchant_branch->id)
            return redirect()->route('panel.merchant.home');


        $this->viewData['pageTitle'] = __('Edit Order #').$order->id;

        $this->viewData['order'] = $order;
        $this->viewData['branch'] = $order->merchant_branch;
        $this->viewData['items'] = $order->orderitems;
        $this->viewData['transactions'] = $order->trans;
        $this->viewData['users'] = array_column(array_map(function($trans){
            return ['id'=>$trans['from_id'],'name'=>Wallet::where('id',$trans['from_id'])->first()->walletowner->mobile.' ID#'.Wallet::where('id',$trans['from_id'])->first()->walletowner->id];
        },$order->trans->toArray()),'name','id');


        return $this->view('order.create',$this->viewData);
    }


    public function update(Request $request,Order $order)
    {
        $merchant = $request->user()->merchant();
        if($order->merchant_branch_id != $order->merchant_branch->id)
            return redirect()->route('panel.merchant.home');


        $RequestData = $request->only(['merchant_branch_id','users','paytype','useramount','product','qty']);

        Validator::make($RequestData, [
            'merchant_branch_id'        => 'required|numeric',
            'users.*'                   => 'required|numeric',
            'paytype.*'                 => 'required|in:wallet,cash',
            'useramount.*'              => 'required|numeric',
            'product.*'                 => 'required|numeric',
            'qty.*'                     => 'required|numeric',
        ])->validate();

        $status = false;
        DB::transaction(function () use ($RequestData,$request,$order) {

            if(isset($RequestData['product'])) {
                $Products = MerchantProduct::whereIn('id',$RequestData['product'])->get();
                $order_items = [];
                foreach ($RequestData['product'] as $key => $val) {
                    $price = $Products->where('id',$val)->first()->price;
                    $oneorederitem = [
                        'order_id'              => $order->id,
                        'merchant_product_id'   => $val,
                        'qty'                   => (int) $RequestData['qty'][$key],
                        'price'                 => $price,
                    ];
                    $order->orderitems()->save($oneorederitem);
                    array_push($order_items,$oneorederitem);
                }
            }

            if(isset($RequestData['users'])){
                $OrderTotal = array_sum(array_map(function($pro){return $pro['price'] * $pro['qty'];},$order_items));
                $useramounts = array_sum($RequestData['useramount']);
                if($OrderTotal != $useramounts)
                    return false;
                $fromtotal = 0;
                foreach($RequestData['users'] as $key=>$user) {
                    if($walletid = User::where('id',$user)->first()->wallet->id) {
                        $order->trans()->save([
                            'amount'            => $RequestData['useramount'][$key],
                            'from_id'           => $walletid,//wallet id
                            'to_id'             => $request->user()->merchant->wallet->id,//wallet id
                            'type'              => $RequestData['paytype'][$key],
                            'status'            => 'unpaid',
                        ]);
                    }
                }
            }

            $status = true;

        });


        if($status) {
            return redirect()->route('panel.merchant.order.index')
                ->with('status','success')
                ->with('msg',__('Successfully edited Order'));
        }else{
            return redirect()->route('panel.merchant.order.edit')
                ->with('status','success')
                ->with('msg',__('Sorry Couldn\'t Edit Order'));
        }
    }



    public function destroy(Order $order,Request $request){
        $merchant = $request->user()->merchant();
        if($order->merchant_branch_id != $order->merchant_branch->id)
            return redirect()->route('panel.merchant.home');

        // Delete Data
        $order->delete();
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('Order has been deleted successfully')];
        }else{
            return redirect()
                ->route('panel.merchant.order.index')
                ->with('status','success')
                ->with('msg',__('This Order has been deleted'));
        }
    }

    public function qrcode($id){
        $user_branches = Auth::user()->staff_branches();

        $Order = Order::where('id','=',$id)
            ->whereIn('merchant_branch_id',$user_branches)
            ->first();
        if($Order->qr_code)
            return ['QRCode'=>$Order->qr_code,'image'=>Base64PngQR($Order->qr_code,['350','350'])];

        do {
            $qrcode = UniqueId();
        } while (Order::where('qr_code','=',$qrcode)->where('id','!=',$id)->first());

        $Order->update(['qr_code'=>$qrcode]);

        return ['QRCode'=>$qrcode,'image'=>Base64PngQR($qrcode,['350','350'])];
    }

    public function ajax(Request $request){
        $type = $request->type;
        switch ($type){
            case 'users':
                $name = $request->search;

                $data = User::whereNull('parent_id')
                    ->where(function($query){
                        // $query->where()
                    });

                break;
        }
    }


}