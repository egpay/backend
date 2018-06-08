<?php

namespace App\Modules\Merchant;

use App\Libs\Payments\Payments;
use App\Libs\WalletData;
use App\Models\PaymentInvoice;
use App\Models\PaymentTransactions;
use Illuminate\Support\Facades\Validator;
use App\Models\PaymentServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Yajra\Datatables\Facades\Datatables;
use Auth;

class PaymentInvoiceController extends MerchantController
{

    protected $viewData;

    public function index(Request $request){
        $merchant = Auth::user()->merchant();
        if($request->isDataTable){
            $eloquentData = $merchant->payment_invoice()->with(['creatable','payment_transaction'=>function($sql){
                $sql->with('model');
            }])
                ->join('payment_transactions','payment_transactions.id','=','payment_invoice.payment_transaction_id')
                ->select([
                    'payment_invoice.id',
                    'payment_invoice.payment_transaction_id',
                    'payment_invoice.creatable_id',
                    'payment_invoice.creatable_type',
                    'payment_invoice.total',
                    'payment_invoice.total_amount',
                    'payment_invoice.status',
                    'payment_invoice.created_at',
                    'payment_invoice.updated_at',
                    'payment_transactions.model_id',
                ]);


            whereBetween($eloquentData,'payment_invoice.created_at',$request->created_at1,$request->created_at2);

            if($request->id){
                $eloquentData->where('payment_invoice.id','=',$request->id);
            }

            if($request->payment_transaction_id){
                $eloquentData->where('payment_invoice.payment_transaction_id','=',$request->payment_transaction_id);
            }

            if($request->payment_services_id){
                $eloquentData->where('payment_transactions.payment_services_id','=',$request->payment_services_id);
            }

            if($request->status){
                $eloquentData->where('payment_invoice.status','=',$request->status);
            }

            if($request->model_id){
                $eloquentData->where('payment_transactions.model_id','=',$request->model_id);
            }


            whereBetween($eloquentData,'payment_transactions.amount',$request->total1,$request->total2);
            whereBetween($eloquentData,'payment_transactions.total_amount',$request->total_amount1,$request->total_amount2);


            $SYSTEM_TOTAL = clone $eloquentData;
            $SYSTEM_TOTAL = $SYSTEM_TOTAL
                ->select([DB::raw('SUM(`payment_invoice`.`total`) as `system_total`')])
                ->first()
                ->system_total;

            $SYSTEM_TOTAL_AMOUNT = clone $eloquentData;
            $SYSTEM_TOTAL_AMOUNT = $SYSTEM_TOTAL_AMOUNT
                ->select([DB::raw('SUM(`payment_invoice`.`total_amount`) as `system_total`')])
                ->first()
                ->system_total;
            $class = $this;
            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('payment_transaction_id',function($data){
                    return $data->payment_transaction_id;
                })
                ->addColumn('payment_services_id',function($data){
                    return $data->payment_transaction->payment_services->{'name_'.$this->systemLang };
                })
                ->addColumn('creatable_id',function($data){
                    return $data->payment_transaction->model->name;
                })
                ->addColumn('total','{{amount($total)}}')
                ->addColumn('total_amount','{{amount($total_amount)}}')
                ->addColumn('status','{{$status}}')
                ->addColumn('created_at','{{explode(" ",$created_at)[0]}} <br /> {{explode(" ",$created_at)[1]}}')
                ->addColumn('action',function($data){
                    return "<button class=\"btn btn-primary\" type=\"button\" onclick='urlIframe(\"".route('panel.merchant.payment.invoice.show',$data->id)."\")'><i class=\"ft-eye\"></i></button>";
                })
                 ->addColumn('reprint',function($data)use($class){
                     if($data['status']=='paid') {
                         return '<div class="hidden" id="invoice'.$data->id.'">'.$class->reprint($data->payment_transaction_id,true).'</div>
                            <button class="btn btn-primary" type="button" onclick="$(\'#invoice'.$data->id.' #bill\').print()"><i class="ft-printer"></i></button>';
                     } else
                         return '';
                })
                ->addColumn('SYSTEM-TOTAL',function($data) use ($SYSTEM_TOTAL) {
                    return amount($SYSTEM_TOTAL,true);
                })
                ->addColumn('SYSTEM-TOTAL-AMOUNT',function($data) use ($SYSTEM_TOTAL_AMOUNT) {
                    return amount($SYSTEM_TOTAL_AMOUNT,true);
                })
                ->make(true);
        }else{
            // View Data
            $this->viewData['tableColumns'] = [__('ID'),__('T ID'),__('Service'),__('User'),__('Total'),__('Total Amount'),__('Status'),__('Date'),__('Action'),__('reprint')];
            $this->viewData['breadcrumb'][] = [
                'text'=> __('Invoice')
            ];


            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Payment Invoices');
            }else{
                $this->viewData['pageTitle'] = __('Payment Invoices');
            }

            $this->viewData['paymentServices'] = PaymentServices::get(['id','name_'.$this->systemLang.' as name']);
            $this->viewData['merchantStaffs'] = $merchant->MerchantStaff()->select(DB::raw("CONCAT(`firstname`,' ',`lastname`) as fullName"),'merchant_staff.id')->pluck('fullName','id')
                ->reverse()->put('0',__('Select staff'))->reverse()->toArray();


            return $this->view('payment.invoice.index',$this->viewData);
        }
    }


    public function show(PaymentInvoice $invoice){
        $this->viewData['pageTitle'] = __('Invoice #ID:').' '.$invoice->id;
        $this->viewData['tableColumns'] = ['#',__('ID'),__('T ID'),__('Service'),__('Status'),__('Date'),__('Data'),__('Action')];
        $this->viewData['result'] = $invoice;
        $this->viewData['lang'] = $this->systemLang;

        return $this->view('payment.invoice.show',$this->viewData);
    }



    private function reprint($ID,$repeated=false){
        $data = PaymentTransactions::findOrFail($ID);
        $adapter = Payments::selectAdapterByService($data->payment_services_id);
        $response = $adapter::ReviewTransaction($data);

        $this->viewData['lang'] = $this->systemLang;
        $response['data']->service_info['merchant_id'] = $data->model()->first()->merchant()->id.'-'.$data->model()->first()->id;
        $response['data']->repeated = $repeated;
        $this->viewData['data'] = $response['data'];
        //return View::make('payment.invoice._receipt',$this->viewData)->render();

        return $this->view('payment.invoice._receipt',$this->viewData)->render();
        return $this->view('payment.invoice._receipt',$this->viewData);

    }



}