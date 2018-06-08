@extends('system.layouts')

@section('content')

    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row"></div>
            <div class="content-body">
                <div id="user-profile">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="card profile-with-cover">
                                <div class="card-img-top img-fluid bg-cover height-300" style="background: url('{{asset('assets/system/images/carousel/22.jpg')}}') 50%;"></div>
                                <div class="media profil-cover-details">
                                    @if($result->logo)
                                        <div class="media-left pl-2 pt-2">
                                            <a href="jaascript:void(0);" class="profile-image">
                                                <img title="{{$result->{'name_'.$systemLang} }}" src="{{asset('storage/app/'.imageResize($result->logo,70,70))}}"  class="rounded-circle img-border height-100"  />
                                            </a>
                                        </div>
                                    @endif
                                    <div class="media-body media-middle row">
                                        <div class="col-xs-6">
                                            <h3 class="card-title" style="margin-bottom: 0.5rem;">
                                                {{$result->{'name_'.$systemLang} }}
                                                @if($result->status == 'in-active')
                                                    <b style="color: red;">(IN-ACTIVE)</b>
                                                @endif
                                            </h3>
                                            <span>{{$result->{'description_'.$systemLang} }}</span>
                                        </div>
                                        <div class="col-xs-6 text-xs-right">
                                            {{--<button type="button" class="btn btn-primary hidden-xs-down"><i class="fa fa-plus"></i> Follow</button>--}}
                                            {{--<div class="btn-group hidden-md-down" role="group" aria-label="Basic example">--}}
                                            {{--<button type="button" class="btn btn-success"><i class="fa fa-dashcube"></i> Message</button>--}}
                                            {{--<button type="button" class="btn btn-success"><i class="fa fa-cog"></i></button>--}}
                                            {{--</div>--}}
                                        </div>
                                    </div>
                                </div>
                                <nav class="navbar navbar-light navbar-profile">
                                    <button class="navbar-toggler hidden-sm-up" type="button" data-toggle="collapse" data-target="#exCollapsingNavbar2" aria-controls="exCollapsingNavbar2" aria-expanded="false" aria-label="Toggle navigation"></button>
                                    <div class="collapse navbar-toggleable-xs" id="exCollapsingNavbar2">
                                        <ul class="nav navbar-nav float-xs-right">

                                            <li class="nav-item active">
                                                <a class="nav-link"  href="javascript:void(0);" onclick="urlIframe('{{route('merchant.merchant.edit',$result->id)}}')"><i class="fa fa-pencil"></i> {{__('Edit Merchant info')}} <span class="sr-only">(current)</span></a>
                                            </li>

                                            <li class="nav-item active">
                                                <a class="nav-link"  href="javascript:void(0);" onclick="urlIframe('{{route('payment.invoice.index',$result->id)}}')"><i class="fa fa-file-text"></i> {{__('Payment Invoices')}} <span class="sr-only">(current)</span></a>
                                            </li>

                                            {{--<li class="nav-item active">--}}
                                            {{--<a class="nav-link"  href="javascript:void(0);" onclick="urlIframe('{{route('payment.transactions.list',$result->id)}}')"><i class="fa fa-file-text"></i> {{__('Payment Transactions')}} <span class="sr-only">(current)</span></a>--}}
                                            {{--</li>--}}

                                        </ul>





                                    </div>
                                </nav>
                            </div>
                        </div>
                    </div>


                    @if($result->MerchantImages)
                        <div class="col-sm-12 card">
                            <div class="card-block">
                                @foreach($result->MerchantImages as $image)
                                    <div class="col-md-2 well">
                                        <img class="zoomImage" style="width:100%; height:auto;" src="{{asset(str_replace('public/','storage/',$image->path))}}">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-4">
                            <section id="spacing" class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        {{__('Merchant Info')}}
                                        <span style="float: right;"><a class="btn btn-outline-primary"  href="javascript:void();" onclick="urlIframe('{{route('merchant.merchant.edit',$result->id)}}')"><i class="fa fa-pencil"></i> {{__('Edit')}}</a></span>
                                    </h4>

                                </div>
                                <div class="card-body collapse in">
                                    <div class="card-block">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>{{__('Value')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>{{__('ID')}}</td>
                                                    <td>{{$result->id}}</td>
                                                </tr>

                                                @foreach(listLangCodes() as $key => $value)

                                                    <tr>
                                                        <td>{{__('Name')}} ({{$value}})</td>
                                                        <td>{{ $result->{'name_'.$key} }}</td>
                                                    </tr>

                                                @endforeach

                                                @foreach(listLangCodes() as $key => $value)

                                                    <tr>
                                                        <td>{{__('Description')}} ({{$value}})</td>
                                                        <td><code>{{ $result->{'description_'.$key} }}</code></td>
                                                    </tr>

                                                @endforeach

                                                <tr>
                                                    <td>{{__('Area')}}</td>
                                                    <td><code>{{ implode(' -> ',\App\Libs\AreasData::getAreasUp($result->area_id,true,$systemLang)) }}</code></td>
                                                </tr>

                                                <tr>
                                                    <td>{{__('Address')}} </td>
                                                    <td><code>{{$result->address}}</code></td>
                                                </tr>

                                                @if($result->merchant_contract_id)
                                                    <tr>
                                                        <td>{{__('Contract ID')}} </td>
                                                        <td><a href="{{route('merchant.contract.show',$result->merchant_contract_id)}}">{{$result->merchant_contract_id}}</a></td>
                                                    </tr>
                                                @endif


                                                <tr>
                                                    <td>{{__('Is Reseller')}}</td>
                                                    <td>
                                                        @if($result->is_reseller == 'active')
                                                            <b style="color: green;">{{__('Yes')}}</b>
                                                        @else
                                                            <b style="color: red;">{{__('No')}}</b>
                                                        @endif
                                                    </td>
                                                </tr>



                                                @if($result->parent_id)
                                                    <tr>
                                                        <td>{{__('Parent From')}}</td>
                                                        <td>
                                                            {{$result->parent->{'name_'.$systemLang} }}
                                                        </td>
                                                    </tr>
                                                @endif



                                                <tr>
                                                    <td>{{__('Created By')}}</td>
                                                    <td>
                                                        <a href="{{route('system.staff.show',$result->staff_id)}}" target="_blank">
                                                            {{__('#ID')}}:{{$result->staff_id}} <br >{{$result->staff->firstname .' '. $result->staff->lastname}}
                                                        </a>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>{{__('Created At')}}</td>
                                                    <td>
                                                        @if($result->created_at == null)
                                                            --
                                                        @else
                                                            {{$result->created_at->diffForHumans()}}
                                                        @endif
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>{{__('Updated At')}}</td>
                                                    <td>
                                                        @if($result->updated_at == null)
                                                            --
                                                        @else
                                                            {{$result->updated_at->diffForHumans()}}
                                                        @endif
                                                    </td>
                                                </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </section>

                        </div>
                        <div class="col-md-4">
                            <section id="spacing" class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        {{__('Category Info')}}
                                        <span style="float: right;"><a class="btn btn-outline-primary"  href="javascript:void();" onclick="urlIframe('{{route('merchant.category.edit',$result->category->id)}}')"><i class="fa fa-pencil"></i> {{__('Edit')}}</a></span>
                                    </h4>
                                </div>
                                <div class="card-body collapse in">
                                    <div class="card-block">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>{{__('Value')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>{{__('ID')}}</td>
                                                    <td>{{$result->category->id}} ( <a target="_blank" href="{{route('merchant.category.show',$result->category->id)}}">{{__('View')}}</a> )</td>
                                                </tr>

                                                @foreach(listLangCodes() as $key => $value)

                                                    <tr>
                                                        <td>{{__('Name')}} ({{$value}})</td>
                                                        <td>{{ $result->category->{'name_'.$key} }}</td>
                                                    </tr>

                                                @endforeach


                                                @foreach(listLangCodes() as $key => $value)

                                                    <tr>
                                                        <td>{{__('Description')}} ({{$value}})</td>
                                                        <td><code>{{ $result->category->{'description_'.$key} }}</code></td>
                                                    </tr>

                                                @endforeach

                                                <tr>
                                                    <td>{{__('Commission')}}</td>
                                                    <td>
                                                        {{ $result->category->commission }}
                                                        @if($result->category->commission_type == 'fixed') LE @else % @endif
                                                    </td>
                                                </tr>



                                                <tr>
                                                    <td>{{__('Status')}} </td>
                                                    <td>

                                                        @if($result->category->status == 'active')
                                                            <b style="color: green">{{__('Active')}}</b>
                                                        @else
                                                            <b style="color: red">{{__('In-Active')}}</b>
                                                        @endif

                                                    </td>
                                                </tr>


                                                <tr>
                                                    <td>{{__('Created By')}}</td>
                                                    <td>
                                                        <a href="{{url('system/staff/'.$result->category->staff_id)}}" target="_blank">
                                                            {{__('#ID')}}:{{$result->category->staff_id}} <br >{{$result->category->staff->firstname .' '. $result->category->staff->lastname}}
                                                        </a>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>{{__('Created At')}}</td>
                                                    <td>
                                                        @if($result->category->created_at == null)
                                                            --
                                                        @else
                                                            {{$result->category->created_at->diffForHumans()}}
                                                        @endif
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>{{__('Updated At')}}</td>
                                                    <td>
                                                        @if($result->category->updated_at == null)
                                                            --
                                                        @else
                                                            {{$result->category->updated_at->diffForHumans()}}
                                                        @endif
                                                    </td>
                                                </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </section>

                        </div>
                        <div class="col-md-4">

                            <section class="card">
                                <div class="card-header">
                                    <h4 class="card-title">{{__('Last Contract')}}</h4>
                                </div>
                                <div class="card-body collapse in">
                                    <div class="card-block">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>{{__('Value')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>{{__('ID')}}</td>
                                                    <td>{{$result->contract->id}} ( <a href="{{route('merchant.contract.show',$result->contract->id)}}" target="_blank">View</a> ) </td>
                                                </tr>

                                                <tr>
                                                    <td>{{__('description')}}</td>
                                                    <td><code>{{$result->contract->description}}</code></td>
                                                </tr>

                                                <tr>
                                                    <td>{{__('Price')}}</td>
                                                    <td>{{$result->contract->price}} {{__('LE')}}</td>
                                                </tr>

                                                <tr>
                                                    <td>{{__('Start At')}}</td>
                                                    <td>{{explode(' ',$result->contract->start_date)[0]}} ( {{$result->contract->start_date->diffForHumans()}} )</td>
                                                </tr>

                                                <tr>
                                                    <td>{{__('End At')}}</td>
                                                    <td>{{explode(' ',$result->contract->end_date)[0]}}</td>
                                                </tr>

                                                <tr>
                                                    <td>{{__('Admin Name')}}</td>
                                                    <td>{{$result->contract->admin_name}} <small>( {{$result->contract->admin_job_title}} )</small></td>
                                                </tr>


                                                <tr>
                                                    <td>{{__('Created By')}}</td>
                                                    <td>
                                                        <a href="{{url('system/staff/'.$result->contract->staff_id)}}" target="_blank">
                                                            {{__('#ID')}}:{{$result->contract->staff_id}} <br >{{$result->contract->staff->firstname .' '. $result->contract->staff->lastname}}
                                                        </a>
                                                    </td>
                                                </tr>


                                                <tr>
                                                    <td>{{__('Created At')}}</td>
                                                    <td>
                                                        @if($result->contract->created_at == null)
                                                            --
                                                        @else
                                                            {{$result->contract->created_at->diffForHumans()}}
                                                        @endif
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>{{__('Updated At')}}</td>
                                                    <td>
                                                        @if($result->contract->updated_at == null)
                                                            --
                                                        @else
                                                            {{$result->contract->updated_at->diffForHumans()}}
                                                        @endif
                                                    </td>
                                                </tr>

                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </section>


                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-12">
                            <section id="spacing" class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        {{__('Wallets')}}

                                    </h4>

                                </div>
                                <div class="card-body collapse in">
                                    <div class="card-block">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th>{{__('Wallet ID')}}</th>
                                                    <th>{{__('Wallet Type')}}</th>
                                                    <th>{{__('Balance')}}</th>
                                                    <th>{{__('Last Update')}}</th>
                                                    <th>{{__('Action')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($result->wallet as $key => $value)
                                                    <tr>
                                                        <th>{{$value->id}}</th>
                                                        <th>{{ucfirst(__($value->type))}}</th>
                                                        <th>{{$value->balance}}</th>
                                                        <th>{{$value->updated_at->diffForHumans()}}</th>
                                                        <th>
                                                            <button class="btn btn-primary" type="button" onclick='location = "{{route('system.wallet.show',$value->id)}}"'><i class="ft-eye"></i></button>
                                                        </th>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                    <th>{{__('Wallet ID')}}</th>
                                                    <th>{{__('Wallet Type')}}</th>
                                                    <th>{{__('Balance')}}</th>
                                                    <th>{{__('Last Update')}}</th>
                                                    <th>{{__('Action')}}</th>
                                                </tr>
                                                </tfoot>


                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </section>

                        </div>

                    </div>


                    <div class="row">

                        <div class="col-md-6">
                            <section id="spacing" class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        {{__('Merchant Staff')}}
                                        <span style="float: right;">
                                            <a style="margin-bottom: 10px;" class="btn btn-outline-success"  href="javascript:void();" onclick="urlIframe('{{route('merchant.staff-group.index',['merchant_id'=>$result->id])}}')"><i class="fa fa-eye"></i> {{__('View Group')}}</a>
                                            <a style="margin-bottom: 10px;" class="btn btn-outline-primary"  href="javascript:void();" onclick="urlIframe('{{route('merchant.staff-group.create',['merchant_id'=>$result->id])}}')"><i class="fa fa-plus"></i> {{__('Add Group')}}</a>
                                            <br>
                                            <a style="margin-bottom: 10px;" class="btn btn-outline-success"  href="javascript:void();" onclick="urlIframe('{{route('merchant.staff.index',['merchant_id'=>$result->id])}}')"><i class="fa fa-eye"></i> {{__('View Staff')}}</a>
                                            <a style="margin-bottom: 10px;" class="btn btn-outline-primary"  href="javascript:void();" onclick="urlIframe('{{route('merchant.staff.create',['merchant_id'=>$result->id])}}')"><i class="fa fa-plus"></i> {{__('Add Staff')}}</a>
                                        </span>
                                    </h4>

                                </div>
                                <div class="card-body collapse in">
                                    <div class="card-block">
                                        <div class="table-responsive">
                                            <table class="table" id="product-list">

                                                @foreach($merchantStaffGroups as $key => $value)

                                                    <tr class="treegrid-{{$value->id}}">
                                                        <td>
                                                            <b>
                                                                <a target="_blank" href="{{route('merchant.staff-group.show',$value->id)}}">{{$value->title }}</a>
                                                            </b>
                                                        </td>
                                                    </tr>

                                                    @if(isset($merchantStaff[$value->id]))
                                                        @foreach($merchantStaff[$value->id] as $staffKey => $staffValue)
                                                            <tr class="treegrid-2{{$staffValue->id}} treegrid-parent-{{$value->id}}">
                                                                <td>
                                                                    <a target="_blank" href="{{route('merchant.staff.show',$staffValue->id)}}">
                                                                        {{$staffValue->firstname}} {{$staffValue->lastname}} <small>({{$staffValue->username}})</small>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif

                                                @endforeach

                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </section>

                        </div>
                        <div class="col-md-6">
                            <section id="spacing" class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        {{__('Contacts')}}
                                    </h4>

                                </div>
                                <div class="card-body collapse in">
                                    <div class="card-block">
                                        <div class="table-responsive">

                                            <table class="table" id="product-list">


                                                <!-- Start Email -->
                                                @php
                                                    $name = $result->contact->where('type','name');
                                                @endphp
                                                @if($name->isNotEmpty())
                                                    <tr class="treegrid-name">
                                                        <td>
                                                            <b>{{__('Name')}}</b>
                                                        </td>
                                                    </tr>
                                                    @foreach($name as $key => $value)
                                                        <tr class="treegrid-m{{$key}} treegrid-parent-name">
                                                            <td>
                                                                {{$value->value}}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            <!-- End Email -->



                                                <!-- Start Email -->
                                                @php
                                                    $email = $result->contact->where('type','email');
                                                @endphp
                                                @if($email->isNotEmpty())
                                                    <tr class="treegrid-email">
                                                        <td>
                                                            <b>{{__('Email')}}</b>
                                                        </td>
                                                    </tr>
                                                    @foreach($email as $key => $value)
                                                        <tr class="treegrid-m{{$key}} treegrid-parent-email">
                                                            <td>
                                                                <a href="mailto:{{$value->value}}">{{$value->value}}</a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            <!-- End Email -->


                                                <!-- Start Mobile -->
                                                @php
                                                    $mobile = $result->contact->where('type','mobile');
                                                @endphp
                                                @if($mobile->isNotEmpty())
                                                    <tr class="treegrid-mobile">
                                                        <td>
                                                            <b>{{__('Mobile')}}</b>
                                                        </td>
                                                    </tr>
                                                    @foreach($mobile as $key => $value)
                                                        <tr class="treegrid-m{{$key}} treegrid-parent-mobile">
                                                            <td>
                                                                <a href="tel:{{$value->value}}">{{$value->value}}</a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            <!-- End Mobile -->


                                                <!-- Start phone -->
                                                @php
                                                    $phone = $result->contact->where('type','phone');
                                                @endphp
                                                @if($phone->isNotEmpty())
                                                    <tr class="treegrid-phone">
                                                        <td>
                                                            <b>{{__('Phone')}}</b>
                                                        </td>
                                                    </tr>
                                                    @foreach($phone as $key => $value)
                                                        <tr class="treegrid-m{{$key}} treegrid-parent-phone">
                                                            <td>
                                                                <a href="tel:{{$value->value}}">{{$value->value}}</a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            <!-- End phone -->

                                                <!-- Start fax -->
                                                @php
                                                    $fax = $result->contact->where('type','fax');
                                                @endphp
                                                @if($fax->isNotEmpty())
                                                    <tr class="treegrid-fax">
                                                        <td>
                                                            <b>{{__('Fax')}}</b>
                                                        </td>
                                                    </tr>
                                                    @foreach($fax as $key => $value)
                                                        <tr class="treegrid-m{{$key}} treegrid-parent-fax">
                                                            <td>
                                                                <a href="tel:{{$value->value}}">{{$value->value}}</a>
                                                            </td>
                                                        </tr>
                                                @endforeach
                                            @endif
                                            <!-- End fax -->


















                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </section>

                        </div>

                    </div>









                    <div class="row">
                        <div class="col-md-12">
                            <section id="spacing" class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        {{__('Merchant Branches')}}
                                        <span style="float: right;"><a class="btn btn-outline-primary"  href="javascript:void();" onclick="urlIframe('{{route('merchant.branch.create',['merchant_id'=>$result->id])}}')"><i class="fa fa-plus"></i> {{__('Add New Branche')}}</a></span>
                                    </h4>

                                </div>
                                <div class="card-body collapse in">
                                    <div class="card-block">
                                        <div class="table-responsive">
                                            <table class="table" id="merchant-branches">
                                                <thead>
                                                <tr>
                                                    <th>{{__('ID')}}</th>
                                                    <th>{{__('Name')}}</th>
                                                    <th>{{__('Address')}}</th>
                                                    <th>{{__('Map')}}</th>
                                                    <th>{{__('Action')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </section>

                        </div>

                        <div class="col-md-12">
                            <section id="spacing" class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        {{__('Merchant Contracts')}}
                                        <span style="float: right;"><a class="btn btn-outline-primary"  href="javascript:void();" onclick="urlIframe('{{route('merchant.contract.create',['merchant_id'=>$result->id])}}')"><i class="fa fa-plus"></i> {{__('Add New Contract')}}</a></span>
                                    </h4>

                                </div>
                                <div class="card-body collapse in">
                                    <div class="card-block">
                                        <div class="table-responsive">
                                            <table class="table" id="contract-table">
                                                <thead>
                                                <tr>
                                                    <th>{{__('ID')}}</th>
                                                    <th>{{__('Description')}}</th>
                                                    <th>{{__('Price')}}</th>
                                                    <th>{{__('Plan')}}</th>
                                                    <th>{{__('Start At')}}</th>
                                                    <th>{{__('End At')}}</th>
                                                    <th>{{__('Created By')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </section>

                        </div>

                    </div>




                    <div class="row">

                        <div class="col-md-12">
                            <section id="spacing" class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        {{__('Merchant Products')}}
                                        <span style="float: right;">
                                            <a style="margin-bottom: 10px;" class="btn btn-outline-primary" href="javascript:void();" onclick="urlIframe('{{route('merchant.product-category.index',['merchant_id'=>$result->id])}}')"><i class="fa fa-eye"></i> {{__('View Category')}}</a>
                                            <a style="margin-bottom: 10px;" class="btn btn-outline-primary" href="javascript:void();" onclick="urlIframe('{{route('merchant.product-category.create',['merchant_id'=>$result->id])}}')"><i class="fa fa-plus"></i> {{__('Add Product Category')}}</a>
                                            <br>
                                            <a style="margin-bottom: 10px;" class="btn btn-outline-success" href="javascript:void();" onclick="urlIframe('{{route('merchant.product.index',['merchant_id'=>$result->id])}}')"><i class="fa fa-eye"></i> {{__('View Product')}}</a>
                                            <a style="margin-bottom: 10px;" class="btn btn-outline-success" href="javascript:void();" onclick="urlIframe('{{route('merchant.product.create',['merchant_id'=>$result->id])}}')"><i class="fa fa-plus"></i> {{__('Add Product')}}</a>
                                        </span>
                                    </h4>

                                </div>
                                <div class="card-body collapse in">
                                    <div class="card-block">
                                        <div class="table-responsive">
                                            <table class="table" id="product-list">

                                                @foreach($productsCategories as $key => $value)

                                                    <tr class="treegrid-{{$value->id}}">
                                                        <td>
                                                            @if($value->icon)
                                                                <img src="{{asset('storage/app/'.imageResize($value->icon,70,70))}}" >
                                                            @endif
                                                            <b>
                                                                <a  @if($value->approved_at == null) style="color: red;" @endif target="_blank" href="{{route('merchant.category.show',$value->id)}}">{{$value->{'name_'.$systemLang} }}</a>
                                                            </b>
                                                        </td>
                                                        <td colspan="2">{{$value->{'description_'.$systemLang} }}</td>
                                                    </tr>

                                                    @if(isset($products[$value->id]))
                                                        @foreach($products[$value->id] as $productKey => $productValue)
                                                            <tr class="treegrid-2{{$productValue->id}} treegrid-parent-{{$value->id}}">
                                                                <td>
                                                                    @if($productValue->icon)
                                                                        <img src="{{asset('storage/app/'.imageResize($productValue->icon,70,70))}}" >
                                                                    @endif
                                                                    <a target="_blank" href="{{route('merchant.product-category.show',$productValue->id)}}" @if($productValue->approved_at == null) style="color: red;" @endif>
                                                                        {{$productValue->{'name_'.$systemLang} }}
                                                                    </a>
                                                                </td>
                                                                <td>{{$productValue->price}} {{__('LE')}}</td>
                                                                <td>{{$productValue->{'description_'.$systemLang} }}</td>
                                                            </tr>
                                                        @endforeach
                                                    @endif

                                                @endforeach

                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </section>

                        </div>

                    </div>




























































                    {{--<section id="timeline" class="timeline-center timeline-wrapper">--}}
                    {{--<h3 class="page-title text-xs-center">Timeline</h3>--}}
                    {{--<ul class="timeline">--}}
                    {{--<li class="timeline-line"></li>--}}
                    {{--<li class="timeline-group">--}}
                    {{--<a href="#" class="btn btn-primary"><i class="fa fa-calendar-o"></i> Today</a>--}}
                    {{--</li>--}}
                    {{--</ul>--}}
                    {{--<ul class="timeline">--}}
                    {{--<li class="timeline-line"></li>--}}
                    {{--<li class="timeline-item">--}}
                    {{--<div class="timeline-badge">--}}
                    {{--<span class="bg-red bg-lighten-1" data-toggle="tooltip" data-placement="right" title="Portfolio project work"><i class="fa fa-plane"></i></span>--}}
                    {{--</div>--}}
                    {{--<div class="timeline-card card border-grey border-lighten-2">--}}
                    {{--<div class="card-header">--}}
                    {{--<h4 class="card-title"><a href="#">Portfolio project work</a></h4>--}}
                    {{--<p class="card-subtitle text-muted mb-0 pt-1">--}}
                    {{--<span class="font-small-3">5 hours ago</span>--}}
                    {{--</p>--}}
                    {{--<a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>--}}
                    {{--<div class="heading-elements">--}}
                    {{--<ul class="list-inline mb-0">--}}
                    {{--<li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>--}}
                    {{--<li><a data-action="expand"><i class="ft-maximize"></i></a></li>--}}
                    {{--</ul>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="card-body collapse in">--}}
                    {{--<img class="img-fluid" src="../../../app-assets/images/portfolio/width-1200/portfolio-1.jpg" alt="Timeline Image 1">--}}
                    {{--<div class="card-body collapse in">--}}
                    {{--<div class="card-block">--}}
                    {{--<p class="card-text">Nullam facilisis fermentum aliquam. Suspendisse ornare dolor vitae libero hendrerit auctor lacinia a ligula. Curabitur elit tellus, porta ut orci sed, fermentum bibendum nisi.</p>--}}
                    {{--<ul class="list-inline">--}}
                    {{--<li class="pr-1"><a href="#" class=""><span class="fa fa-thumbs-o-up"></span> Like</a></li>--}}
                    {{--<li class="pr-1"><a href="#" class=""><span class="fa fa-commenting-o"></span> Comment</a></li>--}}
                    {{--<li><a href="#" class=""><span class="fa fa-share-alt"></span> Share</a></li>--}}
                    {{--</ul>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="card-footer px-0 py-0">--}}
                    {{--<div class="card-block ">--}}
                    {{--<div class="media">--}}
                    {{--<div class="media-left">--}}
                    {{--<a href="#">--}}
                    {{--<span class="avatar avatar-online"><img src="../../../app-assets/images/portrait/small/avatar-s-1.png" alt="avatar"></span>--}}
                    {{--</a>--}}
                    {{--</div>--}}
                    {{--<div class="media-body">--}}
                    {{--<p class="text-bold-600 mb-0"><a href="#">Jason Ansley</a></p>--}}
                    {{--<p>Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo.</p>--}}
                    {{--<ul class="list-inline">--}}
                    {{--<li class="pr-1"><a href="#" class=""><span class="fa fa-thumbs-o-up"></span> Like</a></li>--}}
                    {{--<li class="pr-1"><a href="#" class=""><span class="fa fa-commenting-o"></span> Reply</a></li>--}}
                    {{--</ul>--}}
                    {{--<div class="media">--}}
                    {{--<div class="media-left">--}}
                    {{--<a href="#">--}}
                    {{--<span class="avatar avatar-online"><img src="../../../app-assets/images/portrait/small/avatar-s-18.png" alt="avatar"></span>--}}
                    {{--</a>--}}
                    {{--</div>--}}
                    {{--<div class="media-body">--}}
                    {{--<p class="text-bold-600 mb-0"><a href="#">Janice Johnston</a></p>--}}
                    {{--<p>Gravida nulla. Nulla vel metus scelerisque ante sollicitudin.</p>--}}
                    {{--<ul class="list-inline">--}}
                    {{--<li class="pr-1"><a href="#" class=""><span class="fa fa-thumbs-o-up"></span> Like</a></li>--}}
                    {{--<li class="pr-1"><a href="#" class=""><span class="fa fa-commenting-o"></span> Reply</a></li>--}}
                    {{--</ul>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="card-block">--}}
                    {{--<fieldset class="form-group position-relative has-icon-left mb-0">--}}
                    {{--<input type="text" class="form-control" placeholder="Write comments...">--}}
                    {{--<div class="form-control-position">--}}
                    {{--<i class="fa fa-dashcube"></i>--}}
                    {{--</div>--}}
                    {{--</fieldset>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</li>--}}
                    {{--<li class="timeline-item mt-3">--}}
                    {{--<div class="timeline-badge">--}}
                    {{--<span class="avatar avatar-online" data-toggle="tooltip" data-placement="left" title="Eu pid nunc urna integer"><img src="../../../app-assets/images/portrait/small/avatar-s-14.png" alt="avatar"></span>--}}
                    {{--</div>--}}
                    {{--<div class="timeline-card card border-grey border-lighten-2">--}}
                    {{--<div class="card-header">--}}
                    {{--<h4 class="card-title"><a href="#">Sofia Orav</a></h4>--}}
                    {{--<p class="card-subtitle text-muted mb-0 pt-1">--}}
                    {{--<span class="font-small-3">8 hours ago</span>--}}
                    {{--</p>--}}
                    {{--<a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>--}}
                    {{--<div class="heading-elements">--}}
                    {{--<ul class="list-inline mb-0">--}}
                    {{--<li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>--}}
                    {{--<li><a data-action="expand"><i class="ft-maximize"></i></a></li>--}}
                    {{--</ul>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="card-body collapse in">--}}
                    {{--<div class="embed-responsive embed-responsive-4by3">--}}
                    {{--<iframe src="https://player.vimeo.com/video/118589137?title=0&byline=0"></iframe>--}}
                    {{--</div>--}}
                    {{--<div class="card-body collapse in">--}}
                    {{--<div class="card-block">--}}
                    {{--<p class="card-text">Nullam facilisis fermentum aliquam. Suspendisse ornare dolor vitae libero hendrerit auctor lacinia a ligula. Curabitur elit tellus, porta ut orci sed, fermentum bibendum nisi.</p>--}}
                    {{--<p class="card-text">Eu pid nunc urna integer, sed, cras tortor scelerisque penatibus facilisis a pulvinar, rhoncus sagittis ut nunc elit! Sociis in et? Rhoncus, vel dignissim in scelerisque. Dolor lacus pulvinar adipiscing adipiscing montes! Elementum risus adipiscing non, cras scelerisque risus penatibus? Massa vut, habitasse, tincidunt!</p>--}}
                    {{--<ul class="list-inline">--}}
                    {{--<li class="pr-1"><a href="#" class=""><span class="fa fa-thumbs-o-up"></span> Like</a></li>--}}
                    {{--<li class="pr-1"><a href="#" class=""><span class="fa fa-commenting-o"></span> Comment</a></li>--}}
                    {{--<li><a href="#" class=""><span class="fa fa-share-alt"></span> Share</a></li>--}}
                    {{--</ul>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="card-footer px-0 py-0">--}}
                    {{--<div class="card-block ">--}}
                    {{--<fieldset class="form-group position-relative has-icon-left mb-0">--}}
                    {{--<input type="text" class="form-control" placeholder="Write comments...">--}}
                    {{--<div class="form-control-position">--}}
                    {{--<i class="fa fa-dashcube"></i>--}}
                    {{--</div>--}}
                    {{--</fieldset>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</li>--}}
                    {{--<li class="timeline-item block">--}}
                    {{--<div class="timeline-badge">--}}
                    {{--<a title="" data-context="inverse" data-container="body" class="border-silc" href="#" data-original-title="block highlight"></a>--}}
                    {{--</div>--}}
                    {{--<div class="timeline-card card border-grey border-lighten-2">--}}
                    {{--<div class="card-header">--}}
                    {{--<div class="text-xs-center">--}}
                    {{--<p><i class="fa fa-bar-chart font-medium-4"></i></p>--}}
                    {{--<h4>Campaign Report</h4>--}}
                    {{--<p class="timeline-date">18 hours ago</p>--}}
                    {{--<p>Rhoncus, vel dignissim in scelerisque. Dolor lacus pulvinar adipiscing adipiscing montes! Elementum risus adipiscing non, cras scelerisque risus penatibus? Massa vut, habitasse, tincidunt!</p>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="card-body collapse in">--}}
                    {{--<div class="card-block">--}}
                    {{--<div class="chart-container">--}}
                    {{--<div id="stacked-column" class="height-400 overflow-hidden echart-container"></div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</li>--}}
                    {{--</ul>--}}
                    {{--<!-- 2016 -->--}}
                    {{--<ul class="timeline">--}}
                    {{--<li class="timeline-line"></li>--}}
                    {{--<li class="timeline-group">--}}
                    {{--<a href="#" class="btn btn-primary"><i class="fa fa-calendar-o"></i> 2016</a>--}}
                    {{--</li>--}}
                    {{--</ul>--}}
                    {{--<ul class="timeline">--}}
                    {{--<li class="timeline-line"></li>--}}
                    {{--<!-- /.timeline-line -->--}}
                    {{--<li class="timeline-item">--}}
                    {{--<div class="timeline-badge">--}}
                    {{--<span class="avatar avatar-online" data-toggle="tooltip" data-placement="right" title="Eu pid nunc urna integer"><img src="../../../app-assets/images/portrait/small/avatar-s-5.png" alt="avatar"></span>--}}
                    {{--</div>--}}
                    {{--<div class="timeline-card card card-inverse">--}}
                    {{--<img class="card-img img-fluid" src="../../../app-assets/images/portfolio/width-1200/portfolio-2.jpg" alt="Card image">--}}
                    {{--<div class="card-img-overlay bg-overlay">--}}
                    {{--<h4 class="card-title">Good Morning</h4>--}}
                    {{--<p class="card-text"><small>26 Aug, 2016 at 2.00 A.M</small></p>--}}
                    {{--<p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>--}}
                    {{--<p class="card-text">Eu pid nunc urna integer, sed, cras tortor scelerisque penatibus facilisis a pulvinar, rhoncus sagittis ut nunc elit! Sociis in et? Rhoncus, vel dignissim in scelerisque. Dolor lacus pulvinar adipiscing adipiscing montes!</p>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</li>--}}
                    {{--<li class="timeline-item mt-3">--}}
                    {{--<div class="timeline-badge">--}}
                    {{--<span class="bg-teal bg-lighten-1" data-toggle="tooltip" data-placement="left" title="Nullam facilisis fermentum"><i class="fa fa-check-square-o"></i></span>--}}
                    {{--</div>--}}
                    {{--<div class="timeline-card card border-grey border-lighten-2">--}}
                    {{--<div class="card-header">--}}
                    {{--<h4 class="card-title"><a href="#">Sofia Orav</a></h4>--}}
                    {{--<p class="card-subtitle text-muted mb-0 pt-1">--}}
                    {{--<span class="font-small-3">18 June, 2016 at 4.50 P.M</span>--}}
                    {{--</p>--}}
                    {{--<a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>--}}
                    {{--<div class="heading-elements">--}}
                    {{--<ul class="list-inline mb-0">--}}
                    {{--<li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>--}}
                    {{--<li><a data-action="expand"><i class="ft-maximize"></i></a></li>--}}
                    {{--</ul>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="card-body collapse in">--}}
                    {{--<div class="card-body collapse in">--}}
                    {{--<div class="card-block">--}}
                    {{--<p class="card-text">Nullam facilisis fermentum aliquam. Suspendisse ornare dolor vitae libero hendrerit auctor lacinia a ligula. Curabitur elit tellus, porta ut orci sed, fermentum bibendum nisi.</p>--}}
                    {{--<ul class="list-group icheck-task">--}}
                    {{--<li class="list-group-item">--}}
                    {{--<input type="checkbox" id="input-1"> Cras justo odio</li>--}}
                    {{--<li class="list-group-item">--}}
                    {{--<input type="checkbox" id="input-2" checked> Dapibus ac facilisis in</li>--}}
                    {{--<li class="list-group-item">--}}
                    {{--<input type="checkbox" id="input-3"> Morbi leo risus</li>--}}
                    {{--<li class="list-group-item">--}}
                    {{--<input type="checkbox" id="input-4" disabled checked> Porta ac consectetur ac</li>--}}
                    {{--<li class="list-group-item">--}}
                    {{--<input type="checkbox" id="input-5"> Vestibulum at eros</li>--}}
                    {{--</ul>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="card-footer px-0 py-0">--}}
                    {{--<div class="card-block ">--}}
                    {{--<fieldset class="form-group position-relative has-icon-left mb-0">--}}
                    {{--<input type="text" class="form-control" placeholder="Write comments...">--}}
                    {{--<div class="form-control-position">--}}
                    {{--<i class="fa fa-dashcube"></i>--}}
                    {{--</div>--}}
                    {{--</fieldset>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</li>--}}
                    {{--<li class="timeline-item">--}}
                    {{--<div class="timeline-badge">--}}
                    {{--<span class="bg-purple bg-lighten-1" data-toggle="tooltip" data-placement="right" title="Nullam facilisis fermentum"><i class="fa fa-podcast"></i></span>--}}
                    {{--</div>--}}
                    {{--<div class="timeline-card card border-grey border-lighten-2">--}}
                    {{--<div class="card-header">--}}
                    {{--<h4 class="card-title"><a href="#">Non-ribbon Chord Chart</a></h4>--}}
                    {{--<p class="card-subtitle text-muted mb-0 pt-1">--}}
                    {{--<span class="font-small-3">6 Feb, 2016 at 6.00 A.M</span>--}}
                    {{--</p>--}}
                    {{--<a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>--}}
                    {{--<div class="heading-elements">--}}
                    {{--<ul class="list-inline mb-0">--}}
                    {{--<li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>--}}
                    {{--<li><a data-action="expand"><i class="ft-maximize"></i></a></li>--}}
                    {{--</ul>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="card-body collapse in">--}}
                    {{--<div class="card-body collapse in">--}}
                    {{--<div class="card-block">--}}
                    {{--<p class="card-text">Nullam facilisis fermentum aliquam. Suspendisse ornare dolor vitae libero hendrerit auctor lacinia a ligula. Curabitur elit tellus, porta ut orci sed, fermentum bibendum nisi.</p>--}}
                    {{--<div class="chart-container">--}}
                    {{--<div id="non-ribbon-chord" class="height-400 overflow-hidden echart-container"></div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="card-footer px-0 py-0">--}}
                    {{--<div class="card-block ">--}}
                    {{--<fieldset class="form-group position-relative has-icon-left mb-0">--}}
                    {{--<input type="text" class="form-control" placeholder="Write comments...">--}}
                    {{--<div class="form-control-position">--}}
                    {{--<i class="fa fa-dashcube"></i>--}}
                    {{--</div>--}}
                    {{--</fieldset>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</li>--}}
                    {{--</ul>--}}
                    {{--<!-- 2015 -->--}}
                    {{--<ul class="timeline">--}}
                    {{--<li class="timeline-line"></li>--}}
                    {{--<li class="timeline-group">--}}
                    {{--<a href="#" class="btn btn-primary"><i class="fa fa-calendar-o"></i> 2015</a>--}}
                    {{--</li>--}}
                    {{--</ul>--}}
                    {{--<ul class="timeline">--}}
                    {{--<li class="timeline-line"></li>--}}
                    {{--<!-- /.timeline-line -->--}}
                    {{--<li class="timeline-item block">--}}
                    {{--<div class="timeline-badge">--}}
                    {{--<a title="" data-context="inverse" data-container="body" class="border-silc" href="#" data-original-title="block highlight"></a>--}}
                    {{--</div>--}}
                    {{--<div class="timeline-card card border-grey border-lighten-2">--}}
                    {{--<div class="card-header">--}}
                    {{--<div class="text-xs-center">--}}
                    {{--<p class="mt-1"><i class="fa fa-file-image-o font-medium-4"></i></p>--}}
                    {{--<h4>Media Gallery</h4>--}}
                    {{--<p class="timeline-date">July 1, 2015</p>--}}
                    {{--<p>Eu pid nunc urna integer, sed, cras tortor scelerisque penatibus facilisis a pulvinar, rhoncus sagittis ut nunc elit! Sociis in et?</p>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<!-- Image grid -->--}}
                    {{--<div class="card-body collapse in">--}}
                    {{--<div class="card-block my-gallery" itemscope itemtype="http://schema.org/ImageGallery">--}}
                    {{--<div class="row">--}}
                    {{--<figure class="col-md-3 col-sm-6 col-xs-12" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">--}}
                    {{--<a href="../../../app-assets/images/gallery/1.jpg" itemprop="contentUrl" data-size="480x360">--}}
                    {{--<img class="img-thumbnail img-fluid" src="../../../app-assets/images/gallery/1.jpg" itemprop="thumbnail" alt="Image description" />--}}
                    {{--</a>--}}
                    {{--</figure>--}}
                    {{--<figure class="col-md-3 col-sm-6 col-xs-12" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">--}}
                    {{--<a href="../../../app-assets/images/gallery/2.jpg" itemprop="contentUrl" data-size="480x360">--}}
                    {{--<img class="img-thumbnail img-fluid" src="../../../app-assets/images/gallery/2.jpg" itemprop="thumbnail" alt="Image description" />--}}
                    {{--</a>--}}
                    {{--</figure>--}}
                    {{--<figure class="col-md-3 col-sm-6 col-xs-12" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">--}}
                    {{--<a href="../../../app-assets/images/gallery/3.jpg" itemprop="contentUrl" data-size="480x360">--}}
                    {{--<img class="img-thumbnail img-fluid" src="../../../app-assets/images/gallery/3.jpg" itemprop="thumbnail" alt="Image description" />--}}
                    {{--</a>--}}
                    {{--</figure>--}}
                    {{--<figure class="col-md-3 col-sm-6 col-xs-12" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">--}}
                    {{--<a href="../../../app-assets/images/gallery/4.jpg" itemprop="contentUrl" data-size="480x360">--}}
                    {{--<img class="img-thumbnail img-fluid" src="../../../app-assets/images/gallery/4.jpg" itemprop="thumbnail" alt="Image description" />--}}
                    {{--</a>--}}
                    {{--</figure>--}}
                    {{--</div>--}}
                    {{--<div class="row">--}}
                    {{--<figure class="col-md-3 col-sm-6 col-xs-12" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">--}}
                    {{--<a href="../../../app-assets/images/gallery/5.jpg" itemprop="contentUrl" data-size="480x360">--}}
                    {{--<img class="img-thumbnail img-fluid" src="../../../app-assets/images/gallery/5.jpg" itemprop="thumbnail" alt="Image description" />--}}
                    {{--</a>--}}
                    {{--</figure>--}}
                    {{--<figure class="col-md-3 col-sm-6 col-xs-12" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">--}}
                    {{--<a href="../../../app-assets/images/gallery/6.jpg" itemprop="contentUrl" data-size="480x360">--}}
                    {{--<img class="img-thumbnail img-fluid" src="../../../app-assets/images/gallery/6.jpg" itemprop="thumbnail" alt="Image description" />--}}
                    {{--</a>--}}
                    {{--</figure>--}}
                    {{--<figure class="col-md-3 col-sm-6 col-xs-12" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">--}}
                    {{--<a href="../../../app-assets/images/gallery/7.jpg" itemprop="contentUrl" data-size="480x360">--}}
                    {{--<img class="img-thumbnail img-fluid" src="../../../app-assets/images/gallery/7.jpg" itemprop="thumbnail" alt="Image description" />--}}
                    {{--</a>--}}
                    {{--</figure>--}}
                    {{--<figure class="col-md-3 col-sm-6 col-xs-12" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">--}}
                    {{--<a href="../../../app-assets/images/gallery/8.jpg" itemprop="contentUrl" data-size="480x360">--}}
                    {{--<img class="img-thumbnail img-fluid" src="../../../app-assets/images/gallery/8.jpg" itemprop="thumbnail" alt="Image description" />--}}
                    {{--</a>--}}
                    {{--</figure>--}}
                    {{--</div>--}}
                    {{--<div class="row">--}}
                    {{--<figure class="col-md-3 col-sm-6 col-xs-12" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">--}}
                    {{--<a href="../../../app-assets/images/gallery/9.jpg" itemprop="contentUrl" data-size="480x360">--}}
                    {{--<img class="img-thumbnail img-fluid" src="../../../app-assets/images/gallery/9.jpg" itemprop="thumbnail" alt="Image description" />--}}
                    {{--</a>--}}
                    {{--</figure>--}}
                    {{--<figure class="col-md-3 col-sm-6 col-xs-12" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">--}}
                    {{--<a href="../../../app-assets/images/gallery/10.jpg" itemprop="contentUrl" data-size="480x360">--}}
                    {{--<img class="img-thumbnail img-fluid" src="../../../app-assets/images/gallery/10.jpg" itemprop="thumbnail" alt="Image description" />--}}
                    {{--</a>--}}
                    {{--</figure>--}}
                    {{--<figure class="col-md-3 col-sm-6 col-xs-12" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">--}}
                    {{--<a href="../../../app-assets/images/gallery/11.jpg" itemprop="contentUrl" data-size="480x360">--}}
                    {{--<img class="img-thumbnail img-fluid" src="../../../app-assets/images/gallery/11.jpg" itemprop="thumbnail" alt="Image description" />--}}
                    {{--</a>--}}
                    {{--</figure>--}}
                    {{--<figure class="col-md-3 col-sm-6 col-xs-12" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">--}}
                    {{--<a href="../../../app-assets/images/gallery/12.jpg" itemprop="contentUrl" data-size="480x360">--}}
                    {{--<img class="img-thumbnail img-fluid" src="../../../app-assets/images/gallery/12.jpg" itemprop="thumbnail" alt="Image description" />--}}
                    {{--</a>--}}
                    {{--</figure>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<!--/ Image grid -->--}}
                    {{--<!-- Root element of PhotoSwipe. Must have class pswp. -->--}}
                    {{--<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">--}}
                    {{--<!-- Background of PhotoSwipe.--}}
                    {{--It's a separate element as animating opacity is faster than rgba(). -->--}}
                    {{--<div class="pswp__bg"></div>--}}
                    {{--<!-- Slides wrapper with overflow:hidden. -->--}}
                    {{--<div class="pswp__scroll-wrap">--}}
                    {{--<!-- Container that holds slides.--}}
                    {{--PhotoSwipe keeps only 3 of them in the DOM to save memory.--}}
                    {{--Don't modify these 3 pswp__item elements, data is added later on. -->--}}
                    {{--<div class="pswp__container">--}}
                    {{--<div class="pswp__item"></div>--}}
                    {{--<div class="pswp__item"></div>--}}
                    {{--<div class="pswp__item"></div>--}}
                    {{--</div>--}}
                    {{--<!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->--}}
                    {{--<div class="pswp__ui pswp__ui--hidden">--}}
                    {{--<div class="pswp__top-bar">--}}
                    {{--<!--  Controls are self-explanatory. Order can be changed. -->--}}
                    {{--<div class="pswp__counter"></div>--}}
                    {{--<button class="pswp__button pswp__button--close" title="Close (Esc)"></button>--}}
                    {{--<button class="pswp__button pswp__button--share" title="Share"></button>--}}
                    {{--<button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>--}}
                    {{--<button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>--}}
                    {{--<!-- Preloader demo http://codepen.io/dimsemenov/pen/yyBWoR -->--}}
                    {{--<!-- element will get class pswp__preloader-active when preloader is running -->--}}
                    {{--<div class="pswp__preloader">--}}
                    {{--<div class="pswp__preloader__icn">--}}
                    {{--<div class="pswp__preloader__cut">--}}
                    {{--<div class="pswp__preloader__donut"></div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">--}}
                    {{--<div class="pswp__share-tooltip"></div>--}}
                    {{--</div>--}}
                    {{--<button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">--}}
                    {{--</button>--}}
                    {{--<button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">--}}
                    {{--</button>--}}
                    {{--<div class="pswp__caption">--}}
                    {{--<div class="pswp__caption__center"></div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<!--/ PhotoSwipe -->--}}
                    {{--</div>--}}
                    {{--</li>--}}
                    {{--<li class="timeline-item">--}}
                    {{--<div class="timeline-badge">--}}
                    {{--<span class="bg-warning bg-darken-1" data-toggle="tooltip" data-placement="right" title="Application API Support"><i class="fa fa-life-ring"></i></span>--}}
                    {{--</div>--}}
                    {{--<div class="timeline-card card border-grey border-lighten-2">--}}
                    {{--<div class="card-header">--}}
                    {{--<div class="media">--}}
                    {{--<div class="media-left">--}}
                    {{--<a href="#">--}}
                    {{--<span class="avatar avatar-md avatar-busy"><img src="../../../app-assets/images/portrait/small/avatar-s-18.png" alt="avatar"></span>--}}
                    {{--<i></i>--}}
                    {{--</a>--}}
                    {{--</div>--}}
                    {{--<div class="media-body">--}}
                    {{--<h4 class="card-title"><a href="#">Application API Support</a></h4>--}}
                    {{--<p class="card-subtitle text-muted mb-0 pt-1">--}}
                    {{--<span class="font-small-3">15 Oct, 2015 at 8.00 A.M</span>--}}
                    {{--<span class="tag tag-pill tag-default tag-warning float-xs-right">High</span>--}}
                    {{--</p>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="card-body collapse in">--}}
                    {{--<img class="img-fluid" src="../../../app-assets/images/portfolio/width-1200/portfolio-3.jpg" alt="Timeline Image 1">--}}
                    {{--<div class="card-body collapse in">--}}
                    {{--<div class="card-block">--}}
                    {{--<p class="card-text">Nullam facilisis fermentum aliquam. Suspendisse ornare dolor vitae libero hendrerit auctor lacinia a ligula. Curabitur elit tellus, porta ut orci sed, fermentum bibendum nisi.</p>--}}
                    {{--<ul class="list-inline">--}}
                    {{--<li class="pr-1"><a href="#" class=""><span class="fa fa-commenting-o"></span> Comment</a></li>--}}
                    {{--</ul>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="card-footer px-0 py-0">--}}
                    {{--<div class="card-block">--}}
                    {{--<div class="media">--}}
                    {{--<div class="media-left">--}}
                    {{--<a href="#">--}}
                    {{--<span class="avatar avatar-online"><img src="../../../app-assets/images/portrait/small/avatar-s-4.png" alt="avatar"></span>--}}
                    {{--</a>--}}
                    {{--</div>--}}
                    {{--<div class="media-body">--}}
                    {{--<p class="text-bold-600 mb-0"><a href="#">Crystal Lawson</a></p>--}}
                    {{--<p>Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo.</p>--}}
                    {{--<div class="media">--}}
                    {{--<div class="media-left">--}}
                    {{--<a href="#">--}}
                    {{--<span class="avatar avatar-online"><img src="../../../app-assets/images/portrait/small/avatar-s-6.png" alt="avatar"></span>--}}
                    {{--</a>--}}
                    {{--</div>--}}
                    {{--<div class="media-body">--}}
                    {{--<p class="text-bold-600 mb-0"><a href="#">Rafila Găitan</a></p>--}}
                    {{--<p>Gravida nulla. Nulla vel metus scelerisque ante sollicitudin.</p>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<fieldset class="form-group position-relative has-icon-left mb-0">--}}
                    {{--<input type="text" class="form-control" placeholder="Write comments...">--}}
                    {{--<div class="form-control-position">--}}
                    {{--<i class="fa fa-dashcube"></i>--}}
                    {{--</div>--}}
                    {{--</fieldset>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</li>--}}
                    {{--<li class="timeline-item mt-3">--}}
                    {{--<div class="timeline-badge">--}}
                    {{--<span class="bg-amber bg-darken-1" data-toggle="tooltip" data-placement="left" title="Quote of the day"><i class="fa fa-smile-o"></i></span>--}}
                    {{--</div>--}}
                    {{--<div class="timeline-card card border-grey border-lighten-2">--}}
                    {{--<div class="card-header">--}}
                    {{--<h4 class="card-title"><a href="#">Quote of the day</a></h4>--}}
                    {{--<p class="card-subtitle text-muted mb-0 pt-1">--}}
                    {{--<span class="font-small-3">03 March, 2015 at 5 P.M</span>--}}
                    {{--</p>--}}
                    {{--</div>--}}
                    {{--<div class="card-body collapse in">--}}
                    {{--<img class="img-fluid" src="../../../app-assets/images/portfolio/width-600/portfolio-3.jpg" alt="Timeline Image 1">--}}
                    {{--<div class="card-block">--}}
                    {{--<blockquote class="card-blockquote">--}}
                    {{--<p class="card-text">Eu pid nunc urna integer, sed, cras tortor scelerisque penatibus facilisis a pulvinar, rhoncus sagittis ut nunc elit! Sociis in et?</p>--}}
                    {{--<footer>Someone famous in--}}
                    {{--<cite title="Source Title"> - Source Title</cite>--}}
                    {{--</footer>--}}
                    {{--</blockquote>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</li>--}}
                    {{--</ul>--}}
                    {{--<!-- 2014 -->--}}
                    {{--<ul class="timeline">--}}
                    {{--<li class="timeline-line"></li>--}}
                    {{--<li class="timeline-group">--}}
                    {{--<a href="#" class="btn btn-primary"><i class="fa fa-calendar-o"></i> 2014</a>--}}
                    {{--</li>--}}
                    {{--</ul>--}}
                    {{--<ul class="timeline">--}}
                    {{--<li class="timeline-line"></li>--}}
                    {{--<!-- /.timeline-line -->--}}
                    {{--<li class="timeline-item block">--}}
                    {{--<div class="timeline-badge">--}}
                    {{--<a title="" data-context="inverse" data-container="body" class="border-silc" href="#" data-original-title="block highlight"></a>--}}
                    {{--</div>--}}
                    {{--<div class="timeline-card card border-grey border-lighten-2">--}}
                    {{--<div class="card-header">--}}
                    {{--<div class="text-xs-center">--}}
                    {{--<p><i class="fa fa-map-marker font-medium-4"></i></p>--}}
                    {{--<h4>Moved to Brooklyn</h4>--}}
                    {{--<p class="timeline-date">Jan 1, 2014</p>--}}
                    {{--<p>Eu pid nunc urna integer, sed, cras tortor scelerisque penatibus facilisis a pulvinar, rhoncus sagittis ut nunc elit! Sociis in et? Rhoncus, vel dignissim in scelerisque. Dolor lacus pulvinar adipiscing adipiscing montes! Elementum risus adipiscing non, cras scelerisque risus penatibus? Massa vut, habitasse, tincidunt!</p>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="card-body collapse in">--}}
                    {{--<div class="card-block">--}}
                    {{--<div id="moved-brooklyn" class="height-450"></div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</li>--}}
                    {{--</ul>--}}
                    {{--<!-- 2014 -->--}}
                    {{--<ul class="timeline">--}}
                    {{--<li class="timeline-line"></li>--}}
                    {{--<li class="timeline-group">--}}
                    {{--<a href="#" class="btn btn-primary"><i class="fa fa-calendar-o"></i> Founded in 2012</a>--}}
                    {{--</li>--}}
                    {{--</ul>--}}
                    {{--</section>--}}
                </div>

            </div>
        </div>
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->
    <div class="modal fade" id="modal-map" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">View Map</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8" id="map"></div>
                        <div class="list-group-item col-md-12" id="instructions"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>

        <div class="modal fade" id="enlargeImageModal" tabindex="-1" role="dialog" aria-labelledby="enlargeImageModal">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <img src="" class="enlargeImageModalSource" style="width: 100%;">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>

        </div>
        @endsection

        @section('header')
            <link rel="stylesheet" type="text/css" href="{{asset('assets/system/css/core/menu/menu-types/vertical-menu.css')}}">
            <link rel="stylesheet" type="text/css" href="{{asset('assets/system/css/core/menu/menu-types/vertical-overlay-menu.css')}}">
            <link rel="stylesheet" type="text/css" href="{{asset('assets/system/css/pages/users.css')}}">
            <link rel="stylesheet" type="text/css" href="{{asset('assets/system/css/pages/timeline.css')}}">

            <link rel="stylesheet" type="text/css" href="{{asset('assets/system/treegrid/jquery.treegrid.css')}}">

            <style>
                #map{
                    height: 500px !important;
                    width: 100% !important;
                }
                .zoomImage{
                    cursor: zoom-in;
                }
            </style>
        @endsection

        @section('footer')

            <script type="text/javascript" src="{{asset('assets/system/treegrid/jquery.treegrid.js')}}"></script>
            <script type="text/javascript" src="{{asset('assets/system/treegrid/jquery.treegrid.bootstrap3.js')}}"></script>



            <script src="//maps.googleapis.com/maps/api/js?key={{env('gmap_key')}}" type="text/javascript" async defer></script>
            <script src="//cdnjs.cloudflare.com/ajax/libs/gmaps.js/0.4.25/gmaps.min.js" type="text/javascript"></script>

            <script type="text/javascript">


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

                $(document).ready(function() {
                    $('#product-list,#merchant-staff').treegrid({
                        expanderExpandedClass: 'fa fa-minus',
                        expanderCollapsedClass: 'fa fa-plus'
                    });





                    $('#merchant-branches').DataTable({
                        "iDisplayLength": 10,
                        processing: true,
                        serverSide: true,
                        "order": [[ 0, "desc" ]],
                        "ajax": {
                            "url": "{{url()->full()}}",
                            "type": "GET",
                            "data": function(data){
                                data.isBranches = "true";
                            }
                        }
                    });

                    $('#contract-table').DataTable({
                        "iDisplayLength": 10,
                        processing: true,
                        serverSide: true,
                        "order": [[ 0, "desc" ]],
                        "ajax": {
                            "url": "{{url()->full()}}",
                            "type": "GET",
                            "data": function(data){
                                data.isContract= "true";
                            }
                        }
                    });


                });

            </script>

            <script>
                $('.zoomImage').on('click', function() {
                    $('.enlargeImageModalSource').attr('src', $(this).attr('src'));
                    $('#enlargeImageModal').modal('show');
                });
            </script>
@endsection
