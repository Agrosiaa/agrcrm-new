@extends('backend.seller.layouts.master')
@section('title','Agrosiaa | Profile')
@section('css')
<!-- BEGIN PAGE LEVEL PLUGINS -->
<link href="/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />
<link href="/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
<link href="/assets/global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet" type="text/css" />
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link href="/assets/pages/css/profile-2.min.css" rel="stylesheet" type="text/css" />
<!-- END PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="/assets/frontend/global/css/style.css">
@endsection
@include('backend.partials.common.nav')
@section('content')
<!-- BEGIN PAGE CONTENT BODY -->
<div class="page-content content-min-height">
<div class="container">
<!-- BEGIN PAGE CONTENT INNER -->
<div class="page-content-inner">
<div class="profile">
<div class="tabbable-line tabbable-full-width">
<ul class="nav nav-tabs">
    <li class="active">
        <a href="#tab_1_1" data-toggle="tab"> Overview </a>
    </li>
    <li>
        <a href="#tab_1_2" data-toggle="tab"> Account </a>
    </li>
    {{--<li>
        <a href="#tab_1_3" data-toggle="tab"> Help </a>
    </li>--}}
</ul>
<input type="hidden" id="seller_id" value="{{$seller['id']}}">
<div class="tab-content">
<div class="tab-pane active" id="tab_1_1">
    <div class="row">
        <div class="col-md-3">
            <ul class="list-unstyled profile-nav">
                <li>
                    @if($profileImage==null)
                    <img src="/assets/pages/media/profile/people19.png" class="img-responsive pic-bordered" alt="" />
                    @else
                    <img src="{{$profileImage}}" height="253" width="274" class="img-responsive pic-bordered" alt="" />
                    @endif
                </li>
            </ul>
        </div>
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-8 profile-info">
                    <h1 class="font-green sbold uppercase">{{$user['first_name']}} {{$user['last_name']}}</h1>
                    <h5 class="profile-usertitle-name">Registration ID : {{$user['id']}}</h5>


                </div>
                <!--end col-md-8-->

                <!--end col-md-4-->
            </div>
            <!--end row-->

        </div>
    </div>
