var lang = null;
$( document ).ajaxStart(function() {
    if(lang===null) {
        var keys = ['Must choose merchant first','Select Product','Confirm delete'];
        var url = $('meta[name="ajax-post"]').attr('content');
        $.post(url,{type:'getLanguage',Langkeys:keys},function(data){
            lang = data;
        },'json');
    }
});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    error: function(jqXHR,textStatus,errorThrown){
        toastr.error(errorThrown, 'Error !', {"closeButton": true});
    }
});

function generateCode(length,selector) {
    if(!checkMerchant())
        return false;
    var possible  = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    var text = "";

    for ( var i=0; i < length; i++ ) {
        text += possible.charAt(Math.floor(Math.random() * possible.length));
    }
    return $(selector).val(text);
}

function checkMerchant(){
    if(parseInt($('#merchant_id').val())===0){
        toastr.error(__('Must choose merchant first'), 'Error !', {"closeButton": true});
        return false;
    }
    return true;
}


function FillAndShowTable(table,product,Route){
    if(!$(table).find('tbody tr#'+product.id).length){
        $(table).find('tbody').append(
            $('<tr>',{id:product.id})
                .append([
                    $('<td>',{text:product.id}),
                    $('<td>',{html:[
                        $('<a>',{href:Route+product.id,text:product.name,target:'_blank'}),
                        $('<input>',{type:'hidden',name:'items[]',value:product.id})
                    ]},),
                    $('<td>',{html:$('<a>',{href:'javascript:void(0);',html:$('<i>',{class:'text-danger fa fa-trash'})})}),
                    $('<td>',{text:product.price})
                ])
        );
    }
    $(table).removeClass('hidden');
}


$('#merchant_id').on('change',function(e){
    const merchant_id = parseInt($('#merchant_id').val());
    const ajaxRequestUrl = $('meta[name="ajax-post"]').attr('content');
    const newData = new Array;
    $.getJSON(ajaxRequestUrl,{merchant_id:merchant_id,type:'getProductCategory'},function(data){
        $.each(data,function (key,value) {
            newData.push('<option value="'+value.id+'">'+value.name+'</option>');
        });
        $('#product_category_id').html(newData.join("\n"));
    });

    $('table#users tbody').empty();
    $('table#items tbody').empty();
});

$('#product_category_id').on('change',function (e) {
    const category_id = parseInt($('#product_category_id').val());
    const newData = new Array;
    $.getJSON(ajaxRequestUrl,{category_id:category_id,type:'getProducts'},function(data){
        $.each(data,function (key,value) {
            newData.push('<option value="'+value.id+'" data-price="'+value.price+'">'+value.name+'</option>');
        });
        $('#product_id').html(newData.join("\n"));
    });
});

$('#user_id').on('change',function (e) {
    const table = $('table#users');
    const Route = $(table).attr('data-route').slice(0,-1);
    const user = {
        id:$(this).val(),
        name:$('#user_id').parents('.controls').find('span.select2-selection__rendered').text()
    };
    if((user.id) && (!$(table).find('tbody tr#'+user.id).length)){
        $(table).find('tbody').append(
            $('<tr>',{id:user.id})
                .append([
                    $('<td>',{text:user.id}),
                    $('<td>',{html:[
                        $('<a>',{href:Route+user.id,text:user.name,target:'_blank'}),
                        $('<input>',{type:'hidden',name:'users[]',value:user.id})
                    ]}),
                    $('<td>',{html:$('<a>',{href:'javascript:void(0);',html:$('<i>',{class:'text-danger fa fa-trash'})})}),
                ])
        );
        $(table).removeClass('hidden');
        $('#user_id').val(0).trigger('change');
    }
});

$('#product_id').on('change',function (e) {
    const table = $('table#items');
    const Route = $(this).attr('data-route').slice(0,-1);
    const product = {
        id:$(this).val(),
        name:$(this).find(':selected').text(),
        price:$(this).find(':selected').attr('data-price')
    };
    FillAndShowTable(table,product,Route);
    $('#product_id').val(0);
});




$('body').on('click','.removeUser',function(){
    if(!confirm(__('Confirm delete'))) {
        return false;
    } else {
        $(this).parents('tr').remove();
    }
});

$('body').on('click','.removeItem',function(){
    if(!confirm(__('Confirm delete')))
        return false;
    $(this).parents('tr').remove();
});


/*
    payment Service
 */
$('select#type').on('change',function () {
    switch($(this).val()){
        case 'product':
            $('.serviceDiv').addClass('hidden');
            $('.productDiv').removeClass('hidden');
        break;
        case 'service':
            $('.productDiv').addClass('hidden');
            $('.serviceDiv').removeClass('hidden');
            $.getJSON(ajaxRequestUrl,{type:'payment_service_categories'},function(data){
                const newData = new Array;
                newData.push('<option value="0">Select</option>');
                $.each(data,function (key,value) {
                    newData.push('<option value="'+key+'">'+value+'</option>');
                });
                $('#payment_service_category_id').html(newData.join("\n"));
            });
        break;
    }
});

$('#payment_service_category_id').on('change',function (e) {
    const newData = new Array;
    const category_id = $('#payment_service_category_id').val();
    $.getJSON(ajaxRequestUrl,{type:'payment_service_providers',category_id:category_id},function(data){
        if(data) {
            newData.push('<option value="0">Select</option>');
            $.each(data, function (key, value) {
                newData.push('<option value="' + key + '">' + value + '</option>');
            });
            $('#payment_service_provider_id').html(newData.join("\n"));
        }
    });
});


$('#payment_service_provider_id').on('change',function (e) {
    const newData = new Array;
    const provider_id = $('#payment_service_provider_id').val();
    $.getJSON(ajaxRequestUrl,{type:'payment_services',provider_id:provider_id},function(data){
        if(data) {
            newData.push('<option value="0">Select</option>');
            $.each(data, function (key, value) {
                newData.push('<option value="' + key + '">' + value + '</option>');
            });
            $('#service_id').html(newData.join("\n"));
        }
    });
});


$('#service_id').on('change',function(){
    const table = $('table#items');
    const Route = $(this).attr('data-route').slice(0,-1);
    const product = {
        id:$(this).val(),
        name:$(this).find(':selected').text(),
        price:$(this).find(':selected').attr('data-price')
    };
    FillAndShowTable(table,product,Route);
    $('#service_id').val(0);
});

