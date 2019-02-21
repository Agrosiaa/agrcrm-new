function verifyOrderSummery(){
    var rememberToken = $('meta[name="csrf_token"]').attr('content');
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN' : rememberToken } });
    $.ajax({
        url: $("#order_summery_url").val(),
        async:false,
        error: function(data, textStatus, xhr) {
            if(data.status==404){
                alert('cart is empty');
            }else{
                alert('something went wrong');
            }
            window.location.href = '/';
        },
        success: function(data, textStatus, xhr) {
            if(xhr.status==200){
                $.each(data.cartData, function (index, value) {
                    //cart_67
                    $("#cart_"+value.id).val(value.quantity);
                    $("#product_unit_price_"+value.id).html(value.unit_price);
                    $("#product_sub_total_"+value.id).html(value.discounted_price);
                });
                updateCartPrice($("#price_update_url").val());
                if(data.productDeletedFlag==true){
                    alert(data.deletedProduct);
                }else if(data.outOfStockFlag == true){
                    alert(data.outOfStock);
                }else{
                    if(data.priceChangedFlag==true){
                        $("#price_modal_body").html(data.priceChangedProduct);
                        $('#price_modal').modal('show');
                    }else if(data.cartUpdatedFlag==true){
                        $("#price_modal_body").html(data.cartUpdated);
                        $('#price_modal').modal('show');
                    }else{
                        proceedToPayment();
                    }
                }
            }
        },
        type: 'POST'
    });
    return false;
}
function proceedToPayment(){
    var index = $("#continue_to_payment").parents(".checkout-item").index();
    $("#continue_to_payment").parents(".checkout-item").addClass("completed");
    $("#continue_to_payment").parents(".checkout-item").removeClass("active");
    $("#continue_to_payment").parents(".checkout-item").next().addClass("active");
    $(".checkout-steps ul li").eq(index+1).addClass("completed");
    $('html,body').animate({scrollTop: $(".payment-options").offset().top},'slow');
}

function codLimitCheck(){
    var totalAmount = parseInt($("#final_amount").text());
    if(totalAmount > 25000){
        $('.payment-message').show();
        $('.payment-method input[data-slug="cod"]').prop('hidden', true);
        $('.payment-method input[data-slug="cod"]').next().prop('hidden', true);
        $('.payment-method input[data-slug="citrus"]').prop('checked', true);
    }else{
        $('.payment-message').hide();
        $('.payment-method input[data-slug="cod"]').prop('hidden', false);
        $('.payment-method input[data-slug="cod"]').next().prop('hidden', false);
    }
}

$(document).ready(function(){
        $('.delivery-type input[value="2"]').prop('disabled', true);
        $('.delivery-type input[value="2"]').next().css('color','#BFBDBD');

    $('#atPost').on('change', function(){
        var postId = $(this).val();
        var pincode = $("#pincode").val();
        $.ajax({
            url:'/my-account/get-post-office-info/'+postId+'/'+pincode,
            method: 'GET',
            async: false,
            success: function(data,textStatus,xhr){
                $('#taluka').val(data.taluka);
                $('#district').val(data.district);
            },
            error: function(data){

            }
        });
    });

    var citiList = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('office_name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: "/my-account/get-pincode?pincode=%QUERY",
            filter: function(x) {
                return $.map(x, function (data) {
                    return {
                        pincode: data.pincode,
                        at_post: data.post_offices,
                        state: data.state
                    };
                });
            },
            wildcard: "%QUERY"
        }
    });
    var language = $('#language').val();
    citiList.initialize();
    $('#pincode').typeahead(null, {
        display: 'pincode',
        source: citiList.ttAdapter(),
        templates: {
            suggestion: Handlebars.compile('<div><input type="text" class="form-control"  style=" border: solid 1px deepskyblue ;padding-top: 5px ; color: black;" value="{{pincode}}"></div>')
        }
    }).on('typeahead:selected', function (obj, datum) {
        var POData = new Array();
        POData = $.parseJSON(JSON.stringify(datum));
        $('#pincode').val(POData["pincode"]);
        $('#atPost').html(POData["at_post"]);
        $('#state').val(POData["state"]);
        $('#atPost').trigger('change');
        $("#notifyMe").prop('disabled', true);
    }).on('typeahead:open', function (obj, datum) {
        //$('#pincode').val("");
        $('#atPost').html('');
        $('#taluka').val('');
        $('#district').val('');
        $('#state').val('');
    });
});

