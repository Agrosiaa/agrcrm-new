/**
 * Created by manoj on 4/27/16.
 */
$('#calulate').on('click', function () {
    var selling_price = $('#selling_price').val();
    var base_price = $('#base_price').val();
    var discount = $('#discount').val();
    var discount_price = Math.round(selling_price-((discount/100)*selling_price));

    $('#base_price').val(selling_price);
    $('#discount_price').val(discount_price);
});
