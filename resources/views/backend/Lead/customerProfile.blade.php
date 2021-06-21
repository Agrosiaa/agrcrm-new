@extends('backend.seller.layouts.master')
@section('title','Agrosiaa | Customer')
@include('backend.partials.common.nav')
@section('css')
    <link rel="stylesheet" type="text/css" href="/assets/frontend/global/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="/assets/frontend/global/css/mCustomScrollbar.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/frontend/global/css/styles/style.css">
    <link href="/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/jstree/dist/themes/default/style.css" rel="stylesheet" type="text/css" />

@endsection
@section('content')
    <!-- BEGIN CONTAINER -->
    <div class="page-content">
        <div class="container">
            <!-- BEGIN PAGE CONTENT INNER -->
            <div class="page-content-inner">
                <div class="row">
                    <div class="portlet">
                        <div class="portlet-body">
                            <div class="tabbable-bordered">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#farmer" data-toggle="tab" id="farmer_a"> Farmer </a>
                                    </li>
                                    <li>
                                        <a href="#gardener" data-toggle="tab" id="gardener_a"> Urban Gardener </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="farmer">
                                        <form class="form-horizontal form-row-seperated" action="#" method="POST">
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label"><h3>MY BASIC INFO</h3></label>
                                                    <div class="col-md-8 text-right">
                                                        <button class="btn base-color" type="submit">
                                                            <i class="fa fa-check-circle"></i> Save
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Farmer Name:
                                                        <span class="required"> * </span>
                                                    </label>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control" name="customer_name" placeholder=""  value="">
                                                    </div>
                                                </div>
                                                <!--<div class="form-group">
                                                    <label class="col-md-3 control-label">Full Address:
                                                        <span class="required"> * </span>
                                                    </label>
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" name="village" placeholder="village"  value="">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" name="taluka" placeholder="taluka"  value="">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" name="district" placeholder="district"  value="">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" name="state" placeholder="state"  value="">
                                                    </div>
                                                </div>-->
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Allied bussines/job:
                                                    </label>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control" name="allied_job" placeholder=""  value="">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Total Land Area (In Acres)
                                                    </label>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control" name="total_land" placeholder="Total land in Acres"  value="">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Preferred Communication Language
                                                    </label>
                                                    <div class="col-md-6">
                                                        <select class="form-control" name="communication_lang">
                                                            <option value="">Please select preferred communication language</option>
                                                            <option value="English">English</option>
                                                            <option value="Hindi">Hindi</option>
                                                            <option value="Marathi">Marathi</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Mother Tongue Language (If Any)
                                                    </label>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control" name="mother_tongue" placeholder=""  value="">
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label"><h3>MY FARM</h3></label>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Crop</label>
                                                    <div class="col-md-2">
                                                        <select class="form-control" name="crops[]">
                                                            <option value="">Please select crop</option>
                                                            @foreach($crops as $crop)
                                                            <option value="{{$crop['id']}}">{{$crop['name']}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="date" name="sowed_date[]" placeholder="Sowing date">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Cropping Pattern
                                                    </label>
                                                    <div class="col-md-6">
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input class="form-check-input radio-inline" type="radio" name="intercropping" value="Intercropping">
                                                                Intercropping</label>
                                                            <label class="form-check-label">
                                                                <input class="form-check-input radio-inline" type="radio" name="monocropping" value="Monocropping">
                                                                Monocropping</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Crops taken in previous/current season  and its Area
                                                    </label>
                                                    <div class="col-md-2">
                                                        <select class="form-control" name="crops_taken[]">
                                                            <option value="">Please select crop</option>
                                                            @foreach($crops as $crop)
                                                                <option value="{{$crop['id']}}">{{$crop['name']}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <select class="form-control" name="crops_taken_year[]">
                                                            <option value="">Select year</option>
                                                            <option value="2020">2020</option>
                                                            <option value="2019">2019</option>
                                                            <option value="2018">2018</option>

                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <select class="form-control" name="crops_taken_month[]">
                                                            <option value="">Select month</option>
                                                            <option value="1">January</option>
                                                            <option value="2">February</option>
                                                            <option value="3">March</option>
                                                            <option value="4">April</option>
                                                            <option value="5">May</option>
                                                            <option value="6">June</option>
                                                            <option value="7">July</option>
                                                            <option value="8">August</option>
                                                            <option value="9">September</option>
                                                            <option value="10">October</option>
                                                            <option value="11">November</option>
                                                            <option value="12">December</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="number" name="crop_taken_area[]" placeholder="Area">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Use Microirrigation
                                                    </label>
                                                    <div class="col-md-6">
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input class="form-check-input radio-inline" type="radio" name="use_microirrigation" value="1" checked>
                                                                Yes</label>
                                                            <label class="form-check-label">
                                                                <input class="form-check-input radio-inline" type="radio" name="use_microirrigation" value="1">
                                                                NO</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Pesticides used:
                                                    </label>
                                                    <div class="col-md-6">
                                                        <div class="row border">
                                                            <div class="bootstrap-tagsinput" id="customer-tag-div">
                                                                @foreach($customerTags as $customerTag)
                                                                    <button id="tag{{$customerTag['tag_cloud_id']}}{{$customerTag['crm_customer_id']}}" class="lable" style="background-color:rgb(241 243 244);display: inline;font-size: 90%;margin-left: 2px;margin-top:3px;margin-bottom:3px;padding-bottom: 2px;padding-top: 2px">{{$customerTag['name']}}&nbsp;<span style="color: red;" onclick="removeCustTag({{$customerTag['tag_cloud_id']}},{{$customerTag['crm_customer_id']}})">&nbsp;×</span></button>&nbsp;&nbsp;&nbsp;
                                                                @endforeach
                                                            </div>
                                                            <div class="logo-wrap">
                                                                <div class=container>
                                                                    <div class="menu clearfix">
                                                                        <ul class="clearfix">
                                                                            <li class="select-category select-pesticide" id="search_header_main">
                                                                                <input type="text" id="tag_name" class="typeahead" placeholder="Search Pesticide" style=""/>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Seeds and pesticide brands used:
                                                    </label>
                                                    <div class="col-md-6">
                                                        <div class="row border">
                                                            <div class="bootstrap-tagsinput" id="customer-tag-div">
                                                                @foreach($customerTags as $customerTag)
                                                                    <button id="tag{{$customerTag['tag_cloud_id']}}{{$customerTag['crm_customer_id']}}" class="lable" style="background-color:rgb(241 243 244);display: inline;font-size: 90%;margin-left: 2px;margin-top:3px;margin-bottom:3px;padding-bottom: 2px;padding-top: 2px">{{$customerTag['name']}}&nbsp;<span style="color: red;" onclick="removeCustTag({{$customerTag['tag_cloud_id']}},{{$customerTag['crm_customer_id']}})">&nbsp;×</span></button>&nbsp;&nbsp;&nbsp;
                                                                @endforeach
                                                            </div>
                                                            <div class="logo-wrap">
                                                                <div class=container>
                                                                    <div class="menu clearfix">
                                                                        <ul class="clearfix">
                                                                            <li class="select-category select-pesticide" id="search_header_main">
                                                                                <input type="text" id="tag_name" class="typeahead" placeholder="Search seeds and pesticide brands" style=""/>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Seeds Variety:
                                                    </label>
                                                    <div class="col-md-6">
                                                        <div class="row border">
                                                            <div class="bootstrap-tagsinput" id="customer-tag-div">
                                                                @foreach($customerTags as $customerTag)
                                                                    <button id="tag{{$customerTag['tag_cloud_id']}}{{$customerTag['crm_customer_id']}}" class="lable" style="background-color:rgb(241 243 244);display: inline;font-size: 90%;margin-left: 2px;margin-top:3px;margin-bottom:3px;padding-bottom: 2px;padding-top: 2px">{{$customerTag['name']}}&nbsp;<span style="color: red;" onclick="removeCustTag({{$customerTag['tag_cloud_id']}},{{$customerTag['crm_customer_id']}})">&nbsp;×</span></button>&nbsp;&nbsp;&nbsp;
                                                                @endforeach
                                                            </div>
                                                            <div class="logo-wrap">
                                                                <div class=container>
                                                                    <div class="menu clearfix">
                                                                        <ul class="clearfix">
                                                                            <li class="select-category select-pesticide" id="search_header_main">
                                                                                <input type="text" id="tag_name" class="typeahead" placeholder="Search seed variety" style=""/>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Tools used for farming:
                                                    </label>
                                                    <div class="col-md-6">
                                                        <div class="row border">
                                                            <div class="bootstrap-tagsinput" id="customer-tag-div">
                                                                @foreach($customerTags as $customerTag)
                                                                    <button id="tag{{$customerTag['tag_cloud_id']}}{{$customerTag['crm_customer_id']}}" class="lable" style="background-color:rgb(241 243 244);display: inline;font-size: 90%;margin-left: 2px;margin-top:3px;margin-bottom:3px;padding-bottom: 2px;padding-top: 2px">{{$customerTag['name']}}&nbsp;<span style="color: red;" onclick="removeCustTag({{$customerTag['tag_cloud_id']}},{{$customerTag['crm_customer_id']}})">&nbsp;×</span></button>&nbsp;&nbsp;&nbsp;
                                                                @endforeach
                                                            </div>
                                                            <div class="logo-wrap">
                                                                <div class=container>
                                                                    <div class="menu clearfix">
                                                                        <ul class="clearfix">
                                                                            <li class="select-category select-pesticide" id="search_header_main">
                                                                                <input type="text" id="tag_name" class="typeahead" placeholder="Search tools" style=""/>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Produce sold in which market
                                                    </label>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control" name="product_sold_market" placeholder=""  value="">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Product purchase from
                                                    </label>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control" name="product_name" placeholder=""  value="">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Income level
                                                    </label>
                                                    <div class="col-md-6">
                                                        <select class="form-control" name="communication_lang">
                                                            <option value="">Please select income level</option>
                                                            <option value="Low">Low</option>
                                                            <option value="Medium">Medium</option>
                                                            <option value="High">High</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-md-11 text-right">
                                                        <button class="btn base-color" type="submit">
                                                            <i class="fa fa-check-circle"></i> Save
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane" id="gardener">
                                        <form class="form-horizontal form-row-seperated" action="#" method="POST">
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label"><h3>MY BASIC INFO</h3></label>
                                                    <div class="col-md-8 text-right">
                                                        <button class="btn base-color" type="submit">
                                                            <i class="fa fa-check-circle"></i> Save
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Gardener Name:
                                                        <span class="required"> * </span>
                                                    </label>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control" name="product_name" placeholder=""  value="">
                                                    </div>
                                                </div>
                                                <!--<div class="form-group">
                                                    <label class="col-md-3 control-label">Full Address:
                                                        <span class="required"> * </span>
                                                    </label>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control" name="product_name" placeholder=""  value="">
                                                    </div>
                                                </div>-->
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Business/job:
                                                        <span class="required"> * </span>
                                                    </label>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control" name="product_name" placeholder=""  value="">
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label"><h3>MY Garden</h3></label>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Gardening type
                                                    </label>
                                                    <div class="col-md-6">
                                                        <select class="form-control" name="communication_lang">
                                                            <option value="">Please select gardening type</option>
                                                            <option value="terrace-gardening">Terrace gardening</option>
                                                            <option value="balcony-gardening">Balcony gardening</option>
                                                            <option value="backyard-gardening">Backyard gardening</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Plants used
                                                    </label>
                                                    <div class="col-md-6">
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input class="form-check-input radio-inline" type="radio" name="intercropping" value="Intercropping">
                                                                Indoor plants</label>
                                                            <label class="form-check-label">
                                                                <input class="form-check-input radio-inline" type="radio" name="monocropping" value="Monocropping">
                                                                Outdoor plants</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Indoor plants:
                                                    </label>
                                                    <div class="col-md-6">
                                                        <div class="row border">
                                                            <div class="bootstrap-tagsinput" id="customer-tag-div">
                                                                @foreach($customerTags as $customerTag)
                                                                <button id="tag{{$customerTag['tag_cloud_id']}}{{$customerTag['crm_customer_id']}}" class="lable" style="background-color:rgb(241 243 244);display: inline;font-size: 90%;margin-left: 2px;margin-top:3px;margin-bottom:3px;padding-bottom: 2px;padding-top: 2px">{{$customerTag['name']}}&nbsp;<span style="color: red;" onclick="removeCustTag({{$customerTag['tag_cloud_id']}},{{$customerTag['crm_customer_id']}})">&nbsp;×</span></button>&nbsp;&nbsp;&nbsp;
                                                                @endforeach
                                                            </div>
                                                            <div class="logo-wrap">
                                                                <div class=container>
                                                                    <div class="menu clearfix">
                                                                        <ul class="clearfix">
                                                                            <li class="select-category select-pesticide" id="search_header_main">
                                                                                <input type="text" id="tag_name" class="typeahead" placeholder="Search tools" style=""/>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Indoor plants:
                                                    </label>
                                                    <div class="col-md-6">
                                                        <div class="row border">
                                                            <div class="bootstrap-tagsinput" id="customer-tag-div">
                                                                @foreach($customerTags as $customerTag)
                                                                <button id="tag{{$customerTag['tag_cloud_id']}}{{$customerTag['crm_customer_id']}}" class="lable" style="background-color:rgb(241 243 244);display: inline;font-size: 90%;margin-left: 2px;margin-top:3px;margin-bottom:3px;padding-bottom: 2px;padding-top: 2px">{{$customerTag['name']}}&nbsp;<span style="color: red;" onclick="removeCustTag({{$customerTag['tag_cloud_id']}},{{$customerTag['crm_customer_id']}})">&nbsp;×</span></button>&nbsp;&nbsp;&nbsp;
                                                                @endforeach
                                                            </div>
                                                            <div class="logo-wrap">
                                                                <div class=container>
                                                                    <div class="menu clearfix">
                                                                        <ul class="clearfix">
                                                                            <li class="select-category select-pesticide" id="search_header_main">
                                                                                <input type="text" id="tag_name" class="typeahead" placeholder="Search tools" style=""/>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Outdoor plants:
                                                    </label>
                                                    <div class="col-md-6">
                                                        <div class="row border">
                                                            <div class="bootstrap-tagsinput" id="customer-tag-div">
                                                                @foreach($customerTags as $customerTag)
                                                                <button id="tag{{$customerTag['tag_cloud_id']}}{{$customerTag['crm_customer_id']}}" class="lable" style="background-color:rgb(241 243 244);display: inline;font-size: 90%;margin-left: 2px;margin-top:3px;margin-bottom:3px;padding-bottom: 2px;padding-top: 2px">{{$customerTag['name']}}&nbsp;<span style="color: red;" onclick="removeCustTag({{$customerTag['tag_cloud_id']}},{{$customerTag['crm_customer_id']}})">&nbsp;×</span></button>&nbsp;&nbsp;&nbsp;
                                                                @endforeach
                                                            </div>
                                                            <div class="logo-wrap">
                                                                <div class=container>
                                                                    <div class="menu clearfix">
                                                                        <ul class="clearfix">
                                                                            <li class="select-category select-pesticide" id="search_header_main">
                                                                                <input type="text" id="tag_name" class="typeahead" placeholder="Search tools" style=""/>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Plants grown in garden:
                                                    </label>
                                                    <div class="col-md-6">
                                                        <div class="row border">
                                                            <div class="bootstrap-tagsinput" id="customer-tag-div">
                                                                @foreach($customerTags as $customerTag)
                                                                <button id="tag{{$customerTag['tag_cloud_id']}}{{$customerTag['crm_customer_id']}}" class="lable" style="background-color:rgb(241 243 244);display: inline;font-size: 90%;margin-left: 2px;margin-top:3px;margin-bottom:3px;padding-bottom: 2px;padding-top: 2px">{{$customerTag['name']}}&nbsp;<span style="color: red;" onclick="removeCustTag({{$customerTag['tag_cloud_id']}},{{$customerTag['crm_customer_id']}})">&nbsp;×</span></button>&nbsp;&nbsp;&nbsp;
                                                                @endforeach
                                                            </div>
                                                            <div class="logo-wrap">
                                                                <div class=container>
                                                                    <div class="menu clearfix">
                                                                        <ul class="clearfix">
                                                                            <li class="select-category select-pesticide" id="search_header_main">
                                                                                <input type="text" id="tag_name" class="typeahead" placeholder="Search tools" style=""/>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Flower/Ornamental
                                                    </label>
                                                    <div class="col-md-6">
                                                        <div class="row border">
                                                            <div class="bootstrap-tagsinput" id="customer-tag-div">
                                                                @foreach($customerTags as $customerTag)
                                                                <button id="tag{{$customerTag['tag_cloud_id']}}{{$customerTag['crm_customer_id']}}" class="lable" style="background-color:rgb(241 243 244);display: inline;font-size: 90%;margin-left: 2px;margin-top:3px;margin-bottom:3px;padding-bottom: 2px;padding-top: 2px">{{$customerTag['name']}}&nbsp;<span style="color: red;" onclick="removeCustTag({{$customerTag['tag_cloud_id']}},{{$customerTag['crm_customer_id']}})">&nbsp;×</span></button>&nbsp;&nbsp;&nbsp;
                                                                @endforeach
                                                            </div>
                                                            <div class="logo-wrap">
                                                                <div class=container>
                                                                    <div class="menu clearfix">
                                                                        <ul class="clearfix">
                                                                            <li class="select-category select-pesticide" id="search_header_main">
                                                                                <input type="text" id="tag_name" class="typeahead" placeholder="Search tools" style=""/>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Vegetable:
                                                    </label>
                                                    <div class="col-md-6">
                                                        <div class="row border">
                                                            <div class="bootstrap-tagsinput" id="customer-tag-div">
                                                                @foreach($customerTags as $customerTag)
                                                                <button id="tag{{$customerTag['tag_cloud_id']}}{{$customerTag['crm_customer_id']}}" class="lable" style="background-color:rgb(241 243 244);display: inline;font-size: 90%;margin-left: 2px;margin-top:3px;margin-bottom:3px;padding-bottom: 2px;padding-top: 2px">{{$customerTag['name']}}&nbsp;<span style="color: red;" onclick="removeCustTag({{$customerTag['tag_cloud_id']}},{{$customerTag['crm_customer_id']}})">&nbsp;×</span></button>&nbsp;&nbsp;&nbsp;
                                                                @endforeach
                                                            </div>
                                                            <div class="logo-wrap">
                                                                <div class=container>
                                                                    <div class="menu clearfix">
                                                                        <ul class="clearfix">
                                                                            <li class="select-category select-pesticide" id="search_header_main">
                                                                                <input type="text" id="tag_name" class="typeahead" placeholder="Search tools" style=""/>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Fruit:
                                                    </label>
                                                    <div class="col-md-6">
                                                        <div class="row border">
                                                            <div class="bootstrap-tagsinput" id="customer-tag-div">
                                                                @foreach($customerTags as $customerTag)
                                                                <button id="tag{{$customerTag['tag_cloud_id']}}{{$customerTag['crm_customer_id']}}" class="lable" style="background-color:rgb(241 243 244);display: inline;font-size: 90%;margin-left: 2px;margin-top:3px;margin-bottom:3px;padding-bottom: 2px;padding-top: 2px">{{$customerTag['name']}}&nbsp;<span style="color: red;" onclick="removeCustTag({{$customerTag['tag_cloud_id']}},{{$customerTag['crm_customer_id']}})">&nbsp;×</span></button>&nbsp;&nbsp;&nbsp;
                                                                @endforeach
                                                            </div>
                                                            <div class="logo-wrap">
                                                                <div class=container>
                                                                    <div class="menu clearfix">
                                                                        <ul class="clearfix">
                                                                            <li class="select-category select-pesticide" id="search_header_main">
                                                                                <input type="text" id="tag_name" class="typeahead" placeholder="Search tools" style=""/>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Medicinal
                                                    </label>
                                                    <div class="col-md-6">
                                                        <div class="row border">
                                                            <div class="bootstrap-tagsinput" id="customer-tag-div">
                                                                @foreach($customerTags as $customerTag)
                                                                <button id="tag{{$customerTag['tag_cloud_id']}}{{$customerTag['crm_customer_id']}}" class="lable" style="background-color:rgb(241 243 244);display: inline;font-size: 90%;margin-left: 2px;margin-top:3px;margin-bottom:3px;padding-bottom: 2px;padding-top: 2px">{{$customerTag['name']}}&nbsp;<span style="color: red;" onclick="removeCustTag({{$customerTag['tag_cloud_id']}},{{$customerTag['crm_customer_id']}})">&nbsp;×</span></button>&nbsp;&nbsp;&nbsp;
                                                                @endforeach
                                                            </div>
                                                            <div class="logo-wrap">
                                                                <div class=container>
                                                                    <div class="menu clearfix">
                                                                        <ul class="clearfix">
                                                                            <li class="select-category select-pesticide" id="search_header_main">
                                                                                <input type="text" id="tag_name" class="typeahead" placeholder="Search tools" style=""/>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Tools used for gardening
                                                    </label>
                                                    <div class="col-md-6">
                                                        <div class="row border">
                                                            <div class="bootstrap-tagsinput" id="customer-tag-div">
                                                                @foreach($customerTags as $customerTag)
                                                                <button id="tag{{$customerTag['tag_cloud_id']}}{{$customerTag['crm_customer_id']}}" class="lable" style="background-color:rgb(241 243 244);display: inline;font-size: 90%;margin-left: 2px;margin-top:3px;margin-bottom:3px;padding-bottom: 2px;padding-top: 2px">{{$customerTag['name']}}&nbsp;<span style="color: red;" onclick="removeCustTag({{$customerTag['tag_cloud_id']}},{{$customerTag['crm_customer_id']}})">&nbsp;×</span></button>&nbsp;&nbsp;&nbsp;
                                                                @endforeach
                                                            </div>
                                                            <div class="logo-wrap">
                                                                <div class=container>
                                                                    <div class="menu clearfix">
                                                                        <ul class="clearfix">
                                                                            <li class="select-category select-pesticide" id="search_header_main">
                                                                                <input type="text" id="tag_name" class="typeahead" placeholder="Search tools" style=""/>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">How do you manage fertilizer/ disease /pests of plants
                                                    </label>
                                                    <div class="col-md-6">
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input class="form-check-input radio-inline" type="radio" name="intercropping" value="Intercropping">
                                                                Chemical</label>
                                                            <label class="form-check-label">
                                                                <input class="form-check-input radio-inline" type="radio" name="monocropping" value="Monocropping">
                                                                Organic</label>
                                                            <label class="form-check-label">
                                                                <input class="form-check-input radio-inline" type="radio" name="monocropping" value="Monocropping">
                                                                Both</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Plants /Seeds / Fertilizers purchase from
                                                    </label>
                                                    <div class="col-md-6">
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input class="form-check-input radio-inline" type="radio" name="intercropping" value="Intercropping">
                                                                Local</label>
                                                            <label class="form-check-label">
                                                                <input class="form-check-input radio-inline" type="radio" name="monocropping" value="Monocropping">
                                                                Online</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">How you watering your plants
                                                    </label>
                                                    <div class="col-md-6">
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input class="form-check-input radio-inline" type="radio" name="intercropping" value="Intercropping">
                                                                Drip</label>
                                                            <label class="form-check-label">
                                                                <input class="form-check-input radio-inline" type="radio" name="monocropping" value="Monocropping">
                                                                Sprinkler</label>
                                                            <label class="form-check-label">
                                                                <input class="form-check-input radio-inline" type="radio" name="monocropping" value="Monocropping">
                                                                Water can/Bucket</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-md-11 text-right">
                                                        <button class="btn base-color" type="submit">
                                                            <i class="fa fa-check-circle"></i> Save
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- END CONTENT BODY -->
    </div>
    <!-- END CONTENT -->
    <!-- END CONTAINER -->
@endsection
@section('javascript')
    <!-- BEGIN CORE PLUGINS -->
    <script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
    <!-- END CORE PLUGINS -->
    <!-- BEGIN THEME GLOBAL SCRIPTS -->
    <script src="/assets/global/scripts/app.min.js" type="text/javascript"></script>
    <!-- END THEME GLOBAL SCRIPTS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script type="text/javascript" src="/assets/frontend/custom/registration/js/typeahead.bundle.js"></script>
    <script type="text/javascript" src="/assets/frontend/custom/registration/js/handlebars-v3.0.3.js"></script>

        <!-- END PAGE LEVEL SCRIPTS -->
    <!-- BEGIN THEME LAYOUT SCRIPTS -->
    <script src="/assets/layouts/layout3/scripts/demo.min.js" type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            $('#tag_name').on('select',function () {
                $('#tag_name').val('');
            });
            var crmCustId = $('#crm_customer_id').val();
            var tagList = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.obj.whitespace('office_name'),
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                remote: {
                    url: "/get-tags?tag_name=%QUERY",
                    filter: function(x) {
                        return $.map(x, function (data) {
                            return {
                                id: data.id,
                                name: data.name
                            };
                        });
                    },
                    wildcard: "%QUERY"
                }
            });
            var language = $('#language').val();
            tagList.initialize();
            $('#tag_name').typeahead(null, {
                displayKey: 'name',
                engine: Handlebars,
                source: tagList.ttAdapter(),
                limit: 30,
                templates: {
                    empty: [
                        '<div class="empty-message">',
                        'Unable to find any Result that match the current query',
                        '</div>'
                    ].join('\n'),
                    suggestion: Handlebars.compile('<div style="text-transform: capitalize;"><strong>@{{name}}</strong></div>')
                }
            }).on('typeahead:selected', function (obj, datum) {
                var POData = new Array();
                POData = $.parseJSON(JSON.stringify(datum));
                POData.name = POData.name.replace(/\&/g,'%26');
                str = '<button id="tag'+POData.id+crmCustId+'" class="lable" style="display: inline;font-size: 90%;margin-left: 2px;margin-top:3px;margin-bottom:3px;padding-bottom: 2px;padding-top: 2px">'+POData.name+'<span style="color: red;" onclick="removeCustTag('+POData.id+','+crmCustId+')"> ×</span></button>&nbsp;&nbsp;&nbsp';
                $('#customer-tag-div').append(str);
                if(crmCustId != 'null'){
                    $.ajax({
                        url: '/tag/customer-tag',
                        type: 'POST',
                        dataType: 'array',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            'crm_cust_id' : crmCustId,
                            'tag_id' : POData.id
                        },
                        success: function (responce) {
                        },
                        error: function (responce) {
                        }
                    })
                }
            }).on('typeahead:open', function (obj, datum) {

            });
            $('#tag_name').keypress(function (e) {
                var key = e.which;
                if(key == '13'){
                    var singleQuote = "'";
                    var tagName = singleQuote+$('#tag_name').val()+singleQuote;
                    var tag = $('#tag_name').val().replace(/ /g,"_");
                    var tagStr = '<button id="tag'+tag+crmCustId+'" class="lable" style="display: inline;font-size: 90%;margin-left: 2px;margin-top:3px;margin-bottom:3px;padding-bottom: 2px;padding-top: 2px">'+tag+'<span style="color: red;" onclick="removeCustTag('+tagName+','+crmCustId+')"> ×</span></button>&nbsp;&nbsp;&nbsp';
                    $('#customer-tag-div').append(tagStr);
                    $.ajax({
                        url: '/customer/create-assign-tag',
                        type: 'POST',
                        dataType: 'array',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            'tag_name' : $('#tag_name').val(),
                            'customer_id' : crmCustId
                        },
                        success: function (status) {
                        },
                        error: function (status) {
                        }
                    })
                }
            });
        });
    </script>
@endsection