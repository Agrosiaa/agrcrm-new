$(document).ready(function(){
    $('#category_id').on('change', function() {
        var category = $(this).val();
        if (category == null || category==''){
            $("#sub_category").html('');
        }else{
            $.ajax({
                url: "/operational/category/get-sub/"+category,
                type: 'GET',
                success: function(data) {
                    var res= $.map(data['subCategory'],function(value){
                        return value;
                    });
                    if(res != null) {
                        var selectCategoryDropdown = '<option value="">Select...</option>';
                        for (var i = 0; i < res.length; i++) {
                            selectCategoryDropdown += '<option value="' + res[i]['id'] + '">' + res[i]['name'] + '</option>';
                        }
                        $("#subCategory").html('');
                        $('#subCategory').append(selectCategoryDropdown);
                    } else {
                        $('#subCategory').append('no record found');
                    }
                }

            });
        }
    });
    $("#subCategory").change(function () {
        $('input[name="sub_category"]').val(this.value);
    });

    $(".filter-cancel").on('click',function(){
        $(".filter input:text").each(function(){
            $(this).val('');
        });
    });

});