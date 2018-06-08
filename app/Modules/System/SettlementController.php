<?php

namespace App\Modules\System;

use App\Libs\Commission;
use App\Libs\WalletData;
use App\Models\PaymentInvoice;
use App\Models\Setting;
use App\Models\WalletTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\WalletSettlement;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Facades\Datatables;

class SettlementController extends SystemController
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

        // if($request->one){
        //     $a = Commission::paymentSettlement('2018-02-03 00:00:00','2018-02-03 23:59:59');
        //     $b = Commission::savePaymentSettlement();
        //     dd($b);
        // }

        if($request->isDataTable){

            $eloquentData = WalletSettlement::select([
                'wallet_settlement.id',
                'wallet_settlement.wallet_id',
                'wallet_settlement.status',
                'wallet_settlement.from_date_time',
                'wallet_settlement.to_date_time',
                'wallet_settlement.num_success',
                'wallet_settlement.num_error',
                'wallet_settlement.created_at',
                // -- Wallet
                'wallet.walletowner_id',
                'wallet.walletowner_type'
            ])
                ->join('wallet','wallet.id','=','wallet_settlement.wallet_id')
                ->leftJoin('wallet as agent_wallet','agent_wallet.id','=','wallet_settlement.agent_wallet_id')
                ->with(['wallet'=>function($query){
                    $query->with('walletowner');
                }]);

            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }

            /*
             * Start handling filter
             */

            whereBetween($eloquentData,'wallet_settlement.created_at',$request->created_at1,$request->created_at2);

            if($request->id){
                $eloquentData->where('wallet_settlement.id', '=',$request->id);
            }

            if($request->status){
                $eloquentData->where('wallet_settlement.status', '=',$request->status);
            }

            whereBetween($eloquentData,'wallet_settlement.system_commission',$request->system_commission1,$request->system_commission2);
            whereBetween($eloquentData,'wallet_settlement.agent_commission',$request->agent_commission1,$request->agent_commission2);
            whereBetween($eloquentData,'wallet_settlement.merchant_commission',$request->merchant_commission1,$request->merchant_commission2);


            if($request->agent_id){
                $eloquentData->where('agent_wallet.walletowner_id','=',$request->agent_id);
            }

            if($request->wallet_id){
                $eloquentData->where('wallet_settlement.wallet_id', '=',$request->wallet_id);
            }

            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('walletowner',function($data){
                    return getWalletOwnerName($data->wallet,$this->systemLang);
                })
                ->addColumn('status','{{statusColor($status)}}')
                ->addColumn('from_date_time',function($data){
                    return '<table class="table">
                                <tbody>
                                    <tr>
                                        <td>'.__('From Date').'</td>
                                        <td>'.explode(' ',$data->from_date_time)[0].'</td>
                                    </tr>
                                    <tr>
                                        <td>'.__('To Date').'</td>
                                        <td>'.explode(' ',$data->to_date_time)[0].'</td>
                                    </tr>
                                    <tr>
                                        <td>'.__('Success Settlement').'</td>
                                        <td>'.$data->num_success.'</td>
                                    </tr>
                                    <tr>
                                        <td>'.__('Fail Settlement').'</td>
                                        <td>'.$data->num_error.'</td>
                                    </tr>
                                </tbody>
                            </table>';
                })
                ->addColumn('created_at',function($data){
                    return $data->created_at->diffForHumans();
                })
                ->addColumn('action',function($data){
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('system.settlement.show',[$data->id])."\">".__('View')."</a></li>
                              </ul>
                            </div>";
                })
                ->make(true);
        }else{
            // View Data
            $this->viewData['tableColumns'] = [
                __('ID'),
                __('Owner'),
                __('Status'),
                __('Report'),
                __('Created At'),
                __('Action')
            ];

            $this->viewData['breadcrumb'][] = [
                'text'=> __('Settlement')
            ];

            $this->viewData['walletUserType'] = WalletData::$ownerType;

            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Settlement');
            }else{
                $this->viewData['pageTitle'] = __('Settlement');
            }

            return $this->view('wallet.settlement.index',$this->viewData);
        }
    }

    public function show($ID,Request $request){

        if($request->isDataTable){

            $eloquentData = PaymentInvoice::with(['creatable','payment_transaction'])
                ->join('payment_transactions','payment_transactions.id','=','payment_invoice.payment_transaction_id')
                ->where('payment_invoice.wallet_settlement_id','=',$ID)
                ->select([
                    'payment_invoice.id',
                    'payment_invoice.payment_transaction_id',
                    'payment_invoice.creatable_id',
                    'payment_invoice.creatable_type',
                    'payment_invoice.total',
                    'payment_invoice.total_amount',
                    'payment_invoice.status',
                    'payment_invoice.wallet_settlement_data',
                    'payment_invoice.created_at',
                    'payment_invoice.updated_at'
                ]);

            return Datatables::eloquent($eloquentData)
                ->addColumn('details',function($data){
                    return ' ';
                })
                ->addColumn('id','{{$id}}')
                ->addColumn('payment_transaction_id',function($data){
                    return $data->payment_transaction_id;
                })
                ->addColumn('payment_services_id',function($data){
                    return '<a target="_blank" href="'.route('payment.services.show',$data->payment_transaction->payment_services->id).'">'.$data->payment_transaction->payment_services->{'name_'.$this->systemLang }.'</a>';
                })

                ->addColumn('status','{{$status}}')
                ->addColumn('created_at','{{explode(" ",$created_at)[0]}} <br /> {{explode(" ",$created_at)[1]}}')


                ->addColumn('data',function($data){
                    $return = '<table class="table table-condensed">
                            <tbody>
                              <tr>
                                <td>'.__('Total').'</td>
                                <td>'.amount($data->total).'</td>
                              </tr>
                              <tr>
                                <td>'.__('Total Amount').'</td>
                                <td>'.amount($data->total_amount).'</td>
                              </tr>';


                    if(!empty($data->wallet_settlement_data)){
                        $return.= '
                              <tr>
                                <td>'.__('System Commission').'</td>
                                <td>'.amount($data->wallet_settlement_data['system_commission']).'</td>
                              </tr>
                              <tr>
                                <td>'.__('Agent Commission').'</td>
                                <td>'.amount($data->wallet_settlement_data['agent_commission']).'</td>
                              </tr>
                              <tr>
                                <td>'.__('Merchant Commission').'</td>
                                <td>'.amount($data->wallet_settlement_data['merchant_commission']).'</td>
                              </tr>
                              <tr>
                                <td>'.__('DB System Commission').'</td>
                                <td>'.$data->wallet_settlement_data['DB_system_commission'].' '.iif($data->wallet_settlement_data['DB_charge_type'] == 'fixed','LE','%').'</td>
                              </tr>
                              <tr>
                                <td>'.__('DB Agent Commission').'</td>
                                <td>'.$data->wallet_settlement_data['DB_agent_commission'].' '.iif($data->wallet_settlement_data['DB_charge_type'] == 'fixed','LE','%').'</td>
                              </tr>
                              <tr>
                                <td>'.__('DB Merchant Commission').'</td>
                                <td>'.$data->wallet_settlement_data['DB_merchant_commission'].' '.iif($data->wallet_settlement_data['DB_charge_type'] == 'fixed','LE','%').'</td>
                              </tr>
                              ';
                    }

                    $return.='</tbody>
                          </table>';

                    return $return;
                })

                ->addColumn('action',function($data){
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('payment.invoice.show',$data->id)."\">".__('View')."</a></li>
                              </ul>
                            </div>";
                })
                ->make(true);

        }elseif($request->isWalletTransaction){
            $eloquentData = WalletTransaction::with(['fromWallet','toWallet','model'])
                    ->leftJoin('wallet as w_from','w_from.id','=','transactions.from_id')
                    ->leftJoin('wallet as w_to','w_to.id','=','transactions.to_id')
                    ->where('transactions.model_type','App\Models\WalletSettlement')
                    ->where('transactions.model_id',$ID)
                    ->select(['transactions.id','transactions.*']);

            $systemLang = $this->systemLang;

            return Datatables::eloquent($eloquentData)
                ->addColumn('details',function($data){
                    return ' ';
                })
                ->addColumn('id','{{$id}}')
                ->addColumn('model_id',function($data){
                    if(!$data->model){
                        return '--';
                    }else{
                        return WalletData::getModelTypeByModel($data->model_type) . ' ('.$data->model_id.') ';
                    }
                })
                ->addColumn('amount','{{amount($amount)}}')
                ->addColumn('created_at',function($data){
                    return $data->created_at->diffForHumans();
                })
                ->addColumn('type', function($data){
                    return __($data->type);
                })
                ->addColumn('status', function($data){
                    return __($data->status);
                })
                ->addColumn('action',function($data){
                    return "<button class=\"btn btn-primary\" type=\"button\" onclick='urlIframe(\"".route('system.wallet.transactions.show',$data->id)."\")'><i class=\"ft-eye\"></i></button>";
                })
                ->make(true);
        }


        $WalletSettlement = WalletSettlement::with(['wallet'=>function($query){
            $query->with('walletowner');
        }])->findOrFail($ID);

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Settlement'),
            'url'=> route('system.settlement.index')
        ];

        $this->viewData['tableColumns'] = ['#',__('ID'),__('T ID'),__('Service'),__('Status'),__('Date'),__('Data'),__('Action')];
        $this->viewData['tableColumnsWalletTransaction'] = [
            '#',
            __('ID'),
            __('Model'),
            __('Amount'),
            __('Created At'),
            __('Type'),
            __('Status'),
            __('Action')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Settlement #ID:').$ID
        ];

        $this->viewData['pageTitle'] = __('Settlement #ID:').' '.$ID;

        $this->viewData['result'] = $WalletSettlement;

        return $this->view('wallet.settlement.show',$this->viewData);
    }



    public function generateReport(Request $request){
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Wallet Settlement'),
            'url'=> route('system.settlement.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Generate Report'),
        ];

        $this->viewData['walletUserType'] = WalletData::$ownerType;
        $this->viewData['pageTitle'] = __('Generate Settlement Report');

        return $this->view('wallet.settlement.generate-report',$this->viewData);

    }
    public function generateReportPost(Request $request){
        $this->validate($request,[
            'created_at1'=> 'required|date_format:"Y-m-d H:i:s"',
            'created_at2'=> 'required|after_or_equal:"'.$request->created_at1.'"',
            'model_type'  => 'required'
        ]);



        // Handle Date Time
        $start = explode('-',explode(' ',$request->created_at1)[0]);
        $end   = explode('-',explode(' ',$request->created_at2)[0]);


        $startDate = Carbon::create($start[0],$start[1],$start[2]);
        $endDate   = Carbon::create($end[0],$end[1],$end[2]);

        $diffInMonths = $startDate->diffInMonths($endDate);

        $dates = [];
        if($diffInMonths > 0){
            // First Month
            $EOM = Carbon::create($start[0],$start[1],$start[2],23,59,59)->endOfMonth();
            $dates[] = [
                'from'  => $request->created_at1,
                'to'    => $EOM->toDateTimeString()
            ];

            $lastTo = $EOM;

            for ($i=1;$i<= $diffInMonths;$i++){
                $newMonthFrom = Carbon::create($lastTo->year,$lastTo->month,$lastTo->day,$lastTo->hour,$lastTo->minute,$lastTo->second)
                    ->addSecond();

                $dates[] = [
                    'from'  => $newMonthFrom->toDateTimeString(),
                    'to'    => $newMonthFrom->endOfMonth()->toDateTimeString()
                ];

                $newMonthTo = $newMonthFrom;
                $lastTo = $newMonthTo;
            }

            $dates[last(array_keys($dates))]['to'] = $request->created_at2;

        }else{
            $dates[] = [
                'from'  => $request->created_at1,
                'to'    => $request->created_at2
            ];
        }

        $getDataByDate = [];
        foreach ($dates as $key => $value) {
            $getDataByDate[] = [
                'from' => $value['from'],
                'to'   => $value['to']
            ];
        }


        $this->viewData['formData'] = $request->all();
        $this->viewData['result']   = $getDataByDate;


        $this->viewData['breadcrumb'][] = [
            'text'=> __('Wallet Settlement'),
            'url'=> route('system.settlement.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Generate Report'),
        ];
        $this->viewData['pageTitle'] = __('Settlement Report');


        return $this->view('wallet.settlement.generate-report-post',$this->viewData);

    }
    public function paymentSettlementAjax(Request $request){
        $validator = Validator::make($request->all(),[
            'from'       => 'required|date_format:"Y-m-d H:i:s"',
            'to'         => 'required|after_or_equal:"'.$request->from.'"',
            'model_type' => 'required'
        ]);

        if($validator->fails()){
            return ['status'=> false,'msg'=>__('Validation Error')];
        }


        $data = Commission::paymentSettlement($request->from,$request->to,$request->model_type,$request->model_id);

        //dd($data);

        $totalMerchantCommission = 0;
        $totalSystemCommission   = 0;
        if(isset($data['data']->settlement) && !empty($data['data']->settlement)){
            $totalMerchantCommission   = array_sum(array_column($data['data']->settlement,'merchant_commission'));
            $totalSystemCommission     = array_sum(array_column($data['data']->settlement,'system_commission'));
        }


        // Assign Data to Template
        $this->viewData['totalMerchantCommission'] = $totalMerchantCommission;
        $this->viewData['totalSystemCommission']   = $totalSystemCommission;

        $this->viewData['result'] = $data;

        return $this->view('wallet.settlement.generate-report-ajax',$this->viewData);


    }



}
