
function getConversationData(id,name){
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN' : $("#tokenss").val() } });
    $.ajax({
        url: "/verification/product/query-conversation",
        data: {'id':id},
        async:false,
        error: function(xhr,err) {
            $('#product_id').val(id);
            $('#itembase_sku').html(name);
            $('#query-raised').modal('show');
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
        },
        type: 'POST'
    });
    $.ajax({
        url: "/verification/product/query-status",
        data: {'id':id},
        async:false,
        error: function(xhr,err) {
            $('#product_id').val(id);
        },
        success: function(data, textStatus, xhr) {
            if(xhr.status == 200){
                if(data == 'show'){
                    $('#query-form').show();
                }else{
                    $('#query-form').hide();
                }
            }
        },
        type: 'POST'
    });
}

