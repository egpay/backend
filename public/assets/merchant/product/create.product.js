function AddUploadImage(){
    var $clone = $('.imagetoupload:last').clone();
    $clone.find('input').val('');
    $('.uploaddata').append([
        $clone,
        $('<hr>')
    ]);
}

function VerticalTabContent(lang,obj,url){
    attrId = $(obj).val();
    if ((attrId > 0) && $('a#baseVerticalLeft-tab' + attrId).length == 0) {
        $('.tab-pane,.nav-link').removeClass('active');
        $('.tab-pane,.nav-link').attr('aria-expanded', 'false');
        var li = $('<li>', {id: attrId, class: 'nav-item',}).append(
            $('<a>', {
                href: '#tabVerticalLeft' + attrId,
                text: $(obj).find(':selected').text(),
                id: 'baseVerticalLeft-tab' + attrId,
                class: 'nav-link active',
                'data-toggle': 'tab',
                'aria-controls': 'tabVerticalLeft' + attrId,
                'aria-expanded': 'true'
            }).append(
                $('<a>', {
                    href: 'javascript:void(0);',
                    style: 'display:inline;float:left;margin-top:-7px;margin-left:-15px;',
                    onclick: '$(this).parents(\'li\').remove();$(\'#attributeValues div#tabVerticalLeft' + attrId + '\').remove();',
                    class: 'btn fa fa-trash text-danger'
                })
            )
        );

        $(li).appendTo($('ul.nav-left'));
        $.get(url, {getAttribute: true, id: attrId}, function (data) {

            if ($.inArray(data.type, ['select', 'checkbox', 'radio']) >= 0) {
                var html = $('<table>', {
                    class: 'table',
                });
                $(html).append(TableRow(lang['tr'],'text'));
                $(html).append(TableRow(['','','','',$('<a>',{href:'javascript:void(0)',
                    class:'btn btn-success fa fa-plus',
                    onclick:'NewAttributeRow({"yes":\''+lang["yes"]+'\',"no":\''+lang["no"]+'\',"LE":\''+lang["LE"]+'\'},$(this),'+attrId+',\''+url+'\');'})],'html'));
            } else if(data.type=='text'){
                var html = $('<div>',{class:'form-group'})
                        .append([
                            createLabel(data.name,'attribute[' + data.id + '][val][]'),
                            createText('attribute[' + data.id + '][val][]','text')
                        ]);
            } else if(data.type=='textarea'){
                var html = $('<div>',{class:'form-group'})
                        .append([
                            createLabel(data.name,'attribute[' + data.id + '][val][]'),
                            createTextarea('attribute[' + data.id + '][val][]','text')
                        ]);
            }

            var tabContent = $('<div>', {
                id: 'tabVerticalLeft' + attrId,
                class: 'tab-pane active',
                'aria-labelledby': 'baseVerticalLeft-tab' + attrId,
                'aria-expanded': 'true',
            }).append($('<div>', {class: 'form-group'})
                .append(createLabel(lang['required'],'attribute[' + data.id + '][required]'))
                .append(createSelect([{'id':0,'text':lang['no']},{'id':1,'text':lang['yes']}],'attribute[' + data.id + '][required]',false))
            )
                .append(html);
            $(tabContent).appendTo('#attributeValues');
        }, 'json');
        $(obj).val(0);
    }
}

function NewAttributeRow(lang,obj,attrId,url){
    $.get(url, {getAttribute: true, id: attrId}, function (data) {
        TableRow([
            createSelect(data.attribute_value,'attribute['+attrId+'][val][]'),
            createSelect([{'id':0,'text':lang['no']},{'id':1,'text':lang['yes']}],'attribute['+attrId+'][stock][]',false),
            createText('attribute['+attrId+'][qty][]','number'),
            $('<div>',{class:'input-group'})
                .append(
                    [createSelect([{'id':'+','text':'+'},{'id':'-','text':'-'}],'attribute['+attrId+'][pricetype][]',false).attr('style','width:50%'),
                     createText('attribute['+attrId+'][price][]','number').attr('style','width:50%'),
                     $('<span>',{class:'input-group-addon',text:lang['LE']})
                    ]),
            $('<a>',{href:'javascript:void(0)',
                class:'btn btn-danger fa fa-trash',
                onclick:'$(this).parents(\'tr\').remove();'})
            ],'html')
            .insertBefore($(obj).parents('tr'));
    });
}


function createText(name,type){
    return $('<input>', {type:type,class:'form-control',name:name});
}


function createTextarea(name,id,value){
    return $('<textarea>', {id: id}).append($('<input>',{type:'hidden',name:name,value:value}));
}

function createLabel(text,lfor){
    return $('<label>',{text:text,for:lfor,class:'label-control'});
}

function createSelect(opts,name,selected){
    var select = $('<select>', {
        name: name,
        class:'form-control',
    });
    $.each(opts, function (key, value) {
        if(value.id == selected) {
            $(select).append($('<option>', {
                value: value['id'],
                text: value['text'],
                selected: true
            }));
        } else {
            $(select).append($('<option>', {
                value: value['id'],
                text: value['text'],
            }));
        }
    });
    return select;
}


function TableRow(cols,opt){
    var tr = $('<tr>');
    $.each(cols,function(i,item){
        if(opt=='text')
            tr.append($('<td>',{text:item}));
        else
            tr.append($('<td>',{html:item}));
    });
    return tr;
}