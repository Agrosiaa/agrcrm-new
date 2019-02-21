var _customSelect = function(){
    $("select").wrap("<div class='selectWrap'></div>")
    $(".selectWrap").prepend("<div class='customSelect'></div>")

    $("select").each(function(){
        $(this).prev().html($('option:selected',this).text());
    });

    $("select").click(function(){
        $(this).prev().html($('option:selected',this).text());
    })
}
var compare_popup_close = function(){
    $(document).on("click",".compare-popup-close", function(){
        $(".compare-selected-product").slideUp(800);
    });
}

var productImagesCuroselSelect = function(){
    $(document).on("click",".product-images-curosel .slide a", function(e){
        e.preventDefault();
        var src = $(this).children("img").attr("src");
        $(".product-detail-wrap .product-img img").attr("src",src);
        $(".product-detail-wrap .product-img").attr("index",$(this).parent().index());
    });
}

var productImageSlideShow = function(){
    $(".product-images-curosel .slide .product-img-wrap").each(function(){
        $(".product-images-slide-show .slides-wrap").append("<div class='img-slide'>"+$(this).html()+"</div>");
    });
    $(document).on("click",".product-images-slide-show .prev-slide",function(){
        var index = parseInt($(".product-images-slide-show .slides-wrap").attr("currentIndex"));
        $(".product-images-slide-show .next-slide").show();
        $(".product-images-slide-show .slides-wrap .img-slide").eq(index).fadeOut()
        $(".product-images-slide-show .slides-wrap .img-slide").eq(index-1).fadeIn();
        $(".product-images-slide-show .slides-wrap").attr("currentIndex",index-1);
        if(index-1==0){
            $(this).hide();
        }
    });

    $(document).on("click",".product-images-slide-show .next-slide",function(){
        var index = parseInt($(".product-images-slide-show .slides-wrap").attr("currentIndex"));
        var len = $(".product-images-slide-show .slides-wrap .img-slide").length;
        $(".product-images-slide-show .prev-slide").show();
        $(".product-images-slide-show .slides-wrap .img-slide").eq(index).fadeOut()
        $(".product-images-slide-show .slides-wrap .img-slide").eq(index+1).fadeIn();
        $(".product-images-slide-show .slides-wrap").attr("currentIndex",index+1);
        if(len-1 == index+1){
            $(this).hide();
        }

    });
    $(document).on("click",".product-images-slide-show .close",function(){
        $(".product-images-slide-show").fadeOut();
    })
}

var sideMenuToggle = function(){
    $(document).on("click",".menu ul li .side-menu-icon,.main-menu-wrap .overlay",function(){
        $(".main-menu-wrap .menu").toggleClass("open")
    });
}

var filters = function(){
    $(document).on("click",".filter-sort-view .filter a",function(){
        $(".filters").slideToggle();
    })
}

var footerMenuItem = function(){
    $(document).on("click",".footer-menu .head",function(){
        if($(window).width()<768){
            $(this).toggleClass("open");
            $(this).next().slideToggle();
        }
    });
}
var categoriesList = function(){
    $(document).on("click",".main-menu-wrap .category-list",function(e){
        e.preventDefault();
        if($(window).width()<768)
            $(this).find(".categories-wrap").slideToggle();
    });
    $(document).on("click",".category-list-item > a",function(e){
        e.stopPropagation();
        e.preventDefault();
        if($(window).width()<768)
        {
            $(".sub-categories").slideUp();
            if(!($(this).next().is(':visible'))){
                $(this).next().slideDown();
            }
        }
    });
}
var specificationHeadClick = function(){
    $(document).on("click",".specification .head",function(){
        if($(window).width()<768){
            $(this).next().slideToggle();
        }
    })
}

var filtersSorting = function(){
    $(document).on("click",".filters ul li input",function(){
        var me = $(this);
        if(this.checked){
            me.parent().addClass("checked");
        }else{
            me.parent().removeClass("checked");
        }

        me.parent().siblings().each(function(){
            if($(this).hasClass("checked")==false){
                me.parent().insertBefore($(this));
                return false;
            }
        });
    });
}
$(document).ready(function(){
    _customSelect();
    compare_popup_close();
    sideMenuToggle();
    filters();
    footerMenuItem();
    categoriesList();
    specificationHeadClick();
    filtersSorting();
    $(".filters-wrap").height($(".filters").height());

    $(".filters ul,.sub-categories").mCustomScrollbar();
    $('.slideshow,.banner-slideshow').cycle();

    productImageSlideShow();
    productImagesCuroselSelect();
    $(document).on("click",".product-detail-wrap .product-img",function(){
        var index = $(this).attr("index");
        $(".product-images-slide-show").fadeIn(500);
        if(!index)
            index=0;
        $(".product-images-slide-show .slides-wrap .img-slide").eq(index).show();
        $(".product-images-slide-show .slides-wrap").attr("currentIndex",index)
        if(index==0){
            $(".product-images-slide-show .prev-slide").hide();
        }
    });

});
$(window).resize(function(){
    if($(window).width()>=768){
        $(".footer-menu .desc").show();
        $(".footer-menu .head").removeClass("open");
        $(".categories-wrap,.category-list-item .sub-categories").removeAttr("style");
    }else{
        $(".footer-menu .desc").hide();
    }
});


$(window).scroll(function(){
    var scrollAmt = $(window).scrollTop();
    if(scrollAmt > 30){
        $("#header").addClass("fixed")
    }else{
        $("#header").removeClass("fixed")
    }
    if($(window)    .width() >= 768)
    {

        /* for sticky filters */
        var filters = $(".filters-wrap");
        if(filters.offset().top + filters.height() < scrollAmt + $(window).height()-20)
        {
            $(".filters").addClass("fixed");
        }else{
            $(".filters").removeClass("fixed");
        }

        var productList = $(".product-list-wrap");
        if(productList.offset().top + productList.height() < scrollAmt + $(window).height()-20)
        {
            $(".filters").addClass("absolute");
        }else{
            $(".filters").removeClass("absolute");
        }
        /* sticky filters end*/
    }

})