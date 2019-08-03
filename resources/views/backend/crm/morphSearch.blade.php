<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <title>Morphing Search Input</title>
    <meta name="description" content="A search input that morphs into a fullscreen search page." />
    <meta name="keywords" content="search, input, effect, morph, transition, inspiration" />
    <meta name="author" content="Codrops" />
    <link rel="shortcut icon" href="../favicon.ico">
    <link href='https://fonts.googleapis.com/css?family=Raleway:100,700,800' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.2.0/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="/assets/custom/crm/css/normalize.css" />
    <link rel="stylesheet" type="text/css" href="/assets/custom/crm/css/demo.css" />
    <link rel="stylesheet" type="text/css" href="/assets/custom/crm/css/component.css" />
    <!--[if IE]>
    <script src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body>
<div class="container">
    <aside class="sidebar clearfix">
        <nav>
            <a href="#"><i class="fa fa-fw fa-comments-o"></i></a>
            <a href="#"><i class="fa fa-fw fa-heart-o"></i></a>
            <a href="#"><i class="fa fa-fw fa-send-o"></i></a>
            <a href="#"><i class="fa fa-fw fa-smile-o"></i></a>
        </nav>
    </aside>
    <div id="morphsearch" class="morphsearch">
        <form class="morphsearch-form">
            <input class="morphsearch-input typeahead" id="customer_data" type="search" placeholder="Search..."/>
            <button class="morphsearch-submit" type="submit">Search</button>
        </form>
        <div class="morphsearch-content">
        </div><!-- /morphsearch-content -->
        <span class="morphsearch-close"></span>
    </div><!-- /morphsearch -->
    <header class="codrops-header">
        <h1>Morphing Search <span>A search input that morphs into a fullscreen search page.</span></h1>
    </header>
    <div class="overlay"></div>
</div><!-- /container -->
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
        morphSearch.querySelector( 'button[type="submit"]' ).addEventListener( 'click', function(ev) { ev.preventDefault(); } );
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
                suggestion: Handlebars.compile('<a class="default" href="/leads/customer-details/@{{ mobile }}/@{{ url_param }}"><div style="text-transform: capitalize;"><strong>@{{fname}}</strong>&nbsp<strong>@{{lname}}</strong>&nbsp<span class="@{{btn_class}}">@{{email}}</span>&nbsp@{{ mobile }}</div></a>')
            }
        }).on('typeahead:selected', function (obj, datum) {
            var POData = new Array();
            POData = $.parseJSON(JSON.stringify(datum));
        }).on('typeahead:open', function (obj, datum) {

        });
    });

</script>
</body>
</html>