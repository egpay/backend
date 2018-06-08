@extends('merchant.layouts')

@section('content')

            <div class="card">
                <div class="card-header">
                    <h2>{{$pageTitle}}</h2>
                </div>
            </div>
            <div class="content-body">
                <!-- Server-side processing -->
                <section id="server-processing">
                    {!! Form::open(['route' => isset($result->id) ? ['panel.merchant.employee.update',$result->id]:'panel.merchant.product-attribute.store','files'=>true, 'method' => isset($result->id) ?  'PATCH' : 'POST']) !!}
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h2>{{__('Attributes')}}</h2>
                                </div>
                                <div class="card-block card-dashboard">
                                    <div class="row nav-vertical">
                                        <div class="col-sm-2 nav-vertical">
                                            <ul class="noneli p-0 m-0 nav nav-tabs nav-left">

                                            </ul>
                                            <div id="attributeli">
                                                <select name="attribute" id="attribute" class="form-control">
                                                    <option value="0">{{__('Select Attribute')}}</option>
                                                    @foreach($categories as $category)
                                                        <optgroup label="{{(($systemLang=='ar')?$category->name_ar:$category->name_en)}}">
                                                            @foreach($category->attributes as $attribute)
                                                                <option value="{{$attribute->id}}">{{(($systemLang=='ar')?$attribute->name_ar:$attribute->name_en)}}</option>
                                                            @endforeach
                                                        </optgroup>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>

                                        <div class="col-sm-10 tab-content" id="attributeValues">

                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-block card-dashboard">
                                        {!! Form::submit(__('Save'),['class'=>'btn btn-success pull-right']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                {!! Form::close() !!}
                </section>
            </div>
                <!--/ Javascript sourced data -->
    <!-- ////////////////////////////////////////////////////////////////////////////-->

@endsection

@section('header')
@endsection

@section('footer')
    <script src="{{asset('assets/system')}}/js/scripts/navs/navs.js"></script>
    <script src="{{asset('assets/system')}}/js/scripts/custom/formElementBuilder.js"></script>
    <script>
        $(function(){
            $('#attribute').on('change',function(){
                var attrId = $(this).val();
                if($('a#baseVerticalLeft-tab'+attrId).length){
                    $('a#baseVerticalLeft-tab'+attrId).trigger('click');
                    return;
                }
                if((attrId > 0) && $('a#baseVerticalLeft-tab'+attrId).length == 0) {
                    $('.tab-pane,.nav-link').removeClass('active');
                    $('.tab-pane,.nav-link').attr('aria-expanded','false');
                    var li = $('<li>',{
                        id:attrId,
                        class:'nav-item',
                    }).append(
                        $('<a>',{
                            href:'#tabVerticalLeft'+attrId,
                            text:$(this).find(':selected').text(),
                            id:'baseVerticalLeft-tab'+attrId,
                            class:'nav-link active',
                            'data-toggle':'tab',
                            'aria-controls':'tabVerticalLeft'+attrId,
                            'aria-expanded':'true'
                        }).append(
                            $('<a>',{
                                href:'javascript:void(0);',
                                style:'display:inline;float:left;margin-top:-7px;margin-left:-15px;',
                                onclick:'$(this).parents(\'li\').remove();$(\'#attributeValues div#tabVerticalLeft'+attrId+'\').remove();',
                                class:'btn fa fa-trash text-danger'
                            })
                        )
                    );

                    $(li).appendTo($('ul.nav-left'));
                    $.get('{{request()->fullUrl()}}', {getAttribute: true, id: attrId}, function (data) {
                        var html = $('<table>',{
                                class:'table',
                            });
                        if($.inArray(data.type,['select','checkbox','radio']) >= 0) {
                            $(html).append(
                                $('<tr>')
                                    .append(
                                        $('<td>', {text: '{{__('Option Value')}}'})
                                    )
                                    .append(
                                        $('<td>', {text: '{{__('Stock Availability')}}'})
                                    )
                                    .append(
                                        $('<td>', {text: '{{__('Quantity')}}'})
                                    )
                                    .append(
                                        $('<td>', {text: '{{__('Price')}}'})
                                    )
                                    .append(
                                        $('<td>', {text: '{{__('Remove')}}'})
                                    )
                            );
                        }
                            if(data.type == 'text') {
                                    var value = data.attribute_value[0];
                                    $(html).append(
                                        $('<label>',{text:'{{__('Option values')}}'})
                                    ).append(
                                        createText('attribute['+value.attribute_id+']['+value.id+'][opt]', value.attribute_id, value.text)
                                    );
                            } else if(data.type == 'textarea'){
                                var value = data.attribute_value[0];
                                $(html).append(
                                    $('<label>',{text:'{{__('Option values')}}'})
                                ).append(
                                    createTextarea('attribute['+value.attribute_id+']['+value.id+'][opt]', value.attribute_id, value.text)
                                );
                            } else if($.inArray(data.type,['select','checkbox','radio']) >= 0) {
                                $(data.attribute_value).each(function(key,value){
                                    $(html).append(
                                        $('<tr>')
                                            .append(
                                                $('<td>',{html:createSelect(data.attribute_value,'attribute['+value.attribute_id+']['+value.id+'][opt]', value.id,value.id)})
                                            )
                                            .append(
                                                $('<td>').append(
                                                    $('<div>',{class:'form-group'})
                                                        .append($('<select>',{class:'form-control',name:'attribute['+value.attribute_id+']['+value.id+'][stock]'})
                                                            .append($('<option>',{value:'0',text:'{{__('No')}}'}))
                                                            .append($('<option>',{value:'1',text:'{{__('Yes')}}'}))
                                                    )
                                                )
                                            )
                                            .append(
                                                $('<td>').append(
                                                    $('<div>',{class:'form-group'})
                                                        .append($('<input>',{class:'form-control',type:'number',name:'attribute['+value.attribute_id+']['+value.id+'][qty]'})
                                                    )
                                                )
                                            )
                                            .append(
                                                $('<td>')
                                                    .append(
                                                        $('<div>',{class:'input-group'})
                                                        .append(
                                                            $('<select>',{class:'form-control','style':'width:50%;',name:'attribute['+value.attribute_id+']['+value.id+'][pricetype]'})
                                                                .append($('<option>',{value:'+',text:'{{__('+')}}'}))
                                                                .append($('<option>',{value:'-',text:'{{__('-')}}'}))
                                                            )
                                                        .append(
                                                            $('<input>',{class:'form-control',type:'text','style':'width:50%;',name:'attribute['+value.attribute_id+']['+value.id+'][price]'})
                                                        )
                                                        .append(
                                                            $('<span>',{class:'input-group-addon',text:'{{__('LE')}}'})
                                                        )
                                                    )
                                            )
                                            .append(
                                                $('<td>').append(
                                                    $('<a>',{href:'javascript:void(0)',class:'btn btn-danger fa fa-trash',onclick:'$(this).parents(\'tr\').remove()'})
                                                )
                                            )
                                    );
                                });

                            }
                        var tabContent = $('<div>',{
                            id:'tabVerticalLeft'+attrId,
                            class:'tab-pane active',
                            'aria-labelledby':'baseVerticalLeft-tab'+attrId,
                            'aria-expanded':'true',
                        }).append(
                            $('<div>',{class:'form-group'})
                                .append(
                                    $('<label>',{text:'{{__('Required')}}',class:''})
                                )
                                .append($('<select>',{name:'attribute['+data.id+'][required]',class:'form-control'})
                                    .append($('<option>',{value:'0',text:'{{__('No')}}'}))
                                    .append($('<option>',{value:'1',text:'{{__('Yes')}}'}))
                                )
                        )
                        .append(html);
                        $(tabContent).appendTo('#attributeValues');
                    }, 'json');
                }
            });
        });
    </script>
@endsection