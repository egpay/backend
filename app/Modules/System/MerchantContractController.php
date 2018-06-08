<?php

namespace App\Modules\System;

use App\Models\Upload;
use App\Models\Merchant;
use App\Models\MerchantContract;
use App\Models\MerchantPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;
use App\Http\Requests\MerchantContractFormRequest;
use Carbon;
use Auth;

class MerchantContractController extends SystemController
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
                'url'=> url('system/merchant/contract')
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
            $eloquentData = MerchantContract::viewData($this->systemLang);

            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }


            whereBetween($eloquentData,'merchant_contracts.created_at',$request->created_at1,$request->created_at2);

            if($request->id){
                $eloquentData->where('merchant_contracts.id', '=',$request->id);
            }

            if($request->plan_id){
                $eloquentData->where('merchant_contracts.plan_id','=',$request->plan_id);
            }

            if($request->description){
                $eloquentData->where('merchant_contracts.description','LIKE',"%{$request->description}%");
            }

            whereBetween($eloquentData,'merchant_contracts.price',$request->price1,$request->price2);
            whereBetween($eloquentData,'merchant_contracts.start_date',$request->start_date1,$request->start_date2);
            whereBetween($eloquentData,'merchant_contracts.end_date',$request->end_date1,$request->end_date2);

            if($request->admin_name){
                $eloquentData->where('merchant_contracts.admin_name','LIKE',"%".$request->admin_name."%");
            }

            if($request->admin_job_title){
                $eloquentData->where('merchant_contracts.admin_job_title','LIKE',"%".$request->admin_job_title."%");
            }

            if($request->staff_id){
                $eloquentData->where('merchant_contracts.staff_id',$request->staff_id);
            }

            // Supervisor
            if(!staffCan('show-tree-users-data',Auth::id())){
                $eloquentData->whereIn('merchants.staff_id',Auth::user()->managed_staff_ids());
            }

            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('logo',function($data){
                    if(!$data->logo) return '--';
                    return '<img src="'.asset('storage/'.imageResize($data->logo,70,70)).'" />';
                })
                ->addColumn('name', function($data){
                    return $data->name.' ('.$data->category_name.') ';
                })
                ->addColumn('start_date','{{$start_date}} : {{$end_date}}')
                ->addColumn('price','{{$price}} LE')
                ->addColumn('action',function($data){
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('merchant.contract.show',$data->id)."\">".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a href=\"".route('merchant.contract.edit',$data->id)."\">".__('Edit')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('merchant.contract.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
                              </ul>
                            </div>";
                })
                ->make(true);
        }else{

            // View Data
            $this->viewData['tableColumns'] = ['ID','Logo','Merchant','Period','Price','Action'];

            $this->viewData['breadcrumb'][] = [
                'text'=> __('Merchant Contracts'),
            ];

            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Merchant Contracts');
            }else{
                $this->viewData['pageTitle'] = __('Merchant Contracts');
            }


            $this->viewData['merchantPlans'] = array_column(MerchantPlan::get(['id','title'])->toArray(),'title','id');

            return $this->view('merchant.contract.index',$this->viewData);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Merchant Contracts'),
            'url'=> url('system/merchant/contract')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Create Merchant Contract'),
        ];

        $this->viewData['pageTitle'] = __('Create Merchant Contract');

