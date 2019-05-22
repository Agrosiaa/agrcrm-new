@extends('backend.seller.layouts.master')
@section('title','Agrosiaa | Dashboard')
@include('backend.partials.common.nav')
@section('css')
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
                                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                            <li class="nav-item col-md-5">
                                                <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#customer-order" role="tab" aria-controls="pills-achievements" aria-selected="true" style="width:213px">Orders</a>
                                            </li>
                                            <li class="nav-item col-md-5">
                                                <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#customer-return" role="tab" aria-controls="pills-annoucement" aria-selected="false" style="width:213px">Return</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content" id="pills-tabContent">
                                            <div class="tab-pane fade active" id="customer-order" onscroll="" role="tabpanel" aria-labelledby="pills-home-tab" style="height: 200px;overflow-y: scroll">
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
                                            </div>
                                            <div class="tab-pane fade" id="customer-return" role="tabpanel" aria-labelledby="pills-profile-tab" style="height: 200px;overflow-y: scroll">
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
                                            </div>
                                        </div>
                                    <div class="scroller-footer">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" style="height: 340px">
                            <div class="portlet green box">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="icon- font-violet"></i>
                                        <span class="caption-subject font-violet bold uppercase">Addresses</span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                        <li class="nav-item col-md-3">
                                            <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#address1" role="tab" aria-controls="pills-achievements" aria-selected="true">Address 1</a>
                                        </li>
                                        <li class="nav-item col-md-3">
                                            <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#address2" role="tab" aria-controls="pills-annoucement" aria-selected="false">Address 2</a>
                                        </li>
                                        <li class="nav-item col-md-3">
                                            <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#address3" role="tab" aria-controls="pills-annoucement" aria-selected="false">Address 3</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="pills-tabContent">
                                        <div class="tab-pane fade active" id="address1" onscroll="" role="tabpanel" aria-labelledby="pills-home-tab" style="height: 215px;overflow-y: scroll">
                                            @if(count($customerInfo->address) >= 1)
                                                <div class="row" style="border-bottom: 1px solid #b2b2b2; padding: 10px;background-color: #fefefe;">
                                                    <div class="col-md-12"><i>Full Name : </i> <span style="color: #000000">{{$customerInfo->address['0']->full_name}}</span></div>
                                                    <div class="col-md-12"><i>Mobile : </i> <span style="color: #000000">{{$customerInfo->address['0']->mobile}}</span></div>
                                                    <div class="col-md-12"><i>House/Block Number : </i> <span style="color: #000000">{{$customerInfo->address['0']->flat_door_block_house_no}}</span></div>
                                                    <div class="col-md-12"><i>Name of Premise/Building/Village : </i> <span style="color: #000000">{{$customerInfo->address['0']->name_of_premise_building_village}}</span></div>
                                                    <div class="col-md-12"><i>Area/Locality : </i> <span style="color: #000000">{{$customerInfo->address['0']->area_locality_wadi}}</span></div>
                                                    <div class="col-md-12"><i>Road/Street/Lane : </i> <span style="color: #000000">{{$customerInfo->address['0']->road_street_lane}}</span></div>
                                                    <div class="col-md-12"><i>Post : </i> <span style="color: #000000">{{$customerInfo->address['0']->at_post}}</span></div>
                                                    <div class="col-md-12"><i>Taluka : </i> <span style="color: #000000">{{$customerInfo->address['0']->taluka}}</span></div>
                                                    <div class="col-md-12"><i>District : </i> <span style="color: #000000">{{$customerInfo->address['0']->district}}</span></div>
                                                    <div class="col-md-12"><i>State : </i> <span style="color: #000000">{{$customerInfo->address['0']->state}}</span></div>
                                                    <div class="col-md-12"><i>Pincode : </i> <span style="color: #000000">{{$customerInfo->address['0']->pincode}}</span></div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="tab-pane fade" id="address2" role="tabpanel" aria-labelledby="pills-profile-tab" style="height: 215px;overflow-y: scroll">
                                            @if(count($customerInfo->address) >= 2)
                                                <div class="row" style="border-bottom: 1px solid #b2b2b2; padding: 10px;background-color: #fefefe;">
                                                    <div class="col-md-12"><i>Full Name : </i> <span style="color: #000000">{{$customerInfo->address['1']->full_name}}</span></div>
                                                    <div class="col-md-12"><i>Mobile : </i> <span style="color: #000000">{{$customerInfo->address['1']->mobile}}</span></div>
                                                    <div class="col-md-12"><i>House/Block Number : </i> <span style="color: #000000">{{$customerInfo->address['1']->flat_door_block_house_no}}</span></div>
                                                    <div class="col-md-12"><i>Name of Premise/Building/Village : </i> <span style="color: #000000">{{$customerInfo->address['1']->name_of_premise_building_village}}</span></div>
                                                    <div class="col-md-12"><i>Area/Locality : </i> <span style="color: #000000">{{$customerInfo->address['1']->area_locality_wadi}}</span></div>
                                                    <div class="col-md-12"><i>Road/Street/Lane : </i> <span style="color: #000000">{{$customerInfo->address['1']->road_street_lane}}</span></div>
                                                    <div class="col-md-12"><i>Post : </i> <span style="color: #000000">{{$customerInfo->address['1']->at_post}}</span></div>
                                                    <div class="col-md-12"><i>Taluka : </i> <span style="color: #000000">{{$customerInfo->address['1']->taluka}}</span></div>
                                                    <div class="col-md-12"><i>District : </i> <span style="color: #000000">{{$customerInfo->address['1']->district}}</span></div>
                                                    <div class="col-md-12"><i>State : </i> <span style="color: #000000">{{$customerInfo->address['1']->state}}</span></div>
                                                    <div class="col-md-12"><i>Pincode : </i> <span style="color: #000000">{{$customerInfo->address['1']->pincode}}</span></div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="tab-pane fade" id="address3" role="tabpanel" aria-labelledby="pills-profile-tab" style="height: 215px;overflow-y: scroll">
                                            @if(count($customerInfo->address) >= 3)
                                                <div class="row" style="border-bottom: 1px solid #b2b2b2; padding: 10px;background-color: #fefefe;">
                                                    <div class="col-md-12"><i>Full Name : </i> <span style="color: #000000">{{$customerInfo->address['2']->full_name}}</span></div>
                                                    <div class="col-md-12"><i>Mobile : </i> <span style="color: #000000">{{$customerInfo->address['2']->mobile}}</span></div>
                                                    <div class="col-md-12"><i>House/Block Number : </i> <span style="color: #000000">{{$customerInfo->address['2']->flat_door_block_house_no}}</span></div>
                                                    <div class="col-md-12"><i>Name of Premise/Building/Village : </i> <span style="color: #000000">{{$customerInfo->address['2']->name_of_premise_building_village}}</span></div>
                                                    <div class="col-md-12"><i>Area/Locality : </i> <span style="color: #000000">{{$customerInfo->address['2']->area_locality_wadi}}</span></div>
                                                    <div class="col-md-12"><i>Road/Street/Lane : </i> <span style="color: #000000">{{$customerInfo->address['2']->road_street_lane}}</span></div>
                                                    <div class="col-md-12"><i>Post : </i> <span style="color: #000000">{{$customerInfo->address['2']->at_post}}</span></div>
                                                    <div class="col-md-12"><i>Taluka : </i> <span style="color: #000000">{{$customerInfo->address['2']->taluka}}</span></div>
                                                    <div class="col-md-12"><i>District : </i> <span style="color: #000000">{{$customerInfo->address['2']->district}}</span></div>
                                                    <div class="col-md-12"><i>State : </i> <span style="color: #000000">{{$customerInfo->address['2']->state}}</span></div>
                                                    <div class="col-md-12"><i>Pincode : </i> <span style="color: #000000">{{$customerInfo->address['2']->pincode}}</span></div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button class="col-md-2 col-md-offset-3 btn btn-primary" onclick="chatHistory('{{$id}}','{{$mobile}}')"> Make a Log </button>&nbsp;&nbsp;&nbsp;
                            <button class="col-md-2 btn btn-primary"> Place Order </button>&nbsp;&nbsp;&nbsp;
                            <button class="col-md-2 btn btn-primary"> Schedule </button>&nbsp;&nbsp;&nbsp;
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
                </div>
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
    <script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
    <script>
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
    </script>
@endsection
