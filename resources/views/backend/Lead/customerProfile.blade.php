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
                    @include('backend.partials.error-messages')
                    <meta name="csrf-token" content="{{ csrf_token() }}">
                    <input type="hidden" id="crm_customer_id" value="{{$id}}">
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
                                            <ul class="nav nav-tabs nav-tabs-lg">
                                                <li class="active">
                                                    <a href="#tab_1" data-toggle="tab"> Basic Info </a>
                                                </li>
                                                <li class="">
                                                    <a href="#tab_2" data-toggle="tab"> My Farm </a>
                                                </li>
                                                <li class="">
                                                    <a href="#tab_3" data-toggle="tab"> Spray History </a>
                                                </li>
                                                @if($profileData)
                                                <li class="text-right" style="padding-left: 32%;">
                                                    <select class="form-control crops_sowed_selection">
                                                            <option value="">Select sowed crop</option>
                                                            @foreach($profileData->CropsSowed as $cropSowed)
                                                            <option data-crop_date="{{$cropSowed->crop}} ({{date('d-m-Y', strtotime($cropSowed->sowed_date))}})" data-crop_sowed_id="{{$cropSowed->id}}">
                                                                {{$cropSowed->crop}} ({{date('d-m-Y', strtotime($cropSowed->sowed_date))}})
                                                            </option>
                                                            @endforeach
                                                    </select>
                                                </li>
                                                @endif
                                            </ul>
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="tab_1">
                                                    <form class="form-horizontal form-row-seperated" action="/customer/customer-profile" method="POST">
                                                        {{ csrf_field() }}
                                                        <input type="hidden" name="mobile" value="{{$mobile}}">
                                                        <div class="form-body">
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Farmer Name:
                                                                    <span class="required"> * </span>
                                                                </label>
                                                                <div class="col-md-6">
                                                                    <input type="text" class="form-control" name="full_name" placeholder="" @if($profileData) value="{{$profileData['full_name']}}" @endif>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Allied bussines/job:
                                                                </label>
                                                                <div class="col-md-6">
                                                                    <input type="text" class="form-control" name="allied_job" placeholder=""  @if($profileData) value="{{$profileData['allied_job']}}" @endif >
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Total Land Area (In Acres)
                                                                </label>
                                                                <div class="col-md-6">
                                                                    <input type="text" class="form-control" name="total_land" placeholder="Total land in Acres"  @if($profileData) value="{{$profileData['total_land']}}" @endif>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Preferred Communication Language
                                                                </label>
                                                                <div class="col-md-6">
                                                                    <select class="form-control" name="communication_lang">
                                                                        <option value="">Please select preferred communication language</option>
                                                                        <option value="English" @if($profileData && $profileData['communication_lang'] == 'English')  selected @endif>English</option>
                                                                        <option value="Hindi" @if($profileData && $profileData['communication_lang'] == 'Hindi') selected @endif>Hindi</option>
                                                                        <option value="Marathi" @if($profileData && $profileData['communication_lang'] == 'Marathi') selected @endif>Marathi</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Mother Tongue Language (If Any)
                                                                </label>
                                                                <div class="col-md-6">
                                                                    <input type="text" class="form-control" name="mother_tongue" placeholder="" @if($profileData) value="{{$profileData['mother_tongue']}}" @endif>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="text-right">
                                                                    <button class="btn base-color" type="submit">
                                                                        <i class="fa fa-check-circle"></i> Save
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="tab-pane" id="tab_2">
                                                <form class="form-horizontal form-row-seperated" action="/customer/customer-profile" method="POST">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="mobile" value="{{$mobile}}">
                                                <div class="form-body">
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-3 text-right">
                                                                <label class="control-label">Crop:</label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                @if($profileData)
                                                                @foreach($profileData->CropsSowed as $cropSowed)
                                                                <input type="hidden" name="cropSowedIds[]" value="{{$cropSowed->id}}">
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        @if($user->role->slug == 'sales_employee')
                                                                        <h5>{{$cropSowed->crop}}</h5>
                                                                        @else
                                                                        <select class="form-control" id="crops-select" name="crops[]">
                                                                            <option value="">Select crop</option>
                                                                            @foreach($crops as $crop)
                                                                            @if($crop['id'] == $cropSowed->crop_tag_cloud_id)
                                                                            <option value="{{$crop['id']}}" selected>{{$crop['name']}}</option>
                                                                            @else
                                                                            <option value="{{$crop['id']}}">{{$crop['name']}}</option>
                                                                            @endif
                                                                            @endforeach
                                                                        </select>
                                                                        @endif
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="row">
                                                                            <div class="col-md-3">
                                                                                <label class="control-label">Sowing:</label>
                                                                            </div>
                                                                            @if($user->role->slug == 'sales_employee')
                                                                            <h5>{{date('d-m-Y', strtotime($cropSowed->sowed_date))}}</h5>
                                                                            @else
                                                                            <div class="col-md-6">
                                                                                <input type="date" value="{{$cropSowed->sowed_date}}" name="sowed_date[]" style="margin-top: 4px;" placeholder="Sowing date">
                                                                            </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="row">
                                                                            <div class="col-md-3">
                                                                                <label class="control-label">Pattern:</label>
                                                                            </div>
                                                                            @if($user->role->slug == 'sales_employee')
                                                                            <h5>{{$cropSowed->cropping_pattern}}</h5>
                                                                            @else
                                                                            <div class="col-md-8">
                                                                                <select class="form-control" name="cropping_pattern[]">
                                                                                    <option value="">Select pattern</option>
                                                                                    <option value="Intercropping" @if($cropSowed->cropping_pattern == 'Intercropping') selected @endif>Intercropping</option>
                                                                                    <option value="Monocropping" @if($cropSowed->cropping_pattern == 'Monocropping') selected @endif>Monocropping</option>
                                                                                </select>
                                                                            </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @endforeach
                                                                <br>
                                                                @endif
                                                                <div class="row">
                                                                    <div class="col-md-2">
                                                                        <select class="form-control" id="crops-select" name="crops[]">
                                                                            <option value="">Select crop</option>
                                                                            @foreach($crops as $crop)
                                                                            <option value="{{$crop['id']}}">{{$crop['name']}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="row">
                                                                            <div class="col-md-3">
                                                                                <label class="control-label">Sowing:</label>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <input type="date" name="sowed_date[]" style="margin-top: 4px;" placeholder="Sowing date">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="row">
                                                                            <div class="col-md-3">
                                                                                <label class="control-label">Pattern:</label>
                                                                            </div>
                                                                            <div class="col-md-8">
                                                                                <select class="form-control" name="cropping_pattern[]">
                                                                                    <option value="">Select pattern</option>
                                                                                    <option value="Intercropping">Intercropping</option>
                                                                                    <option value="Monocropping">Monocropping</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-1">
                                                                        <a href="javascript:;" class="btn btn-sm green add-crop-sowed"> Add
                                                                            <i class="fa fa-plus"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label">Use Microirrigation
                                                        </label>
                                                        <div class="col-md-6">
                                                            <div class="form-check" style="margin-top: 7px;">
                                                                <label class="form-check-label">
                                                                    <input class="form-check-input radio-inline" type="radio" name="use_microirrigation" value="1"
                                                                    @if($profileData && $profileData['use_microirrigation'] == '1') checked @endif>
                                                                    Yes</label>
                                                                <label class="form-check-label">
                                                                    <input class="form-check-input radio-inline" type="radio" name="use_microirrigation" value="0"
                                                                    @if($profileData && $profileData['use_microirrigation'] == '0') checked @endif>
                                                                    NO</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label">Pesticides used:
                                                        </label>
                                                        <div class="col-md-8">
                                                            <div class="row border border-dark">
                                                                <div class="bootstrap-tagsinput" id="farm_pesticide_div">
                                                                    @foreach($pesticideTags as $customerTag)
                                                                    <button id="tag{{$customerTag['tag_cloud_id']}}{{$customerTag['crm_customer_id']}}" class="lable" style="background-color:rgb(241 243 244);display: inline;font-size: 90%;margin-left: 2px;margin-top:3px;margin-bottom:3px;padding-bottom: 2px;padding-top: 2px">{{$customerTag['name']}}
                                                                        @if($user->role->slug == 'admin')
                                                                        &nbsp;<span style="color: red;" onclick="removeCustTag({{$customerTag['tag_cloud_id']}},{{$customerTag['crm_customer_id']}})">&nbsp;×</span>
                                                                        @endIf
                                                                    </button>&nbsp;&nbsp;&nbsp;
                                                                    @endforeach
                                                                </div>
                                                                <div class="logo-wrap">
                                                                    <div class=container>
                                                                        <div class="menu clearfix">
                                                                            <ul class="clearfix">
                                                                                <li class="select-category select-pesticide" id="search_header_main">
                                                                                    <input type="text"  data-tag_type="pesticide" data-ref_div="farm_pesticide_div"  class="typeahead" placeholder="Search Pesticide" style=""/>
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
                                                        <div class="col-md-8">
                                                            <div class="row border border-dark">
                                                                <div class="bootstrap-tagsinput" id="farm_seed_pest_div">
                                                                    @foreach($seedPesticideBrandTags as $customerTag)
                                                                    <button id="tag{{$customerTag['tag_cloud_id']}}{{$customerTag['crm_customer_id']}}" class="lable" style="background-color:rgb(241 243 244);display: inline;font-size: 90%;margin-left: 2px;margin-top:3px;margin-bottom:3px;padding-bottom: 2px;padding-top: 2px">{{$customerTag['name']}}
                                                                        @if($user->role->slug == 'admin')
                                                                        &nbsp;<span style="color: red;" onclick="removeCustTag({{$customerTag['tag_cloud_id']}},{{$customerTag['crm_customer_id']}})">&nbsp;×</span>
                                                                        @endIf
                                                                    </button>&nbsp;&nbsp;&nbsp;
                                                                    @endforeach
                                                                </div>
                                                                <div class="logo-wrap">
                                                                    <div class=container>
                                                                        <div class="menu clearfix">
                                                                            <ul class="clearfix">
                                                                                <li class="select-category select-pesticide" id="search_header_main">
                                                                                    <input type="text" class="typeahead" data-tag_type="seed-pesticide-brand" data-ref_div="farm_seed_pest_div" placeholder="Search seeds and pesticide brands" style=""/>
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
                                                        <div class="col-md-8">
                                                            <div class="row border border-dark">
                                                                <div class="bootstrap-tagsinput" id="farm_seed_div">
                                                                    @foreach($seedVarietyTags as $customerTag)
                                                                    <button id="tag{{$customerTag['tag_cloud_id']}}{{$customerTag['crm_customer_id']}}" class="lable" style="background-color:rgb(241 243 244);display: inline;font-size: 90%;margin-left: 2px;margin-top:3px;margin-bottom:3px;padding-bottom: 2px;padding-top: 2px">{{$customerTag['name']}}
                                                                        @if($user->role->slug == 'admin')
                                                                        &nbsp;<span style="color: red;" onclick="removeCustTag({{$customerTag['tag_cloud_id']}},{{$customerTag['crm_customer_id']}})">&nbsp;×</span>
                                                                        @endIf
                                                                    </button>&nbsp;&nbsp;&nbsp;
                                                                    @endforeach
                                                                </div>
                                                                <div class="logo-wrap">
                                                                    <div class=container>
                                                                        <div class="menu clearfix">
                                                                            <ul class="clearfix">
                                                                                <li class="select-category select-pesticide" id="search_header_main">
                                                                                    <input type="text" data-tag_type="seed-variety" data-ref_div="farm_seed_div" class="typeahead" placeholder="Search seed variety" style=""/>
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
                                                        <div class="col-md-8">
                                                            <div class="row border border-dark">
                                                                <div class="bootstrap-tagsinput" id="farm_tool_div">
                                                                    @foreach($toolTags as $customerTag)
                                                                    <button id="tag{{$customerTag['tag_cloud_id']}}{{$customerTag['crm_customer_id']}}" class="lable" style="background-color:rgb(241 243 244);display: inline;font-size: 90%;margin-left: 2px;margin-top:3px;margin-bottom:3px;padding-bottom: 2px;padding-top: 2px">{{$customerTag['name']}}
                                                                        @if($user->role->slug == 'admin')
                                                                        &nbsp;<span style="color: red;" onclick="removeCustTag({{$customerTag['tag_cloud_id']}},{{$customerTag['crm_customer_id']}})">&nbsp;×</span>
                                                                        @endIf
                                                                    </button>&nbsp;&nbsp;&nbsp;
                                                                    @endforeach
                                                                </div>
                                                                <div class="logo-wrap">
                                                                    <div class=container>
                                                                        <div class="menu clearfix">
                                                                            <ul class="clearfix">
                                                                                <li class="select-category select-pesticide" id="search_header_main">
                                                                                    <input type="text" class="typeahead" data-ref_div="farm_tool_div" data-tag_type="tool" placeholder="Search tools" style=""/>
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
                                                            <input type="text" class="form-control" name="product_sold_market" placeholder="Enter sold market name"  @if($profileData) value="{{$profileData['product_sold_market']}}" @endif>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label">Product purchase from
                                                        </label>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control" name="product_purchase_from" placeholder="Product purchase market"  @if($profileData) value="{{$profileData['product_purchase_from']}}" @endif>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label">Income level
                                                        </label>
                                                        <div class="col-md-6">
                                                            <select class="form-control" name="income_level">
                                                                <option value="">Please select income level</option>
                                                                <option value="Low" @if($profileData && $profileData['income_level'] == 'Low')  selected @endif>Low</option>
                                                                <option value="Medium" @if($profileData && $profileData['income_level'] == 'Medium')  selected @endif>Medium</option>
                                                                <option value="High" @if($profileData && $profileData['income_level'] == 'High')  selected @endif>High</option>
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
                                                <div class="tab-pane" id="tab_3">
                                                    <div class="row">
                                                        <form class="form-horizontal form-row-seperated" id="spraying-form" action="/customer/crop-spraying" method="POST">
                                                            {{ csrf_field() }}
                                                            <input type="hidden" name="mobile" value="{{$mobile}}">
                                                        <!--<div class="col-md-12 border border-dark">
                                                            <div class="portlet light " id="blockui_sample_1_portlet_body">
                                                                <div class="portlet-title">
                                                                    <div class="caption">
                                                                        <i class="icon-crop font-green-sharp"></i>
                                                                        <span class="caption-subject font-green-sharp sbold">Portlet Blocking</span>
                                                                    </div>
                                                                </div>
                                                                <div class="portlet-body">
                                                                    <div class="form-body">
                                                                        <div class="form-group">
                                                                            <label class="col-md-3 control-label">Spraying 1:
                                                                                <span class="required"> * </span>
                                                                            </label>
                                                                            <div class="col-md-3">
                                                                                <input type="text" class="form-control" name="full_name" placeholder="" @if($profileData) value="{{$profileData['full_name']}}" @endif>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <input type="date" class="form-control" name="full_name" placeholder="" @if($profileData) value="{{$profileData['full_name']}}" @endif>
                                                                            </div>
                                                                            <div class="col-md-1">
                                                                                <a href="javascript:;" class="btn btn-sm green add-crop-spraying"> Add
                                                                                    <i class="fa fa-plus"></i>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>-->
                                                            <div class="form-group">
                                                                <div class="col-md-11 text-right">
                                                                    <button class="btn base-color" type="submit">
                                                                        <i class="fa fa-check-circle"></i> Save
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
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
                                                        <div class="form-check" style="margin-top: 7px;">
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
                                                        <div class="row border border-dark">
                                                            <div class="bootstrap-tagsinput" id="">
                                                                @foreach($customerTags as $customerTag)
                                                                <button id="tag{{$customerTag['tag_cloud_id']}}{{$customerTag['crm_customer_id']}}" class="lable" style="background-color:rgb(241 243 244);display: inline;font-size: 90%;margin-left: 2px;margin-top:3px;margin-bottom:3px;padding-bottom: 2px;padding-top: 2px">{{$customerTag['name']}}
                                                                    &nbsp;<span style="color: red;" onclick="removeCustTag({{$customerTag['tag_cloud_id']}},{{$customerTag['crm_customer_id']}})">&nbsp;×</span>
                                                                </button>&nbsp;&nbsp;&nbsp;
                                                                @endforeach
                                                            </div>
                                                            <div class="logo-wrap">
                                                                <div class=container>
                                                                    <div class="menu clearfix">
                                                                        <ul class="clearfix">
                                                                            <li class="select-category select-pesticide" id="search_header_main">
                                                                                <input type="text" class="typeahead" placeholder="Search tools" style=""/>
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
                                                        <div class="row border border-dark">
                                                            <div class="bootstrap-tagsinput" id="">
                                                                @foreach($customerTags as $customerTag)
                                                                <button id="tag{{$customerTag['tag_cloud_id']}}{{$customerTag['crm_customer_id']}}" class="lable" style="background-color:rgb(241 243 244);display: inline;font-size: 90%;margin-left: 2px;margin-top:3px;margin-bottom:3px;padding-bottom: 2px;padding-top: 2px">{{$customerTag['name']}}
                                                                    &nbsp;<span style="color: red;" onclick="removeCustTag({{$customerTag['tag_cloud_id']}},{{$customerTag['crm_customer_id']}})">&nbsp;×</span>
                                                                </button>&nbsp;&nbsp;&nbsp;
                                                                @endforeach
                                                            </div>
                                                            <div class="logo-wrap">
                                                                <div class=container>
                                                                    <div class="menu clearfix">
                                                                        <ul class="clearfix">
                                                                            <li class="select-category select-pesticide" id="search_header_main">
                                                                                <input type="text" class="typeahead" placeholder="Search tools" style=""/>
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
                                                        <div class="row border border-dark">
                                                            <div class="bootstrap-tagsinput" id="">
                                                                @foreach($customerTags as $customerTag)
                                                                <button id="tag{{$customerTag['tag_cloud_id']}}{{$customerTag['crm_customer_id']}}" class="lable" style="background-color:rgb(241 243 244);display: inline;font-size: 90%;margin-left: 2px;margin-top:3px;margin-bottom:3px;padding-bottom: 2px;padding-top: 2px">{{$customerTag['name']}}
                                                                    &nbsp;<span style="color: red;" onclick="removeCustTag({{$customerTag['tag_cloud_id']}},{{$customerTag['crm_customer_id']}})">&nbsp;×</span>
                                                                </button>&nbsp;&nbsp;&nbsp;
                                                                @endforeach
                                                            </div>
                                                            <div class="logo-wrap">
                                                                <div class=container>
                                                                    <div class="menu clearfix">
                                                                        <ul class="clearfix">
                                                                            <li class="select-category select-pesticide" id="search_header_main">
                                                                                <input type="text" class="typeahead" placeholder="Search tools" style=""/>
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
                                                        <div class="row border border-dark">
                                                            <div class="bootstrap-tagsinput" id="">
                                                                @foreach($customerTags as $customerTag)
                                                                <button id="tag{{$customerTag['tag_cloud_id']}}{{$customerTag['crm_customer_id']}}" class="lable" style="background-color:rgb(241 243 244);display: inline;font-size: 90%;margin-left: 2px;margin-top:3px;margin-bottom:3px;padding-bottom: 2px;padding-top: 2px">{{$customerTag['name']}}
                                                                    &nbsp;<span style="color: red;" onclick="removeCustTag({{$customerTag['tag_cloud_id']}},{{$customerTag['crm_customer_id']}})">&nbsp;×</span>
                                                                </button>&nbsp;&nbsp;&nbsp;
                                                                @endforeach
                                                            </div>
                                                            <div class="logo-wrap">
                                                                <div class=container>
                                                                    <div class="menu clearfix">
                                                                        <ul class="clearfix">
                                                                            <li class="select-category select-pesticide" id="search_header_main">
                                                                                <input type="text" class="typeahead" placeholder="Search tools" style=""/>
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
                                                        <div class="row border border-dark">
                                                            <div class="bootstrap-tagsinput" id="">
                                                                @foreach($customerTags as $customerTag)
                                                                <button id="tag{{$customerTag['tag_cloud_id']}}{{$customerTag['crm_customer_id']}}" class="lable" style="background-color:rgb(241 243 244);display: inline;font-size: 90%;margin-left: 2px;margin-top:3px;margin-bottom:3px;padding-bottom: 2px;padding-top: 2px">{{$customerTag['name']}}
                                                                    &nbsp;<span style="color: red;" onclick="removeCustTag({{$customerTag['tag_cloud_id']}},{{$customerTag['crm_customer_id']}})">&nbsp;×</span>
                                                                </button>&nbsp;&nbsp;&nbsp;
                                                                @endforeach
                                                            </div>
                                                            <div class="logo-wrap">
                                                                <div class=container>
                                                                    <div class="menu clearfix">
                                                                        <ul class="clearfix">
                                                                            <li class="select-category select-pesticide" id="search_header_main">
                                                                                <input type="text" class="typeahead" placeholder="Search tools" style=""/>
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
                                                        <div class="row border border-dark">
                                                            <div class="bootstrap-tagsinput" id="">
                                                                @foreach($customerTags as $customerTag)
                                                                <button id="tag{{$customerTag['tag_cloud_id']}}{{$customerTag['crm_customer_id']}}" class="lable" style="background-color:rgb(241 243 244);display: inline;font-size: 90%;margin-left: 2px;margin-top:3px;margin-bottom:3px;padding-bottom: 2px;padding-top: 2px">{{$customerTag['name']}}
                                                                    &nbsp;<span style="color: red;" onclick="removeCustTag({{$customerTag['tag_cloud_id']}},{{$customerTag['crm_customer_id']}})">&nbsp;×</span>
                                                                </button>&nbsp;&nbsp;&nbsp;
                                                                @endforeach
                                                            </div>
                                                            <div class="logo-wrap">
                                                                <div class=container>
                                                                    <div class="menu clearfix">
                                                                        <ul class="clearfix">
                                                                            <li class="select-category select-pesticide" id="search_header_main">
                                                                                <input type="text" class="typeahead" placeholder="Search tools" style=""/>
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
                                                        <div class="row border border-dark">
                                                            <div class="bootstrap-tagsinput" id="">
                                                                @foreach($customerTags as $customerTag)
                                                                <button id="tag{{$customerTag['tag_cloud_id']}}{{$customerTag['crm_customer_id']}}" class="lable" style="background-color:rgb(241 243 244);display: inline;font-size: 90%;margin-left: 2px;margin-top:3px;margin-bottom:3px;padding-bottom: 2px;padding-top: 2px">{{$customerTag['name']}}
                                                                    &nbsp;<span style="color: red;" onclick="removeCustTag({{$customerTag['tag_cloud_id']}},{{$customerTag['crm_customer_id']}})">&nbsp;×</span>
                                                                </button>&nbsp;&nbsp;&nbsp;
                                                                @endforeach
                                                            </div>
                                                            <div class="logo-wrap">
                                                                <div class=container>
                                                                    <div class="menu clearfix">
                                                                        <ul class="clearfix">
                                                                            <li class="select-category select-pesticide" id="search_header_main">
                                                                                <input type="text" class="typeahead" placeholder="Search tools" style=""/>
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
                                                        <div class="row border border-dark">
                                                            <div class="bootstrap-tagsinput" id="">
                                                                @foreach($customerTags as $customerTag)
                                                                <button id="tag{{$customerTag['tag_cloud_id']}}{{$customerTag['crm_customer_id']}}" class="lable" style="background-color:rgb(241 243 244);display: inline;font-size: 90%;margin-left: 2px;margin-top:3px;margin-bottom:3px;padding-bottom: 2px;padding-top: 2px">{{$customerTag['name']}}
                                                                    &nbsp;<span style="color: red;" onclick="removeCustTag({{$customerTag['tag_cloud_id']}},{{$customerTag['crm_customer_id']}})">&nbsp;×</span>
                                                                </button>&nbsp;&nbsp;&nbsp;
                                                                @endforeach
                                                            </div>
                                                            <div class="logo-wrap">
                                                                <div class=container>
                                                                    <div class="menu clearfix">
                                                                        <ul class="clearfix">
                                                                            <li class="select-category select-pesticide" id="search_header_main">
                                                                                <input type="text" class="typeahead" placeholder="Search tools" style=""/>
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
                                                        <div class="row border border-dark">
                                                            <div class="bootstrap-tagsinput" id="gard_tool_div">
                                                                @foreach($customerTags as $customerTag)
                                                                <button id="tag{{$customerTag['tag_cloud_id']}}{{$customerTag['crm_customer_id']}}" class="lable" style="background-color:rgb(241 243 244);display: inline;font-size: 90%;margin-left: 2px;margin-top:3px;margin-bottom:3px;padding-bottom: 2px;padding-top: 2px">{{$customerTag['name']}}
                                                                    &nbsp;<span style="color: red;" onclick="removeCustTag({{$customerTag['tag_cloud_id']}},{{$customerTag['crm_customer_id']}})">&nbsp;×</span>
                                                                </button>&nbsp;&nbsp;&nbsp;
                                                                @endforeach
                                                            </div>
                                                            <div class="logo-wrap">
                                                                <div class=container>
                                                                    <div class="menu clearfix">
                                                                        <ul class="clearfix">
                                                                            <li class="select-category select-pesticide" id="search_header_main">
                                                                                <input type="text" class="typeahead" data-ref_div="gard_tool_div" placeholder="Search tools" style=""/>
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
            <div id="pesticides_selection" hidden="hidden">
                <option value="">Select pesticide </option>
                @foreach($pesticideTags as $pesticide)
                <option value="{{$pesticide['tag_cloud_id']}}">{{$pesticide['name']}}</option>
                @endforeach
            </div>
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
    <script type="text/javascript" src="/assets/custom/tag/js/tag.js"></script>
    <script type="text/javascript" src="/assets/custom/customer/js/profile.js"></script>

        <!-- END PAGE LEVEL SCRIPTS -->
    <!-- BEGIN THEME LAYOUT SCRIPTS -->
    <script src="/assets/layouts/layout3/scripts/demo.min.js" type="text/javascript"></script>
        <script>

        </script>
@endsection