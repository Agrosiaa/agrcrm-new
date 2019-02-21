function getOrderDetailsCitrus(id){
    var rememberToken = $('meta[name="csrf_token"]').attr('content');
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN' : rememberToken } });
    $.ajax({
        url: "/order/get-order-return-status",
        data: {'id':id},
        async:false,
        error: function(xhr,err) {
        },
        success: function(data, textStatus, xhr) {
            if(xhr.status==200){
                $('#product_info_citrus').html(data);
                $('#rma_reason').change(function() {
                    $('#other_rma_reason').css('display', ($(this).val() == 'other') ? 'block' : 'none');
                    if($(this).val() != 'other'){
                        $('#reason').val('');
                    }
                });
            }else{
            }
        },
        type: 'POST'
    });
}

function getOrderDetailsCOD(id){
    var rememberToken = $('meta[name="csrf_token"]').attr('content');
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN' : rememberToken } });
    $.ajax({
        url: "/order/get-order-return-status",
        data: {'id':id},
        async:false,
        error: function(xhr,err) {
        },
        success: function(data, textStatus, xhr) {
            if(xhr.status==200){
                $('#product_info_cod').html(data);
                $('#rma_reason').change(function() {
                    $('#other_rma_reason').css('display', ($(this).val() == 'other') ? 'block' : 'none');
                    if($(this).val() != 'other'){
                        $('#reason').val('');
                    }
                });
            }else{
            }
        },
        type: 'POST'
    });
}

