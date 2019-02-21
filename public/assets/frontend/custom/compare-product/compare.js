$(document).ready(function(){

 window.onload = function() {
     var categoryID = $('#category').val();
     var searchIDs = readCookie('products');
     var citiList = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('products'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: "/products/get-list?name=%QUERY"+"&id="+categoryID+"&preiwIDs="+searchIDs,
            type:'GET',
            filter: function(x) {
                return $.map(x, function (data) {
                    return {
                        name: data.product_name,
                        id: data.id
                    };
                });
            },
            wildcard: "%QUERY"
        }
    });

    citiList.initialize();
     $('.add-product .typeahead').typeahead(null, {
         display: 'name',
         displayKey:'id',
         source: citiList.ttAdapter(),
         templates: {
             empty: [
                 '<div class="empty-message">',
                 'Unable to find any product that match the current query',
                 '</div>'
             ].join('\n'),

             suggestion: Handlebars.compile('<div> <strong>{{name}}</strong></div>')
         }
     }).on('typeahead:selected', function (obj, datum) {
         var url = window.location.hostname;
         var POData = new Array();
         POData = $.parseJSON(JSON.stringify(datum));
         var ids = readCookie('products');
         var count = readCookie('count');
         var pids = ids.split(',');
         pids.push(POData['id']);
         ids = pids.join(',');
         count = pids.length;
         document.cookie = "products ="+ids+";domain="+url+";path=/";
         document.cookie = "count ="+count+";domain="+url+";path=/";
         var rememberToken = $('meta[name="csrf_token"]').attr('content');
         $.ajaxSetup({ headers: { 'X-CSRF-TOKEN' : rememberToken } });
         $.ajax({
             url: "/products/compare",
             async:false,
             data:{'id':ids,'count':count},
             type: 'POST',
             success: function(data, textStatus, xhr) {
                 $('.compare-products-wrap').html(data);
                 location.reload();
             }
         });
     })
         .on('typeahead:open', function (obj, datum) {

         });
}

    var ids = readCookie('products');
    var count = readCookie('count');
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
        if(count < 2) {
            $("#compare-btn").hide();
        } else{
            $("#compare-btn").show();
        }
        console.log('count in compare js'+count);
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
        if(count < 1) {
            $("#compare-ui").hide();
        }
        if(count < 2){
            $('#compare-btn').hide();
        }else{
            $('#compare-btn').show();
        }
        if(count < 3){
            $(".add-to-compare").show();
            $(".add-to-compare input:checkbox:not(:checked)").show();
            $(".add-to-compare input:checkbox:not(:checked) + label").css("visibility","visible");
        }
    });
});
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
$(document).on("click",".remove-compare-item",function(){
    var removeId = this.value;
    var productIds = readCookie('products');
    var productIds = productIds.split(',');
    productIds = jQuery.grep(productIds, function(value) {
        return value != removeId;
    });
    var url = window.location.hostname;
    //document.cookie = "products ="+productIds+";domain="+url+";path=/";
    count = productIds.length;
    document.cookie = "products ="+productIds+";domain="+url+";path=/";
    document.cookie = "count ="+count+";domain="+url+";path=/";
    var ids = readCookie('products');
    var count = readCookie('count');
    var rememberToken = $('meta[name="csrf_token"]').attr('content');
    if(count == 0){
        window.location.href = '/';
    }else{
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN' : rememberToken } });
    $.ajax({
        url: "/products/compare",
        async:false,
        data:{'id':ids,'count':count},
        type: 'POST',
        success: function(data, textStatus, xhr) {
            $('.compare-products-wrap').html(data);
            location.reload();
        }
    });
    }
});