<?php

namespace App\Modules\Merchant;


use App\Models\Merchant;
use Illuminate\Http\Request;
use App\Models\AreaType;
use Auth;

class DashboardController extends MerchantController
{

    public function info(Request $request){

        $year = date('Y');
        $merchant = Merchant::where('id','=',$request->user()->merchant()->id)->withCount(['merchant_branch','merchant_products','payment_invoice'])
            ->with(['wallet'=>function($sql){
                $sql->withCount(['transactionFrom','transactionTo']);
            }])
            ->first();

        $merchant['transaction_from_count'] = array_sum(recursiveFind($merchant->wallet->toArray(),'transaction_from_count'));
        $merchant['transaction_to_count'] = array_sum(recursiveFind($merchant->wallet->toArray(),'transaction_to_count'));
        $merchant['transactions'] = $merchant['transaction_from_count'] + $merchant['transaction_to_count'];

        $this->viewData['pageTitle'] = __('Merchant Dashboard');
        $this->viewData['merchant'] = $merchant;
        $this->viewData['merchant_branches'] = $merchant->merchant_branch()
            ->select(['id','name_'.$this->systemLang.' AS name'])
            ->with(['orders'=>function($query){
                $query->select('id','merchant_branch_id');
                $query->selectRaw('SUM(total) AS orders_total');
                $query->selectRaw('CONCAT(YEAR(created_at),\'-\',MONTH(created_at)) AS date');
                $query->where('is_paid','=','yes');
                $query->groupBy('date');
            }])
            ->get();

        $this->viewData['merchant_invoice'] = request()->user()->merchant()
            ->select(['id'])
            ->where('id',$merchant->id)
            ->with(['payment_invoice'=>function($query){
                $query->select(['creatable_id','creatable_type','status']);
                $query->selectRaw('DATE_FORMAT(`created_at`,\'%b - %Y\') AS month');
                $query->selectRaw('SUM(total_amount) AS total_amount');
                $query->selectRaw('CONCAT(YEAR(created_at),\'-\',MONTH(created_at)) AS date');
                $query->groupBy(['date','status']);
            }])
            ->first()->payment_invoice;

        $this->viewData['appointment'] = Auth::user()->merchant()->appointment()->orderBy('created_at','DESC')->limit(1)->with('appointmentStatus')->first();

        $this->viewData['months'] = array_map(function($month)use($year){return $year.'-'.(($month<10)?'0'.$month:$month);},range(1,12));
        return $this->view('dashboard',$this->viewData);
    }

    public function edit(Request $request){
        $this->viewData['merchant'] = $request->user()->merchant();
        $this->viewData['pageTitle'] = __('Merchant Home');
        $this->viewData['areaData'] = AreaType::getFirstArea($this->systemLang);
        return $this->view('merchant.form',$this->viewData);
    }

    public function update(Request $request){
        $merchant = request()->user()->merchant();
        $RequestData = $request->only(['name_ar','name_en','description_ar','description_en','area_id','address']);
        $RequestData['area_id'] = getLastNotEmptyItem($RequestData['area_id']);

        if($merchant->update($RequestData)) {
            return redirect()->route('panel.merchant.edit')
                ->with('status','success')
                ->with('msg',__('Successfully edited Merchant Category'));
        }else{
            return redirect()->route('panel.merchant.edit')
                ->with('status','success')
                ->with('msg',__('Sorry Couldn\'t Edit Merchant Information'));;
        }
    }

    public function sendaudio(){

    }


}