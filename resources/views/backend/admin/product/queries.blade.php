@extends('backend.seller.layouts.master')
@section('css')
<!-- BEGIN PAGE LEVEL PLUGINS -->
<link href="/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
<link href="/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
<link href="/assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
<!-- END PAGE LEVEL PLUGINS -->
@endsection
@include('backend.partials.common.nav')
@section('content')
<!-- BEGIN PAGE CONTENT BODY -->
<div class="page-content content-min-height">
    <div class="container">
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal form-row-seperated" action="#">
                        <input type="hidden" name="tokenss" id="tokenss" value="{{ csrf_token() }}">
                        <div class="portlet">
                            <div class="portlet-title">
                                <div class="caption">
                                    Product Queries </div>

                            </div>
                            <div class="portlet-body">
                                <div class="table-container">
                                    <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_queries">
                                        <thead>
                                        <tr role="row" class="heading">
                                            <th width="1%">
                                                <input type="checkbox" class="group-checkable" disabled="disabled"> </th>
                                            <th width="25%"> Product&nbsp;Name &nbsp;(sku) </th>
                                            <th width="10%"> Seller&nbsp;SKU </th>
                                            <th width="20%"> Seller Name </th>
                                            <th width="15%"> Date </th>
                                            <th width="18%"> Verification&nbsp;Status </th>
                                            <th width="15%"> Query </th>
                                        </tr>
                                        <tr role="row" class="filter">
                                            <td> </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="product_id"> </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="product_name"> </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="product_sku"> </td>
                                            <td>
                                                <div class="input-group date date-picker margin-bottom-5" data-date-format="dd/mm/yyyy">
                                                    <input type="text" class="form-control form-filter input-sm" readonly name="order_date_from" placeholder="">
                                                   <span class="input-group-btn">
                                                       <button class="btn btn-sm default" type="button">
                                                           <i class="fa fa-calendar"></i>
                                                       </button>
                                                   </span>
                                                </div>
                                            </td>
                                            <td>
                                                <select name="product_status" class="form-control form-filter input-sm">
                                                    <option value="">Select...</option>
                                                    <option value="unpublished">Query Raised</option>
                                                    <option value="unpublished">Query Resolved</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="margin-bottom-5">
                                                    <button class="btn btn-sm base-color filter-submit margin-bottom">
                                                        <i class="fa fa-search"></i> Search</button>
                                                </div>
                                                <button class="btn btn-sm btn-default filter-cancel">
                                                    <i class="fa fa-times"></i> Reset</button>
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
                </form>
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
@include('backend.partials.common.query-chat-popup')
@endsection
@section('javascript')
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="/assets/global/scripts/datatable.js" type="text/javascript"></script>
<script src="/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="/assets/pages/scripts/ecommerce-queries-admin.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script src="/assets/custom/common/js/query-conversation-adminData.js" type="text/javascript"></script>
@endsection
