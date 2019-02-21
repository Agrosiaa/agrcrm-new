var language = $('#language').val();
$("#seller_name").change(function() {
    var id = this.value;
    var role = $("#user_role").val();
        var route='/operational/products/get-address/'+id;

    $.get(route,function(res){
        var str = "";
        for(var i=0; i<res.length; i++){
            str+='<option value='+res[i]['id']+'>'+res[i]['address_unique_name']+'</option>';
        }
        $('#seller_address_id').html(str);
    });
});

$("#district").change(function() {
    var name = this.value;
    var route='/get-taluka/'+name;
    $.ajax({
        url:route,
        type:'GET',
        async: true,
        success: function(data,textStatus,xhr){
            if(language == "mr"){
                var str = '<option value="">तालुका निवडा </option>';
            }else{
                var str = '<option value="">Please Select Taluka </option>';
            }
            for(var i=0; i<data.length; i++){
                taluka = data[i]['taluka'].replace(/\s+/g, '-');
                str+='<option value='+taluka+'>'+data[i]['taluka']+'</option>';
            }
            $('#taluka').html(str);
        },
        error: function(data,textStatus,xhr){

        }
    });
    $('#taluka').prop("disabled",false);
    $('#pincode').val('');
    $('#at_post').val('');
});

$("#taluka").change(function() {
    var name = this.value;
    $('#at_post').prop("disabled",false);
    $('#pincode').val('');
    $('#at_post').val('');
});

