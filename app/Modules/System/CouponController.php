<?php

namespace App\Modules\System;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;
use Auth;

class CouponController extends SystemController
{

    public function __construct(){
        parent::__construct();
        $this->viewData['breadcrumb'] = [
            [
                'text'=> __('Home'),
                'url'=> url('system'),
            ],
            [
                'text'=> __('Coupons'),
                'url'=> url('system/coupon')
            ]
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        if($request->isDataTable){

            $eloquentData = Coupon::viewData($this->systemLang);

            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }


            whereBetween($eloquentData,'coupons.created_at',$request->created_at1,$request->created_at2);

            if($request->type){
                $eloquentData->where('coupons.type', '=',$request->type);
            }


            if($request->id){
                $eloquentData->where('coupons.id', '=',$request->id);
            }

            if($request->reward_start) {
                $eloquentData->where('coupons.reward','>=',$request->reward_start);
            }

            if($request->reward_end) {
                $eloquentData->where('coupons.reward','<=',$request->reward_end);
            }

            if($request->description){
                $name = $request->description;
                $eloquentData->where(function($query) use($name) {
                    $query->where(DB::raw("CONCAT(coupons.description_ar,' ',coupons.description_ar)"),'LIKE',"%$name%")
                        ->orWhere(DB::raw("CONCAT(coupons.description_en,' ',coupons.description_en)"),'LIKE',"%$name%");
                });
            }

            if($request->user_id){
                $eloquentData->whereRaw("find_in_set($request->user_id,users)");
            }

            /*
            if($request->item_id){
                $eloquentData->whereRaw("find_in_set($request->product_id,items)");
            }
            */

            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('merchant', function($data){
                    return link_to_route('merchant.merchant.show',$data->merchant_name,['id'=>$data->merchant_id]);
                })
                ->addColumn('reward', function($data){
                    if($data->reward_type=='fixed')
                        return $data->reward.' '.__('LE');
                    else
                        return '% '.$data->reward;
                })
                ->addColumn('quantity', function($data){
                    return (($data->quantity===0)?__('Unlimited'):$data->quantity);
                })
                ->addColumn('users', function($data){
                    if($counts = count(array_filter($data->users))) {
                        $users = array_map(function($userID){return link_to_route('system.users.show','User:#'.$userID,['id'=>$userID]);},$data->users);
                        $limit = 5;
                        if($counts>$limit)
                            return implode('<br>', array_chunk($users,$limit)[0]);
                        else
                            return implode('<br>', $users);
                    } else
                        return __('All users');
                })
                ->addColumn('items', function($data){
                    if($counts = count(array_filter($data->items))) {
                        if($data->type == 'product')
                            $items = array_map(function($itemID){return link_to_route('merchant.product.show','Product:#'.$itemID,['id'=>$itemID]);},$data->items);
                        elseif($data->type == 'service')
                            $items = array_map(function($itemID){return link_to_route('payment.services.show','Payment:#'.$itemID,['id'=>$itemID]);},$data->items);

                        $limit = 5;
                        if($counts>$limit)
                            return implode('<br>', array_chunk($items,$limit)[0]);
                        else
                            return implode('<br>', $items);
                    } else
                        return __('All merchant items');
                })
                ->addColumn('action',function($data){
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('merchant.coupon.show',$data->id)."\">".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a href=\"".route('merchant.coupon.edit',$data->id)."\">".__('Edit')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('merchant.coupon.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
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
            $this->viewData['tableColumns'] = ['ID',__('Merchant'),__('Reward'),__('quantity'),__('Users'),__('Items'),__('Action')];


            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Merchant Coupons');
            }else{
                $this->viewData['pageTitle'] = __('Merchant Coupons');
            }

            $this->viewData['breadcrumb'][] = [
                'text'=> __('Merchant Coupons'),
            ];


            return $this->view('merchant.coupon.index',$this->viewData);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Merchant Coupon'),
            'url'=> url('system/merchant/coupon')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Create Merchant Coupon'),
        ];

        $this->viewData['pageTitle'] = __('Create Merchant Coupon');


        $merchantID = request('merchant_id');
        if($merchantID){
            $merchantData = Merchant::where('id',$merchantID)
                ->whereIn('staff_id',Auth::user()->managed_staff_ids())
                ->firstOrFail();

            $this->viewData['merchantData'] = $merchantData;
        }


        return $this->view('merchant.coupon.create',$this->viewData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $RequestedData = $request->only(['type','merchant_id','code','description_ar','description_en','reward','reward_type','quantity','users','items','start_date','end_date']);
        $rules = [
            'type'              =>      'required|in:product,service',
            'merchant_id'       =>      'required|exists:merchants,id',
            'code'              =>      'required|unique:coupons,code',
            'description_ar'    =>      'required',
            'description_en'    =>      'required',
            'reward'            =>      'required',
            'reward_type'       =>      'required|in:fixed,percentage',
            'quantity'          =>      'required|integer',
            'users.*'           =>      'nullable|exists:users,id',
        ];
        if($RequestedData['type'] == 'product')
            $rules['items.*'] = 'nullable|exists:merchant_products,id';
        elseif($RequestedData['type'] == 'service')
            $rules['items.*'] = 'nullable|exists:payment_services,id';
        //Validation
        $this->validate($request,$rules);

        $RequestedData['staff_id'] = Auth::id();

        if(Coupon::create($RequestedData)){
            return redirect()
                ->route('merchant.coupon.create')
                ->with('status', 'success')
                ->with('msg', __('Coupon has been added successfully'));
        } else {
            return redirect()
                ->route('merchant.coupon.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add coupon'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(Coupon $coupon)
    {

        // Supervisor
        if(!staffCan('show-tree-users-data',Auth::id()) && !in_array($coupon->merchant->staff_id,Auth::user()->managed_staff_ids())){
            abort(404);
        }

        $this->viewData['breadcrumb'] = [
            [
                'text'=> __('Home'),
                'url'=> url('system'),
            ],
            [
                'text'=> __('Merchants'),
                'url'=> route('merchant.coupon.index'),
            ],
            [
                'text'=> $coupon->merchant->{'name_'.$this->systemLang},
                'url'=> route('merchant.merchant.show',$coupon->merchant->id),
            ],
            [
                'text'=>  $coupon->id,
            ]
        ];


        $coupon->objUsers = $coupon->getUsers()->get();
        $coupon->objItems = $coupon->getItems()->get();
        $this->viewData['pageTitle'] = $coupon->reward;
        $this->viewData['result'] = $coupon;
        $this->viewData['lang'] = $this->systemLang;

        return $this->view('merchant.coupon.show',$this->viewData);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(Coupon $coupon){

        // Supervisor
        if(!staffCan('show-tree-users-data',Auth::id()) && !in_array($coupon->merchant->staff_id,Auth::user()->managed_staff_ids())){
            abort(404);
        }

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Merchant Coupon'),
            'url'=> url('system/merchant/coupon')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Edit Merchant Coupon'),
        ];
        $this->viewData['pageTitle'] = __('Edit Merchant Coupon');

        $coupon->objUsers = $coupon->getUsers()->get();
        $coupon->objItems = $coupon->getItems()->get();

        $this->viewData['result'] = $coupon;
        $this->viewData['lang'] = $this->systemLang;

        return $this->view('merchant.coupon.create',$this->viewData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Coupon $coupon)
    {
        $RequestedData = $request->only(['merchant_id','code','description_ar','description_en','reward','reward_type','quantity','users','items','start_date','end_date']);
        $this->validate($request,[
            'merchant_id'       =>      'required|exists:merchants,id',
            'code'              =>      'required|unique:coupons,code,'.$coupon->id,
            'description_ar'    =>      'required',
            'description_en'    =>      'required',
            'reward'            =>      'required',
            'reward_type'       =>      'required|in:fixed,percentage',
            'quantity'          =>      'required|integer',
            'users.*'           =>      'nullable|exists:users,id',
            'items.*'           =>      'nullable|exists:merchant_products,id',
        ]);

        $RequestedData['type'] = 'product';
        $RequestedData['staff_id'] = Auth::id();
        // Supervisor
        if(!staffCan('show-tree-users-data',Auth::id()) && !in_array($coupon->merchant->staff_id,Auth::user()->managed_staff_ids())){
            abort(404);
        }

        if($coupon->update($RequestedData)) {
            return redirect()->route('merchant.coupon.edit',$coupon->id)
                ->with('status','success')
                ->with('msg',__('Successfully edited Merchant Coupon'));
        }else{
            return redirect()->route('merchant.coupon.edit')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t Edit Merchant Coupon'));
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Coupon $coupon,Request $request){

        // Supervisor
        if(!staffCan('show-tree-users-data',Auth::id()) && !in_array($coupon->merchant->staff_id,Auth::user()->managed_staff_ids())){
            abort(404);
        }
        // Delete Data
        $coupon->delete();
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('Coupon has been deleted successfully')];
        }else{
            redirect()
                ->route('merchant.coupon.index')
                ->with('status','success')
                ->with('msg',__('This coupon has been deleted'));
        }
    }


}
