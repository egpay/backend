<?php

namespace App\Modules\System;

use App\Http\Requests\MerchantProductCategoryFormRequest;
use App\Models\MerchantProductCategory;
use Yajra\Datatables\Facades\Datatables;
use App\Http\Requests\MerchantProductFormRequest;
use Illuminate\Support\Facades\Storage;
use App\Models\MerchantProduct;
use Illuminate\Http\Request;
use App\Models\AreaType;
use App\Models\MerchantCategory;
use Carbon;
use App\Models\Merchant;
use App\Models\Upload;

class MerchantProductController extends SystemController
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

            $eloquentData = MerchantProduct::viewData($this->systemLang);
            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }


            // Product Filter

            whereBetween($eloquentData,'merchant_products.created_at',$request->created_at1,$request->created_at2);

            if($request->id){
                $eloquentData->where('merchant_products.id', '=',$request->id);
            }


            if($request->merchant_id){
                $eloquentData->where('merchant_products.merchant_id',$request->merchant_id);

                if($request->merchant_product_category_id){
                    $eloquentData->where('merchant_products.merchant_product_category_id',$request->merchant_product_category_id);
                }

                if($request->created_by_merchant_staff_id){
                    $eloquentData->where('merchant_products.created_by_merchant_staff_id',$request->created_by_merchant_staff_id);
                }

            }



            if($request->name){
                orWhereByLang($eloquentData,'merchant_products.name',$request->name);
            }

            if($request->description){
                orWhereByLang($eloquentData,'merchant_products.description',$request->description);
            }

            whereBetween($eloquentData,'merchant_products.price',$request->price1,$request->price2);

            if($request->approved_by_staff_id){
                $eloquentData->where('merchant_products.approved_by_staff_id',$request->approved_by_staff_id);
            }


            if($request->is_approved == 'yes'){
                $eloquentData->whereNotNull('merchant_products.approved_at');
            }elseif($request->is_approved == 'no'){
                $eloquentData->whereNull('merchant_products.approved_at');
            }

            whereBetween($eloquentData,'merchant_products.approved_at',$request->approved_at1,$request->approved_at2);

            // Branch Filter
            if(is_array($request->area_id) && !empty($request->area_id) && !(count($request->area_id) == 1 && $request->area_id[0] == '0') ){
                $eloquentData->where('merchant_branches.area_id','IN',\App\Libs\AreasData::getAreasDown($request->area_id));
            }

            if($request->merchant_category_id){
                $eloquentData->where('merchants.merchant_category_id', '=',$request->merchant_category_id);
            }


            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('icon',function($data){
                    if(!$data->icon) return '--';
                    return '<img src="'.asset('storage/'.imageResize($data->icon,70,70)).'" />';
                })
                ->addColumn('name', function($data){
                    return $data->name;
                })
                ->addColumn('price','{{number_format($price)}}')
                ->addColumn('merchant_name',function($data){
                    return '<a target="_blank" href="'.route('merchant.merchant.show',$data->merchant_id).'">'.$data->merchant_name.' ('.$data->merchant_category_name.') '.'</a>';
                })
                ->addColumn('created_by_merchant_staff_id',function($data){
                    $created_by_merchant_staff_name = trim($data->created_by_merchant_staff_name);
                    if(!$created_by_merchant_staff_name){
                        return 'NULL';
                    }else{
                        return $created_by_merchant_staff_name;
                    }
                })

                ->addColumn('status',function($data){

                    if($data->approved_at){
                        return '<b style="color: green;">'.$data->approved_at.' By ('.$data->approved_by_staff_name.')</b>';
                    }else{
                        return '<b style="color: red;"> In-Active </b>';
                    }
                })

                ->addColumn('action',function($data){

                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('merchant.product.show',$data->id)."\">".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a href=\"".route('merchant.product.edit',$data->id)."\">".__('Edit')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('merchant.product.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
                              </ul>
                            </div>";
                })
                ->make(true);
        }else{

            // View Data
            $this->viewData['tableColumns'] = [__('ID'),__('Icon'),__('Name'),__('Price'),__('Merchant'),__('Created By'),__('Status'),__('Action')];

            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Merchant Products');
            }else{
                $this->viewData['pageTitle'] = __('Merchant Products');
            }

            $this->viewData['breadcrumb'][] = [
                'text'=> __('Merchant Products'),
            ];
            // Filter
            $this->viewData['areaData'] = AreaType::getFirstArea($this->systemLang);
            $MerchantCategory = MerchantCategory::get(['id','name_'.$this->systemLang.' as name']);
            if($MerchantCategory->isNotEmpty()){
                $this->viewData['merchantCategories'] = array_merge(['Select Category'],array_column($MerchantCategory->toArray(),'name','id'));
            }else{
                $this->viweData['merchantCategories'] = [__('Select Category')];
            }
            // Filter

            return $this->view('merchant.product.index',$this->viewData);
        }
    }


    public function create()
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Merchant Products'),
            'url'=> url('system/merchant/product')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Create Merchant Products'),
        ];

        // Add Branch To Merchant With GET ID
        $merchantID = request('merchant_id');
        if($merchantID){
            $merchantData = Merchant::findOrFail($merchantID);
            $this->viewData['merchantData'] = $merchantData;
        }

        // -- Category
        $this->viewData['MerchantProductCategory'] = ['Select Product Category'];
        $old_merchant_id = old('merchant_id');
        if($old_merchant_id){
            $MerchantProductCategory = array_column(MerchantProductCategory::where('merchant_id',$old_merchant_id)->get()->toArray(),'name_'.$this->systemLang,'id');
            $this->viewData['MerchantProductCategory'] = array_merge($this->viewData['MerchantProductCategory'],$MerchantProductCategory);

            // Merchant
            $this->viewData['current_merchant'] = Merchant::find($old_merchant_id);
            // Merchant
        }
        // -- Category


        $this->viewData['pageTitle'] = __('Create Merchant Products');
        return $this->view('merchant.product.create',$this->viewData);
    }


    public function store(MerchantProductFormRequest $request)
    {
        $theRequest = $request->all();

        if($request->status == 'active'){
          //  $theRequest['approved_by_staff_id'] = Auth::id();
            $theRequest['approved_at'] = Carbon::now();
        }


        if($insertData = MerchantProduct::create($theRequest)){

            // Start Upload Files
            $uploads = new \Illuminate\Support\Collection();
            $files = $request->file;
            if($files){
                foreach ($files as $key => $value){
                    $uploads->push(new Upload([
                        'path' => $value->store('contract/'.$insertData->merchant_id),
                        'title' => @$request->title[$key],
                        'model_id' => $insertData->id
                    ]));
                }
                $insertData->upload()->saveMany($uploads);
            }
            // End Upload Files

            return redirect()
                ->route('merchant.product.create')
                ->with('status','success')
                ->with('msg',__('Data has been added successfully'));
        }else{
            return redirect()
                ->route('merchant.product.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Merchant Products'));
        }
    }


    public function show(MerchantProduct $product)
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Merchant Product'),
            'url'=> url('system/merchant/product')
        ];
        $this->viewData['breadcrumb'][] = [
            'text'=> $product->{'name_'.$this->systemLang},
        ];
        $this->viewData['pageTitle'] = __('Merchant Product');

        $this->viewData['result'] = $product;

        return $this->view('merchant.product.show',$this->viewData);
    }


    public function edit(MerchantProduct $product)
    {
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Merchant Product'),
            'url'=> url('system/merchant/product')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Edit Merchant Product'),
        ];
        $this->viewData['pageTitle'] = __('Edit Merchant Product');

        $this->viewData['result'] = $product;

        $this->viewData['MerchantProductCategory'] = ['Select Product Category'];
        $MerchantProductCategory = array_column(MerchantProductCategory::where('merchant_id',$product['merchant_id'])->get()->toArray(),'name_'.$this->systemLang,'id');
        $this->viewData['MerchantProductCategory'] = array_merge($this->viewData['MerchantProductCategory'],$MerchantProductCategory);

        return $this->view('merchant.product.create',$this->viewData);
    }


    public function update(MerchantProductFormRequest $request,MerchantProduct $product)
    {
        $theRequest = $request->all();

        if($product->update($theRequest)) {
            // Start Upload Files
            $uploads = new \Illuminate\Support\Collection();
            $files = $request->file;
            if($files){
                foreach ($files as $key => $value){
                    $uploads->push(new Upload([
                        'path' => $value->store('contract/'.$product->merchant_id),
                        'title' => @$request->title[$key],
                        'model_id' => $product->id
                    ]));
                }
                $product->upload()->saveMany($uploads);
            }
            // End Upload Files

            return redirect()
                ->route('merchant.product.edit',$product->id)
                ->with('status','success')
                ->with('msg',__('Successfully edited Merchant Product'));
        }else{
            return redirect()
                ->route('merchant.product.edit')
                ->with('status','success')
                ->with('msg',__('Sorry Couldn\'t Edit Merchant Product'));;
        }
    }

    public function destroy(MerchantProduct $product,Request $request){
        // Delete Data
        $product->delete();
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('Product has been deleted successfully')];
        }else{
            redirect()
                ->route('merchant.product.index')
                ->with('status','success')
                ->with('msg',__('This product has been deleted'));
        }
    }

}