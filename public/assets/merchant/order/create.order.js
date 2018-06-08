var lang = null;
$( document ).ajaxStart(function() {
    if(lang===null) {
        var keys = ['Select Product','Out Of stock','Required','Order total must equal user/s payments'];
        var url = $('meta[name="ajax-post"]').attr('content');
        $.post(url,{type:'getLanguage',Langkeys:keys},function(data){
            lang = data;
        },'json');
    }
});


function createSelectWithAllOpts(opts,name,id){
    var oneopt = opts[0];
    if(oneopt.required=='1')
        var obj = {id: id, name: name, class:'form-control required'}
    else
        var obj = {id: id, name: name, class:'form-control'}
    var select = $('<select>', obj);
    $.each(opts, function (key, value) {
        if(value.stock == '1'){
            if((value.quantity == 0) || (value.quantity == null))
                var disabled = true;
            else
                var disabled = false;
        }
        if(!value.plus_price)
            value.plus_price = 0;

            $(select).append($('<option>', {
                value: value['id'],
                text: value['value_text']+((disabled)?'['+__('Out Of stock')+']':''),
                'data-price':value.plus_price,
                disabled:disabled
            }));
    });
    return select;
}

function createCheckWithAllOpts(opts,name,id){
    var container = $('<ul>', {
        class:'col-sm-12 noneli',
    });
    $.each(opts, function(key, value){
        if(value.stock == '1'){
            if((value.quantity == 0) || (value.quantity == null))
                var disabled = true;
            else
                var disabled = false;
        }
        if(!value.plus_price)
            value.plus_price = 0;
        if(value.required=='1')
            var obj = {type:'checkbox',value:value['id'],name:name,id:id,'data-price':value.plus_price,disabled:disabled,'class':'required'};
        else
            var obj = {type:'checkbox',value:value['id'],name:name,id:id,'data-price':value.plus_price,disabled:disabled};
        $(container).append(
            $('<li>',{class:'col-sm-6'}).append(
                $('<label>', {html:' '+value['value_text']+((disabled)?' <span class="text-danger">['+__('Out Of stock')+']</span>':'')})
                    .prepend($('<input>',obj))
            )
        );
    });

    return container;
}

function createRadioWithAllOpts(opts,name,id){
    var container = $('<ul>', {
        class:'col-sm-12 noneli',
    });
    $.each(opts, function(key, value){
        if(value.stock == '1'){
            if((value.quantity == 0) || (value.quantity == null))
                var disabled = true;
            else
                var disabled = false;
        }
        if(!value.plus_price)
            value.plus_price = 0;
        if(value.required=='1')
            var obj = {type:'radio',value:value['id'],name:name,'data-price':value.plus_price,disabled:disabled,class:'required'};
        else
            var obj = {type:'radio',value:value['id'],name:name,'data-price':value.plus_price,disabled:disabled};
        $(container).append(
            $('<li>',{class:'col-sm-6'}).append(
                $('<label>', {html: ' '+value['value_text']+((disabled)?' <span class="text-danger">['+__('Out Of stock')+']</span>':'')})
                    .prepend($('<input>',obj))
            )
        );
    });
    return container;
}

function createText(name,id,value){
    return $('<input>', {type:'text',name:name,class:'form-control', id: id});
}


function createTextarea(name,id,value){
    return $('<textarea>', {id:id,name:name,class:'form-control'});
}



function ProductAttributes(obj,url){
    var opt = $(obj).find(':selected');
    if($(opt).val() <= 0)
        return;
    if(parseInt(opt.attr('data-attribute')) > 0){
        productId = opt.val();
        $.get(url,{type:'productAttributes',proid:productId},function(content){
            var allContent = '';
            $.each(content,function(attribute_id,attribute){
                var value = attribute[0];
                var html = $('<div>',{class:'col-sm-12'});
                if (value.type == 'text') {
                    $(html).append(
                        $('<label>', {text: value.name})
                    ).append(
                        createText('attribute[' + value.attribute_id + '][val][]', value.attribute_id, value.id)
                    );
                } else if (value.type == 'textarea') {
                    $(html).append(
                        $('<label>', {text: value.name})
                    ).append(
                        createTextarea('attribute[' + value.attribute_id + '][val][]', value.attribute_id, value.id)
                    );
                } else if (value.type == 'select'){
                    $(html).append(
                        $('<div>',{class:'input-group'})
                            .append([
                                $('<label>',{text : attribute[0].name}),
                                createSelectWithAllOpts(attribute,'attribute[' + value.attribute_id + '][val][]', attribute.id)
                            ])
                    );
                } else if (value.type == 'checkbox'){
                    $(html).append(
                        $('<div>',{class:'input-group'})
                            .append([
                                $('<label>',{text : attribute[0].name}),
                                createCheckWithAllOpts(attribute,'attribute[' + value.attribute_id + '][val][]', attribute.id)
                            ])
                    );
                } else if (value.type == 'radio'){
                    $(html).append(
                        $('<div>',{class:'input-group'})
                            .append([
                                $('<label>',{text : attribute[0].name}),
                                createRadioWithAllOpts(attribute,'attribute[' + value.attribute_id + '][val][]', attribute.id)
                            ])
                    );
                }
                $(html).append($('<hr>'));
                $('#productAttributes .modal-body form').append([html,$('<div>',{class:'clearfix'})]);
            });
            $('#productAttributes .modal-body form').append([
                $('<div>',{class:'mb-3'}),
                $('<input>',{class:'btn btn-danger pull-right',value:__('Cancel'),onclick:'$(\'#productAttributes\').modal(\'hide\')'}),
                $('<input>',{class:'btn btn-primary pull-left',value:__('Confirm'),type:'submit'}),

            ]);
            $('#productAttributes').modal('toggle');

            $('#options-form').validate({errorLabelContainer: "#messageBox"});

        },'json');
    } else {
            //Check & Add Product
            AddProductToTable(opt,$(opt).attr('data-price'));
    }
}

