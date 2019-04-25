@extends('backend.seller.layouts.master')
@section('title','Agrosiaa | Leads')
@include('backend.partials.common.nav')
@section('css')
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/jstree/dist/themes/default/style.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->
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
                                   @if($user['role_id'] == 1)
                                        <div class="col-md-4 pull-right" style="padding-top: 15px;">
                                            <a href="/leads/export-customer-number" class="btn blue" style="margin-left: 30%">
                                                Upload Sheet
                                            </a>
                                        </div>
                                    @endif
                                    </div>
                                    @if($user['role_id'] == 1 && (($status == 'new') || ($status == 'call-back') || ($status == 'complete') || ($status == 'failed')))
                                        <table class="table table-striped table-bordered table-hover table-checkable" id="sales_admin_list">
                                            <thead>
                                            <tr role="row" class="heading">
                                                <th width="20%"> Mobile&nbsp;No </th>
                                                <th width="30%"> Assigned Agent </th>
                                                <th width="30%"> Timestamp </th>
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
                                            <th width="30%"> Timestamp </th>
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
                </div>
            </div>
            <!-- END PAGE CONTENT INNER -->
        </div>
    </div>
    <!-- END PAGE CONTENT BODY -->
    <!-- END PAGE CONTENT BODY -->
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
    <script src="/assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
    <script src="/assets/pages/scripts/superadmin/order/ecommerce-orders.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL SCRIPTS -->
    <!-- BEGIN THEME LAYOUT SCRIPTS -->
    <script src="/assets/layouts/layout3/scripts/layout.min.js" type="text/javascript"></script>
    <script src="/assets/layouts/layout3/scripts/demo.min.js" type="text/javascript"></script>

    <!-- END THEME LAYOUT SCRIPTS -->
    <script>

        function redirect(slug){
            window.location.href= '/leads/manage/'+slug;
        }
    </script>
@endsection
