$(document).ajaxSuccess(function() {
    var ids = readCookie('products');
    var searchIDs = $.parseJSON('[' + ids + ']');
    var count = readCookie('count');
    for(var k in searchIDs) {
        var removeBtn = searchIDs[k];
        $('#'+removeBtn).prop('checked', true);
    }
    if(count < 2){
        $('#compare-btn').hide();
    }else{
        $('#compare-btn').show();
    }
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
});