<?php

namespace App\Modules\System;

use App\Models\ProductAttributeCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;
use Auth;

class MerchantProductAttributeCategoryController extends SystemController
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
            $eloquentData = ProductAttributeCategory::select([
                'attribute_categories.id',
                'attribute_categories.name_ar',
                'attribute_categories.name_en',
                'attribute_categories.description_ar',
                'attribute_categories.description_en',
                DB::raw("(SELECT COUNT(*) FROM `attributes` WHERE attribute_category_id = `attribute_categories`.`id`) as `count`")
            ]);

            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }

            whereBetween($eloquentData,'attribute_categories.created_at',$request->created_at1,$request->created_at2);

            if($request->id){
                $eloquentData->where('attribute_categories.id','=',$request->id);
            }

            if($request->name){
                orWhereByLang($eloquentData,'attribute_categories.name',$request->name);
            }

            if($request->description){
                orWhereByLang($eloquentData,'attribute_categories.description',$request->description);
            }

            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')

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
                ->addColumn('description', function($data){
                    $return = '<table class="table table-striped">
                                <tbody>';
                    foreach (listLangCodes() as $key => $value){
                        $return.= '<tr>
                                    <td>'.$value.'</td>
                                    <td>'.$data->{'description_'.$key}.'</td>
                                  </tr>';
                    }
                    $return.= '</tbody>
                              </table>';
                    return $return;
                })
                ->addColumn('count','{{$count}}')

                ->addColumn('action',function($data){
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('merchant.product-attributes-category.show',$data->id)."\">".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a href=\"".route('merchant.product-attributes-category.edit',$data->id)."\">".__('Edit')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('merchant.product-attributes-category.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
                              </ul>
                            </div>";
                })
                ->make(true);
        }else{
            // View Data
            $this->viewData['tableColumns'] = ['ID','Category Name','Category Description','Attributes Count','Action'];
            $this->viewData['breadcrumb'][] = [
                'text'=> __('Merchant Product Attribute Category'),
            ];

            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Merchant Product Attribute Categories');
            }else{
                $this->viewData['pageTitle'] = __('Merchant Product Attribute Categories');
            }

            return $this->view('merchant.product-attribute-category.index',$this->viewData);
        }
    }


    public function create(Request $request)
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Product attr categories'),
            'url'=> url('system/merchant/product-attributes-category')
        ];
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Pro attr category'),
        ];
        $this->viewData['pageTitle'] = __('Create Product attribute category');
        return $this->view('merchant.product-attribute-category.create',$this->viewData);
    }


    public function store(Request $request){
        $theRequest = $request->only('name_ar','name_en','description_ar','description_en');

        $this->validate($request,[
            'name_ar'               =>      'required',
            'name_en'               =>      'required',
            'description_ar'        =>      'required',
            'description_en'        =>      'required',
        ]);

        if(ProductAttributeCategory::create($theRequest)) {
            return redirect()->route('merchant.product-attributes-category.index')
                ->with('status', 'success')
                ->with('msg', __('Data has been added successfully'));
        } else {
            return redirect()->route('merchant.product-attributes-category.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Merchant product Category'));
        }
    }



    public function show(Request $request, ProductAttributeCategory $product_attributes_category){
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Merchant Product attributes Categories'),
            'url'=> url('system/merchant/product-attributes-category')
        ];
        $this->viewData['breadcrumb'][] = [
            'text'=> $product_attributes_category->{'name_'.$this->systemLang},
        ];

        $this->viewData['pageTitle'] = __('Merchant Product Category');
        $this->viewData['result'] = $product_attributes_category;
        return $this->view('merchant.product-attribute-category.show',$this->viewData);
    }


    public function edit(ProductAttributeCategory $product_attributes_category)
    {
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Merchant Product Categories'),
            'url'=> url('system/merchant/product-attributes-category')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Product attribute categories'),
        ];
        $this->viewData['pageTitle'] = __('Edit Product attribute categories');

        $this->viewData['result'] = $product_attributes_category;

        return $this->view('merchant.product-attribute-category.create',$this->viewData);
    }


    public function update(Request $request, ProductAttributeCategory $product_attributes_category)
    {
        $theRequest = $request->only('name_ar','name_en','description_ar','description_en');

        $this->validate($request,[
            'name_ar'               =>      'required',
            'name_en'               =>      'required',
            'description_ar'        =>      'required',
            'description_en'        =>      'required',
        ]);

        if($product_attributes_category->update($theRequest)) {
            return redirect()->route('merchant.product-attributes-category.edit',$product_attributes_category->id)
                ->with('status','success')
                ->with('msg',__('Successfully Edit Merchant product attribute Category'));
        } else {
            return redirect()->route('merchant.product-attributes-category.edit')
                ->with('status','success')
                ->with('msg',__('Sorry Couldn\'t Edit Merchant product attribute Category'));;
        }
    }


    public function destroy(Request $request,ProductAttributeCategory $product_attributes_category){
        // Delete Data
        $status = $product_attributes_category->delete();
        if($status){
            $msg = __('Merchant Product attribute category has been deleted successfully');
        } else {
            $msg = __('Merchant Product attribute category couldn\'t be deleted');
        }

        if($request->ajax()){
            return ['status'=> true,'msg'=> $msg];
        }else{
            redirect()
                ->route('merchant.product-attributes-category.index')
                ->with('status','success')
                ->with('msg',$msg);
        }
    }


}
