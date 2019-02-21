<!DOCTYPE html>
<html>
<head>
    <title>Agrosiaa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link href="/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="/assets/global/frontend/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="/assets/global/frontend/css/mCustomScrollbar.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/global/frontend/css/style.css">

    <script type="text/javascript" src="/assets/global/frontend/scripts/jquery-1.12.0.min.js"></script>
    <script type="text/javascript" src="/assets/global/frontend/scripts/mCustomScrollbar.min.js"></script>
    <script type="text/javascript" src="/assets/global/frontend/scripts/jquery.cycle2.min.js"></script>
    <script type="text/javascript" src="/assets/global/frontend/scripts/jquery.cycle2.carousel.min.js"></script>
    <script type="text/javascript" src="/assets/global/frontend/scripts/custom.js"></script>
</head>
<body>
<div id="header">
<div class="top-menu-wrap">
    <div class="container">
        <div class="menu top-menu">
            <ul class="pull-right">
                <li class="desktop-only"><a href="#">Bulk Orders</a></li>
                <li class="desktop-only"><a href="#">Vender</a></li>
                <li><a href="#">Help</a></li>
                <!-- <li class="download-app"><a href="#">Download App</a></li> -->
                <li><a href="#" class="facebook"></a><a href="#" class="twitter"></a></li>
                <!-- <li><a href="#">English</a></li>
                <li><a href="#">Marathi</a></li>
                <li><a href="#">Hindi</a></li> -->

            </ul>
        </div>
    </div>
</div>
<div class="logo-wrap">
    <div class=container>
        <div class="menu clearfix">
            <ul class="clearfix">
                <li class="mobile-only"><a href="#" class="side-menu-icon"></a></li>
                <li class="logo"><a href="#"></a></li>
                <li class="select-category">
                    <select>
                        <option>All Categories</option>
                    </select>
                    <input type="text" placeholder="Search products, brands">
                    <a href="#" class="search"></a>
                </li>
                <li class="mobile-only profile-icon"><a href="#"></a></li>
                <li class="cart">
                    <a href="#">
                        <!--<span>10</span>-->
                    </a>
                </li>
                <li class="login-register">
                    <a href="#" class="login">
                        <i></i>
                        <span>Login/Register</span>
                    </a>
                    <!-- <a href="#">Register</a> -->
                </li>
                <li class="about-agrosiaa">
                    <a href="#">
                        <div>About</div>
                        <div class="desc">Agrosiaa</div>
                        <i></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="main-menu-wrap">
<div class="container">
<div class="row">
<div class="col-xs-6 col-sm-3 col-md-3 category-list">
<a href="#" class="category-lbl">Store <i></i></a>

</div>
<div class="col-sm-7 menu col-md-6">
    <div class="about-agrosiaa-mobile mobile-only">
        About <span>Agrosiaa</span>
        <a href="#" class="play-icon"></a>
    </div>
    <ul>
        <li class="vendors desktop-only"><a href="#">Vendors</a></li>
        <li><a href="#">Why Agrosiaa</a></li>
        <li><a href="#">How it Works</a></li>
        <li class="mobile-only"><a href="#">Vendors</a></li>
        <li class="mobile-only"><a href="#">Bulk Orders</a></li>
        <li><a href="#">Contact Us</a></li>
    </ul>
    <div class="overlay"></div>
</div>
<div class="col-xs-6 col-sm-2 col-md-3 contact-wrap">
    <a href="#" class="contanct"><i></i>+228 872 4444</a>
</div>
</div>
</div>
</div>
</div>

<div id="content">
<div class="container">
<div class="navigation">
    @if(array_key_exists('lastParentCategory',$categoryData))
        <a href="#">{{$categoryData['lastParentCategory']}}</a> >
    @endif
    @if(array_key_exists('parentCategory',$categoryData))
    <a href="#">{{$categoryData['parentCategory']}}</a> >
    @endif
    <a href="#">{{$categoryData['itemHead']}}</a> >
    <a href="#"> {{$product['product_name']}}</a>
