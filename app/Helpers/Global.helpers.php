<?php


function databaseAmount($amount){
    $pos = strpos($amount,'.');
    if($pos === false){
        return $amount;
    }

    return substr($amount,0,$pos).substr($amount,$pos,3);
}


function setError($data,$model_type,$model_id,$msg = null,$type = 'error'){
    $create = \App\Models\ErrorLog::create([
        'model_type'=> $model_type,
        'model_id'=> $model_id,
        'type'=> $type,
        'data'=> $data,
        'msg'=> $msg
    ]);

    if($create){
        return true;
    }else{
        return false;
    }

}


function amount($amount,$format = false){
    if($format){
        return number_format($amount,2).' '.__('LE');
    }
    return $amount.' '.__('LE');
}

function humanStr($value){
    return __(ucwords(str_replace('_', ' ', $value)));
}

// Arrays Helpers

function arrayGetOnly(array $array,$only){
    if(empty($array)){
        return [];
    }else{
        $newData = [];
        if(is_array($only)){
            foreach ($only as $key => $value) {
                if(isset($array[$value])){
                    $newData[$value] = $array[$value];
                }
            }
        }elseif(is_string($only)){
            if(isset($array[$only])){
                $newData[$only] = $array[$only];
            }
        }else{
            return [];
        }

        return $newData;
    }
}

// Arrays Helpers




function listLangCodes(){
    return [
        'ar'=> 'العربية',
        'en'=> 'English'
    ];
}

function iif($conditions,$true = null,$false = null){
    if($conditions){
        if(is_object($true) && ($true instanceof Closure)){
            return $true();
        }else{
            return $true;
        }
    }else{
        if(is_object($false) && ($false instanceof Closure)){
            return $false();
        }else{
            return $false;
        }
    }
}


function whereBetween( &$eloquent,$columnName,$form,$to){
    if(!empty($form) && empty($to)){
        $eloquent->whereRaw("$columnName >= ?",[$form]);
    }elseif(empty($form) && !empty($to)){
        $eloquent->whereRaw("$columnName <= ?",[$to]);
    }elseif(!empty($form) && !empty($to)){
        $eloquent->where(function($query) use($columnName,$form,$to) {
            $query->whereRaw("$columnName BETWEEN ? AND ?",[$form,$to]);
        });
    }
}

function orWhereByLang(&$eloquent,$columnName,$value,$operator = 'like'){
    $eloquent->where(function($query) use($columnName,$value,$operator){
        $count = 0;
        foreach (listLangCodes() as $key => $langName) {

            if($count == 0){
                if($operator == 'like'){
                    $query->where("$columnName".'_'."$key",'LIKE','%'.$value.'%');
                }else{
                    $query->where("$columnName".'_'."$key",$operator,$value);
                }
            }else{
                if($operator == 'like'){
                    $query->orWhere("$columnName".'_'."$key",'LIKE','%'.$value.'%');
                }else{
                    $query->orWhere("$columnName".'_'."$key",$operator,$value);
                }
            }
            $count++;
        }
    });
}

function imageResize($imagePath,$width,$height){
    return (((strpos($imagePath,'public/'))=='0')?str_replace('public/','',$imagePath):$imagePath);

    if(Storage::exists($imagePath) || explode('/',File::mimeType(storage_path('app/public/'.$imagePath)))[0] == 'image' ){
        $resizedFileName = File::dirname($imagePath).'/'.File::name($imagePath).'_'.$width.'X'.$height.'.'.File::extension($imagePath);

        if(!Storage::exists($resizedFileName)){
            Image::make(storage_path().'/app/public/'.$imagePath)->resize($width,$height)->save(storage_path().'/app/public/'.$resizedFileName);
        }
        return $resizedFileName;
    }

    return false;
}


function image($imagePath,$width,$height){
    return imageResize($imagePath,$width,$height);
}




/*
 * @ $areaID : array or int
 */

function getLastNotEmptyItem($array){
    if(empty($array) || !is_array($array)){
        return false;
    }

    $last = end($array);
    if(empty($last)){
        $last = prev($array);
    }
    return $last;
}

function contactType($row){
    return __(ucfirst(str_replace('_',' ',$row->type)));
}


function contactValue($row){
    if($row->type == 'email'){
        return '<a href="mailto:'.$row->value.'">'.$row->value.'</a>';
    }else{
        return '<a href="tel:'.$row->value.'">'.$row->value.'</a>';
    }
}

function UniqueId(){
    return md5(str_random(20).uniqid().str_random(50).(time()*rand()));
}

function Base64PngQR($var,$size=false){
    $height = ((isset($size['0']))? $size['0']:'256');
    $width = ((isset($size['1']))? $size['1']:'256');
    $renderer = new \BaconQrCode\Renderer\Image\Png();
    $renderer->setHeight($height);
    $renderer->setWidth($width);
    $writer = new \BaconQrCode\Writer($renderer);
    return $writer->outputContent($var);
}


function setting($name,$returnAll = false){
    static $data;
    if($data == null){
        $getData = App\Models\Setting::get(['name','value'])->toArray();
        $data = array_column($getData,'value','name');
    }
    if($returnAll){
        return $data;
    }elseif(isset($data[$name])){
        $unserialize = @unserialize($data[$name]);
        if(is_array($unserialize)){
            return $unserialize;
        }
        return $data[$name];
    }

    return null;
}

function recursiveFind(array $array, $needle)
{
    $response = [];
    $iterator  = new RecursiveArrayIterator($array);
    $recursive = new RecursiveIteratorIterator(
        $iterator,
        RecursiveIteratorIterator::SELF_FIRST
    );
    foreach ($recursive as $key => $value) {
        if ($key === $needle) {
            $response[] = $value;
        }
    }
    return ((count($response)=='1')?$response:$response);
}

function response_to_object($array) {
    $obj = new stdClass;
    foreach($array as $k => $v) {
        if(strlen($k)) {
            if((is_array($v)) && count($v)) {
                $obj->{$k} = response_to_object($v); //RECURSION
            } elseif(($k == 'info') && (is_array($v))) {
                    $obj->{$k} = implode("\n",$v);
            } else {
                $obj->{$k} = $v;
            }
        }
    }
    return $obj;
}

function calcDim($width,$height,$maxwidth,$maxheight) {
    if($width != $height){
        if($width > $height){
            $t_width = $maxwidth;
            $t_height = (($t_width * $height)/$width);
            //fix height
            if($t_height > $maxheight)
            {
                $t_height = $maxheight;
                $t_width = (($width * $t_height)/$height);
            }
        } else {
            $t_height = $maxheight;
            $t_width = (($width * $t_height)/$height);
            //fix width
            if($t_width > $maxwidth){
                $t_width = $maxwidth;
                $t_height = (($t_width * $height)/$width);
            }
        }
    } else
        $t_width = $t_height = min($maxheight,$maxwidth);
    return array('height'=>(int)$t_height,'width'=>(int)$t_width);
}

function PaymentParamName($param,$lang){
    $paramData = \App\Models\PaymentServiceAPIParameters::where('external_system_id','=',explode('_',$param)['1'])->first();
    return $paramData->{'name_'.$lang};
}