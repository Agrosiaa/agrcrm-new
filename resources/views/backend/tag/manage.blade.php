@extends('backend.seller.layouts.master')
@section('title','Agrosiaa | Tags')
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
                            <div class="portlet-title row">
                                <div class="caption">
                                    <i class="icon-settings font-green"></i>
                                    <span class="caption-subject font-green sbold uppercase"> Tag Listing </span>
                                </div>
                                <div class="text-right" style="margin-right: 10px">
                                    <a href="javascript:void(0);" class="btn blue m-icon" id="open-tag-modal">
                                        Create Tag
                                    </a>
                                    <a href="/tag/sync-tag" class="btn green m-icon">Sync Tag</a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover table-checkable" id="tag_listing">
                                    <thead>
                                    <tr role="row" class="heading">
                                        <th width="10%"> Tag Name </th>
                                        <th width="10%"> Tag Type </th>
                                        <th width="10%"> Created Date </th>
                                        <th width="10%"> Action </th>
                                    </tr>
                                    <tr role="row" class="filter">
                                        <td>
                                            <input type="text" class="form-control form-filter input-sm" name="name"> </td>
                                        <td>
                                            <select class="form-control form-filter input-sm" name="tag_type">
                                                <option value="">Please select tag type</option>
                                                @foreach($tagTypes as $tagType)
                                                <option value="{{$tagType['id']}}"">{{$tagType['name']}}</option>
                                                @endforeach
                                            </select>
                                        <td></td>
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
                <div id="create-tag-modal" class="modal fade bs-modal-lg" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-md">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title" style="text-align: center"><b id="tag_modal_title">Create Tag</b></h4>
                            </div>
                            <form class="form-horizontal" method="post" role="form" action="/tag/create-edit-tag">
                                {{csrf_field()}}
                                <input type="hidden" name="tag_id" id="tag_id">
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Tag Type</label>
                                                <div class="col-md-9">
                                                    <select class="form-control" name="tag_type_id">
                                                        <option value="">Please select tag type</option>
                                                        @foreach($tagTypes as $tagType)
                                                        <option value="{{$tagType['id']}}"">{{$tagType['name']}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Tag Name</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" id="tag_name" name="tag_name" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 col-md-offset-8">
                                            <button type="submit" class="btn btn-sm btn-success">Save</button>
                                            <button class="btn btn-sm btn-danger" data-dismiss="modal">Cancel</button>
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
    <script src="/assets/pages/scripts/tag/ecommerce-orders.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL SCRIPTS -->
    <!-- BEGIN THEME LAYOUT SCRIPTS -->
    <script src="/assets/layouts/layout3/scripts/layout.min.js" type="text/javascript"></script>
    <script src="/assets/layouts/layout3/scripts/demo.min.js" type="text/javascript"></script>
    <!-- END THEME LAYOUT SCRIPTS -->
    <script>
        function editTag(id,name,typeId) {
            if(typeId != null){
                $("select option").each(function(){
                    if ($(this).val() == typeId)
                        $(this).attr("selected","selected");
                });
            }
            $('#tag_id').val(id);
            $('#tag_name').val(name);
            $('#tag_modal_title').text('Edit Tag');
            $('#create-tag-modal').modal('show');
        }
        $('#open-tag-modal').on('click',function () {
            $('#tag_id').val(null);
            $('#tag_name').val(null);
            $('#tag_modal_title').text('Create Tag');
            $('#create-tag-modal').modal('show');
        })
    </script>
@endsection
