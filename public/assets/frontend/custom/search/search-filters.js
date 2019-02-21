var language = $('#language').val();
function applyBrandAndFilters() {
    var brands = $("#url_brands").val();
    if(brands != 0) {
        brands = brands.split(",");
        for (var i in brands) {
            var me = $("#brand"+brands[i]);
            me.attr('checked', true);
            me.parent().addClass("checked");
            var filterItem = '<li filterid="'+ me.attr("id") +'"><span>'+me.next().html()+'</span><a href="#">x</a></li>';
            $(".applied-filters ul").append(filterItem);
        }
    }
    var features = $("#url_features").val();
    if(features != 0) {
        features = features.split(",");
        for (var i in features) {
            var me = $("#"+features[i]);
            me.attr("checked", true);
            me.parent().addClass("checked");
            var filterItem = '<li filterid="'+ me.attr("id") +'"><span>'+me.next().html()+'</span><a href="#">x</a></li>';
            $(".applied-filters ul").append(filterItem);
        }
    }
    $(".categories input:checkbox:checked").each(function(){
        var me = $(this);
        me.parent().addClass("checked");
        var filterItem = '<li filterid="'+ me.attr("id") +'"><span>'+me.next().html()+'</span><a href="#">x</a></li>';
        $(".applied-filters ul").append(filterItem);
    });

    if(minP != minPrice || maxP!=maxPrice){
        $('li[filterid="ex12c"]').remove();
        var filterItem = '<li filterid="ex12c"><span>'+(language == 'mr' ? "किंमत:" : "Price:")+minP+'-'+maxP+'</span><a href="#">x</a></li>';
        $(".applied-filters ul").append(filterItem);
    }

    if(minD != minDiscount || maxD != maxDiscount){
        $('li[filterid="ex13c"]').remove();
        var filterItem = '<li filterid="ex13c"><span>'+(language == 'mr' ? "सूट:" : "Discount:")+minD+'-'+maxD+'</span><a href="#">x</a></li>';
        $(".applied-filters ul").append(filterItem);
    }
}

$(document).ready(function(){
    $("#brand_search").on('keyup',function(){
        var searchKeyword = $(this).val().toLowerCase();
        $('.brands input[name="brands[]"]').each(function(){
            var tempBrand = $(this).next().text().toLowerCase();
            if(tempBrand.indexOf(searchKeyword) >= 0){
                $(this).parent().attr('hidden', false);
            }else{
                $(this).parent().attr('hidden', true);
            }
        });
    });
});
