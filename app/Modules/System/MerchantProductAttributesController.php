<?php

namespace App\Modules\System;

use App\Models\Attribute;
use App\Models\AttributeCategory;
use App\Models\ProductAttributeCategory;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;
use Auth;

class MerchantProductAttributesController extends SystemController
{
    public function __construct(){
        parent::__construct();
        $this->viewData['type'] = ['0'=>'','text'=>__('text'), 'radio'=>__('radio'), 'checkbox'=>__('checkbox'), 'select'=>__('select')];
        $this->viewData['multi'] = [''=>'','active'=>__('Yes'),'in-active'=>__('No')];
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
            $eloquentData = Attribute::select([
                'attributes.id',
                'attributes.type',
                'attributes.name_ar',
                'attributes.name_en',
                'attributes.description_ar',
                'attributes.description_en',
                'attributes.multi_lang',
                DB::raw("(SELECT COUNT(*) FROM `attribute_values` WHERE `attribute_values`.`attribute_id` = `attributes`.`id`) as `count`")
            ]);

            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }

            whereBetween($eloquentData,'attributes.created_at',$request->created_at1,$request->created_at2);

            if($request->id){
                $eloquentData->where('attributes.id','=',$request->id);
            }

            if(($request->type) && (strlen($request->type))){
                orWhereByLang($eloquentData,'attributes.type',$request->type);
            }

            if($request->name){
                orWhereByLang($eloquentData,'attributes.name',$request->name);
            }

            if($request->description){
                orWhereByLang($eloquentData,'attributes.description',$request->description);
            }

