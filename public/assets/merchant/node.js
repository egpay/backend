// LARAVEL CSRF
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    error: function(jqXHR,textStatus,errorThrown){
        toastr.error(errorThrown, 'Error !', {"closeButton": true});
    }
});

var ajaxRequestUrl = $('meta[name="ajax-post"]').attr('content');

function __(key){
    if(key in lang){
        return lang[key];
    } else {
        return key;
    }
}


function empty (mixedVar) {
    //  discuss at: http://locutus.io/php/empty/
    // original by: Philippe Baumann
    //    input by: Onno Marsman (https://twitter.com/onnomarsman)
    //    input by: LH
    //    input by: Stoyan Kyosev (http://www.svest.org/)
    // bugfixed by: Kevin van Zonneveld (http://kvz.io)
    // improved by: Onno Marsman (https://twitter.com/onnomarsman)
    // improved by: Francesco
    // improved by: Marc Jansen
    // improved by: Rafał Kukawski (http://blog.kukawski.pl)
    //   example 1: empty(null)
    //   returns 1: true
    //   example 2: empty(undefined)
    //   returns 2: true
    //   example 3: empty([])
    //   returns 3: true
    //   example 4: empty({})
    //   returns 4: true
    //   example 5: empty({'aFunc' : function () { alert('humpty'); } })
    //   returns 5: false
    var undef
    var key
    var i
    var len
    var emptyValues = [undef, null, false, 0, '', '0']
    for (i = 0, len = emptyValues.length; i < len; i++) {
        if (mixedVar === emptyValues[i]) {
            return true
        }
    }
    if (typeof mixedVar === 'object') {
        for (key in mixedVar) {
            if (mixedVar.hasOwnProperty(key)) {
                return false
            }
        }
        return true
    }
    return false
}

function formSubmit($formID,$url,$method,$errorPrefix,$done){
    if($method == 'post'){
        $method = 'post';
        $.post($url,$($formID).serialize(),function($data){
            if($data.status == false && $data.type  == 'validation'){
                $error = '<div class="alert alert-danger">';
                $.each($data.data,function($key,$value){
                    $.each($value,function($key1,$value1){
                        $error+= $key+': '+$value1+"<br />";
                    });
                });
                $error+= '</div>';

                $('#'+$errorPrefix+'_error').html($error);
                $('#'+$errorPrefix+'_error').show();

                $done(false);
            }else if($data.status == false){
                $error = '<div class="alert alert-danger">';
                $error+= $data.data.error;
                $error+= '</div>';

                $('#'+$errorPrefix+'_error').html($error);
                $('#'+$errorPrefix+'_error').show();

                $done(false);
            }else{
                $('#'+$errorPrefix+'_error').html('');
                $('#'+$errorPrefix+'_error').hide();

                $done($data);
            }
        },'json');
    }else{
        $method = 'get';
        $.get($url,$($formID).serialize(),function($data){
            if($data.status == false && $data.type  == 'validation'){

                $error = '<div class="alert alert-danger">';
                $.each($data.data,function($key,$value){
                    $.each($value,function($key1,$value1){
                        $error+= $key+': '+$value1+"<br />";
                    });
                });
                $error+= '</div>';

                $('#'+$errorPrefix+'_error').html($error);
                $('#'+$errorPrefix+'_error').show();

                $done(false);
            }else{
                $('#'+$errorPrefix+'_error').html('');
                $('#'+$errorPrefix+'_error').hide();

                $done($data);
            }
        },'json');
    }




}

function deleteRecord($routeName,$reload){

    if(!confirm("Do you want to delete this ?")){
        return false;
    }

    if($reload == undefined){
        $reload = 3000;
    }

    $.post(
        $routeName,
        {
            '_method':'DELETE',
            '_token':$('meta[name="csrf-token"]').attr('content'),
            'ajax':true
        },
        function(response){
            //console.log(response);
            if(isJSON(response)){
                $data = response;
                if($data.status == true){
                    toastr.success($data.msg, 'Success !', {"closeButton": true});
                    if($reload){
                        setTimeout(function(){location.reload();},$reload);
                    }
                }else{
                    toastr.error($data.msg, 'Error !', {"closeButton": true});
                }
            }
        }
    )
}

function AjaxRequest(lang,$routeName,$reload){
    if(!confirm(lang['confirm'])){
        return false;
    }
    if($reload == undefined){
        $reload = 3000;
    }
    $.post($routeName,{
            '_token':$('meta[name="csrf-token"]').attr('content'),
            'ajax':true
        },
        function(response){
            if(isJSON(response)){
                $data = response;
                if($data.status == true){
                    toastr.success($data.msg, lang['success'], {"closeButton": true});
                    if($reload){
                        setTimeout(function(){location.reload();},$reload);
                    }
                }else{
                    toastr.error($data.msg, lang['error'], {"closeButton": true});
                }
            }
        }
    )
}

