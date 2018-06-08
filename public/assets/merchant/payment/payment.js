$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    error: function(jqXHR,textStatus,errorThrown){
        toastr.error(errorThrown, 'Error !', {"closeButton": true});
    }
});
var params = {};
var lang = null;
$( document ).ajaxStart(function() {
    if(lang===null) {
        var keys = ['Successful Transaction','Non-Successful Transaction','Date','Time',
            'Merchant ID','Transaction ID','Service ID','Paid Amount','Total Amount','Print','Pay','powered by','Confirmation','Confirm transferring',
            'LE','to','Wallet ID and Wallet ID confirmation are not the same','Confirm paying','Total amount','Must provide transfer amount'
        ];
        var url = $('meta[name="ajax-post"]').attr('content');
        $.post(url,{type:'getLanguage',Langkeys:keys},function(data){
            lang = data;
        },'json');
    }
});

function __(key){
    if(lang && (key in lang)){
        return lang[key];
    } else {
        return key;
    }
}

function round(value, decimals) {
    return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
}


!function ($) {
    $(document).on("click","#left ul.nav li.parent > a > span.sign", function(){
        $(this).find('i:first').toggleClass("icon-minus");
    });

    $("#left ul.nav li.parent.active > a > span.sign").find('i:first').addClass("icon-minus");
    $("#left ul.nav li.current").parents('ul.children').addClass("in");
}(window.jQuery);


function ShowService(serviceID,url){
    if(serviceID > 0){
        $.post(url,{'_token':$('meta[name="csrf-token"]').attr('content')},function(alldata){
            var data = alldata[0];
            var lang = alldata['lang'];
            var content = $('#services');
            var type = ((alldata['type']=='inquiry')?'inquiry':'payment');
            $(content).empty();
            content.append($('<h2>',{text:data.service_name,class:'mb-2'}));
            var form = $('<form>',{'action':url+'/'+type,method:'POST',novalidate:'novalidate'});
            //console.log(data.payment_service_api_parameters);
            $(form).append([
                $('<input>',{name:'_token',type:'hidden',value:$('meta[name="csrf-token"]').attr('content')}),
                $('<input>',{name:'total_amount',type:'hidden',value:data.total_amount})
            ]);
            var rules = {};
            var messages = {};
            createForm(data,form,rules);
            //console.log(ReturnCreateForm[1]);
            //console.log(rules);
            $(form).append([
                $('<div>',{class:'mt-3'}),
                $('<input>',{class:'btn btn-primary pull-right',value:lang.button,type:'submit',name:alldata['type']})
            ]);

            //console.log(rules);
            $(content).append(form);
            $(content).find('form').validate({rules:rules});

            $('html, body').animate({ scrollTop: $(content).offset().top - 150 }, 'slow');

        },'json');
    }
}



$('body').on('submit','#services form',function(e){
    e.preventDefault();
    //console.log($(this).attr('action'));
    var element = $('#services');
    var formType = $(element).find('form input[type="submit"]').attr('name');
    var thisForm = this;
    switch (formType){
        case 'payment':
            var amount = element.find('input[name="amount"]').val();
            $.post($(this).attr('action').slice(0,-7)+'totalamount',{amount:amount},function(data){
                if(data['status'] === false){
                    swal({
                        title: __('Error!!'),
                        'text': data['msg'],
                        'type': "error",
                    });
                } else {
                    swal({
                        title: __('Confirmation'),
                        text: __('Confirm paying') + ' ' + ((typeof data !== 'object') ? data : ''),
                        type: "info",
                        showCancelButton: true,
                        closeOnConfirm: true,
                        showLoaderOnConfirm: true,
                    }, function () {
                        //do nothing
                        $continue = true;
                        return proccessForm(element, thisForm);
                    });
                }
            });
        break;
        case 'prepaid':
            swal({
                title: __('Confirmation'),
                title: __('Confirm paying') +" "+$(element).find('form input[name="total_amount"]').val()+" "+__('LE'),
                type: "info",
                showCancelButton: true,
                closeOnConfirm: true,
                showLoaderOnConfirm: true,
            }, function () {
                //do nothing
                return proccessForm(element,thisForm);
            });
        break;
        default:
            proccessForm(element,thisForm);
        break;
    }
});