</div>
<!--tab_1_2-->
<div class="tab-pane" id="tab_1_2">
    <div class="row profile-account">
        <div class="col-md-3">
            <ul class="ver-inline-menu tabbable margin-bottom-10">
                <li class="active">
                    <a data-toggle="tab" href="#tab_1-1">
                        <i class="fa fa-cog"></i> Personal Information </a>
                    <span class="after"> </span>
                </li>
                <li>
                    <a data-toggle="tab" href="#tab_2-2">
                        <i class="fa fa-lock"></i> Change Password </a>
                </li>
                <li>
                    <a data-toggle="tab" href="#tab_3-3">
                        <i class="fa fa-lock"></i> Bank Details </a>
                </li>
                <!--<li>
                    <a data-toggle="tab" href="#tab_4-4">
                        <i class="fa fa-eye"></i> Account Balance </a>
                </li>-->
                <li>
                    <a data-toggle="tab" href="#tab_5-5">
                        <i class="fa fa-picture-o"></i> Change Profile Picture </a>
                </li>
                <li>
                    <a data-toggle="tab" href="#tab_5-7">
                        <i class="fa fa-picture-o"></i> Other Licenses </a>
                </li>
                <li>
                    <a data-toggle="tab" href="#tab_5-6">
                        <i class="fa fa-picture-o"></i> Uploaded Documents </a>
                </li>
                <li>
                    <a data-toggle="tab" href="#tab_5-8">
                        <i class="fa fa-picture-o"></i> Company details </a>
                </li>
                <li>
                    <a data-toggle="tab" href="#tab_5-9">
                        <i class="fa fa-picture-o"></i> Product pickup addresses </a>
                </li>
                <li>
                    <a data-toggle="tab" href="#tab_5-10">
                        <i class="fa fa-picture-o"></i> Vendor Policy </a>
                </li>
            </ul>
        </div>
        <div class="col-md-9">
            <div class="tab-content">
                @include('backend.partials.error-messages')
                <div id="tab_1-1" class="tab-pane active">
                    <form role="form" action="/profile" method="POST" id="personal_information">
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <label class="control-label">First Name</label>
                            <input type="text" placeholder="John" class="form-control" name="first_name" value="{{ucfirst($user['first_name'])}}"/> </div>
                        <div class="form-group">
                            <label class="control-label">Last Name</label>
                            <input type="text" placeholder="Doe" class="form-control" name="last_name" value="{{ucfirst($user['last_name'])}}"/> </div>
                        <div class="form-group">
                            <label class="control-label">Email</label>
                            <input type="text" placeholder="abc@example.com" class="form-control" name="email" value="{{$user['email']}}" readonly/> </div>
                        <div class="form-group">
                            <label class="control-label">Date Of Birth</label>
                            <div class="input-group date date-picker" data-date-format="dd/mm/yyyy">
                                <input class="form-control form-filter input-sm"  name="dob" value="{{$user['dob']}}">
                                <span class="input-group-btn">
                                    <button class="btn btn-sm default" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Gender</label>
                            <div>
                                <div class="radio-list" data-error-container="#personal_information_gender_error">
                                    <label>
                                        <input name="gender" type="radio" value="F" @if($user['gender']!=null) @if($user['gender']=='F') checked @endif @endif />Female
                                    </label>
                                    <label>
                                        <input name="gender" type="radio" value="M" @if($user['gender']!=null) @if($user['gender']=='M') checked @endif @endif/>Male
                                    </label>
                                </div>
                                <div id="personal_information_gender_error"> </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Mobile Number</label>
                            <input type="text" name="mobile" value="@if($user['mobile']!=null){{$user['mobile']}}@endif" placeholder="+1 646 580 DEMO (6284)" class="form-control" readonly/>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Shop no/Office no/Survey no</label>
                            <input type="text"  class="form-control" name="name_of_premise_building_village" value="{{ucfirst($defaultAddressDetails['name_of_premise_building_village'])}}" readonly/>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Name of Premises/Building/Village</label>
                            <input type="text"  class="form-control" name="shop_no_office_no_survey_no" value="{{ucfirst($defaultAddressDetails['shop_no_office_no_survey_no'])}}" readonly/>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Road/Street/Lane</label>
                            <input type="text"  class="form-control" name="road_street_lane" value="{{ucfirst($defaultAddressDetails['road_street_lane'])}}" readonly/>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Area/Locality/Wadi</label>
                            <input type="text"  class="form-control" name="area_locality_wadi" value="{{ucfirst($defaultAddressDetails['area_locality_wadi'])}}" readonly/>
                        </div>
                        <div class="form-group">
                            <label class="control-label">At post</label>
                            <input type="text"  class="form-control" name="at_post" value="{{ucfirst($defaultAddressDetails['at_post'])}}" readonly/>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Taluka </label>
                            <input type="text"  class="form-control" name="taluka" value="{{ucfirst($defaultAddressDetails['taluka'])}}" readonly/>
                        </div>
                        <div class="form-group">
                            <label class="control-label">District</label>
                            <input type="text"  class="form-control" name="district" value="{{ucfirst($defaultAddressDetails['district'])}}" readonly/>
                        </div>
                        <div class="form-group">
                            <label class="control-label">State</label>
                            <input type="text"  class="form-control" name="state" value="{{ucfirst($defaultAddressDetails['state'])}}" readonly/>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Pin Code</label>
                            <input type="text" placeholder="" class="form-control" name="pincode" value="{{$defaultAddressDetails['pincode']}}" readonly/>
                        </div>
                        <div class="margin-top-10">
                            <button type="submit" class="btn base-color">Save</button>
                            <button type="reset" class="btn default">Cancel</button>
                        </div>
                    </form>
                </div>

                <div id="tab_2-2" class="tab-pane">
                    <form action="/password/update" method="POST" id="change_password">
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <label class="control-label">Current Password</label>
                            <input type="password" class="form-control" name="current_password"/> </div>
                        <div class="form-group">
                            <label class="control-label">New Password</label>
                            <input type="password" class="form-control" name="password" id="password"/> </div>
                        <div class="form-group">
                            <label class="control-label">Re-type New Password</label>
                            <input type="password" class="form-control" name="password_confirmation"/> </div>
                        <div class="margin-top-10">
                            <button class="btn base-color"> Change Password </button>
                            <button type="reset"  class="btn default">Reset</button>
                        </div>

                    </form>
                </div>
                <div id="tab_3-3" class="tab-pane">
                    <form action="#" method="post" id="bank_details">
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <label class="control-label">Account No.</label>
                            <input type="text" class="form-control" name="account_no" value="{{$bankDetails['account_no']}}" readonly/> </div>
                        <div class="form-group">
                            <label class="control-label">Beneficiary Name</label>
                            <input type="text" class="form-control" name="beneficiary_name" value="{{$bankDetails['beneficiary_name']}}" readonly/> </div>
                        <div class="form-group">
                            <label class="control-label">Bank Name</label>
                            <input type="text" class="form-control" name="bank_name" value="{{$bankDetails['bank_name']}}" readonly/> </div>
                        <div class="form-group">
                            <label class="control-label">Branch Name</label>
                            <input type="text" class="form-control" name="branch_name" value="{{$bankDetails['branch_name']}}" readonly/> </div>
                        <div class="form-group">
                            <label class="control-label">RTGS / NEFT IFSC CODE #</label>
                            <input type="text" class="form-control" name="ifsc_code" value="{{$bankDetails['ifsc_code']}}" readonly/> </div>
                        <div class="form-group">
                            <label class="control-label">Pan Number</label>
                            <input type="text" class="form-control" name="pan_number" value="{{$bankDetails['pan_number']}}" readonly/> </div>
                        <div class="form-group">
                            <label class="control-label">TAN No</label>
                            <input type="text" class="form-control" name="tan_number" value="{{$bankDetails['tan_number']}}" readonly/> </div>
                        <div class="form-group">
                            <label class="control-label">Type of Account</label>
                            <select name="account_type" class="form-control form-filter input-sm" disabled>
                                <option value="">Select...</option>
                                <option value="current" @if($bankDetails['account_type']=='current') selected='selected' @endif>Current</option>
                                <option value="saving" @if($bankDetails['account_type']=='saving') selected='selected' @endif>Saving</option>
                                <option value="cash_credit" @if($bankDetails['account_type']=='cash_credit') selected='selected' @endif>Cash Credit</option>
                            </select>
                            <input type="hidden" name="account_type" value="{{$bankDetails['account_type']}}"/>
                        </div>
                    </form>
                </div>

                <div id="tab_5-7" class="tab-pane">
                    For uploading any new licenses, please contact administrator contact @agrosiaa.com. We will get back to you in 2 working days.
                    <form class="form-horizontal" role="form">
                        @foreach($licenses as $licenseInfo)
                          <div class="form-group">
                              <label class="control-label col-md-2">{{$licenseInfo['license']['name']}} License </label>
                              <div class="col-md-4">
                                  <input type="text" name="lic_number" class="form-control" value="{{$licenseInfo['license_number']}}" readonly/>
                              </div>
                              <div class="col-md-4">
                                  <input type="text" name="lic_exp_date"  class="form-control" value="{{$licenseInfo['expiry_date']}}" readonly/>
                              </div>
                          </div>
                        @endforeach
                    </form>
                </div>

                <div id="tab_5-6" class="tab-pane upload">
                    <div class="row">
                        @if($seller['pan_card']!=null)
                        <div class="col-md-3">
                            <div class="text-center">PAN Card</div>
                            @if($seller['pan_card']==null)
                            <img src="/assets/pages/img/no-image.png" alt="" />
                            @else
                            @if (pathinfo($vendorOwnDirectory.$seller['pan_card'], PATHINFO_EXTENSION) == 'pdf')
                            <a href="{{$vendorOwnDirectory.$seller['pan_card']}}" target="_blank">
                                <img src="/assets/pages/img/pdf.jpg" alt="" />
                            </a>
                            @else
                            <a href="{{$vendorOwnDirectory.$seller['pan_card']}}" class="fancybox-button" data-rel="fancybox-button">
                                <img class="img-responsive" src="{{$vendorOwnDirectory.$seller['pan_card']}}" alt="">
                            </a>
                            @endif
                            @endif
                        </div>
                        @endif
                        @if($seller['shop_act']!=null)
                        <div class="col-md-3">
                            <div class="text-center">Shop Act</div>
                            @if($seller['shop_act']==null)
                            <img src="/assets/pages/img/no-image.png" alt="" />
                            @else
                            @if (pathinfo($vendorOwnDirectory.$seller['shop_act'], PATHINFO_EXTENSION) == 'pdf')
                            <a href="{{$vendorOwnDirectory.$seller['shop_act']}}" target="_blank">
                                <img src="/assets/pages/img/pdf.jpg" alt="" />
                            </a>
                            @else
                            <a href="{{$vendorOwnDirectory.$seller['shop_act']}}" class="fancybox-button" data-rel="fancybox-button">
                                <img class="img-responsive" src="{{$vendorOwnDirectory.$seller['shop_act']}}" alt="">
                            </a>
                            @endif
                            @endif
                        </div>
                        @endif
                        @if($seller['gstin_certificate']!=null)
                        <div class="col-md-3">
                            <div class="text-center">GSTIN Certificate</div>
                            @if($seller['gstin_certificate']==null)
                            <img src="/assets/pages/img/no-image.png" alt="" />
                            @else
                                @if (pathinfo($vendorOwnDirectory.$seller['gstin_certificate'], PATHINFO_EXTENSION) == 'pdf')
                                <a href="{{$vendorOwnDirectory.$seller['gstin_certificate']}}" target="_blank">
                                    <img src="/assets/pages/img/pdf.jpg" alt="" />
                                </a>
                                @else
                                <a href="{{$vendorOwnDirectory.$seller['gstin_certificate']}}" class="fancybox-button" data-rel="fancybox-button">
                                    <img class="img-responsive" src="{{$vendorOwnDirectory.$seller['gstin_certificate']}}" alt="">
                                </a>
                                @endif
                            @endif

                        </div>
                        @endif
                        @if($seller['cancelled_cheque']!=null)
                        <div class="col-md-3">
                            <div class="text-center">Cancelled Cheque</div>
                            @if($seller['cancelled_cheque']==null)
                            <img src="/assets/pages/img/no-image.png" alt="" />
                            @else
                            @if (pathinfo($vendorOwnDirectory.$seller['cancelled_cheque'], PATHINFO_EXTENSION) == 'pdf')
                            <a href="{{$vendorOwnDirectory.$seller['cancelled_cheque']}}" target="_blank">
                                <img src="/assets/pages/img/pdf.jpg" alt="" />
                            </a>
                            @else
                            <a href="{{$vendorOwnDirectory.$seller['cancelled_cheque']}}" class="fancybox-button" data-rel="fancybox-button">
                                <img class="img-responsive" src="{{$vendorOwnDirectory.$seller['cancelled_cheque']}}" alt="">
                            </a>
                            @endif
                            @endif
                        </div>
                        @endif
                        @foreach($licenses as $licenseInfo)

                        <div class="col-md-3">
                            <div class="text-center">{{$licenseInfo['license']['name']}} License</div>
                            @if($licenseInfo['license_image']==null)
                            <img src="/assets/pages/img/no-image.png" alt="" />
                            @else
                            @if (pathinfo($vendorOwnDirectory.$licenseInfo['license_image'], PATHINFO_EXTENSION) == 'pdf')
                            <a href="{{$vendorOwnDirectory.$licenseInfo['license_image']}}" target="_blank">
                                <img src="/assets/pages/img/pdf.jpg" alt="" />
                            </a>
                            @else
                            <a href="{{$vendorOwnDirectory.$licenseInfo['license_image']}}" class="fancybox-button" data-rel="fancybox-button">
                                <img class="img-responsive" src="{{$vendorOwnDirectory.$licenseInfo['license_image']}}" alt="">
                            </a>
                            @endif
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>


                <div id="tab_5-8" class="tab-pane">
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <label class="control-label">Company</label>
                            <input type="text" placeholder="" class="form-control" name="company" value="@if($user['seller']!=null){{$user['seller']['company']}}@endif" readonly/>
                        </div>
                        <div class="form-group">
                            <label class="control-label">GSTIN</label>
                            <input type="text" placeholder="" class="form-control" name="gstin" value="@if($user['seller']!=null){{$user['seller']['gstin']}}@endif" readonly/>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Company Identification No</label>
                            <input type="text" class="form-control" name="company_identification_number" value="{{$bankDetails['company_identification_number']}}" readonly/>
                        </div>
                    </form>
                </div>
                <div id="tab_5-9" class="tab-pane">
                    <div class="row">
                      <div class="col-md-5">
                        @if($addressCount == 10)
                        <button class="btn btn-sm base-color pull-left add-button margin-bottom-20" data-toggle="modal" data-target="#add-address"  disabled>Add Address</button>
                        @else
                        <button class="btn btn-sm base-color pull-left add-button margin-bottom-20" data-toggle="modal" data-target="#add-address">Add Address</button>
                        @endif
                      </div>
                    </div>
                    <!-- Modal -->
                    <div id="add-address" class="modal" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <form class="form-horizontal form-row-seperated" id="add_address" action="/add-new-address" method="POST" role="form">
                                    {!! csrf_field() !!}
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Add New Address</h4>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" id="user_id" value="{{$user['id']}}" />
                                        <input type="hidden" id="user_role" value="{{Session::get('role_type')}}" />
                                        <div class="form-group">
                                            <label class="col-md-6 control-label">Address abbreviation:
                                                <span class="required">*</span>
                                            </label>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" name="address_unique_name" id="address_unique_name" placeholder="Enter 10 Characters">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-6 control-label">Shop no/Office no/Survey no <span class="required">*</span></label>
                                            <div class="col-md-6"><input type="text"  class="form-control" id="shop_no_office_no_survey_no" name="shop_no_office_no_survey_no" /></div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-6 control-label">Name of Premises/Building/Village <span class="required">*</span></label>
                                            <div class="col-md-6"><input type="text"  class="form-control" id="name_of_premise_building_village" name="name_of_premise_building_village" /></div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-6 control-label">Road/Street/Lane <span class="required">*</span></label>
                                            <div class="col-md-6"><input type="text"  class="form-control" id="road_street_lane" name="road_street_lane" /></div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-6 control-label">Area/Locality/Wadi <span class="required">*</span></label>
                                            <div class="col-md-6"><input type="text"  class="form-control" id="area_locality_wadi" name="area_locality_wadi" ></div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-6 control-label">State:
                                                <span class="required">*</span>
                                            </label>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" id="state_name" name="state" value="MAHARASHTRA" readonly > </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-6 control-label">District:
                                                <span class="required">*</span>
                                            </label>
                                            <div class="col-md-6">
                                                <select class="form-control" name="district" id="district" >
                                                    <option  value="" >Please Select District</option>
                                                    @foreach($districts as $district)
                                                    <option  value="{{$district['district']}}" >{{$district['district']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-6 control-label">Taluka:
                                                <span class="required">*</span>
                                            </label>
                                            <div class="col-md-6">
                                                <select class="form-control" name="taluka" id="taluka" disabled>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-6 control-label">At Post <span class="required">*</span> </label>
                                            <div id="at-post" class="col-md-6">
                                                <input class="typeahead" type="text" placeholder="Select Post" id="at_post" name="at_post">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-6 control-label">Pin code:
                                                <span class="required">*</span>
                                            </label>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" id="pincode" name="pincode" readonly> </div>
                                        </div>
                                        @if($isDefaultUsed > 0)
                                        <div class="form-group">
                                            <div class="col-md-6 control-label">
                                                <input type="checkbox" class="form-control" id="default_address"><span>Make it as my default address</span>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn base-color">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- end Modal -->
                    <div class="row">
                    @if($addressDetails != null)
                    @foreach($addressDetails as $addressDetail)
                        <?php
                            if(strpos($addressDetail['address_unique_name'], 'default') !== false){
                                $uniqueName = split ("/", $addressDetail['address_unique_name']);
                                $addressUniqueName = $uniqueName[1];
                            }else{
                                $addressUniqueName = $addressDetail['address_unique_name'];
                            }
                        ?>
                        <div class="col-md-6 col-sm-12">
                            <div class="portlet yellow-crusta box">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-cogs"></i>
                                            {{$addressUniqueName}}
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="row static-info">
                                        <div class="col-md-6 name"> Address abbreviation: </div>
                                        <div class="col-md-6 value"> {{$addressUniqueName}} </div>
                                    </div>
                                    <div class="row static-info">
                                        <div class="col-md-6 name"> Shop no/Office no/Survey no: </div>
                                        <div class="col-md-6 value"> {{$addressDetail['shop_no_office_no_survey_no']}} </div>
                                    </div>
                                    <div class="row static-info">
                                        <div class="col-md-6 name"> Name of Premises/Building/Village: </div>
                                        <div class="col-md-6 value"> {{$addressDetail['name_of_premise_building_village']}} </div>
                                    </div>
                                    <div class="row static-info">
                                        <div class="col-md-6 name"> Road/Street/Lane: </div>
                                        <div class="col-md-6 value"> {{$addressDetail['road_street_lane']}} </div>
                                    </div>
                                    <div class="row static-info">
                                        <div class="col-md-6 name"> Area/Locality/Wadi: </div>
                                        <div class="col-md-6 value"> {{$addressDetail['area_locality_wadi']}} </div>
                                    </div>
                                    <div class="row static-info">
                                        <div class="col-md-6 name"> At Post: </div>
                                        <div class="col-md-6 value"> {{$addressDetail['at_post']}} </div>
                                    </div>
                                    <div class="row static-info">
                                        <div class="col-md-6 name"> Taluka: </div>
                                        <div class="col-md-6 value">{{$addressDetail['taluka']}} </div>
                                    </div>
                                    <div class="row static-info">
                                        <div class="col-md-6 name"> District: </div>
                                        <div class="col-md-6 value">{{$addressDetail['district']}} </div>
                                    </div>
                                    <div class="row static-info">
                                        <div class="col-md-6 name"> State: </div>
                                        <div class="col-md-6 value">{{$addressDetail['state']}} </div>
                                    </div>
                                    <div class="row static-info">
                                        <div class="col-md-6 name"> Pincode: </div>
                                        <div class="col-md-6 value">{{$addressDetail['pincode']}} </div>
                                    </div>
                                    {{--<div class="row static-info">
                                        <div class="col-md-7 "><a href="/delete-address/{{$addressDetail['id']}}" class="btn red-mint text-center">Delete Address</a></div>
                                    </div>--}}
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @endif
                    </div>
                </div>
                <div id="tab_5-10" class="tab-pane">
                  <div class="row">
                    <div class="col-md-12">
<p style="text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 14pt"><b>VENDOR
POLICY</b></font></font></p>


<p align="justify" style=" "><font face=""><font style="font-size: 10pt"><font face=""><b>THESE
WEBSITE VENDOR / MERCHANT / SELLER POLICY ("VENDOR POLICY")
IS AN ELECTRONIC RECORD IN THE FORM OF AN ELECTRONIC CONTRACT FORMED
UNDER INFORMATION TECHNOLOGY ACT, 2000 AND RULES MADE THEREUNDER AND
THE AMENDED PROVISIONS PERTAINING TO ELECTRONIC DOCUMENTS / RECORDS
IN VARIOUS STATUTES AS AMENDED BY THE INFORMATION TECHNOLOGY ACT,
2000. THESE VENDOR POLICY ARE COMPUTER GENERATED AND DOES NOT REQUIRE
ANY PHYSICAL, ELECTRONIC OR DIGITAL SIGNATURE. </b></font></font></font>
</p>
<p align="justify" style=" "><font face=""><font style="font-size: 10pt"><font face=""><b>THESE
VENDOR POLICY IS A LEGALLY BINDING DOCUMENT BETWEEN VENDOR / MERCHANT
/ SELLER AND AGROSIAA (BOTH TERMS DEFINED BELOW). THESE VENDOR POLICY
WILL BE EFFECTIVE UPON YOUR ACCEPTANCE OF THE SAME (DIRECTLY OR
INDIRECTLY IN ELECTRONIC FORM OR BY MEANS OF AN ELECTRONIC RECORD)
AND WILL GOVERN THE RELATIONSHIP BETWEEN VENDOR / MERCHANT / SELLER
AND AGROSIAA FOR THE USE OF THE WEBSITE (DEFINED BELOW). </b></font></font></font>
</p>
<p align="justify" style=" "><a name="_GoBack"></a>
<font face=""><font style="font-size: 10pt"><font face=""><b>THIS
DOCUMENT IS PUBLISHED AND SHALL BE CONSTRUED IN ACCORDANCE WITH THE
PROVISIONS OF RULE 3 (1) OF THE INFORMATION TECHNOLOGY
(INTERMEDIARIES GUIDELINES) RULES, 2011 UNDER INFORMATION TECHNOLOGY
ACT, 2000 THAT REQUIRES PUBLISHING THE RULES AND REGULATIONS, PRIVACY
POLICY AND USER AGREEMENT FOR ACCESS OR USAGE OF THE WEBSITE.</b></font></font></font></p>
<p align="justify" style="margin-top: 0.49cm;  ">
<font face=""><font style="font-size: 10pt"><b>THE
VENDOR POLICY IS COMMON FOR WEBSITE AND MOBILE APP AND THEREFORE THE
WORD ‘WEBSITE’ SHALL BE MEAN ‘MOBILE APP’ FOR THE MOBILE APP
USERS.  </b></font></font>
</p>
<p align="justify" style=" "><font face=""><font style="font-size: 10pt">Agrosiaa
Agri-Commodities Online Services LLP</font></font><font face=""><font style="font-size: 10pt">
("Agrosiaa," "we," "us," and "our")
reserves the right to change any of the terms and conditions
contained in this Policy or any policies or guidelines governing the
Site or Services, at any time and in its sole discretion. Any changes
will be effective upon posting of the revisions on the Site. All
notice of changes to this Policy will be posted on the Site for
thirty (30) days. You are responsible for reviewing the notice and
any applicable changes. Changes to referenced policies and guidelines
may be posted without notice to you. YOUR CONTINUED USE OF THIS SITE
AND THE SERVICES FOLLOWING OUR POSTING OF ANY CHANGES WILL CONSTITUTE
YOUR ACCEPTANCE OF SUCH CHANGES OR MODIFICATIONS. IF YOU DO NOT AGREE
TO ANY CHANGES TO THIS PARTICIPATION AGREEMENT, DO NOT CONTINUE TO
USE THE SERVICES OR SITE.</font></font></p>
<p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">This
Vendors Policy is incorporated into and subject to the Terms of Use
and Privacy Policy.</font></font></p>
<p style=" ">
</p>
<ol>
	<li><p style=" "><b><font face=""><font style="font-size: 10pt">1). ROLE
	OF AGROSIAA</font></font></b></p>
</li></ol>
<p style="margin-left: 1.27cm;  ">

</p>
<p align="justify" style="margin-left: 1.27cm;  ">
<font face=""><font style="font-size: 10pt">Agrosiaa
provides a platform for third-party Vendor / Merchant / Seller
("Sellers") and buyers ("Buyers") to negotiate
and complete transactions. Agrosiaa is not involved in the actual
transaction between Vendor / Merchant / Seller  and Buyers. As a
Vendor / Merchant / Seller, you may list any item on the Site unless
it is a prohibited item as defined in the procedures and guidelines
contained hereunder or otherwise prohibited by law. Without
limitation, you may not list any item or link or post any related
material that (a) infringes any third-party intellectual property
rights (including copyright, trademark, patent, and trade secrets) or
other proprietary rights (including rights of publicity or privacy);
(b) constitutes libel or slander or is otherwise defamatory; or (c)
is counterfeited, illegal, stolen, or fraudulent. It is up to the
Seller to accurately describe the item for sale. As a Vendor /
Merchant / Seller, you use the Site and the Services for display and
selling the products.</font></font></p>
<p style="margin-left: 1.27cm;  ">

</p>
<ol start="2">
	<li><p style=" "><font face=""><font style="font-size: 10pt"><b>2).VENDORS
	REGISTRATION</b></font></font></p>