            if($request->multilang){
                orWhereByLang($eloquentData,'attributes.multi_lang',$request->multilang);
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
                                <li class=\"dropdown-item\"><a href=\"".route('merchant.product-attributes.show',$data->id)."\">".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a href=\"".route('merchant.product-attributes.edit',$data->id)."\">".__('Edit')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('merchant.product-attributes-category.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
                              </ul>
                            </div>";
                })
                ->make(true);
        }else{
            // View Data
            $this->viewData['tableColumns'] = ['ID','Attribute Name','Attribute Description','Attr-Val count','Action'];
            $this->viewData['breadcrumb'][] = [
                'text'=> __('Merchant Product Attribute'),
            ];

            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Merchant Product Attributes');
            }else{
                $this->viewData['pageTitle'] = __('Merchant Product Attributes');
            }

            return $this->view('merchant.product-attribute.index',$this->viewData);
        }
    }


    public function create(Request $request)
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Product attr categories'),
            'url'=> url('system/merchant/product-attributes')
        ];
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Pro attr category'),
        ];
        $this->viewData['pageTitle'] = __('Create Product attribute');
        $this->viewData['attribute_categories'] = AttributeCategory::all()->pluck('name_'.$this->systemLang,'id');
        return $this->view('merchant.product-attribute.create',$this->viewData);
    }


    public function store(Request $request){
        $theRequest = $request->only(['attribute_category_id','type','multi_lang','name_ar','name_en','description_ar','description_en','value_name_en','value_name_ar','value_default']);

        $this->validate($request,[
            'attribute_category_id' =>      'required',
            'type'                  =>      'required',
            'multi_lang'            =>      'required',
            'name_ar'               =>      'required',
            'name_en'               =>      'required',
            'description_ar'        =>      'required',
            'description_en'        =>      'required',
            'value_name_en'         =>      'required|array',
            'value_name_ar'         =>      'required|array',
            //'value_default'         =>      'required',
        ]);

        //dd($theRequest);

        $attributeData = [
            'attribute_category_id' =>      $theRequest['attribute_category_id'],
            'type'                  =>      $theRequest['type'],
            'multi_lang'            =>      $theRequest['multi_lang'],
            'name_ar'               =>      $theRequest['name_ar'],
            'name_en'               =>      $theRequest['name_en'],
            'description_ar'        =>      $theRequest['description_ar'],
            'description_en'        =>      $theRequest['description_en'],
        ];


        if($Attribute = Attribute::create($attributeData)){
            $AttrValues = [];
            foreach($theRequest['value_name_en'] as $key=>$val){
                $Attr = [
                    'attribute_id'  =>  $Attribute->id,
                    'text_en'       =>  $val,
                    'text_ar'       =>  $theRequest['value_name_ar'][$key],
                ];
                if((isset($theRequest['is_default'])) && $theRequest['is_default'][$key])
                    $Attr['is_default'] = (($theRequest['value_default'][$key])?1:'0');
                $AttrValues[] = $Attr;
            }
            $Attribute->attributeValue()->insert($AttrValues);

            return redirect()->route('merchant.product-attributes.index')
                ->with('status', 'success')
                ->with('msg', __('Data has been added successfully'));
        } else {
            return redirect()->route('merchant.product-attributes.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Merchant product attributes'));
        }
    }



    public function show(Request $request, Attribute $product_attribute){
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Merchant Product attributes'),
            'url'=> url('system/merchant/product-attributes')
        ];
        $this->viewData['breadcrumb'][] = [
            'text'=> $product_attribute->{'name_'.$this->systemLang},
        ];

        $this->viewData['pageTitle'] = __('Merchant Product Category');
        $this->viewData['result'] = $product_attribute;
        return $this->view('merchant.product-attribute.show',$this->viewData);
    }


    public function edit(Attribute $product_attribute)
    {
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Merchant Product attributes'),
            'url'=> url('system/merchant/product-attributes')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Product attribute'),
        ];
        $this->viewData['pageTitle'] = __('Edit Product attribute');

        $product_attribute->attributeValue;
        $this->viewData['result'] = $product_attribute;
        $this->viewData['attribute_categories'] = AttributeCategory::all()->pluck('name_'.$this->systemLang,'id');
        return $this->view('merchant.product-attribute.create',$this->viewData);
    }


    public function update(Request $request, Attribute $product_attribute)
    {
        $theRequest = $request->only(['type','multi_lang','name_ar','name_en','description_ar','description_en','value_name_en','value_name_ar','value_default']);

        $this->validate($request,[
            'type'                  =>      'required',
            'multi_lang'            =>      'required',
            'name_ar'               =>      'required',
            'name_en'               =>      'required',
            'description_ar'        =>      'required',
            'description_en'        =>      'required',
            'value_name_en'         =>      'required',
            'value_name_ar'         =>      'required',
            //'value_default'         =>      'required',
        ]);

        $attributeData = [
            'attribute_category_id' =>      $product_attribute->attribute_category_id,
            'type'                  =>      $theRequest['type'],
            'multi_lang'            =>      $theRequest['multi_lang'],
            'name_ar'               =>      $theRequest['name_ar'],
            'name_en'               =>      $theRequest['name_en'],
            'description_ar'        =>      $theRequest['description_ar'],
            'description_en'        =>      $theRequest['description_en'],
        ];

        if($product_attribute->update($attributeData)) {
            $AttrValues = [];
            foreach($theRequest['value_name_en'] as $key=>$val){
                $Attr = [
                    'attribute_id'  =>  $product_attribute->id,
                    'text_en'       =>  $val,
                    'text_ar'       =>  $theRequest['value_name_ar'][$key],
                ];
                if((isset($theRequest['is_default'])) && $theRequest['is_default'][$key])
                    $Attr['is_default'] = (($theRequest['value_default'][$key])?1:'0');
                $AttrValues[] = $Attr;
            }
            $product_attribute->attributeValue()->delete();
            $product_attribute->attributeValue()->insert($AttrValues);
            return redirect()->route('merchant.product-attributes.edit',$product_attribute->id)
                ->with('status','success')
                ->with('msg',__('Successfully Edit Merchant product attribute'));
        } else {
            return redirect()->route('merchant.product-attributes.edit')
                ->with('status','success')
                ->with('msg',__('Sorry Couldn\'t Edit Merchant product attribute'));
        }
    }


    public function destroy(Request $request,Attribute $product_attribute){
        // Delete Data
        $status = $product_attribute->delete();
        if($status){
            $msg = __('Merchant Product attribute has been deleted successfully');
        } else {
            $msg = __('Merchant Product attribute couldn\'t be deleted');
        }

        if($request->ajax()){
            return ['status'=> true,'msg'=> $msg];
        }else{
            redirect()
                ->route('merchant.product-attributes.index')
                ->with('status','success')
                ->with('msg',$msg);
        }
    }


}
