@extends('backend.seller.layouts.master')
@section('title','Agrosiaa | Customer')
@include('backend.partials.common.nav')
@section('css')
    <link rel="stylesheet" type="text/css" href="/assets/frontend/global/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="/assets/frontend/global/css/mCustomScrollbar.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/frontend/global/css/styles/style.css">
    <link href="/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/jstree/dist/themes/default/style.css" rel="stylesheet" type="text/css" />

@endsection
@section('content')
    <!-- BEGIN CONTAINER -->
    <div class="page-content">
        <div class="container">
            <!-- BEGIN PAGE CONTENT INNER -->
            <div class="page-content-inner">
                <div class="row">
                    @include('backend.partials.error-messages')
                    <input type="hidden" id="base_url" value="{{env('BASE_URL')}}">
                    <input type="hidden" id="crm_customer_id" value="{{$id}}">
                    @if($id == 'null' && $user['role_id'] == 2)
                    <div class="col-md-12 col-md-offset-11">
                        @if($customerInfo->profile->first_name != null || $customerInfo->profile->last_name != null)
                        <a href="/crm/create-lead/{{$user['id']}}/{{$mobile}}" class="btn green">Create Lead</a>
                        @else
                        <a href="javascript:void(0);" class="btn green" data-toggle="modal" data-target="#profile-edit-modal">
                            Create Lead
                        </a>
                        @endif
                    </div>
                    @endif
                    @if($id != 'null')
                    <div class="col-md-12 col-md-offset-9">
                        <a href="#" onclick="chatHistory('{{$id}}','{{$mobile}}')" class="btn yellow">Make a Log </a>
                        <a href="#" id="place_order_button" class="btn blue">Place Order </a>
                        <a href="#" id="schedule-button" class="btn red-intense">Schedule </a>
                    </div>
                    @endif
                </div>
                <hr>
                <div class="row">
                <div class="col-md-6">
                    <div class="portlet yellow box">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-user font-violet"></i>
                                <span class="caption-subject font-violet bold uppercase">Profile</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="scroller" style="height: 250px;" data-always-visible="1" data-rail-visible="0">
                                <div class="row static-info">
                                    <div class="col-md-5 name"> Customer Name: </div>
                                    <div class="col-md-7 value"> {{ucwords($customerInfo->profile->first_name)}}  {{ucwords($customerInfo->profile->last_name)}} </div>
                                </div>
                                <div class="row static-info">
                                    <div class="col-md-5 name"> Mobile No: </div>
                                    <div class="col-md-7 value"> {{$customerInfo->profile->mobile}} </div>
                                </div>
                                @if($customerInfo->profile->email != null)
                                <div class="row static-info">
                                    <div class="col-md-5 name"> Email: </div>
                                    <div class="col-md-7 value"> {{$customerInfo->profile->email}} </div>
                                </div>
                                @endif
                                @if($customerInfo->profile->dob != null)
                                <div class="row static-info">
                                    <div class="col-md-5 name"> Date of Birth: </div>
                                    <div class="col-md-7 value"> {{$customerInfo->profile->dob}} </div>
                                </div>
                                @endif
                            </div>
                            <a href="javascript:void(0);" class="btn blue m-icon" data-toggle="modal" data-target="#profile-edit-modal">
                                Edit
                            </a>
                            <div class="scroller-footer">
                            </div>
                        </div>
                        <div class="col-md-6">

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="portlet green box">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon- font-violet"></i>
                                <span class="caption-subject font-violet bold uppercase">Addresses</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            @if(empty($customerInfo->address))
                            <div id="no_product_div" style="text-align: center">
                                <h5>No address added yet</h5>
                            </div>
                            @else
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                @foreach($customerInfo->address as $key=>$value)
                                @if($key == 0)
                                <li class="nav-item col-md-3 active">
                                    <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#address{{$key}}" role="tab" aria-controls="pills-achievements" aria-selected="true">Address {{$key + 1}}</a>
                                </li>
                                @else
                                <li class="nav-item col-md-3">
                                    <a class="nav-link" id="pills-home-tab" data-toggle="pill" href="#address{{$key}}" role="tab" aria-controls="pills-achievements" aria-selected="true">Address {{$key + 1}}</a>
                                </li>
                                @endif
                                @endforeach
                            </ul>
                            <div class="tab-content" id="pills-tabContent" style="height:50%;">
                                @foreach($customerInfo->address as $key=>$value)
                                @if($key == 0)
                                <div class="tab-pane fade active in" id="address{{$key}}" onscroll="" role="tabpanel" aria-labelledby="pills-home-tab" style="height: 260px;overflow-x: scroll">
                                    <div class="row" style="border-bottom: 1px solid #b2b2b2; padding: 10px;background-color: #fefefe;">
                                        <div class="col-md-12"><i>Full Name : </i> <span style="color: #000000">{{$value->full_name}}</span></div>
                                        <div class="col-md-12"><i>Mobile : </i> <span style="color: #000000">{{$value->mobile}}</span></div>
                                        <div class="col-md-12"><i>House/Block Number : </i> <span style="color: #000000">{{$value->flat_door_block_house_no}}</span></div>
                                        <div class="col-md-12"><i>Name of Premise/Building/Village : </i> <span style="color: #000000">{{$value->name_of_premise_building_village}}</span></div>
                                        <div class="col-md-12"><i>Area/Locality : </i> <span style="color: #000000">{{$value->area_locality_wadi}}</span></div>
                                        <div class="col-md-12"><i>Road/Street/Lane : </i> <span style="color: #000000">{{$value->road_street_lane}}</span></div>
                                        <div class="col-md-12"><i>Post : </i> <span style="color: #000000">{{$value->at_post}}</span></div>
                                        <div class="col-md-12"><i>Taluka : </i> <span style="color: #000000">{{$value->taluka}}</span></div>
                                        <div class="col-md-12"><i>District : </i> <span style="color: #000000">{{$value->district}}</span></div>
                                        <div class="col-md-12"><i>State : </i> <span style="color: #000000">{{$value->state}}</span></div>
                                        <div class="col-md-12"><i>Pincode : </i> <span style="color: #000000">{{$value->pincode}}</span></div>
                                    </div>
                                </div>
                                @else
                                <div class="tab-pane fade active" id="address{{$key}}" onscroll="" role="tabpanel" aria-labelledby="pills-home-tab" style="height: 260px;overflow-x: scroll">
                                    <div class="row" style="border-bottom: 1px solid #b2b2b2; padding: 10px;background-color: #fefefe;">
                                        <div class="col-md-12"><i>Full Name : </i> <span style="color: #000000">{{$value->full_name}}</span></div>
                                        <div class="col-md-12"><i>Mobile : </i> <span style="color: #000000">{{$value->mobile}}</span></div>
                                        <div class="col-md-12"><i>House/Block Number : </i> <span style="color: #000000">{{$value->flat_door_block_house_no}}</span></div>
                                        <div class="col-md-12"><i>Name of Premise/Building/Village : </i> <span style="color: #000000">{{$value->name_of_premise_building_village}}</span></div>
                                        <div class="col-md-12"><i>Area/Locality : </i> <span style="color: #000000">{{$value->area_locality_wadi}}</span></div>
                                        <div class="col-md-12"><i>Road/Street/Lane : </i> <span style="color: #000000">{{$value->road_street_lane}}</span></div>
                                        <div class="col-md-12"><i>Post : </i> <span style="color: #000000">{{$value->at_post}}</span></div>
                                        <div class="col-md-12"><i>Taluka : </i> <span style="color: #000000">{{$value->taluka}}</span></div>
                                        <div class="col-md-12"><i>District : </i> <span style="color: #000000">{{$value->district}}</span></div>
                                        <div class="col-md-12"><i>State : </i> <span style="color: #000000">{{$value->state}}</span></div>
                                        <div class="col-md-12"><i>Pincode : </i> <span style="color: #000000">{{$value->pincode}}</span></div>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="portlet purple box">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon- font-violet"></i>
                                <span class="caption-subject font-violet bold uppercase">Customer Tag Cloud</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="bootstrap-tagsinput" id="customer-tag-div">
                                @foreach($customerTags as $customerTag)
                                @if(isset($customerTag['tag_type_name']))
                                <button id="tag{{$customerTag['tag_cloud_id']}}{{$customerTag['crm_customer_id']}}" class="lable" style="background-color:rgb(241 243 244);display: inline;font-size: 90%;margin-left: 2px;margin-top:3px;margin-bottom:3px;padding-bottom: 2px;padding-top: 2px">{{$customerTag['name']}}&nbsp;<span style="border-radius: 4px !important;background: green;">{{$customerTag['tag_type_name']}}</span>&nbsp;<span style="color: red;" onclick="removeCustTag({{$customerTag['tag_cloud_id']}},{{$customerTag['crm_customer_id']}})">&nbsp;×</span></button>&nbsp;&nbsp;&nbsp;
                                @else
                                <button id="tag{{$customerTag['tag_cloud_id']}}{{$customerTag['crm_customer_id']}}" class="lable" style="background-color: rgb(241 243 244);display: inline;font-size: 90%;margin-left: 2px;margin-top:3px;margin-bottom:3px;padding-bottom: 2px;padding-top: 2px">{{$customerTag['name']}}<span style="color: red;" onclick="removeCustTag({{$customerTag['tag_cloud_id']}},{{$customerTag['crm_customer_id']}})">&nbsp;×</span></button>&nbsp;&nbsp;&nbsp;
                                @endif
                                @endforeach
                            </div>
                            <div class="logo-wrap">
                                <div class=container>
                                    <div class="menu clearfix">
                                        <ul class="clearfix">
                                            <li class="select-category" id="search_header_main">
                                                <input type="text" id="tag_name" class="typeahead" placeholder="Search Tag" style=""/>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="portlet blue box">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon- font-violet"></i>
                                <span class="caption-subject font-violet bold uppercase">Order Details</span>
                                <input type="hidden" id="customer_mobile" value="{{$customerInfo->profile->mobile}}">
                            </div>
                        </div>
                        <div class="portlet-body">
                            <ul class="nav nav-pills mb-12" id="pills-tab" role="tablist">
                                <li class="nav-item active">
                                    <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#customer-order" role="tab" aria-controls="pills-achievements" aria-selected="true" style="width:255px">Orders</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#customer-return" role="tab" aria-controls="pills-annoucement" aria-selected="false" style="width:255px">Return</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade active in" id="customer-order" onscroll="" role="tabpanel" aria-labelledby="pills-home-tab" style="height: 800px;overflow-y: scroll">
                                    <table class="table table-striped table-bordered table-hover table-checkable" id="sales_admin_list">
                                        <thead>
                                        <tr role="row" class="heading">
                                            <th width="10%"> Order&nbsp;No </th>
                                            <th width="15%"> Order Date </th>
                                            <th width="20%"> Products </th>
                                            <th width="5%"> Qnt </th>
                                            <th width="10%"> SKUID </th>
                                            <th width="8%"> Status </th>
                                            <th width="8%"> Shipment </th>
                                            <th width="10%"> AWB NO </th>
                                            <th width="10%"> Total </th>
                                        </tr>
                                        <tr role="row" class="filter">
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="order_no"> </td>
                                            <td></td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="product"> </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="quantity"> </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="skuid"> </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="status"> </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="shipment"> </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="awb_no"> </td>
                                            <td>
                                                <div class="margin-bottom-5">
                                                    <button class="btn btn-sm btn-success filter-submit margin-bottom">
                                                        <i class="fa fa-search"></i> Search</button>
                                                </div>
                                                <button class="btn btn-sm btn-default filter-cancel">
                                                    <i class="fa fa-times"></i> Reset</button>
                                            </td>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="customer-return" role="tabpanel" aria-labelledby="pills-profile-tab" style="height: 200px;overflow-y: scroll">
                                    @if($customerInfo->returns == null)
                                    <div class="col-md-12" style="text-align: center;margin-top: 10%"><i><b>There are No Order Return for this Customer</b></i></div>
                                    @else
                                    @foreach($customerInfo->returns as $order)
                                    <div class="row" style="border-bottom: 1px solid #b2b2b2; padding: 10px;background-color: #fefefe;">
                                        <div class="col-md-12" style="text-align: right; color: lightcoral"><i>{!! date('dS M Y',strtotime($order->created_at)) !!}</i></div>
                                        <div class="col-md-12"><i>Order Number : </i> <span style="color: #000000">{{$order->id}}</span></div>
                                        <div class="col-md-12"><i>Product : </i> {{$order->product_name}}</div>
                                        <div class="col-md-12"><i>Qty : </i><span style="color: #007AFF">{{$order->quantity}}</span> </div>
                                        <div class="col-md-12"><i>Status : </i><span style="color: #007AFF">{{$order->status}}</span></div>
                                        <div class="col-md-12"><i>Consignment Number : </i> {{$order->consignment_number}}</div>
                                        <div class="col-md-12"><i>Payment Mode : </i> {{$order->payment_mode}}</div>
                                        <div class="col-md-12"><i>Grand Total : </i> {{$order->subtotal}}</div>
                                    </div>
                                    @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="scroller-footer">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="portlet blue box">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon- font-violet"></i>
                                <span class="caption-subject font-violet bold uppercase">Abandoned Cart Listing</span>
                                <input type="hidden" id="customer_mobile" value="{{$customerInfo->profile->mobile}}">
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="table-container">
                                <table class="table table-striped table-bordered table-hover table-checkable" id="abandoned_cart_listing">
                                    <thead>
                                    <tr role="row" class="heading">
                                        <th width="15%"> Registered From </th>
                                        <th width="20%"> Created On </th>
                                        <th width="20%"> Updated On </th>
                                        <th width="10%"> Action </th>
                                    </tr>
                                    <tr role="row" class="filter">
                                        <td>
                                        </td>
                                        <td>
                                            <div class="input-group date date-picker">
                                                <input type="text" size="16" class="form-control form-filter" name="toDate" id="toDate" placeholder="to date">
                                                            <span class="input-group-btn">
                                                         <button class="btn default date-set" type="button">
                                                             <i class="fa fa-calendar"></i>
                                                         </button>
                                                     </span>
                                            </div>
                                            <div class="input-group date date-picker">
                                                <input type="text" size="16" class="form-control form-filter" name="fromDate" id="fromDate" placeholder="from date">
                                                            <span class="input-group-btn">
                                                         <button class="btn default date-set" type="button">
                                                             <i class="fa fa-calendar"></i>
                                                         </button>
                                                     </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group date date-picker">
                                                <input type="text" size="16" class="form-control form-filter" name="toUpdatedDate" id="toUpdatedDate" placeholder="to date">
                                                            <span class="input-group-btn">
                                                         <button class="btn default date-set" type="button">
                                                             <i class="fa fa-calendar"></i>
                                                         </button>
                                                     </span>
                                            </div>
                                            <div class="input-group date date-picker">
                                                <input type="text" size="16" class="form-control form-filter" name="fromUpdatedDate" id="fromUpdatedDate" placeholder="from date">
                                                            <span class="input-group-btn">
                                                         <button class="btn default date-set" type="button">
                                                             <i class="fa fa-calendar"></i>
                                                         </button>
                                                     </span>
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn btn-xs blue filter-submit"> Search <i class="fa fa-search"></i> </button>
                                            <button class="btn btn-xs default filter-cancel"> Reset <i class="fa fa-undo"></i> </button>
                                        </td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                <div id="schedule_modal" class="modal fade bs-modal-md" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-md">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title" style="text-align: center"><b>Set Schedule for Next Call</b></h4>
                            </div>
                            <form class="form-horizontal" method="post" role="form" action="/crm/set-schedule">
                                <input type="hidden" name="cust_detail_id" value="{{$id}}">
                                <div class="modal-body">
                                    <div class="form-group">
                                        {{csrf_field()}}
                                        <label class="control-label col-sm-4">Reminder Time</label>
                                        <div class="col-md-8">
                                            <div class="input-group date form_datetime input-large">
                                                <input type="text" size="16" name="reminder_time" class="form-control">
                                                        <span class="input-group-btn">
                                                            <button class="btn default date-set" type="button">
                                                                <i class="fa fa-calendar"></i>
                                                            </button>
                                                        </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8 col-md-offset-4">
                                            <button type="submit" class="btn btn-sm btn-success">Create</button>
                                            <button class="btn btn-sm btn-danger" data-dismiss="modal">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div id="reply" class="modal" role="dialog" data-dismiss="modal">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content" style="width: 590px">
                            <div class="modal-header" style="width: 580px">
                                <div class="col-md-7">
                                    <h4 class="modal-title reply-title" style="color: black"> </h4>
                                </div>
                                <div class="col-md-4">
                                    @if($user['role_id'] == 2)
                                        <select id="select-call-status" class="" style="-webkit-appearance: menulist; align-self: center">Select Call Status
                                            <option>Select Call Status</option>
                                            @foreach($callStatuses as $callStatus)
                                            <option value="{!! $callStatus['id'] !!}">{!! $callStatus['name'] !!}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="close pull-right" data-dismiss="modal">&times;</button>
                                </div>
                            </div>
                            <div class="modal-body" style="width: 580px">
                                <div class="row">
                                    <div class="col-md-12" >
                                        <div class="portlet light" style="background-image: url(/assets/global/img/chat-background.jpg);">
                                            <div class="portlet-body" >
                                                <div id="chat_scroll_div" class="scroller scro" style="height: 338px;" data-always-visible="1" data-rail-visible1="0" data-handle-color="#D7DCE2">
                                                    <div class="general-item-list" id="chat_message">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row" id="query-form">
                                    <div class="col-md-10">
                                        <input type="text" name="reply_text" id="reply_text" class="col-md-10" maxlength="500"  placeholder="reply" required>
                                        <input type="hidden" id="customer_detail_id" value="">
                                        <input type="hidden" id="customer_detail_mobile" value="">
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-sm btn-success table-group-action-submit chat-submit pull-right">Reply</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{--Modal For place Orders--}}
                <div id="place_order" class="modal fade bs-modal-lg" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title" style="text-align: center"><b>Step 1- Enter Address Information</b></h4>
                                <div>
                                    <a id="select_product_modal" class="text-right"><h4>Next</h4></a>
                                </div>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <form method="post" role="form" action="/customer/add-address" >
                                            {{ csrf_field() }}
                                            <input type="hidden" value="{{$customerInfo->profile->id}}" name="customer_user_id">
                                            <input class="form-control" type="hidden" value="{{$id}}" name="crm_customer_id">
                                            <input class="form-control" type="hidden" value="{{$mobile}}" name="customer_mobile">
                                            <div class="address-form">
                                                <div class="form-group">
                                                    <label for="company">Full Name</label><span class="required">*</span>
                                                    <input type="text" class="form-control" name="full_name" id="full_name" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="mobile">Mobile</label><span class="required">*</span>
                                                    <input type="text" class="form-control" name="mobile" id="mobile" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="company">Flat/Door/Block No.</label><span class="required">*</span>
                                                    <input type="text" class="form-control" name="flat_door_block_house_no" id="flat_door_block_house_no" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="company">Name of the Premise/Building/Village</label><span class="required">*</span>
                                                    <input type="text" class="form-control" name="name_of_premise_building_village" id="name_of_premise_building_village" required>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="area">Area/Locality/Wadi</label><span class="required">*</span>
                                                            <input class="form-control area" id="area" name="area_locality_wadi" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="post">Road/Street/Lane</label><span class="required">*</span>
                                                            <div id="at-post">
                                                                <input class="form-control" type="text" id="road_street_lane" name="road_street_lane" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="pin">Pin</label><span class="required">*</span>
                                                            <input class="form-control pincode typeahead" type="text" id="pincode" name="pincode" required>
                                                            <span style="color: darkred"><h7>Make sure you choose the exact pincode from the dropdown values only</h7></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="post">Post</label><span class="required">*</span>
                                                            <div id="at-post">
                                                                <select class="form-control" name="at_post" id="atPost" required>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="state">State</label><span class="required">*</span>
                                                            <input class="form-control state" type="text" id="stateName" name="state" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="dist">District</label><span class="required">*</span>
                                                            <input type="text" class="form-control" name="district" id="district" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="taluka">Taluka</label><span class="required">*</span>
                                                            <input type="text" class="form-control" name="taluka" id="taluka" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if(count($customerInfo->address) < 3)
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary btn-icon">Add new address</button>
                                                </div>
                                                @endif
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-right">
                                            <h6 class="text-danger" id="select_address_msg" hidden>Please select address</h6>
                                        </div>
                                        <div class="address-list-wrap">
                                            <label>Saved Address</label>
                                            <div class="address-list" id="address-list">
                                                @if($customerInfo->address != null)
                                                @foreach($customerInfo->address as $address)
                                                <div class="address-item" id="address_{{$address->id}}">
                                                    <input type="radio" name="customer_address_id" value="{{$address->id}}" style="width: 30px; height: 30px">
                                                    <div class="full-address" id="delivery_address_{!! $address->id !!}">
                                                        <div class="name">{{ucwords($address->full_name)}}</div>
                                                        <div class="mobile"><span><i class="fa fa-phone"></i> {{$address->mobile}}</span></div>
                                                        <div class="address">{{$address->flat_door_block_house_no}}, {{$address->name_of_premise_building_village}}, {{$address->area_locality_wadi}}, {{$address->road_street_lane}}, {{$address->at_post}}, {{$address->taluka}}, {{$address->district}} - {{$address->pincode}}, {{ucwords(strtolower($address->state))}}, INDIA</div>
                                                    </div>
                                                    <div class="col-md-12 col-sm-4">
                                                        <div class="edit-delete-btns">
                                                            <button type="button" class="btn-edit btn btn-success" data-edit="{{$address->id}}">Edit address</button>
                                                            <button type="button" class="btn-delete btn btn-danger" data-delete="{{$address->id}}">delete address</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <form class="form-horizontal" method="post" role="form" action="/customer/create-order" >
                    {{ csrf_field() }}
                    <div id="select_products" class="modal fade bs-modal-md" tabindex="-1" role="dialog" style="height: 500%">
                        <div class="modal-dialog modal-lg">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title" style="text-align: center"><b>Step 2- Add Products For Checkout</b></h4>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <a id="place_order_modal"><h4 style="text-align: left">Previous</h4></a>
                                        </div>
                                        <div class="col-sm-6">
                                            <a id="confirm_order_modal"><h4 style="text-align: right">Next</h4></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <div class="logo-wrap">
                                        <div class=container>
                                            <div class="menu clearfix">
                                                <ul class="clearfix">
                                                    <li class="select-category" id="search_header_main">
                                                        <input type="text" id="product_name" class="typeahead" placeholder=" Search Products" style=""/>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <h4>Checkout Preview</h4>
                                    <div id="check_out_preview">
                                    </div>
                                    <div id="no_product_div" style="text-align: center">
                                        <h5>No Product Added for Checkout Yet</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="confirm_order" class="modal fade bs-modal-lg" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-lg">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title" style="text-align: center"><b>Step 3- Confirm Order</b></h4>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <a id="select_order_modal"><h4 style="text-align: left">Previous</h4></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="row">
                                            <h4 class="col-md-offset-1"><b>Order Summary</b></h4>
                                        </div>
                                        <div class="row col-md-offset-1" id="selected_products">
                                        </div>
                                        <hr>
                                        <div class="row" id="del_charge_div" hidden="">
                                            <div class="col-md-3 col-md-offset-7 text-right">
                                                <h4>Delivery Charges:</h4>
                                            </div>
                                            <div class="col-md-1">
                                                <h4 class="pull-right">50</h4>
                                            </div>
                                        </div>
                                        <div class="row" id="discount_div" hidden="">
                                            <div class="col-md-3 col-md-offset-7 text-right">
                                                <h4>Agrosiaa Discount:</h4>
                                            </div>
                                            <div class="col-md-1">
                                                <h4 class="pull-right" id="discount_val"></h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2 col-md-offset-8 text-right">
                                                <h4>Total:</h4>
                                            </div>
                                            <div class="col-md-1">
                                                <h4 class="pull-right" id="order_total"></h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="row">
                                            <h4 class="col-md-offset-1"><b>Delivery Address</b></h4>
                                        </div>
                                        <div class="row col-md-offset-1" id="delivery_address">
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="row">
                                            <h4 class="col-md-offset-1"><b>Referral Code</b></h4>
                                        </div>
                                        <div class="row col-md-offset-1">
                                            <div class="col-md-5">
                                                <input type="text" class="form-control" id="referral_code" name="referral_code" placeholder="Enter referral code" style=""/>
                                            </div>
                                            <div class="col-md-1">
                                                <a class="btn btn-primary" id="apply_referral">Apply</a>
                                            </div>
                                        </div>
                                        <div class="row col-md-offset-1">
                                            <div class="col-md-8">
                                                <span id="referal_code_valid"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-11">
                                            <input class="form-control" type="hidden" id="address_id" name="address_id" value="">
                                            <input class="form-control" type="hidden" value="{{$customerInfo->profile->id}}" name="cust_id">
                                            <input class="form-control" type="hidden" value="{{$user->id}}" name="sales_id">
                                            <input class="form-control" type="hidden" value="{{$id}}" name="crm_customer_id">
                                            <input class="form-control" type="hidden" value="{{$mobile}}" name="customer_mobile">
                                            <button type="submit" class="btn btn-primary btn-icon pull-right">Confirm Order</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                {{--End of modal for place orders--}}
                {{--Modal for Profile edit--}}
                <div id="profile-edit-modal" class="modal fade bs-modal-md" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-md">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title" style="text-align: center"><b>Customer Profile Edit</b></h4>
                            </div>
                            <hr>
                            <form method="post" action="/customer/edit-customer">
                                {{ csrf_field() }}
                                @if($id == 'null')
                                <input type="hidden" name="create_lead" value="true">
                                @endif
                                <input type="hidden" value="{{$customerInfo->profile->id}}" name="user_id">
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">First Name : </label>
                                                <div class="col-md-6">
                                                    @if($id == 'null')
                                                    <input type="text" class="form-control" id="f_name" value="{{ucwords($customerInfo->profile->first_name)}}" name="f_name" required>
                                                    @else
                                                    <input type="text" class="form-control" id="f_name" value="{{ucwords($customerInfo->profile->first_name)}}" name="f_name" readonly>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Last Name :</label>
                                                <div class="col-md-6">
                                                    @if($id == 'null')
                                                    <input type="text" class="form-control" id="l_name" value="{{ucwords($customerInfo->profile->last_name)}}" name="l_name" required>
                                                    @else
                                                    <input type="text" class="form-control" id="l_name" value="{{ucwords($customerInfo->profile->last_name)}}" name="l_name" readonly>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Birth date : </label>
                                                <div class="col-md-6">
                                                    <input type="date" class="form-control" id="dob" value="{{$customerInfo->profile->dob}}" name="dob">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Email id : </label>
                                                <div class="col-md-6">
                                                    @if($id == 'null')
                                                    <input type="email" class="form-control" id="profile_email" value="{{$customerInfo->profile->email}}" name="profile_email">
                                                    @else
                                                    <input type="email" class="form-control" id="profile_email" value="{{$customerInfo->profile->email}}" name="profile_email" readonly>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Mobile Number : <span class="required">*</span></label>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" id="profile_mobile" value="{{ucwords($customerInfo->profile->mobile)}}" name="profile_mobile" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-10">
                                            <button type="submit" class="btn btn-sm btn-success pull-right">Save</button>
                                        </div>
                                        <div class="col-md-2">
                                            <button class="btn btn-sm btn-danger pull-right" data-dismiss="modal">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                {{--END of Modal for Profile edit--}}

                {{--Modal for edit address--}}
                @if($customerInfo->address != null)
                @foreach($customerInfo->address as $address)
                <div id="edit_address_{{$address->id}}" class="modal fade bs-modal-md" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-md">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title" style="text-align: center"><b>Edit Address</b></h4>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form method="post" role="form" action="/customer/edit-address" >
                                            {{ csrf_field() }}
                                            <input type="hidden" value="{{$address->id}}" name="id">
                                            <input class="form-control" type="hidden" value="{{$id}}" name="crm_customer_id">
                                            <input class="form-control" type="hidden" value="{{$mobile}}" name="customer_mobile">
                                            <div class="address-form">
                                                <div class="form-group">
                                                    <label for="company">Full Name</label><span class="required">*</span>
                                                    <input type="text" class="form-control" name="full_name" id="full_name_{{$address->id}}" value="{{$address->full_name}}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="mobile">Mobile</label><span class="required">*</span>
                                                    <input type="text" class="form-control" name="mobile" id="mobile_{{$address->id}}" value="{{$address->mobile}}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="company">Flat/Door/Block No.</label><span class="required">*</span>
                                                    <input type="text" class="form-control" name="flat_door_block_house_no" id="flat_door_block_house_no_{{$address->id}}" value="{{$address->flat_door_block_house_no}}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="company">Name of the Premise/Building/Village</label><span class="required">*</span>
                                                    <input type="text" class="form-control" name="name_of_premise_building_village" id="name_of_premise_building_village_{{$address->id}}" value="{{$address->name_of_premise_building_village}}" required>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="area">Area/Locality/Wadi</label><span class="required">*</span>
                                                            <input class="form-control area" id="area_{{$address->id}}" name="area_locality_wadi" value="{{$address->area_locality_wadi}}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="post">Road/Street/Lane</label><span class="required">*</span>
                                                            <div id="at-post">
                                                                <input class="form-control" type="text" id="road_street_lane_{{$address->id}}" name="road_street_lane" value="{{$address->road_street_lane}}" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="pin">Pin</label><span class="required">*</span>
                                                            <input class="form-control edit-pincode typeahead" type="text" id="pincode_{{$address->id}}" name="pincode" value="{{$address->pincode}}" required>
                                                            <span style="color: darkred"><h7>Make sure you choose the exact pincode from the dropdown values only</h7></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="post">Post</label><span class="required">*</span>
                                                            <div id="at-post">
                                                                <select class="form-control edit-atPost" name="at_post" id="atPost_{{$address->id}}" required>
                                                                    <option value="{{$address->at_post}}">{{$address->at_post}}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="state">State</label><span class="required">*</span>
                                                            <input class="form-control edit-stateName" type="text" id="stateName_{{$address->id}}" name="state" value="{{$address->state}}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="dist">District</label><span class="required">*</span>
                                                            <input type="text" class="form-control edit-district" name="district" id="district_{{$address->id}}" value="{{$address->district}}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="taluka">Taluka</label><span class="required">*</span>
                                                            <input type="text" class="form-control edit-taluka" name="taluka" id="taluka_{{$address->id}}" value="{{$address->taluka}}" readonly>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary btn-icon">Edit address</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif

                {{--End of Modal for Edit Address--}}
                {{--Modal For Abandoned Details--}}
                <div id="AbandonedDetailModal" class="modal container fade" tabindex="-1">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">Abandoned Cart Details</h4>
                        </div>
                        <div class="modal-body">
                        </div>
                        <div class="modal-footer">
                            <button type="button" data-dismiss="modal" class="btn btn-outline dark">Close</button>
                        </div>
                    </div>
                </div>
                {{--End Modal--}}
                <!-- END PAGE CONTENT INNER -->
            </div>
        </div>
    <!-- END CONTENT BODY -->
    </div>
    <!-- END CONTENT -->
    <!-- END CONTAINER -->
