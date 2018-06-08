function __(key){
    if(($.isArray(lang)) && (key in lang)){
        return lang[key];
    } else {
        return key;
    }
}

// RUN TIME JAVASCRIPT CODES

var ajaxRequestUrl = $('meta[name="ajax-post"]').attr('content');

// Disable button after submit
$('form').submit(function() {
    // $(this).find("button[type='submit']").not('.without-loading').val('Loading...').prop('disabled',true);
    // $(this).find("input[type='submit']").not('.without-loading').val('Loading...').prop('disabled',true);
});
// Disable button after submit

$(document).ready(function(){

    // SENDER MODEL
   $('#sender_type').change(function(){
       if($(this).val() == 'sms'){
           $('#sender-email-div').hide();
           $('#sender-sms-div').show();
       }else{
           $('#sender-sms-div').hide();
           $('#sender-email-div').show();
       }
   });

    CKEDITOR.replace('sender_email_body');

    $('#sender_sms_body').smsArea({
        counters: {
            message: $('#sender_sms_body'),
            character: $('#sender-sms_length')
        }
    });

    $('#sender-form').submit(function(){
        $('#sender-type-error,#sender-send_to-error,#sender-sms_body-error,#sender-from_name-error,#sender-from_email-error,#sender-subject-error,#sender-email_body-error,#sender-file-error').html('');

        $(this).ajaxSubmit({
            success:function(response){
                if(response.status == true){
                    toastr.success(response.msg, 'Success', {"closeButton": true});
                }else{
                    toastr.error(response.msg, 'Error !', {"closeButton": true});
                }
                $('#sender-form')[0].reset();
                $('#sender-modal').modal('hide');

            },
            error: function(jqXHR,textStatus,errorThrown){
                toastr.error(textStatus, 'Error !', {"closeButton": true});
                $errors = jqXHR.responseJSON;
                $.each($errors,function($key,$value){
                    $errorMsgForOneField = '';
                    $.each($value,function($mKey,$mValue){
                        $errorMsgForOneField += $mValue+'<br />';
                    });
                    console.log($key);
                    $('#sender-'+$key+'-error').html($errorMsgForOneField);
                });
            }
        });


    });




});

// RUN TIME JAVASCRIPT CODES

function alertSuccess($msg){
    toastr.success($msg, 'Success', {"closeButton": true});
}

function alertError($msg){
    toastr.error($msg, 'Error', {"closeButton": true});
}


function getSystemLoadAVG($callback){
    $.get('/system/ajax',{
        'type': 'system_load_avg'
    },function($data){
        $callback($data);
    });
}

