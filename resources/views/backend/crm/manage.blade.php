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
    <link rel="stylesheet" type="text/css" href="/assets/frontend/global/css/mCustomScrollbar.min.css">
    {{--<link rel="stylesheet" type="text/css" href="/assets/frontend/global/css/styles/style.css">
    --}}<link href="/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/jstree/dist/themes/default/style.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
    <script src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
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
                        {{--<div class="logo-wrap">
                            <div class=container>
                                <div class="menu clearfix">
                                    <ul class="clearfix">
                                        <li class="select-category" id="search_header_main">
                                            <input type="text" id="customer_data" class="typeahead" placeholder="Enter Customers mobile/name" style=""/>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>--}}
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
                        <h1>CRM Search<span>A search input that morphs into a fullscreen search page.</span></h1>
                    </header>
                    <div class="overlay"></div>
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
                $.ajax({
                    url: '/crm/customer-details/'+mobile+'/null',
                    type: 'get',
                    dataType: 'array',
                    data: {
                        'is_crm_search': true
                    },
                    success: function (responce) {
                        if(responce['responseText'] == 'true'){
                            window.location.href = '/crm/customer-details/'+mobile+'/null'
                        }else {
                            alert('There is no user register with this number');
                        }
                    },
                    error: function (responce) {
                        if(responce['responseText'] == 'true'){
                            window.location.href = '/crm/customer-details/'+mobile+'/null'
                        }else {
                            alert('There is no user register with this number');
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
                    suggestion: Handlebars.compile('<h1><a class="default" href="/crm/customer-details/@{{ mobile }}/@{{ url_param }}"><div style="text-transform: capitalize;"><strong>@{{fname}}</strong>&nbsp<strong>@{{lname}}</strong>&nbsp<span class="@{{btn_class}}">@{{email}}</span>&nbsp@{{ mobile }}</div></a></h1>')
                }
            }).on('typeahead:selected', function (obj, datum) {
                var POData = new Array();
                POData = $.parseJSON(JSON.stringify(datum));
            }).on('typeahead:open', function (obj, datum) {

            });
        });

    </script>
@endsection