@endsection
@section('javascript')
    <!-- BEGIN CORE PLUGINS -->
    <script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
    <!-- END CORE PLUGINS -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="/assets/global/scripts/datatable.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL SCRIPTS -->
    <script src="/assets/global/scripts/app.min.js" type="text/javascript"></script>
    <!-- END THEME GLOBAL SCRIPTS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script type="text/javascript" src="/assets/frontend/custom/registration/js/typeahead.bundle.js"></script>
    <script type="text/javascript" src="/assets/frontend/custom/registration/js/handlebars-v3.0.3.js"></script>
    <script src="/assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
    <script src="/assets/custom/pincode/addresses.js"></script>
    <script src="/assets/custom/pincode/editaddress.js"></script>
    <script src="/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
        <script src="/assets/pages/scripts/customer/order/ecommerce-orders.min.js" type="text/javascript"></script>
        <script src="/assets/pages/scripts/customer/abandonedCart/ecommerce-orders.min.js" type="text/javascript"></script>

        <!-- END PAGE LEVEL SCRIPTS -->
    <!-- BEGIN THEME LAYOUT SCRIPTS -->
    <script src="/assets/layouts/layout3/scripts/layout.min.js" type="text/javascript"></script>
    <script src="/assets/layouts/layout3/scripts/demo.min.js" type="text/javascript"></script>
    <script>

        $(document).ready(function () {
            $('#place_order').modal('hide');

            $('#product_name').on('select',function () {
                $('#product_name').val('');
            });
            var productList = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.obj.whitespace('office_name'),
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                remote: {
                    url: "{{env('BASE_URL')}}/get-products?product_name=%QUERY",
                    filter: function(x) {
                        return $.map(x, function (data) {
                            return {
                                id: data.id,
                                name: data.name,
                                company: data.company,
                                translated_name: data.translated_name,
                                position: data.position,
                                slug: data.slug,
                                btn_class: data.class,
                                url_param: data.url_param,
                                translated_slug:data.translated_slug,
                                price:data.discounted_price,
                                sku:data.seller_sku,
                                minimum_quantity:data.minimum_quantity,
                                maximum_quantity:data.maximum_quantity
                            };
                        });
                    },
                    wildcard: "%QUERY"
                }
            });
            var language = $('#language').val();
            productList.initialize();
            $('#product_name').typeahead(null, {
                displayKey: 'name',
                engine: Handlebars,
                source: productList.ttAdapter(),
                limit: 30,
                templates: {
                    empty: [
                        '<div class="empty-message">',
                        'Unable to find any Result that match the current query',
                        '</div>'
                    ].join('\n'),
                    suggestion: Handlebars.compile('<div style="text-transform: capitalize;"><strong>@{{translated_name}}</strong><span class="@{{btn_class}}">@{{sku}}</span><span><i class="fa fa-rupee"></i>@{{ price }}</span></div>')
                }
            }).on('typeahead:selected', function (obj, datum) {
                var POData = new Array();
                POData = $.parseJSON(JSON.stringify(datum));
                POData.name = POData.name.replace(/\&/g,'%26');
                str = '<div class="row" id="div_'+POData.id+'">'+
                        '<div class="col-md-7"><h5>'+POData.name+'<span class="tag label label-info" style="margin-left: 2px">'+POData.company+'</span></h5>'+
                        '</div>'+
                        '<div class="col-md-3">'+
                                '<a class="btn" onclick="updateProductQuantity('+POData.id+',false,'+POData.price+','+POData.minimum_quantity+','+POData.maximum_quantity+')" >-</a>'+
                                '<input class="cart-quantity" type="text" id="product_'+POData.id+'" value='+POData.minimum_quantity+' style="width: 30px; text-align: center" readonly>'+
                                '<a class="btn" onclick="updateProductQuantity('+POData.id+',true,'+POData.price+','+POData.minimum_quantity+','+POData.maximum_quantity+')">+</a>'+
                        '</div>'+
                        '<div class="col-md-2"><i class="fa fa-rupee"></i><span id="price_'+POData.id+'">'+POData.price * POData.minimum_quantity+'</span> &nbsp;&nbsp;<a><span onclick="removeProduct('+POData.id+')">x</span></a></div>'+
                        '</div>'+
                        '<input class="form-control product-list" type="hidden" id="product_id_'+POData.id+'" name="product_id[]" value="'+POData.id+'">'+
                        '<input class="form-control" type="hidden" id="product_qnt'+POData.id+'" name="product_qnt['+POData.id+']" value="1">';


                str2 = '<div class="row" id="selected_products_div_'+POData.id+'"><div class="col-md-7"><h5>'+POData.name+'<span class="tag label label-info" style="margin-left: 2px">'+POData.company+'</span></h5></div>'+
                    '<div class="col-md-3">'+
                    '<input class="cart-quantity" type="text" id="selected_product_qnt'+POData.id+'" value="1" style="width: 30px; text-align: center" readonly>'+
                    '</div>'+
                    '<div class="col-md-2"><i class="fa fa-rupee"></i><span class="product-price-total" id="products_price_'+POData.id+'">'+POData.price+'</span></div>'+
                    '</div>';
                $('#check_out_preview').append(str);
                $('#selected_products').append(str2);
                $('#no_product_div').hide();
            }).on('typeahead:open', function (obj, datum) {

                });
        });

        $(document).ready(function () {
            $('#tag_name').on('select',function () {
                $('#tag_name').val('');
            });
            var crmCustId = $('#crm_customer_id').val();
            var tagList = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.obj.whitespace('office_name'),
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                remote: {
                    url: "/get-tags?tag_name=%QUERY",
                    filter: function(x) {
                        return $.map(x, function (data) {
                            return {
                                id: data.id,
                                name: data.name
                            };
                        });
                    },
                    wildcard: "%QUERY"
                }
            });
            var language = $('#language').val();
            tagList.initialize();
            $('#tag_name').typeahead(null, {
                displayKey: 'name',
                engine: Handlebars,
                source: tagList.ttAdapter(),
                limit: 30,
                templates: {
                    empty: [
                        '<div class="empty-message">',
                        'Unable to find any Result that match the current query',
                        '</div>'
                    ].join('\n'),
                    suggestion: Handlebars.compile('<div style="text-transform: capitalize;"><strong>@{{name}}</strong></div>')
                }
            }).on('typeahead:selected', function (obj, datum) {
                var POData = new Array();
                POData = $.parseJSON(JSON.stringify(datum));
                POData.name = POData.name.replace(/\&/g,'%26');
                str = '<button id="tag'+POData.id+crmCustId+'" class="lable" style="display: inline;font-size: 90%;margin-left: 2px;margin-top:3px;margin-bottom:3px;padding-bottom: 2px;padding-top: 2px">'+POData.name+'<span style="color: red;" onclick="removeCustTag('+POData.id+','+crmCustId+')"> ×</span></button>&nbsp;&nbsp;&nbsp';
                $('#customer-tag-div').append(str);
                if(crmCustId != 'null'){
                    $.ajax({
                        url: '/tag/customer-tag',
                        type: 'POST',
                        dataType: 'array',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            'crm_cust_id' : crmCustId,
                            'tag_id' : POData.id
                        },
                        success: function (responce) {
                        },
                        error: function (responce) {
                        }
                    })
                }
            }).on('typeahead:open', function (obj, datum) {

            });
            $('#tag_name').keypress(function (e) {
                var key = e.which;
                if(key == '13'){
                    var singleQuote = "'";
                    var tagName = singleQuote+$('#tag_name').val()+singleQuote;
                    var tag = $('#tag_name').val().replace(/ /g,"_");
                    var tagStr = '<button id="tag'+tag+crmCustId+'" class="lable" style="display: inline;font-size: 90%;margin-left: 2px;margin-top:3px;margin-bottom:3px;padding-bottom: 2px;padding-top: 2px">'+tag+'<span style="color: red;" onclick="removeCustTag('+tagName+','+crmCustId+')"> ×</span></button>&nbsp;&nbsp;&nbsp';
                    $('#customer-tag-div').append(tagStr);
                    $.ajax({
                        url: '/customer/create-assign-tag',
                        type: 'POST',
                        dataType: 'array',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            'tag_name' : $('#tag_name').val(),
                            'customer_id' : crmCustId
                        },
                        success: function (status) {
                        },
                        error: function (status) {
                        }
                    })
                }
            });
        });

        function removeCustTag(tagId,crmCustId){
            var tag = 'tag'+tagId+crmCustId;
            tag = tag.replace(/ /g,"_");
            $('#'+tag).remove();
            $.ajax({
                url: '/customer/remove-tag/'+tagId+'/'+crmCustId,
                type: 'GET',
                async: true,
                success: function(data,textStatus,xhr){
                    console.log('IN sucess');
                    console.log(data);
                },
                error:function(errorData){
                    console.log('In Error');
                    console.log(errorData);
                }
            });
        }

        function updateProductQuantity(id,add,price,minQnt,maxQnt) {
            var qnt = $('#product_'+id).val();
            if(add == true){
                if(qnt >= maxQnt){
                    alert('Maximum allowed quantity for this product is '+maxQnt);
                }else{
                    qnt++;
                    price = price*qnt;
                    $('#price_'+id).text(price);
                    $('#products_price_'+id).text(price);
                    $('#selected_product_qnt'+id).val(qnt);
                    $('#product_'+id).val(qnt);
                    $('#product_qnt'+id).val(qnt);
                }
            } else {
                if(qnt <= minQnt){
                    alert('Minimum allowed quantity for this product is '+minQnt);
                }else{
                    if(qnt > 0){
                        qnt--;
                        price = price*qnt;
                        $('#price_'+id).text(price);
                        $('#products_price_'+id).text(price);
                        $('#selected_product_qnt'+id).val(qnt);
                        $('#product_'+id).val(qnt);
                        $('#product_qnt'+id).val(qnt);
                    }
                }
            }

        }

        function removeProduct(id) {
            $('#div_'+id).remove();
            $('#selected_products_div_'+id).remove();
            $('#product_qnt'+id).remove();
            $('#product_id_'+id).remove();
            if ( $('#check_out_preview').children().length == 0 ) {
                $('#no_product_div').show();
            }
        }

        $('#place_order_button').on('click',function () {
            $('#place_order').modal('show');
        });
        $('#schedule-button').on('click',function () {
            $('#schedule_modal').modal('show');
        })
        $('#select_product_modal').on('click',function () {
            var addressId = $('input[name=customer_address_id]:checked').val();
            if(addressId){
                $('#select_products').modal('show');
                $('#place_order').modal('hide');
                var str = $('#delivery_address_'+addressId).html();
                $('#address_id').val(addressId);
                $('#delivery_address').html(str);
            }else{
                $('#select_address_msg').show();
            }
        });

        $('#place_order_modal').on('click',function () {
            $('#place_order').modal('show');
            $('#select_products').modal('hide');
        });

        $('#confirm_order_modal').on('click',function () {
            $('#confirm_order').modal('show');
            $('#del_charge_div').hide();
            $('#referral_code').val('');
            $('#discount_div').hide();
            $('#referal_code_valid').text('');
            $('#select_products').modal('hide');
            var sum = 0;
            $('.product-price-total').each(function()
            {
                sum += parseFloat($(this).text());
            });
            if(sum <= 500){
                sum += 50;
                $('#del_charge_div').show();
            }
            $('#order_total').text(sum);
        });

        $('#apply_referral').on('click',function () {
            var referral = $('#referral_code').val();
            var sum = 0;
            var discount = 0;
            var discountFloat = 0;
            $('.product-price-total').each(function()
            {
                sum += parseFloat($(this).text());
            });
            var discountedSum = sum;
            if(sum <= 500){
                $('#discount_div').hide();
                $('#order_total').text(sum);
                $('#referal_code_valid').removeClass("text-success");
                $('#referal_code_valid').addClass("text-danger");
                $('#referal_code_valid').text("Discount is applicable if order total grater than 500");
            }else{
                if(sum > 2500){
                    discountFloat = (2 * sum)/100;
                    discount = discountFloat.toFixed(2);
                }else{
                    discount = 50;
                }
                discountedSum -= discount;
                $.ajax({
                    url: '{{env('BASE_URL')}}/validate-referral',
                    type: 'POST',
                    dataType: 'array',
                    data: {
                        'referral' : referral
                    },
                    success: function(response, ){
                        data= JSON.parse(response.responseText);
                        if(data != null){
                            if(data.is_validate){
                                $('#referal_code_valid').removeClass("text-danger");
                                $('#referal_code_valid').addClass("text-success");
                                $('#referal_code_valid').text("Referral code applied successfully");
                                $('#discount_div').show();
                                $('#order_total').text(discountedSum);
                                $('#discount_val').text(discount);
                            }else{
                                $('#discount_div').hide();
                                $('#order_total').text(sum);
                                $('#referal_code_valid').removeClass("text-success");
                                $('#referal_code_valid').addClass("text-danger");
                                $('#referal_code_valid').text("Invalid referral code");

                            }
                        }
                    },
                    error:function(response){
                        data= JSON.parse(response.responseText);
                        if(data != null){
                            if(data.is_validate){
                                $('#discount_div').show();
                                $('#referal_code_valid').removeClass("text-danger");
                                $('#referal_code_valid').addClass("text-success");
                                $('#referal_code_valid').text("Referral code applied successfully");
                                $('#order_total').text(discountedSum);
                                $('#discount_val').text(discount);
                            }else{
                                $('#discount_div').hide();
                                $('#order_total').text(sum);
                                $('#referal_code_valid').removeClass("text-success");
                                $('#referal_code_valid').addClass("text-danger");
                                $('#referal_code_valid').text("Invalid referral code");
                            }
                        }
                    }
                });
            }
        });

        $('#select_order_modal').on('click',function () {
            $('#select_products').modal('show');
            $('#confirm_order').modal('hide');
        });

        $(document).on("click",'.btn-delete',function (e) {
            e.preventDefault();
            var id = $(this).data("delete");
            $.ajax({
                url: "{{env('BASE_URL')}}/delete-address",
                type: 'get',
                dataType: 'array',
                data: {
                    'address_id': id
                },
                success: function (responce) {
                    $('#place_order').modal('show');
                    location.reload();
                },
                error: function (responce) {
                    $('#place_order').modal('show');
                    location.reload();
                }
            })
        });

        $(document).on("click",'.btn-edit',function (e) {
            e.preventDefault();
            var id = $(this).data("edit");
            $('#edit_address_'+id).modal('show');
        });

        jQuery('#add_new_address').validate({
                rules: {
                    full_name: {
                        required: true,
                        // alphaSpace:true,
                        minlength: 1,
                        maxlength: 50
                    },
                    mobile:{
                        required: true
                    },
                    name_of_premise_building_village: {
                        //  alpha_num_space_allow:true,
                        minlength: 1,
                        maxlength: 50
                    },
                    flat_door_block_house_no: {
                        minlength: 1,
                        maxlength: 50
                        // alphaSpaceSpecial:true
                    },
                    area_locality_wadi: {
                        //  areaLocality:true,
                        minlength: 1,
                        maxlength: 50
                    },
                    road_street_lane: {
                        //  areaLocality:true,
                        minlength: 1,
                        maxlength: 50
                    },
                    taluka:{
                        required: true
                    },
                    district:{
                        required: true
                    },
                    pincode:{
                        required: true
                    },
                    stateName:{
                        required: true
                    },
                    at_post:{
                        required: true
                    }
                },
                messages:{
                    full_name : {
                        required: "This field is required.",
                        minlength: "Please enter at least {0} characters.",
                        maxlength: "Please enter no more than {0} characters"
                    },
                    mobile:{
                        required: "This field is required."
                    },
                    name_of_premise_building_village: {
                        required:  "This field is required.",
                        minlength: "Please enter at least {0} characters.",
                        maxlength: "Please enter no more than {0} characters."
                    },
                    flat_door_block_house_no: {
                        required: "This field is required.",
                        minlength: "Please enter at least {0} characters.",
                        maxlength: "Please enter no more than {0} characters."
                    },
                    area_locality_wadi: {
                        required: "This field is required.",
                        minlength: "Please enter at least {0} characters.",
                        maxlength: "Please enter no more than {0} characters."
                    },
                    road_street_lane: {
                        required: "This field is required.",
                        minlength: "Please enter at least {0} characters.",
                        maxlength: "Please enter no more than {0} characters."
                    },
                    taluka:{
                        required: "This field is required."
                    },
                    district:{
                        required: "This field is required."
                    },
                    pincode:{
                        required: "This field is required."
                    },
                    stateName:{
                        required: "This field is required."
                    },
                    at_post:{
                        required: "This field is required."
                    }
                },
                errorElement: "em",
                errorPlacement: function ( error, element ) {
                    // Add the `help-block` class to the error element
                    error.addClass( "help-block" );
                    error.css("color","red");
                    if ( element.prop( "type" ) === "checkbox" ) {
                        error.insertAfter( element.parent( "label" ) );
                    } else {
                        error.insertAfter( element );
                    }
                },
                highlight: function ( element, errorClass, validClass ) {
                    $( element ).parents( ".col-sm-5" ).addClass( "has-error" ).removeClass( "has-success" );
                },
                unhighlight: function (element, errorClass, validClass) {
                    $( element ).parents( ".col-sm-5" ).addClass( "has-success" ).removeClass( "has-error" );
                },
                submitHandler: function (form) { // for demo
                    form.submit();
                }
            });

        $(document).on("click",".chat-submit",function (e) {
            var  message= $('#reply_text').val();
            var customer= $('#customer_detail_id').val();
            var customerNumber = $('#customer_detail_mobile').val();
            if(message != '') {
                $.ajax({
                    url: '/leads/sales-chat',
                    type: 'POST',
                    dataType: 'array',
                    data: {
                        'reply_message' : message,
                        'customer_id' : customer
                    },
                    success: function (responce) {
                        document.getElementById("reply_text").value = "";
                        chatHistory(customer,customerNumber);
                    },
                    error: function (responce) {
                        document.getElementById("reply_text").value = "";
                        chatHistory(customer,customerNumber);
                    }
                })
            }
        });

        $('#select-call-status').on('change',function () {
            var statusId = $(this).val();
            var customer= $('#customer_detail_id').val();
            var customerNumber = $('#customer_detail_mobile').val();
            $.ajax({
                url: '/leads/sales-chat',
                type: 'POST',
                dataType: 'array',
                data: {
                    'reply_status_id' : statusId,
                    'customer_id' : customer,
                    'in_profile' : true
                },
                success: function (responce) {
                    chatHistory(customer,customerNumber);
                },
                error: function (responce) {
                    chatHistory(customer,customerNumber);
                }
            })
        });

        function chatHistory(id,number) {
            $('#reply').modal('show');
            $('#customer_detail_id').val(id);
            $('#customer_status_detail_id').val(id);
            $('#customer_detail_mobile').val(number);
            $('.reply-title').text("Chat History - " +number);
            $.ajax({
                url: '/leads/sales-chat-listing/'+id,
                type: 'get',
                dataType: 'json',
                success: function (responce , xrh){
                    var obj = JSON.stringify(responce);
                    var jsonObj = JSON.parse(obj);
                    var str = '';
                    $.each(jsonObj, function(key , data) {
                        if(data['is_allocation'] == true){
                            str += '<div class="item" style="text-align: center">' +
                                '<span class="tag label label-info" style="font-size: 90%;">' +
                                data['number'] +' was allocated to ' +data['sale_agent'] + ' on '+ data['time'] +
                                '</span>' +
                                '</div> '+
                                '<br>';
                        }else if(data['is_schedule'] == true){
                            str += '<div class="item" style="text-align: center">' +
                                '<span class="tag label label-info" style="font-size: 90%;">'
                                +'Schedule to call back is set on ' +data['reminder'] +
                                '</span>' +
                                '</div> '+
                                '<br>';
                        }else if(data['is_created_tag'] == true){
                            str += '<div class="item" style="text-align: center">' +
                                '<span class="tag label label-info" style="font-size: 90%;">' +
                                data['sale_agent'] +' added tag '+'<span style="color: #0e0e0e">' +data['name']+'</span>' + ' on '+ data['time'] +
                                '</span>' +
                                '</div> '+
                                '<br>';
                        }else if(data['is_deleted_tag'] == true){
                            str += '<div class="item" style="text-align: center">' +
                                '<span class="tag label label-info" style="font-size: 90%;">' +
                                data['sale_agent'] +' removed tag ' +data['name'] + ' on '+ data['time'] +
                                '</span>' +
                                '</div> '+
                                '<br>';
                        } else {
                            if(data['reminder_time'] == true){
                                if(data['reminder'] != null){
                                    str += '<div class="item" style="text-align: center">' +
                                        '<span class="tag label label-info" style="font-size: 90%;">' +
                                        data['call'] +' was completed on ' +data['callTime'] +
                                        '</span>' +
                                        '</div> '+
                                        '<br>' +
                                        '<div class="item" style="text-align: center">' +
                                        '<span class="tag label label-info" style="font-size: 90%;">' +
                                        data['nextCall'] +' reminder set on ' +data['reminder'] +
                                        '</span>' +
                                        '</div> '+
                                        '<br>'
                                    ;
                                } else {
                                    str += '<div class="item" style="text-align: center">' +
                                        '<span class="tag label label-info" style="font-size: 90%;">' +
                                        data['call'] +' was completed on ' +data['callTime'] +
                                        '</span>' +
                                        '</div> '+
                                        '<br>';
                                }
                            }else {
                                if(data['status'] == null) {
                                    if(data['message'] == null){
                                        str += '<div class="item" style="text-align: center"><span class="tag label label-info" style="font-size: 90%;">'+ data['userName'] +' viewed profile @ ' +data['time']+'</span></div><br>';
                                    }else {
                                        if(data['message'] != ''){
                                            if(data['user'] == true){
                                                str += '<div class="item">' +
                                                    '<div class="item-head">' +
                                                    '<div class="item-details pull-right">' +
                                                    '<img class="item-pic rounded" height="35" width="35" src="/assets/layouts/layout3/img/avatar.png">' +
                                                    '<span style="color: black">' + data['userName'] + '</span>' +
                                                    '&nbsp;&nbsp;&nbsp;<span class="item-label" style="color: #8c8c8e">' + data['time'] + '</span>' +
                                                    '</div>' +
                                                    '</div>' +
                                                    '<div class="item-body pull-right col-md-offset-3" style="margin-top: auto;margin-bottom: 5px;border-radius: 15px !important;background-color: #78e08f;padding: 5px;position: relative;">' +
                                                    '<span>' + data['message'] + '</span>' +
                                                    '</div>' +
                                                    '</div>' +
                                                    '<br>';
                                            }else {
                                                str += '<div class="item">' +
                                                    '<div class="item-head">' +
                                                    '<div class="item-details">' +
                                                    '<img class="item-pic rounded" height="35" width="35" src="/assets/layouts/layout3/img/avatar.png">' +
                                                    '<span style="color: black">' + data['userName'] + '</span>' +
                                                    '&nbsp;&nbsp;&nbsp;<span class="item-label" style="color: #8c8c8e">' + data['time'] + '</span>' +
                                                    '</div>' +
                                                    '</div>';
                                                if(data['message'].length < 40){
                                                    str +=  '<div class="item-body col-md-9" style="margin-top: 5px;">' +
                                                        '<span style="margin-top: auto;margin-bottom: 5px;border-radius: 15px !important;background-color: #82ccdd;padding: 5px;position: relative;;margin-left: -15px;">' + data['message'] + '</span>';
                                                } else {
                                                    str +=  '<div class="item-body col-md-9" style="margin-top: auto;margin-bottom: 5px;border-radius: 15px !important;background-color: #82ccdd;padding: 5px;position: relative;">' +
                                                        '<span>' + data['message'] + '</span>';
                                                }
                                                str +=   '</div>' +
                                                    '</div>' +
                                                    '<br>';
                                            }
                                        }
                                    }
                                } else {
                                    str += '<div class="item" style="text-align: center"><span class="tag label label-info" style="font-size: 90%;">'+ data['status'] +' @ ' +data['time'] + ' by ' + data['userName'] + '</span></div><br>';
                                }
                            }
                        }
                    });
                    $('#chat_message').html(str);
                },
                error: function (responce) {
                    console.log(responce);
                }
            });
        }
        $(document).on("click","#profile-edit",function (e) {
            var first_name = $('#f_name').val();
            var last_name = $('#l_name').val();
            var email = $('#profile_email').val();
            var dob = $('#dob').val();
            var mobile = $('#profile_mobile').val();
            var id = $('#user_id').val();
            $.ajax({
                url: "{{env('BASE_URL')}}/edit-profile",
                type: 'POST',
                dataType: 'array',
                data: {
                    'f_name': first_name,
                    'l_name': last_name,
                    'email': email,
                    'dob': dob,
                    'mobile': mobile,
                    'id' :id
                },
                success: function (responce) {
                    location.reload();
                },
                error: function (responce) {
                }
            })
        });
    </script>
        <script>
            function openCustomerDetails(id) {
                $.ajax({
                    url: '/customer/abandoned-cart-detail/'+id+'',
                    type: 'GET',
                    async: true,
                    success: function(data,textStatus,xhr){
                        $("#AbandonedDetailModal .modal-body").html(data);
                        $("#AbandonedDetailModal").modal('show');
                    },
                    error:function(errorData){
                        alert('Something went wrong');
                    }

                });
            }
        </script>
@endsection