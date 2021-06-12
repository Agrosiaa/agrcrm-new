@extends('backend.seller.layouts.master')
@section('title','Agrosiaa | Leads')
@include('backend.partials.common.nav')
@section('css')
    <style>
        .sortable-handler {
            touch-action: none;
        }
    </style>
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/jstree/dist/themes/default/style.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->
   <script type="text/css">
   </script>
@endsection
@section('content')
    <!-- BEGIN PAGE CONTENT BODY -->
    <!-- BEGIN PAGE CONTENT BODY -->
    <div class="page-content">
        <div class="container">
            <!-- BEGIN PAGE CONTENT INNER -->
            <div class="page-content-inner">
                <div class="row">
                    @include('backend.partials.error-messages')
                    <div class="col-md-12">
                        <input type="hidden" id="base_url" value="{{env('BASE_URL')}}">
                        <!-- Begin: life time stats -->
                        <?php $totalRecords = CustomerNumberHelper::orderCount(); ?>
                        <div class="portlet light portlet-fit portlet-datatable ">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="icon-settings font-green"></i>
                                    <span class="caption-subject font-green sbold uppercase"> Customer status Listing </span>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row margin-bottom-40">
                                        <div class="col-md-2 tags current dashboard-stat purple-plum" onclick="redirect('new')">
                                            <div class="tag1">
                                                <div class="text-center"></div>
                                                <div class="text-center margin-bottom-10 margin-top-10">New ({{$totalRecords['new']}})</div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 tags dashboard-stat yellow-mint" onclick="redirect('call-back')">
                                            <div class="tag2">
                                                <div class="text-center"></div>
                                                <div class="text-center margin-bottom-10 margin-top-10">Call Back ({{$totalRecords['call-back']}})</div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 tags dashboard-stat green-dark" onclick="redirect('complete')">
                                            <div class="tag3">
                                                <div class="text-center"></div>
                                                <div class="text-center margin-bottom-10 margin-top-10">Complete ({{$totalRecords['complete']}})</div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 tags dashboard-stat red-flamingo" onclick="redirect('failed')">
                                            <div class="tag4">
                                                <div class="text-center"></div>
                                                <div class="text-center margin-bottom-10 margin-top-10">Failed ({{$totalRecords['failed']}})</div>
                                            </div>
                                        </div>
                                    <input type="hidden" name="current_status" id="current_status" value="{{Route::current()->getParameter('type')}}" />
                                </div>
                                <div class="table-container">
                                    @if($user['role_id'] == 1)
                                        <div class="text-right">
    <!--                                        <a href="/leads/export-customer-number" class="btn blue">-->
    <!--                                            Upload Sheet-->
    <!--                                        </a>-->
                                            <a href="/leads/import-customer-call-data" class="btn blue">
                                                Customer Call Data Sheet
                                            </a>
                                            <a href="/leads/sync-abandoned-cart" class="btn blue">
                                                Sync Cart
                                            </a>
                                            <a href="javascript:void(0);" class="btn blue m-icon" data-toggle="modal" data-target="#assign-to-agents-modal">
                                                Assign
                                            </a>
                                        </div>
                                    @endif
                                    @if($user['role_id'] == 1 && (($status == 'new') || ($status == 'call-back') || ($status == 'complete') || ($status == 'failed')))
                                        <table class="table table-striped table-bordered table-hover table-checkable" id="sales_admin_list">
                                            <thead>
                                            <tr role="row" class="heading">
                                                <th width="20%"> Mobile&nbsp;No </th>
                                                <th width="30%"> Assigned Agent </th>
                                                <th width="30%"> Allocated </th>
                                                <th width="20%"> Actions </th>

                                            </tr>
                                            <tr role="row" class="filter">
                                                <td>
                                                    <input type="text" class="form-control form-filter input-sm" name="mobile_number"> </td>
                                                <td>
                                                    <input type="text" class="form-control form-filter input-sm" name="agent_name" > </td>
                                                <td>
                                                    <input type="text" class="form-control form-filter input-sm" name="created_date"> </td>
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
                                    @elseif($user['role_id'] == 2 && (($status == 'new') || ($status == 'call-back') || ($status == 'complete') || ($status == 'failed')))
                                    <table class="table table-striped table-bordered table-hover table-checkable" id="sales_admin_list">
                                        <thead>
                                        <tr role="row" class="heading">
                                            <th width="20%"> Mobile&nbsp;No </th>
                                            <th width="30%"> Allocated </th>
                                            <th width="50%"> Actions </th>
                                        </tr>
                                        <tr role="row" class="filter">
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="mobile_number" > </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="created_date"> </td>
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
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!-- End: life time stats -->
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
                                <div class="row">
                                    @foreach($callBacks as $callBack)
                                        @if($status == 'new' && $callBack['slug'] == 'call-back-1')
                                            <div class="col-md-3" id="call_back_1">
                                                <button type="button" class="btn btn-circle blue btn-outline" onclick="setReminder({{$callBack['id']}})">{{$callBack['name']}}</button>
                                            </div>
                                        @elseif($status == 'call-back' && $callBack['slug'] != 'call-back-1')
                                            @if($callBack['slug'] == 'call-back-2')
                                            <div class="col-md-3 call_back" id="call_back_2">
                                                <button type="button" class="btn btn-circle blue btn-outline" onclick="setReminder({{$callBack['id']}})">{{$callBack['name']}}</button>
                                            </div>
                                            @endif
                                                @if($callBack['slug'] == 'call-back-3')
                                                    <div class="col-md-3 call_back" id="call_back_3">
                                                        <button type="button" class="btn btn-circle blue btn-outline" onclick="setReminder({{$callBack['id']}})">{{$callBack['name']}}</button>
                                                    </div>
                                                @endif
                                        @endif
                                    @endforeach
                                </div>
                                <br>
                                <div class="row" id="query-form">
                                    <div class="col-md-10">
                                        <input type="text" name="reply_text" id="reply_text" required="required" class="form-control col-md-10" maxlength="500" placeholder="reply">
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
                <div id="reminder_modal" class="modal fade bs-modal-md" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-md">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title" style="text-align: center"><b>Set Reminder for Next Call</b></h4>
                            </div>
                            <form class="form-horizontal" method="post" role="form" action="/leads/set-reminder">
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
                                            <input type="hidden" id="customer_status_detail_id" name="customer_status_detail_id" value="">
                                            <input type="hidden" id="call_back_id" name="call_back_id" value="">
                                            <button type="submit" class="btn btn-sm btn-success">Create</button>
                                            <button class="btn btn-sm btn-danger" data-dismiss="modal">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                {{--Assign student modal--}}
                <div id="assign-to-agents-modal" class="modal fade bs-modal-lg" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <input type="hidden" id="product_id" value="">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title" style="text-align: center"><b>Assign Number to Agent</b></h4>
                            </div>
                           <form class="form-horizontal" id="assign_num_form" method="post" role="form" action="/leads/assign-customer">
                               <div class="modal-body">
                                   {{csrf_field()}}
                                   <div class="row">
                                       <div class="col-md-12">
                                           <div class="form-group">
                                               <label class="col-md-4 control-label">Mobile Number</label>
                                               <div class="col-md-4">
                                                   <input type="number" class="form-control" id="mobile_number" name="mobile_number" required>
                                               </div>
                                           </div>
                                       </div>
                                   </div>
                                   <div class="row">
                                       <div class="col-md-12">
                                           <div class="form-group">
                                               <label class="col-md-4 control-label">Source</label>
                                               <div class="col-md-4">
                                                   <input type="text" class="form-control" id="lead_source" name="lead_source">
                                               </div>
                                           </div>
                                       </div>
                                   </div>
                                    <div class="row">
                                        <div class="col-md-4 col-md-offset-8">
                                            <button type="submit" class="btn btn-sm btn-success">Assign</button>
                                            <button class="btn btn-sm btn-danger" data-dismiss="modal">Cancel</button>
                                        </div>
                                    </div>
                              </div>
                           </form>
                        </div>
                    </div>
                </div>
                <div id="create-customer-modal" class="modal fade bs-modal-lg" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title" style="text-align: center"><b>Create Customer</b></h4>
                            </div>
                            <hr>
                            <form id="create-customer-form">
                                {{ csrf_field() }}
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">First Name : <span class="required">*</span></label>
                                                <div class="col-md-4">
                                                    <input type="text" class="form-control" id="fname" name="fname" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Last Name : <span class="required">*</span></label>
                                                <div class="col-md-4">
                                                    <input type="text" class="form-control" id="lname" name="lname" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Birth date : </label>
                                                <div class="col-md-4">
                                                    <input type="date" class="form-control" id="dob" name="dob">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Email id : </label>
                                                <div class="col-md-4">
                                                    <input type="text" class="form-control" id="email" name="email">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Mobile Number : <span class="required">*</span></label>
                                                <div class="col-md-4">
                                                    <input type="text" class="form-control" id="cust_mobile_number" name="mobile" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Address : </label>
                                                <div class="col-md-4">
                                                    <a id="add-new-address">Click here to add address</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div id="address-div">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Full Name : <span class="required">*</span></label>
                                                    <div class="col-md-4">
                                                        <input type="text" class="form-control address" id="address_fname" name="address_fname" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Mobile Number : <span class="required">*</span></label>
                                                    <div class="col-md-4">
                                                        <input type="number" class="form-control address" id="address_mobile" name="address_mobile" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Flat/Door/Blockno./House : <span class="required">*</span></label>
                                                    <div class="col-md-4">
                                                        <input type="text" class="form-control address" id="house_block" name="house_block" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Name of Premises/Building/Village : <span class="required">*</span></label>
                                                    <div class="col-md-4">
                                                        <input type="text" class="form-control address" id="village_premises" name="village_premises" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-5 control-label">Area/Locality/Wadi : <span class="required">*</span></label>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control address" id="area" name="area" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-5 control-label">Road Street Lane : <span class="required">*</span></label>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control address" id="road_street" name="road" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="col-md-5 control-label">Pincode
                                                        <span class="required"> * </span>
                                                    </div>
                                                    <div class="col-md-5" >
                                                        <input type="text" class="typeahead form-control" id="pincode" name="pincode" />
                                                        <span style="color: darkred"><h7>Make sure you choose the exact pincode from the dropdown values only</h7></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="col-md-5 control-label">At Post
                                                        <span class="required"> * </span>
                                                    </div>
                                                    <div id="at-post" class="col-md-5">
                                                        <select id="atPost" name="at_post" class="form-control">

                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-5 control-label">State : <span class="required">*</span></label>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control address" id="stateName" name="state" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-5 control-label">District : <span class="required">*</span></label>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control address" id="district" name="dist" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-5 control-label">Taluka : <span class="required">*</span></label>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control address" id="taluka" name="taluka" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" class="form-control" id="lead_crm_id" name="lead_crm_id">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="submit" id="create_customer" class="btn btn-sm btn-success">Create</button>
                                            <button class="btn btn-sm btn-danger pull-right" data-dismiss="modal">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                </div>
            </div>
            <!-- END PAGE CONTENT INNER -->
        </div>
    </div>
    <!-- END PAGE CONTENT BODY -->
    <!-- END PAGE CONTENT BODY -->