$('#options-form').on('submit',function(e){
    if($(this).valid()) {
        e.preventDefault();
        var opt = $('#product_id').find(':selected');
        var attr = $('<ul>', {class: 'noneli'});
        var extraprice = Array();
        $(this).find('input[name^="attribute"]').each(function () {

            var li = $('<li>');
            if ($(this).attr('type') == 'text') {
                $(li).append([
                    $('<span>', {text: $(this).val()}),
                    $('<input>', {type: 'hidden', value: $(this).val(), name: $(this).attr('name')})
                ]);
            } else if ($(this).attr('type') == 'checkbox') {
                $(this).each(function () {
                    if ($(this).is(':checked')) {
                        extraprice.push($(this).attr('data-price'));
                        $(li).append([
                            $('<span>', {text: $(this).parents('label').text()}),
                            $('<input>', {type: 'hidden', value: $(this).parents('label').text(), name: $(this).attr('name')})
                        ]);
                    }
                });
            } else if ($(this).attr('type') == 'radio') {
                if ($(this).is(':checked')) {
                    extraprice.push($(this).attr('data-price'));
                    $(li).append([
                        $('<span>', {text: $(this).parents('label').text()}),
                        $('<input>', {type: 'hidden', value: $(this).parents('label').text(), name: $(this).attr('name')})
                    ]);
                }
            }
            $(attr).append(li);
        });
        $(this).find('select[name^="attribute"]').each(function () {
            if (this.length) {
                extraprice.push($(this).attr('data-price'));
                var li = $('<li>');
                $(li).append([
                    $('<span>', {text: $(this).find(':selected').val()}),
                    $('<input>', {type: 'hidden', value: $(this).find(':selected').val(), name: $(this).attr('name')})
                ]);
                $(attr).append(li);
            }
        });

        if (opt.val() > 0){
            extraprice.push($(opt).attr('data-price'));
            price = sumArr(extraprice);
            var protext = hashFnv32a($(opt).text() + $(attr).text());
            //Check & Add Product
            AddProductToTable(opt,price,attr);
            $('#productAttributes').modal('hide');
        }
    } else {
        toastr.error(__('Required'),__('Required'), {"closeButton": true});
    }
});


function AddProductToTable(opt,price,attr){
    var productID = hashFnv32a($(opt).text() + $(attr).text());
    if(attr){
        attr.find('[name^="attribute"]').each(function(){
            var name = $(this).attr('name').replace('attribute','[attribute]');
            $(this).attr('name','product['+productID+']'+name);
        });
    }
    if($('#products tbody tr#'+productID).length) {
        var qty = $('#products tbody tr#' + productID).find('input[name*="qty"]');
        qty.val(parseInt(qty.val()) + 1);
    } else {
        $('#products tbody').append(
            $('<tr>', {class: 'row', id: productID})
                .append([
                    $('<td>').append([
                        $(opt).val(),
                        $('<input>', {type: 'hidden', name: 'product[' + productID + '][id]', value: $(opt).val()})
                    ]),
                    $('<td>').append([$(opt).text(), $(attr)]),
                    $('<td>', {class: 'proprice', text: price}),
                    $('<td>').append($('<input>', {
                        class: 'form-control',
                        name: 'product[' + productID + '][qty]',
                        value: '1'
                    })),
                    $('<td>').append($('<a>', {
                        href: 'javascript:void(0);',
                        class: 'removeproduct'
                    }).append($('<i>', {class: 'text-danger fa fa-trash'}))),
                ])
        );
        if ($('table#products').hasClass('hidden')) {
            $('table#products').removeClass('hidden');
        }
    }
    $('#product_id').val(0).trigger("change");
}

$('body').on('click','input.valid[name^="attribute"]',function(){
    if(!$(this).is(':checked')){
        $(this).removeClass('valid');
    }
});

$('body').on('hidden.bs.modal', '.modal', function () {
    $(this).find('.modal-body #options-form').empty();
    $(this).find('.modal-body #options-form').html('');
    $(this).find('.modal-body #options-form').text('');
    //$(this).removeData('bs.modal');
});

