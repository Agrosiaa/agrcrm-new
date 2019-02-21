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
            <div class="container">
                <!-- BEGIN PAGE BREADCRUMBS -->

                <!-- END PAGE BREADCRUMBS -->
                <!-- BEGIN PAGE CONTENT INNER -->
                <div class="page-content-inner">
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
                                                        <div class="cont-col2">
                                                            <div class="desc"> No orders are present in Pending Due To Vendor
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                    <li>
                                                        <div class="row">
                                                            <div class="col-md-1">
                                                                <div class="label label-sm label-info">
                                                                    <i class="fa fa-shopping-cart"></i>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-7" style="margin-left: -15px">
                                                                {{--Order Number :<a href="/operational/order/view/{{$value['order_id']}}" target="_blank"> AGR{{str_pad($value['order_id'], 9, "0", STR_PAD_LEFT)}} </a>--}}
{{--                                                                @if($value['orders']['consignment_number'] != null || $value['orders']['consignment_number'] != "")--}}
                                                                    {{--<span style="font-size: 12px"><i><b>Consignment number : {{($value['orders']['consignment_number'])}}</b></i></span>--}}
                                                                {{--@endif--}}
                                                                <br>
                                                                {{--<span style="font-size: 11px"><b>Order timestamp : {{$value['orders']['created_at']}}</b></span>--}}
                                                            </div>
                                                            <div class="col-md-4">
                                                                {{--<div class="date"> {{$value['created_at']}} </div>--}}
                                                            </div>
                                                        </div>
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
                                                    <div class="cont-col2">
                                                        <div class="desc"> No orders are present in Pending For Vendor Cancellation
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                                <li>
                                                    <div class="row">
                                                        <div class="col-md-1">
                                                            <div class="label label-sm label-info">
                                                                <i class="fa fa-shopping-cart"></i>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-7" style="margin-left: -15px">
                                                            Order Number :{{--<a href="/operational/order/view/{{$value['order_id']}}" target="_blank"> AGR{{str_pad($value['order_id'], 9, "0", STR_PAD_LEFT)}} </a>--}}
                                                            {{--@if($value['orders']['consignment_number'] != null || $value['orders']['consignment_number'] != "")
                                                                <span style="font-size: 12px"><i><b>Consignment number : {{($value['orders']['consignment_number'])}}</b></i></span>
                                                            @endif--}}
                                                            <br>
                                                            <span style="font-size: 11px"><b>Order timestamp : {{--{{$value['orders']['created_at']}}--}}</b></span>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="date">  </div>
                                                        </div>
                                                    </div>
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
                                                        <div class="cont-col2">
                                                            <div class="desc"> No orders are present in Pending For Customer Cancellation
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                    <li>
                                                        <div class="row">
                                                            <div class="col-md-1">
                                                                <div class="label label-sm label-info">
                                                                    <i class="fa fa-shopping-cart"></i>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-7" style="margin-left: -15px">
                                                                Order Number :{{--<a href="/operational/order/view/{{$value['order_id']}}" target="_blank"> AGR{{str_pad($value['order_id'], 9, "0", STR_PAD_LEFT)}} </a>--}}
                                                                {{--@if($value['orders']['consignment_number'] != null || $value['orders']['consignment_number'] != "")
                                                                    <span style="font-size: 12px"><i><b>Consignment number : {{($value['orders']['consignment_number'])}}</b></i></span>
                                                                @endif--}}
                                                                <br>
                                                                <span style="font-size: 11px"><b>Order timestamp : {{--{{$value['orders']['created_at']}}--}}</b></span>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="date">  </div>
                                                            </div>
                                                        </div>
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
                                        <span class="caption-subject font-red bold uppercase">Customer Issues</span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="scroller" style="height: 250px;" data-always-visible="1" data-rail-visible="0">
                                        <ul class="feeds">
                                                <div class="col1">
                                                    <div class="cont">
                                                        <div class="cont-col2">
                                                            <div class="desc"> No orders are present in Customer Issues
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                    <li>
                                                        <div class="row">
                                                            <div class="col-sm-1">
                                                                <div class="label label-sm label-info">
                                                                    <i class="fa fa-shopping-cart"></i>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-7" style="margin-left: -15px">
                                                                Order Number :{{--<a href="/operational/order/view/{{$value['order_id']}}" target="_blank"> AGR{{str_pad($value['order_id'], 9, "0", STR_PAD_LEFT)}} </a>--}}
                                                                {{--@if($value['orders']['consignment_number'] != null || $value['orders']['consignment_number'] != "")
                                                                    <span style="font-size: 12px"><i><b>Consignment number : {{($value['orders']['consignment_number'])}}</b></i></span>
                                                                @endif--}}
                                                                <br>
                                                                <span style="font-size: 11px"><b>Order timestamp : {{--{{$value['orders']['created_at']}}--}}</b></span>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="date"> {{--{{$value['created_at']}}--}} </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                        </ul>
                                    </div>
                                    <div class="scroller-footer">
                                    </div>
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
</div>
<!-- END CONTAINER -->
@endsection
