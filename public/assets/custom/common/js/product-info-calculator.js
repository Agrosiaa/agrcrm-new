function getSellingPrice(){
    if($('#check').prop('checked') == true){
        var data = {
            base_price: $('#base_price').val(),
            discount_percent: $('#discount').val(),
            discount_base_price: $('#discount_base_price').val(),
            commission: $('#commission').val(),
            logistic_tax: $("#logistic_tax").val(),
            tax_id:$("#all_taxes").val()
        }
    }else{
        var data = {
            base_price: $('#base_price').val(),
            discount_percent: $('#discount').val(),
            discount_base_price: $('#discount_base_price').val(),
            commission: $('#commission').val(),
            logistic_tax: $("#logistic_tax").val(),
            tax_id: $("#taxes").val()
        }
    }
    $.ajax({
        url: "/product/calculate-price",
        data: data,
        async:false,
        error: function(xhr,err) {
        },
        success: function(data, textStatus, xhr) {
            if(xhr.status==200){
                $("#discount").val(data['discountPercent']);
                $("#discount_base_price").val(data['discountedBasePrice']);
                $("#commission_amount").val(data['commissionAmount']);
                $("#gst_commission").val(data['gstOnCommission']);
                $("#logistic_amount").val(data['logisticAmount']);
                $("#gst_logistic").val(data['gstOnLogistic']);
                $("#subtotal_amount").val(data['subtotal']);
                $("#subtotal_final").val(data['subtotal']);
                $("#base_price_final").val(data['basePrice']);
                $("#gst_tax_amount").val(data['gstTaxAmount']);
                $("#discounted_selling_price").val(data['discountedSellingPrice']);
                $("#selling_price_without_discount").val(data['sellingPriceWithoutDiscount']);
                $("#discounted_price").val(data['discountedSellingPrice']);
            }
        },
        type: 'POST'
    });
}

function SellingPrice(){
    if($('#check').prop('checked') == true){
        var data = {
            base_price: $('#base_price').val(),
            discount_percent: $('#discount').val(),
            discount_base_price: $('#discount_base_price').val(),
            commission: $('#commission').val(),
            logistic_tax: $("#logistic_tax").val(),
            tax_id:$("#all_taxes").val()
        }
    }else{
        var data = {
            base_price: $('#base_price').val(),
            discount_percent: $('#discount').val(),
            discount_base_price: $('#discount_base_price').val(),
            commission: $('#commission').val(),
            logistic_tax: $("#logistic_tax").val(),
            tax_id: $("#taxes").val()
        }
    }
    $.ajax({
        url: "/product/calculate-price",
        data: data,
        async:false,
        error: function(xhr,err) {

        },
        success: function(data, textStatus, xhr) {
            if(xhr.status==200){
                $("#discount").val(data['discountPercent']);
                $("#discount_base_price").val(data['discountedBasePrice']);
                $("#commission_amount").val(data['commissionAmount']);
                $("#gst_commission").val(data['gstOnCommission']);
                $("#logistic_amount").val(data['logisticAmount']);
                $("#gst_logistic").val(data['gstOnLogistic']);
                $("#subtotal_amount").val(data['subtotal']);
                $("#subtotal_final").val(data['subtotal']);
                $("#base_price_final").val(data['basePrice']);
                $("#gst_tax_amount").val(data['gstTaxAmount']);
                $("#discounted_selling_price").val(data['discountedSellingPrice']);
                $("#selling_price_without_discount").val(data['sellingPriceWithoutDiscount']);
                $("#discounted_price").val(data['discountedSellingPrice']);
            }
        },
        type: 'POST'
    });
}

function getProductCategoryTaxes(id,product_id){
    $.ajax({
        url: "/product/category-taxes",
        data: {'id':id ,'product_id':product_id},
        async:false,
        error: function(xhr,err) {
        },
        success: function(data, textStatus, xhr) {
            if(xhr.status==200){
                $("#commission").val(data['percentage']['commission']);
                $("#logistic_tax").val(data['percentage']['logistic']);
            }
        },
        type: 'POST'
    });
}
function getAddProductCategoryTaxes(id){
    $.ajax({
        url: "/product/category-taxes-add",
        data: {'id':id},
        async:false,
        error: function(xhr,err) {
        },
        success: function(data, textStatus, xhr) {
            if(xhr.status==200){
                $("#commission").val(data['categoryInfo']['commission']);
                $("#logistic_tax").val(data['categoryInfo']['logistic_percentage']);
            }
        },
        type: 'POST'
    });
}