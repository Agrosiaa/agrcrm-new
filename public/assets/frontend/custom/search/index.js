var language = $('#language').val();
$("#compare-ui").hide();
function getDetail(){
    var searchIDs = $(".add-to-compare input:checkbox:checked").map(function(){
        return $(this).val();
    }).get(); // <----
    var count = $(".add-to-compare input:checkbox:checked").length;
    var url = window.location.hostname;
    document.cookie = "products ="+searchIDs+";domain="+url+";path=/";
    document.cookie = "count ="+count+";domain="+url+";path=/";
    $("#compare-data").val(searchIDs);
    $("#compare-count").val(count);
    $.ajax({
        url: "/products/detail",
        async:false,
        data:{'id':searchIDs,'count':count},
        type: 'GET',
        success: function(data, textStatus, xhr) {
            if(xhr.status==200){
                $("#compareData").html(data);
            }else{
                //alert(xhr.responseText);
            }
        }
    });
}
function compareDetail(values){
    var count = $(".add-to-compare input:checkbox:checked").length;
    $.ajax({
        url: "/products/compare",
        async:false,
        data:{'id':values,'count':count},
        type: 'GET',
        success: function(data, textStatus, xhr) {
            location.reload();
        }
    });

}
function sortBy() {
    var finaleUrl = "/search/results/?page=1"
    loadMoreData(finaleUrl,true);
}
function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)", "i"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}
$(document).on("click", "#load_more",function(e) {
    e.preventDefault();
    var listUrl = $("#load_more").attr('href');
    loadMoreData(listUrl,false);
});
$('.brands input:checkbox').change(function() {
    var listUrl = $("#load_more").attr('href');
    var finaleUrl = "/search/results/?page=1"
    loadMoreData(finaleUrl,true);
});
$('input[name="categories[]"]').change(function() {
    var listUrl = $("#load_more").attr('href');
    var finaleUrl = "/search/results/?page=1"
    loadMoreData(finaleUrl,true);
});

