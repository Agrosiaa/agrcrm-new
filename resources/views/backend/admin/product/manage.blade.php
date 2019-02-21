@extends('backend.seller.layouts.master')
@section('title','Agrosiaa | Products')
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
@include('backend.partials.common.nav')
@section('content')
<!-- BEGIN PAGE CONTENT BODY -->
<div class="page-content">
    <div class="container">
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12">
                    @include('backend.partials.error-messages')
                    <!-- Begin: life time stats -->
                    <div class="portlet light">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-shopping-cart"></i>Product Listing </div>
                        </div>
                        <div class="portlet-body">
                            <div class="table-container">
                                @if(Session::get('role_type')!='seller')
                                <form action="/product-status-bulk" method="POST">
                                    {!! csrf_field() !!}
                                   <!-- <div class="table-actions-wrapper">
                                        <span> </span>
                                        <select class="table-group-action-input form-control input-inline input-small input-sm">
                                            <option value="">Select...</option>
                                            <option value="Cancel">Approve</option>
                                        </select>
                                        <button class="btn btn-sm base-color btn-default table-group-action-submit">
                                            <i class="fa fa-check"></i> Submit</button>
                                    </div>-->
                                    <?php $disabled = ""; ?>
                                    @else
                                    <?php $disabled = "disabled"; ?>
                                    @endif

                                    <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_products">
                                        <thead>
                                        <tr role="row" class="heading">
                                            <th width="1%">
                                                <input type="checkbox" class="group-checkable" {{$disabled}}> </th>
                                            <th width="5%"> Item Based SKU </th>
                                            <th width="5%"> Sub Category </th>
                                            <th width="25%"> Product Name </th>
                                            <th width="10%"> Agrosiaa SKU </th>
                                            <th width="10%"> Price </th>
                                            <th width="10%"> Quantity </th>
                                            <th width="10%"> Status </th>
                                            <th width="10%"> Verification Status </th>
                                            <th width="10%"> Actions </th>
                                        </tr>
                                        <tr role="row" class="filter">
                                            <td> </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="product_id"> </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="sub_category"> </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="product_name"> </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="product_sku"> </td>

                                            <td>
                                                <div class="margin-bottom-5">
                                                    <input type="text" class="form-control form-filter input-sm" name="product_price_from" placeholder="From" /> </div>
                                                <input type="text" class="form-control form-filter input-sm" name="product_price_to" placeholder="To" /> </td>
                                            <td>
                                                <div class="margin-bottom-5">
                                                    <input type="text" class="form-control form-filter input-sm" name="product_quantity_from" placeholder="From" /> </div>
                                                <input type="text" class="form-control form-filter input-sm" name="product_quantity_to" placeholder="To" /> </td>

                                            <td>
                                                <select name="product_status" class="form-control form-filter input-sm">
                                                    <option value="">Select...</option>
                                                    <option value="publish">Enable</option>
                                                    <option value="unpublished">Disable</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="product_status" class="form-control form-filter input-sm">
                                                    <option value="">Select...</option>
                                                    <option value="publish">Pending</option>
                                                    <option value="unpublished">Query Raised</option>
                                                    <option value="unpublished">Query Resolved</option>
                                                    <option value="unpublished">Admin Approved</option>
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
                                        <td>No Product</td>
                                        <td>No Product</td>
                                        <td>No Product</td>
                                        <td>No Product</td>
                                        <td>No Product</td>
                                        <td>No Product</td>
                                        <td>No Product</td>
                                        <td>No Product</td>
                                        <td>No Product</td>
                                        </tbody>
                                    </table>
                                </form>
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
@include('backend.partials.seller.calculator')
@endsection
@section('javascript')
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="/assets/global/scripts/datatable.js" type="text/javascript"></script>
<script src="/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
<script src="/assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/fancybox/source/jquery.fancybox.pack.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="/assets/custom/seller/validation/calculator.js" type="text/javascript"></script>
<script src="/assets/pages/scripts/ecommerce-products-admin.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jstree/dist/jstree.min.js" type="text/javascript"></script>
<script src="/assets/pages/scripts/ui-tree.min.js" type="text/javascript"></script>
<script src="/assets/custom/common/js/product-info-calculator.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME LAYOUT SCRIPTS -->
<script src="/assets/layouts/layout3/scripts/demo.min.js" type="text/javascript"></script>
<!-- END THEME LAYOUT SCRIPTS -->
@endsection
