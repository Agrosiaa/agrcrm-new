$(document).ready(function(){
    /* Measuring Unit functionality */
    var inputValue = $('#input_type_id').val();
    var measuringUnit = $('#measuring-units').val();
    if(inputValue=='text'){
        if(measuringUnit==1){
            $('#show-selected').show();
            //$('#unit-type').prop("disabled", false);
            $('#excel_column_measurable_unit_description').prop("disabled", false);
            $('#excel_column_measurable_unit_input_type_description').prop("disabled", false);
            $('#excel_column_measurable_unit_example').prop("disabled", false);
            $("#excel_column_measurable_unit_description").rules("add", {
                required: true,
                alpha_specialchars :true
            });
            $("#excel_column_measurable_unit_input_type_description").rules("add", {
                required: true,
                alpha_specialchars :true
            });
            $("#excel_column_measurable_unit_example").rules("add", {
                alpha_specialchars :true
            });
        }else{
            //$('#tab_1_1_2_b').hide();
            $('#show-selected').hide();
            //$('#unit-type').prop("disabled", true);
            $('#excel_column_measurable_unit_description').prop("disabled", true);
            $('#excel_column_measurable_unit_input_type_description').prop("disabled", true);
            $('#excel_column_measurable_unit_example').prop("disabled", true);

            $("#excel_column_measurable_unit_description").rules("remove");
            $("#excel_column_measurable_unit_input_type_description").rules("remove");
            $("#excel_column_measurable_unit_example").rules("remove");
        }
        //$('#measuring-units').prop("disabled", false);

        $('#tab_1_1_2_b').hide();
        /* Dynamic Remove Validation If input type is not select */
        //$("#feature_option").rules("remove");
        //$("#feature_option_value").rules("remove");
    }else{
        $('#unit-type').prop("disabled", true);
        $('#excel_column_measurable_unit_description').prop("disabled", true);
        $('#excel_column_measurable_unit_input_type_description').prop("disabled", true);
        $('#excel_column_measurable_unit_example').prop("disabled", true);
        $('#show-selected').hide();
        $('#tab_1_1_2_b').show();
        $('#measuring-units').prop("disabled", true);
        /* Dynamic Validation If input type is not select */
        $("#feature_option").rules("add", {
            required: true,
            minlength:3,
            maxlength:40,
        });
        /*$("#feature_option_value").rules("add", {
         required: true,
         number:true,
         maxlength:2,
         maxlenght:3
         });*/
        $("#excel_column_measurable_unit_description").rules("remove");
        $("#excel_column_measurable_unit_input_type_description").rules("remove");
        $("#excel_column_measurable_unit_example").rules("remove");
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
$("#unit-type").on("change",function (e) {
    /* Measuring Unit functionality */
    $('#show-selected').html($('#unit-type').find('option:selected').attr('customValue'));
    /* End */
});
$("#input_type_id").on("change",function (e) {
    var inputValue = $('#input_type_id').val();
    var measuringUnit = $('#measuring-units').val();
    if(inputValue=='text'){
        if(measuringUnit==1){
            $('#show-selected').show();
            $('#show-selected').html($('#unit-type').find('option:selected').attr('customValue'));
            $('#unit-type').prop("disabled", false);
            $('#excel_column_measurable_unit_description').prop("disabled", false);
            $('#excel_column_measurable_unit_input_type_description').prop("disabled", false);
            $('#excel_column_measurable_unit_example').prop("disabled", false);
            $("#excel_column_measurable_unit_description").rules("add", {
                required: true,
                alpha_specialchars :true,
            });
            $("#excel_column_measurable_unit_input_type_description").rules("add", {
                required: true,
                alpha_specialchars :true,
            });
            $("#excel_column_measurable_unit_example").rules("add", {
                alpha_specialchars :true,
            });
        }else{
            $('#show-selected').hide();
            $('#unit-type').prop("disabled", true);
            $('#excel_column_measurable_unit_description').prop("disabled", true);
            $('#excel_column_measurable_unit_input_type_description').prop("disabled", true);
            $('#excel_column_measurable_unit_example').prop("disabled", true);

            $("#excel_column_measurable_unit_description").rules("remove");
            $("#excel_column_measurable_unit_input_type_description").rules("remove");
            $("#excel_column_measurable_unit_example").rules("remove");
        }
        $('#measuring-units').prop("disabled", false);
        $('#tab_1_1_2_b').hide();
        /* Dynamic Remove Validation If input type is not select */
        $("#feature_option").rules("remove");
        //$("#feature_option_value").rules("remove");
    }else{
        $('#unit-type').prop("disabled", true);
        $('#excel_column_measurable_unit_description').prop("disabled", true);
        $('#excel_column_measurable_unit_input_type_description').prop("disabled", true);
        $('#excel_column_measurable_unit_example').prop("disabled", true);
        $('#show-selected').hide();
        $('#tab_1_1_2_b').show();
        $('#measuring-units').prop("disabled", true);
        /* Dynamic Validation If input type is not select */
        $("#feature_option").rules("add", {
            required: true,
            minlength:3,
            maxlength:40,
        });
        /*$("#feature_option_value").rules("add", {
         required: true,
         number:true,
         maxlength:2,
         maxlenght:3
         });*/
        $("#excel_column_measurable_unit_description").rules("remove");
        $("#excel_column_measurable_unit_input_type_description").rules("remove");
        $("#excel_column_measurable_unit_example").rules("remove");
    }
});
$("#measuring-units").on("change",function (e) {
    var inputValue = $('#input_type_id').val();
    var measuringUnit = $('#measuring-units').val();
    if(inputValue=='text'){
        if(measuringUnit==1){
            $('#show-selected').show();
            $('#unit-type').prop("disabled", false);
            $('#excel_column_measurable_unit_description').prop("disabled", false);
            $('#excel_column_measurable_unit_input_type_description').prop("disabled", false);
            $('#excel_column_measurable_unit_example').prop("disabled", false);
            $("#excel_column_measurable_unit_description").rules("add", {
                required: true,
                alpha_specialchars :true,
            });
            $("#excel_column_measurable_unit_input_type_description").rules("add", {
                required: true,
                alpha_specialchars :true,
            });
            $("#excel_column_measurable_unit_example").rules("add", {
                alpha_specialchars :true,
            });
        }else{
            $('#show-selected').hide();
            $('#unit-type').prop("disabled", true);
            $('#excel_column_measurable_unit_description').prop("disabled", true);
            $('#excel_column_measurable_unit_input_type_description').prop("disabled", true);
            $('#excel_column_measurable_unit_example').prop("disabled", true);

            $("#excel_column_measurable_unit_description").rules("remove");
            $("#excel_column_measurable_unit_input_type_description").rules("remove");
            $("#excel_column_measurable_unit_example").rules("remove");
        }
        /* Dynamic Remove Validation If input type is not select */
        $("#feature_option").rules("remove");
        //$("#feature_option_value").rules("remove");
    }else{
        $('#unit-type').prop("disabled", true);
        $('#excel_column_measurable_unit_description').prop("disabled", true);
        $('#excel_column_measurable_unit_input_type_description').prop("disabled", true);
        $('#excel_column_measurable_unit_example').prop("disabled", true);
        $('#show-selected').hide();
        /* Dynamic Validation If input type is not select */
        $("#feature_option").rules("add", {
            required: true,
            minlength:3,
            maxlength:40,
        });
        /*$("#feature_option_value").rules("add", {
         required: true,
         number:true,
         maxlength:2,
         maxlenght:3
         });*/
        $("#excel_column_measurable_unit_description").rules("remove");
        $("#excel_column_measurable_unit_input_type_description").rules("remove");
        $("#excel_column_measurable_unit_example").rules("remove");
    }
});