</div>
<div class="row">
<div class="col-md-12 product-detail-outer">
<!-- Product detail wrap start -->
<div class="product-detail-wrap">
    <div class="row inner">
        <div class="col-md-7">
            <div class="product-img">
                @if($productImageArray != null)
                @foreach($productImageArray as $productImage)
                    @if($productImage['position']==1)
                        <img src="{{$productImage['path']}}">
                        <a href="#" class="zoom"></a>
                    @endif
                @endforeach
                @else
                <img src="/uploads/userdata/seller/noimage.jpg">
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
                    <div class="slideshow"
                         data-cycle-fx=carousel
                         data-cycle-timeout=0
                         data-cycle-next="#next0"
                         data-cycle-prev="#prev0"
                         data-cycle-carousel-visible=3
                         data-allow-wrap=false
                         data-cycle-slides=">div.slide">
                        @if($productImageArray != null)
                        @foreach($productImageArray as $productImage)
                            <div class="slide">
                                <a href="#" class="product-img-wrap">
                                    <img src="{{$productImage['path']}}">
                                </a>
                            </div>
                        @endforeach
                        @endif
                        <a href="#" class="next-prev-btn prev" id="prev0"></a>
                        <a href="#" class="next-prev-btn next" id="next0"></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="product-details">
                <div class="product-name">{{$product['product_name']}}</div>
                @if($brand != null)
                <div class="brand">Brand: <span>{{$brand['name']}}</span></div>
                @endif
                <div class="skuid">SKUID: {{$product['item_based_sku']}}</div>
                <div class="price-wrap">
                    @if($product['discount'] != 0)
                    <div class="original-price"><i class="fa fa-inr"></i>{{$product['base_price']}}.00</div>
                    <div class="price"> <i class="fa fa-inr"></i>  {{$product['discounted_price']}}.00</div>
                    <div class="discount"><div>{{$product['discount']}}%</div>Off</div>
                    @else
                    <div class="price"> <i class="fa fa-inr"></i>  {{$product['discounted_price']}}.00</div>
                    @endif
                    <div>All Inclusive of Taxes</div>
                </div>
                <div class="quantity">
                    <button class="btn btn-primary">-</button>
                    <input type="text" placeholder="Qty" value="1" disabled>
                    <button class="btn btn-primary">+</button>
                </div>
                <div class="buy-addCart-buttons">
                    <button class="btn btn-primary btn-icon">Buy Now</button>
                    <button class="btn btn-info btn-icon">Add to Cart</button>
                </div>
                <div class="add-to-compare">
                    <input id="add-to-compare" type="checkbox">
                    <label for="add-to-compare">Add to Compare</label>
                </div>
                <ul>
                    <li>Easy Return</li>
                    <li>100% Original</li>
                    <li>Brand New</li>
                    <li>Pay Securely</li>
                </ul>

                <div class="delivery-info">
                    <div class="item">
                        <div>DELIVERED BY?</div>
                        <div>Wed, 23rd Dec:<span>FREE</span></div>
                    </div>

                    <div class="item">
                        <div>CASH ON DELIVERY?</div>
                        <div><span>Available</span></div>
                    </div>
                    <div>7 Days no question Replacement <a href="#">Return Policy</a></div>
                </div>
            </div>
        </div>
    </div>

    <div class="specification">
        <div class="title">Specifications for {{$product['product_name']}}</div>

        <div class="item">
            <div class="head">Product Description <i></i></div>
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
                @if($featureMaster!=null)
                    @foreach($featureMaster as $feature)
                        <li>
                            <div>{{ucfirst($feature['name'])}}</div>
                           @if(!empty($feature['value']))
                            <div>{{$feature['value']}}</div>
                            @else
                            <div>-</div>
                            @endif
                        </li>
                    @endforeach
                @endif
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
                        @else
                            <div>-</div>
                        @endif
                </li>
                <li>
                    <div>Sales Package / Accessories</div>
                    <div>@if(!empty($product['sales_package_or_accessories'])){{$product['sales_package_or_accessories']}}@else - @endif</div>
                </li>
                <li>
                    @if(!empty($product['domestic_warranty'])){{$product['domestic_warranty']}} year
                    <div>Domestic Warranty</div>
                     @endif
                </li>

                <li>
                    <div>Warranty Summary</div>
                    <div>@if(!empty($product['warranty_summary'])){{$product['warranty_summary']}}@else - @endif</div>
                </li>

                <li>
                    <div>Warranty Service Type</div>
                    <div>@if(!empty($product['warranty_service_type'])){{$product['warranty_service_type']}}@else - @endif</div>
                </li>

                <li>
                    <div>Warranty - Items covered</div>
                    <div>@if(!empty($product['warranty_items_covered'])){{$product['warranty_items_covered']}}@else - @endif</div>
                </li>

                <li>
                    <div>Warranty - Items not Covered</div>
                    <div>@if(!empty($product['warranty_items_not_covered'])){{$product['warranty_items_not_covered']}}@else - @endif</div>
                 </li>
            </ul>
        </div>
    </div>

</div>
<!-- Product detail wrap end -->
</div>
</div>
</div>

