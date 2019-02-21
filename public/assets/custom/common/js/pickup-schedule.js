function getPickUpScheduleData(){
    var orderType = $('#order_type').val();
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN' : $("#tokenss").val() } });
    $.ajax({
        url: "/operational/order/get-pickup-schedule-data/"+orderType,
        async:false,
        error: function(xhr,err) {
        },
        success: function(data, textStatus, xhr) {
            if(xhr.status==200){
                $('#pickUpScheduleData').html(data);
            }

        },
        type: 'POST'
    });
}