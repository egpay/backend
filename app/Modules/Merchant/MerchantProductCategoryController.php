<?php

namespace App\Modules\Merchant;

use App\Models\MerchantBranch;
use App\Models\MerchantProductCategory;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;

use Illuminate\Support\Facades\Validator;
use Auth;

class MerchantProductCategoryController extends MerchantController
{

    protected $viewData = [];

    public function index(Request $request){

        $merchant = $request->user()->merchant();
        if($request->isDataTable){
            $eloquentData = MerchantProductCategory::viewData($this->systemLang,['merchant_product_categories.status']);
            $eloquentData->where('merchant_product_categories.merchant_id','=',$merchant->id);

            whereBetween($eloquentData,'merchant_product_categories.created_at',$request->created_at1,$request->created_at2);



            if($request->id){
                orWhereByLang($eloquentData,'merchant_product_categories.id',$request->id);
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
                ->addColumn('name_ar',function($data){
                    return $data->name_ar;
                })
                ->addColumn('name_en','{{$name_en}}')

                ->addColumn('icon',function($data){
                    if(!$data->icon) return '--';
                    return '<img src="'.asset('storage/'.imageResize($data->icon,70,70)).'" />';
                })

                ->addColumn('status',function($data){
                    if($data->status == 'active'){
                        return '<b class="text-success">Active</b>';
                    }else{
                        return '<b class="text-danger">In-Active</b>';
                    }
                })

                ->addColumn('action',function($data){

                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('panel.merchant.product-category.show',$data->id)."\">".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a href=\"".route('panel.merchant.product-category.edit',$data->id)."\">".__('Edit')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('panel.merchant.product-category.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
                              </ul>
                            </div>";
                })
                ->make(true);
        }else{

            // View Data
            $this->viewData['tableColumns'] = [__('ID'),__('Name (AR)'),__('Name (EN)'),__('Icon'),__('Status'),__('Action')];
            $this->viewData['pageTitle'] = __('Merchant Product Categories');
            $this->viewData['merchantStaffs'] = $merchant->MerchantStaff()->select(DB::raw("CONCAT(`firstname`,' ',`lastname`) as fullName"),'merchant_staff.id')->pluck('fullName','id')->toArray();

            return $this->view('productcategory.index',$this->viewData);
        }
    }

    public function create(Request $request)
    {
        $this->viewData['merchant'] = $request->user()->merchant();
        $this->viewData['pageTitle'] = __('Create product Category');
        return $this->view('productcategory.create',$this->viewData);
    }


    public function store(Request $request)
    {
        $theRequest = $request->only(['name_en','description_en','name_ar','description_ar','status','icon']);

        Validator::make($theRequest, [
            'name_en'                       => 'required',
            'description_en'                => 'required',
            'name_ar'                       => 'required',
            'description_ar'                => 'required',
            'status'                        => 'required|in:active,in-active',
            'icon'                          => 'image'
        ])->validate();

        $merchant = $request->user()->merchant();

        $theRequest['merchant_id'] = $merchant->id;
        $theRequest['created_by_merchant_staff_id'] = Auth::id();


        if($request->file('icon')) {
            $theRequest['icon'] = $request->icon->store('public/productcategory');
        }else{
            unset($theRequest['icon']);
        }


        if(MerchantProductCategory::create($theRequest))
            return redirect()->route('panel.merchant.product-category.index');
        else{
            return redirect()->route('panel.merchant.product-category.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Merchant product Category'));
        }

    }

    public function show(Request $request, MerchantProductCategory $product_category){
        $merchant = $request->user()->merchant();
        if($merchant->id != $product_category->merchant_id)
            return redirect()->route('panel.merchant.home');

        $this->viewData['pageTitle'] = __('Product Category');
        $this->viewData['result'] = $product_category;
        return $this->view('productcategory.view',$this->viewData);
    }

    public function edit(Request $request, MerchantProductCategory $product_category){
        $merchant = $request->user()->merchant();
        if($merchant->id != $product_category->merchant_id)
            return redirect()->route('panel.merchant.home');

        $this->viewData['pageTitle'] = __('Edit Category');

        $this->viewData['result'] = $product_category;

        return $this->view('productcategory.create',$this->viewData);
    }


    public function update(Request $request,MerchantProductCategory $product_category)
    {
        $merchant = $request->user()->merchant();
        if($merchant->id != $product_category->merchant_id)
            redirect()->route('panel.merchant.home');

        $theRequest = $request->only(['name_en','description_en','name_ar','description_ar','status','icon']);
        Validator::make($theRequest, [
            'name_en'                       => 'required',
            'description_en'                => 'required',
            'name_ar'                       => 'required',
            'description_ar'                => 'required',
            'status'                        => 'required|in:active,in-active',
            'icon'                          => (($request->file('icon') != null)?'image|required': ''),
        ])->validate();

        if($request->file('icon')) {
            $theRequest['icon'] = $request->icon->store('public/productcategory');
        }else{
            unset($theRequest['icon']);
        }


        if($product_category->update($theRequest)) {
            return redirect()->route('panel.merchant.product-category.index')
                ->with('status','success')
                ->with('msg',__('Successfully edited Category'));
        }else{
            return redirect()->route('panel.merchant.product-category.edit')
                ->with('status','success')
                ->with('msg',__('Sorry Couldn\'t Edit Category'));
        }
    }



    public function destroy(MerchantBranch $branch,Request $request){
        $merchant = $request->user()->merchant();
        if($merchant->id != $branch->id) {;
            redirect()->route('panel.merchant.home');
        }
        // Delete Data
        $branch->delete();
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('Category has been deleted successfully')];
        }else{
            redirect()
                ->route('panel.merchant.product-category.index')
                ->with('status','success')
                ->with('msg',__('Category has been deleted'));
        }
    }

}