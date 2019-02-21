$("#payment_method").submit(function(e){
    e.preventDefault();
    $('#form-submit').prop('disabled',true);
    var slug = $("input[name='payment_method_id']:checked").data("slug");
    if(slug=='cod'){
        $("#payment_method").unbind().submit();
    }else{
        var cartId = $("input[name='cart_items[]']").map(function(){return $(this).val();}).get();
        var rememberToken = $('meta[name="csrf_token"]').attr('content');
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN' : rememberToken } });
        var finalCartData;
        var deliveryType = $("input[name='delivery_type_id']:checked").val();
        var icpClosed = false;
        var addressId = $("input[name='customer_address_id']:checked").val()
        var dataObj;
        $.ajax({
            url: '/checkout/details',
            async:false,
            data: {'cartId':cartId,'deliveryType':deliveryType, 'addressId': addressId},
            error: function(data, textStatus, xhr) {
                alert("something went wrong");
                dataObj = null;
            },
            success: function(data, textStatus, xhr) {
                if(xhr.status == 200){
                    dataObj = data;
                }
            },
            type: 'POST'
        });
        try {
            var configObj = {
                icpUrl: $('#icp_url').val(),
                eventHandler: function(cbObj) {
                    if (cbObj.event === 'icpLaunched') {
                        console.log('Citrus ICP pop-up is launched');
                    } else if (cbObj.event === 'icpClosed') {
                        if(icpClosed == false){
                            icpClosed = true;
                            console.log('Citrus ICP pop-up is closed');
                            var citrusResponse = cbObj.message;
                            if(citrusResponse.TxStatus=="SUCCESS"){
                                var formData = $("#payment_method").serialize();
                                formData = formData + "&citrusData=" + JSON.stringify(citrusResponse)
                                var rememberToken = $('meta[name="csrf_token"]').attr('content');
                                $.ajaxSetup({ headers: { 'X-CSRF-TOKEN' : rememberToken,'Access-Control-Allow-Origin':'*' } });
                                $.ajax({
                                    url: '/checkout/submit',
                                    data: formData,
                                    error: function(data, textStatus, xhr) {
                                    window.location.href = '/'; //Change this to error page url
                                    },
                                    success: function(data, textStatus, xhr) {
                                        if(xhr.status==200){
                                            window.location.href = '/order/success/'+data.orderId;
                                        }
                                    },
                                    type: 'POST'
                                });
                            }else if(citrusResponse.TxStatus=="FAIL"){
                                var formData = $("#payment_method").serialize();
                                formData = formData + "&citrusData=" + JSON.stringify(citrusResponse)
                                var rememberToken = $('meta[name="csrf_token"]').attr('content');
                                $.ajaxSetup({ headers: { 'X-CSRF-TOKEN' : rememberToken,'Access-Control-Allow-Origin':'*' } });
                                $.ajax({
                                    url: '/checkout/submit',
                                    data: formData,
                                    error: function(data, textStatus, xhr) {
                                        window.location.href = '/checkout/auth'; //Change this to error page url
                                    },
                                    success: function(data, textStatus, xhr) {
                                        if(xhr.status==200){
                                            window.location.href = '/checkout/auth'; //Change it to success page url
                                        }
                                    },
                                    type: 'POST'
                                });
                            }else if(citrusResponse.TxStatus=="CANCELED"){
                                var formData = $("#payment_method").serialize();
                                formData = formData + "&citrusData=" + JSON.stringify(citrusResponse)
                                var rememberToken = $('meta[name="csrf_token"]').attr('content');
                                $.ajaxSetup({ headers: { 'X-CSRF-TOKEN' : rememberToken,'Access-Control-Allow-Origin':'*' } });
                                $.ajax({
                                    url: '/checkout/submit',
                                    data: formData,
                                    error: function(data, textStatus, xhr) {
                                        window.location.href = '/checkout/auth'; //Change this to error page url
                                    },
                                    success: function(data, textStatus, xhr) {
                                        if(xhr.status==200){
                                            window.location.href = '/checkout/auth'; //Change it to success page url
                                        }
                                    },
                                    type: 'POST'
                                });
                            }
                        }
                    }
                }
            }
            citrusICP.launchIcp(dataObj,configObj);
        }
        catch(error) {
            console.log(error);
        }
    }
});