function isJSON(m) {
    if (typeof m == 'object') {
        try{ m = JSON.stringify(m); }
        catch(err) { return false; } }

    if (typeof m == 'string') {
        try{ m = JSON.parse(m); }
        catch (err) { return false; } }

    if (typeof m != 'object') { return false; }
    return true;

};


function getNextAreas($id,$typeID,$attrID,$selected){
    $.getJSON(ajaxRequestUrl,{
        'type': 'getNextAreas',
        'id': $id
    },function(response){
        //console.log(response);
        if(response.type != false){
            $select = new Array;

            $select.push('<label for="area_id">'+response.type.name+'</label>');

            $select.push('<select id="area_id_my_type_'+response.type.id+'" name="area_id[]" onchange="getNextAreas($(this).val(),'+response.type.id+',\''+$attrID+'\')" class="form-control">');
            $select.push('<option value="">Select '+response.type.name+'</option>');

            $.each(response.areas,function(key,value){
                if($selected == value.id){
                    $select.push('<option selected="selected" value="'+value.id+'">'+value.name+'</option>');
                }else{
                    $select.push('<option value="'+value.id+'">'+value.name+'</option>');
                }
            });

            $select.push('</select>');

            if($('#divAreaID_'+response.type.id).attr('egpay') == 'select'){
                $('#divAreaID_'+response.type.id).html(
                    $select.join("\n")
                );

                $('[egpay="select"]').each(function(){
                    $catID = ($(this).attr('id')).replace('divAreaID_','');
                    if($catID > response.type.id){
                        $(this).remove();
                    }
                });
            }else{
                $($attrID).append(
                    '<fieldset egpay="select" id="divAreaID_'+response.type.id+'" class="form-group">'+
                    $select.join("\n")+
                    '</fieldset>'
                );
            }
        }else{
            $('[egpay="select"]').each(function(){
                $catID = ($(this).attr('id')).replace('divAreaID_','');
                console.log($typeID);
                if($catID > $typeID){
                    $(this).remove();
                }
            });
        }

        if(isset($runAreaLoop) && $runAreaLoop == true){
            if(isset($areaLoopData[$typeID])){
                $getSelectID = null;
                $nextTypeID = null;
                $nextAreaID = null;

                $areaLoopData.forEach(function(value,key){
                    if(key == $typeID){
                        $getSelectID = true;
                    }else if($getSelectID === true){
                        $nextTypeID = key;
                        $nextAreaID = value;
                        $getSelectID = null;
                    }
                });


                if($nextTypeID != null){
                    $('#area_id_my_type_'+$nextTypeID).val($nextAreaID).change();
                }else{
                    $runAreaLoop = false;
                }

            }

        }

    });

}

function staffSelect($formID){
    return ajaxSelect2($formID,'staff');
}

