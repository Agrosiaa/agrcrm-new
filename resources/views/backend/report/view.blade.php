@extends('backend.seller.layouts.master')
@section('title','Agrosiaa | Report')
@include('backend.partials.common.nav')
@section('css')
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->
@endsection
@section('content')
    <!-- BEGIN CONTAINER -->
    <div class="page-container">
        <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
            <!-- BEGIN CONTENT BODY -->
            <!-- BEGIN PAGE CONTENT BODY -->
            @include('backend.partials.error-messages')
            <div class="page-content min-height">
                <div class="container">
                    <!-- BEGIN PAGE BREADCRUMBS -->

                    <!-- END PAGE BREADCRUMBS -->
                    <!-- BEGIN PAGE CONTENT INNER -->
                    <div class="page-content-inner">
                        <!-- </div> -->
                        <form action="/report/generate" method="POST" class="form-horizontal form-bordered" enctype="multipart/form-data" id="report">
                            {!! csrf_field() !!}
                            <div class="form-body" id="report-list">
                                <div class="row">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Select Report</label>
                                        <div class="col-md-4">
                                            <select class="form-control" id="role" name="report">
                                                <option value="">Select...</option>
                                                <option value="sales-orders">Sales Orders Report</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-body" id="date-select" hidden>
                                <div class="row">
                                    <div class="form-group" >
                                        <label class="control-label col-md-3 ">Select Date</label>
                                        <div class="input-group date date-picker col-md-4" data-date-format="yyyy-mm-dd">
                                            <input style="margin-left: 4%" type="text" class="form-control form-filter input-sm"  name="date" id="datepicker">
                                            <span  class="input-group-btn">
                                      <button class="btn btn-sm default" type="button">
                                          <i class="fa fa-calendar"></i>
                                      </button>
                                  </span>
                                        </div>
                                        <div class="portlet-body col-md-7" id="message" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-body" id="date-select-to-for" hidden>
                                <div class="row ">
                                    <div class="col-md-8 col-md-offset-1" >
                                        <label class="control-label col-md-3">Select from Date</label>
                                        <div class="input-group date date-picker col-md-4" data-date-format="yyyy-mm-dd">
                                            <input type="text" class="form-control form-filter input-sm"  name="from_date" id="datepicker">
                                            <span class="input-group-btn">
                                      <button class="btn btn-sm default" type="button">
                                          <i class="fa fa-calendar"></i>
                                      </button>
                                    </span>
                                        </div>
                                    </div>
                                    <br><br>
                                    <div class="col-md-8 col-md-offset-1">
                                        <label class="control-label col-md-3">Select to Date</label>
                                        <div class="input-group date date-picker col-md-4" data-date-format="yyyy-mm-dd">
                                            <input type="text" class="form-control form-filter input-sm"  name="to_date" id="datepicker">
                                            <span class="input-group-btn">
                                      <button class="btn btn-sm default" type="button">
                                          <i class="fa fa-calendar"></i>
                                      </button>
                                    </span>
                                        </div>
                                    </div>
                                    <br><br>
                                    <div style="margin-left: 3%" class="portlet-body col-md-6" id="message1" >
                                    </div>
                                </div>
                            </div>
                            <div class="form-body" id="year-select" hidden>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Select Year</label>
                                        <div class="col-md-4">
                                            <select class="form-control" id="year" name="year">
                                                <option value="">Select...</option>
                                                <option value="{!! $thisYear !!}">{!! $thisYear !!}</option>
                                                <option value="{!! $nextYear !!}">{!! $nextYear !!}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-body" id="submit" hidden>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="control-label col-md-3"></label>
                                        <div class="col-md-3">
                                            <button type="submit" class="btn base-color fileinput-exists" id="submit">Export as Excel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
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
@section('javascript')
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="/assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>

    <script>
        $('#role').on('change', function() {
            if(this.value == 'out-stock'){
                $('#date-select-to-for').hide();
                $('#vendor-select').show();
                $('#submit').show();
                $('#date-select').hide();
                $('#status-list').hide();
                $('#quarter-list').hide();
                $('#year-select').hide();
            }else if(this.value == 'delivery' || this.value == 'time-sale' || this.value == 'return-pick-up'){
                $('#date-select-to-for').hide();
                $('#vendor-select').hide();
                $('#date-select').show();
                $('#submit').show();
                $('#status-list').hide();
                $('#quarter-list').hide();
                $('#year-select').hide();
            }else if(this.value == 'return'){
                $('#date-select-to-for').hide();
                $('#vendor-select').show();
                $('#date-select').show();
                $('#status-list').show();
                $('#submit').show();
                $('#quarter-list').hide();
                $('#year-select').hide();
            }else if(this.value == 'product-expiry' || this.value == 'vendor-licence'){
                $('#date-select-to-for').hide();
                $('#quarter-list').show();
                $('#year-select').show();
                $('#submit').show();
                $('#date-select').hide();
                $('#status-list').hide();
                $('#vendor-select').hide();
            }else if(this.value == 'settlement' || this.value =='taxation' || this.value == 'logistic-accounting' || this.value =='krishimitra-orders' || this.value =='sales-orders'){
                $('#vendor-select').hide();
                $('#date-select').hide();
                $('#year-select').hide();
                $('#date-select-to-for').show();
                $('#status-list').hide();
                $('#submit').show();
                $('#quarter-list').hide();
            }
        });
    </script>
    <script>
        $('#role').on('change', function() {
            if(this.value == 'return'){
                $('#message').find('div').remove();
                $('<div class=" note note-success" style="margin-left: 44.5%;margin-top: 2%">' +
                    '<p>Select any month & consider it as <b>Target Month</b></p>' +
                    '<p>Consider 5th of Target Month as <b>boundary date</b></p>' +
                    '<br>'+
                    '<p>If expected return requested date lies before boundary date & in Target Month,then choose any date between return requested date to End of Target Month</p>'+
                    '<p><b>eg: </b>Target Month : May</p>'+
                    '<p>Boundary date : 5th May</p>'+
                    '<p>Expected return requested date : 3rd May</p>'+
                    '<p> Date Selection : 3rd May - 31st May</p>'+
                    '<br>'+
                    '<p>If expected return requested date lies after boundary date & in Target Month,then choose any date between return requested date to 5th of month next to Target Month</p>'+
                    '<p><b>eg: </b>Target Month : May</p>'+
                    '<p>Boundary date : 5th May</p>'+
                    '<p>Expected return requested date : 20th May</p>'+
                    '<p> Date Selection : 20th May - 5th June</p>'+
                    '<p><b>(Same logic for all status of return process)</b></p>'+
                    '</div>').appendTo('#message');
            } else if(this.value=='return-pick-up'){
                $('#message').find('div').remove();
                $('<div class=" note note-success" style="margin-left: 44.5%;margin-top: 2%">' +
                    '<p>Select any month & consider it as <b>Target Month</b></p>' +
                    '<p>Consider 5th of Target Month as <b>boundary date</b></p>' +
                    '<br>'+
                    '<p>If expected return requested date lies before boundary date & in Target Month,then choose any date between return requested date to End of Target Month</p>'+
                    '<p><b>eg: </b>Target Month : May</p>'+
                    '<p>Boundary date : 5th May</p>'+
                    '<p>Expected return requested date : 3rd May</p>'+
                    '<p> Date Selection : 3rd May - 31st May</p>'+
                    '<br>'+
                    '<p>If expected return requested date lies after boundary date & in Target Month,then choose any date between return requested date to 5th of month next to Target Month</p>'+
                    '<p><b>eg: </b>Target Month : May</p>'+
                    '<p>Boundary date : 5th May</p>'+
                    '<p>Expected return requested date : 20th May</p>'+
                    '<p> Date Selection : 20th May - 5th June</p>'+
                    '</div>').appendTo('#message');
            } else if(this.value=='delivery'){
                $('#message').find('div').remove();
                $('<div class=" note note-success" style="margin-left: 44.5%;margin-top: 2%">' +
                    '<p>Select any month & consider it as <b>Target Month</b></p>' +
                    '<p>Consider 5th of Target Month as <b>boundary date</b></p>' +
                    '<br>'+
                    '<p>If expected order placed date lies before boundary date & in Target Month,then choose any date between order placed date to End of Target Month.</p>'+
                    '<p><b>eg: </b>Target Month : May</p>'+
                    '<p>Boundary date : 5th May</p>'+
                    '<p>Expected return requested date : 3rd May</p>'+
                    '<p> Date Selection : 3rd May - 31st May</p>'+
                    '<br>'+
                    '<p>If expected order placed date lies after boundary date & in Target Month,then choose any date between order placed date to 5th of month next to Target Month.</p>'+
                    '<p><b>eg: </b>Target Month : May</p>'+
                    '<p>Boundary date : 5th May</p>'+
                    '<p>Expected return requested date : 20th May</p>'+
                    '<p> Date Selection : 20th May - 5th June</p>'+
                    '</div>').appendTo('#message');
            }else if(this.value=='time-sale'){
                $('#message').find('div').remove();
                $('<div class=" note note-success" style="margin-left: 44.5%;margin-top: 2%">' +
                    '<p>Select any month & consider it as <b>Target Month</b></p>' +
                    '<p>Consider 5th of Target Month as <b>boundary date</b></p>' +
                    '<br>'+
                    '<p>If expected order placed date lies before boundary date & in Target Month,then choose any date between order placed date to End of Target Month.</p>'+
                    '<p><b>eg: </b>Target Month : May</p>'+
                    '<p>Boundary date : 5th May</p>'+
                    '<p>Expected return requested date : 3rd May</p>'+
                    '<p> Date Selection : 3rd May - 31st May</p>'+
                    '<br>'+
                    '<p>If expected order placed date lies after boundary date & in Target Month,then choose any date between order placed date to 5th of month next to Target Month.</p>'+
                    '<p><b>eg: </b>Target Month : May</p>'+
                    '<p>Boundary date : 5th May</p>'+
                    '<p>Expected return requested date : 20th May</p>'+
                    '<p> Date Selection : 20th May - 5th June</p>'+
                    '</div>').appendTo('#message');
            }else if(this.value == 'settlement' || this.value == 'taxation'){
                $('#message1').find('div').remove();
                $('<div class=" note note-success" style="margin-left: 44.5%;margin-top: 2%">' +
                    '<p><b>Case1: No Return</b></p>'+
                    '<p>If the required product delivery date is 5th May. You can input From & To dates such as 5th May lies between the selected dates.</p>'+
                    '<p><b>Case 2: Return</b></p>'+
                    '<p>If the required product delivery date is 5th May with return placed and return completion date as 21st May. You can input From & To dates such that 5th May(Product delivery date) & 21st May(return completion date) should both lie between selected dates.</p>'+
                    '</div>').appendTo('#message1');
            }else if(this.value == 'logistic-accounting'){
                $('#message1').find('div').remove();
                $('<div class=" note note-success" style="margin-left: 44.5%;margin-top: 2%"><p>If the required Account confirmed date is 10th May .You can input From & To dates such as 10th May lies between the selected dates.</p></div>').appendTo('#message1');
            }else if(this.value == 'krishimitra-orders' || this.value == 'sales-orders'){
                $('#message1').find('div').remove();
            }
        })
    </script>
@endsection
