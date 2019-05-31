@extends('backend.seller.layouts.master')
@section('title','Agrosiaa | Dashboard')
@include('backend.partials.common.nav')
@section('css')
    <link rel="stylesheet" type="text/css" href="/assets/frontend/global/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="/assets/frontend/global/css/mCustomScrollbar.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/frontend/global/css/styles/style.css">

@endsection
@section('content')
    <!-- BEGIN CONTAINER -->
    <div class="page-container">
        <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
            <!-- BEGIN CONTENT BODY -->
            <!-- BEGIN PAGE HEAD-->
            <div class="page-head">
                <div class="container">
                    <!-- BEGIN PAGE TITLE -->

                    <!-- END PAGE TITLE -->

                </div>
            </div>
            <!-- END PAGE HEAD-->
            <!-- BEGIN PAGE CONTENT BODY -->
            <div class="page-content content-min-height">
                <div class="container" style="width: 90%;">
                    <!-- BEGIN PAGE BREADCRUMBS -->

                    <!-- END PAGE BREADCRUMBS -->
                    <!-- BEGIN PAGE CONTENT INNER -->
                    {{--<div class="page-content-inner">--}}
                    <div class="row">
                        <div class="col-md-12 col-md-offset-9">
                            <a href="#" onclick="chatHistory('{{$id}}','{{$mobile}}')" class="btn yellow">Make a Log </a>
                            <a href="#" id="place_order_button" class="btn blue">Place Order </a>
                            <a href="#" class="btn red-intense">Schedule </a>
                        </div>
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
                                    <div class="scroller-footer">
                                    </div>
                                    <a href="javascript:void(0);" class="btn blue m-icon" data-toggle="modal" data-target="#profile-edit-modal">
                                        Edit
                                    </a>
                                </div>
                                <div class="col-md-6">

                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="portlet blue box">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="icon- font-violet"></i>
                                        <span class="caption-subject font-violet bold uppercase">Order Details</span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <ul class="nav nav-pills mb-12" id="pills-tab" role="tablist">
                                        <li class="nav-item col-md-5">
                                            <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#customer-order" role="tab" aria-controls="pills-achievements" aria-selected="true" style="width:213px">Orders</a>
                                        </li>
                                        <li class="nav-item col-md-5">
                                            <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#customer-return" role="tab" aria-controls="pills-annoucement" aria-selected="false" style="width:213px">Return</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="pills-tabContent">
                                        <div class="tab-pane fade active" id="customer-order" onscroll="" role="tabpanel" aria-labelledby="pills-home-tab" style="height: 200px;overflow-y: scroll">
                                            @if($customerInfo->orders == null)
                                                <div class="col-md-12" style="text-align: center;margin-top: 10%;"><i><b>There are No Orders for this Customer</b></i></div>
                                            @else
                                                @foreach($customerInfo->orders as $order)
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
                            <div class="portlet green box">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="icon- font-violet"></i>
                                        <span class="caption-subject font-violet bold uppercase">Addresses</span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                        @foreach($customerInfo->address as $key=>$value)
                                                <li class="nav-item col-md-3">
                                                    <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#address{{$key}}" role="tab" aria-controls="pills-achievements" aria-selected="true">Address {{$key + 1}}</a>
                                                </li>
                                        @endforeach
                                    </ul>
                                    <div class="tab-content" id="pills-tabContent" style="height:50%;">
                                        @foreach($customerInfo->address as $key=>$value)
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
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br><br><br>
                    <div id="reply" class="modal" role="dialog" data-dismiss="modal">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content" style="width: 590px">
                                <div class="modal-header" style="width: 580px">
                                    <div class="col-md-7">
                                        <h4 class="modal-title reply-title" style="color: black"> </h4>
                                    </div>
                                    <div class="col-md-4">
                                        <select id="select-call-status" class="" style="-webkit-appearance: menulist; align-self: center">Select Call Status
                                            <option>Select Call Status</option>
                                            @foreach($callStatuses as $callStatus)
                                                <option value="{!! $callStatus['id'] !!}">{!! $callStatus['name'] !!}</option>
                                            @endforeach
                                        </select>
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
                                                    <div class="scroller scro" style="height: 338px;" data-always-visible="1" data-rail-visible1="0" data-handle-color="#D7DCE2">
                                                        <div class="general-item-list" id="chat_message">

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row" id="query-form">
                                        <form action="" role="form">
                                            <div class="col-md-10">
                                                <input type="text" name="reply_text" id="reply_text" required="required" maxlength="500" class="form-control" placeholder="reply">
                                                <input type="hidden" id="customer_detail_id" value="">
                                            </div>
                                            <div class="col-md-2">
                                                <button class="btn btn-sm btn-success table-group-action-submit chat-submit pull-right">Reply</button>
                                            </div>
                                        </form>
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
                                    <h4 class="modal-title" style="text-align: center"><b>Place Order</b></h4>
                                    <div>
                                        <a id="select_product_modal"><h4 style="text-align: right">next</h4></a>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <form id="add_new_address">
                                                <input type="hidden" value="{{$customerInfo->profile->id}}" id="customer_id">
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
                                                                <input class="form-control state" type="text" id="stateName" name="stateName" readonly>
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
                                                        <button type="submit" id="new_address_button" class="btn btn-primary btn-icon">Add new address</button>
                                                    </div>
                                                     @endif
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-md-6">
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
                    <form id="customer_order">
                    <div id="select_products" class="modal fade bs-modal-md" tabindex="-1" role="dialog" style="height: 500%">
                        <div class="modal-dialog modal-md">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title" style="text-align: center"><b>Add Products Checkout</b></h4>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <a id="place_order_modal"><h4 style="text-align: left">previous</h4></a>
                                        </div>
                                        <div class="col-sm-6">
                                            <a id="confirm_order_modal"><h4 style="text-align: right">next</h4></a>
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
                                    <br><hr>
                                    <h4>Checkout Preview</h4>
                                    <div id="check_out_preview">
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
                                    <h4 class="modal-title" style="text-align: center"><b>Confirm Order</b></h4>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <a id="select_order_modal"><h4 style="text-align: left">previous</h4></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="row">
                                            <h4 class="col-md-offset-1">Order Summery</h4>
                                        </div>
                                        <div class="row col-md-offset-2" id="selected_products">
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-2 col-md-offset-8">
                                                <h4>Total:</h4>
                                            </div>
                                            <div class="col-md-1">
                                                <h4 class="pull-right" id="order_total"></h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="row">
                                            <h4 class="col-md-offset-1">Delivery Order</h4>
                                        </div>
                                        <div class="row col-md-offset-2" id="delivery_address">
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-11">
                                            <input class="form-control" type="hidden" id="address_id" name="address_id" value="">
                                        <button type="submit" id="confirm_order_button" class="btn btn-primary btn-icon pull-right" onclick="confirmOrder()">Confirm Order</button>
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
                                <form id="edit-customer-profile">
                                    <input type="hidden" value="{{$customerInfo->profile->id}}" id="user_id">
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">First Name : <span class="required">*</span></label>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control" id="f_name" value="{{ucwords($customerInfo->profile->first_name)}}" name="f_name" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Last Name : <span class="required">*</span></label>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control" id="l_name" value="{{ucwords($customerInfo->profile->last_name)}}" name="l_name" required>
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
                                                        <input type="email" class="form-control" id="profile_email" value="{{$customerInfo->profile->email}}" name="profile_email">
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
                                                <button type="submit" id="profile-edit" class="btn btn-sm btn-success pull-right">Edit</button>
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
                                                    <form id="add_new_address">
                                                        <input type="hidden" value="{{$customerInfo->profile->id}}" id="customer_id">
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
                                                                        <input class="form-control edit-stateName" type="text" id="stateName_{{$address->id}}" name="stateName" value="{{$address->state}}" readonly>
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
                                                                <button type="submit" id="edit_address_button" class="btn btn-primary btn-icon" onclick="editAddress({{$address->id}})">Edit address</button>
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
                <!-- END PAGE CONTENT INNER -->
            </div>
        </div>
        <!-- END PAGE CONTENT BODY -->
        <!-- END CONTENT BODY -->
    </div>
    <!-- END CONTENT -->
    <!-- BEGIN QUICK SIDEBAR -->

    <!-- END QUICK SIDEBAR -->
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
    <script src="/assets/custom/superadmin/krishimitra/addresses.js"></script>
    <script src="/assets/custom/superadmin/krishimitra/editaddress.js"></script>
    <script src="/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>

    <!-- END PAGE LEVEL SCRIPTS -->
    <!-- BEGIN THEME LAYOUT SCRIPTS -->
    <script src="/assets/layouts/layout3/scripts/layout.min.js" type="text/javascript"></script>
    <script src="/assets/layouts/layout3/scripts/demo.min.js" type="text/javascript"></script>
    <script>

        $(document).ready(function () {

            $('#place_order').modal('hide');
            var productList = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.obj.whitespace('office_name'),
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                remote: {
                    url: "http://agrcrm-api.com/get-products?product_name=%QUERY",
                    filter: function(x) {
                        return $.map(x, function (data) {
                            return {
                                id: data.id,
                                name: data.name,
                                translated_name: data.translated_name,
                                position: data.position,
                                slug: data.slug,
                                btn_class: data.class,
                                url_param: data.url_param,
                                translated_slug:data.translated_slug,
                                price:data.discounted_price,
                                sku:data.seller_sku
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
                str = '<div class="row" id="div_'+POData.id+'"><div class="col-md-7"><h5>'+POData.name+'</h5></div>'+
                        '<div class="col-md-3">'+
                                '<a class="btn" onclick="updateProductQuantity('+POData.id+',false,'+POData.price+')" >-</a>'+
                                '<input class="cart-quantity" type="text" id="product_'+POData.id+'" value="1" style="width: 30px; text-align: center" readonly>'+
                                '<a class="btn" onclick="updateProductQuantity('+POData.id+',true,'+POData.price+')">+</a>'+
                        '</div>'+
                        '<div class="col-md-2"><i class="fa fa-rupee"></i><span id="price_'+POData.id+'">'+POData.price+'</span> &nbsp;&nbsp;<a><span onclick="removeProduct('+POData.id+')">x</span></a></div>'+
                    '</div>'+
                        '<input class="form-control product-list" type="hidden" id="product_id_'+POData.id+'" name="product_id[]" value="'+POData.id+'">'+
                        '<input class="form-control" type="hidden" id="product_qnt'+POData.id+'" name="product_qnt['+POData.id+']" value="1">';


                str2 = '<div class="row" id="selected_products_div_'+POData.id+'"><div class="col-md-7"><h5>'+POData.name+'</h5></div>'+
                    '<div class="col-md-3">'+
                    '<input class="cart-quantity" type="text" id="selected_product_qnt'+POData.id+'" value="1" style="width: 30px; text-align: center" readonly>'+
                    '</div>'+
                    '<div class="col-md-2"><i class="fa fa-rupee"></i><span class="product-price-total" id="products_price_'+POData.id+'">'+POData.price+'</span></div>'+
                    '</div>';
                $('#check_out_preview').append(str);
                $('#selected_products').append(str2);
            }).on('typeahead:open', function (obj, datum) {

                });
        });

        function updateProductQuantity(id,update,price) {
            var qnt = $('#product_'+id).val();
            if(update == true){
                qnt++;
                price = price*qnt;
                $('#price_'+id).text(price);
                $('#products_price_'+id).text(price);
                $('#selected_product_qnt'+id).val(qnt);
                $('#product_'+id).val(qnt);
                $('#product_qnt'+id).val(qnt);
            } else {
                qnt--;
                if(qnt > 0){
                    price = price*qnt;
                    $('#price_'+id).text(price);
                    $('#products_price_'+id).text(price);
                    $('#selected_product_qnt'+id).val(qnt);
                    $('#product_'+id).val(qnt);
                    $('#product_qnt'+id).val(qnt);
                }
            }
        }

        function removeProduct(id) {
            $('#div_'+id).remove();
            $('#selected_products_div_'+id).remove();
            $('#product_qnt'+id).remove();
            $('#product_id_'+id).remove();
        }

        function confirmOrder() {
            var frm = $('#customer_order');
            $.ajax({
                url: "{{env('BASE_URL')}}/confirm-order",
                type: 'post',
                data: frm.serialize(),
                success: function (data) {
                    console.log('Submission was successful.');
                    console.log(data);
                },
                error: function (data) {
                    console.log('An error occurred.');
                    console.log(data);
                }
            })
        }

        $('#place_order_button').on('click',function () {
            $('#place_order').modal('show');
        });

        $('#select_product_modal').on('click',function () {
            $('#select_products').modal('show');
            $('#place_order').modal('hide');
            var addressId = $('input[name=customer_address_id]:checked').val();
            var str = $('#delivery_address_'+addressId).html();
            $('#address_id').val(addressId);
            $('#delivery_address').html(str);
        });

        $('#place_order_modal').on('click',function () {
            $('#place_order').modal('show');
            $('#select_products').modal('hide');
        });

        $('#confirm_order_modal').on('click',function () {
            $('#confirm_order').modal('show');
            $('#select_products').modal('hide');
            var sum = 0;
            $('.product-price-total').each(function()
            {
                sum += parseFloat($(this).text());
            });
            $('#order_total').text(sum);
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
                    location.reload();
                    $('#place_order').modal('show');
                },
                error: function (responce) {
                    location.reload();
                    $('#place_order').modal('show');
                }
            })
        });

        $(document).on("click",'.btn-edit',function (e) {
            e.preventDefault();
            var id = $(this).data("edit");
            $('#edit_address_'+id).modal('show');
        });

        function editAddress(id) {
            var addressFullName= $('#full_name_'+id).val();
            var addrMobile= $('#mobile_'+id).val();
            var house_block = $('#flat_door_block_house_no_'+id).val();
            var village_premises = $('#name_of_premise_building_village_'+id).val();
            var area = $('#area_'+id).val();
            var road_street = $('#road_street_lane_'+id).val();
            var pin = $('#pincode_'+id).val();
            var post = $('#atPost_'+id).val();
            var state = $('#stateName_'+id).val();
            var dist = $('#district_'+id).val();
            var taluka = $('#taluka_'+id).val();
            alert('values'+ addressFullName + addrMobile + pin);
            alert('In edit');
            if(house_block != '' && village_premises != '' && area != '' && road_street != '' && pin != '' && post != '' && state != '' && dist != '' && taluka != '')
            {
                alert('In ajax');
                $.ajax({
                    url: "{{env('BASE_URL')}}/edit-address",
                    type: 'POST',
                    dataType: 'array',
                    data: {
                        'address_id': id,
                        'address_fname': addressFullName,
                        'address_mobile': addrMobile,
                        'house_block': house_block,
                        'village_premises': village_premises,
                        'area': area,
                        'road_street': road_street,
                        'pin': pin,
                        'at_post': post,
                        'state': state,
                        'dist': dist,
                        'taluka': taluka
                    },
                    success: function (responce) {
                        $('#place_order').modal('show');
                    },
                    error: function (responce) {
                        $('#place_order').modal('show');
                    }
                })
            }
        }

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

        $(document).on("click","#new_address_button",function (e) {
            e.stopPropagation();
            var userId = $('#customer_id').val();
            var addressFullName= $('#full_name').val();
            var addrMobile= $('#mobile').val();
            var house_block = $('#flat_door_block_house_no').val();
            var village_premises = $('#name_of_premise_building_village').val();
            var area = $('#area').val();
            var road_street = $('#road_street_lane').val();
            var pin = $('#pincode').val();
            var post = $('#atPost').val();
            var state = $('#stateName').val();
            var dist = $('#district').val();
            var taluka = $('#taluka').val();
            if(house_block != '' && village_premises != '' && area != '' && road_street != '' && pin != '' && post != '' && state != '' && dist != '' && taluka != '')
            {
                $.ajax({
                    url: "{{env('BASE_URL')}}/add-address",
                    type: 'POST',
                    dataType: 'array',
                    data: {
                        'user_id': userId,
                        'address_fname': addressFullName,
                        'address_mobile': addrMobile,
                        'house_block': house_block,
                        'village_premises': village_premises,
                        'area': area,
                        'road_street': road_street,
                        'pin': pin,
                        'at_post': post,
                        'state': state,
                        'dist': dist,
                        'taluka': taluka
                    },
                    success: function (responce) {
                        location.reload();
                    },
                    error: function (responce) {
                        location.reload();
                    }
                })
            }
        });

        $(document).on("click",".chat-submit",function (e) {
            var  message= $('#reply_text').val();
            var customer= $('#customer_detail_id').val();
            $.ajax({
                url: '/leads/sales-chat',
                type: 'POST',
                dataType: 'array',
                data: {
                    'reply_message' : message,
                    'customer_id' : customer
                },
                success: function (responce) {
                    var obj = JSON.stringify(responce);
                    var jsonObj = JSON.parse(obj);
                    var str = '';
                    $.each(jsonObj, function(key , data) {

                    })
                },
                error: function (responce) {
                }
            })
        });

        $('#select-call-status').on('change',function () {
            var statusId = $(this).val();
            var customer= $('#customer_detail_id').val();
            $.ajax({
                url: '/leads/sales-chat',
                type: 'POST',
                dataType: 'array',
                data: {
                    'reply_status_id' : statusId,
                    'customer_id' : customer
                },
                success: function (responce) {
                    $('#reply').modal('toggle');
                    location.reload();
                },
                error: function (responce) {
                    location.reload();
                    $('#reply').modal('toggle');
                }
            })
        });


        function chatHistory(id,number) {
            $('#reply').modal('show');
            $('#customer_detail_id').val(id);
            $('#customer_status_detail_id').val(id);
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
                                    if(data['user'] == true){
                                        str += '<div class="item">' +
                                            '<div class="item-head">' +
                                            '<div class="item-details pull-right">' +
                                            '<img class="item-pic rounded" height="35" width="35" src="/assets/layouts/layout3/img/avatar.png">' +
                                            '<span style="color: black">' + data['userName'] + '</span>' +
                                            '&nbsp;&nbsp;&nbsp;<span class="item-label" style="color: #8c8c8e">' + data['time'] + '</span>' +
                                            '</div>' +
                                            '</div>' +
                                            '<div class="item-body pull-right">' +
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
                                            '</div>' +
                                            '<div class="item-body">' +
                                            '<span>' + data['message'] + '</span>' +
                                            '</div>' +
                                            '</div>' +
                                            '<br>';
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
@endsection