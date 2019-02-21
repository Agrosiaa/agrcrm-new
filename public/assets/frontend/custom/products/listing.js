$("#compare-ui").hide();
$(document).on("click",".add-to-compare input",function(value,index){
    var count = $(".add-to-compare input:checkbox:checked").length;
    var searchID = $(".add-to-compare input:checkbox:checked").val();
    var url = window.location.hostname;
    document.cookie = "productId ="+searchID +";domain="+url+";path=/";
    searchCompare(searchID);
    showHideDivButton(count);
});

function getDetail(){
    var searchIDs = $(".add-to-compare input:checkbox:checked").map(function(){
        return $(this).val();
    }).get(); // <----

    var count = $(".add-to-compare input:checkbox:checked").length;
    if(count != null){
        if(count >= 3){
            $(".add-to-compare input:checkbox:checked").show();
            $(".add-to-compare input:checkbox:checked + label").css("visibility","visible");
            $(".add-to-compare input:checkbox:not(:checked)").hide();
            $(".add-to-compare input:checkbox:not(:checked)").closest('div').hide();
            $(".add-to-compare input:checkbox:not(:checked) + label").css("visibility","hidden");
        }else{
            $(".add-to-compare input:checkbox:not(:checked)").show();
            $(".add-to-compare input:checkbox:not(:checked)").closest('div').show();
            $(".add-to-compare input:checkbox:not(:checked) + label").css("visibility","visible");

        }
    }
    var url = window.location.hostname;
    document.cookie = "products ="+searchIDs+";domain="+url+";path=/";
    document.cookie = "count ="+count+";domain="+url+";path=/";
    $('#compare-data').val(searchIDs);
    $('#compare-count').val(count);
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
    var finaleUrl = "/products/listing/"+$('#url').val()+"?page=" + $('#url_page').val();
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
$('input[name="load_more_data"]').change(function() {
    var listUrl = $("#load_more").attr('href');
    var finaleUrl = "/products/listing/"+$('#url').val()+"?page=" + $('#url_page').val();
    loadMoreData(finaleUrl,true);
});
$('input[name="load_more_data"]').change(function() {
    var listUrl = $("#load_more").attr('href');
    var finaleUrl = "/products/listing/"+$('#url').val()+"?page=" + $('#url_page').val();
    loadMoreData(finaleUrl,true);
});
$('#out_of_stock').change(function() {
    var listUrl = $("#load_more").attr('href');
    var finaleUrl = "/products/listing/"+$('#url').val()+"?page=" + $('#url_page').val();
    loadMoreData(finaleUrl,true);
});
$('input[name="features"]').change(function() {
    var listUrl = $("#load_more").attr('href');
    var finaleUrl = "/products/listing/"+$('#url').val()+"?page=" + $('#url_page').val();
    loadMoreData(finaleUrl,true);
});

$('input[name="brands[]"').change(function(){
    var finaleUrl = "/products/listing/"+$('#url').val()+"?page=" + $('#url_page').val();
    loadMoreData(finaleUrl,true);
});

function loadMoreData(listUrl,reload){
    if(listUrl!='javascript:void(0)'){
        var currentLanguage = $('#language').val();
        /* Get Brand Array List */
        //$.LoadingOverlay("show");
        var count = $("[type='checkbox']:checked").length;
        var brands = new Array();
        var features = new Array();
        var exclude_out_of_stock = $("#out_of_stock:checked").length;
        $('input[name="brands[]"]:checked').each(function() {
            brands.push($(this).val());
        });
        $('input[name="features"]:checked').each(function() {
            features.push($(this).val());
        });
        listUrl = listUrl + "&exclude_out_of_stock="+exclude_out_of_stock;
        if(brands.length>0){
            listUrl = listUrl+"&brands="+brands;
        }
        if(features.length>0){
            listUrl = listUrl+"&features="+features;
        }
        var priceRange = $("#ex12c").val();
        var discountRange = $("#ex13c").val();
        var sortBy = $(".sort-by-filter option:selected").val();
        listUrl = listUrl+"&price="+priceRange+"&discount="+discountRange+"&sort="+sortBy+"&count="+count;
        var stateObj = {foo:"bar"}
        window.history.replaceState(stateObj,"filters",listUrl);
        $.ajax({
            url: listUrl,
            async:false,
            error: function(data,xhr,err) {
                alert('something went wrong');
            },
            success: function(data, textStatus, xhr) {
              if(reload){
                var htmlData = $.trim(data);
                if (htmlData=='') {
                  $('#no_result_found').show();
                    $(".sort-by").hide();
                    if($("#filters ul").children().length > 0){
                        $("#filters").show();
                    }else{
                        $("#filters").hide();
                    }
                  $('#slider-filter').hide();
                  $("#load_more").css("display","none");
                }else{
                    $('#no_result_found').hide();
                    $('#filters').show();
                    $(".sort-by").hide();
                    $('#slider-filter').show();
                    $("#load_more").css("display","block");
                }
                $("#product_listing").html(data);
                }else{
                    $("#product_listing").append(data);
                }
                var productId = readCookie('productId');

                if(productId != null){
                    searchCompare(productId);
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
                    $("#load_more").html(currentLanguage == 'mr' ?  'आणखी कोणतेही वस्तू आढळले नाहीत ' : 'No more results found');
                }else{
                    $("#load_more").html('<button class="btn btn-primary">'+(currentLanguage == 'mr' ? " अधिक उत्पादने दाखवा" :"Show more products" ) +'(<span id="remained_items">'+data.remainingItems+'</span>)</button>');
                    //$("#remained_items").html(data.remainingItems);
                    $("#load_more").attr('href',data.nextUrl);
                }
            },
            type: 'GET'
        });
    }
}
$(document).ready(function(){
    var ids = readCookie('products');
    var count = readCookie('count');
    if(ids != null && count != null && ids != "" && count != ""){
        $('#compare-data').val(ids);
        $('#compare-count').val(count);
        var searchIDs = $.parseJSON('[' + ids + ']');
        for(var k in searchIDs) {
            var removeBtn = searchIDs[k];
            $('#'+removeBtn).prop('checked', true);
        }
        showHideDivButton(count);
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
    $(document).on("click",".compare-selected-product .compare-item .remove-compare-item",function(){
        $(this).parent(".compare-product").prev().show();
        $(this).parent(".compare-product").remove();

        var removeBtn = $(this).val();
        $("#"+removeBtn).prop('checked', false);
        var count =    $(".add-to-compare input:checkbox:checked").length;
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
        showHideDivButton(count);
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
    });
$("#ex12c").on("slideStop", function(slideEvt) {
    var priceRange = $(this).val();
    $('li[filterid="ex12c"]').remove();
    var filterItem = '<li filterid="'+ $(this).attr("id") +'"><span>'+"Price:"+priceRange.replace(',','-')+'</span><a href="#">x</a></li>';
    $(".applied-filters ul").append(filterItem);
    var finaleUrl = "/products/listing/"+$('#url').val()+"?page=" + $('#url_page').val();
    loadMoreData(finaleUrl,true);
});
$("#ex13c").on("slideStop", function(slideEvt) {
    var discountRange = $(this).val();
    $('li[filterid="ex13c"]').remove();
    var filterItem = '<li filterid="'+ $(this).attr("id") +'"><span>'+"Discount:"+discountRange.replace(',','-')+'</span><a href="#">x</a></li>';
    $(".applied-filters ul").append(filterItem);
    var finaleUrl = "/products/listing/"+$('#url').val()+"?page=" + $('#url_page').val();
    loadMoreData(finaleUrl,true);
});

var finaleUrl = "/products/listing/"+$('#url').val()+"?page=1";
applyBrandAndFilters();
loadMoreData(finaleUrl,true);
    //setTimeout(function(){ loadMoreData(finaleUrl,true); }, 5000);
var url = window.location.href;
var brands = getParameterByName('brands',url);
//var brandArray = brands.split(',');
});
function cart(productId,buyType){
    var length = $('#length').val();
    var width = $('#width').val();
    var rememberToken = $('meta[name="csrf_token"]').attr('content');
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN' : rememberToken } });
    $.ajax({
        url: "/cart/add/listing",
        data: {'product_id':productId,'buy_type':buyType,'length':length,'width':width},
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

function searchCompare(searchID){
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
}

function showHideDivButton(count){

    if(count < 1) {
        $("#compare-ui").hide();
    } else{
        $("#compare-ui").show();
    }
    if(count >= 2) {
        $("#compare-btn").show();
    } else{
        $("#compare-btn").hide();
    }
    if(count >= 3){
        $(".add-to-compare input:checkbox:checked").show();
        $(".add-to-compare input:checkbox:checked + label").css("visibility","visible");
        $(".add-to-compare input:checkbox:not(:checked)").hide();
        $(".add-to-compare input:checkbox:not(:checked)").closest('div').hide();
        $(".add-to-compare input:checkbox:not(:checked) + label").css("visibility","hidden");
    }else{
        $(".add-to-compare input:checkbox:not(:checked)").show();
        $(".add-to-compare input:checkbox:not(:checked)").closest('div').show();
        $(".add-to-compare input:checkbox:not(:checked) + label").css("visibility","visible");

    }
}

