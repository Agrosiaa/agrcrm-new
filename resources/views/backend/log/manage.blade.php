@extends('backend.seller.layouts.master')
@section('title','Agrosiaa | Logs')
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
                        <div class="portlet light portlet-fit portlet-datatable ">
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover table-checkable" id="log_listing">
                                    <thead>
                                    <tr role="row" class="heading">
                                        <th width="30%"> Sales Agent </th>
                                        <th width="30%"> Customer Mobile </th>
                                        <th width="30%"> Last Updated On </th>
                                        <th width="30%"> View </th>
                                    </tr>
                                    <tr role="row" class="filter">
                                        <td>
                                            <select class="form-control form-filter input-sm" name="user_id">
                                                <option value="">Please select sales agent</option>
                                                @foreach($agents as $agent)
                                                <option value="{{$agent['id']}}"">{{$agent['name']}}</option>
                                                @endforeach
                                            </select>
                                        <td>
                                            <input type="text" class="form-control form-filter input-sm" name="mobile"> </td>
                                        <td>
                                            <div class="input-group date" data-provide="datepicker">
                                                <input type="text" name="from_date" size="10" readonly class="form-control form-filter input-sm">
                                                <span class="input-group-btn">
                                                    <button class="btn default date-set" type="button">
                                                        <i class="fa fa-calendar"></i>
                                                    </button>
                                                </span>
                                            </div>
                                            <div class="input-group date" data-provide="datepicker">
                                                <input type="text" size="10" name="to_date" readonly class="form-control form-filter input-sm">
                                                <span class="input-group-btn">
                                                    <button class="btn default date-set" type="button">
                                                        <i class="fa fa-calendar"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </td>
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
    <script src="/assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
    <script src="/assets/pages/scripts/log/ecommerce-orders.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL SCRIPTS -->
    <!-- BEGIN THEME LAYOUT SCRIPTS -->
    <script src="/assets/layouts/layout3/scripts/layout.min.js" type="text/javascript"></script>
    <script src="/assets/layouts/layout3/scripts/demo.min.js" type="text/javascript"></script>
    <!-- END THEME LAYOUT SCRIPTS -->
@endsection
