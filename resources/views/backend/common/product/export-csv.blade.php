@extends('backend.seller.layouts.master')
@section('content')
@include('backend.partials.common.nav')
<!-- BEGIN PAGE CONTENT BODY -->
<div class="page-content">
<div class="container">
<!-- BEGIN PAGE BREADCRUMBS -->
<!-- END PAGE BREADCRUMBS -->
<!-- BEGIN PAGE CONTENT INNER -->
  <div class="page-content-inner">
    <div class="row">
      <div class="col-md-12">
        <div class="portlet light ">
          <div class="portlet-title">
            <div class="caption" style="color: #78A539">
              <span class="caption-subject sbold uppercase">Export Excel</span>
            </div>
          </div>
          <div class="portlet-body form">
            @include('backend.partials.error-messages')
            <!-- BEGIN FORM-->
            <form action="/administration/export" method="POST" class="form-horizontal form-bordered">
                {!! csrf_field() !!}
                <div class="form-body">
                  <div class="row">
                      <div class="panel panel-success">
                          <div class="panel-heading">
                              <h3 class="panel-title">Important Notes</h3>
                          </div>
                          <div class="panel-body">
                              <ul>
                                  <li>Maximum<strong> 100</strong> products can be uploaded for one item head for a single import instance.</li>
                                  <li>Products will fail to upload in case name on the tabs in the excel sheet are altered.</li>
                                  <li>Strictly use dropdown values <strong>(Brand, Pickup Address etc)</strong> for related fields in the excel sheet.</li>
                                  <li>For successful validation, following fields should not be left blank: <strong>vendor sku, product name, base price, quantity</strong></li>
                                  <li>Search keywords should be , separated only.</li>
                              </ul>
                          </div>
                      </div>
                    <div class="form-group">
                        <label class="col-md-3"> Select Category
                        </label>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group">
                        <div class="col-md-5">
                            <select class="form-control" name="category">
                                @foreach($rootCategories as $rootCategorie)
                                    <option value="{{$rootCategorie['slug']}}">{{$rootCategorie['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        @if($rootCategories)
                        <div class="col-md-2">
                            <button type="submit" class="btn base-color" id="btn-export"> Export As Excel </button>
                        </div>
                        @endif
                        {{--<div class="loading-img" style="display: none;">--}}
                            {{--<img src="/assets/global/img/loading-spinner-grey.gif">--}}
                        {{--</div>--}}
                    </div>
                  </div>
                </div>
              </form>
              <!-- END FORM-->
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
<!-- END CONTAINER -->
<!-- BEGIN FOOTER -->
<!-- BEGIN INNER FOOTER -->
@endsection
@section('javascript')
@endsection
