<?php

namespace App\Modules\Merchant;

use App\Libs\WalletData;
use App\Models\Attribute;
use App\Models\AudioMessage;
use App\Models\MerchantBranch;
use App\Models\ProductAttribute;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\Merchant;
use App\Models\AreaType;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Advertisement;


class AjaxController extends MerchantController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function get(Request $request){

        switch ($request->type){
            case 'getAdvertisementAnalytics':
                $advertisement_id = $request->advertisement_id;
                $year = $request->year;
                $month = $request->month;
                $day = $request->day;
                if(!$year){
                    $year = date('Y');
                }

                $advertisement = Advertisement::where('id',$advertisement_id)->first();

                if(!$advertisement){
                    return ['status'=> false];
                }


                // -- ADV Analytics
                $analytics = collect($advertisement->analytics($advertisement->id,$year,$month,$day))->map(function($data){
                    $date = explode('-',explode(' ',$data->user_action_date_time)[0]);
                    $data->yearMonth = $date[0].'-'.$date[1];
                    $data->month = (int)$date[1];
                    return $data;
                });

                $clickAnalytics = collect($analytics)->where('user_action_type','=','click')->count();
                $viewAnalytics  = collect($analytics)->where('user_action_type','=','view')->count();

                // Gender
                $maleAnalytics   = collect($analytics)->where('gender','=','male')->count();
                $femaleAnalytics = collect($analytics)->where('gender','=','female')->count();
                $nullAnalytics   = collect($analytics)->where('gender','=',null)->count();

                $groupByDateClickAnalytics = collect($analytics)
                    ->where('user_action_type','=','click')
                    ->groupBy('month')
                    ->toArray();

                // ----
                $groupByDateViewAnalytics = collect($analytics)
                    ->where('user_action_type','=','view')
                    ->groupBy('month')
                    ->toArray();


                $monthsClick = [];
                if(!empty($groupByDateClickAnalytics)){
                    for($i=1;$i<=12;$i++){
                        if(isset($groupByDateClickAnalytics[$i])){
                            $monthsClick[$i] = count($groupByDateClickAnalytics[$i]);
                        }else{
                            $monthsClick[$i] = 0;
                        }
                    }
                }

                $monthsView = [];
                if(!empty($groupByDateViewAnalytics)){
                    for($i=1;$i<=12;$i++){
                        if(isset($groupByDateViewAnalytics[$i])){
                            $monthsView[$i] = count($groupByDateViewAnalytics[$i]);
                        }else{
                            $monthsView[$i] = 0;
                        }
                    }
                }
                // -- ADV Analytics

                return [
                    'status'=> true,
                    'year'=> $year,
                    'monthsView'=> $monthsView,
                    'monthsClick'=> $monthsClick,
                    'clickAnalytics'=> $clickAnalytics,
                    'viewAnalytics'=> $viewAnalytics,
                    'maleAnalytics'=> $maleAnalytics,
                    'femaleAnalytics'=> $femaleAnalytics,
                    'nullAnalytics'=> $nullAnalytics
                ];

            break;

            // MOSTAFA
            // finduser
            case 'user':
                $word = $request->word;
                $data = User::where('id','=',$word)
                    ->orwhere('firstname','LIKE','%'.$word.'%')
                    ->orwhere('lastname','LIKE','%'.$word.'%')
                    ->orwhere('email','LIKE','%'.$word.'%')
                    ->orwhere('mobile','LIKE','%'.$word.'%')
                    ->orwhere('national_id','=',$word)
                    ->get(['id','firstname','lastname']);

                $return = [];
                foreach ($data as $value) {
                    $return[] = [
                        'id'=> $value->id,
                        'value'=>  $value->firstname.' '.$value->lastname.' #ID:'.$value->id
                    ];
                }
                return $return;
            break;

            // MOSTAFA
            case 'getNextAreas':
                return \App\Libs\AreasData::getNextAreas($request->id,$this->systemLang);
                break;
            case 'staff':
                $word = $request->word;
                $data = Staff::where('name','LIKE','%'.$word.'%')
                    ->orWhere('email','LIKE','%'.$word.'%')
                    ->orWhere('mobile1','LIKE','%'.$word.'%')
                    ->get(['id','name']);

                $return = [];
                foreach ($data as $value) {
                    $return[] = [
                        'id'=> $value->id,
                        'value'=>  $value->name.' #ID:'.$value->id
                    ];
                }

                return $return;
            break;
            case 'merchant':
                $word = $request->word;
                $data = Merchant::where('name_ar','LIKE',"%$word%")
                    ->orWhere('name_en','LIKE',"%$word%")
                    ->orWhere('description_ar','LIKE',"%$word%")
                    ->orWhere('description_en','LIKE',"%$word%")
                    ->orWhere('id',$word)
                    ->get(['id',"name_{$this->systemLang} as name"]);

                $return = [];
                foreach ($data as $value) {
                    $return[] = [
                        'id'=> $value->id,
                        'value'=>  $value->name.' #ID:'.$value->id
                    ];
                }
                return $return;
            break;

            case 'productcategory':
                $word = $request->word;
                $data = Auth::user()->merchant()->merchant_product_catgories()->where(function($query) use ($word) {
                    $query->where('name_ar','LIKE',"%$word%")
                        ->orWhere('name_en','LIKE',"%$word%")
                        ->orWhere('description_ar','LIKE',"%$word%")
                        ->orWhere('description_en','LIKE',"%$word%")
                        ->orWhere('id',$word);
                    })
                    ->Active()
                    ->get(['id',"name_{$this->systemLang} as name"]);

                $return = [];
                foreach ($data as $value) {
                    $return[] = [
                        'id'=> $value->id,
                        'value'=>  $value->name.' #ID:'.$value->id
                    ];
                }
                return $return;
            break;

            case 'product':
                $catid = (int) $request->catid;

                $data = Auth::user()->merchant()->merchant_products()
                    ->select(['id',"merchant_products.name_{$this->systemLang} as name",'price'])
                    ->where('merchant_product_category_id','=',$catid)
                    ->withCount('attribute')
                    ->Active()
                    ->get();

                $return = [];
                foreach ($data as $value) {
                    $return[] = [
                        'id'=> $value->id,
                        'value'=>  $value->name.' #ID:'.$value->id,
                        'price'=>   $value->product_price(),
                        'attribute'=>$value->attribute_count
                    ];
                }

                return $return;
            break;

            case 'productAttributes':
                $proid = (int) $request->proid;
                $lang = $this->systemLang;
                $attributes = ProductAttribute::viewData($this->systemLang,[])->where('product_id','=',$proid)->get();

                $oldattributevalues = Attribute::whereIn('id',$attributes->pluck('attribute_id'))
                    ->with(['attributeValue'=>function($sqlQuery)use($lang){
                        $sqlQuery->select(['id','attribute_id','text_'.$lang.' as text','is_default']);
                    }])->get();

                return $attributes->groupBy('attribute_id');

            break;
            case 'customer':
                $word = $request->word;
                if(strlen($word < 11))
                    return;
                $data = User::where('mobile','=',$word)
                    ->orwhere('mobile','LIKE','%'.$word.'%')
                    ->get(['id','mobile'])->first();

                return $data;
            break;

            case 'getTransaction':
                $id       = $request->id;
                $ownerMD5 = $request->ownerMD5;

                $data = WalletTransaction::with(['model','fromWallet','toWallet','transactions_status'])->find($id);
                if(!$data){
                    return ['status'=> false];
                }
                $result = $data->toArray();
                if($data->fromWallet == null){
                    $result['fromType'] = ' -- ';
                    $result['fromName'] = ' -- ';
                }else{
                    $result['fromType'] = WalletData::getWalletOwnerType($data->fromWallet->walletowner_type);
                    if($ownerMD5 == md5($data->fromWallet->walletowner_id.$data->fromWallet->walletowner_type)){
                        $result['fromName'] = '<span style="color: red;">'.getWalletOwnerName($data->fromWallet,$this->systemLang).'</span>';
                    }else{
                        $result['fromName'] = getWalletOwnerName($data->fromWallet,$this->systemLang);
                    }
                }
                if($data->toWallet == null){
                    $result['toType'] = ' -- ';
                    $result['toName'] = ' -- ';
                }else{
                    $result['toType'] = WalletData::getWalletOwnerType($data->toWallet->walletowner_type);
                    if($ownerMD5 == md5($data->toWallet->walletowner_id.$data->toWallet->walletowner_type)){
                        $result['toName'] = '<span style="color: red;">'.getWalletOwnerName($data->toWallet,$this->systemLang).'</span>';
                    }else{
                        $result['toName'] = getWalletOwnerName($data->toWallet,$this->systemLang);
                    }
                }

                $result['modelName'] = WalletData::getModelTypeByModel($data->model_type);
                $result['updated_at'] = $data->updated_at->diffForHumans();

                if($result['modelName'] == 'invoice'){
                    $result['payment_services_name'] = $data->model_data->payment_services->{'name_'.$this->systemLang};
                }else{
                    // @TODO: order
                    //$result['payment_services_name'] = $data->model->payment_services->{'name_'.$this->systemLang};
                }
                return ['status'=> true,'data'=>$result];
            br.eak;
        }
    }


    public function post(Request $request){
        switch ($request->type){
            case 'audio-msg':
                $data = substr($request->data, strpos($request->data, ",") + 1);
                $decodedData = base64_decode($data);

                $filename = 'audio-messages/'.date('Y-m-d').'/'.Auth::user()->merchant()->id.'/'.Auth::id().'_'.date('h-i-s').'_Audio_Msg.mp3';
                if(Storage::put($filename, $decodedData)){
                    AudioMessage::create([
                        'path'                  =>  $filename,
                        'msgsendermodel_id'     =>  Auth::id(),
                        'msgsendermodel_type'   =>  get_class(Auth::user()),
                    ]);
                }

                return ['status'=> true,'msg'=> __('Your Message has been sent')];

            break;

            case 'getLanguage':
                $keys = $request->Langkeys;
                $newkeys = array_column(array_map(function($val){
                    return ['key'=>$val,'value'=>__($val)];
                },$keys),'value','key');
                return response()->json(array_merge($newkeys,['langCode'=>$this->systemLang]));
            break;
        }
    }

}
