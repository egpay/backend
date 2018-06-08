<?php

namespace App\Modules\System;

use App\Libs\WalletData;
use App\Models\AttributeValue;
use App\Models\Merchant;
use App\Models\MerchantStaffGroup;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Facades\Datatables;
use App\Http\Requests\MerchantStaffGroupFormRequest;
use Illuminate\Http\Request;

class MerchantOrderController extends SystemController
{
    public function __construct(){
        parent::__construct();
        $this->viewData['breadcrumb'] = [
            [
                'text'=> __('Home'),
                'url'=> url('system'),
            ],
            [
                'text'=> __('Merchant'),
                'url'=> url('system/merchant')
            ]
        ];
        $this->viewData['paymenttype'] = [
            '0'=>__('Select type'),
            'wallet'=>__('Wallet'),
            'cash'=>__('Cash'),
        ];
    }

    public function index(Request $request){
        if($request->isDataTable){

            $eloquentData = Order::SystemViewData($this->systemLang);

            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }

            whereBetween($eloquentData,'merchant_staff_group.created_at',$request->created_at1,$request->created_at2);
            if($request->id){
                $eloquentData->where('orders.id', '=',$request->id);
            }

            if($request->merchant_id){
                $eloquentData->where('merchant_branches.merchant_id',$request->merchant_id);
            }

            if($request->title){
                $eloquentData->where('merchant_branches.title','LIKE',"%{$request->title}%");
            }

            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')

                ->addColumn('merchant_name',function($data){
                    return '<a target="_blank" href="'.route('merchant.merchant.show',$data->merchant_id).'">'.$data->merchant_name.'</a>
                            (<a target="_blank" href="'.route('merchant.branch.show',$data->branch_id).'">'.$data->branch_name.'</a>)';
                })
                ->addColumn('Items','{{$count_order_items}}')
                ->addColumn('total',function($data){
                    return $data->total. ' '.__('LE');
                })
                ->addColumn('action',function($data){
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('merchant.order.show',$data->id)."\">".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a href=\"".route('merchant.order.edit',$data->id)."\">".__('Edit')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('merchant.order.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
                              </ul>
                            </div>";
                })

                ->addColumn('status',function($data){
                    if($data->status == 'in-active'){
                        return 'tr-danger';
                    }
                })
                ->make(true);
        }else{

            // View Data
            $this->viewData['tableColumns'] = ['ID','Merchant/Branch','Items','Total','Action'];


            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Merchant Order');
            }else{
                $this->viewData['pageTitle'] = __('Merchant Orders');
            }


            $this->viewData['breadcrumb'][] = [
                'text'=> __('Merchant Order'),
            ];

            return $this->view('merchant.order.index',$this->viewData);
        }
    }


    public function create()
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Merchant Order'),
            'url'=> url('system/merchant/order')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Create Merchant Order'),
        ];

        $this->viewData['pageTitle'] = __('Create Merchant Order');

        $merchantID = request('merchant_id');
        if($merchantID){
            $merchantData = Merchant::findOrFail($merchantID);
            $this->viewData['merchantData'] = $merchantData;
        }

        return $this->view('merchant.order.create',$this->viewData);
    }


    public function store(Request $request)
    {
        $RequestData = $request->only(['merchant_id','merchant_branch_id','users','product']);

        $merchant = Merchant::Active()->findOrfail($RequestData['merchant_id']);


        Validator::make($RequestData, [
            'merchant_branch_id'        => 'required|integer',
            'users.*'                   => 'required|array:3',
            'users.*.id'                => 'required|integer',
            'users.*.amount'            => 'required|integer',
            'users.*.paytype'           => 'required|in:wallet,cash',
            'product.*.id'              => 'required|integer|in:'.implode(',',$merchant->merchant_products()->pluck('id')->toArray()),
        ])->validate();

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
                                $merchant->eCommerceWallet->id,
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
        } catch (\PDOException $e){
            return redirect()
                ->route('merchant.order.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Order'));
        }


        if($GLOBALS['status']) {
            return redirect()
                ->route('merchant.order.show',$GLOBALS['orderId'])
                ->with('status', 'success')
                ->with('msg', __('Data has been added successfully'));
        } else {
            return redirect()
                ->route('merchant.order.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Order'));
        }

    }


    public function show(Order $order,Request $request){
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Merchant Order'),
            'url'=> url('system/merchant/Order')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Merchant Order'),
        ];

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
            return ((isset($newstatus)) ?
                ['status'=>true,'msg'=>__('successfully updated order status')] :
                ['status'=>false,'msg'=>'Don\'t have permissions to update order status']
            );
        }

        $this->viewData['pageTitle'] = __('Merchant Order');
        $this->viewData['lang'] = $this->systemLang;

        $order->merchant_branch;
        $order->orderitems;
        $this->viewData['order'] = $order;
        $this->viewData['transactions'] = $order->trans;
        $this->viewData['status'] = $order->statusable->reverse();
        $this->viewData['order'] = $order;

        return $this->view('merchant.order.show',$this->viewData);

    }

    public function edit(Order $order){
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Merchant Order'),
            'url'=> url('system/merchant/order')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Create Merchant Order'),
        ];

        $this->viewData['pageTitle'] = __('Create Merchant Order');

        $order->merchant;
        $order->merchant_branch;
        $order->orderitems;
        $order->trans;
        $order['transToShow'] = ['pending','paid'];


        $productCategories = ($order->merchant->merchant_product_categories()->pluck('name_'.$this->systemLang.' as name','id'))->toArray();
        array_unshift($productCategories,__('Select category'));
        $order['productCategories'] = $productCategories;
        $this->viewData['order'] = $order;
        $this->viewData['colName'] = 'name_'.$this->systemLang;


        $merchantID = request('merchant_id');
        if($merchantID){
            $merchantData = Merchant::findOrFail($merchantID);
            $this->viewData['merchantData'] = $merchantData;
        }

        return $this->view('merchant.order.create',$this->viewData);
    }

    public function update(Order $Order, Request $request){
        $RequestData = $request->only(['merchant_branch_id','users','product']);
        $merchant = $Order->merchant()->Active()->first();
        if(!$merchant)
            abort(404);



        Validator::make($RequestData, [
            'merchant_branch_id'        => 'required|integer',
            'users.*'                   => 'required|array:3',
            'users.*.id'                => 'required|integer',
            'users.*.amount'            => 'required|integer',
            'users.*.paytype'           => 'required|in:wallet,cash',
            'users.*.transaction'       => 'nullable|exists:transactions,id',
            'product.*.id'              => 'required|integer|in:'.implode(',',$merchant->merchant_products()->pluck('id')->toArray()),
        ])->validate();



        $GLOBALS['status'] = false;
        $lang = $this->systemLang;
        try {
            DB::transaction(function () use ($RequestData, $Order, $request, $merchant, $lang) {
                $Order->update(['total'=>'0']);
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
                    $productTotalPrice = $productPrice * $oneProduct['qty'];
                    $ExistOrderItem = $Order->orderitems()->where('merchant_product_id',$oneProduct['id'])->first();
                    if($ExistOrderItem) {
                        if (($ExistOrderItem->price != $productTotalPrice) || ($ExistOrderItem->qty != $oneProduct['qty'])) {
                            $ExistOrderItem->update([
                                'price'     =>  $productTotalPrice,
                                'qty'       =>  $oneProduct['qty'],
                            ]);
                        }
                        $OrderItem = $ExistOrderItem;
                    } else {
                        $oneorederitem = [
                            'order_id' => $Order->id,
                            'merchant_product_id' => $productData->id,
                            'qty' => $oneProduct['qty'],
                            'price' => $productPrice,
                        ];
                        $OrderItem = $Order->orderitems()->create($oneorederitem);
                    }
                    $Order->increment('total', $productPrice * $oneProduct['qty']);


                    //Delete all OrderItem Attributes
                    if($OrderItem->orderItemAttribute) {
                        foreach ($OrderItem->orderItemAttribute as $oneOIAttribute) {
                            $oneOIAttribute->delete();
                        }
                    }

                    //Insert new attributes
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


                //Check Order Transactions
                $transactions = $Order->trans;

                $RequestTransactionIds = recursiveFind($RequestData['users'],'transaction');
                foreach($transactions->whereNotIn('id',$RequestTransactionIds) as $ToDeleteTrans){
                    WalletData::changeTransactionStatus($ToDeleteTrans->id, 'reverse', Auth::user()->modelPath, Auth::id());
                }

                if (isset($RequestData['users'])) {
                    foreach ($RequestData['users'] as $user) {
                        $userWalletId = User::where('id', $user['id'])->first()->eCommerceWallet->id;
                        if(isset($user['transaction'])){
                            $oneTrans = $transactions->where('id',$user['transaction'])->first();
                            if (($oneTrans->amount != $user['amount']) || ($oneTrans->type != $user['paytype'])) {
                                switch ($oneTrans->type) {
                                    case 'pending':
                                    case 'paid':
                                        //move to reverse
                                        WalletData::changeTransactionStatus($oneTrans->id, 'reverse', Auth::user()->modelPath, Auth::id());
                                        WalletData::makeTransaction(
                                            $user['amount'],
                                            $user['paytype'],
                                            $userWalletId,
                                            $merchant->eCommerceWallet->id,
                                            'order',
                                            $Order->id,
                                            Auth::user()->modelPath,
                                            Auth::id(),
                                            'pending'
                                        );
                                    break;
                                    case 'reverse':
                                        //Do nothing to it
                                    break;
                                }
                            }
                        } else {
                            WalletData::makeTransaction(
                                $user['amount'],
                                $user['paytype'],
                                $userWalletId,
                                $merchant->eCommerceWallet->id,
                                'order',
                                $Order->id,
                                get_class(Auth::user()),
                                Auth::id(),
                                'pending'
                            );
                        }
                    }
                }

                $GLOBALS['status'] = true;
                $GLOBALS['orderId'] = $Order->id;
            });
        } catch (\PDOException $e){
            return redirect()
                ->route('merchant.order.edit',$GLOBALS['orderId'])
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t Edit Order'));
        }


        if($GLOBALS['status']) {
            return redirect()
                ->route('merchant.order.show',$GLOBALS['orderId'])
                ->with('status', 'success')
                ->with('msg', __('Order successfully edited'));
        } else {
            return redirect()
                ->route('merchant.order.edit',$Order->id)
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t update Order'));
        }
    }


    public function destroy(Order $order,Request $request){
        // Delete Data
        $order->delete();
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('Merchant Order has been deleted successfully')];
        }else{
            redirect()
                ->route('merchant.order.index')
                ->with('status','success')
                ->with('msg',__('This Merchant order has been deleted'));
        }
    }


    public function qrcode($id){

        $Order = Order::findOrfail($id);
        if($Order->qr_code)
            return ['QRCode'=>$Order->qr_code,'image'=>Base64PngQR($Order->qr_code,['350','350'])];

        do {
            $qrcode = UniqueId();
        } while (Order::where('qr_code','=',$qrcode)->where('id','!=',$id)->first());

        $Order->update(['qr_code'=>$qrcode]);

        return ['QRCode'=>$qrcode,'image'=>Base64PngQR($qrcode,['350','350'])];
    }
}