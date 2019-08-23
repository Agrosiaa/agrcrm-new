@extends('backend.seller.layouts.master')
@section('title','Agrosiaa | Agents')
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
    <link href="/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="/assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
    <link href="/assets/layouts/layout3/css/layout.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/layouts/layout3/css/themes/default.min.css" rel="stylesheet" type="text/css" id="style_color" />
    <link href="/assets/layouts/layout3/css/custom.min.css" rel="stylesheet" type="text/css" />
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
                        <div class="portlet light portlet-fit portlet-datatable ">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="icon-settings font-green"></i>
                                    <span class="caption-subject font-green sbold uppercase"> Agent status Listing </span>
                                </div>
                            </div>
                            <div class="portlet-body">
                                    <table class="table table-striped table-bordered table-hover table-checkable" id="sales_agent_list">
                                        <thead>
                                        <tr role="row" class="heading">
                                            <th width="30%"> Agent Name </th>
                                            <th width="30%"> Actions </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($saleAgents as $saleAgent)
                                            @if($saleAgent['is_active'] == true)
                                            <tr role="row">
                                                <td class="sorting_1">{{$saleAgent['name']}}</td>
                                                <td>
                                                    <div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-small bootstrap-switch-animate bootstrap-switch-on" style="width: 90px;">
                                                        <div class="bootstrap-switch-container" onclick="changeStatus({{$saleAgent['id']}})" style="width: 132px; margin-left: 0px;">
                                                            <span class="bootstrap-switch-handle-on bootstrap-switch-primary" style="width: 44px;">ON</span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @else
                                            <tr role="row">
                                                <td class="sorting_1">{{$saleAgent['name']}}</td>
                                                <td>
                                                    <div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-small bootstrap-switch-animate bootstrap-switch-off" style="width: 90px;">
                                                        <div class="bootstrap-switch-container" onclick="changeStatus({{$saleAgent['id']}})" style="width: 132px; margin-left: 0px;">
                                                            <span class="bootstrap-switch-handle-off bootstrap-switch-default" style="width: 44px;margin-left: 44px;">OFF</span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endif
                                        @endforeach
                                        </tbody>
                                    </table>
                            </div>
                            <div class="portlet-title">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="caption">
                                            <i class="icon-settings font-green"></i>
                                            <span class="caption-subject font-green sbold uppercase"> Abandoned Cart Agent </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <span> Please select agent for abandoned cart</span>
                                        <select id="select-agent" class="" style="-webkit-appearance: menulist; align-self: center">
                                            <option>Assign Agent</option>
                                            @foreach($saleAgents as $saleAgent)
                                                <option value="{!! $saleAgent['id'] !!}">{!! $saleAgent['name'] !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
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
    <script src="../assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL SCRIPTS -->
    <script src="/assets/global/scripts/app.min.js" type="text/javascript"></script>
    <!-- END THEME GLOBAL SCRIPTS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="/assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
    {{--<script src="/assets/pages/scripts/superadmin/Agents/sales-agents.min.js" type="text/javascript"></script>--}}
    <script src="/assets/pages/scripts/components-bootstrap-switch.min.js" type="text/javascript"></script>

    <!-- END PAGE LEVEL SCRIPTS -->
    <!-- BEGIN THEME LAYOUT SCRIPTS -->
    <script src="/assets/layouts/layout3/scripts/layout.min.js" type="text/javascript"></script>
    <script src="/assets/layouts/layout3/scripts/demo.min.js" type="text/javascript"></script>
    <script src="/assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
    <script>
        function changeStatus(id) {
            $.ajax({
                url: '/agents/change-agent-status/' + id,
                type: 'get',

                success: function(responce) {
                    console.log(responce);
                    location.reload();
                },
                error: function(responce) {
                    console.log(responce);
                    location.reload();
                }
            })
        }
    </script>
    <script>
        $('#select-agent').on('change',function () {
            var agent = this.value;
            alert(agent);
            $.ajax({
                url: '/agents/assign-abandoned-cart-agent/' + agent,
                type: 'get',

                success: function(responce) {
                },
                error: function(responce) {

                }
            })
        })
    </script>
@endsection