$('input[name="features"]').change(function() {
    var listUrl = $("#load_more").attr('href');
    var finaleUrl = "/search/results/?page=1"
    //var finaleUrl = "/search/listing/?page=" + $('#url_page').val();
    loadMoreData(finaleUrl,true);
});
$('#out_of_stock').change(function() {
    var listUrl = $("#load_more").attr('href');
    //var finaleUrl = listUrl.split('?')[0]+"?"+"page=1";
    var finaleUrl = "/search/results/?page=1";
    if($(this).prop("checked") == true){
        $('#exclude_out_of_stock').val(1);
    }else{
        $('#exclude_out_of_stock').val(0);
    }
    loadMoreData(finaleUrl,true);
});
function loadMoreData(listUrl,reload){
    var currentLanguage = $('#language').val();
    if(listUrl!='javascript:void(0)'){
        /* Get Brand Array List */
        //$.LoadingOverlay("show");
        var count = $(".add-to-compare input:checkbox:checked").length;
        var brands = new Array();
        var features = new Array();
        var exclude_out_of_stock = $('#exclude_out_of_stock').val();
        if(typeof(exclude_out_of_stock) == 'undefined' || exclude_out_of_stock ==''){
            exclude_out_of_stock = $("#out_of_stock:checked").length;
        }else{
            if(exclude_out_of_stock == 0 || exclude_out_of_stock == '0'){
                $('#out_of_stock').prop('checked', false);
            }else{
                $('#out_of_stock').prop('checked', true);
            }
        }
        $(".brands input:checkbox:checked").each(function() {
            brands.push($(this).val());
        });
        var categories = new Array();
        $(".categories input:checkbox:checked").each(function() {
            categories.push($(this).val());
        });

        $('input[name="features"]:checked').each(function() {
            features.push($(this).val());
        });
        var brandLength = brands.length;
        var categoryLength = categories.length;
        var featuresLength = features.length;
        listUrl = listUrl + "&exclude_out_of_stock="+exclude_out_of_stock;
        if(brandLength>0){
            listUrl = listUrl+"&brands="+brands;
        }
        if(categoryLength>0){
            listUrl = listUrl+"&categories="+categories;
        }
        if(featuresLength>0){
            listUrl = listUrl+"&features="+features;
        }
        var parameter = $("#parameter").val();
        if(parameter == 'category'){
            var index = $("#index").val();
            listUrl = listUrl + "&index=" + index;
        }
        var searchFeatureKeyword = $("#searchFeatureKeyword").val();
        if(typeof(searchFeatureKeyword) != 'undefined'){
            searchFeatureKeyword = searchFeatureKeyword.replace(/\&/g,'%26');
        }
        var searchKeyword = $("#searchKeyword").val();
        if(typeof(searchKeyword) != 'undefined'){
            searchKeyword = searchKeyword.replace(/\&/g,'%26');
        }
        var priceRange = $("#ex12c").val();
        var discountRange = $("#ex13c").val();
        var language = $('#language').val();
        var sortBy = $(".sort-by-filter option:selected").val();
        listUrl = listUrl+"&price="+priceRange+"&discount="+discountRange+"&sort="+sortBy+"&count="+count+"&searchFeatureKeyword="+searchFeatureKeyword+"&search_keyword="+searchKeyword+"&parameter="+parameter;
        var stateObj = {foo:"bar"}
        window.history.replaceState(stateObj,"filters",listUrl);
        if(categoryLength.length || featuresLength.length ){
            $("#remained_items").html(0);
            $("#load_more").attr('href','javascript:void(0)');
            $("#load_more").html(language == 'mr'?  'आणखी कोणतेही वस्तू आढळले नाहीत ' :'No more results found');
        }else{
            $.ajax({
                url: listUrl,
                async:false,
                error: function(data,xhr,err) {
                    alert("Something went wrong.");
                },
                success: function(data, textStatus, xhr) {
                    if(data == ''){
                        productNotFound();
                    }else{
                    }
                    if(reload){
                        $("#product_listing").html(data);
                    }else{
                        $("#product_listing").append(data);
                    }
                    var productId = readCookie('productId');
                    if(productId != null){
                        $.ajax({
                            url: "/products/search-compare",
                            async:false,
                            data:{'id':productId},
                            type: 'GET',
                            success: function(data, textStatus, xhr) {
                                if(xhr.status==200){
                                    if(typeof(data) === 'object') {
                                        $('.add-to-compare').each(function(){
                                            $(this).fadeIn();
                                        });
                                    } else {
                                        $('.add-to-compare').each(function(){
                                            if(this.id == data.toString()) {
                                                $(this).fadeIn();
                                            } else {
                                                $(this).fadeOut();
                                            }
                                        });
                                    }
                                }else{
                                    //alert(xhr.responseText);
                                }
                            }
                        });
                    }
                },
                type: 'GET'
            });
            $.ajax({
                url: "/products/loader-url",
                async:false,
                error: function(data,xhr,err) {
                    alert('something went wrong');
                },
                success: function(data, textStatus, xhr) {
                    if(data.remainingItems==0){
                        $("#remained_items").html(0);
                        $("#load_more").attr('href','javascript:void(0)');
                        $("#load_more").html(language == 'mr'?  'आणखी कोणतेही वस्तू आढळले नाहीत ' :'No more results found');
                    }else{
                        $("#load_more").html('<button class="btn btn-primary">'+(currentLanguage === 'en' ? "Show more products" : " अधिक उत्पादने दाखवा") +'(<span id="remained_items">'+data.remainingItems+'</span>)</button>');
                        //$("#remained_items").html(data.remainingItems);
                        $("#load_more").attr('href',data.nextUrl);
                    }
                },
                type: 'GET'
            });
        }
    }
}
$(document).ready(function(){
    var ids = readCookie('products');
    var count = readCookie('count');
    var productId = readCookie('productId');
    if(ids != null && count != null){
        $('#compare-data').val(ids);
        $('#compare-count').val(count);
        var searchIDs = $.parseJSON('[' + ids + ']');
        for(var k in searchIDs) {
            var removeBtn = searchIDs[k];
            $('#'+removeBtn).prop('checked', true);
        }
        if(count < 1) {
            $("#compare-ui").hide();
        } else{
            $("#compare-ui").show();
        }
        if(count <= 1) {
            $("#compare-btn").hide();
        } else{
            $("#compare-btn").show();
        }
        if(count >= 3){
            $(".add-to-compare input:checkbox:checked").show();
            $(".add-to-compare input:checkbox:checked + label").css("visibility","visible");
            $(".add-to-compare input:checkbox:not(:checked)").hide();
            $(".add-to-compare input:checkbox:not(:checked) + label").css("visibility","hidden");


        }else{
            $(".add-to-compare input:checkbox:not(:checked)").show();
            $(".add-to-compare input:checkbox:not(:checked) + label").css("visibility","visible");

        }


        $.ajax({
            url: "/products/detail",
            async:false,
            data:{'id':searchIDs,'count':count},
            type: 'GET',
            success: function(data, textStatus, xhr) {
                if(xhr.status==200){
                    $("#compareData").html(data);
                }else{
                    //alert(xhr.responseText);
                }
            }
        });


    }
    if(productId != null){
        $.ajax({
            url: "/products/search-compare",
            async:false,
            data:{'id':productId},
            type: 'GET',
            success: function(data, textStatus, xhr) {
                if(xhr.status==200){

                    if(typeof(data) === 'object') {
                        $('.add-to-compare').each(function(){
                            $(this).fadeIn();
                        });
                    } else {
                        $('.add-to-compare').each(function(){
                            if(this.id == data) {
                                $(this).fadeIn();
                            } else {
                                $(this).fadeOut();
                            }
                        });
                    }
                }else{
                    //alert(xhr.responseText);
                }
            }
        });
    }
    $(document).on("click",".add-to-compare input",function(value,index){

        var count = $(".add-to-compare input:checkbox:checked").length;
        var searchID = $(".add-to-compare input:checkbox:checked").val();
        var url = window.location.hostname;
        document.cookie = "productId ="+searchID +";domain="+url+";path=/";
        $.ajax({
            url: "/products/search-compare",
            async:false,
            data:{'id':searchID},
            type: 'GET',
            success: function(data, textStatus, xhr) {
                if(xhr.status==200){

                   if(typeof(data) === 'object') {
                       $('.add-to-compare').each(function(){
                           $(this).fadeIn();
                       });
                   } else {
                       $('.add-to-compare').each(function(){
                           if(this.id == data) {
                               $(this).fadeIn();
                           } else {
                               $(this).fadeOut();
                           }
                       });
                   }
                }else{
                    //alert(xhr.responseText);
                }
            }
        });

        if(count >= 1) {
            $("#compare-ui").show();
        } else{
            $("#compare-ui").hide();
        }
        if(count >= 3){
            $(".add-to-compare input:checkbox:checked").show();
            $(".add-to-compare input:checkbox:checked + label").css("visibility","visible");
            $(".add-to-compare input:checkbox:not(:checked)").hide();
            $(".add-to-compare input:checkbox:not(:checked) + label").css("visibility","hidden");
        }else{
            $(".add-to-compare input:checkbox:not(:checked)").show();
            $(".add-to-compare input:checkbox:not(:checked) + label").css("visibility","visible");
        }

        if(count <= 1) {
            $("#compare-btn").hide();
        } else{
            $("#compare-btn").show();
        }
    });

    $(document).on("click",".compare-selected-product .compare-item .remove-compare-item",function(){
        $(this).parent(".compare-product").prev().show();
        $(this).parent(".compare-product").remove();
        var removeBtn = $(this).val();
        $("#"+removeBtn).prop('checked', false);
        var count = readCookie('count') - 1;
        var url = window.location.hostname;
        document.cookie = "products ="+removeBtn +";domain="+url+";path=/;expires=" + new Date(0).toUTCString();
        document.cookie = "count ="+count+";domain="+url+";path=/expires=" + new Date(0).toUTCString();
        document.cookie = "productId ="+removeBtn +";domain="+url+";path=/;expires=" + new Date(0).toUTCString();
        var searchNewId = $(".add-to-compare input:checkbox:checked").map(function(){
            return $(this).val();
        }).get();
        var counter =    $(".add-to-compare input:checkbox:checked").length;
        document.cookie = "products ="+searchNewId+";domain="+url+";path=/";
        document.cookie = "count ="+count+";domain="+url+";path=/";
        $('#compare-data').val(searchNewId);
        $('#compare-count').val(count);
        if(count < 1) {
            $("#compare-ui").hide();
        }
        if(count <= 3){
            $(".add-to-compare").show();
            $(".add-to-compare input:checkbox:not(:checked)").show();
            $(".add-to-compare input:checkbox:not(:checked) + label").css("visibility","visible");
        }
        if(count < 2) {
            $("#compare-btn").hide();
        } else{
            $("#compare-btn").show();
        }
    });
    var filterHt = function(){
        if($(".filters").height() < $(window).height()-150 )
            $(".filters").height($(window).height()-150);

        $(".filters-wrap").height($(".filters").height());
    }
    filterHt();
    $(window).resize(function(){
        filterHt();
    })

    $(window).scroll(function(){
        var scrollAmt = $(window).scrollTop();
        if($(window).width() >= 768)
        {
            /* for sticky filters */
            var filters = $(".filters-wrap");
            if(filters.offset().top + filters.height() < scrollAmt + $(window).height()-20)
            {
                $(".filters").addClass("fixed");
            }else{
                $(".filters").removeClass("fixed");
            }

            var productList = $(".product-list-outer");
            if(productList.offset().top + productList.height() < scrollAmt + $(window).height()-20)
            {
                $(".filters").addClass("absolute");
            }else{
                $(".filters").removeClass("absolute");
            }
            /* sticky filters end*/
        }
    })
/*$("#ex12c").on("slide", function(slideEvt) {
 var finaleUrl = "/search/listing/mulching-films?page=1"
 loadMoreData(finaleUrl,true);
 });*/

    $("#category_search").on('keyup',function(){
        var searchKeyword = $(this).val().toLowerCase();
        $('.categories input[name="categories[]"]').each(function(){
            var tempBrand = $(this).next().text().toLowerCase();
            if(tempBrand.indexOf(searchKeyword) >= 0){
                $(this).parent().attr('hidden', false);
            }else{
                $(this).parent().attr('hidden', true);
            }
        });
    });
$("#ex12c").on("slideStop", function(slideEvt) {
    var priceRange = $(this).val();
    $('li[filterid="ex12c"]').remove();
    var filterItem = '<li filterid="'+ $(this).attr("id") +'"><span>'+(language == 'mr' ? "किंमत:" : "Price:")+priceRange.replace(',','-')+'</span><a href="#">x</a></li>';
    $(".applied-filters ul").append(filterItem);
    var finaleUrl = "/search/results/?page=1";
    loadMoreData(finaleUrl,true);
});
$("#ex13c").on("slideStop", function(slideEvt) {
    var discountRange = $(this).val();
    $('li[filterid="ex13c"]').remove();
    var filterItem = '<li filterid="'+ $(this).attr("id") +'"><span>'+(language == 'mr' ? "सूट:" : "Discount:")+discountRange.replace(',','-')+'</span><a href="#">x</a></li>';
    $(".applied-filters ul").append(filterItem);
    var finaleUrl = "/search/results?page=1"
    loadMoreData(finaleUrl,true);
});

//var finaleUrl = "/search/listing/?page=" + $('#url_page').val()
    var finaleUrl = "/search/results/?page=1"
    applyBrandAndFilters();
    disableProperty();
    var brandsNull = $("#brands-null").val();
    var categoryNull = $("#category-null").val();
    if(typeof(brandsNull) != 'undefined' && typeof(categoryNull) != 'undefined'){
        productNotFound();
    }else{
        loadMoreData(finaleUrl,true);
    }

var url = window.location.href;
var brands = getParameterByName('brands',url);
//var brandArray = brands.split(',');

});
function cart(productId,buyType){

    var rememberToken = $('meta[name="csrf_token"]').attr('content');
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN' : rememberToken } });
    $.ajax({
        url: "/cart/add/listing",
        data: {'product_id':productId,'buy_type':buyType},
        async:false,
        error: function(xhr,err) {
            location.reload();
        },
        success: function(data, textStatus, xhr) {
            if(xhr.status==200){
                if(buyType=='buy_now'){
                    window.location.href = '/checkout';
                }else{
                    location.reload();
                }
            }else{
                //alert(xhr.responseText);
            }
        },
        type: 'POST'
    });
}
function disableProperty(){
    var parameter = $("#parameter").val();
    if($('input[name="categories[]"]').length==1){
        $('input[name="categories[]"]').attr('disabled', true);
        $('input[name="categories[]"]').attr('checked', true);
    }
    if($('input[name="brands[]"]').length==1 && parameter != 'category'){
        $('input[name="brands[]"]').attr('checked', true);
        $('input[name="brands[]"]').attr('disabled', true);
    }
}

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
function compare(){
    $.ajax({
        url: "/products/compare",
        async:false,
        type: 'POST'
    });
}

function productNotFound(){
    $('#no_result_found').show();
    $('.filters').hide();
    $('.applied-filters').hide();
    $('.sort-by').hide();
    $(".text-center").hide();
}
