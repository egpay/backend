<?php

namespace App\Modules\Merchant;


use App\Models\Attribute;
use App\Models\MerchantBranch;
use App\Models\MerchantCategory;
use App\Models\MerchantProductCategory;
use App\Models\MerchantProduct;
use App\Models\ProductAttribute;
use App\Models\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Validator;
use Auth;

class MerchantProductController extends MerchantController
{

    protected $viewData = [];

    public function index(Request $request){
        $merchant = $request->user()->merchant();
        $merchantGroups = $merchant->merchant_product_catgories()->pluck('id')->toArray();
        if($request->isDataTable){
            $eloquentData = MerchantProduct::viewData($this->systemLang);

            $eloquentData->wherein('merchant_products.merchant_product_category_id',$merchantGroups);

            whereBetween($eloquentData,'merchant_products.created_at',$request->created_at1,$request->created_at2);

            if($request->id){
                $eloquentData->where('merchant_products.id', '=',$request->id);
            }


            if($request->merchant_id){

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
                ->addColumn('name_en', function($data){
                    return $data->name_en;
                })
                ->addColumn('name_ar', function($data){
                    return $data->name_ar;
                })
                ->addColumn('price','{{number_format($price)}}')
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
                        return '<b style="color: red;"> -- </b>';
                    }
                })