<div id="footer">
    <div class="footer-usp menu">
        <div class="container">
            <ul  class="row">
                <li class="col-md-2">
                    <div class="img-wrap"><img src="/assets/global/frontend/img/extensiveRange.png"></div>
                    <div>Extensive Range</div>
                    <p>We offer the most extensive range of agricultural products. Offering variety to our customers is our forte.</p>
                </li>
                <li class="col-md-2">
                    <div class="img-wrap"><img src="/assets/global/frontend/img/assuredQuality.png"></div>
                    <div>Assured Quality</div>
                    <p>Every product available on Agrosiaa is authenticated and tested by our team of experts. We assure you absolute quality and authenticity.</p>
                </li>
                <li class="col-md-2">
                    <div class="img-wrap"><img src="/assets/global/frontend/img/costEffective.png"></div>
                    <div>Cost Effective</div>
                    <p>All the products on Agrosiaa are cost effective. There are no hidden or extra charges on the MRP.</p>
                </li>
                <li class="col-md-2">
                    <div class="img-wrap"><img src="/assets/global/frontend/img/easyPurchage.png"></div>
                    <div>Easy Purchase</div>
                    <p>Buying a product here is a just a matter of few clicks. Purchasing anything on Agrosiaa is an absolutely easy and safe process.</p>
                </li>
                <li class="col-md-2">
                    <div class="img-wrap"><img src="/assets/global/frontend/img/seamlessDelivery.png"></div>
                    <div>Seamless Delivery</div>
                    <p>Making an on-time delivery is our strength. Our efficient team makes sure that your order is delivered seamlessly at your doorstep.</p>
                </li>
                <li class="col-md-2">
                    <div class="img-wrap"><img src="/assets/global/frontend/img/userFriendly.png"></div>
                    <div>User Friendly</div>
                    <p>Our platform is easy to use and it takes only few clicks to make a purchase. It has been created considering the needs of our urban and rural customers.</p>
                </li>
            </ul>
        </div>
    </div>
    <div class="footer-menu-wrap">
        <div class="footer-menu clearfix">
            <div class="clearfix">
                <div class="logo pull-left">
                    <a href="#"></a>
                </div>

                <div class="pull-left footer-menu-wrap">
                    <div class="footer-item">
                        <div class="inner">
                            <div class="head">About</div>
                            <ul class="desc">
                                <li><a href="#">Founders</a></li>
                                <li><a href="#">Why Agrosiaa</a></li>
                                <li><a href="#">Invest in Agrosiaa</a></li>
                                <li><a href="#">How it Works</a></li>
                            </ul>
                        </div>
                        <div class="head">Terms</div>
                        <ul class="desc">
                            <li><a href="#">Customer Policy</a></li>
                            <li><a href="#">Returns</a></li>
                            <li><a href="#">Billing</a></li>
                        </ul>
                    </div>

                    <div class="footer-item">
                        <div class="head">Categories</div>
                        <ul class="desc">
                            <li><a href="#">Seeds</a></li>
                            <li><a href="#">Agro Chemicals</a></li>
                            <li><a href="#">Irrigation</a></li>
                            <li><a href="#">Tools & Equipments</a></li>
                            <li><a href="#">Allied Products / Accessories / Packaging Solutions</a></li>
                            <li><a href="#">Garden</a></li>
                            <li><a href="#">Organic Products</a></li>
                            <li><a href="#">Books / Magazines / Newsletters / Events / Exhibitions</a></li>
                            <li><a href="#">Insurance / Finance Schemes</a></li>
                            <li><a href="#">Government Schemes / Soil Testing</a></li>
                            <li><a href="#">Animal Husbandry</a></li>
                            <li><a href="#">Consultancy / Expert Services</a></li>
                        </ul>
                    </div>
                    <div  class="footer-item">
                        <div class="head">Contact</div>
                        <div class="desc">
                            <div>39, Swami Vivekanand Industrial Estate, Handewadi Rd., Hadapsar, Pune 411 028 - INDIA.</div>
                            <div>Ph.: +91 20 2697 0924
                                <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2697 0533
                                <br>Fax: +91 20 2697 0925
                                <br>Email: <a href="#" target="_top">growth@agrosiaa.com</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pull-left write-us">
                    <div class="head">Write to Us</div>
                    <form class="desc">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Name">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <textarea placeholder="Message"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>

            </div>
            <div class="copyright">&copy; Copyright 2015 Agrosiaa. Designed by creator3.in</div>
        </div>
    </div>
    </div>
</div>
</body>
</html>