@endsection
@section('javascript')
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
    <script src="/assets/pages/scripts/csr/leads/ecommerce-orders.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>

    <!-- END PAGE LEVEL SCRIPTS -->
    <!-- BEGIN THEME LAYOUT SCRIPTS -->
    <script src="/assets/layouts/layout3/scripts/layout.min.js" type="text/javascript"></script>
    <script src="/assets/layouts/layout3/scripts/demo.min.js" type="text/javascript"></script>

    <!-- END THEME LAYOUT SCRIPTS -->
    <script>
        $( document ).ready(function() {
           $('#address-div').hide();
            jQuery(document).ready(function() {
                //Assign number to sale agent validation
                jQuery("#assign_num_form").validate({
                rules: {
                    mobile_number: {
                        required: true,
                        minlength:9,
                        maxlength:10,
                        number: true
                    }
                },
                    messages: {
                        mobile_number: {
                            required: 'Please enter the mobile number',
                            number: 'Please enter valid mobile number',
                            minlength: 'Please enter valid mobile number',
                            maxlength: 'Please enter valid mobile number'
                        }

                    },
                    submitHandler: function(form) {
                        form.submit();
                    }
            });

                //Create customer form validate
                jQuery("#create-customer-form").validate({
                    rules: {
                        fname: 'required',
                        lname: 'required',
                        address_fname: 'required',
                        flat_house_no: 'required',
                        village: 'required',
                        area: 'required',
                        road: 'required',
                        pincode: 'required',
                        at_post: 'required',
                        state: 'required',
                        dist: 'required',
                        taluka: 'required',
                        mobile_number: {
                            required: true,
                            minlength:9,
                            maxlength:10,
                            number: true
                        },
                        address_mobile_number: {
                            required: true,
                            minlength:9,
                            maxlength:10,
                            number: true
                        }
                    },
                    messages: {
                        fname: 'Please enter customer first name',
                        lname: 'Please enter customer last name',
                        address_fname: 'Please enter customer full name',
                        flat_house_no: 'Please enter flat/door/blockno./house',
                        village: 'Please enter premises/building/village',
                        area: 'Please enter area/locality/wadi',
                        road: 'Please enter road street lane',
                        pin_code: 'Please enter pin code',
                        post: 'Please enter post',
                        state: 'Please enter state',
                        dist: 'Please enter district',
                        taluka: 'Please enter taluka',
                        mobile_number: {
                            required: 'Please enter the mobile number',
                            number: 'Please enter valid mobile number',
                            minlength: 'Please enter valid mobile number',
                            maxlength: 'Please enter valid mobile number'
                        },
                        address_mobile_number: {
                            required: 'Please enter the mobile number',
                            number: 'Please enter valid mobile number',
                            minlength: 'Please enter valid mobile number',
                            maxlength: 'Please enter valid mobile number'
                        }

                    },
                    submitHandler: function(form) {
                        form.submit();
                    }
                });
        });

        });
        function redirect(slug){
            window.location.href= '/leads/manage/'+slug;
        }
    </script>
    <script>

        function passId(id,number) {
            $('#reply').modal('show');
            $('#customer_detail_id').val(id);
            $('#customer_detail_mobile').val(number);
            $('#customer_status_detail_id').val(id);
            $('.reply-title').text("Chat History - " +number);
            $('.call_back').hide();
            $.ajax({
                url: '/leads/call-back-status/'+id,
                type: 'get',
                dataType: 'json',
                success: function (response) {
                    var nextCallBack = response['status_id'] + 1;
                    if(response['setNextCall'] == true){
                        $('#call_back_'+nextCallBack+'').show();
                    } else {
                        $('#call_back_'+nextCallBack+'').hide();
                    }
                },
                error: function (response) {
                    
                }
            });
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
                }
            });
        }

        function setReminder(callId) {
            if(callId == 3){
                var customer= $('#customer_detail_id').val();
                var customerNumber = $('#customer_detail_mobile').val();
                $.ajaxSetup({ headers: { 'X-CSRF-TOKEN' : '<?php echo e(csrf_token()); ?>' } });
                $.ajax({
                    url: '/leads/set-reminder',
                    type: 'POST',
                    dataType: 'array',
                    data: {
                        'call_back_id' : callId,
                        'customer_status_detail_id' : customer,
                        'reminder_time' : ''
                    },
                    success: function (responce) {
                        passId(customer,customerNumber);
                    },
                    error: function (responce) {
                        passId(customer,customerNumber);
                    }
                })
            }else {
                $('#reminder_modal').modal('show');
                $('#call_back_id').val(callId);
            }
        }

        function createCustomer(leadId, mobile) {
            $('#create-customer-modal').modal('show');
            $('#cust_mobile_number').val(mobile);
            $('#lead_crm_id').val(leadId);
            $('#address-div').hide();
        }

        $('#add-new-address').on('click',function () {
            $('#address-div').show();
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
                        passId(customer,customerNumber);
                    },
                    error: function (responce) {
                        document.getElementById("reply_text").value = "";
                        passId(customer,customerNumber);
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
                    'customer_id' : customer
                },
                success: function (responce) {
                    passId(customer,customerNumber);
                },
                error: function (responce) {
                    passId(customer,customerNumber);
                }
            })
        });

        $(document).on("click","#create_customer",function (e) {
            e.stopPropagation();
            if($('#fname').val() != '' && $('#lname').val() != '' && $('#cust_mobile_number').val() != ''){
                var mob = $('#cust_mobile_number').val();
                if($('#address-div').is(":visible")){
                    if( $('#stateName').val() != '' && $('#district').val() != '' && $('#taluka').val() != '' &&
                        $('#area').val() != '' && $('#village_premises').val() != '' && $('#house_block').val() != '' &&
                        $('#address_mobile').val() != '' && $('#address_fname').val() != '' && $('#pincode').val() != '' &&
                        $('#atPost').val() != '' && $('#road_street').val() != ''
                    ){
                        $.ajax({
                            url: "/customer/create-customer",
                            type: 'POST',
                            dataType: 'array',
                            data: $('#create-customer-form').serialize(),
                            success: function (responce) {
                                $('#create-customer-modal').modal('toggle');
                                window.location.href('/customer/customer-details/'+mob+'/null');
                            },
                            error: function (responce) {
                                location.reload();
                                $('#create-customer-modal').modal('toggle');
                            }
                        });
                    }
                } else {
                    $.ajax({
                        url: "/customer/create-customer",
                        type: 'POST',
                        dataType: 'array',
                        data: $('#create-customer-form').serialize(),
                        success: function (responce) {
                            $('#create-customer-modal').modal('toggle');
                            window.location.href('/customer/customer-details/'+mob+'/null');
                        },
                        error: function (responce) {
                            location.reload();
                            $('#create-customer-modal').modal('toggle');
                        }
                    });
                }
            }
        });
    </script>
@endsection
