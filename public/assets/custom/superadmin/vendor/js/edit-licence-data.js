function getLicenceData(licenceName,id){
    $.ajax({
        url: "/operational/vendor/get-licence/"+licenceName+"/"+id,
        async:false,
        error: function(xhr,err) {
        },
        success: function(data, textStatus, xhr) {
            if(xhr.status==200){
                $('#licence-form-data').html(data);
                FormValidation.init();
            }else{
                console.log(xhr.responseText);
            }
        },
        type: 'GET'
    });
}