function proccessForm(element,thisform) {
    $(element).find('form input[type="submit"]').attr('disabled',true);
    var formData = {};

    $.each($(thisform).find(':input'),function(key,val){
        formData[$(val).attr('name')] = $(val).val();
        if($(val).attr('name').indexOf('parameters') > -1) {
            params[$(val).attr('name').substr(11).slice(0, -1).replace(' ','_')] = $(val).val();
        }
    });

    element.block({
        message: '<div class="ft-refresh-cw icon-spin font-medium-2"></div>',
        overlayCSS: {backgroundColor: '#FFF',cursor: 'wait',},css: {border: 0,padding: 0,backgroundColor: 'none'}
    });
    var PostUrl = $(thisform).attr('action');
    switch($(thisform).find('input[type="submit"]').attr('name')){
        case 'inquiry':
            $.post(PostUrl,formData,function(data){
                if(data.status){
                    var textData = data.data;
                    var printButton = $('<div>',{style:'text-align:center',class:'col-xs-5 col-center'}).append([
                        $('<button>',{class:'btn btn-primary fa fa-print m-2',text:' '+__('Print'),onclick:'$("#bill").print()'}),
                    ]);
                    if(data.service) {
                        var Payform = $('<form>',{'action':PostUrl.replace('inquiry','payment'),id:'paymentform',method:'POST',novalidate:'novalidate',id:'paymentform'});
                        $(Payform).append([
                            $('<input>',{name:'_token',type:'hidden',value:$('meta[name="csrf-token"]').attr('content')}),
                        ]);
                        var Payrules = {};
                        data.service[0].formData = formData;
                        data.service[0].type = 'inquiry';
                        data.service[0].params = data.params;
                        data.service[0].total_amount = data.data.system_amount.total_amount;
                        createForm(data.service[0],Payform,Payrules,data.values,textData.system_amount.amount,textData.transactionId);
                        printButton.append([
                            Payform.append([
                                $('<input>', {class: 'btn btn-success', value: __('Pay'),type:'submit',name:'payment'})
                            ]),
                        ]);
                        $(content).find('form#paymentform').validate({rules:Payrules});
                    }
                    $(element).html(CreateReceipt(data,printButton));
                } else {
                    $(element).html([
                        $('<h2>',{text:data.msg}),
                        $('<div>',{class:'col-xs-4 col-center',id:'bill'}).append([
                            $('<p>',{text:data.msg})
                        ])
                    ]);
                }
                $('#services').unblock();
            },'json');
        break;
        case 'payment':
        case 'prepaid':
            $.post(PostUrl,formData,function(PaymentData){
                if(PaymentData.status){
                    var printButton = $('<div>',{style:'text-align:center',class:'col-xs-5 col-center'}).append([
                        $('<button>',{class:'btn btn-primary fa fa-print m-2',text:' '+__('Print'),onclick:'$("#bill").print()'}),
                    ]);
                    $(element).html(CreateReceipt(PaymentData,printButton));
                } else {
                    $(element).html([
                        $('<h2>',{text:PaymentData.msg}),
                        $('<div>',{class:'col-xs-4 col-center',id:'paymentBill'}).append([
                            $('<p>',{text:PaymentData.msg})
                        ])
                    ]);
                }
                $('#services').unblock();
            },'json');
        break;
    }
}


function CreateReceipt(data,printButton){
    var textData = data.data;
    var datetime = textData.dateTime.split(' ');
    var viewInfo = [];
    if(__('langCode') in textData['info']) {
        $.each(textData['info'][__('langCode')], function (i, val) {
            if (val !== 'undefined') {
                viewInfo.push($('<tr>').append($('<td>', {text: val['key']})));
                viewInfo.push($('<tr>').append($('<td>', {text: val['value']})));
            }
        });
    }
    if(data.balance){
        $('#balance').text(data.balance);
    }
    return [$('<h2>',{text:data.msg}),
        $('<div>',{class:'col-xs-5 col-center',style:'text-align:center;font-family:"Lucida Grande","Neo Sans Arabic";',id:'bill'}).append([
            $('<div>',{style:'text-align:center'}).append($('<img>',{src:window.location.origin+'/egpay/public/assets/merchant/image/logo.png'})),
            $('<div>',{style:'text-align:center'}).append([
                '<style>.dividerB{border-bottom:5px double gray;}</style>',
                $('<div>',{class:'dividerB',html:'<b>'+textData.service_info['provider_name_'+__('langCode')]+'<br>'+textData.service_info['service_name_'+__('langCode')]+'</b>'}),
                $('<table>',{class:'table table-condensed'}).append([
                    $('<tr>').append([$('<td>',{text:__('Merchant ID')}),$('<td>',{text:textData.service_info.merchant_id})]),
                    $('<tr>').append([$('<td>',{text:__('Date')}),$('<td>',{text:datetime[0]})]),
                    $('<tr>').append([$('<td>',{text:__('Time')}),$('<td>',{text:datetime[1]})]),
                    //$('<tr>').append([$('<td>',{text:__('Transaction ID')}),$('<td>',{text:textData.transactionId})]),
                    $('<tr>').append([$('<td>',{text:__('Service ID')}),$('<td>',{text:textData.service_info.service_id})]),
                ]),
                $('<div>',{class:'dividerB',html:$('<b>',{text:__('Successful Transaction')})}),
                $('<table>',{class:'table table-condensed',style:'text-align:center'}).append(viewInfo),
                $('<table>',{class:'table table-condensed'}).append([
                    $('<tr>').append([$('<td>',{text:__('Paid Amount')}),$('<td>',{text:round(textData.system_amount.amount,2)})]),
                    $('<tr>').append([$('<td>',{text:__('Total Amount')}),$('<td>',{text:round(textData.system_amount.total_amount,2)})]),
                ]),
                $('<table>',{style:'font-size: 10px;width: 100%;font-weight:bold'}).append([
                    $('<tr>').append([$('<td>',{text:'EGPAY'})]),
                    $('<tr>').append([$('<td>',{text:'www.EGPAY.com'})]),
                    $('<tr>').append([$('<td>',{text:'Tel: +2 22739229'})]),
                    $('<tr>',{style:'border-bottom:1px solid #000'}).append([$('<td>',{style:'text-align: right;font-weight:normal',html:'<sub>'+__('powered by')+' '+textData.payment_by.name.toLowerCase()+'</sub>'})]),
                ]),
            ]),
        ]),
        printButton];
}