function get_transaction_data($url){
    $('#transaction-div').html('');
    $.getJSON($url,function($data){
        $result = '<table class="table" style="width: 100%;" cellspacing="0" border="0">';

        if(!empty($data.parameter)){
            $result+= '<tr style="background: aliceblue;text-align: center;">'+
                '<td colspan="2">Request Map</td>'+
            '</tr>';

            $.each($data.parameter,function($key,$value){
                $result+= '<tr>'+
                    '<td>'+$data.parameterData[$key]+'</td>'+
                    '<td>'+ $value +'</td>'+
                    '</tr>';
            });
        }

        if(!empty($data.response)){
            $result+= '<tr style="background: aliceblue;text-align: center;">'+
                '<td colspan="2">Response</td>'+
            '</tr>';

            $.each($data.response,function($key,$value){
                if(typeof $value === "object" && !Array.isArray($value) && $value !== null){
                    $.each($value,function($key1,$value1){
                        if(typeof $value1 === "object" && !Array.isArray($value1) && $value1 !== null) {
                            $.each($value1, function ($key2, $value2) {
                                $result += '<tr>' +
                                    '<td>' + $key2 + '</td>' +
                                    '<td>' + $value2 + '</td>' +
                                    '</tr>';
                            });
                        }else{
                            $result+= '<tr>'+
                                '<td>'+$key1+'</td>'+
                                '<td>'+ $value1 +'</td>'+
                                '</tr>';
                        }

                    });
                }else{
                    $result+= '<tr>'+
                        '<td>'+$key+'</td>'+
                        '<td>'+ $value +'</td>'+
                        '</tr>';
                }

            });
        }

        $result+= '</table>';

        $('#transaction-div').html($result);
        $('#transaction-model').modal('show');

    });

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


function viewMap($latitude,$longitude,$title){
    $('#instructions').html('');
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position){

            $('#modal-map').modal('show');
            $('#modal-map').on('shown.bs.modal', function (e) {
                $latitudeMe = position.coords.latitude;
                $longitudeMe = position.coords.longitude;
                map = new GMaps({
                    div: '#map',
                    lat: $latitudeMe,
                    lng: $longitudeMe
                });

                map.addMarker({
                    lat: $latitude,
                    lng: $longitude,
                    infoWindow: {
                        content: $title
                    }
                });

                map.addMarker({
                    lat: $latitudeMe,
                    lng: $longitudeMe,
                    infoWindow: {
                        content: "{{__('My Location')}}"
                    }
                });

                map.travelRoute({
                    origin: [$latitudeMe, $longitudeMe],
                    destination: [$latitude, $longitude],
                    travelMode: 'driving',
                    step: function(e){
                        $('#instructions').append('<li class="list-group-item">'+e.instructions+'</li>');
                        $('#instructions li:eq('+e.step_number+')').delay(450*e.step_number).fadeIn(200, function(){
                            map.setCenter(e.end_location.lat(), e.end_location.lng());
                            map.drawPolyline({
                                path: e.path,
                                strokeColor: '#131540',
                                strokeOpacity: 0.6,
                                strokeWeight: 6
                            });
                        });
                    }
                });
            });

        },function () {
            $('#modal-map').modal('show');
            $('#modal-map').on('shown.bs.modal', function (e) {
                map = new GMaps({
                    div: '#map',
                    lat: $latitude,
                    lng: $longitude
                });

                map.addMarker({
                    lat: $latitude,
                    lng: $longitude,
                    infoWindow: {
                        content: $title
                    }
                });
            });
        });
    } else {
        $('#modal-map').modal('show');
        $('#modal-map').on('shown.bs.modal', function (e) {
            map = new GMaps({
                div: '#map',
                lat: $latitude,
                lng: $longitude
            });

            map.addMarker({
                lat: $latitude,
                lng: $longitude,
                infoWindow: {
                    content: $title
                }
            });
        });
    }
}

function adminDefineUser($model,$id,$content){
    if($model == 'App\\Models\\Staff'){
        return '<a href="'+route('staff/'+$id)+'">'+$content+'</a>';
    }else if($model == 'App\\Models\\User'){
        return '<a href="'+route('users'+$id)+'">'+$content+'</a>';
    }else{
        return '<a href="'+route('merchant/staff/'+$id)+'">'+$content+'</a>';
    }
}


function route($route){
    return '/system/'+$route;
}


// LARAVEL CSRF
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    error: function(jqXHR,textStatus,errorThrown){
        toastr.error(errorThrown, 'Error !', {"closeButton": true});
    }
});

