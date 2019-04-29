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
                                        <div class="col-md-2" style="padding-top: 15px;">
                                            <a href="/leads/export-customer-number" class="btn blue" style="margin-left: 30%">
                                                Upload Sheet
                                            </a>
                                        </div>
                                        <div class="col-md-2 pull-right" style="padding-top: 15px;">
                                            <a href="javascript:void(0);" class="btn blue m-icon" data-toggle="modal" data-target="#assign-to-agents-modal">
                                                Assign
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
                <div id="reply" class="modal" role="dialog" data-dismiss="modal">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title reply-title"> </h4>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="portlet light ">
                                            <div class="portlet-body">
                                                <div class="scroller" style="height: 338px;" data-always-visible="1" data-rail-visible1="0" data-handle-color="#D7DCE2">
                                                    <div class="general-item-list" id="chat_message">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="query-form">
                                    <form action="" role="form">
                                        <div class="col-md-9">
                                            <input type="text" name="reply_text" id="reply_text" required="required" maxlength="500" class="form-control" placeholder="reply">
                                            <input type="hidden" id="customer_detail_id" value="">
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-sm btn-success table-group-action-submit chat-submit pull-right">Reply</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
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
                           <form>
                               <div class="modal-body">
                                   <div class="row">
                                       <div class="col-md-12">
                                           <div class="form-group">
                                               <label class="col-md-4 control-label">Mobile Number</label>
                                               <div class="col-md-4">
                                                   <input type="text" class="form-control" id="mobile_number" name="mobile_number" value="">
                                               </div>
                                           </div>
                                       </div>
                                   </div>
                                    <div class="row">
                                        <div class="col-md-4 col-md-offset-8">
                                            <button type="submit" class="btn btn-sm btn-success">Create</button>
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
    <script>
        function passId(id,number) {
            $('#reply').modal('show');
            $('#customer_detail_id').val(id);
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
                        str  += '<div class="item">'+
                            '<div class="item-head">'+
                            '<div class="item-details">'+
                            '<img class="item-pic rounded" height="35" width="35" src="/assets/layouts/layout3/img/avatar.png">'+
                            '<span>'+data['userName']+'</span>'+
                            '&nbsp;&nbsp;&nbsp;<span class="item-label">' +data['time']+ ' Ago</span>'+
                            '</div>'+
                            '</div>'+
                            '<div class="item-body">'+
                            '<span>'+data['message']+'</span>'+
                            '</div>'+
                            '</div>'+
                            '<br>';
                    });
                    $('#chat_message').html(str);
                },
                error: function (responce) {
                    console.log(responce);
                }
            });
        }
        $(document).on("click",".chat-submit",function (e) {
            e.stopPropagation();
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
                    $('#reply').modal('toggle');
                    location.reload();
                },
                error: function (responce) {
                    location.reload();
                    $('#reply').modal('toggle');
                }
            })
        });
    </script>
@endsection