</li></ol>
<p style="margin-left: 1.27cm;  ">

</p>
<ol start="2">
	<ol>
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">2.1The
		Vendor/ Merchant / Seller’s intending to sell their products on
		the website needs to register themselves on the Website and are
		required to provide verification documents. The Vendor / Merchant /
		Seller’s has declared that all particulars as provided by the
		Vendor / Merchant / Seller’s in the online registration form as
		are relied upon by Agrosiaa, are true and correct in all material
		particulars, and Agrosiaa has placed explicit reliance and faith
		upon the said representation of the said party. </font></font>
		</p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">2.2The
		Vendor / Merchant / Seller’s has declared that the goods/
		products/ services as offered for sale by the Vendor / Merchant /
		Seller’s satisfy all statutory requirements as may be applicable
		at the point of manufacture as well as the point of sale or
		delivery.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">2.3The
		Vendor / Merchant / Seller’s has declared that the Vendor /
		Merchant / Seller’s shall strictly adhere to all assurances made
		by it to the final purchaser, as regards the quality and quantity
		of the goods/ products/ services, and the time of delivery, and all
		risk and costs associated therewith shall be to the sole and
		exclusive account of the Vendor / Merchant / Seller’s.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">2.4Vendor
		/ Merchant / Seller’s whose transaction value has exceeded INR
		50,000/- (cumulative) through single transactions have to complete
		the additional verification (know your customer or KYC) process.
		Till the time such an additional verification is concluded, your
		remittances shall be on hold and only released upon completion of
		the KYC process. This verification procedure is aimed to further
		help Agrosiaa become a safer and secure e-commerce platform.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">2.5Vendor
		/ Merchant / Seller needs to provide valid self attesteddocuments
		as proof of identity and address of the beneficiary or bank account
		holder mentioned during the registration process.</font></font></p>
	</li></ol>
</ol>
<p style="margin-left: 1.27cm; margin-bottom: 0cm; ">

</p>

<p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">Given
below are the documents acceptable for the KYC process:</font></font></p>
<p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt"><b>1.
For bank accounts belonging to individuals</b></font></font></p>
<p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt"><b>Proof
of Identity (PoI)</b></font></font></p>
<p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">Passport</font></font></p>
<p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">PAN
card</font></font></p>
<p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">Voter’s
identity card</font></font></p>

<p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt"><b>Proof
of Address (PoA)</b></font></font></p>
<p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">Passport</font></font></p>
<p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">Voter’s
identity card</font></font></p>
<p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">Telephone/Mobile
bill</font></font></p>
<p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">Bank
account/Credit Card statement</font></font></p>
<p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">Electricity
bill</font></font></p>
<p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">Ration
card</font></font></p>

<p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt"><b>2.
For bank accounts belonging to companies</b></font></font></p>
<p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt"><b>Proof
of Identity (PoI)</b></font></font></p>
<p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">PAN
Card of the Company</font></font></p>
<p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">VAT/TIN
(mandatory)</font></font></p>
<p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt"><b>Proof
of Address (PoA)</b></font></font></p>
<p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">Company
telephone bill</font></font></p>
<p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">Company
electricity bill</font></font></p>

<p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt"><b>3.
For bank accounts belonging to partnership firms</b></font></font></p>
<p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt"><b>Proof
of Identity (PoI)</b></font></font></p>
<p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">VAT/TIN
(mandatory)</font></font></p>

<p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt"><b>Proof
of Address (PoA)</b></font></font></p>
<p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">Telephone
bill in the name of firm/partners</font></font></p>
<p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">Electricity
bill in the name of firm/partners</font></font></p>

<p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt"><b>4.
For bank accounts belonging to trusts &amp; foundations</b></font></font></p>
<p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt"><b>Proof
of Identity (PoI)</b></font></font></p>
<p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">VAT/TIN
(mandatory)</font></font></p>
<p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt"><b>Proof
of Address (PoA)</b></font></font></p>
<p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">Telephone
bill</font></font></p>
<p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">Electricity
bill</font></font></p>

<p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt"><b>5.
For bank accounts belonging to sole proprietor</b></font></font></p>
<p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt"><b>Proof
of Identity (PoI)</b></font></font></p>
<p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">Passport</font></font></p>
<p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">PAN
card</font></font></p>
<p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">Voter’s
identity card</font></font></p>
<p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">VAT/TIN</font></font></p>
<p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt"><b>Proof
of Address (PoA)</b></font></font></p>
<p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">Telephone/Mobile
bill</font></font></p>
<p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">Bank
account/Credit card statement</font></font></p>
<p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">Electricity
bill</font></font></p>
<p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">Ration
card</font></font></p>

<ol start="2">
	<ol start="6">
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">2.6If
		a Vendor / Merchant / Seller intends to update/change his/her bank
		account details, he/she should intimate Agrosiaa of such a change
		at least 10 business days before the effective date after change in
		bank account. For processing payments after duly completing the KYC
		process, you may be required to provide verification documents
		again for the new bank account to avoid payment rejections and
		delays in reprocessing of payments. As part of the KYC process, you
		are required to provide from the above list, one valid document for
		proof of identity and one valid document for proof of address.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">2.7The
		Vendor / Merchant / Seller’s has declared that it shall conduct
		its transactions in such manner so as to strictly comply with and
		adhere to the policies of Agrosiaa and all applicable provisions of
		law governing sale of goods/ services. </font></font>
		</p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">2.8The
		Vendor / Merchant / Seller’s has declared that it shall strictly
		adhere to the shipping and delivery/ cancellation/ return/ refund
		policies as stated herein below and further amended and declared
		from time to time. </font></font>
		</p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">2.9The
		Vendor / Merchant / Seller’s has declared that it shall maintain
		adequate levels of security in order to ensure that no
		confidential/ proprietary information is obtained in any manner by
		any third party unconnected to the transaction, or not part of the
		necessary infrastructure for the completion of the contract/s as
		envisaged herein.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">2.10The
		Vendor / Merchant / Seller’s has declared that it shall take all
		adequate precautions as may be necessary to secure all personal
		financial details, including but not restricted to particulars such
		as passwords, account numbers or any such other relevant
		information, as per the directions of their bankers/ credit
		providers/ payment gateway, and is aware that Agrosiaa does not
		actively collect or retain the same.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">2.11The
		Vendor / Merchant / Seller’s has declared that it shall not
		attempt to gather copy or use any information contained on the said
		website, or its servers, or to use the same for any purpose
		prohibited by law, attempt to copy or display the same in violation
		of any law, or attempt in any manner to disturb the routine or
		smooth functioning of the website. The Vendor / Merchant / Seller’s
		has acknowledged that any breach of the said covenant shall amount
		to a penal offence under the provisions of the Information
		Technology Act, 2000, as amended from time to time, or any such
		other applicable provision of law.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">2.12The
		Vendor / Merchant / Seller’s acknowledge acceptance of the above
		terms and conditions are a prerequisite to the registration of the
		Vendor / Merchant / Seller’s and consequent display of its
		products on the said website.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">2.13Agrosiaa
		reserves the right to reject any Vendor / Merchant / Seller without
		any explanation. Listings of product by the Vendor shall be
		permitted by Agrosiaa post receipt of all documents as stated in
		this Policy.</font></font></p>
	</li></ol>
</ol>
<p style="margin-left: 1.27cm;  ">

</p>
<ol start="3">
	<li><p style=" "><b><font face=""><font style="font-size: 10pt">3) PASSWORD
	SECURITY</font></font></b></p>
</li></ol>
<p style="margin-left: 1.27cm;  ">

</p>
<p align="justify" style="margin-left: 1.27cm;  ">
<font face=""><font style="font-size: 10pt">Each
</font></font><font face=""><font style="font-size: 10pt">Vendor
/ Merchant / Seller</font></font><font face=""><font style="font-size: 10pt">
can access the site, use the Services, electronically sign Your
Transactions, and review your completed transactions by log in with a
password. </font></font><font face=""><font style="font-size: 10pt">Vendor
/ Merchant / Seller</font></font><font face=""><font style="font-size: 10pt">
are solely responsible for maintaining the security of their
password. You may not disclose your password to any third party
(other than third parties authorized by you to use your account) and
are solely responsible for any use of or action taken under your
password on this Site. If your password is compromised, you must
change your password.</font></font></p>
<p align="justify" style="margin-left: 1.27cm;  ">

</p>
<ol start="4">
	<li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt"><b>4) NECESSARY
	GOVERNMENT REGISTRATIONS</b></font></font></p>
</li></ol>

<ol start="4">
	<ol>
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">4.1Agrosiaa
		has placed explicit faith and reliance on the declaration given by
		the Vendor / Merchant / Sellers, that it has obtained all necessary
		registration/s including but not restricted to PAN / TAN / TIN /
		VAT / SERVICE TAX / GST/SHOP ACT, or any such other registration/s
		as required under the law of the land from time to time, and the
		same shall be kept renewed and updated as and when required.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">4.2Vendor
		/ Merchant / Sellers</font></font><font face=""><font style="font-size: 10pt">
		are required to submit their PAN / TAN / TIN / VAT / SERVICE TAX /
		GST details in order to comply with tax audits and to ensure a
		hassle-free tax assessment process. It is mandatory for the </font></font><font face=""><font style="font-size: 10pt">Vendor
		/ Merchant / Seller</font></font><font face=""><font style="font-size: 10pt">s
		to submit PAN / TAN / TIN / VAT / SERVICE TAX / GST details during
		registration and update the documents from time to time during
		their association with Agrosiaa.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">4.3Agrosiaa
		shall not be held liable or responsible in case such material
		particulars are not updated by the </font></font><font face=""><font style="font-size: 10pt">Vendor
		/ Merchant / Sellers</font></font><font face=""><font style="font-size: 10pt">,
		or for any direct or indirect consequence thereof.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">4.4PAN
		is mandatory for following reasons:</font></font></p>
    <ul>
    	<li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">In
    	order to comply with tax audits it is necessary for Agrosiaa to have
    	the PAN details of all </font></font><font face=""><font style="font-size: 10pt">Vendor
    	/ Merchant / Seller</font></font><font face=""><font style="font-size: 10pt">s
    	who give a commission of Rs.5000/- or more annually.</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">The
    	PAN details of all </font></font><font face=""><font style="font-size: 10pt">Vendor
    	/ Merchant / Sellers</font></font><font face=""><font style="font-size: 10pt">
    	doing business through the Website have to be compulsorily submitted
    	for the purpose of legal and audit compliance.</font></font></p>
    </li></ul>
	</li></ol>
</ol>

<ol start="4">
	<ol>
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">4.5Provisions
		of the policy:</font></font></p>
    <ul>
    	<li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">PAN
    	details now have to be compulsorily submitted. The accounts of
    	Vendor / Merchant / Vendor / Merchant / Sellers who fail to submit
    	the details within the stipulated time will be made pending. </font></font>
    	</p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Vendor
    	/ Merchant / Seller will be alerted through email and dashboard
    	messages about the deadlines for submitting these details.</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">PAN
    	/ TAN / TIN / VAT / SERVICE TAX / GST details have to be filled on
    	the dashboard and a copy of PAN card, TIN/VAT needs to be uploaded.</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">The
    	submission of TAN details is NOT mandatory, but is beneficial for
    	the Vendor / Merchant / Seller in order to streamline the TDS
    	process.  </font></font>
    	</p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Vendor
    	/ Merchant / Sellers who attempt to submit fake PAN / TAN / TIN /
    	VAT / SERVICE TAX / GST details will face stringent actions. </font></font>
    	</p>
    </li></ul>
	</li></ol>
</ol>

<p style="margin-left: 3.18cm; margin-bottom: 0cm; ">

</p>
<ol start="4">
	<ol start="6">
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">4.6All
		applicable direct or indirect taxes, arising out of placement or
		receipt of any orders or any sale transaction on the said website,
		and or at the time of the legally construed point of sale, shall be
		to the account of the respective Vendor / Merchant / Sellers.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">4.7The
		Vendor / Merchant / Sellers shall bear and directly pay any PAN /
		TAN / TIN / VAT / SERVICE TAX / GST or any such other tax by
		whatsoever name, levied by any appropriate authority, arising out
		of the sale of the goods/products/ provision of services; Agrosiaa
		shall not be responsible or liable for the same in any manner.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">4.8Agrosiaa
		shall not be liable for refund of excess direct or indirect taxes
		paid on behalf of the Vendor / Merchant / Sellers, or any refunds
		arising out of cancellation of any orders.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">4.9The
		parties shall bear and pay any taxes as may be levied on them
		directly by the concerned authorities, i.e., the parties shall bear
		any direct incidence of independent taxation.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">4.10The
		</font></font><font face=""><font style="font-size: 10pt">Vendor
		/ Merchant / Sellers</font></font><font face=""><font style="font-size: 10pt">
		has declared that it has obtained all necessary registrations,
		permissions and sanctions for the manufacture and sale of the
		goods/ products/ services as offered.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">4.11In
		case, at any point of time, it is necessary for Agrosiaa to effect
		any payments to any government authority against any applicable
		taxes, Agrosiaa shall be firstly entitled to deduct the same from
		the amount to be remitted to the </font></font><font face=""><font style="font-size: 10pt">Vendor
		/ Merchant / Sellers</font></font><font face=""><font style="font-size: 10pt">,
		or in the alternate to adjust the same against any other payments
		due to the </font></font><font face=""><font style="font-size: 10pt">Vendor
		/ Merchant / Sellers</font></font><font face=""><font style="font-size: 10pt">,
		or at its own discretion to recover the same subsequently.</font></font></p>
	</li></ol>
</ol>
<p style=" ">
</p>
<ol start="5">
	<li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt"><b>5) LISTING
	POLICY</b></font></font></p>
</li></ol>

