<?php

namespace App\Modules\Merchant;

use App\Models\Attribute;
use App\Models\Merchant;
use App\Models\AreaType;
use App\Models\MerchantBranch;
use App\Models\MerchantPlan;
use App\Models\AttributeCategory;
use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use App\Models\MerchantCategory;
use App\Http\Requests\MerchantBranchFormRequest;
use Auth;
use Illuminate\Support\Facades\Validator;

class AttributesController extends MerchantController
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $merchant = Auth::user()->merchant();



        /*
        $category = AttributeCategory::create([
            'name_ar'           =>'Clothes AR',
            'name_en'           =>'Clothes EN',
            'description_ar'    =>'Clothes Description AR',
            'description_en'    =>'Clothes Description EN',
        ]);

        $attribute = $category->attributes()->create([
            'type'              =>  'text',
            'name_ar'           =>  'LettersSize AR',
            'name_en'           =>  'LettersSize EN',
            'description_ar'    =>  'Size In Letters AR',
            'description_en'    =>  'Size In Letters EN',
            'multi_lang'        =>  'active'
        ]);

        $attribute->attributeValue()->create([
            'text_ar'       =>'Red AR',
            'text_en'       =>'Red EN',
        ]);

        $attribute->attributeValue()->create([
            'text_ar'       =>'Blue AR',
            'text_en'       =>'Blue EN',
        ]);

        $attribute->attributeValue()->create([
            'text_ar'       =>'Green AR',
            'text_en'       =>'Green EN',
        ]);

        $attribute->attributeValue()->create([
            'text_ar'       =>'Gray AR',
            'text_en'       =>'Gray EN',
        ]);
        die;
        */




        if($request->getAttribute){
            $validator = Validator::make($request->only(['id']), ['id' => 'required|exists:attributes,id']);
            if($validator->errors()->any()){
                return '';
            } else {
                $lang = $this->systemLang;
                return Attribute::where('id','=',$request->id)->with(['attributeValue'=>function($query)use($lang){
                    $query->select(['id','attribute_id','text_'.$lang.' as text','is_default']);
                }])->first();
            }
        }

        /*

        $categories = $merchant->attributeCategories($this->systemLang)->with('attributes')->get();
        return view('component.attributes',[
            'pageTitle'     => __('Product Attributes'),
            'categories'    => $categories,
            'systemLang'    => $this->systemLang,
        ]);

        dd($merchant->attributeCategories()->with('attributes')->get());

        die;




        //add text
        $category->attributes()->create([
            'type'              =>  'select',
            'default_value'     =>  5,
            'options'           =>  [
                'values'=>[2,4,5,9],
                'ar'=>['red AR','Blue Ar','Black AR','Gray AR'],
                'en'=>['red EN','Blue EN','Black EN','Gray EN'],
            ],
            'name_ar'           =>  'Color AR',
            'name_en'           =>  'Color EN',
            'description_ar'    =>  'Color Description AR',
            'description_en'    =>  'Color Description EN',
            'multi_lang'        =>  'active'
        ]);



        $merchant->update([
            'attribute_categories'      => $category->id
        ]);



        dd('here');



        die;
        */

        if($request->isDataTable){

            $eloquentData = ProductAttributes::viewData($this->systemLang);

            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }


            whereBetween($eloquentData,'product_attributes.created_at',$request->created_at1,$request->created_at2);
            if($request->id){
                $eloquentData->where('product_attributes.id', '=',$request->id);
            }


            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('logo',function($data){
                    if(!$data->logo) return '--';
                    return '<img src="'.asset('storage/'.imageResize($data->logo,70,70)).'" />';
                })
                ->addColumn('name', function($data){
                    return $data->name.' ('.$data->merchant_name.') ';
                })
                ->addColumn('address','{{str_limit($address,10)}}')
                ->addColumn('map',function($data){
                    if(!empty($data->latitude) && !empty($data->longitude)){
                        return '<a href="javascript:void(0);" onclick="viewMap('.$data->latitude.','.$data->longitude.',\''.$data->name.' ('.$data->merchant_name.') \')">View Map</a>';
                    }else{
                        return '--';
                    }
                })
                ->addColumn('action',function($data){

                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('merchant.branch.show',$data->id)."\">".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a href=\"".route('merchant.branch.edit',$data->id)."\">".__('Edit')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('merchant.branch.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
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
            $this->viewData['tableColumns'] = ['ID','Logo','Name','Address','Map','Action'];


            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Merchant Branches');
            }else{
                $this->viewData['pageTitle'] = __('Merchant Branches');
            }

            // Filter Data
            $this->viewData['areaData'] = AreaType::getFirstArea($this->systemLang);
            $MerchantCategory = MerchantCategory::get(['id','name_'.$this->systemLang.' as name']);
            if($MerchantCategory->isNotEmpty()){
                $this->viewData['merchantCategories'] = array_merge(['Select Category'],array_column($MerchantCategory->toArray(),'name','id'));
            }else{
                $this->viweData['merchantCategories'] = [__('Select Category')];
            }
            // Filter Data

            $merchantPlans =  MerchantPlan::get(['id','title'])->toArray();
            $merchantPlans = array_merge([['id'=>0,'title'=>__('Select Plan')]],$merchantPlans);
            $this->viewData['merchantPlans'] = $merchantPlans;

            $this->viewData['breadcrumb'][] = [
                'text'=> __('Merchant Branches'),
            ];

            return $this->view('merchant.branch.index',$this->viewData);
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
            'text'=> __('Merchant Branches'),
            'url'=> url('system/merchant/branch')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Create Merchant Branche'),
        ];

        $this->viewData['pageTitle'] = __('Create Merchant Branche');
        $this->viewData['areaData'] = AreaType::getFirstArea($this->systemLang);


        // Add Branch To Merchant With GET ID
        $merchantID = request('merchant_id');
        if($merchantID){
            $merchantData = Merchant::findOrFail($merchantID);
            $this->viewData['merchantData'] = $merchantData;
        }


        return $this->view('merchant.branch.create',$this->viewData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){

        dd($request->all());

        //TODO Insert loggedin user id

        $request['staff_id'] = Auth::id();
        $request['area_id'] = getLastNotEmptyItem($request->area_id);

        if(MerchantBranch::create($request->all()))
            return redirect()
                ->route('merchant.branch.create')
                ->with('status','success')
                ->with('msg',__('Data has been added successfully'));
        else{
            return redirect()
                ->route('merchant.branch.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Merchant Branch'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(MerchantBranch $branch){
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Merchant Branches'),
            'url'=> url('system/merchant/branch')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Edit Merchant Branche'),
        ];
        $this->viewData['pageTitle'] = __('Edit Merchant Branche');

        $this->viewData['result'] = $branch;

        $this->viewData['areaData'] = AreaType::getFirstArea($this->systemLang);
        return $this->view('merchant.branch.create',$this->viewData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(MerchantBranchFormRequest $request,MerchantBranch $branch)
    {
        $request['area_id'] = getLastNotEmptyItem($request->area_id);
        if($branch->update($request->all())) {
            return redirect()->route('merchant.branch.edit',$branch->id)
                ->with('status','success')
                ->with('msg',__('Successfully edited Merchant branch'));
        }else{
            return redirect()->route('merchant.branch.edit')
                ->with('status','success')
                ->with('msg',__('Sorry Couldn\'t Edit Merchant Branch'));
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(MerchantBranch $branch,Request $request){
        // Delete Data
        $branch->delete();
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('Branch has been deleted successfully')];
        }else{
            redirect()
                ->route('merchant.branch.index')
                ->with('status','success')
                ->with('msg',__('This branch has been deleted'));
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
