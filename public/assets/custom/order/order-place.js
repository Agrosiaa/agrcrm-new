function removeProduct(id) {
    $('#div_'+id).remove();
    $('#selected_products_div_'+id).remove();
    $('#product_qnt'+id).remove();
    $('#product_id_'+id).remove();
    if ( $('#check_out_preview').children().length == 0 ) {
        $('#no_product_div').show();
    }
}

$('#place_order_button').on('click',function () {
    $('#place_order').modal('show');
});
$('#schedule-button').on('click',function () {
    $('#schedule_modal').modal('show');
})
$('#select_product_modal').on('click',function () {
    var addressId = $('input[name=customer_address_id]:checked').val();
    if(addressId){
        $('#select_products').modal('show');
        $('#place_order').modal('hide');
        var str = $('#delivery_address_'+addressId).html();
        $('#address_id').val(addressId);
        $('#delivery_address').html(str);
    }else{
        $('#select_address_msg').show();
    }
});

$('#place_order_modal').on('click',function () {
    $('#place_order').modal('show');
    $('#select_products').modal('hide');
});

$('#confirm_order_modal').on('click',function () {
    $('#confirm_order').modal('show');
    $('#del_charge_div').hide();
    $('#referral_code').val('');
    $('#discount_div').hide();
    $('#referal_code_valid').text('');
    $('#select_products').modal('hide');
    var sum = 0;
    $('.product-price-total').each(function()
    {
        sum += parseFloat($(this).text());
    });
    if(sum <= 500){
        sum += 50;
        $('#del_charge_div').show();
    }
    $('#order_total').text(sum);
});

$('#apply_referral').on('click',function () {
    var referral = $('#referral_code').val();
    var uri = $("meta[name='api_base_url']").attr("content");
    var sum = 0;
    var discount = 0;
    var discountFloat = 0;
    $('.product-price-total').each(function()
    {
        sum += parseFloat($(this).text());
    });
    var discountedSum = sum;
    if(sum <= 500){
        $('#discount_div').hide();
        $('#order_total').text(sum);
        $('#referal_code_valid').removeClass("text-success");
        $('#referal_code_valid').addClass("text-danger");
        $('#referal_code_valid').text("Discount is applicable if order total grater than 500");
    }else{
        if(sum > 2500){
            discountFloat = (2 * sum)/100;
            discount = discountFloat.toFixed(2);
        }else{
            discount = 50;
        }
        discountedSum -= discount;
        $.ajax({
            url: uri+'/validate-referral',
            type: 'POST',
            dataType: 'array',
            data: {
                'referral' : referral
            },
            success: function(response){
                data= JSON.parse(response.responseText);
                if(data != null){
                    if(data.is_validate){
                        $('#referal_code_valid').removeClass("text-danger");
                        $('#referal_code_valid').addClass("text-success");
                        $('#referal_code_valid').text("Referral code applied successfully");
                        $('#discount_div').show();
                        $('#order_total').text(discountedSum);
                        $('#discount_val').text(discount);
                    }else{
                        $('#discount_div').hide();
                        $('#order_total').text(sum);
                        $('#referal_code_valid').removeClass("text-success");
                        $('#referal_code_valid').addClass("text-danger");
                        $('#referal_code_valid').text("Invalid referral code");

                    }
                }
            },
            error:function(response){
                data= JSON.parse(response.responseText);
                if(data != null){
                    if(data.is_validate){
                        $('#discount_div').show();
                        $('#referal_code_valid').removeClass("text-danger");
                        $('#referal_code_valid').addClass("text-success");
                        $('#referal_code_valid').text("Referral code applied successfully");
                        $('#order_total').text(discountedSum);
                        $('#discount_val').text(discount);
                    }else{
                        $('#discount_div').hide();
                        $('#order_total').text(sum);
                        $('#referal_code_valid').removeClass("text-success");
                        $('#referal_code_valid').addClass("text-danger");
                        $('#referal_code_valid').text("Invalid referral code");
                    }
                }
            }
        });
    }
});

function updateProductQuantity(id,add,price,minQnt,maxQnt) {
    var qnt = $('#product_'+id).val();
    if(add == true){
        if(qnt >= maxQnt){
            alert('Maximum allowed quantity for this product is '+maxQnt);
        }else{
            qnt++;
            price = price*qnt;
            $('#price_'+id).text(price);
            $('#products_price_'+id).text(price);
            $('#selected_product_qnt'+id).val(qnt);
            $('#product_'+id).val(qnt);
            $('#product_qnt'+id).val(qnt);
        }
    } else {
        if(qnt <= minQnt){
            alert('Minimum allowed quantity for this product is '+minQnt);
        }else{
            if(qnt > 0){
                qnt--;
                price = price*qnt;
                $('#price_'+id).text(price);
                $('#products_price_'+id).text(price);
                $('#selected_product_qnt'+id).val(qnt);
                $('#product_'+id).val(qnt);
                $('#product_qnt'+id).val(qnt);
            }
        }
    }

}

$('#select_order_modal').on('click',function () {
    $('#select_products').modal('show');
    $('#confirm_order').modal('hide');
});
