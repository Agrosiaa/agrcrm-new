@extends('backend.seller.layouts.master')
@section('title','Agrosiaa | Leads')
@section('css')
    <style>
    </style>
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href='https://fonts.googleapis.com/css?family=Raleway:100,700,800' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="/assets/custom/crm/fonts/font-awesome-4.2.0/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="/assets/custom/crm/css/normalize.css" />
    <link rel="stylesheet" type="text/css" href="/assets/custom/crm/css/demo.css" />
    <link rel="stylesheet" type="text/css" href="/assets/custom/crm/css/component.css" />
    <link rel="stylesheet" type="text/css" href="/assets/frontend/global/css/bootstrap.css">
    <!--[if IE]>
    <!-- END PAGE LEVEL PLUGINS -->
    <script type="text/css">
    </script>
@endsection
@section('content')
    <!-- BEGIN PAGE CONTENT BODY -->
    <!-- BEGIN PAGE CONTENT BODY -->
    <div>
        <div class="container">
            <!-- BEGIN PAGE CONTENT INNER -->
            <div class="page-content-inner">
                <div class="row">
                    @include('backend.partials.error-messages')
                    <div class="col-md-8 col-md-offset-2">
                        <!-- Begin: life time stats -->
                        <input type="hidden" id="user_role" value="{{$role}}">
                    </div>
                    <div id="morphsearch" class="morphsearch">
                        <form class="morphsearch-form">
                            <input class="morphsearch-input typeahead" id="customer_data" type="search" placeholder="Search..." style="width: 100%;padding: 14px 40px;display: inline-block;border: 1px solid #ccc;border-radius: 4px;box-sizing: border-box;"/>
                            <button class="morphsearch-submit" type="submit">Search</button>
                        </form>
                        <div class="morphsearch-content">
                        </div><!-- /morphsearch-content -->
                        <span class="morphsearch-close"></span>
                    </div><!-- /morphsearch -->
                    <header class="codrops-header">
                        <h1>CRM Search</h1>
                    </header>
                    <div class="overlay"></div>
                    <!-- End: life time stats -->
                    @if($role == 'sales_employee')
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
                                        {{ csrf_field()  }}
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
                                                    <button type="submit" id="create_customer" class="btn btn-sm btn-success">Create</button>
                                                    <button class="btn btn-sm btn-danger pull-right" data-dismiss="modal">Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
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
    <!-- BEGIN THEME GLOBAL SCRIPTS -->
    <script src="/assets/global/scripts/app.min.js" type="text/javascript"></script>
    <!-- END THEME GLOBAL SCRIPTS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script type="text/javascript" src="/assets/frontend/custom/registration/js/typeahead.bundle.js"></script>
    <script type="text/javascript" src="/assets/frontend/custom/registration/js/handlebars-v3.0.3.js"></script>
    <script src="/assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>

    <!-- END PAGE LEVEL SCRIPTS -->
    <!-- BEGIN THEME LAYOUT SCRIPTS -->
    <script src="/assets/layouts/layout3/scripts/layout.min.js" type="text/javascript"></script>
    <script src="/assets/layouts/layout3/scripts/demo.min.js" type="text/javascript"></script>

    <!-- END THEME LAYOUT SCRIPTS -->
    <script src="/assets/custom/crm/js/classie.js"></script>
    <script>
        (function() {
            var morphSearch = document.getElementById( 'morphsearch' ),
                input = morphSearch.querySelector( 'input.morphsearch-input' ),
                ctrlClose = morphSearch.querySelector( 'span.morphsearch-close' ),
                ctrlCloseButton = morphSearch.querySelector( 'button.morphsearch-submit' ),
                isOpen = isAnimating = false,
                // show/hide search area
                toggleSearch = function(evt) {
                    // return if open and the input gets focused
                    if( evt.type.toLowerCase() === 'focus' && isOpen ) return false;

                    var offsets = morphsearch.getBoundingClientRect();
                    if( isOpen ) {
                        classie.remove( morphSearch, 'open' );

                        // trick to hide input text once the search overlay closes
                        // todo: hardcoded times, should be done after transition ends
                        if( input.value !== '' ) {
                            setTimeout(function() {
                                classie.add( morphSearch, 'hideInput' );
                                setTimeout(function() {
                                    classie.remove( morphSearch, 'hideInput' );
                                    input.value = '';
                                }, 300 );
                            }, 500);
                        }

                        input.blur();
                    }
                    else {
                        classie.add( morphSearch, 'open' );
                    }
                    isOpen = !isOpen;
                };

            // events
            input.addEventListener( 'focus', toggleSearch );
            ctrlClose.addEventListener( 'click', toggleSearch );
            // esc key closes search overlay
            // keyboard navigation events
            document.addEventListener( 'keydown', function( ev ) {
                var keyCode = ev.keyCode || ev.which;
                if( keyCode === 27 && isOpen ) {
                    toggleSearch(ev);
                }
            } );


            /***** for demo purposes only: don't allow to submit the form *****/
            morphSearch.querySelector( 'button[type="submit"]' ).addEventListener( 'click', function(ev) { ev.preventDefault();
            var mobile = $('#customer_data').val();
            var role = $('#user_role').val();
                $.ajax({
                    url: '/customer/customer-details/'+mobile+'/null',
                    type: 'get',
                    dataType: 'array',
                    data: {
                        'is_crm_search': true
                    },
                    success: function (responce) {
                        if(responce['responseText'] == 'true'){
                            window.location.href = '/customer/customer-details/'+mobile+'/null'
                        }else {
                            if(mobile.length == 10){
                                if(role == 'sales_employee'){
                                    createCustomer(mobile);
                                    classie.remove( morphSearch, 'open' );
                                }
                            }else {
                                alert('Please enter valid 10 digit number')
                            }
                        }
                    },
                    error: function (responce) {
                        if(responce['responseText'] == 'true'){
                            window.location.href = '/customer/customer-details/'+mobile+'/null'
                        }else {
                            if(mobile.length == 10){
                                createCustomer(mobile);
                                classie.remove( morphSearch, 'open' );
                            }else {
                                alert('Please enter valid 10 digit number')
                            }
                        }
                    }
                })
            } );
        })();
    </script>
    <script>
        $(document).ready(function () {
            var customerList = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.obj.whitespace('office_name'),
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                remote: {
                    url: "{{env('BASE_URL')}}/get-customers?customer_data=%QUERY",
                    filter: function(x) {
                        return $.map(x, function (data) {
                            return {
                                id: data.id,
                                fname: data.fname,
                                lname: data.lname,
                                btn_class: data.class,
                                url_param: data.url_param,
                                mobile:data.mobile,
                                email:data.email
                            };
                        });
                    },
                    wildcard: "%QUERY"
                }
            });
            var language = $('#language').val();
            customerList.initialize();
            $('#customer_data').typeahead(null, {
                displayKey: 'name',
                engine: Handlebars,
                source: customerList.ttAdapter(),
                limit: 30,
                templates: {
                    empty: [
                        '<div class="empty-message">',
                        'Unable to find any Result that match the current query',
                        '</div>'
                    ].join('\n'),
                    suggestion: Handlebars.compile('<h1><a class="default" href="/customer/customer-details/@{{ mobile }}/@{{ url_param }}"><div style="text-transform: capitalize;"><strong>@{{fname}}</strong>&nbsp<strong>@{{lname}}</strong>&nbsp<span class="@{{btn_class}}">@{{email}}</span>&nbsp@{{ mobile }}</div></a></h1>')
                }
            }).on('typeahead:selected', function (obj, datum) {
                var POData = new Array();
                POData = $.parseJSON(JSON.stringify(datum));
            }).on('typeahead:open', function (obj, datum) {

            });
        });

        function createCustomer(mobile) {
            $('#create-customer-modal').modal('show');
            $('#cust_mobile_number').val(mobile);
        }
    </script>
    <script>
        $(document).on("click","#create_customer",function (e) {
            e.stopPropagation();
            if($('#fname').val() != '' && $('#lname').val() != '' && $('#cust_mobile_number').val() != ''){
                $.ajax({
                    url: "/customer/create-customer",
                    type: 'POST',
                    async: false,
                    dataType: 'array',
                    data: $('#create-customer-form').serialize(),
                    success: function (status) {
                        //window.location.href= "/customer/customer-details/"+mobile+"/null";
                    },
                    error: function (status) {
                       // window.location.href = "/customer/customer-details/"+mobile+"/null";
                    }
                })
            }
        });
    </script>
@endsection