function createForm(data,form,rules,values,amount,transactionId){
    var inputVal = 'undefined';
    $(data.payment_service_api_parameters).each(function(i,param){
        if((values) && (values[param.name.toLowerCase()] !== 'undefined')){
            param['default_value'] = values[param.name.toLowerCase()];
        }
        var name = 'parameters[parameter_'+param.external_system_id+']';
        rules[name] = {
            required:((param.required=='yes')?true:false),
            minlength: param.min_length,
            maxlength: param.max_length,
            type:((param.type=='N')?'number':'text')
        };
        if((data.formData !== 'undefined') && (data.type=='inquiry')){
             if (data['params'][param.name.toLocaleLowerCase().replace(' ','_')] !== 'undefined') {
                 var inputName = data.params[param.name.toLocaleLowerCase().replace(' ','_')];
                 var inputVal = params[inputName];
             }
        }

        $(form).append(
            $('<div>',{class:'input-group'}).append([
                $('<label>',{text:param.name,class:'label-control'}),
                $('<input>',{
                    name:name,
                    class:'form-control'+((param.required=='yes')?' required':''),
                    type:'text',
                    required:((param.required=='yes')?true:false),
                    value:((inputVal !== 'undefined')?inputVal:((param.default_value !== 'undefined')?param.default_value:'')),
                    readonly:((inputVal !== 'undefined')?((data.type=='inquiry')?true:false):false),
                    minlength:param.min_length,
                    maxlength:param.max_length,
                })
            ])
        );
    });
    if(data.request_amount_input == 'yes'){
        $(form).append(
            $('<div>',{class:'input-group'}).append([
                $('<label>',{text:__('Amount'),class:'label-control'}),
                $('<input>',{
                    name:'amount',
                    class:'form-control required',
                    type:'number',
                    required:true,
                })
            ])
        );
    } else {
        if (amount !== 'undefined') {
            if((data.formData !== 'undefined') && (data.type=='inquiry')){
                $(form).append(
                    $('<div>',{class:'input-group'}).append([
                        $('<label>',{text:__('Amount'),class:'label-control'}),
                        $('<input>',{
                            name:'amount',
                            class:'form-control required',
                            type:'text',
                            readonly:(data.type=='inquiry')?true:false,
                            value:amount,
                            required:true,
                        })
                    ])
                );
            } else {
                form.append($('<input>', {type: 'hidden', name: 'amount', value: amount}));
            }
        }
        if ((data.type == 'inquiry') && (data.total_amount !== 'undefined')) {
            $(form).append(
                $('<div>',{class:'input-group'}).append([
                    $('<label>',{text:__('Total amount'),class:'label-control'}),
                    $('<input>',{
                        name:'total_amount',
                        class:'form-control required',
                        type:'text',
                        readonly:true,
                        value:data.total_amount,
                        required:true,
                    })
                ])
            );
        }
    }
    if(transactionId !== 'undefined') {
        form.append($('<input>', {type: 'hidden',name:'inquiry_transaction_id',value:transactionId}));
    }
}


$('#transfer form').on('submit', function(e){
    e.preventDefault();
    var TransferUrl = $(this).find('input[name="url"]').val();
    var wallet_id = $(this).find('input[name="wallet_id"]').val();
    var wallet_id_confirmation = $(this).find('input[name="wallet_id_confirmation"]').val();
    var amount = $(this).find('input[name="amount"]').val();
    if((!$.isNumeric(wallet_id)) || wallet_id != wallet_id_confirmation){
        toastr.error(__('Wallet ID and Wallet ID confirmation are not the same'), 'Error !', {"closeButton": true});
        return false;
    }
    if(!$.isNumeric(amount)){
        toastr.error(__('Must provide transfer amount'), 'Error !', {"closeButton": true});
        return false;
    }
        swal({
            title: __('Confirmation'),
            text: __('Confirm transferring')+" " + amount + __('LE'),
            type: "info",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
        }, function () {
            $('#transfer').block({
                message: '<div class="ft-refresh-cw icon-spin font-medium-2"></div>',
                overlayCSS: {backgroundColor: '#FFF',cursor: 'wait',},css: {border: 0,padding: 0,backgroundColor: 'none'}
            });
            $.post(TransferUrl, {wallet_id: wallet_id,wallet_id_confirmation:wallet_id_confirmation,amount: amount}, function (data) {
                if(data.status) {
                    swal(__('Successfully transferred')+" "+amount+" "+__('LE'));
                    $('#transfer form').find('input[type="number"]').val('');
                    $('#balance').text(data.balance);
                    $('#transfer').unblock();
                } else {
                    toastr.error(data.msg, 'Error !', {"closeButton": true});
                }
            }, 'json');
        });

});