(function($){
    $.fn.smsArea = function(options){

        var
            e = this,
            cutStrLength = 0,

            s = $.extend({

                cut: true,
                maxSmsNum: 3,
                interval: 400,

                counters: {
                    message: $('#smsCount'),
                    character: $('#smsLength')
                },

                lengths: {
                    ascii: [160, 306, 459],
                    unicode: [70, 134, 201]
                }
            }, options);


        e.keyup(function(){

            clearTimeout(this.timeout);
            this.timeout = setTimeout(function(){

                var
                    smsType,
                    smsLength = 0,
                    smsCount = -1,
                    charsLeft = 0,
                    text = e.val(),
                    isUnicode = false;

                for(var charPos = 0; charPos < text.length; charPos++){
                    switch(text[charPos]){
                        case "\n":
                        case "[":
                        case "]":
                        case "\\":
                        case "^":
                        case "{":
                        case "}":
                        case "|":
                        case "€":
                            smsLength += 2;
                            break;

                        default:
                            smsLength += 1;
                    }

                    //!isUnicode && text.charCodeAt(charPos) > 127 && text[charPos] != "€" && (isUnicode = true)
                    if(text.charCodeAt(charPos) > 127 && text[charPos] != "€")
                        isUnicode = true;
                }

                if(isUnicode)   smsType = s.lengths.unicode;
                else                smsType = s.lengths.ascii;

                for(var sCount = 0; sCount < s.maxSmsNum; sCount++){

                    cutStrLength = smsType[sCount];
                    if(smsLength <= smsType[sCount]){

                        smsCount = sCount + 1;
                        charsLeft = smsType[sCount] - smsLength;
                        break
                    }
                }

                if(s.cut) e.val(text.substring(0, cutStrLength));
                smsCount == -1 && (smsCount = s.maxSmsNum, charsLeft = 0);

                s.counters.message.html(smsCount);
                s.counters.character.html(charsLeft);

            }, s.interval)
        }).keyup()
    }}(jQuery));

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
            '_token':$('meta[name="csrf-token"]').attr('content')
        },
        function(response){
            console.log(response);
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
        'type' : 'getNextAreas',
        'id'   : $id
    },function(response){
        console.log(response);
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

function ajaxSelect2($formID,$controllerFunction,$chars){

    if($chars == undefined){
        $chars = 1;
    }

    $($formID).select2({
        ajax: {
            method: 'GET',
            url: ajaxRequestUrl,
            dataType: 'json',
            delay: 500,
            data: function (params) {
                return {
                    type : $controllerFunction,
                    word: params.term
                };
            },
            processResults: function (data, params) {
                // parse the results into the format expected by Select2
                // since we are using custom formatting functions we do not need to
                // alter the remote JSON data, except to indicate that infinite
                // scrolling can be used
                params.page = params.page || 1;

                return {
                    results: data,
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
        minimumInputLength: $chars,
        templateResult: function (data) {
            if (data.loading === true) { // adjust for custom placeholder values
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

function ajaxSelect2WithGroupId($formID,$controllerFunction,$chars){
    if($chars == undefined){
        $chars = 1;
    }
    $($formID).select2({
        ajax: {
            method: 'GET',
            url: ajaxRequestUrl,
            dataType: 'json',
            delay: 500,
            data: function (params) {
                return {
                    groupid:$('#to_id_group').val(),
                    type : $controllerFunction,
                    word: params.term
                };
            },
            processResults: function (data, params) {
                // parse the results into the format expected by Select2
                // since we are using custom formatting functions we do not need to
                // alter the remote JSON data, except to indicate that infinite
                // scrolling can be used
                params.page = params.page || 1;

                return {
                    results: data,
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
        minimumInputLength: $chars,
        templateResult: function (data) {
            if (data.loading === true) { // adjust for custom placeholder values
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

function rand (min, max) {
    //  discuss at: http://locutus.io/php/rand/
    // original by: Leslie Hoare
    // bugfixed by: Onno Marsman (https://twitter.com/onnomarsman)
    //      note 1: See the commented out code below for a version which
    //      note 1: will work with our experimental (though probably unnecessary)
    //      note 1: srand() function)
    //   example 1: rand(1, 1)
    //   returns 1: 1
    var argc = arguments.length
    if (argc === 0) {
        min = 0
        max = 2147483647
    } else if (argc === 1) {
        throw new Error('Warning: rand() expects exactly 2 parameters, 1 given')
    }
    return Math.floor(Math.random() * (max - min + 1)) + min
}