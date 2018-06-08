<?php

namespace App\Modules\System;

use App\Http\Requests\RechargeListFormRequest;
use App\Models\RechargeList;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Mockery\Exception;
use Yajra\Datatables\Facades\Datatables;
use Form;

class RechargeListController extends SystemController
{

    public function __construct(){
        parent::__construct();
        $this->viewData['breadcrumb'] = [
            [
                'text'=> __('Home'),
                'url'=> url('system'),
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

            $eloquentData = RechargeList::select([
                'id',
                'merchant_id',
                'status',
                'updated_at'
            ])->with('numbers');


            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }

            /*
             * Start handling filter
             */

            whereBetween($eloquentData,'created_at',$request->created_at1,$request->created_at2);

            if($request->id){
                $eloquentData->where('id', '=',$request->id);
            }

            if($request->merchant_id){
                $eloquentData->where('merchant_id', '=',$request->merchant_id);
            }

            if($request->status){
                $eloquentData->where('status','=',$request->status);
            }


            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('merchant',function($data){
                    return '<a target="_blank" href="'.route('merchant.merchant.show',$data->merchant->id).'">'.$data->merchant->{'name_'.$this->systemLang}.'</a>';
                })
                ->addColumn('status', '{{statusColor($status)}}')
                ->addColumn('count', function($data){
                    return $data->numbers->count();
                })
                ->addColumn('updated_at',function($data){
                    return $data->updated_at->diffForHumans();
                })
                ->addColumn('action',function($data){
                    return "<div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('payment.recharge-list.show',$data->id)."\">".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('payment.recharge-list.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
                              </ul>
                            </div>";
                })
                ->make(true);
        }else{
            // View Data
            $this->viewData['tableColumns'] = [
                __('ID'),
                __('Merchant'),
                __('Status'),
                __('Num. Mobile'),
                __('Last Update'),
                __('Action')
            ];
            $this->viewData['breadcrumb'][] = [
                'text'=> __('Recharge List')
            ];

            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Recharge List');
            }else{
                $this->viewData['pageTitle'] = __('Recharge List');
            }


            return $this->view('payment.recharge-list.index',$this->viewData);
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
            'text'=> __('Recharge List'),
            'url'=> route('payment.recharge-list.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Create Recharge List'),
        ];

        $this->viewData['pageTitle'] = __('Create Recharge List');

        return $this->view('payment.recharge-list.create',$this->viewData);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RechargeListFormRequest $request)
    {
        $theRequest = $request->all();


        if($request->file('numbers')) {
            $theRequest['xls_path'] = $request->numbers->store('recharge-list/'.date('y').'/'.date('m'));
        }

        if(isset($theRequest['start_at']) && $theRequest['start_at'] != 'custom'){
            $theRequest['cron_jobs'] = date('Y-m-d H:i:s');
        }

        $xls = storage_path('app/'.$theRequest['xls_path']);



        DB::beginTransaction();

        try{
            $rechargeList = RechargeList::create($theRequest);

            $xls = Excel::load($xls)->toArray();


            if(!is_array($xls) || empty($xls)){
                throw new Exception('Error');
            }

            $numbersToAdd = [];
            foreach ($xls as $key => $value){

                if(!isset($value[0],$value[1]) || empty($value[0]) || empty($value[1])){
                    continue;
                }


                // Improve Mobile Number
                if(mb_strlen($value[0]) == 10){
                    $value[0] = '0'.$value[0];
                }elseif(
                    mb_strlen($value[0]) == 12 && starts_with($value[0],'2')
                ){
                    $value[0] = mb_substr($value[0],1);
                }elseif(
                    (mb_strlen($value[0]) == 13 && starts_with($value[0],'+2')) ||
                    (mb_strlen($value[0]) == 13 && starts_with($value[0],'02'))
                ){
                    $value[0] = mb_substr($value[0],2);
                }elseif(mb_strlen($value[0]) == 14 && starts_with($value[0],'002')){
                    $value[0] = mb_substr($value[0],3);
                }




                // Get Service Provider
                if(isset($value[2]) && in_array($value[2],['orange','vodafone','etisalat','we'])){
                    $service = strtolower($value[2]);
                }elseif(starts_with($value[0],'012')){
                    $service = 'orange';
                }elseif(starts_with($value[0],'010')){
                    $service = 'vodafone';
                }elseif(starts_with($value[0],'011')){
                    $service = 'etisalat';
                }elseif(starts_with($value[0],'015')){
                    $service = 'we';
                }else{
                    continue;
                }

                if(!is_numeric($value[1])){
                    continue;
                }

                $numbersToAdd[] = [
                    'service'=> $service,
                    'mobile'=> $value[0],
                    'amount'=> $value[1]
                ];
            }



            if(empty($numbersToAdd)){
                throw new Exception('Error');
            }

            $rechargeList->numbers()->createMany($numbersToAdd);

        }catch (\Exception $exception){
            DB::rollBack();
            return redirect()
                ->route('payment.recharge-list.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Recharge List'));
        }


        DB::commit();

        return redirect()
            ->route('payment.recharge-list.show',$rechargeList->id)
            ->with('status','success')
            ->with('msg',__('Data has been added successfully'));

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(RechargeList $recharge_list){

        $this->viewData['breadcrumb'] = [
            [
                'text'=> __('Home'),
                'url'=> url('system'),
            ],
            [
                'text'=> __('Recharge List'),
                'url'=> route('payment.recharge-list.index'),
            ]
        ];

        $this->viewData['pageTitle'] = __('Show Recharge List');

        $this->viewData['result'] = $recharge_list;
        return $this->view('payment.recharge-list.show',$this->viewData);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(RechargeList $recharge_list, Request $request)
    {
        // Delete Data
        $recharge_list->delete();
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('Recharge List has been deleted successfully')];
        }else{
            redirect()
                ->route('payment.recharge-list.index')
                ->with('status','success')
                ->with('msg',__('Recharge List has been deleted successfully'));
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
