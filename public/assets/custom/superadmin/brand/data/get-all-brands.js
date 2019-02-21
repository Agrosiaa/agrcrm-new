$.ajax({
    url: "/operational/brand/all",
    async:false,
    error: function(data,xhr,err) {
        console.log(data);
    },
    success: function(data, textStatus, xhr) {
        if(xhr.status==200){
            var options = '';
            $.each( data.brands, function( index, value ){
                options+="<option value='"+value.id+"'>"+value.name+"</option>"
            });
            $("#brand_master").html(options);
            $("#brand_edit").show();
        }
    },
    type: 'GET'
});