$('#discount').on('change', function () {
    var discount = $('#discount').val();
    var base_price = $('#base_price').val();
    var discount_price = Math.round(base_price-((discount/100)*base_price));
    $('#discount_price').val(discount_price);
});

$('#base_price').on('blur', function () {
    var discount = $('#discount').val();
    var base_price = $('#base_price').val();
    var discount_price = Math.round(base_price-((discount/100)*base_price));
    $('#discount_price').val(discount_price);
});