function ajaxSelect2($formID,$controllerFunction,chars){
    var chars = ((chars == 'undefined') ? 1:chars);
    $($formID).select2({
        ajax: {
            method: 'GET',
            url: ajaxRequestUrl,
            dataType: 'json',
            delay: 1000,
            data: function (params) {

                return {
                    type : $controllerFunction,
                    word: params.term
                };
            },
            processResults: function (data, params) {
                params.page = params.page || chars;
                return {
                    results: data,
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
        minimumInputLength: chars,
        templateResult: function (data) {
            if (data.loading === true) { // adjust for custom placeholder values
                return data.text;
            }
            return data.value;
        },
        templateSelection: function(data){
            if(data.text != ''){
                return data.text;
            }
            return data.id;
        }
    });

}


function OptsSelect2($formID,opts){
    $($formID).select2({
        ajax: {
            method: 'GET',
            url: ajaxRequestUrl,
            dataType: 'json',
            delay: 500,
            data: function (params) {
                opts.word = params.term;
                return opts;
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                return {
                    results: data,
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
        minimumInputLength: 1,
        templateResult: function (data) {
            if (data.loading === true) { // adjust for custom placeholder values;
                return data.text;
            }
            return data.value;
        },
        templateSelection: function(data, container){
            if(data.text != ''){
                return data.text;
            }
            return data.value;
        }
    });
}






// PHP.JS Functions
function isset () {
    //  discuss at: http://locutus.io/php/isset/
    // original by: Kevin van Zonneveld (http://kvz.io)
    // improved by: FremyCompany
    // improved by: Onno Marsman (https://twitter.com/onnomarsman)
    // improved by: Rafał Kukawski (http://blog.kukawski.pl)
    //   example 1: isset( undefined, true)
    //   returns 1: false
    //   example 2: isset( 'Kevin van Zonneveld' )
    //   returns 2: true
    var a = arguments
    var l = a.length
    var i = 0
    var undef
    if (l === 0) {
        throw new Error('Empty isset')
    }
    while (i !== l) {
        if (a[i] === undef || a[i] === null) {
            return false
        }
        i++
    }
    return true
}



function current (arr) {
    //  discuss at: http://locutus.io/php/current/
    // original by: Brett Zamir (http://brett-zamir.me)
    //      note 1: Uses global: locutus to store the array pointer
    //   example 1: var $transport = ['foot', 'bike', 'car', 'plane']
    //   example 1: current($transport)
    //   returns 1: 'foot'
    var $global = (typeof window !== 'undefined' ? window : global)
    $global.$locutus = $global.$locutus || {}
    var $locutus = $global.$locutus
    $locutus.php = $locutus.php || {}
    $locutus.php.pointers = $locutus.php.pointers || []
    var pointers = $locutus.php.pointers
    var indexOf = function (value) {
        for (var i = 0, length = this.length; i < length; i++) {
            if (this[i] === value) {
                return i
            }
        }
        return -1
    }
    if (!pointers.indexOf) {
        pointers.indexOf = indexOf
    }
    if (pointers.indexOf(arr) === -1) {
        pointers.push(arr, 0)
    }
    var arrpos = pointers.indexOf(arr)
    var cursor = pointers[arrpos + 1]
    if (Object.prototype.toString.call(arr) === '[object Array]') {
        return arr[cursor] || false
    }
    var ct = 0
    for (var k in arr) {
        if (ct === cursor) {
            return arr[k]
        }
        ct++
    }
    // Empty
    return false
}

function next (arr) {
    //  discuss at: http://locutus.io/php/next/
    // original by: Brett Zamir (http://brett-zamir.me)
    //      note 1: Uses global: locutus to store the array pointer
    //   example 1: var $transport = ['foot', 'bike', 'car', 'plane']
    //   example 1: next($transport)
    //   example 1: next($transport)
    //   returns 1: 'car'
    var $global = (typeof window !== 'undefined' ? window : global)
    $global.$locutus = $global.$locutus || {}
    var $locutus = $global.$locutus
    $locutus.php = $locutus.php || {}
    $locutus.php.pointers = $locutus.php.pointers || []
    var pointers = $locutus.php.pointers
    var indexOf = function (value) {
        for (var i = 0, length = this.length; i < length; i++) {
            if (this[i] === value) {
                return i
            }
        }
        return -1
    }
    if (!pointers.indexOf) {
        pointers.indexOf = indexOf
    }
    if (pointers.indexOf(arr) === -1) {
        pointers.push(arr, 0)
    }
    var arrpos = pointers.indexOf(arr)
    var cursor = pointers[arrpos + 1]
    if (Object.prototype.toString.call(arr) !== '[object Array]') {
        var ct = 0
        for (var k in arr) {
            if (ct === cursor + 1) {
                pointers[arrpos + 1] += 1
                return arr[k]
            }
            ct++
        }
        // End
        return false
    }
    if (arr.length === 0 || cursor === (arr.length - 1)) {
        return false
    }
    pointers[arrpos + 1] += 1
    return arr[pointers[arrpos + 1]]
}

function urlIframe($url,$headerTitle){

    $url = $url+"?without_navbar=true";

    $('#modal-iframe-url').height(($(window).height()-150)+"px");
    $('#modal-iframe-width').css('max-width',($(window).width()-100)+"px");

    $('#modal-iframe-title').text($headerTitle);


    $('#modal-iframe').modal('show');

    $('#modal-iframe-url').hide();

    $('#modal-iframe-url').attr('src',$url);
    $('#modal-iframe-image').show();

    $('#modal-iframe-url').load(function(){
        $('#modal-iframe-image').hide();
        $('#modal-iframe-url').show();
    });
}


function doGetAction(route){
    $.getJSON(route,{},function(data){
        if(data.status){
            toastr.success(data.msg, data.title, {"closeButton": true});
        } else {
            toastr.error(data.msg, data.title, {"closeButton": true});
        }
    });
}


function doPostAction(route,params){
    $.post(route,params,function(data){
        if(data.status){
            toastr.success(data.msg, data.title, {"closeButton": true});
        } else {
            toastr.error(data.msg, data.title, {"closeButton": true});
        }
    },'json');
}