<ol start="5">
	<ol>
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">5.1 To
		assist the Vendor / Merchant / Seller’s list their items on their
		Website correctly, we have highlighted some listing policies and
		described how Agrosiaa handles listing violations when they are
		reported.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">5.2 Vendor
		/ Merchant / Seller’s shall not be permitted to have multiple
		accounts on the Website. The restriction is being imposed in order
		to protect fraud against users at large. This policy is important
		to ensure standards and rules regarding performance, risk and best
		practices as are applicable for each Vendor / Merchant / Seller and
		shall result in ensuring a safe and positive experience on the
		Website. Further, buyers need to compare different items to make
		smart and informed purchasing decisions. The duplicate-listings
		policy is designed to ensure Vendor / Merchant / Seller’s don't
		list in a way that clutters the buying experience and hurts the
		overall marketplace. Vendor / Merchant / Seller’s can't have more
		than one listing of an identical item at the same time.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">5.3 Violations
		of this policy may result in a range of actions, including but not
		limited to the following:</font></font></p>
    <ul>
    	<li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">listing
    	cancellation;</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">limits
    	on account privileges;</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">account
    	suspension; and/or</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">loss
    	of special status.</font></font></p>
    </li></ul>
	</li></ol>
</ol>

<ol start="5">
	<ol start="4">
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">5.4 These
		restrictions on duplicate listings include listing an identical
		item in different categories or listing an identical item using
		different display names. Listings are considered duplicates if they
		are for items that have no significant differences between them. To
		avoid your listings from being treated as duplicates, make sure you
		clearly show the differences between items in the titles,
		descriptions, prices, photos, product IDs, item specifics, or parts
		compatibility areas of a listing. Agrosiaa may also look at other
		parts of the listing to determine whether the listing is duplicate.
		If the differences between the items you are selling are not
		obvious in the search results, your listings may be treated as
		duplicates and will be subject to the consequences outlined above.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt"><b>5.5 Proper
		Category Listing:</b></font></font></p>
	</li></ol>
</ol>
<p style="margin-left: 2.54cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">Items
must be listed in the appropriate category. Items that do not belong
to the category in which they are listed will be moved to the
appropriate category.</font></font></p>
<ol start="5">
	<ol start="6">
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt"><b>5.6 Exceptions:</b></font></font></p>
	</li></ol>
</ol>
<p style="margin-left: 2.54cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">Multiple-quantity
listings may offer a choice of color in otherwise identical items.
However, a Vendor / Merchant / Seller must be able to fulfil the
entire quantity of every listing in any offered color even if another
Vendor / Merchant / Seller chooses exactly the same color selection.
Choice listings may not be offered ‘subject to availability’ or
the buyer may be requested to contact the Vendor / Merchant / Seller
to see the available colors and quantity. This exception for choice
of color does not apply to single-quantity listings.</font></font></p>
<ol start="5">
	<ol start="7">
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt"><b>5.7 Duplicate
		Listings:</b></font></font></p>
	</li></ol>
</ol>
<p style="margin-left: 2.54cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">A
duplicate listing occurs when a Vendor / Merchant / Seller lists
identical items in multiple listings.</font></font></p>
<ol start="5">
	<ol start="8">
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt"><b>5.8 Maximum
		Permissible Logistic Charges:</b></font></font></p>
	</li></ol>
</ol>
<p style="margin-left: 2.54cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">A
Vendor / Merchant / Seller shall not charge buyers for logistics in
excess of the fees prescribed.</font></font></p>
<ol start="5">
	<ol start="9">
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">5.9 The
		Vendor / Merchant / Seller’s has declared that it has obtained
		all necessary registrations, permissions and sanctions for the
		manufacture and sale of the goods/ products/ services as offered.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">5.10 The
		Vendor / Merchant / Seller’s has declared that there is no
		restriction, prohibition or prior permission required on the part
		of the Vendor / Merchant / Seller’s under any applicable law for
		the sale of the goods/ products/ services as listed on the website,
		and in case any such restriction/s/ or prohibitions exist and prior
		permissions are required, the same are already obtained or shall be
		obtained and provided to AGROSIAA as and when required, and
		assuredly prior to effecting any sale of any such goods/ products/
		services.</font></font></p>
	</li></ol>
</ol>
<p align="justify" style="margin-left: 1.27cm; margin-bottom: 0cm; ">

</p>
<ol start="6">
	<li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt"><b>6) FEE
	POLICY</b></font></font></p>
</li></ol>

<ol start="6">
	<ol>
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">6.1 Agrosiaa
		collects the amount, including the item and shipping price, paid by
		a buyer for any transaction made. Vendor / Merchant /Seller’s may
		not charge buyers an additional fee for their use of ordinary forms
		of payment, including electronic transfers or credit cards. Such
		costs should be built into the price of the item.&nbsp; This policy
		reduces the potential for confusion among buyers about the true
		cost of an item. Further, some forms of payment surcharges, such as
		credit card surcharges, are prohibited by law or under the issuing
		institution's rules and regulations for merchants</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">6.2 Agrosiaa
		shall receive the entire amount of consideration, and retain the
		same till the end of the period as specified in the refund</font></font><font face=""><font style="font-size: 10pt">
		policy. Thereafter, at the end of the specified period, and in case
		there is no demand for refund by the Buyer, Agrosiaa shall pay the
		sale price, less permissible deductions such as applicable taxes
		and agreed commission/s.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">6.3 The
		said percentage commission shall be levied on the actual sale
		price, exclusive of taxes, and shall be to the account of and
		payable by the Vendor / Merchant /Seller as specified herein, and
		at such rates as may be mutually agreed to by and between the
		parties and revised from time to time.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">6.4 Agrosiaa
		deducts as selling commission (a percentage) of the item price,
		service tax on the commission percentage and shipping charges.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">6.5 Agrosiaa
		reserves the right to issue a warning to you or
		temporarily/indefinitely suspend/ terminate your account on the
		Website and/or refuse to provide you with access to the Website in
		case of non-payment of fees/processing of refunds by you to
		Agrosiaa. Agrosiaa also reserves the right to take legal action in
		case of non-payment of fees/dues by you to Agrosiaa.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">6.6 Agrosiaa
		will invoice the Vendor / Merchant / Seller 3 (three) times in a
		month, i.e. 10th, 20th and 28</font></font><sup><font face=""><font style="font-size: 10pt">th</font></font></sup><font face=""><font style="font-size: 10pt">
		/ 30</font></font><sup><font face=""><font style="font-size: 10pt">th</font></font></sup><font face=""><font style="font-size: 10pt">
		/31</font></font><sup><font face=""><font style="font-size: 10pt">st</font></font></sup><font face=""><font style="font-size: 10pt">.
		Payments towards such invoices shall be deducted by Agrosiaa from
		the Vendor / Merchant / Seller remittances or if there is no amount
		in the credit of Vendor / Merchant / Seller account, in such event,
		Vendor / Merchant /Seller shall be liable to pay such Fee to
		Agrosiaa within 7 days from the date of the invoice.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">6.7 Parties
		expressly agree that any/all remittance/settlement related pay-outs
		shall be deemed to have been accepted by Vendor / Merchant /Seller,
		if Vendor / Merchant /Seller does not furnish a written objection
		specifying the nature of the dispute within ninety (90) days from
		the date of transaction.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">6.8 Settlement
		of Accounts shall be effected by the parties on a daily / weekly /
		monthly basis, and grievance if any is to be raised within the same
		accounting year, failing which the parties shall be deemed to have
		accepted the accounts.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">6.9 Any
		disputes pertaining to the same shall be resolved by the parties in
		the manner as specified hereinafter.</font></font></p>
	</li></ol>
</ol>
<p style="margin-left: 1.27cm;  ">

</p>
<ol start="7">
	<li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt"><b>7) SHIPPING
	AND DELIVERY</b></font></font></p>
</li></ol>
<p style="margin-left: 1.27cm; margin-bottom: 0cm; ">

</p>
<ol start="7">
	<ol>
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">7.1 All
		orders received by the Vendor / Merchant / Seller will only be
		shipped through Registered Logistics Partners of Agrosiaa.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">7.2 Upon
		receipt of the Order and processing the same by Vendor / Merchant /
		Seller, the Logistic Partner of Agrosiaa shall pick up the item for
		delivery to the Ultimate Customer. The details shipping and
		delivery policy is stated in para 17.</font></font></p>
	</li></ol>
</ol>
<p style="margin-left: 1.27cm; margin-bottom: 0cm; ">

</p>
<ol start="8">
	<li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt"><b>8) MISLEADING
	TITLE POLICY</b></font></font></p>
</li></ol>
<p align="justify" style="margin-left: 2.54cm; margin-bottom: 0cm; ">

</p>
<ol start="8">
	<ol>
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">8.1 Your
		title should accurately describe only the actual item or items you
		offer/list for sale. The title shall be considered as misleading
		and hence prohibited from being listed/offered on the Website if:</font></font></p>
    <ul>
    	<li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">your
    	listing/title contains a word or phrase that does not seem to belong
    	to the item listed/offered for sale; and</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">a
    	buyer cannot identify the item that has been listed/offered for
    	sale.</font></font></p>
    </li></ul>
	</li></ol>
</ol>


<ol start="8">
	<ol>
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">8.2 Violations
		of this policy may result in a range of actions, including but not
		limited to the following:</font></font></p>
    <ul>
    	<li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Listing
    	Cancellation;</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Limits
    	placed on account privileges;</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Loss
    	of special status; and/or</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Account
    	Suspension</font></font></p>
    </li></ul>
	</li></ol>
</ol>

<p style=" ">
</p>
<ol start="9">
	<li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt"><b>9) PROHIBITED
	&amp; RESTRICTED ITEMS</b></font></font></p>
</li></ol>

<ol start="9">
	<ol>
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">9.1 Before
		a Vendor / Merchant / Seller lists an item, the Vendor / Merchant /
		Seller needs to find out if the item is allowed to be sold on the
		Website and if the type of item is subject to certain restrictions,
		to avoid potential issues with seller listing. As a Vendor /
		Merchant / Seller, you are ultimately responsible for making sure
		that selling an item is legal under applicable laws.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">9.2 Violations
		of this Policy may result in a range of actions, including but not
		limited to the following:</font></font></p>
    <ul>
    	<li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">listing
    	cancellation;</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">limits
    	on account privileges;</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">account
    	suspension; and/or</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">loss
    	of special status</font></font></p>
    </li></ul>
	</li></ol>
</ol>


<ol start="9">
	<ol start="3">
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">9.3 When
		Policy violations occur, Agrosiaa may email the Vendor / Merchant /
		Seller as well as the buyer that a listing has been ended. You may
		contact Agrosiaa to report violations by sending an email. </font></font>
		</p>
	</li></ol>
</ol>

<ol start="9">
	<ol start="4">
		<li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt"><b>9.4 Rules
		on Prohibited and Restricted Items</b></font></font></p>
	</li></ol>
</ol>
<p style="margin-left: 2.54cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">Policies
about listing items are generally guided by the applicable law in
force. However, many items that are not necessarily prohibited by law
have been termed as prohibited and restricted in the list below as
they may involve the sale of dangerous or sensitive items. The
limitations are a result of the input by numerous stakeholders,
including the community.</font></font></p>

<p style="margin-left: 2.54cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">It
is also important to respect third-party intellectual property rights
and Vendor / Merchant / Sellers must have legal rights to sell
products they list.</font></font></p>

<ol start="9">
	<ol>
		<li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt"><b>9.5 Prohibited
		and Restricted Items List:</b></font></font></p>
    <ul>
    	<li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Adult
    	Material </font></font>
    	</p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Alcohol,
    	Wine &amp; Liquor</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Animal
    	&amp; Wildlife products or hides/skins/teeth, nails and other parts
    	etc. of animals</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Antiques
    	&amp; Artifacts</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Beta
    	Software</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Bootleg
    	/ Pirated Software</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Catalogue
    	&amp; URL sales</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Used
    	Clothing</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Compilation
    	&amp; Information Media</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Contact
    	Information and User databases</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Contracts</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Copyrights</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Currency
    	&amp; Stamps</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Downloadable
    	media (Unless expressly permitted by Agrosiaa)</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Drugs</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Electronic
    	Surveillance Equipment</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Event
    	Tickets</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Firearms,
    	Ammunition, Militaria &amp; Weapons</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Explosives
    	&amp; Explosive substances</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Games
    	Software</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Government
    	ID’s and Licenses</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Hazardous
    	&amp; restricted products</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Human
    	parts &amp; remains</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Importation
    	of Products - examples include CDs that were intended only for
    	distribution in a certain country</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Items
    	encouraging illegal activities</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Misleading
    	titles</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Movie
    	prints</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Offensive
    	material</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Police,
    	Army, Navy &amp; Air Force related items</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Prohibited
    	services</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Promotional
    	items</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Real
    	Estate</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Replica
    	&amp; counterfeit items</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Ringtones</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Stocks
    	&amp; other Securities</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Stolen
    	property</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Surveillance
    	equipment’s</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Tobacco
    	&amp; related products</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Trademarks
    	&amp; Patents</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Travel</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Cable
    	descramblers and black boxes which includes devices intended to
    	obtain cable and satellite signals for free</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Copyright
    	unlocking devices which includes mod chips or other devices designed
    	to circumvent copyright protection</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Drug
    	test circumvention aids which includes drug cleansing shakes, urine
    	test additives, and related items</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Gaming/gambling
    	which includes lottery tickets, sports bets, memberships/ enrolment
    	in online gambling sites, and related content</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Hacking
    	and cracking materials which includes manuals, how-to guides,
    	information, or equipment enabling illegal access to software,
    	servers, watomites, or other protected property</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Illegal
    	products which includes materials, products, or information
    	promoting illegal products or enabling illegal acts</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Miracle
    	cures which include unsubstantiated cures, remedies or other items
    	marketed as quick health fixes</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Offensive
    	products which includes literature, products or other materials
    	that:</font></font></p>
    	<ul>
    		<li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Defame
    		or slander any person or groups of people based on race, ethnicity,
    		national origin, religion, sex, or other factors b) Encourage or
    		incite violent acts c) Promote intolerance or hatred</font></font></p>
    	</li></ul>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Prescription
    	drugs or herbal drugs or any kind of online pharmacies which
    	includes&nbsp;drugs or other products requiring a prescription by a
    	licensed medical practitioner.</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Pyrotechnic
    	devices and hazardous materials which includes fireworks and
    	related&nbsp;products; toxic, flammable, and radioactive materials
    	and substances</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Regulated
    	products which includes air bags; batteries containing mercury;
    	freon or&nbsp;similar substances/refrigerants, chemical/industrial
    	solvents, government uniforms, car titles or logos, license plates,
    	police badges and law enforcement equipment, lock-picking devices,
    	pesticides; postage meters, recalled items, slot machines,
    	surveillance equipment; products regulated by government or other
    	agency specifications</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Securities,
    	which includes stocks, bonds, or related financial products</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Tobacco
    	and cigarettes which includes cigarettes, cigars, chewing tobacco,
    	and&nbsp;related products</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Traffic
    	devices which includes radar detectors/ jammers, license plate
    	covers, traffic&nbsp;signal changers, and related products</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Wholesale
    	currency which includes discounted currencies or currency exchanges</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Multi-Level
    	marketing collection fees</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Matrix
    	sites or sites using a matrix scheme approach</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Work-at-home
    	information</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Drop-shipped
    	merchandise</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Any
    	product or service which is not in compliance with all applicable
    	laws and&nbsp;regulations whether federal, state, local or
    	international including the laws of India.</font></font></p>
    </li></ul>
	</li></ol>
