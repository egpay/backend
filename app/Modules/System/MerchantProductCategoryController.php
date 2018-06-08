<?php

namespace App\Modules\System;

use App\Models\Merchant;
use App\Models\MerchantProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;
use App\Http\Requests\MerchantProductCategoryFormRequest;
use Auth;

class MerchantProductCategoryController extends SystemController
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
    }


    public function index(Request $request){


        if($request->isDataTable){
            $eloquentData = MerchantProductCategory::viewData($this->systemLang);

            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }


            whereBetween($eloquentData,'merchant_product_categories.created_at',$request->created_at1,$request->created_at2);

            if($request->id){
                $eloquentData->where('merchant_product_categories.id','=',$request->id);
            }

            if($request->name){
                orWhereByLang($eloquentData,'merchant_product_categories.name',$request->name);
            }

            if($request->description){
                orWhereByLang($eloquentData,'merchant_product_categories.description',$request->description);
            }

            whereBetween($eloquentData,'merchant_product_categories.approved_at',$request->approved_at1,$request->approved_at2);



            if($request->approved_by_staff_id){
                $eloquentData->where('merchant_product_categories.approved_by_staff_id',$request->approved_by_staff_id);
            }

            if($request->is_approved == 'yes'){
                $eloquentData->whereNotNull('merchant_product_categories.approved_at');
            }elseif($request->is_approved == 'no'){
                $eloquentData->whereNull('merchant_product_categories.approved_at');
            }

            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('merchant_logo',function($data){
                    if(!$data->merchant_logo) return '--';
                    return '<img src="'.asset('storage/'.imageResize($data->merchant_logo,70,70)).'" />';
                })
                ->addColumn('merchant_name','{{$merchant_name}} ( {{$merchant_category_name}} )')

                ->addColumn('icon',function($data){
                    if(!$data->icon) return '--';
                    return '<img src="'.asset('storage/'.imageResize($data->icon,70,70)).'" />';
                })

                ->addColumn('name', function($data){
                    $return = '<table class="table table-striped">
                                <tbody>';
                    foreach (listLangCodes() as $key => $value){
                        $return.= '<tr>
                                    <td>'.$value.'</td>
                                    <td>'.$data->{'name_'.$key}.'</td>
                                  </tr>';
                    }

                    $return.= '</tbody>
                              </table>';
                    return $return;
                })
                ->addColumn('status',function($data){
                    if(is_null($data->approved_at)){
                        return '<b style="color: green;">Approved</b>';
                    }else{
                        return '<b style="color: red;">Disapproved</b>';
                    }
                })
                ->addColumn('action',function($data){
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('merchant.product-category.show',$data->id)."\">".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a href=\"".route('merchant.product-category.edit',$data->id)."\">".__('Edit')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('merchant.product-category.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
                              </ul>
                            </div>";
                })
                ->make(true);
        }else{

            // View Data
            $this->viewData['tableColumns'] = ['ID','Merchant Logo','Merchant','Icon','Name','Status','Action'];
            $this->viewData['breadcrumb'][] = [
                'text'=> __('Merchant Plans'),
            ];


            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Merchant Product Categories');
            }else{
                $this->viewData['pageTitle'] = __('Merchant Product Categories');
            }


            return $this->view('merchant.product-category.index',$this->viewData);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Merchant Product Categories'),
            'url'=> url('system/merchant/product-category')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Create MPC'),
        ];

        $this->viewData['pageTitle'] = __('Create MPC');

        // Add Branch To Merchant With GET ID
        $merchantID = request('merchant_id');
        if($merchantID){
            $merchantData = Merchant::findOrFail($merchantID);
            $this->viewData['merchantData'] = $merchantData;
        }

        return $this->view('merchant.product-category.create',$this->viewData);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MerchantProductCategoryFormRequest $request)
    {
        $theRequest = $request->all();

        //TODO logged in staffid
        $theRequest['approved_by_staff_id'] = Auth::id();


        if($request->file('icon')) {
            $theRequest['icon'] = $request->icon->store('merchant-category/'.date('y').'/'.date('m'));
        }

        if(MerchantProductCategory::create($theRequest))
            return redirect()->route('merchant.product-category.create')
                ->with('status','success')
                ->with('msg',__('Data has been added successfully'));
        else{
            return redirect()->route('merchant.product-category.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Merchant product Category'));
        }

    }


    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(MerchantProductCategory $product_category){

        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Merchant Product Categories'),
            'url'=> url('system/merchant/product-category')
        ];
        $this->viewData['breadcrumb'][] = [
            'text'=> $product_category->{'name_'.$this->systemLang},
        ];

        // -- Products
        $this->viewData['products'] = [];

        $merchantProducts = $product_category->products;
        if($merchantProducts){
            foreach ($merchantProducts as $key => $value){
                $this->viewData['products'][] = $value;
            }
        }
        // -- Products

        $this->viewData['pageTitle'] = __('Merchant Product Category');
        $this->viewData['result'] = $product_category;
        return $this->view('merchant.product-category.show',$this->viewData);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(MerchantProductCategory $product_category)
    {
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Merchant Product Categories'),
            'url'=> url('system/merchant/product-category')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Edit MPC'),
        ];
        $this->viewData['pageTitle'] = __('Edit MPC');

        $this->viewData['result'] = $product_category;

        return $this->view('merchant.product-category.create',$this->viewData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(MerchantProductCategoryFormRequest $request, MerchantProductCategory $product_category)
    {
        $theRequest = $request->all();

        if($request->file('icon')) {
            $theRequest['icon'] = $request->icon->store('merchant-category/'.date('y').'/'.date('m'));
        }

        if($product_category->update($theRequest)) {
            return redirect()->route('merchant.product-category.edit',$product_category->id)
                ->with('status','success')
                ->with('msg',__('Successfully Edit Merchant product Category'));
        }else{
            return redirect()->route('merchant.product-category.edit')
                ->with('status','success')
                ->with('msg',__('Sorry Couldn\'t Edit Merchant product Category'));;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(MerchantProductCategory $product_category)
    {
        // Delete Data
        $product_category->delete();
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('Merchant Product Category has been deleted successfully')];
        }else{
            redirect()
                ->route('merchant.product-category.index')
                ->with('status','success')
                ->with('msg',__('This Merchant Product Category has been deleted'));
        }
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
