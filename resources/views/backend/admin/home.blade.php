@extends('backend.seller.layouts.master')
@section('title','Agrosiaa | Dashboard')
@include('backend.partials.common.nav')
@section('css')
   <style>
       .feeds li{
           text-align: left;
       }
   </style>
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
                        <div class="col-md-6 col-md-6">
                            <div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="icon-share font-violet"></i>
                                        <span class="caption-subject font-violet bold uppercase">Pending Due To Vendor</span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="scroller" style="height: 250px;" data-always-visible="1" data-rail-visible="0">
                                        <ul class="feeds">
                                                <div class="col1">
                                                    <div class="cont">
                                                        <div class="cont-col2" id="message_pdtv">
                                                        </div>
                                                    </div>
                                                </div>
                                                    <li id="pdtv_data">
                                                    </li>
                                        </ul>
                                    </div>
                                    <div class="scroller-footer">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-md-6">
                            <div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="icon-share font-yellow"></i>
                                        <span class="caption-subject font-yellow bold uppercase">Pending For Vendor Cancellation</span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="scroller" style="height: 250px;" data-always-visible="1" data-rail-visible="">
                                        <ul class="feeds">
                                            <div class="col1">
                                                <div class="cont">
                                                    <div class="cont-col2" id="message_pfvc">
                                                    </div>
                                                </div>
                                            </div>
                                                <li id="pfvc_data">
                                                </li>
                                        </ul>
                                    </div>
                                    <div class="scroller-footer">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="icon-share font-green"></i>
                                        <span class="caption-subject font-green bold uppercase">Pending For Customer Cancellation</span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="scroller" style="height: 250px;" data-always-visible="1" data-rail-visible="0">
                                        <ul class="feeds">
                                                <div class="col1">
                                                    <div class="cont">
                                                        <div class="cont-col2" id="message_pfcc">
                                                        </div>
                                                    </div>
                                                </div>
                                            <li id="pfcc_data">
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="scroller-footer">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="icon-share font-red"></i>
                                        <span class="caption-subject font-red bold uppercase">Pending for Pickup</span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="scroller" style="height: 250px;" data-always-visible="1" data-rail-visible="0">
                                        <ul class="feeds">
                                                <div class="col1">
                                                    <div class="cont">
                                                        <div class="cont-col2" id="message_pfp">
                                                        </div>
                                                    </div>
                                                </div>
                                            <li id="pfp_data">
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="scroller-footer">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="icon-share text-danger"></i>
                                        <span class="caption-subject text-danger bold uppercase">Customer Issues</span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="scroller" style="height: 250px;" data-always-visible="1" data-rail-visible="0">
                                        <ul class="feeds">
                                                <div class="col1">
                                                    <div class="cont">
                                                        <div class="cont-col2" id="message_ci">
                                                        </div>
                                                    </div>
                                                </div>
                                            <li id="ci_data">
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="scroller-footer">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="icon-share text-success"></i>
                                        <span class="caption-subject text-success bold uppercase">Dispatched orders</span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="scroller" style="height: 250px;" data-always-visible="1" data-rail-visible="0">
                                        <ul class="feeds">
                                                <div class="col1">
                                                    <div class="cont">
                                                        <div class="cont-col2" id="message_dp">
                                                        </div>
                                                    </div>
                                                </div>
                                            <li id="do_data">
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="scroller-footer">
                                    </div>
                                </div>
                            </div>
                        </div>
                {{--</div>--}}
                    {{--Model for reply--}}
                    <div id="reply" class="modal" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Chat</h4>
                                </div>
                                <div class="modal-body">
                                    <form class="form-horizontal" role="form" action="" id="chat_modal">
                                        <input type="hidden" name="order_id" value="" id="Order_ID">
                                        <div class="form-group">
                                            <label class="col-md-2 control-label"> Reply:
                                                <span class="required"> * </span>
                                            </label>

                                            <div class="col-md-10">
                                                <input type="text"  class="form-control" value="" id="reply_text" name="reply_text" placeholder="" required="required">
                                            </div>
                                            <div class="col-md-12" id="chat_message" style="margin-left: 3%">

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3 pull-right">
                                                <a href="javascript:void(0)" class="btn btn-sm btn-success pull-right chat-submit">Submit</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--End of modal--}}
                    {{--Model for cancle--}}
                    <div id="cancel" class="modal" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Cancel Reason</h4>
                                </div>
                                <div class="modal-body">
                                    <form class="form-horizontal" role="form" action="" id="cancel_modal">
                                        <input type="hidden" name="order_id" value="" id="Cancel_Order_ID">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"> Reason:
                                            <span class="required"> * </span>
                                        </label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" id="cancel_text" value="" name="cancel_text" placeholder="" >
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 pull-right">
                                            <a href="javascript:void(0)" class="btn btn-sm btn-success pull-right cancel-submit">Submit</a>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--End of modal--}}
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
        $(document).ready(function() {
            $.ajax({
                url: "{{env('BASE_URL')}}/order-detail",
                type: 'get',
                dataType: 'json',
                success: function (responce) {
                    var obj = JSON.stringify(responce);
                    var jsonObj = JSON.parse(obj);
                    var str = '';
                    $.each(jsonObj, function(key , value) {
                        console.log(value);
                        if (value['pending_due_to_vendor']['length'] == 0){
                            str = '<div class="desc">' +
                                'No orders are present in Pending Due to vendor' +
                                ' </div>';
                            $('#message_pdtv').html(str);
                        }else{
                            $.each(value['pending_due_to_vendor'], function(key , data) {
                                str = '<div class="row">'+
                                    '<div class="col-md-1">'+
                                    '<div class="label label-sm label-info">'+
                                    ' <i class="fa fa-shopping-cart"></i>'+
                                    ' </div>'+
                                    ' </div>'+
                                    '<div class="col-md-6" style="margin-left: -15px;margin-top: -3px">'+
                                    'Order Number : <span>'+"AGR0000"+ +data['order_id']+' </span>'+
                                    '<br>'+
                                    '<span style="font-size: 11px"><b>Order timestamp : '+data['created_at']+'</b></span>'+
                                    '</div>'+
                                    '<div class="col-md-3" style="margin-top: -3px;">'+
                                    '<div class="date" style="text-align:left;font-size: 11px"> '+data['work_order_date']+''+
                                    '<br>';
                                if(data['consignment_number'] != null){
                                    str += '<span style="font-size: 10px;"><i>'+data['consignment_number']+'</i></span>';
                                }
                                str += '</div>'+
                                    '</div>'+
                                    '<div class="col-md-1" style="margin-left: -20px;">'+
                                    '<button class="btn blue rounded fa fa-comments chat_reply" style="padding-right: 120%;margin-left: 100%;" type="submit" value="'+data['order_id']+'" data-toggle="modal" data-target="#reply"></button>'+
                                    '</div>'+
                                    '<div class="col-md-1" >'+
                                    '<button style="margin-left: 20px;" class="btn red rounded order_cancel" type="submit" value="'+data['order_id']+'" data-toggle="modal" data-target="#cancel">X</button>'+
                                    '</div>'+
                                    '</div>'+
                                    '<br>';
                                $('#pdtv_data').html(str);
                            });
                        } if (value['pending_for_customer_cancel']['length'] == 0) {
                            str = '<div class="desc"> ' +
                                'No orders are present in Pending for Customer Cancel' +
                                ' </div>';
                            $('#message_pfcc').html(str);
                        }else{
                            $.each(value['pending_for_customer_cancel'], function(key , data) {
                                str = '<div class="row">'+
                                    '<div class="col-md-1">'+
                                    '<div class="label label-sm label-info">'+
                                    ' <i class="fa fa-shopping-cart"></i>'+
                                    ' </div>'+
                                    ' </div>'+
                                    '<div class="col-md-6" style="margin-left: -15px;margin-top: -3px">'+
                                    'Order Number : <span>'+"AGR0000"+ +data['order_id']+' </span>'+
                                    '<br>'+
                                    '<span style="font-size: 11px"><b>Order timestamp : '+data['created_at']+'</b></span>'+
                                    '</div>'+
                                    '<div class="col-md-3" style="margin-top: -3px;">'+
                                    '<div class="date" style="text-align:left;font-size: 11px"> '+data['work_order_date']+''+
                                    '<br>';
                                if(data['consignment_number'] != null){
                                    str += '<span style="font-size: 10px;"><i>'+data['consignment_number']+'</i></span>';
                                }
                                str += '</div>'+
                                    '</div>'+
                                    '<div class="col-md-1" style="margin-left: -20px;">'+
                                    '<button class="btn blue rounded fa fa-comments chat_reply" style="padding-right: 120%;margin-left: 100%;" type="submit" value="'+data['order_id']+'" data-toggle="modal" data-target="#reply"></button>'+
                                    '</div>'+
                                    '<div class="col-md-1" >'+
                                    '<button style="margin-left: 20px;" class="btn red rounded order_cancel" type="submit" value="'+data['order_id']+'" data-toggle="modal" data-target="#cancel">X</button>'+
                                    '</div>'+
                                    '</div>'+
                                    '<br>';
                                $('#pfcc_data').append(str);
                            });
                        }
                            if (value['pending_for_vendor_cancel']['length'] == 0) {
                            str = '<div class="desc"> ' +
                                'No orders are present in Pending for vendor Cancel' +
                                ' </div>';
                            $('#message_pfvc').html(str);
                        }else{
                                $.each(value['pending_for_vendor_cancel'], function(key , data) {
                                    str = '<div class="row">'+
                                        '<div class="col-md-1">'+
                                        '<div class="label label-sm label-info">'+
                                        ' <i class="fa fa-shopping-cart"></i>'+
                                        ' </div>'+
                                        ' </div>'+
                                        '<div class="col-md-6" style="margin-left: -15px;margin-top: -3px">'+
                                        'Order Number : <span>'+"AGR0000"+ +data['order_id']+' </span>'+
                                        '<br>'+
                                        '<span style="font-size: 11px"><b>Order timestamp : '+data['created_at']+'</b></span>'+
                                        '</div>'+
                                        '<div class="col-md-3" style="margin-top: -3px;">'+
                                        '<div class="date" style="text-align:left;font-size: 11px"> '+data['work_order_date']+''+
                                            '<br>';
                                            if(data['consignment_number'] != null){
                                              str += '<span style="font-size: 10px;"><i>'+data['consignment_number']+'</i></span>';
                                            }
                                        str += '</div>'+
                                        '</div>'+
                                        '<div class="col-md-1" style="margin-left: -20px;">'+
                                            '<button class="btn blue rounded fa fa-comments chat_reply" style="padding-right: 120%;margin-left: 100%;" type="submit" value="'+data['order_id']+'" data-toggle="modal" data-target="#reply"></button>'+
                                        '</div>'+
                                        '<div class="col-md-1" >'+
                                        '<button style="margin-left: 20px;" class="btn red rounded order_cancel" type="submit" value="'+data['order_id']+'" data-toggle="modal" data-target="#cancel">X</button>'+
                                        '</div>'+
                                        '</div>'+
                                        '<br>';
                                    $('#pfvc_data').append(str);
                                });
                            }if (value['pending_for_pickup']['length'] == 0) {
                            str = '<div class="desc"> ' +
                                'No orders are present in Pending for Pickup' +
                                ' </div>';
                            $('#message_pfp').html(str);
                        }else{
                            $.each(value['pending_for_pickup'], function(key , data) {
                                str = '<div class="row">'+
                                    '<div class="col-md-1">'+
                                    '<div class="label label-sm label-info">'+
                                    ' <i class="fa fa-shopping-cart"></i>'+
                                    ' </div>'+
                                    ' </div>'+
                                    '<div class="col-md-6" style="margin-left: -15px;margin-top: -3px">'+
                                    'Order Number : <span>'+"AGR0000"+ +data['order_id']+' </span>'+
                                    '<br>'+
                                    '<span style="font-size: 11px"><b>Order timestamp : '+data['created_at']+'</b></span>'+
                                    '</div>'+
                                    '<div class="col-md-3" style="margin-top: -3px;">'+
                                    '<div class="date" style="text-align:left;font-size: 11px"> '+data['work_order_date']+''+
                                    '<br>';
                                if(data['consignment_number'] != null){
                                    str += '<span style="font-size: 10px;"><i>'+data['consignment_number']+'</i></span>';
                                }
                                str += '</div>'+
                                    '</div>'+
                                    '<div class="col-md-1" style="margin-left: -20px;">'+
                                    '<button class="btn blue rounded fa fa-comments chat_reply" style="padding-right: 120%;margin-left: 100%;" type="submit" value="'+data['order_id']+'" data-toggle="modal" data-target="#reply"></button>'+
                                    '</div>'+
                                    '<div class="col-md-1" >'+
                                    '<button style="margin-left: 20px;" class="btn red rounded order_cancel" type="submit" value="'+data['order_id']+'" data-toggle="modal" data-target="#cancel">X</button>'+
                                    '</div>'+
                                    '</div>'+
                                    '<br>';
                                $('#pfp_data').append(str);
                            });
                        }if (value['customer_issues']['length'] == 0) {
                            str = '<div class="desc"> ' +
                                'No orders are present in Customer Issue' +
                                ' </div>';
                            $('#message_ci').html(str);
                        }else{
                            $.each(value['customer_issues'], function(key , data) {
                                str = '<div class="row">'+
                                    '<div class="col-md-1">'+
                                    '<div class="label label-sm label-info">'+
                                    ' <i class="fa fa-shopping-cart"></i>'+
                                    ' </div>'+
                                    ' </div>'+
                                    '<div class="col-md-6" style="margin-left: -15px;margin-top: -3px">'+
                                    'Order Number : <span>'+"AGR0000"+ +data['order_id']+' </span>'+
                                    '<br>'+
                                    '<span style="font-size: 11px"><b>Order timestamp : '+data['created_at']+'</b></span>'+
                                    '<br><br>'+
                                    '<span class="tag label label-info" style="font-size: 90%;">'+data['name']+'<span data-role="remove"></span></span>'+
                                    '</div>'+
                                    '<div class="col-md-3" style="margin-top: -3px;">'+
                                    '<div class="date" style="text-align:left;font-size: 11px"> '+data['work_order_date']+''+
                                    '<br>';
                                if(data['consignment_number'] != null){
                                    str += '<span style="font-size: 10px;"><i>'+data['consignment_number']+'</i></span>';
                                }
                                str += '</div>'+
                                    '</div>'+
                                    '<div class="col-md-1 " style="margin-left: -20px;">'+
                                    '<button class="btn blue rounded fa fa-comments chat_reply" style="padding-right: 120%;margin-left: 100%;" type="submit" value="'+data['order_id']+'" data-toggle="modal" data-target="#reply"></button>'+
                                    '</div>'+
                                    '<div class="col-md-1" >'+
                                    '<button style="margin-left: 20px;" class="btn red rounded order_cancel" type="submit" value="'+data['order_id']+'" data-toggle="modal" data-target="#cancel">X</button>'+
                                    '</div>'+
                                    '</div>'+
                                    '<br>';
                                $('#ci_data').append(str);
                            });
                        }if (value['dispatch_orders']['length'] == 0) {
                            str = '<div class="desc"> ' +
                                'No orders are present in Dispatch Orders' +
                                ' </div>';
                            $('#message_dp').html(str);
                        }else{
                            $.each(value['dispatch_orders'], function(key , data) {
                                str = '<div class="row">'+
                                    '<div class="col-md-1">'+
                                    '<div class="label label-sm label-info">'+
                                    ' <i class="fa fa-shopping-cart"></i>'+
                                    ' </div>'+
                                    ' </div>'+
                                    '<div class="col-md-7" style="margin-left: -15px;margin-top: -3px">'+
                                    'Order Number : <span>'+"AGR0000"+ +data['order_id']+' </span>'+
                                    '<br>'+
                                    '<span style="font-size: 11px"><b>Order timestamp : '+data['created_at']+'</b></span>'+
                                    '</div>'+
                                    '<div class="col-md-4" style="margin-top: -3px;">'+
                                    '<div class="date" style="text-align:left;font-size: 11px;"> '+data['work_order_date']+''+
                                    '</div>'+
                                    '</div>'+
                                    '</div>'+
                                    '<br>';
                                $('#do_data').append(str);
                            });
                        }
                    })
                }
            });

        });
    </script>
    <script>
            $(document).on("click", ".chat_reply", function () {
                var eventId = $(this).val();
                $('#Order_ID').val(eventId);
                $.ajax({
                    url: "{{env('BASE_URL')}}/order-chat/"+eventId,
                    type: 'get',
                    dataType: 'json',
                    success: function (responce) {
                        var obj = JSON.stringify(responce);
                        var jsonObj = JSON.parse(obj);
                        console.log(jsonObj);
                        var str = '';
                        $.each(jsonObj, function(key , data) {
                            console.log(data)
                            str  += [key+1]+'-'+'<span style="margin-left: 1%">'+data['message'] +'</span>'+
                                '<br>';

                        });
                        $('#chat_message').html(str);
                    },
                    error: function (responce) {
                    }
                });
            });

        $(document).on("click",".chat-submit",function (e) {
            e.stopPropagation();
            var OrderId = $('#Order_ID').val();
            var comment = $('#reply_text').val();
            $.ajax({
                url: "{{env('BASE_URL')}}/order-reply",
                type: 'POST',
                dataType: 'array',
                data: {
                    'order_id' : OrderId,
                    'reply_message' : comment
                },
                success: function (responce) {
                    $('#reply').modal('toggle');
                },
                error: function (responce) {
                    $('#reply').modal('toggle');
                }
            })
        });
        $(document).on("click", ".order_cancel", function () {
            var eventId = $(this).val();
            $('#Cancel_Order_ID').val(eventId);
        });

        $(document).on("click",".cancel-submit",function (e) {
            e.stopPropagation();
            var OrderId = $('#Cancel_Order_ID').val();
            var comment = $('#cancel_text').val();
            $.ajax({
                url: "{{env('BASE_URL')}}/order-cancel",
                type: 'POST',
                dataType: 'array',
                data: {
                    'order_id' : OrderId,
                    'cancel_text' : comment
                },
                success: function (responce) {
                    $('#cancel').modal('toggle');
                },
                error: function (responce) {
                    $('#cancel').modal('toggle');
                }
            })
        })
    </script>
@endsection
