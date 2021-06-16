@extends('backend.seller.layouts.master')
@section('title','Agrosiaa | Product')
@include('backend.partials.common.nav')
@section('css')
<link rel="stylesheet" type="text/css" href="/assets/frontend/global/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/assets/frontend/global/css/styles/style.css">
<link href="/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
<link href="/assets/global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet" type="text/css" />
<link href="/assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />

@endsection
@section('content')
<!-- BEGIN CONTAINER -->
<div class="page-container">
<!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">
    <!-- BEGIN CONTENT BODY -->
    <!-- BEGIN PAGE HEAD-->
        <div class="page-head">
            <div class="container">
                <!-- BEGIN PAGE TITLE -->

                <!-- END PAGE TITLE -->

            </div>
        </div>
    <!-- END PAGE HEAD-->
    <!-- BEGIN PAGE CONTENT BODY -->
    <div class="page-content content-min-height">
<div class="container" style="width: 90%;">
<!-- BEGIN PAGE BREADCRUMBS -->
    <div class="navigation">
        @if(array_key_exists('lastParentCategory',$categoryData))
        <a href="#">{{ucwords($categoryData['lastParentCategory']->name)}}</a> >
        @endif
        @if(array_key_exists('parentCategory',$categoryData))
        <a href="#">{{ucwords($categoryData['parentCategory']->name)}}</a> >
        @endif
        <a href=#>{{ucwords($categoryData['itemHead']->name)}}</a>
        <a href="#"> {{ucwords($product['product_name'])}}</a>
    </div>
    <div class="row">
        <div class="col-md-12 product-detail-outer">
            <!-- Product detail wrap start -->
            <div class="product-detail-wrap">
                <div class="row inner">
                    <div class="col-md-7">
                        <div class="product-img">
                            @if($response->productImageArray != null)
                                @foreach($response->productImageArray as $productImage)
                                    @if($productImage->position==1)
                                    <img src='{{asset($productImage->path)}}' alt="{{$productImage->alternate_text}}" title="{{$product['product_name']}}">
                                    @endif
                                @endforeach
                            @else
                                <img alt="No Image Found" title="No Image Found" src="/uploads/userdata/seller/noimage.png">
                            @endif
                        </div>
                        <div class="product-images-slide-show">
                            <a href="#" class="close">x</a>
                            <a href="#" class="next-prev-btns next-slide"></a>
                            <a href="#" class="next-prev-btns prev-slide"></a>
                            <div class="slides-wrap">

                            </div>

                        </div>
                        <div class="product-images-curosel">
                            <div class="slider-wrap">
                                @if($response->productImageArray != null)
                                    @foreach($response->productImageArray as $productImage)
                                        <div class="slide">
                                            <a href="#" class="product-img-wrap">
                                                <img src="{{$productImage->path}}" alt="{{$productImage->alternate_text}}" title="{{$product['product_name']}}-{{$productImage->position}}">
                                            </a>
                                        </div>
                                    @endforeach
                                @endif
                                <!-- <a href="#" class="next-prev-btn prev" id="prev0"></a>
                                <a href="#" class="next-prev-btn next" id="next0"></a>
                            </div> -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="product-details">
                            <div class="product-name">
                                {{ucwords($product['product_name'])}}
                            </div>
                            @if($brand != null)
                            <div class="brand">Brand: <span>{{ucfirst($brand['name'])}} </span></div>
                            @endif
                            <div class="skuid">SKUID: <span>{{$product['item_based_sku']}}</span></div>
                            <div class="price-wrap">
                                @if($product['discount'] != 0)
                                @if($categoryData['itemHead']->is_configurable == true && $product['is_override_configurable'] == false)
                                <div class="original-price"><i class="fa fa-inr"></i>{{$product['selling_price']}}.00</div>
                                <div class="price"> <i class="fa fa-inr"></i>  {{$product['discounted_price']}}.00 <span>per sq. mtr</span></div>
                                <div style="margin-left: 100px" class="discount"><div>{{$product['discount']}}%</div>Off</div>
                                @else
                                <div class="original-price"><i class="fa fa-inr"></i>{{$product['selling_price']}}.00 </div>
                                <div class="price"> <i class="fa fa-inr"></i>  {{$product['discounted_price']}}.00</div>
                                <div class="discount"><div>{{$product['discount']}}%</div>Off</div>
                                @endif
                                @elseif($categoryData['itemHead']->is_configurable == true && $product['is_override_configurable'] == false)
                                <div class="price"> <i class="fa fa-inr"></i>  {{$product['discounted_price']}}.00 <span>per sq. mtr</span></div>
                                @else
                                <div class="price"> <i class="fa fa-inr"></i>  {{$product['discounted_price']}}.00 </div>

                                @endif
                                <div>All Inclusive of Taxes</div>
                            </div>
                            @if($product['quantity'] >= $product['minimum_quantity'] && $product['quantity'] > 0 )
                            <!--<div class="quantity">
                                <button class="btn btn-primary">-</button>
                                <input type="text" id="productQuantity" placeholder="Qty" value="{{$product['minimum_quantity']}}">
                                <button class="btn btn-primary">+</button>
                            </div>
                            <div class="row">
                                <span id="product_quantity_message" style="display:none;color:red"></span>
                            </div>
                            <div class="check-availability">
                                <div class="check">
                                    <span>Check availability at</span>&nbsp;<input type="text" id="pincode" name="pincode" placeholder="@lang('message.enter_pincode_text')"> &nbsp;<a href="javascript:void(0)" class="btn btn-primary" id="check" disabled="">@lang('message.check_button')</a>
                                </div>
                                <div class="available">
                                    <span>Delivery available at<span class="pincode" id="available-pincode"></span></span>&nbsp;<a href="javascript:void(0)" class="btn btn-primary change">@lang('message.change_button')</a>
                                </div>
                                <div class="not-available">
                                    <span>Delivery not available at<span class="pincode" id="not-available-pincode"></span></span>&nbsp;<a href="javascript:void(0)" class="btn btn-primary change">@lang('message.change_button')</a> &nbsp;<a href="#" class="btn btn-primary add" data-toggle="modal" data-target="#enquiry-form">@lang('message.Send_Enquiry_text')</a>
                                </div>
                            </div>-->
                            @if($categoryData['itemHead']->is_configurable == true && $product['is_override_configurable'] == false)
                                <div class="configurable">
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#select-size">Select Size</button>
                                </div>
                            @else
                            <!--<div class="buy-addCart-buttons">
                                @if($product['is_active'] == false)
                                <button class="btn btn-primary btn-icon" disabled>Buy Now</button>
                                @else
                                <button class="btn btn-primary btn-icon">Buy Now</button>
                                @endif
                                <button class="btn btn-info btn-icon">Add to Cart</button>
                            </div>-->
                        @endif
                        @else
                        <div class="out-of-stock">Product Is Out of Stock</div>
                        @endif
                        @if($product['is_active'] == false)
                        <p style="color:red;">This product is disabled by vendor for some reasons. Kindly contact <a href="tel:8550999760">+91-8550999760</a> for urgent requirement.</p>
                        @endif
                        <!--<ul>
                            <li>Easy Return</li>
                            <li>100% Original</li>
                            <li>Brand New</li>
                            <li>Pay Securely</li>
                        </ul>-->

                            <div class="delivery-info">
                                <div class="item">
                                    <div>Delivered by?</div>
                                </div>
                                <div class="item">
                                    <div>CASH ON DELIVERY?</div>
                                    @if($product['is_cod_available'] == true)
                                    <div><span>Available</span></div>
                                    @else
                                    <div><span style="color: red">Not Available</span></div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="specification">
                    <div class="title">Specifications for {{ ucwords($product['product_name'])}}</div>

                    <div class="item">
                        <div class="head">Product Description<i></i></div>
                        <div class="product-desc details">{{$product['product_description']}}</div>
                    </div>

                    <div class="item">
                        <div class="head">Product Specifications <i></i></div>
                        <ul class="details">
                            <li class="keyspecs">
                                <div>Key Specs</div>
                                <div>
                                    <ul>
                                        <li>{{$product['key_specs_1']}}</li>
                                        <li>{{$product['key_specs_2']}}</li>
                                        @if($product['key_specs_3'] != null)
                                        <li>{{$product['key_specs_3']}}</li>
                                        @endif
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <div>Other Features And Applications</div>
                                @if($product['other_features_and_applications'] != null || $product['other_features_and_applications'] != "")
                                <div>
                                    <ul>
                                        <?php $token = strtok($product['other_features_and_applications'],"#"); ?>
                                        @while($token !== false)
                                        <li>{{$token}}</li>
                                        <?php $token = strtok("#");?>
                                        @endwhile
                                    </ul>
                                </div>
                                @endif
                            </li>
                            <li>
                                @if(!empty($product['sales_package_or_accessories'])){{$product['sales_package_or_accessories']}}
                                <div>Sales Package / Accessories</div>
                                @endif
                            </li>
                            @if($featureMaster!=null)
                            @foreach($featureMaster as $feature)
                            <li>
                                @if(!empty($feature->value) && ($feature->value != '-'))
                                <div>{{ucfirst($feature->name)}}</div>
                                <div>{{$feature->value}}</div>
                                @endif
                            </li>
                            @endforeach
                            @endif

                            <li>
                                @if(!empty($product['domestic_warranty'])){{$product['domestic_warranty']}} {{$product['domestic_warranty_measuring_unit']}}
                                <div> Domestic Warranty </div>
                                @endif
                            </li>

                            <li>
                                @if(!empty($product['warranty_summary'])){{$product['warranty_summary']}}
                                <div> Warranty Summary </div>
                                @endif
                            </li>

                            <li>
                                @if(!empty($product['warranty_service_type'])){{$product['warranty_service_type']}}
                                <div>Warranty Service Type</div>
                                @endif
                            </li>
                            <li>
                                @if(!empty($product['warranty_items_covered'])){{$product['warranty_items_covered']}}
                                <div>Warranty - Items covered</div>
                                @endif
                            </li>

                            <li>
                                @if(!empty($product['warranty_items_not_covered'])){{$product['warranty_items_not_covered']}}
                                <div>Warranty - Items not Covered</div>
                                @endif
                            </li>
                        </ul>
                    </div>
                    <div class="item">
                        <div class="head">Important Information<i></i></div>
                        <ul class="details">
                            <li><b>Legal Disclaimer:</b>
                                <p>Actual product packaging and materials may contain more and different information than what is shown on our website. We recommend that you do not rely solely on the information presented and that you always read labels, warnings, and directions before using or consuming a product.</p>
                                <p>Product images shown are for illustration purpose only. Actual Product may vary depending on stock available with the vendor.</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div id="select-size" class="modal fade bs-modal-sm" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="text-align: center"><b>Select Size</b></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="col-md-4 control-label">Length</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control calculate-length" id="length" name="length" value="" onkeyup="calculateArea(this)">
                            </div>
                            <label class="col-md-4 control-label">mtr</label>
                        </div>
                        <br>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Width</label>
                            <div class="col-md-4">
                                @if($product['configurable_width'] == null && $product['configurable_width'] == "")
                                <input type="text" class="form-control calculate-width" id="width" name="width" value="" onkeyup="calculateArea(this)">
                                @else
                                <input type="text" class="form-control calculate-width" id="width" name="width" value="{{$product['configurable_width']}}" onkeyup="calculateArea(this)" readonly>
                                @endif
                            </div>
                            <label class="col-md-4 control-label">mtr</label>
                        </div>
                        <br>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Area</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control calculate-area" id="area" name="area" value="" readonly>
                            </div>
                            <label class="col-md-4 control-label">sq.mtr</label>
                        </div>
                        <br>
                        <hr>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Total Price</label>
                            <div class="col-md-6">
                                <input type="hidden" class="form-control calculate-total-price" value="{{$product['discounted_price']}}.00">
                                <input type="text" class="form-control calculate-total" id="total" name="total_price" value="" readonly>
                            </div>
                            <lable class="col-md-3 control-label">Rs</lable>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="buy-addCart-buttons">
                        <button class="btn btn-primary btn-lg">Buy Now</button>
                        <button class="btn btn-info btn-lg">Add To Cart</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end modal select size -->
<!-- END PAGE BREADCRUMBS -->
<!-- BEGIN PAGE CONTENT INNER -->
{{--<div class="page-content-inner">--}}
<div class="row">
    @include('backend.partials.error-messages')

</div>
<hr>
<div class="row">
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
<!-- END CONTAINER -->
@endsection
@section('javascript')
<!-- BEGIN CORE PLUGINS -->
<script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="/assets/global/scripts/app.min.js" type="text/javascript"></script>
<!-- END THEME GLOBAL SCRIPTS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script type="text/javascript" src="/assets/frontend/global/js/mCustomScrollbar.min.js"></script>
    <script type="text/javascript" src="/assets/frontend/global/js/jquery.cycle2.min.js"></script>
    <script type="text/javascript" src="/assets/frontend/global/js/jquery.cycle2.carousel.min.js"></script>
    <script type="text/javascript" src="/assets/frontend/global/js/custom.js"></script>

<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME LAYOUT SCRIPTS -->
<script src="/assets/layouts/layout3/scripts/layout.min.js" type="text/javascript"></script>
<script src="/assets/layouts/layout3/scripts/demo.min.js" type="text/javascript"></script>
@endsection