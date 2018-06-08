<?php

namespace App\Modules\Merchant;


use App\Libs\WalletData;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\DB;
use Auth;

class WalletController extends MerchantController
{

    protected $viewData;
    /*
    public function allTransactions(Request $request){
        $merchant = $request->user()->merchant();

        $this->viewData['pageTitle'] = __('Transactions');
        $this->viewData['trans'] = $merchant->wallet->allTransaction();
        $this->viewData['balance'] = $merchant->wallet->balance;
        $this->viewData['merchant'] = $merchant;

        return $this->view('wallet.info',$this->viewData);
    }
    */

    public function wallets(Request $request){
        if($request->isDataTable){

            $eloquentData = Auth::user()->merchant()->wallet()
                ->with('walletowner');

            /*
             * Start handling filter
             */

            whereBetween($eloquentData,'created_at',$request->created_at1,$request->created_at2);

            if($request->id){
                $eloquentData->where('id', '=',$request->id);
            }

            $SYSTEM_TOTAL = clone $eloquentData;
            $SYSTEM_TOTAL = $SYSTEM_TOTAL->select([DB::raw('SUM(`wallet`.`balance`) as `total`')])->first()->total;

            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('type',function($data){
                    if($data->type == 'payment'){
                        $return = '<lable class="label label-warning">';
                    }else{
                        $return = '<lable class="label label-danger">';
                    }

                    return $return.ucfirst($data->type).'</lable>';

                })
                ->addColumn('walletowner_id', function($data){
                    $Model = explode('\\',$data->walletowner_type);
                    $return = ucwords(str_replace('_', ' ', end($Model)));
                    return $return.'<br />'.getWalletOwnerName($data,$this->systemLang);
                })
                ->addColumn('balance','{{number_format($balance,2)}} {{__(\'LE\')}}')
                ->addColumn('updated_at',function($data){
                    if(empty($data->updated_at)){
                        return '--';
                    }
                    return $data->updated_at->diffForHumans();
                })
                ->addColumn('action',function($data){
                    return "<button class=\"btn btn-primary\" type=\"button\" onclick='location = \"".route('panel.merchant.wallet.transactions',$data->id)."\"'><i class=\"ft-eye\"></i></button>";
                })
                ->addColumn('SYSTEM-TOTAL',function($data) use ($SYSTEM_TOTAL) {
                    return amount($SYSTEM_TOTAL,true);
                })
                ->make(true);
        } else {
            // View Data
            $this->viewData['tableColumns'] = [__('ID'),__('Wallet Type'),__('Owner'),__('Balance'),__('Last Update'),__('Action')];


            $this->viewData['pageTitle'] = __('Wallets');

            $this->viewData['walletUserType'] = WalletData::$ownerType;

            return $this->view('wallet.index',$this->viewData);
        }
    }

    public function transactions($ID,Request $request){
        $wallet = Auth::user()->merchant()->wallet()->where('id','=',$ID)->first();
        if(!$wallet){
            $data = Auth::user()->merchant()->child()->with(['paymentWallet'=>function($sql)use($ID){
                $sql->where('wallet.id','=',$ID);
            }]);
            $subMerchant = $data->first();
            if($subMerchant){
                $wallet = $subMerchant->paymentWallet;
            }
        }
        if(!$wallet)
            return abort(404);
        if($request->isDataTable){
            $eloquentData = $wallet->allTransaction();
            /*
             * Start handling filter
             */
            whereBetween($eloquentData,'created_at',$request->created_at1,$request->created_at2);

            if($request->id){
                $eloquentData->where('id', '=',$request->id);
            }

            if($request->model_type){
                if($request->model_type == 'settlement'){
                    $eloquentData->where('transactions.model_type','=','App\Models\WalletSettlement');
                } else
                $eloquentData->where('model_type',WalletData::getModelTypeByModel($request->model_type));
            }

            if($request->model_id){
                $eloquentData->where('model_id',$request->model_id);
            }

            if($request->type){
                $eloquentData->where('type',$request->type);
            }

            if($request->status){
                $eloquentData->where('status',$request->status);
            }

            if($request->amount1 || $request->amount2){
                whereBetween($eloquentData,'amount',$request->amount1,$request->amount2);
            }

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
                ->addColumn('amount',function($data){
                    return amount($data->amount,true);
                })
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
                    return "<button class=\"btn btn-primary\" type=\"button\" onclick='urlIframe(\"".route('panel.merchant.wallet.transactions.show',$data->id)."\")'><i class=\"ft-eye\"></i></button>";
                })
                ->make(true);
        } else {
            $this->viewData['pageTitle'] = __('Show Wallet #ID:').' '.$wallet->id;
            $this->viewData['result'] = $wallet;

            $this->viewData['walletModelType'] = WalletData::$modelType;

            $this->viewData['tableColumns'] = ['#',__('ID'),__('Model'),__('Amount'),__('Created At'),__('Type'),__('Status'),__('Action')];

            $this->viewData['info'] = [
                'diffBetweenModelsType'=> array_chunk(WalletData::diffBetweenModelsType($wallet->id),2,true),
                'diffBetweenStatusType'=> array_chunk(WalletData::diffBetweenStatusType($wallet->id),2,true)
            ];
            return $this->view('wallet.show',$this->viewData);
        }
    }

    public function transactionShow($ID){
        $data = WalletTransaction::findOrFail($ID);
        $this->viewData['pageTitle'] = __('Transaction #ID:').$data->id;

        $this->viewData['result'] = $data;
        $this->viewData['WalletData'] = WalletData::class;
        return $this->view('wallet.transactionShow',$this->viewData);
    }



}