function GetProducts(url,catid){
    newData = new Array;
    newData.push('<option value="0">'+__('Select Product')+'</option>');
    $.ajax({
        url: url,
        dataType: 'json',
        type: 'GET',
        data: {"type": 'product',"catid":catid},
        success: function(response) {
            $.each(response,function (key,value) {
                newData.push('<option value="'+value.id+'" data-price="'+value.price+'" data-attribute="'+value.attribute+'">'+value.value+'</option>');
            })
            $('#product_id').html(newData.join("\n"));
        },
        error: function(x, e) {
        }
    });
}


function AddUserToOrder(){
    var error = false;
    if($('#type').val() == '0'){
        $('#type').parents('.form-group').addClass('has-danger');
        error = true;
    } else {
        $('#type').parents('.form-group').removeClass('has-danger');
    }
    if($('#user_id').val()===null){
        $('#user_id').parents('.form-group').addClass('has-danger');
        error = true;
    } else {
        $('#user_id').parents('.form-group').removeClass('has-danger');
    }
    if($('#amount').val() == ''){
        $('#amount').parents('.form-group').addClass('has-danger');
        error = true;
    } else {
        $('#amount').parents('.form-group').removeClass('has-danger');
    }
    if(error)
        return;

    /*
     lets add user to table
     */
    var opt = $('#user_id');
    var trid = hashFnv32a(opt.val()+$('#type').val());
    if($('table#users').hasClass('hidden')){
        $('table#users').removeClass('hidden');
    }
    if($('#users tbody tr#'+trid).length) {
        var amount = $('#users tbody tr#' + trid).find('input[name^="useramount"]');
        amount.val(parseInt($('#amount').val()));
        $('#users tbody tr#'+ trid).find('.useramount').text($('#amount').val());
    } else {
        var usertext = $('#user_id').parents('.controls').find('span.select2-selection__rendered').text();
        var amount = $('#amount').val();
        $('#users tbody').append(
            $('<tr>',{id:trid})
                .append([
                    $('<td>',{text:usertext}).append($('<input>',{type:'hidden',name:'users['+trid+'][id]',value:$(opt).val()})),
                    $('<td>',{text:$('#type').find(':selected').text()}).append($('<input>',{type:'hidden',name:'users['+trid+'][paytype]',value:$('#type').val()})),
                    $('<td>').append([
                        $('<span>',{class:'useramount',text:amount}),
                        $('<input>',{type:'hidden',name:'users['+trid+'][amount]',value:amount,class:'form-control'})
                    ]),
                    $('<td>').append(
                        $('<a>',{href:'javascript:void(0);',class:'removeuser'}).append($('<i>',{class:'text-danger fa fa-trash'}))
                    )
                ])
        );
        //$('#users tbody').append('<tr id="' + trid + '"><td>' + usertext + '<input type="hidden" name="users[]" value="' + $(opt).val() + '"></td><td>' + $('#type').find(':selected').text() + '<input type="hidden" name="paytype[]" value="' + $('#type').val() + '"></td><td><span class="useramount">'+amount+'</span><input type="hidden" name="useramount[]" value="'+amount+'" class="form-control"></td><td><a href="javascript:void(0);" class="removeuser"><i class="text-danger fa fa-trash"></i></a></td></tr>');
    }

    $('#type').val(0).trigger('change');
    $('#user_id').val(0).trigger('change');
    //$('#user_id').parents('.controls').find('span.select2-selection__rendered').text('');
    $('#amount').val('');
}


function calculatetotall(){
    var prices = Array();
    var qty = Array();
    $('#products tbody tr').each(function(){
        prices.push(parseFloat($(this).find('.proprice').text()) * parseFloat($(this).find('input[name*="qty"]').val()));
        qty.push(parseFloat($(this).find('input[name*="qty"]').val()));
    });
    $('.total').html(sumArr(prices));
    $('.total_qty').text(sumArr(qty));

    $('#amount').val(sumArr(prices) - calculateuserstotal());
    return sumArr(prices);
}


function calculateuserstotal(){
    var userstotal = 0;
    $('span.useramount').each(function(){
        userstotal += parseInt($(this).text());
    });
    $('.userstotal').text(userstotal);
    return userstotal ? userstotal  : 0;
}

function Checktotal() {
    if(parseInt($('.total').text()) === parseInt($('.userstotal').text())){
        return true;
    } else {
        toastr.error(__('Order total must equal user/s payments'),__('Required'), {"closeButton": true});
        return false;
    }
}

function hashFnv32a(str, asString, seed) {
    var i, l,
        hval = (seed === undefined) ? 0x811c9dc5 : seed;

    for (i = 0, l = str.length; i < l; i++) {
        hval ^= str.charCodeAt(i);
        hval += (hval << 1) + (hval << 4) + (hval << 7) + (hval << 8) + (hval << 24);
    }
    if( asString ){
        // Convert to 8 digit hex string
        return ("0000000" + (hval >>> 0).toString(16)).substr(-8);
    }
    return hval >>> 0;
}

function sumArr(arr){
    var sum = 0;
    $.each(arr,function(){sum+=parseFloat(this) || 0;});
    return sum;
}