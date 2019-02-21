@if(Route::current()->domain()== env('DOMAIN_NAME'))
<?php $extendMaster = 'frontend.layouts.master';?>
@else
<?php $extendMaster = 'backend.seller.layouts.master';?>
@endif
@extends($extendMaster)
@section('css')
<!-- BEGIN PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link href="/assets/pages/css/error.min.css" rel="stylesheet" type="text/css" />
<!-- END PAGE LEVEL STYLES -->
@endsection
@section('content')
<!-- BEGIN PAGE CONTENT BODY -->
<div class="page-content">
    <div class="container">
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12 page-500">
                    <div class=" number font-green"> 404 </div>
                    <div class=" details">
                        <h3>Oops! You're lost.</h3>
                        <p> We can not find the page you're looking for.
                            <br/> </p>
                        <p>
                            <a href="/home" class="btn green btn-outline"> Return home </a>
                            <br> </p>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>
</div>
<!-- END PAGE CONTENT BODY -->
@endsection