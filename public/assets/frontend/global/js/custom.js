var _customSelect = function(){
    $(".select-category select").wrap("<div class='selectWrap'></div>")
    $(".selectWrap").prepend("<div class='customSelect'></div>")

    $(".selectWrap select").each(function(){
        $(this).prev().html($('option:selected',this).text());
    });

    $(".selectWrap select").click(function(){
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
        $(".product-images-slide-show .slides-wrap .img-slide").removeAttr("style");
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
        if($(window).width()<768)
            $(this).find(".categories-wrap").slideToggle();
    });
    $(document).on("click",".category-list-item > a",function(e){
        e.stopPropagation();        
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
            var filterItem = '<li filterid="'+ $(this).attr("id") +'"><span>'+$(this).next().html()+'</span><a href="#">x</a></li>';
            $(".applied-filters ul").append(filterItem);
        }else{
            me.parent().removeClass("checked");
            $(".applied-filters ul li[filterid='"+ $(this).attr("id") +"']").remove();
        }

        me.parent().siblings().each(function(){
            if($(this).hasClass("checked")==false){
                me.parent().insertBefore($(this));
                return false;
            }
        });
    });
    $(document).on("click",".applied-filters ul li a",function(e){
        e.preventDefault();
        var filterId="#"+$(this).parent().attr("filterid");
        $(this).parent().remove();
        $(filterId).trigger("click");

    });
}
var addToCompare = function(){
    $(document).on("click",".add-to-compare input",function(){
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
    addToCompare();
    if($(".filters").height() < $(window).height()-150 )
        $(".filters").height($(window).height()-150);

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
        }else{
            $(".product-images-slide-show .prev-slide").show();
        }
        var len = $(".product-images-slide-show .slides-wrap .img-slide").length;
        if(len==1){
            $(".product-images-slide-show .next-slide").hide();
        }
        if(len-1 == index){
            $(".product-images-slide-show .next-slide").hide();
        }else{
            $(".product-images-slide-show .next-slide").show();
        }
    });

    /*** start cart-quick-view ***/
    if($(window).width()>767){
      $(".cart-view").click(function(e){
          e.stopPropagation();
          $('.quick-view').toggleClass('open');
          $("body").css("overflow","hidden");
          $("body").css("position","relative");
          $(".mask").css("display", "block");
          $(".logo-wrap").css("z-index","9");
      });
      $("body").click(function(){
            $('.quick-view').removeClass('open');
             $("body").css("overflow","auto");
             $("body").css("position","initial");
            $(".mask").css("display", "none");
            $(".logo-wrap").removeAttr("style");
      });
      $("#hide").click(function(){
            $('.quick-view').removeClass('open');
             $("body").css("overflow","auto");
             $("body").css("position","initial");
             $(".mask").css("display", "none");
             $(".logo-wrap").removeAttr("style");
      });
      $(".quick-view").click(function(e){
        e.stopPropagation();
      });
    }
    else{
      $(".cart-view").click(function(e){
      location.href = "/checkout/listing";
      });
    }
    /*** end cart-quick-view ***/
});
$(window).resize(function(){
    if($(window).width()>=768){
        $(".footer-menu .desc").show();
        $(".footer-menu .head").removeClass("open");
        $(".categories-wrap,.category-list-item .sub-categories").removeAttr("style");
    }else{
        $(".footer-menu .desc").hide();
    }

    if($(".filters").height() < $(window).height()-150 )
        $(".filters").height($(window).height()-150);

    $(".filters-wrap").height($(".filters").height());
});


$(window).scroll(function(){
    var scrollAmt = $(window).scrollTop();
    if(scrollAmt > 30){
        $("#header").addClass("fixed")
    }else{
        $("#header").removeClass("fixed")
    }


})