</ol>

<p style=" ">
</p>
<ol start="10">
	<li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt"><b>10) DISPLAY
	NAME</b></font></font></p>
</li></ol>

<ol start="10">
	<ol>
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">10.1 A
		display name is a unique name that sellers use to identify
		themselves on the Website. At the time of registration, Vendor /
		Merchant / Seller’s should choose a display name that they like
		and will remember. Display names shall not be profane, obscene or
		violate any Agrosiaa’s or third-party’s trademarks, copyrights
		and intellectual property rights. Furthermore, display names shall
		not be or shall not represent an email address or web address
		(URL).</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">10.2 Further,
		Agrosiaa may, at its sole discretion, disallow certain words and
		phrases (including combinations and grammatical variations thereof)
		from being used as display names. Vendor / Merchant / Seller’s
		shall not be permitted to use Agrosiaa and any other trademarks or
		logos (including combinations and grammatical variations of such
		words) which are proprietary to Agrosiaa.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">10.3 Violations
		of this policy may result in a range of actions, including but not
		limited to the following:</font></font></p>
    <ul>
    	<li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">listing
    	cancellation;</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">limits
    	placed on account privileges;</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">loss
    	of special status; or/and</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">account
    	suspension.</font></font></p>
    </li></ul>
	</li></ol>
</ol>

<p style="margin-left: 1.27cm;  ">

</p>
<ol start="11">
	<li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt"><b>11) LINKS
	POLICY</b></font></font></p>
</li></ol>

<ol start="11">
	<ol>
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">11.1 The
		Website’s listing page can only be used to describe, promote and
		facilitate the sale of the listed Agrosiaa items. The page cannot
		refer to or promote a Vendor / Merchant / Seller’s individual
		website, off-Agrosiaa sales, or other businesses.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">11.2 Item
		Page&nbsp;can contain no URLs, links to, or promotional information
		about any off-Agrosiaa webpage, including the websites of a seller
		or any third party. Links from the Website’s item page that
		interfere in any way with the shopping experience on the Website or
		solicit any Agrosiaa user information, are not allowed. Links from
		the Website’s item page to pages that promote off-Agrosiaa sales
		in any way are forbidden.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt"><b>11.3 Seller
		Page</b></font></font><font face=""><font style="font-size: 10pt">:&nbsp;This
		page may be used to describe a Vendor / Merchant / Seller’s
		business and may not contain URLs or links to the Vendor / Merchant
		/ Seller’s individual website. It may not specifically promote
		off-Agrosiaa sales or sales of items prohibited on Agrosiaa nor may
		it contain links to commercial websites where products from
		multiple sellers are aggregated by a common search engine.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">11.4 Giveaways,
		Raffles, and Prizes:&nbsp;Listings that promote giveaways, random
		drawings or prizes as an enticement for buyers are not permitted on
		the Website as these promotions are highly regulated and may be
		unlawful. These types of listings are not permitted and will be
		ended. Agrosiaa itself may run such promotions on the Website and
		grant authorization to its partners or third-party companies to run
		promotions that comply with applicable laws.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">11.5 Listing
		techniques that circumvent Agrosiaa's fee structure</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">11.6 Vendor
		/ Merchant / Seller’s may not use systems or techniques to
		circumvent Agrosiaa’s fees. Some examples include:</font></font></p>
    <ul>
    	<li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Offering
    	in a listing, the opportunity to purchase the item or other
    	merchandise outside the Website;</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Listing
    	with low prices but unreasonably high shipping or handling costs;</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Listing
    	an item that requires or offers an additional purchase;</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Dutch
    	Avoidance -- listing a single item and offering additional identical
    	items for sale in the item description. In these situations, a
    	Vendor / Merchant / Seller’s typically instructs buyers to
    	indicate the number of items they want and states that they can get
    	the same price as the item in the listing;</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Listing
    	with an email address or domain name in the title;</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Catalogue
    	Sales -- listing of catalogues from which buyers may directly order
    	items is prohibited. In these situations, a Vendor / Merchant /
    	Seller’s will typically offer the catalogue for low-bid prices and
    	complete sales outside the Website for items found in the catalogue;
    	and/or</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Offering
    	items for sale in a manner that circumvents Agrosiaa’s fees;</font></font></p>
    </li></ul>
	</li></ol>
</ol>
<p style="margin-left: 2.54cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">Listings
that circumvent Agrosiaa’s fees are not permitted and will be
ended. Disciplinary action may result in indefinite/temporary
suspension of a Vendor / Merchant / Seller’s account or a formal
warning. Agrosiaa will consider the circumstances of an alleged
offence and the Vendor / Merchant / Seller’s trading records before
taking action.</font></font></p>

<ol start="11">
	<ol start="7">
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt"><b>11.7 Repeat
		Offences</b></font></font></p>
	</li></ol>
</ol>
<p align="justify" style="margin-left: 2.54cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">If
a Vendor / Merchant / Seller repeatedly lists items in a manner that
violates the policies described above, the Vendor / Merchant / Seller
is subject to suspension from the Website.</font></font></p>
<p style=" ">
</p>
<ol start="12">
	<li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt"><b>12) BRAND
	NAME &amp; RESTRICTED WORD</b></font></font></p>
</li></ol>


<ol start="12">
	<ol>
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">12.1 The
		Vendors / Merchants / Seller’s has declared that it is well and
		sufficiently entitled to the use of the tradename/s trademark/s, or
		such other tradename/ trademark as sought to be displayed on the
		said website on behalf of the Vendors / Merchants / Seller’s</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">12.2 The
		Vendors / Merchants / Seller’s has declared that there is no
		restriction, prohibition or prior permission required on the part
		of the Vendors / Merchants / Seller’s under any applicable law
		for the sale of the goods/ products/ services as listed on the
		website, and in case any such restriction/s/ or prohibitions exist
		and prior permissions are required, the same are already obtained
		or shall be obtained and provided to Agrosiaa as and when required,
		and assuredly prior to effecting any sale of any such goods/
		products/ services.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">12.3 Vendors
		/ Merchants / Seller’s are not permitted to include any brand
		names or company logos in their listings other than the specific
		brand name authorised by the original manufacturer to be used for
		products being sold by sellers under a particular listing. Certain
		uses of brand names may also constitute trademark infringement and
		could expose sellers to legal liability.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">12.4 Further,
		Vendors / Merchants / Seller’s shall not be permitted to use the
		following words or phrases in their display names or listings</font></font></p>
	</li></ol>
</ol>
<p align="justify" style="margin-left: 2.54cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">Agrosiaa
(including all cognate &amp; grammatical variations thereof);&nbsp;</font></font></p>
<ol start="12">
	<ol start="5">
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">12.5 Warning:
		&nbsp;Violation of this policy may result in listing cancellation
		or suspension of account.</font></font></p>
	</li></ol>
</ol>


<ol start="13">
	<li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt"><b>13) BREACH
	OF MRP </b></font></font>
	</p>
</li></ol>

<ol start="13">
	<ol>
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">13.1 Agrosiaa
		supports the MRP mentioned by the respected manufacturer of the
		product. The MRP Breach Policy states that a Vendor / Merchant /
		Seller cannot mention a higher selling price on the Website than
		that mentioned on the MRP label of the product.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">13.2 Agrosiaa
		encourages sellers to adhere to the rules and regulations laid out
		by the Government of India. As per the laws of India, a product
		cannot be sold at a higher price than the MRP (Maximum Retail
		Price) stated on the box. This rule is relaxed only in the cases of
		service industry like restaurants or hotels.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		 <font face=""><font style="font-size: 10pt">13.3 Sellers
		who sell products at a higher price than the MRP will be suspended
		and further blacklisted. Agrosiaa will show zero tolerance towards
		any instances wherein the seller has sold the product above the MRP
		stated on the label. Strict action will be taken against sellers
		who list selling price on the Website that is higher than the MRP
		stated on the MRP declaration label of the product.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">13.4 The
		maximum penalty for contravention of any of the provisions of the
		Legal Metrology Act, 2009 and the Legal Metrology (Packaged
		Commodities Rules), 2011 is INR 25,000/- for the nominated person
		and the firm/company as the case may be for the first offence.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">13.5 Thus,
		it is essential for all sellers to adhere to the rules and
		regulations stated by the Government of India to smoothen their
		business flow and at the same time retain the trust of loyal
		customers.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">13.6 Mandatory
		provisions to be included on the MRP declaration label of a product
		as per Government of India rules and regulations:</font></font></p>
    <ul>
    	<li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">The
    	name and address of the manufacturer, the name and address of both
    	the manufacturer and the packer (when they are not same) or the name
    	and address of the importer.</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">The
    	common or generic name of the commodity.</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Net
    	quantity contained in the package (excluding the weight of any
    	packing material).</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Month
    	and year of manufacture or import.</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">The
    	retail sale price.</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">The
    	name of the office, address, telephone no and e mail id (optional)
    	of the person who can be or the office which can be contacted, in
    	case of consumer complaints</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">The
    	above mentioned provisions are mandatory for all products
    	manufactured for retail sale. Further note, that only those labels,
    	</font></font><font face=""><font style="font-size: 10pt">which
    	are securely affixed on the container are acceptable.</font></font></p>
    </li></ul>
	</li></ol>
</ol>

<ol start="13">
	<ol>
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">13.7 Please
		click on the given links to refer to the websites with the rules
		and regulations stated by the Government of India listing out the
		mandatory declarations which are to be pasted on the product in
		detail:</font></font></p>
    <ul>
    	<li><p align="justify" style="margin-bottom: 0cm; ">
    	<a href="http://www.cifti.org/Reports/Legal%20Metrology_CIFTI%20Workshop%2015June,2012.pdf"><font face=""><font style="font-size: 10pt">Government
    	of India Rules and Regulations</font></font></a></p>
    	</li><li><p align="justify" style="margin-bottom: 0cm; ">
    	<font face=""><font style="font-size: 10pt"><a href="http://metrologycentre.com/codes/pc1.html">Retail
    	Packages and their Legal Requirements</a>&nbsp;</font></font></p>
    </li></ul>
	</li></ol>
</ol>
<ol start="13">
	<ol>
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">13.8 Following
		are the cases in which the MRP Breach Policy is applicable:</font></font></p>
    <ul>
    	<li><p align="justify" style="margin-bottom: 0cm; ">
    	<font face=""><font style="font-size: 10pt">Pricing
    	your product above the MRP on the Website.</font></font></p>
    </li>
    	<li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Charging
    	the buyer more than the MRP mentioned on the label of the product.</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Charging
    	an additional fee in the form of taxes over and above the MRP. This
    	is prohibited as MRP mentioned is all inclusive of taxes.</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Labels
    	without mandatory declarations as prescribed by the Government of
    	India.</font></font></p>
    </li></ul>
	</li></ol>
</ol>
<ol start="13">
	<ol>
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">If
		the </font></font><font face=""><font style="font-size: 10pt">Vendor
		/ Merchant / S</font></font><font face=""><font style="font-size: 10pt">eller
		is found guilty of violating the MRP Policy, the following actions
		will be taken:</font></font></p>
    <ul>
    	<li><p align="justify" style="margin-bottom: 0cm; ">
    	<font face=""><font style="font-size: 10pt">Persistent
    	defaulters will be blacklisted and will be liable for breach under
    	the Legal Metrology Act, 2009 and the Legal Metrology (Packaged
    	Commodities) Rules, 2011. Please be informed that Agrosiaa will not
    	bear any responsibility for the repercussions faced by you for
    	violating the applicable laws. One instance is equivalent to a
    	breach made for one or more SKUs.</font></font></p>
    </li></ul>
	</li></ol>
</ol>

<p style="margin-left: 1.27cm; margin-bottom: 0cm; ">

</p>
<ol start="14">
	<li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt"><b>14) INVOICE
	ADHERENCE POLICY</b></font></font></p>
</li></ol>
<p style="margin-left: 1.27cm; margin-bottom: 0cm; ">

</p>
<ol start="14">
	<ol>
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">14.1 Invoice
		Generation is an essential part of the order cycle. </font></font>
		</p>
    <ul>
    	<li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">It
    	is mandatory for a Vendor / Merchant / Seller to print three copies
    	of an invoice:</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">For
    	the Buyer / Customer;</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">For
    	the Logistics Partner; and </font></font>
    	</p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">For
    	the Vendor / Merchant / Seller’s record.</font></font></p>
    </li></ul>
	</li></ol>
</ol>

