$(document).ready(function(){
    /* Measuring Unit functionality */
    var inputValue = $('#input_type_id').val();
    var measuringUnit = $('#measuring-units').val();
    if(inputValue=='text'){
        if(measuringUnit==1){
            $('#show-selected').show();
            //$('#unit-type').prop("disabled", false);

        }else{
            //$('#tab_1_1_2_b').hide();
            $('#show-selected').hide();
            //$('#unit-type').prop("disabled", true);

        }
        //$('#measuring-units').prop("disabled", false);

        $('#tab_1_1_2_b').hide();
        /* Dynamic Remove Validation If input type is not select */
        //$("#feature_option_value").rules("remove");
    }else{
        $('#show-selected').hide();
        $('#tab_1_1_2_b').show();
        /* Dynamic Validation If input type is not select */
        $("#feature_option").rules("add", {
            required: true,
            minlength:3,
            maxlength:40,
            alpha_specialchars_feature: true
        });
        /*$("#feature_option_value").rules("add", {
         required: true,
         number:true,
         maxlength:2,
         maxlenght:3
         });*/
    }
    /* End */

});
$('tbody').on('click', '.btn-remove', function(e) {
    e.preventDefault();
    $(this).parents()[1].remove();
});
$("form").on("click", ".new-option", function (e) {
    e.preventDefault();
    $("#feature_extra").append(" <tr'><td><input type='text' class='work_emp_name' name='feature_option[]' value=''></td><td><button class='btn red btn-remove' >Remove</button></td></tr>");
    $('form').data('validator', null);
});
