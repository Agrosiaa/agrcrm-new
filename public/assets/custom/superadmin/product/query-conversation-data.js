
function getConversationData(id,name){
    App.blockUI({boxed:!0});
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN' : $("#tokenss").val() } });
    $.ajax({
        url: "/operational/products/query-conversation",
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
                $('#itembase_sku').html(name);
                $('#product_id').val(id);
            }
            $.ajax({
                url:"/operational/products/query-count",
                async: false,
                type: "GET",
                error: function(data,err){

                },
                success: function(data,textStatus,xhr){
                    if(xhr.status == 200){
                        if(data.count > 0){
                            $('#query-count').text(data.count);
                        }else{
                            $('#query-count').hide();
                        }
                    }
                }
            });
            $('#query-raised').modal('show');
        },
        type: 'POST'
    });
    $.ajax({
        url: "/operational/products/query-status",
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
    App.unblockUI();
}

