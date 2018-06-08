<?php


// @TODO: Handle Status HTML
function statusColor($status){
    return $status;
}


function adminDefineUser($model,$id,$content){
    if($model == 'App\Models\Staff'){
        return '<a href="'.route('system.staff.show',[$id]).'">'.$content.'</a>';
    }elseif($model == 'App\Models\User'){
        return '<a href="'.route('system.user.show',[$id]).'">'.$content.'</a>';
    }else{
        return '<a href="'.route('merchant.staff.show',[$id]).'">'.$content.'</a>';
    }
}

function adminDefineUserWithName($model,$id,$lang){
    switch($model){
        case 'App\Models\MerchantStaff':
            $content = \App\Models\MerchantStaff::where('id','=',$id)->first()->Name;
            return '<a href="'.route('merchant.staff.show',[$id]).'">'.$content.'</a>';
        break;

        case 'App\Models\User':
            $content = \App\Models\User::where('id','=',$id)->first()->FullName;
            return '<a href="'.route('system.users.show',[$id]).'">'.$content.'</a>';
        break;

        case 'App\Models\Merchant':
            $content = \App\Models\User::Merchant('id','=',$id)->first()->{'name_'.$lang};
            return '<a href="'.route('merchant.merchant.show',[$id]).'">'.$content.'</a>';
        break;

        case 'App\Models\Staff':
        default:
            $content = \App\Models\Staff::where('id','=',$id)->first()->Fullname;
            return '<a href="'.route('system.staff.show',[$id]).'">'.$content.'</a>';
        break;

    }
}

function MerchantDefineUser($model,$id,$content){
    if($model == 'App\Models\Staff'){
        return $content;
    }elseif($model == 'App\Models\User'){
        return $content;
    }else{
        return '<a href="'.route('panel.merchant.employee.show',[$id]).'">'.$content.'</a>';
    }
}


function formError($error,$fieldName,$checkHasError = false){

    if($checkHasError){
        if($error->has($fieldName)){
            return ' has-danger';
        }else{
            return null;
        }
    }

    if($error->has($fieldName)){
        $return = '<p class="text-xs-left"><small class="danger text-muted">';

        foreach ($error->get($fieldName) as $errorMsg) {
            if(is_array($errorMsg)){
                $return .= implode(',',$errorMsg).'<br />';
            }else{
                $return .= $errorMsg.'<br />';
            }
        }
        $return .= '</small></p>';
        return $return;
    }else{
        return null;
    }

}

function generateMenu(array $array){
    $return = '';
    if(!isset($array['url'])){
        $array['url'] = '#';
    }

    if(!isset($array['icon'])){
        $array['icon'] = null;
    }

    if(!isset($array['class'])){
        $array['class'] = null;
    }

    if(!isset($array['aClass'])){
        $array['aClass'] = null;
    }


//    if(!empty($array['permission'])){
//        if(is_array($array['permission'])){
//            $oneTrue = false;
//            foreach($array['permission'] as $key => $value){
//                if(staffCan($value)){
//                    $oneTrue = true;
//                    break;
//                }
//            }
//
//            if(!$oneTrue){
//                return false;
//            }
//        }else{
//            if(!staffCan($array['permission'])){
//                return false;
//            }
//        }
//    }


    if(isset($array['permission'])){
        if(!staffCan($array['permission']))
            return false;
    }


    if(isset($array['permission']) && MenuRoute($array['permission'])){
        $array['class'] .= ' active';
    }


    $return.= '<li class="nav-item'.iif(!empty($array['class']),' '.$array['class']).'">
                <a '.iif(!empty($array['aClass']),'class="'.$array['aClass'].'"').' href="'.iif(!empty($array['url']),' '.$array['url']).'">
                    '.iif(!empty($array['icon']),'<i class="'.$array['icon'].'"></i>').'
                    <span data-i18n="" class="menu-title">'.$array['text'].'</span>';

    if(isset($array['count']) && !empty($array['count'])){
        $return.= '<span class="tag tag tag-primary tag-pill float-xs-right mr-2">'.$array['count'].'</span>';
    }

    $return.='</a>';

    if(isset($array['sub']) && !empty($array['sub'])){
        $return.= '<ul class="menu-content">';
        foreach ($array['sub'] as $key=> $value){
            $return.= generateMenu($value);
        }
        $return.= '</ul>';
    }

    $return.=  '</li>';
    return $return;

}

function GenerateHorizMenu(array $array, $sub=false){
    $data['class'] = ((isset($array['class']))?' '.$array['class']:'');
    $data['icon'] = ((isset($array['icon']))?' '.$array['icon']:'');
    $data['url'] = ((isset($array['url']))?$array['url']:'#');

    if(!$sub){
        $data['class'] = 'nav-item '.$data['class'];
        $data['data-menu'] = 'dropdown';
        $data['aclass'] = 'dropdown-toggle nav-link';
    } else {
        if(isset($array['sub']) && count($array['sub'])) {
            $data['class'] = 'dropdown-submenu ' . $data['class'];
            $data['data-menu'] = 'dropdown-submenu';
            $data['aclass'] = 'dropdown-item dropdown-toggle';
        } else {
            $data['class'] = '';
            $data['data-menu'] = '';
            $data['aclass'] = 'dropdown-item';
        }
    }

    if(isset($array['permission'])){
        if(!merchantcan($array['permission']))
            return false;
    }

    if(isset($array['url']) && MenuRoute($array['permission']))
        $data['class'] .= ' active';

    $menu = "<li data-menu='{$data['data-menu']}' class='dropdown {$data['class']}'>
            <a href='{$data['url']}' data-toggle='dropdown' class='{$data['aclass']}' ".((!$sub)?'aria-expanded="false"':null).">
                <i class='{$data['icon']}'></i><span>{$array['text']}</span>
            </a>";
    if(isset($array['sub']) && count($array['sub'])){
        $menu .= "<ul class='dropdown-menu'>";
            foreach($array['sub'] as $key=>$item){
                $menu .= GenerateHorizMenu($item,true);
            }
        $menu .= "</ul>";
    }
    $menu .= "</li>";
    return $menu;
}

function MenuRoute($routename){
    $requestRoute = request()->route()->getName();
    if(is_array($routename)){
        if(in_array($requestRoute,$routename)){
            return true;
        }
        return false;
    }

    return ($requestRoute == $routename) ? true : false;
}

function staffCan($routename,$staffId = null){
    if($staffId && $staffId == request()->user()->id){
        $staffId = null;
    }

    $userObj = $staffId ? \App\Models\Staff::where('id',$staffId)->first() : request()->user();

    static $permissions;
    if(is_null($permissions)){
        $permissions = \App\Models\Staff::StaffPerms($userObj->id)->toArray();
    }

    if(is_array($routename)) {
        $arr = array_diff($routename,$permissions);
        return (!$arr) ? true : ((count($arr) == count($routename))? false:true);
    } else {
        return (in_array($routename,$permissions)) ? true : false;
    }
}