var FormValidation = function () {

    // basic validation
    var handleValidation = function() {
        // for more info visit the official plugin documentation:
        // http://docs.jquery.com/Plugins/Validation
        var form = $('#form_feature_edit');
        var error = $('.alert-danger', form);
        var success = $('.alert-success', form);
        $.validator.addMethod("regex", function(value, element, regexpr) {
            return regexpr.test(value);
        });
        $.validator.addMethod("alpha_num_space", function(value, element) {
            return this.optional(element) || /^[0-9A-za-z][A-za-z0-9\s]+$/.test(value);
        }, "only alpha num & space are allowed, start with character.");
        $.validator.addMethod("alpha_num", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9]+$/.test(value);
        }, "only alpha num characters are allowed are allowed");
        $.validator.addMethod("float2left", function(value, element) {
            return this.optional(element) || /^[0-9]?[0-9]?(\.[0-9][0-9]?)?$/.test(value);
        }, "The field should be max 4 digit long with a precision of 2.");
        $.validator.addMethod("alpha_specialchars", function(value, element) {
            return this.optional(element) || /(^[A-Za-z0-9 \r\n,/&._-]+$)+/.test(value);
        }, "The field may only contain alphabets, numbers and special characters like , & . - _");
        $.validator.addMethod("float5left", function(value, element) {
            return this.optional(element) || /^[0-9]?[0-9]?[0-9]?[0-9]?[0-9]?(\.[0-9][0-9]?)?$/.test(value);
        }, "The field should be max 7 digit long with a precision of 2.");
        $.validator.addMethod("zip", function(value, element) {
            return this.optional(element) || /^[0-9]{6}(\-[0-9]{4})?$/.test(value);
        }, "The field must be 6 digit.");
        $.validator.addMethod("alpha_specialchars_feature", function(value, element) {
            return this.optional(element) || /(^[A-Za-z0-9 ,.]+$)+/.test(value);
        }, "The field may only contain alphabets, numbers and special characters like , .");
        form.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",  // validate all fields including form hidden input
            messages: {
                code:{
                    remote:'Code already exists! Please Enter unique code.'
                },
                name:{
                    remote:"Name already assigned to selected category."
                },
                category_id:{
                    remote:"Name already assigned to selected category."
                }
            },
            rules: {
                name: {
                    required: true,
                    alpha_num_space: true,
                    minlength:3,
                    maxlength:60,
                    remote: {
                        url: "/operational/feature/check-name",
                        type: "POST",
                        data: {
                            name: function() {
                                return $( "#name" ).val();
                            },
                            category_id: function() {
                                return $( "#category_id" ).val();
                            },
                            feature_id: function(){
                                return $("#feature_id").val();
                            }
                        }
                    }
                },
                code: {
                    required: true,
                    minlength:3,
                    alpha_num_space: true,
                    remote:{
                        url: "/operational/feature/check-code",
                        type: "post",
                        data:{
                            feature_id: function(){
                                return $("#feature_id").val();
                            }
                        }
                    },
                    maxlength:60,
                },
                visibility: {
                    required: true,
                },
                required: {
                    required: true,
                },
                searchable: {
                    required: true,
                },
                use_in_filter: {
                    required: true,
                },
                comparable: {
                    required: true,
                },
                category_id: {
                    required: true,
                    remote: {
                        url: "/operational/feature/check-name",
                        type: "POST",
                        data: {
                            name: function() {
                                return $( "#name" ).val();
                            },
                            category_id: function() {
                                return $( "#category_id" ).val();
                            },
                            feature_id: function(){
                                return $("#feature_id").val();
                            }
                        }
                    }
                },
                input_type_id: {
                    required: true,
                },
                excel_column_description: {
                    required: true,
                    alpha_specialchars :true,
                },
                excel_column_input_type_description: {
                    required: true,
                    alpha_specialchars :true,
                },
                excel_column_example: {
                    alpha_specialchars :true,
                },
                //"feature_option[]": {
                //    required: true,
                //    minlength:3,
                //    maxlength:20,
                //    alpha_num_space: true
                //
                //},
                //"feature_option_value[]": {
                //    required: true,
                //    number:true,
                //    maxlength:2,
                //    maxlenght:3
                //},
            },
            invalidHandler: function (event, validator) { //display error alert on form submit
                success.hide();
                error.show();
                App.scrollTo(error, -200);
            },
            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
                if($('#tab_1_1_1').find('div.has-error').length != 0){
                    $('#tab_1_1_1_a').css('color', 'red');
                }
                if($('#tab_1_1_2').find('help-block').length != 0){
                    $('#tab_1_1_2_b').css('color', 'red');
                }
                if($('#input_type_id').val()=='select'){
                    $("#measuring_description").find('.form-group').removeClass('has-error');
                }else{
                    if($('#measuring-units').val()==0){
                        $("#measuring_description").find('.form-group').removeClass('has-error');
                    }
                }
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element)
                    .closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function (label) {
                label
                    .closest('.form-group').addClass('has-success').removeClass('has-error');

                //.closest('.form-group').removeClass('has-error'); // set success class to the control group
            },
            submitHandler: function (form) {
                success.show();
                error.hide();
                form.submit();
            }
        });
    }
    return {
        //main function to initiate the module
        init: function () {
            handleValidation();
        }
    };
}();

jQuery(document).ready(function() {
    FormValidation.init();
});