<p style="margin-left: 1.27cm; margin-bottom: 0cm; ">

</p>
<ol start="14">
	<ol>
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">14.2 The
		reasons for invoice attachment along with the orders are:-</font></font></p>
    <ul>
    	<li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">For
    	future references or requirements.</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">For
    	warranty / guarantee / statutory requirements.</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">For
    	shipment during transit</font></font></p>
    </li></ul>
	</li></ol>
</ol>
<ol start="14">
	<ol start="3">
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">14.3 Agrosiaa
		has the right to issue warning to the Vendor / Merchant / Seller,
		if the invoice adherence policy is not maintained. This will result
		in the deactivation of Vendor / Merchant / Seller’s account for a
		period of one week.</font></font></p>
	</li></ol>
</ol>
<p style=" ">
</p>
<ol start="15">
	<li><p align="justify" style="margin-bottom: 0cm; ">
	<b><font face=""><font style="font-size: 10pt">15) SELLER
	TAXES</font></font></b><b><font face=""><font style="font-size: 10pt">.
	</font></font></b>
	</p>
</li></ol>
<p align="justify" style="margin-left: 1.27cm; margin-bottom: 0cm; ">

</p>
<p align="justify" style="margin-left: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">You
agree that it is the your responsibility to determine whether Seller
Taxes apply to the transactions and to collect, report, and remit the
correct Seller Taxes to the appropriate tax authority, and that
Agrosiaa is not obligated to determine whether Seller Taxes apply and
is not responsible to collect, report, or remit any sales, use, or
similar taxes arising from any transaction, except to the extent
Agrosiaa expressly agrees to receive taxes or other transaction-based
charges in connection with tax calculation services made available by
Agrosiaa and used by Seller. "Seller Taxes" means any and
all sales, goods and services, use, excise, import, export, value
added, consumption and other taxes and duties assessed, incurred or
required to be collected or paid for any reason in connection with
any advertisement, offer or sale of products by you on or through the
Site, or otherwise in connection with any action, inaction or
omission of you or any of affiliate of yours, or any of your or their
respective employees, agents, contractors or representatives</font></font></p>

<ol start="16">
	<li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt"><b>16) RETURN
	POLICY</b></font></font></p>
</li></ol>

<ol start="16">
	<ol>
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">16.1 Agrosiaa
		considers that customer satisfaction is of paramount importance,
		and accordingly specific provision is made for the cancellation of
		any order/s, return of any goods/ products, and refund of any
		amounts paid.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">16.2 Agrosiaa
		states that the terms and conditions on which an order once placed
		may be cancelled or changed, vary from Vendor / Merchant / Seller
		to Vendor / Merchant / Seller, and all effort is made to ensure
		that the Buyer is made aware of the specific cancellation and
		refund procedures and practices, and term and conditions thereof,
		as may be laid down by the Vendor / Merchant / Seller’s.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt"><b>16.3 Return
		/ Replacement / Refund Policy</b></font></font></p>
	</li></ol>
</ol>
<p style="margin-left: 2.54cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">All
products sold on Agrosiaa should be brand new and 100% genuine.
Agrosiaa’s Return Policy covers the buyer against ‘damaged’,
‘mis-shipped’, ‘defective’ and ‘not as described’
products.</font></font></p>
<p style="margin-left: 2.54cm; margin-bottom: 0cm; ">

</p>
<ol>
  <li><p style="margin-left: 2.54cm;margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">Buyers
can raise a request for return, replacement or refund within the
return guarantee period post order delivery which is as follows:</font></font>
<ul style="margin-left: 2.54cm;">
	<li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Seeds
	– 3 days</font></font></p>
	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Agro
	Chemicals – 3 days</font></font></p>
	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Irrigation
	– 7 days</font></font></p>
	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Allied
	Products – 7 days</font></font></p>
	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Tools
	&amp; Machinery – 7 days</font></font></p>
	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Garden
	– 7 days</font></font></p>
	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Organic
	Products – 3 days</font></font></p>
</li></ul>
</li>
</ol>
</p>

<ol start="16">
	<ol start="4">
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt"><b>16.4 Managing
		Buyer Returns:</b></font></font></p>
	</li></ol>
</ol>
<p align="justify" style="margin-left: 2.54cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">Agrosiaa
offers a streamlined process for returns management for smooth flow
of returns for both buyers and Vendor / Merchant / Seller’s. We
will notify you through email in case a buyer requests return of a
product. Buyer will state the reason for return (in some cases will
also attach images). If buyer’s return does not fit the parameters
specified on Agrosiaa, returns can be rejected.</font></font></p>
<ol start="16">
	<ol start="5">
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt"><b>16.5 Return
		Request Raised</b></font></font></p>
	</li></ol>
</ol>
<ol>
  <li><p style="margin-left: 2.54cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">A
buyer can raise a return request via Agrosiaa buyer support (via Toll
free no.) or directly using self-serve on the Website. You will
receive an email notification when a return request is raised or a
return is created by the buyer and you can view the same under all
returns on the ‘Returns Dashboard’. On the Returns Dashboard, you
will find the following return details:</font></font>
<ul style="margin-left: 2.54cm;">
	<li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Order
	summary</font></font></p>
	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Status,
	quantity &amp; price</font></font></p>
	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Return
	request date and respond by date</font></font></p>
	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Buyer
	details&nbsp;</font></font></p>
</li></ul>
</p></li>
</ol>
<ol start="16">
	<ol start="6">
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt"><b>16.6 Return
		Authorization and Troubleshooting</b></font></font></p>
	</li></ol>
</ol>
<p style="margin-left: 2.54cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">Return
authorization will managed by Agrosiaa for all kinds of return
requests. This includes validation of return request and the approval
of genuine return cases. &nbsp;</font></font></p>
<ol start="16">
	<ol start="7">
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt"><b>16.7 Accepted
		Return:</b></font></font></p>
	</li></ol>
</ol>
<ol></li><p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">One
of the following will be chosen while accepting a return from buyer:</font></font></p>
<ul style="margin-left: 2.54cm;">
	<li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Send
	buyer the new product and get old shipment back</font></font></p>
	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Refund
	buyer and get old shipment back.&nbsp;</font></font></p>
</li></ul>
</li>
</ol>
<ol start="16">
	<ol start="8">
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt"><b>16.8 Buyer
		Returns (RVP)</b></font></font></p>
	</li></ol>
</ol>
<p style="margin-left: 2.54cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">Buyer
returns can be created by the buyer after the product is delivered
successfully. Buyer returns can be one of the three types listed
below depending on the case.</font></font></p>
<ol start="16">
	<ol start="9">
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt"><b>16.9 Buyer
		wants a replacement:</b></font></font></p>
	</li></ol>
</ol>
<p style="margin-left: 2.54cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">If
the buyer has received an item in a 'Damaged' or 'Defective'
condition or it is 'Not as Described' by the Vendor / Merchant /
Seller, he/she can request a replacement at no extra cost.
Replacement is subject to availability of stock with the Vendor /
Merchant / Seller. If the product is out of stock, a refund will be
provided to the buyer, no questions asked.</font></font></p>
<ol start="16">
	<ol start="10">
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt"><b>16.10 Buyer
		refund:</b></font></font></p>
	</li></ol>
</ol>
<p style="margin-left: 2.54cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">Under
Return policy, a refund can be provided to a buyer if the buyer
doesn’t want the product or if the requested replacement cannot be
done due to product unavailability with the Vendor / Merchant /
Seller.</font></font></p>
<ol start="16">
	<ol start="11">
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt"><b>16.11 Courier
		Returns (RTO)</b></font></font></p>
	</li></ol>
</ol>
<p style="margin-left: 2.54cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">Courier
returns are returns which happen before the delivery of the product
to the buyer. Following are the possible scenarios where RTO due to
courier returns happen.</font></font></p>
<ol start="16">
	<ol start="12">
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt"><b>16.12 Buyer
		Not Reachable</b></font></font></p>
	</li></ol>
</ol>
<p style="margin-left: 2.54cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">If
buyer is not reachable after 2 attempts by Logistics Partner’s
attempt to deliver the package, customer support creates a RTO
(return to origin) in the system of the Logistics Partner and the
order would be considered cancelled. If buyers still want to purchase
the item, he/she should place a new order. After receiving the
trigger from&nbsp;Customer Support, the Logistics Partner will return
the package to the Vendor / Merchant / Seller.&nbsp;If&nbsp;Logistics
Partner returns the package as an undeliverable package, a full
refund will be automatically issued to the buyer.     </font></font>
</p>
<ol start="16">
	<ol start="13">
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt"><b>16.13 Buyer
		Cancellation</b></font></font></p>
	</li></ol>
</ol>
<p style="margin-left: 2.54cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">Buyer
may request to cancel the order and an order can be cancelled before
it reaches the buyer.&nbsp;This creates an RTO in the system of the
Logistics Partner and the Logistics Partner will return the package
to the Vendor / Merchant / Seller. If you fail to ship the products
within the prescribed time limit, buyer has a higher chance of
cancelling the order.</font></font><font face=""><font style="font-size: 10pt"><b>&nbsp;</b></font></font></p>
<ol start="16">
	<ol start="14">
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt"><b>16.14 Charges
		on Returns</b></font></font></p>
	</li></ol>
</ol>
<p style="margin-left: 2.54cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">Return
created due to reasons including ‘damaged’, ‘manufacturing
defects’, ‘size/colour exchange’ and ‘buyer ordered product
by mistake’ cases would be addressed under this policy. &nbsp;Under
these scenarios:</font></font>
<ul style="margin-left: 2.54cm;">
	<li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Commission
	charged to you on sale will be reimbursed in case of refund.</font></font></p>
	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Forward
	shipping fee will be waived for replacement/exchange.</font></font></p>
</li></ul>
</p>
<p style="margin-left: 2.54cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">Commission
and forward shipping charges will not be waived if the returns are
the due to the fault of Vendor / Merchant / Seller. This includes
cases&nbsp;such as missing items, mis-shipment, item not as
described, fake/expired products and used products. In these cases
you’ll be charged for return shipping charges and commission will
not be reimbursed.</font></font></p>
<ol start="16">
	<ol start="15">
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt"><b>16.15 Product
		Return Conditions</b></font></font></p>
	</li></ol>
</ol>
<p style="margin-left: 1.27cm; text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">For
following products returns will not be possible for buyers:</font></font></p>
<ul style="margin-left: 2.54cm;">
	<li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Any
	consumable item which has been used or installed</font></font></p>
	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Items
	that are returned without original packaging, freebies or
	accessories</font></font></p>
	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Products
	with tampered or missing serial numbers</font></font></p>
	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Defective
	products which are covered under the manufacturer's warranty</font></font></p>
	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Product
	damaged because of use</font></font></p>
	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Product
	received is not in the same condition as seller shipped to the buyer</font></font></p>
	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Return
	request is made outside the specified time frame</font></font></p>
</li></ul>

<p style="margin-left: 2.54cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">Following
are few of the parameters specifying the item condition that should
be complied with by the buyer for return:</font></font></p>
<center>
	<table width="500" cellpadding="5" cellspacing="1">
		<colgroup><col width="108">
		<col width="367">
		</colgroup><tbody><tr>
			<td width="108" bgcolor="#ffffff" style="border: 3.00pt double #00000a; padding-top: 0.13cm; padding-bottom: 0.13cm; padding-left: 0.26cm; padding-right: 0.13cm">
				<p><font face=""><font style="font-size: 10pt"><b>Category</b></font></font></p>
			</td>
			<td width="367" bgcolor="#ffffff" style="border: 3.00pt double #00000a; padding-top: 0.13cm; padding-bottom: 0.13cm; padding-left: 0.26cm; padding-right: 0.13cm">
				<p><font face=""><font style="font-size: 10pt"><b>Condition</b></font></font></p>
			</td>
		</tr>
		<tr>
			<td width="108" bgcolor="#ffffff" style="border: 3.00pt double #00000a; padding-top: 0.13cm; padding-bottom: 0.13cm; padding-left: 0.26cm; padding-right: 0.13cm">
				<p><font face=""><font style="font-size: 10pt">Seed</font></font></p>
			</td>
			<td width="367" bgcolor="#ffffff" style="border: 3.00pt double #00000a; padding-top: 0.13cm; padding-bottom: 0.13cm; padding-left: 0.26cm; padding-right: 0.13cm">
				<p><font face=""><font style="font-size: 10pt">Should
				be “New, Unopened, and returned with original packaging”.</font></font></p>
			</td>
		</tr>
		<tr>
			<td width="108" bgcolor="#ffffff" style="border: 3.00pt double #00000a; padding-top: 0.13cm; padding-bottom: 0.13cm; padding-left: 0.26cm; padding-right: 0.13cm">
				<p><font face=""><font style="font-size: 10pt">Agro
				Chemicals</font></font></p>
			</td>
			<td width="367" bgcolor="#ffffff" style="border: 3.00pt double #00000a; padding-top: 0.13cm; padding-bottom: 0.13cm; padding-left: 0.26cm; padding-right: 0.13cm">
				<p><font face=""><font style="font-size: 10pt">Should
				be “New, Unopened, and returned with original packaging”.</font></font></p>
			</td>
		</tr>
		<tr>
			<td width="108" bgcolor="#ffffff" style="border: 3.00pt double #00000a; padding-top: 0.13cm; padding-bottom: 0.13cm; padding-left: 0.26cm; padding-right: 0.13cm">
				<p><font face=""><font style="font-size: 10pt">Irrigation</font></font></p>
			</td>
			<td width="367" bgcolor="#ffffff" style="border: 3.00pt double #00000a; padding-top: 0.13cm; padding-bottom: 0.13cm; padding-left: 0.26cm; padding-right: 0.13cm">
				<p><font face=""><font style="font-size: 10pt">Should
				be “New, Unopened, and returned with original packaging”.</font></font></p>
			</td>
		</tr>
		<tr>
			<td width="108" bgcolor="#ffffff" style="border: 3.00pt double #00000a; padding-top: 0.13cm; padding-bottom: 0.13cm; padding-left: 0.26cm; padding-right: 0.13cm">
				<p><font face=""><font style="font-size: 10pt">Allied
				Products</font></font></p>
			</td>
			<td width="367" bgcolor="#ffffff" style="border: 3.00pt double #00000a; padding-top: 0.13cm; padding-bottom: 0.13cm; padding-left: 0.26cm; padding-right: 0.13cm">
				<p><font face=""><font style="font-size: 10pt">Should
				be “New, Unopened, and returned with original packaging and
				original accessories”.</font></font></p>
			</td>
		</tr>
		<tr>
			<td width="108" bgcolor="#ffffff" style="border: 3.00pt double #00000a; padding-top: 0.13cm; padding-bottom: 0.13cm; padding-left: 0.26cm; padding-right: 0.13cm">
				<p><font face=""><font style="font-size: 10pt">Tools
				&amp; Machinery</font></font></p>
			</td>
			<td width="367" bgcolor="#ffffff" style="border: 3.00pt double #00000a; padding-top: 0.13cm; padding-bottom: 0.13cm; padding-left: 0.26cm; padding-right: 0.13cm">
				<p><font face=""><font style="font-size: 10pt">Should
				be “New, Unopened, and returned with original packaging and
				original accessories”.</font></font></p>
			</td>
		</tr>
		<tr>
			<td width="108" bgcolor="#ffffff" style="border: 3.00pt double #00000a; padding-top: 0.13cm; padding-bottom: 0.13cm; padding-left: 0.26cm; padding-right: 0.13cm">
				<p><font face=""><font style="font-size: 10pt">Garden</font></font></p>
			</td>
			<td width="367" bgcolor="#ffffff" style="border: 3.00pt double #00000a; padding-top: 0.13cm; padding-bottom: 0.13cm; padding-left: 0.26cm; padding-right: 0.13cm">
				<p><font face=""><font style="font-size: 10pt">Should
				be “New, Unopened, and returned with original packaging and
				original accessories”.</font></font></p>
			</td>
		</tr>
		<tr>
			<td width="108" bgcolor="#ffffff" style="border: 3.00pt double #00000a; padding-top: 0.13cm; padding-bottom: 0.13cm; padding-left: 0.26cm; padding-right: 0.13cm">
				<p><font face=""><font style="font-size: 10pt">Organic
				Products</font></font></p>
			</td>
			<td width="367" bgcolor="#ffffff" style="border: 3.00pt double #00000a; padding-top: 0.13cm; padding-bottom: 0.13cm; padding-left: 0.26cm; padding-right: 0.13cm">
				<p><font face=""><font style="font-size: 10pt">Should
				be “New, Unopened, and returned with original packaging and
				original accessories”.</font></font></p>
			</td>
		</tr>
	</tbody></table>
