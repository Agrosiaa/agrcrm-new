function getOrderDetails(id){
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN' : rememberToken } });
    $.ajax({
        url: "/order/get-order-return-status",
        data: {'id':id},
        async:false,
        error: function(xhr,err) {
        },
        success: function(data, textStatus, xhr) {
            if(xhr.status==200){
                $('#conversation-list').html(data);
                $('#itembase_sku').html(name);
                $('#product_id').val(id);
            }else{
                $('#product_id').val(id);
                $('#itembase_sku').html(name);
            }
            $('#query-raised').modal('show');
        },
        type: 'POST'
    });
}