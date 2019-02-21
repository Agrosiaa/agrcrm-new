/**
 * Created by sagar on 6/1/16.
 */
$(document).ready(function(){
    $('#state').on('change', function() {
        var state = $(this).val();
        if (state == null || state==''){
            $("#city").prop("disabled", true);
        }else{
            $("#city").prop("disabled", false);
            $.ajax({
                url: "/city/"+state,
                error: function(data) {
                    $("#city").prop("disabled", false);
                },
                success: function(data, textStatus, xhr) {
                    if(xhr.status==200){
                        var selectCityDropdown = '<option value="">Select...</option>';
                        $.each( data.city, function( index, value ){
                            selectCityDropdown = selectCityDropdown + '<option value="'+value.id+'">'+value.name+'</option>';
                        });
                        $('#city').html(selectCityDropdown);
                    }else{
                        $("#city").prop("disabled", false);
                    }

                },
                type: 'GET'
            });
        }
    });
});