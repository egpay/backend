<?php

namespace App\Modules\System;

use App\Models\AttributeCategory;
use App\Models\Merchant;
use App\Models\MerchantCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Yajra\Datatables\Facades\Datatables;
use Form;
use Auth;
use App\Http\Requests\MerchantCategoryFormRequest;

class MerchantCategoryController extends SystemController
{

    public function __construct(){
        parent::__construct();
        $this->viewData['breadcrumb'] = [
            [
                'text'=> __('Home'),
                'url'=> route('system.dashboard'),
            ],
            [
                'text'=> __('Merchant')
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
            $systemLang = $this->systemLang;
            $eloquentData = MerchantCategory::select([
                'merchant_categories.id',
                'merchant_categories.icon',
                'merchant_categories.status',
                'merchant_categories.main_category_id',
                "merchant_categories.name_$systemLang as name",
                "merchant_categories.description_$systemLang as description"
            ])
            ->addSelect(DB::raw('(SELECT COUNT(*) FROM `merchants` WHERE `merchants`.`merchant_category_id` = `merchant_categories`.`id`) as `count_merchants`'));

            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }

            if($request->main_category_id){
                $eloquentData->where('main_category_id','=',$request->main_category_id);
            }else{
                $eloquentData->whereNull('main_category_id');
            }


            whereBetween($eloquentData,'merchant_categories.created_at',$request->created_at1,$request->created_at2);

            if($request->id){
                $eloquentData->where('merchant_categories.id', '=',$request->id);
            }


            if($request->name){
                $name = $request->name;
                $eloquentData->where(function($query) use($name) {
                    $query->where('merchant_categories.name_ar','LIKE','%'.$name.'%')
                        ->orWhere('merchant_categories.name_en','LIKE','%'.$name.'%');
                });
            }

            if($request->description){
                $description = $request->description;
                $eloquentData->where(function($query) use($description) {
                    $query->where('merchant_categories.description_ar','LIKE','%'.$description.'%')
                        ->orWhere('merchant_categories.description_en','LIKE','%'.$description.'%');
                });
            }

            if($request->status){
                $eloquentData->where('merchant_categories.status',$request->status);
            }

            if($request->staff_id){
                $eloquentData->where('merchant_categories.staff_id',$request->staff_id);
            }

            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('icon',function($data){
                    if(!$data->icon) return '--';
                    return '<img style="width: 70px;height: 70px;" src="'.asset('storage/app/'.imageResize($data->icon,70,70)).'" />';
                })
                ->addColumn('name',function($data){
                    if(is_null($data->main_category_id)){
                        return '<a href="'.route("merchant.category.index",["main_category_id"=>$data->id]).'">'.$data->name.'</a>';
                    }else{
                        return $data->name;
                    }
                })
                ->addColumn('description',function ($data){
                    return str_limit($data->description,10);
                })

                ->addColumn('count_merchants','{{number_format($count_merchants)}}')

                ->addColumn('action',function($data){
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('merchant.category.show',$data->id)."\">".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a href=\"".route('merchant.category.edit',$data->id)."\">".__('Edit')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('merchant.category.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
                              </ul>
                            </div>";
                })
                ->addColumn('status',function($data){
                    if($data->status == 'in-active'){
                        return 'tr-danger';
                    }
                    return '';
                })
                ->make(true);
        }else{

            // View Data
            $this->viewData['tableColumns'] = [
                __('ID'),
                __('Icon'),
                __('Name'),
                __('Description'),
                __('Num. Merchants'),
                __('Action')
            ];
            $this->viewData['breadcrumb'][] = [
                'text'=> __('Merchant Category')
            ];

            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Merchant Category');
            }else{
                $this->viewData['pageTitle'] = __('Merchant Category');
            }

            return $this->view('merchant.category.index',$this->viewData);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Merchant Category'),
            'url'=> route('merchant.category.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Create Merchant Category'),
        ];

        $this->viewData['pageTitle']    = __('Create Merchant Category');
        $this->viewData['mainCategory'] = array_column(
            MerchantCategory::whereNull('main_category_id')->get(['id','name_'.$this->systemLang])->toArray(),
            'name_'.$this->systemLang,
            'id'
        );
        $this->viewData['AttrCategories'] = AttributeCategory::select(['id','name_'.$this->systemLang.' as name','description_'.$this->systemLang.' as description'])->get();

        return $this->view('merchant.category.create',$this->viewData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MerchantCategoryFormRequest $request)
    {
        $requestData = $request->all();
        $requestData['staff_id'] = Auth::id();

        if($request->file('icon')) {
            $requestData['icon'] = str_replace('public/','',$request->icon->store('public/merchant/categories/'.date('Y-m-d')));
            try {
                $img = Image::make('storage/'.$requestData['icon']);
                $img->resize(64, 64)->save();
            } catch (\Exception $e){}
        }


        if(MerchantCategory::create($requestData))
            return redirect()->route('merchant.category.create')
                ->with('status','success')
                ->with('msg',__('Data has been added successfully'));
        else{
            return redirect()->route('merchant.category.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Merchant Category'));
        }

    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(MerchantCategory $category,Request $request){

        $MerchantCategory = $category;

        if($request->isMerchant){

            $eloquentData = Merchant::viewData($this->systemLang)
                ->where('merchants.merchant_category_id',$MerchantCategory->id);

            // Supervisor
            if(!staffCan('show-tree-users-data',Auth::id())){
                $eloquentData->whereIn('merchants.staff_id',Auth::user()->managed_staff_ids());
            }

            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('logo',function($data){
                    if(!$data->logo) return '--';
                    return '<img style="width:70px;height:70px" src="'.asset('storage/'.imageResize($data->logo,70,70)).'" />';
                })
                ->addColumn('name', function($data){
                    return '<a target="_blank" href="'.route('merchant.merchant.show',$data->id).'">'.$data->name.' ('.$data->category_name.') </a>';
                })
                ->addColumn('staff_firstname',function($data){
                    return '<a target="_blank" href="'.url('system/staff/'.$data->staff_id).'">'.$data->staff_firstname.' '.$data->staff_lastname.'</a>';
                })
                ->make(true);
        }else{
            $this->viewData['breadcrumb'][] = [
                'text'=> __('Merchant Category'),
                'url'=> route('merchant.category.index')
            ];

            $this->viewData['breadcrumb'][] = [
                'text'=> $category->{'name_'.$this->systemLang}
            ];
            $this->viewData['pageTitle'] = __('Merchant Category');
            $this->viewData['result'] = $MerchantCategory;

            return $this->view('merchant.category.show',$this->viewData);

        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(MerchantCategory $category)
    {
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Merchant Category'),
            'url'=> route('merchant.category.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Edit Merchant Category'),
        ];
        $this->viewData['pageTitle'] = __('Edit Merchant Category');
        $this->viewData['result'] = $category;
        $this->viewData['mainCategory'] = array_column(
            MerchantCategory::whereNull('main_category_id')->get(['id','name_'.$this->systemLang])->toArray(),
            'name_'.$this->systemLang,
            'id'
        );
        $this->viewData['AttrCategories'] = AttributeCategory::select(['id','name_'.$this->systemLang.' as name','description_'.$this->systemLang.' as description'])->get();

        return $this->view('merchant.category.create',$this->viewData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(MerchantCategoryFormRequest $request,MerchantCategory $category)
    {
        $requestData = $request->all();
        $requestData['staff_id'] = Auth::id();

        if($request->file('icon')) {
            $requestData['icon'] = str_replace('public/','',$request->icon->store('public/merchant/categories/'.date('Y-m-d')));
            try {
                $img = Image::make('storage/'.$requestData['icon']);
                $img->resize(64, 64)->save();
            } catch (\Exception $e){}
        }

        if($category->update($requestData)) {
            return redirect()->route('merchant.category.edit',$category->id)
                ->with('status','success')
                ->with('msg',__('Successfully edited Merchant Category'));
        }else{
            return redirect()->route('merchant.category.edit')
                ->with('status','success')
                ->with('msg',__('Sorry Couldn\'t Edit Merchant Category'));;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(MerchantCategory $category,Request $request){
        $category->delete();
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('Category has been deleted successfully')];
        }else{
            redirect()
                ->route('merchant.category.index')
                ->with('status','success')
                ->with('msg',__('This Category has been deleted'));
        }

    }


}
