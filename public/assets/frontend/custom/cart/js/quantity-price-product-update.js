var language = $('#language').val();
function updateQuantity(cartId,action,from){
    $("#sub_total").html('Loading...');
    var quantity = $('#cart_'+cartId).val();
    var rememberToken = $('meta[name="csrf_token"]').attr('content');
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN' : rememberToken } });
    $.ajax({
        url: "/cart/update/quantity",
        data: {'id':cartId,'action':action, 'quantity':quantity},
        async:false,
        error: function(data, textStatus, xhr) {
            //location.reload();
            $('#quantity_message').html(data.responseJSON['message']).show();
            if(data.status == 500){
                $('#quantity_message').text(data.responseJSON['message']).show();
            }else{
                $('#cart_'+cartId).val(data.responseJSON['cart']['product']['minimum_quantity']);
            }
        },
        success: function(data, textStatus, xhr) {
            if(xhr.status==200){
                $('#cart_'+cartId).val(data.cart['quantity']);
                $('#quantity_message').hide();
                $(".place-order").prop('disabled', false);
                if(from=='cart'){
                    $('#product_sub_total_'+cartId).html(data.cart['discounted_price']);
                }
                //location.reload();
            }else{
                //alert(xhr.responseText);
            }
        },
        type: 'POST'
    });
    updateCartPrice($("#price_update_url").val());
}
function removeFromCart(cartId){
    var removedSubtotal = parseFloat($("#cart_block_subtotal_"+cartId).val());
    var subtotal = parseFloat($("#sub_total").html());
    var newSubtotal = subtotal - removedSubtotal;
    $("#sub_total").html(newSubtotal);
    $("#cart_block_" + cartId).hide();
    $("#cart_block_undo_" + cartId).show();
    $("#cart_block_undo_" + cartId).css('margin-left','1%')
    $('#proceed_to_cart').attr("disabled","disabled");
    $('#sub_total_button').prop("disabled",true);
    $('#place-order').prop("disabled",true);
    $('#continue_to_payment').prop("disabled",true);
}

function removeFinal(cartId,from,rowId,role){
    var rememberToken = $('meta[name="csrf_token"]').attr('content');
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN' : rememberToken } });
    $.ajax({
        url: "/cart/remove",
        data: {'id':cartId,'_method':'PUT','role':role},
        async:false,
        error: function(data, textStatus, xhr) {
                location.reload();
        },
        success: function(data, textStatus, xhr) {
            if(xhr.status==200){
                if(from=='cart'){
                    if($(location).attr('pathname')=='/checkout/auth' || $(location).attr('pathname')=='/checkout/guest'){
                        selectDeliveryType($("input[name='delivery_type_id']:checked").val());
                    }
                    if(data.cartCount==0){
                        alert((language == 'mr') ? "कार्ट आता रिक्त आहे!" : 'Cart is now empty!');

                        $('#continue_to_payment').prop("disabled", true);
                    }
                    $('#cart_items_array_' + cartId).prop("disabled", true);
                }
                $("#cart_block_" + cartId).hide();
                $("#cart_block_undo_" + cartId).show();
                updateCartPrice($("#price_update_url").val());
                location.reload();
            }else{
                location.reload();
            }
        },
        type: 'POST'
    });
}

function updateCartPrice(url){
    var rememberToken = $('meta[name="csrf_token"]').attr('content');
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN' : rememberToken } });
    $.ajax({
        url: url,
        async:false,
        error: function(data, textStatus, xhr) {
            $("#sub_total").hide();
        },
        success: function(data, textStatus, xhr) {
            if(xhr.status==200){
                $("#sub_total").html(data.cartPrice);
                var finalAmount = parseInt(data.cartPrice) + parseInt($("#delivery_amount").val()) - parseInt(data.discount);
                $("#final_amount").html(finalAmount);
                $("#coupon_discount").html(data.discount);
                if(data.discount!=0) {
                    $("#coupon_message").show();
                }else {
                    $("#coupon_message").hide();
                }
            }else{
                $("#sub_total").hide();
            }
        },
        type: 'POST'
    });
}
function selectDeliveryType(deliveryTypeId){
    var url = $("#delivery_type_url").val();
    var rememberToken = $('meta[name="csrf_token"]').attr('content');
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN' : rememberToken } });
    $.ajax({
        url: url,
        async:false,
        data: {'delivery_type_id':deliveryTypeId},
        error: function(data, textStatus, xhr) {
            $("#final_amount").html(data.finalPrice);
            $("#delivery_amount").val(data.deliveryAmount);
            $("#delivery_charge").html(data.deliveryAmount);
            $("#coupon_discount").html(data.discount);
        },
        success: function(data, textStatus, xhr) {
            $(".delivery_time").html(data.deliveryDate);
            $("#delivery_charge").html(data.deliveryAmount);
            $("#final_amount").html(data.finalPrice);
            $("#delivery_amount").val(data.deliveryAmount);
            $("#coupon_discount").html(data.discount);
            if(data.discount!=0) {
                $("#coupon_message").show();
            }else {
                $("#coupon_message").hide();
            }
        },
        type: 'POST'
    });
}
function updateQuantities(cartId,action,from){
    $("#sub_total").html('Loading...');
    var quantity = $('#cart_'+cartId).val();
    var rememberToken = $('meta[name="csrf_token"]').attr('content');
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN' : rememberToken } });
    $.ajax({
        url: "/cart/update/quantity",
        data: {'id':cartId,'action':action},
        async:false,
        error: function(data, textStatus, xhr) {
            //location.reload();
            $('.quantity_update_message #quantity_message').html(data.responseJSON['message']).show();
            if(quantity > data.responseJSON['cart']['product']['maximum_quantity']){
                $('#cart_'+cartId).val(data.responseJSON['cart']['product']['maximum_quantity']);
            }else if(quantity < data.responseJSON['cart']['product']['minimum_quantity']){
                $('#cart_'+cartId).val(data.responseJSON['cart']['product']['minimum_quantity']);
            }else{
                $('#quantity_message').text(data.responseJSON['message']).show();
            }
            setTimeout(function() {
                $('#quantity_message').fadeOut('fast');
            }, 50000); //
        },
        success: function(data, textStatus, xhr) {
            if(xhr.status==200){
                $('#cart_'+cartId).val(data.cart['quantity']);
                if(from=='cart'){
                    $('#product_sub_total_'+cartId).html(data.cart['discounted_price']);
                }
               location.reload();
            }else{
                alert(xhr.responseText);
            }
        },
        type: 'POST'
    });
    updateCartPrice($("#price_update_url").val());
}

function restoreCartItem(cartId) {
    var cartBlockSubtotal = parseFloat($("#cart_block_subtotal_"+cartId).val());
    var subtotal = parseFloat($("#sub_total").html());
    $("#sub_total").html((subtotal + cartBlockSubtotal));
    $('#proceed_to_cart').attr("disabled",false);
    $('#sub_total_button').prop("disabled",false);
    $('#place-order').prop("disabled",false);
    $('#continue_to_payment').prop("disabled",false);
    $("#cart_block_" + cartId).show();
    $("#cart_block_undo_" + cartId).hide();
}

typingTimer = 0;
doneTypingInterval = 5000;
function inputKeyDown(){
    clearTimeout(typingTimer);
}

function inputKeyUp(cartId,action,from){
    clearTimeout(typingTimer);
    typingTimer = setTimeout(updateQuantity(cartId,action,from), doneTypingInterval);
}