</center>

<ol start="16">
	<ol>
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt"><b>16.16 Guidelines
		to reduce returns</b></font></font></p>
    <ul>
    	<li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Ensure
    	your product is genuine and saleable.</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Ascertain
    	brand/primary packaging is intact.</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Avoid
    	mis-shipping.</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Ship
    	the exact product as ordered.</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Do
    	not forget to include product components/freebie in the package.</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Accept
    	return requests to provide better buyer experience and improve
    	Vendor / Merchant / Seller ratings.</font></font></p>
    </li></ul>
	</li></ol>
</ol>
<ol start="16">
	<ol start="17">
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">16.17 The
		Vendor / Merchant / Seller’s has declared that it shall strictly
		adhere to the shipping/ delivery/ cancellation/ return/ refund
		policies as declared by it on the website along with the
		particulars of the subject matter goods/ products. </font></font>
		</p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">16.18 The
		Vendor / Merchant / Seller’s has declared that in case of any
		dispute regarding receipt of the order or cancellation or regarding
		return of goods or products, or arising out of any improper
		fulfilment of any order, or refunds, Agrosiaa shall not be a
		necessary or proper party, and shall not be liable for any loss or
		expense arising out of the same, in any manner whatsoever.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">16.19 Agrosiaa
		shall not be liable or responsible for any claims towards refunds
		or damages arising out of any cancellation of any order/s, improper
		fulfilment of the order/s, or subsequent return of goods, and its
		liability shall be restricted to use its good offices with the
		concerned parties for resolution of the issue/s.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">16.20 Agrosiaa,
		in order to ensure that the Vendor / Merchant / Seller’s adhere
		to the declared cancellation/ refund policy, shall remit the
		consideration amounts to the Vendor / Merchant / Seller’s only
		after the specified period within which orders can be cancelled and
		refunds claimed by the Buyers.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">16.21 Agrosiaa
		declares that in case the Buyer fails to inform Agrosiaa regarding
		intention to cancel the order within the specified period and the
		consideration amounts are released to the Vendor / Merchant /
		Seller, the liability of Agrosiaa shall be restricted to follow up
		with the Vendor / Merchant / Seller’s to ensure compliance.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">16.22 In
		case of proper justification, and specifically where there appears
		to be a breach of the cancellation and refund policy, Agrosiaa
		reserves the right to terminate the agreement with the Vendor /
		Merchant / Seller’s. </font></font>
		</p>
	</li></ol>
</ol>

<ol start="17">
	<li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt"><b>17) SHIPPING
	&amp; DELIVERY </b></font></font>
	</p>
</li></ol>

<ol start="17">
	<ol>
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">17.1 Agrosiaa
		shall, on receipt of the completed online order form ‘Offer to
		Purchase’, forward the same for processing to the concerned
		Vendor / Merchant / Seller’s, within 1 working day.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">17.2 The
		orders would be received and allocated to the Vendor / Merchant /
		Seller on their seller panel. The Vendor / Merchant / Seller
		accepts this order as a qualified order and undertakes
		responsibility to execute the order and supply the products as
		ordered. The Vendor / Merchant / Seller has to confirm acceptance
		of the order on the seller panel within 24hours of receipt of the
		order, failure to do so, may lead to withdrawal of the order from
		the Vendor / Merchant / Seller.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">17.3 Post
		acceptance of the order, the Vendor / Merchant / Seller has to
		confirm on the seller panel when the products are packed and ready
		to be shipped. Post this confirmation, Agrosiaa will get the
		products picked from the Vendor / Merchant / Seller’s designated
		location and shipped to the Buyer. The Vendor / Merchant / Seller
		has to provide the material ready for use within 24 hours (Shipping
		SLA) unless explicitly a delay has been informed in advance at the
		time of order confirmation and update the status on the seller
		panel so that the authorized logistics provider can arrange pickup
		of the same.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">17.4 The
		ownership in the products will be transferred to the end of Buyer
		after successful delivery at the destination provided by Buyer,
		until which the ownership in the product shall be with Vendor /
		Merchant / Seller only. As a large market place, Agrosiaa will
		extend its services to Vendor / Merchant / Seller by giving
		mandates to courier and insurance partners, for facilitating the
		smooth functioning of the transaction between the Vendor / Merchant
		/ Seller and Buyer. Vendor / Merchant / Seller will ensure that the
		product will be strongly packed so that it is not damaged in
		transit when dispatched through the courier or any surface mode of
		transportation. Any damage in transit on account of
		inadequate/unsuitable packing will be to the account of Vendor /
		Merchant / Seller. However, any damage to the product in transit
		due to mishandling by the courier, Agrosiaa&nbsp;will facilitate
		the recovery from courier.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">17.5 Agrosiaa
		has presently made arrangements with the registered Logistics
		Partner, for the collection of the goods from the Vendor / Merchant
		Seller’s, and forwarding of the same to the Buyer, on specific
		terms and conditions which shall constitute part and parcel of this
		policy.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">17.6 Agrosiaa
		shall pursue the processing of the order with the Vendor / Merchant
		Seller’s in order to ensure timely compliance.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">17.7 Agrosiaa
		may send such email/s communication to the Buyer’s to update the
		status of the order/s from time to time.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">17.8 The
		Vendor / Merchant / Seller hereby agrees to accept all sales return
		COD (Cash on Delivery) or Non COD (Non Cash on Delivery), which are
		refused/not accepted by the Buyer at the time of delivery. </font></font>
		</p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">17.9 The
		Vendor / Merchant Seller’s has declared that it shall strictly
		adhere to the shipping and delivery/ cancellation/ return/ refund
		policies as declared.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		 <font face=""><font style="font-size: 10pt">17.10 The
		Vendor / Merchant Seller’s has declared that in case of any
		dispute regarding receipt of the order or regarding return of goods
		or products, or arising out of cancellation of any order or
		refunds, Agrosiaa shall not be a necessary or proper party, and
		shall not be liable for any loss or expense arising out of the
		same, in any manner whatsoever.</font></font></p>
	</li></ol>
</ol>
<p align="justify" style="margin-left: 2.54cm; margin-bottom: 0cm; ">

</p>
<ol start="18">
	<li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt"><b>18) INDEMNITY/LIMITATION
	OF LIABILITY</b></font></font></p>
</li></ol>
<p style="margin-left: 1.27cm; margin-bottom: 0cm; ">

</p>
<ol start="18">
	<ol>
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt"><b>18.1 Indemnity
		and Defense</b></font></font><font face=""><font style="font-size: 10pt">.
		You will defend, indemnify and hold harmless Agrosiaa, and each of
		their affiliates, partners (and their respective employees,
		directors, agents and representatives) from and against any and all
		claims, costs, losses, damages, judgments, penalties, interest and
		expenses (including reasonable attorneys' fees) arising out of any
		Claim that arises out of or relates to: (i) any actual or alleged
		breach of your representations, warranties, or obligations set
		forth in this Policy; or (ii) your own website or other sales
		channels, the products you sell, any content you provide, the
		advertisement, offer, sale or return of any products you sell, any
		actual or alleged infringement of any intellectual property or
		proprietary rights by any products you sell or content you provide,
		or Seller Taxes or the collection, payment or failure to collect or
		pay Seller Taxes. For purposes hereof: "Claim" means any
		claim, action, audit, investigation, inquiry or other proceeding
		instituted by a person or entity.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt"><b>18.2 Limitation
		of</b></font></font><font face=""><font style="font-size: 10pt"><b>
		Liability. </b></font></font><font face=""><font style="font-size: 10pt">AGROSIAA
		WILL NOT BE LIABLE FOR ANY DAMAGES OF ANY KIND, INCLUDING WITHOUT
		LIMITATION DIRECT, INDIRECT, INCIDENTAL, PUNITIVE, AND
		CONSEQUENTIAL DAMAGES, ARISING OUT OF OR IN CONNECTION WITH THE
		PARTICIPATION AGREEMENT, THE SITE, THE SERVICES, THE TRANSACTION
		PROCESSING SERVICE, THE INABILITY TO USE THE SERVICES OR THE
		TRANSACTION PROCESSING SERVICE, OR THOSE RESULTING FROM ANY GOODS
		OR SERVICES PURCHASED OR OBTAINED OR MESSAGES RECEIVED OR
		TRANSACTIONS ENTERED INTO THROUGH THE SERVICES.</font></font></p>
	</li></ol>
</ol>
<p align="justify" style="margin-left: 2.54cm; margin-bottom: 0cm; ">

</p>
<ol start="19">
	<li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt"><b>19) DISPUTE
	RESOLUTION</b></font></font></p>
</li></ol>

<ol start="19">
	<ol>
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">19.1 Generally,
		transactions are smooth at Agrosiaa. However, there may be some
		cases where buyers and Vendor / Merchant / Seller face issues. At
		Agrosiaa, we have a dispute resolution process in order to resolve
		disputes between buyers and Vendor / Merchant / Sellers.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">19.2 It
		is important that before buyers raise disputes, the buyer and
		Vendor / Merchant / Seller should attempt to resolve the issues
		between themselves. &nbsp;Whenever a buyer raises a dispute, the
		Vendor / Merchant / Seller’s payment for that order is put on
		hold immediately until a resolution happens. Following are some
		indicative examples of potential disputes:</font></font></p>
    <ul>
    	<li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Wrong
    	item received;</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Item
    	not as described;</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Damaged
    	or seal-broken product;</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Part/accessory
    	missing;</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Item
    	not compatible;</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Seller
    	description/specification wrong;</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Defective
    	(functional issues); and</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">Product
    	not working and manufacturer says invalid invoice.&nbsp;</font></font></p>
    </li></ul>
	</li></ol>
</ol>
<ol start="19">
	<ol start="3">
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">19.3 In
		case a Vendor / Merchant / Seller rejects the return request of a
		buyer and the buyer raises a dispute, Agrosiaa shall try and
		mediate between the Vendor / Merchant / Seller and the buyer for
		resolution of the dispute. If the dispute is resolved in favor of
		the buyer, then the buyer shall be entitled to a refund/replacement
		provided the buyer returns the product to the Vendor / Merchant /
		Seller. If the dispute is settled in favor of the Vendor / Merchant
		/ Seller, the buyer shall not be entitled to any
		refund/replacement.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt"><b>19.4 Reporting
		a Dispute</b></font></font></p>
	</li></ol>
</ol>
<p align="justify" style="margin-left: 2.54cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">Whenever
there is a disagreement, a Vendor / Merchant / Seller’s can write
to&nbsp;<a href="mailto:grievances@agrosiaa.com">grievances@agrosiaa.com</a>&nbsp;in
order to raise a dispute. A dispute can be raised at a particular
transaction level.</font></font></p>
<ol start="19">
	<ol start="5">
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">19.5 The
		Vendor / Merchant / Seller’s has declared that they shall
		strictly adhere to all assurances made by it to the final
		purchaser, as regards the quality and quantity of the goods /
		products / services, and the time of delivery, and all risk and
		costs associated therewith shall be to the sole and exclusive
		account of the Vendor / Merchant / Seller’s.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">19.6 AGROSIAA
		shall pay the specified amount/s to the Vendor / Merchant /
		Seller’s, after deducting all permissible amounts, at the end of
		the cancellation / refund period, and not be responsible for any
		claims arising thereafter. The said amounts shall be remitted only
		and only in case no intimation of cancellation or demand for refund
		is received from the BUYER/S.</font></font></p>
		</li><li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">19.7 The
		Vendor / Merchant / Seller’s has declared that in case of any
		dispute regarding receipt of payments from the payment gateway or
		bank or any such third party, or regarding return of goods or
		products, or arising out of cancellation of any order or refunds,
		AGROSIAAA shall not be a necessary or proper party, and shall not
		be liable for any loss or expense arising out of the same, in any
		manner whatsoever.</font></font></p>
	</li></ol>
</ol>
<p align="justify" style="margin-left: 2.54cm; margin-bottom: 0cm; ">

</p>
<ol start="20">
	<li><p style=" "><b><font face=""><font style="font-size: 10pt">20) TERMINATION</font></font></b></p>