                ->addColumn('action',function($data){
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('panel.merchant.product.show',$data->id)."\">".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a href=\"".route('panel.merchant.product.edit',$data->id)."\">".__('Edit')."</a></li>
                                <!--<li class=\"dropdown-item\"><a onclick=\"AjaxRequest({'confirm':'{{__(\'Approve this product\')}}','success':'{{__(\'Success\')}}','error':'{{__(\'Error\')}}'},'".route('panel.merchant.product.approve',$data->id)."')\" href=\"javascript:void(0)\">".__('Approve')."</a></li>-->
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('panel.merchant.product.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
                              </ul>
                            </div>";
                })
                ->make(true);
        }else{

            // View Data
            $this->viewData['tableColumns'] = [__('ID'),__('Name (EN)'),__('Name (AR)'),__('Price'),__('Created by'),__('Status'),__('Action')];


                $this->viewData['pageTitle'] = __('Merchant Product');

            return $this->view('product.index',$this->viewData);
        }
    }

    public function create(Request $request)
    {
        $merchant = $request->user()->merchant();

        if($request->getAttribute){
            return $this->getAtteribute($request);
        }

        $this->viewData['pageTitle'] = __('Create product');
        $this->viewData['product_category'] =array_column(MerchantProductCategory::where('merchant_id',$merchant->id)->get(['id',\DB::raw("CONCAT(`name_en`,' - ',`name_ar`) AS name")])->toArray(),'name','id');
        $lang = $this->systemLang;
        $this->viewData['categories'] = $merchant->attributeCategories($this->systemLang)->with(['attributes'=>function($query)use($lang){
            $query->select(['id','attribute_category_id','type','name_'.$lang.' as name','description_'.$lang.' as description','multi_lang']);
        }])->get();
        return $this->view('product.create',$this->viewData);
    }


    public function store(Request $request)
    {
        $merchant = $request->user()->merchant();
        $RequestData = $request->only(['name_en','description_en','name_ar','description_ar','status','merchant_product_category_id','price','title','image','attribute']);

        /*
        if ($RequestData['attribute']) {
            $theRequest = $RequestData;
            $merchantPro = MerchantProduct::where('id','=','1')->first();
            //dd($theRequest['attribute']);
            dd($theRequest['attribute']);
            dd(AddProductAttributes($theRequest['attribute'],$merchantPro));
        }
        */

        Validator::make($RequestData, [
            'name_en'                               => 'required',
            'description_en'                        => 'required',
            'name_ar'                               => 'required',
            'description_ar'                        => 'required',
            'merchant_product_category_id'          => 'numeric|exists:merchant_product_categories,id',
            'price'                                 => 'numeric',
            'status'                                => 'in:active,in-active',
            'image.*'                               => 'required|image',
            /*
             * Attributes
             */
            //'attribute'                             => 'nullable'
        ])->validate();


        $theRequest = $RequestData;
        $theRequest['created_by_merchant_staff_id'] = Auth::id();
        $theRequest['merchant_product_category_id'] = (int) $theRequest['merchant_product_category_id'];
        $theRequest['merchant_id'] = (int) $merchant->id;



        $GLOBALS['status'] = false;
        DB::transaction(function () use ($theRequest,$request) {
            $merchantProduct = MerchantProduct::create($theRequest);
            // Product images
            if(request('image') !== null) {
                $uploadedImages = UploadRequestFiles($theRequest['image'],$theRequest['title'],'merchants/'.$merchantProduct->merchant_id.'/products/'.$merchantProduct->id,$merchantProduct);
            }

            //Product Attributes
            if(isset($theRequest['attribute'])) {
                $attributes = AddProductAttributes($theRequest['attribute'], $merchantProduct);
            }

            $GLOBALS['status'] = true;
        });


        if($GLOBALS['status'])
            return redirect()->route('panel.merchant.product.index')
                ->with('status','success')
                ->with('msg',__('Successfuly added product'));
        else {
            return redirect()->route('panel.merchant.product.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add product'));
        }
    }

    public function show(MerchantProduct $product){
        $this->viewData['pageTitle'] = __('Product');
        $this->viewData['result'] = $product;

        $this->viewData['attribute'] = ProductAttribute::viewData($this->systemLang,[])->where('product_id','=',$product->id)->get();
        return $this->view('product.view',$this->viewData);
    }

    public function edit(Request $request, MerchantProduct $product){
        $merchant = $request->user()->merchant();
        if($merchant->id != $product->merchant->id)
            return redirect()->route('panel.merchant.home');

        if($request->getAttribute){
            return $this->getAtteribute($request);
        }

        $this->viewData['pageTitle'] = __('Edit Product');

        $this->viewData['result'] = $product;
        $this->viewData['product_category'] =array_column(MerchantProductCategory::where('merchant_id',$merchant->id)->get(['id',\DB::raw("CONCAT(`name_en`,' - ',`name_ar`) AS name")])->toArray(),'name','id');
        $lang = $this->systemLang;
        $this->viewData['categories'] = $merchant->attributeCategories($this->systemLang)->with(['attributes'=>function($query)use($lang){
            $query->select(['id','attribute_category_id','type','name_'.$lang.' as name','description_'.$lang.' as description','multi_lang']);
        }])->get();
        $this->viewData['oldattribute'] = ProductAttribute::viewData($this->systemLang,[])->where('product_id','=',$product->id)->get();
        $this->viewData['oldattributevalues'] = Attribute::whereIn('id',$this->viewData['oldattribute']->pluck('attribute_id'))
            ->with(['attributeValue'=>function($sqlQuery)use($lang){
                $sqlQuery->select(['id','attribute_id','text_'.$lang.' as text','is_default']);
            }])->get();


        //dd($this->viewData['oldattributevalues']->where('id',2)->first());
        return $this->view('product.create',$this->viewData);
    }


    public function update(Request $request,MerchantProduct $product)
    {
        $merchant = $request->user()->merchant();
        if($merchant->id != $product->merchant->id)
            return redirect()->route('panel.merchant.home');

        $RequestData = $request->only(['name_en','description_en','name_ar','description_ar','status','merchant_product_category_id','price','image','oldtitle','oldimage','attribute']);

        Validator::make($RequestData, [
            'name_en'                               => 'required',
            'description_en'                        => 'required',
            'name_ar'                               => 'required',
            'description_ar'                        => 'required',
            'merchant_product_category_id'          => 'numeric|exists:merchant_product_categories,id',
            'price'                                 => 'numeric',
            'status'                                => 'in:active,in-active',
            'image.*'                               => (($request->file('image'))?'image':'required'),
        ])->validate();


        $theRequest = $RequestData;

        $theRequest['merchant_product_category_id'] = (int) $theRequest['merchant_product_category_id'];




        $GLOBALS['status'] = false;
        DB::transaction(function () use ($theRequest,$request,$product) {


            $product->update($theRequest);

            foreach($product->uploadmodel as $oneupload){
                if(!in_array($oneupload->path,request('oldimage'))){
                    if($oneupload->delete())
                        Storage::delete($oneupload->path);
                }
            }

            // Product images
            if(request('image') !== null) {
                $uploadedImages = UploadRequestFiles($theRequest['image'],$theRequest['title'],'merchants/'.$product->merchant_id.'/products/'.$product->id,$product);
            }

            //Product Attributes
            if(isset($theRequest['attribute'])) {
                $attributes = UpdateProductAttributes($theRequest['attribute'], $product);
            }

            $GLOBALS['status'] = true;
        });

        if($product->update($theRequest)) {
            return redirect()->route('panel.merchant.product.index')
                ->with('status','success')
                ->with('msg',__('Successfully edited Product'));
        }else{
            return redirect()->route('panel.merchant.product.edit')
                ->with('status','success')
                ->with('msg',__('Sorry Couldn\'t Edit Product'));
        }
    }



    public function destroy(MerchantProduct $product,Request $request){
        $merchant = $request->user()->merchant();
        if($merchant->id != $product->merchant->id) {
            return redirect()->route('panel.merchant.home');
        }
        // Delete Data
        $product->delete();
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('Product has been successfully deleted')];
        }else{
            redirect()
                ->route('panel.merchant.product.index')
                ->with('status','success')
                ->with('msg',__('Product has been deleted'));
        }
    }


    public function approve(MerchantProduct $product,Request $request){
        $merchant = $request->user()->merchant();
        if($merchant->id != $product->merchant->id) {
            return redirect()->route('panel.merchant.home');
        }
        // Delete Data
        $product->update([
            'approved_at'                   =>  Carbon::now(),
            'approved_by_staff_id'          =>  Auth::id(),
        ]);
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('Product has been successfully Approved')];
        }else{
            redirect()
                ->route('panel.merchant.product.index')
                ->with('status','success')
                ->with('msg',__('Product has been Approved'));
        }
    }

    private function getAtteribute($request){
            $validator = Validator::make($request->only(['id']), ['id' => 'required|exists:attributes,id']);
            if($validator->errors()->any()){
                return '';
            } else {
                $lang = $this->systemLang;
                return Attribute::select(['id','attribute_category_id','type','name_'.$lang.' as name','description_'.$lang.' as description','multi_lang'])->where('id','=',$request->id)->with(['attributeValue'=>function($query)use($lang){
                    $query->select(['id','attribute_id','text_'.$lang.' as text','is_default']);
                }])->first();
            }
    }

}