//        $merchant = Merchant::find($request->merchant_id);
//        if(isset($merchant) && ($contract = $merchant->merchant_contract)) {
//            $datediff = $contract->end_date->diffInDays(Carbon::now());
//            if(($datediff > 5 )){
//                redirect()
//                    ->route('merchant.contract.index')
//                    ->with('status','danger')
//                    ->with('msg',__('Can\'t add new contract while one is already active'));
//            }
//        }
//        $this->viewData['merchant'] = $merchant;



        // Add Branch To Merchant With GET ID
        $merchantID = request('merchant_id');
        if($merchantID){
            $merchantData = Merchant::where('id',$merchantID)
                ->whereIn('staff_id',Auth::user()->managed_staff_ids())
                ->firstOrFail();
            $this->viewData['merchantData'] = $merchantData;
        }
        $this->viewData['merchantPlans'] = array_column( MerchantPlan::get(['id','title'])->toArray(),'title','id');

        return $this->view('merchant.contract.create',$this->viewData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MerchantContractFormRequest $request)
    {
        $request['staff_id'] = Auth::id();

        $merchant        = Merchant::getWithRelations($request->merchant_id);
        $merchantNewPlan = MerchantPlan::find($request->plan_id);

        if($merchant->end_date){

            // ---
            if(new DateTime(date('Y-m-d')) < new DateTime($merchant->end_date)){
                return redirect()->route('merchant.contract.create')
                    ->with('status','danger')
                    ->with('msg',__('Sorry Couldn\'t add Merchant Contract before ( '.$merchant->end_date.' )'));
            }
            // ---

            $request['start_date'] = $merchant->end_date;
            $explodeEndData        = explode('-',$merchant->end_date);

            $request['end_date'] = (Carbon::create($explodeEndData[0],$explodeEndData[1],$explodeEndData[2]))
                                    ->addMonths($merchantNewPlan->months);
        }else{
            $request['start_date'] = Carbon::now();
            $request['end_date'] = (Carbon::now())
                ->addMonths($merchantNewPlan->months);
        }

        if($insertData = MerchantContract::create($request->all())) {


            Merchant::where('id',$request->merchant_id)
                ->update(['merchant_contract_id'=>$insertData->id]);

            // Start Upload Files
            $uploads = new \Illuminate\Support\Collection();
            $files = $request->file;
            if($files){
                foreach ($files as $key => $value){
                    $uploads->push(new Upload([
                        'path'      => $value->store('contract/'.$insertData->merchant_id),
                        'title'     => @$request->title[$key],
                        'model_id'  => $insertData->id
                    ]));
                }
                $insertData->upload()->saveMany($uploads);
            }
            // End Upload Files
            if(!$merchant->merchant_contract_id){
                return redirect()->route('merchant.contract.create', ['merchant_id'=>$merchant->id]);
            }else{
                return redirect()->route('merchant.contract.show', $insertData->id);
            }
        }else{
            return redirect()->route('merchant.contract.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Merchant Contract'));
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(MerchantContract $contract){

        if(!staffCan('show-tree-users-data',Auth::id()) && !in_array($contract->merchant->staff_id,Auth::user()->managed_staff_ids())){
            abort(404);
        }


        $this->viewData['breadcrumb'][] = [
            'text'=> __('Merchant Contracts'),
            'url'=> url('system/merchant/contract')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> $contract->merchant->{'name_'.$this->systemLang},
            'url'=> route('merchant.merchant.show',$contract->merchant->id)
        ];

        $this->viewData['pageTitle'] = __('Merchant Contract');

        $this->viewData['result'] = $contract;
        return $this->view('merchant.contract.show',$this->viewData);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(MerchantContract $contract)
    {
        if(!staffCan('show-tree-users-data',Auth::id()) && !in_array($contract->merchant->staff_id,Auth::user()->managed_staff_ids())){
            abort(404);
        }

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Merchant Contracts'),
            'url'=> url('system/merchant/contract')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Edit Merchant Contract'),
        ];

        $this->viewData['pageTitle'] = __('Edit Merchant Contract');

        $this->viewData['merchantPlans'] = array_column( MerchantPlan::get(['id','title'])->toArray(),'title','id');
        $this->viewData['result'] = $contract;

        return $this->view('merchant.contract.create',$this->viewData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(MerchantContractFormRequest $request,MerchantContract $contract)
    {
        if(!staffCan('show-tree-users-data',Auth::id()) && !in_array($contract->merchant->staff_id,Auth::user()->managed_staff_ids())){
            abort(404);
        }

        if($contract->update($request->only(['admin_name','admin_job_title','description']))) {
            // Start Upload Files
            $uploads = new \Illuminate\Support\Collection();
            $files = $request->file;
            if($files){
                foreach ($files as $key => $value){
                    $uploads->push(new Upload([
                        'path' => $value->store('contract/'.$contract->merchant_id),
                        'title' => @$request->title[$key],
                        'model_id' => $contract->id
                    ]));
                }
                $contract->upload()->saveMany($uploads);
            }
            // End Upload Files

            return redirect()->route('merchant.contract.edit',$contract->id)
                ->with('status','success')
                ->with('msg',__('Successfully edited Merchant Contract'));
        }else{
            return redirect()->route('merchant.contract.index')
                ->with('status','success')
                ->with('msg',__('Sorry Couldn\'t Edit Merchant Contract'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(MerchantContract $contract,Request $request){
        if(!staffCan('show-tree-users-data',Auth::id()) && !in_array($contract->merchant->staff_id,Auth::user()->managed_staff_ids())){
            abort(404);
        }

        abort(404);
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