</li></ol>
<p align="justify" style="margin-left: 1.27cm;  ">
<font face=""><font style="font-size: 10pt">Agrosiaa,
in its sole discretion, may terminate your listing, access to the
Site or the Services, or any current fixed price sales immediately
without notice for any reason. Agrosiaa, in its sole discretion, also
may prohibit any Vendor / Merchant / Seller from listing items for
fixed price sales.</font></font></p>
<ol start="21">
	<li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt"><b>21) EMAIL
	ABUSE &amp; THREAT</b></font></font></p>
</li></ol>

<ol start="21">
	<ol>
		<li><p align="justify" style="margin-bottom: 0cm; ">
		<font face=""><font style="font-size: 10pt">21.1 Private
		communication, including email correspondence, is not regulated by
		Agrosiaa. Agrosiaa’s Policy is to encourage users to be
		professional, courteous, and respectful when sending emails.
		However, Agrosiaa will investigate and can take action on certain
		types of unwanted emails that violate Agrosiaa’s policies.
		Agrosiaa will investigate and take action if any of the following
		is reported:</font></font></p>
    <ul>
    	<li><p align="justify" style="margin-bottom: 0cm; ">
    	<font face=""><font style="font-size: 10pt">Threats
    	of bodily harm – Agrosiaa does not permit users to send explicit
    	threats of bodily harm.</font></font></p>
    	</li><li><p align="justify" style="margin-bottom: 0cm; ">
    	<font face=""><font style="font-size: 10pt">Misuse
    	of Agrosiaa’s system – Agrosiaa allows sellers/buyers to
    	transact through Agrosiaa’s system, but will investigate any
    	misuse of this service.</font></font></p>
    	</li><li><p align="justify" style="margin-bottom: 0cm; ">
    	<font face=""><font style="font-size: 10pt">Spoof
    	(fake) email – Agrosiaa will never ask you to provide sensitive
    	information through email. In case you receive any spoof (fake)
    	email, you are requested to report the same to us through the
    	‘Contact Us’ tab.</font></font></p>
    	</li><li><p align="justify" style="margin-bottom: 0cm; ">
    	<font face=""><font style="font-size: 10pt">Spam
    	(unsolicited commercial email) – Agrosiaa’s spam policy applies
    	only to unsolicited commercial messages sent by users. Agrosiaa’s
    	sellers/buyers are not allowed to send spam messages to other
    	sellers/buyers.</font></font></p>
    	</li><li><p align="justify" style="margin-bottom: 0cm; ">
    	<font face=""><font style="font-size: 10pt">Offers
    	to buy or sell outside the Website – Agrosiaa prohibits email
    	offers to buy or sell listed items outside the Website. Offers of
    	this nature are a potential fraud risk to both sellers and buyers.</font></font></p>
    	</li><li><p align="justify" style="margin-bottom: 0cm; ">
    	<font face=""><font style="font-size: 10pt">Agrosiaa’s
    	policy prohibits threats of physical harm via any method, including
    	phone, email and our public message boards.</font></font></p>
    </li></ul>
	</li></ol>
</ol>


<ol start="21">
	<ol>
		<li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">21.2 Violations
		of this Policy may result in a range of actions, including but not
		limited to the following:</font></font></p>
    <ul>
    	<li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">limits
    	on account privileges;</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">account
    	suspension;</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">cancellation
    	of listings; and/or</font></font></p>
    	</li><li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt">loss
    	of special status</font></font></p>
    </li></ul>
	</li></ol>
</ol>


<ol start="21">
	<ol start="3">
		<li><p style="margin-bottom: 0cm; "><font face=""><font style="font-size: 10pt"><b>21.3 Other
		Businesses</b></font></font></p>
	</li></ol>
</ol>
<p align="justify" style="margin-left: 2.54cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">Agrosiaa
does not take any responsibility or liability for the actions,
products, content, and services on the Website that are linked to
websites of affiliates and/or third parties using the Website’s
APIs or otherwise. In addition, the Website may provide links to
third-party websites of our affiliated companies and certain other
businesses for which Agrosiaa assumes no responsibility for examining
or evaluating the products and services offered by them. Agrosiaa
does not warrant the offerings of any of these businesses or
individuals or the content of such third-party website(s). Agrosiaa
does not endorse, in any way, any third-party website(s) or content
thereof.</font></font></p>
<p align="justify" style=" ">
</p>
<ol start="22">
	<li><p align="justify" style="margin-bottom: 0cm; ">
	<font face=""><font style="font-size: 10pt"><b>22) No
	Warranties. </b></font></font><font face=""><font style="font-size: 10pt">THE
	SITE AND THE SERVICES ARE PROVIDED ON AN "AS IS" BASIS.
	AGROSIAADOES NOT MAKE ANY OTHER REPRESENTATIONS OR WARRANTIES OF ANY
	KIND, EXPRESS OR IMPLIED, INCLUDING WITHOUT LIMITATION:</font></font></p>
  <ul>
    			<li><p align="justify" style="margin-bottom: 0cm; ">
  			<font face=""><font style="font-size: 10pt">THE
  			IMPLIED WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR
  			PURPOSE, TITLE, AND NON-INFRINGEMENT; </font></font>
  			</p>
  			</li><li><p align="justify" style="margin-bottom: 0cm; ">
  			<font face=""><font style="font-size: 10pt">THAT
  			THE SITE OR THE SERVICES WILL MEET YOUR REQUIREMENTS, WILL ALWAYS
  			BE AVAILABLE, ACCESSIBLE, UNINTERRUPTED, TIMELY, SECURE, OR
  			OPERATE WITHOUT ERROR; </font></font>
  			</p>
  			</li><li><p align="justify" style="margin-bottom: 0cm; ">
  			<font face=""><font style="font-size: 10pt">THAT
  			THE INFORMATION, CONTENT, MATERIALS, OR PRODUCTS INCLUDED ON THE
  			SITE WILL BE AS REPRESENTED BY SELLERS, AVAILABLE FOR SALE AT THE
  			TIME OF FIXED PRICE SALE, LAWFUL TO SELL, OR THAT VENDORS /
  			MERCHANT / SELLERS OR BUYERS WILL PERFORM AS PROMISED; </font></font>
  			</p>
  			</li><li><p align="justify" style="margin-bottom: 0cm; ">
  			<font face=""><font style="font-size: 10pt">ANY
  			IMPLIED WARRANTY ARISING FROM COURSE OF DEALING OR USAGE OF TRADE;
  			AND </font></font>
  			</p>
  			</li><li><p align="justify" style="margin-bottom: 0cm; ">
  			<font face=""><font style="font-size: 10pt">ANY
  			OBLIGATION, LIABILITY, RIGHT, CLAIM, OR REMEDY IN TORT, WHETHER OR
  			NOT ARISING FROM THE NEGLIGENCE OF AGROSIAA. </font></font>
  			</p>
  		</li>
      <li>
        <p align="justify" style="margin-bottom: 0cm; ">
        <font face=""><font style="font-size: 10pt">TO
        THE FULL EXTENT PERMISSIBLE UNDER APPLI</font></font><font face=""><font style="font-size: 10pt">CABLE
        LAW, AGROSIAA DISCLAIM ANY AND ALL SUCH WARRANTIES.</font></font></p>
      </li>
  </ul>
</li></ol>

<ol start="23">
	<li><p align="justify" style="margin-bottom: 0cm; ">
	<font face=""><font style="font-size: 10pt"><b>23) GRIEVANCE
	OFFICER</b></font></font></p>
</li></ol>
<p style="margin-left: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">In
accordance with the IT Act, 2000, and the rules thereunder, the name
and contact details of the grievance officer are provided below:</font></font></p>
<p style="text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">AgrosiaaAgri-Commodities
Online Service LLP</font></font></p>
<p style="text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">105,
New Timber Market,</font></font></p>
<p style="text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">Pune
– 411042, Maharashtra, India</font></font></p>
<p style="text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">Phone:
</font></font>
</p>
<p style="text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">Email:&nbsp;<a href="mailto:greviances@Agrosiaa.com">greviances@Agrosiaa.com</a></font></font></p>
<p style="text-indent: 1.27cm; margin-bottom: 0cm; ">
<font face=""><font style="font-size: 10pt">Time:
Mon – Sat (9:00 – 18:00)</font></font></p>
<p style="text-indent: 1.27cm; margin-bottom: 0cm; ">

</p>
<ol start="24">
	<li><p align="justify" style="margin-bottom: 0cm; ">
	<font face=""><font style="font-size: 10pt"><b>24) QUESTIONS</b></font></font><font face=""><font style="font-size: 10pt"></font></font><br/><font face=""><font style="font-size: 10pt">Please
	<a href="http://www.flipkart.com/s/contact">contact us</a> regarding
	any questions regarding this statement.</font></font></p>
</li></ol>


                    </div>
                  </div>
                </div>
                <div id="tab_5-5" class="tab-pane">
                    <form action="/profile-image" role="form" method="POST" enctype="multipart/form-data" id="profile_image">
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                    @if($profileImage==null)
                                        <img src="/assets/pages/img/no-image.png" alt="" /> </div>
                                    @else
                                        <img src="{{$profileImage}}" alt="" /> </div>
                                    @endif
                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                <div>
                                    <span class="btn default btn-file">
                                        <span class="fileinput-new"> Select image </span>
                                        <span class="fileinput-exists"> Change </span>
                                        <input type="file" name="profile_image" data-error-container="#profile_image_error">
                                    </span>
                                    <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                </div>
                                <div id="profile_image_error"></div>
                            </div>
                        </div>
                        <div class="margin-top-10">
                            <button type="submit" class="btn base-color">Save</button>
                        </div>
                    </form>
                </div>





            </div>
        </div>
    </div>
</div>
<!--end tab-pane-->
<div class="tab-pane" id="tab_1_3">
    <div class="row">

        <div class="col-md-9">
            how to product upload

        </div>
    </div>
</div>
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
@endsection

@section('javascript')
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<script src="/assets/layouts/layout3/scripts/demo.min.js" type="text/javascript"></script>
<script src="/assets/custom/seller/js/profile.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
<script src="/assets/custom/seller/validation/vendor-profile.js" type="text/javascript"></script>
<script src="/assets/global/plugins/fancybox/source/jquery.fancybox.pack.js" type="text/javascript"></script>
<script type="text/javascript" src="/assets/frontend/custom/registration/js/typeahead.bundle.js"></script>
<script type="text/javascript" src="/assets/frontend/custom/registration/js/handlebars-v3.0.3.js"></script>

<script type="text/javascript">
    $(document).ready(function(){

        $("#default_address").on('click',function(){
            $.ajax({
                url:'get-default-address',
                type:'POST',
                data:{
                    'seller_id':$('#seller_id').val()
                },
                async:false,
                error:function(data,xhr,err){

                },
                success:function(data,textStatus,xhr){
                    $('#shop_no_office_no_survey_no').val(data.shop_no_office_no_survey_no).prop('readonly',true);
                    $('#name_of_premise_building_village').val(data.name_of_premise_building_village).prop('readonly',true);
                    $('#road_street_lane').val(data.road_street_lane).prop('readonly',true);
                    $('#area_locality_wadi').val(data.area_locality_wadi).prop('readonly',true);
                    $('#state_name').val(data.state).prop('readonly',true);
                    $('#district').val(data.district).prop('disabled',true);
                    $("#add_address").append('<input type="hidden" name="district" value="'+data.district+'">');
                    $("#add_address").append('<input type="hidden" name="taluka" value="'+data.taluka+'">');
                    $("#add_address").append('<input type="hidden" name="is_default" value="true">');
                    $("#add_address").append('<input type="hidden" name="seller_address_id" value="'+data.id+'">');
                    $('#at_post').val(data.at_post).prop('readonly',true);
                    $('#taluka').html('<option value="'+data.taluka+'" selected>'+data.taluka+'</option>').prop('disabled',true);
                    $('#pincode').val(data.pincode).prop('readonly',true);
                }
            });
        });

        var citiList = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('office_name'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                url: "/get-post-offices?office_name=%QUERY",
                replace: function (url, query) {
                    var taluka = $('#taluka').val();
                    var atPost = $('#at_post').val();
                    var url = "/get-post-offices?office_name="+atPost+"&taluka="+taluka;

                    return url;
                },
                filter: function(x) {
                    /*return $.map(x, function(item) {
                     return {office_name: item.office_name};
                     });*/
                    return $.map(x, function (data) {
                        return {
                            office_name: data.office_name,
                            pincode: data.pincode,
                            taluka: data.taluka,
                            district: data.district,
                            state: data.state
                        };
                    });
                },
                wildcard: "%QUERY"
            }
        });
        citiList.initialize();
        $('#at-post .typeahead').typeahead(null, {
            display: 'office_name',
            source: citiList.ttAdapter(),
            templates: {
                empty: [
                    '<div class="empty-message">',
                    'Unable to find any Post office that match the current query',
                    '</div>'
                ].join('\n')
                //suggestion: Handlebars.compile('<div><strong>'+name+'</strong> - </div>')
            }
        }).on('typeahead:selected', function (obj, datum) {
            var POData = new Array();
            POData = $.parseJSON(JSON.stringify(datum));
            $('#pincode').val(POData["pincode"]);
        }).on('typeahead:open', function (obj, datum) {
            $('#pincode').val("");
        });
    });

</script>
@endsection
