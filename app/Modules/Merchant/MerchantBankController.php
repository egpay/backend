<?php

namespace App\Modules\Merchant;

use App\Models\Bank;
use App\Models\Merchant;
use App\Models\AreaType;
use App\Models\MerchantBank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;
use App\Models\MerchantCategory;
use Illuminate\Support\Facades\Validator;
use Auth;

class MerchantBankController extends MerchantController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $merchant = $request->user()->merchant();

        if($request->isDataTable){
            $eloquentData = MerchantBank::viewData($this->systemLang);

            $eloquentData->where('merchant_banks.merchant_id',$merchant->id);


            whereBetween($eloquentData,'merchant_banks.created_at',$request->created_at1,$request->created_at2);
            if($request->id){
                $eloquentData->where('merchant_banks.id', '=',$request->id);
            }


            if($request->bank_name){
                $eloquentData->where('banks.name_'.$this->systemLang,$request->bank_name);
            }

            if($request->name){
                $name = $request->name;
                $eloquentData->where(function($query) use($name) {
                    $query->where('name','LIKE',"%$name%");
                });
            }


            if($request->account_number){
                $eloquentData->where('merchant_banks.account_number', '=',$request->account_number);
            }

            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('logo',function($data){
                    if(!$data->logo) return '--';
                    return '<img src="'.asset('storage/'.imageResize($data->logo,70,70)).'" />';
                })
                ->addColumn('name', function($data){
                    return $data->name.' ('.$data->name.') ';
                })
                ->addColumn('account_number', function($data){
                    return $data->account_number;
                })

                ->addColumn('action',function($data){

                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('panel.merchant.bank.show',$data->id)."\">".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a href=\"".route('panel.merchant.bank.edit',$data->id)."\">".__('Edit')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('panel.merchant.bank.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
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
            $this->viewData['tableColumns'] = [__('ID'),__('Logo'),__('Name'),__('Account #'),__('Action')];



            $this->viewData['pageTitle'] = __('Merchant Bank accounts');

            // Filter Data
            $this->viewData['areaData'] = AreaType::getFirstArea($this->systemLang);
            $MerchantCategory = MerchantCategory::get(['id','name_'.$this->systemLang.' as name']);
            if($MerchantCategory->isNotEmpty()){
                $this->viewData['merchantCategories'] = array_merge(['Select Category'],array_column($MerchantCategory->toArray(),'name','id'));
            }else{
                $this->viweData['merchantCategories'] = [__('Select Category')];
            }
            // Filter Data
            $this->viewData['banks'] = array_column(Bank::get(['id','name_'.$this->systemLang])->toArray(),'name_'.$this->systemLang,'id');

            return $this->view('banks.index',$this->viewData);
        }
    }

    public function create()
    {
        $this->viewData['pageTitle'] = __('Add Bank account');
        $this->viewData['banks'] = array_column(Bank::get(['id','name_'.$this->systemLang])->toArray(),'name_'.$this->systemLang,'id');


        return $this->view('banks.create',$this->viewData);
    }


    public function store(Request $request){
        $RequestData = $request->only(['name','account_number','bank_id']);

        Validator::make($RequestData, [
            'name'                  => 'required',
            'account_number'        => 'required',
            'bank_id'               => 'required',
        ])->validate();


        $merchant = request()->user()->merchant();

        $RequestData['merchant_id'] = $merchant->id;

        if(MerchantBank::create($RequestData))
            return redirect()
                ->route('panel.merchant.bank.create')
                ->with('status','success')
                ->with('msg',__('Bank account has been added successfully'));
        else{
            return redirect()
                ->route('panel.merchant.bank.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Merchant Bank Account'));
        }
    }

    public function show(MerchantBank $bank)
    {
        $this->viewData['pageTitle'] = __('Bank account Information');
        $this->viewData['result'] = $bank;
        $this->viewData['bankcol'] = 'name_'.$this->systemLang;
        return $this->view('banks.view',$this->viewData);
    }


    public function edit(Request $request,MerchantBank $bank){
        $merchant = $request->user()->merchant();
        if($merchant->id != $bank->merchant_id)
            return abort(404);

        $this->viewData['pageTitle'] = __('Edit bank account: ').$bank->name;
        $this->viewData['banks'] = array_column(Bank::get(['id','name_'.$this->systemLang])->toArray(),'name_'.$this->systemLang,'id');
        $this->viewData['result'] = $bank;

        return $this->view('banks.create',$this->viewData);
    }


    public function update(Request $request,MerchantBank $bank)
    {
        $merchant = $request->user()->merchant();
        if($merchant->id != $bank->merchant_id) {;
            return abort(404);
        }
        $RequestData = $request->only(['name','account_number','bank_id']);

        Validator::make($RequestData, [
            'name'                  => 'required',
            'account_number'        => 'required',
            'bank_id'               => 'required',
        ])->validate();

        if($bank->update($RequestData)) {
            return redirect()->route('panel.merchant.bank.index')
                ->with('status','success')
                ->with('msg',__('Successfully edited bank account details'));
        }else{
            return redirect()->route('panel.merchant.bank.edit')
                ->with('status','success')
                ->with('msg',__('Sorry Couldn\'t Edit Merchant bank account'));
        }
    }



    public function destroy(MerchantBank $bank,Request $request){
        $merchant = $request->user()->merchant();
        if($merchant->id != $bank->merchant_id) {;
            return abort(404);
        }
        // Delete Data
        $bank->delete();
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('Bank has been deleted successfully')];
        }else{
            return redirect()
                ->route('panel.merchant.bank.index')
                ->with('status','success')
                ->with('msg',__('This bank account details has been deleted'));
        }
    }



}