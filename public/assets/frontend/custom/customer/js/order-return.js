var FormValidation = function () {

    // basic js

    var handleValidation = function() {
        if(language == 'en'){
            var alphaSpace = "Please enter only contains alpha and space and starting with alphabet";
            var alpha_num_space = "only alpha num & space are allowed and starting with space not allowed";
            var ifsc = "Please enter in this format asdc0123456";
        }else{
            var alphaSpace = "केवळ अक्षरे आणि जागा अनुमत आहेत";
            var alpha_num_space = "केवळ अक्षरे आणि जागा अनुमत आहेत";
            var ifsc = "कृपया asdc0123456 या स्वरुपात प्रविष्ट करा";
        }
        // for more info visit the official plugin documentation:
        // http://docs.jquery.com/Plugins/Validation
        $.validator.addMethod("alphaSpace", function(value, element) {
            return this.optional(element) || value == value.match(/^[a-zA-Z]+[a-zA-Z\s]+$/);
        },alphaSpace);
        $.validator.addMethod("alpha_num_space", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9]+([a-zA-Z0-9\s])*$/.test(value);
        }, alpha_num_space);
        $.validator.addMethod("ifsc", function(value, element) {
            return this.optional(element) || value == value.match(/^[A-Za-z0-9]{11}$/);
        },ifsc);
        var form = $('#cod-order-return-form');
        var error = $('.alert-danger', form);
        var success = $('.alert-success', form);

        form.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: ":hidden",  // validate all fields including form hidden input
            messages: {

            },
            rules: {
                bank_name: {
                    required: true,
                    alphaSpace: true,
                    minlength: 3,
                    maxlength: 50
                },
                branch_name: {
                    required: true,
                    alpha_num_space: true,
                    minlength: 5,
                    maxlength: 50
                },
                ifsc_code: {
                    required: true,
                    ifsc: true
                },
                account_no: {
                    required: true,
                    digits: true,
                    minlength: 9,
                    maxlength: 16
                }
            },

            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").size() > 0) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.attr("data-error-container")) {
                    error.appendTo(element.attr("data-error-container"));
                } else if (element.parents('.radio-list').size() > 0) {
                    error.appendTo(element.parents('.radio-list').attr("data-error-container"));
                } else if (element.parents('.radio-inline').size() > 0) {
                    error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
                } else if (element.parents('.checkbox-list').size() > 0) {
                    error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
                } else if (element.parents('.checkbox-inline').size() > 0) {
                    error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },

            invalidHandler: function (event, validator) { //display error alert on form submit
                success.hide();
                error.show();
                //App.scrollTo(error1, -200);
            },

            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },

            unhighlight: function (element) { // revert the change done by hightlight
                $(element)
                    .closest('.form-group').removeClass('has-error'); // set error class to the control group
            },

            success: function (label) {
                label
                    .closest('.form-group').removeClass('has-error'); // set success class to the control group
            },

            submitHandler: function (form1) {
                success.show();
                error.hide();
                return true;
            }
        });
    }

    var handleValidation1 = function() {
        // for more info visit the official plugin documentation:
        // http://docs.jquery.com/Plugins/Validation

        var form1 = $('#cancel-return-request-form');
        var error1 = $('.alert-danger', form1);
        var success1 = $('.alert-success', form1);

        form1.validate({

            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: ":hidden",  // validate all fields including form hidden input
            messages: {

            },
            rules: {
                rma_cancel_text: {
                    required: true,
                    minlength: 3,
                    maxlength: 50
                }
            },

            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").size() > 0) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.attr("data-error-container")) {
                    error.appendTo(element.attr("data-error-container"));
                } else if (element.parents('.radio-list').size() > 0) {
                    error.appendTo(element.parents('.radio-list').attr("data-error-container"));
                } else if (element.parents('.radio-inline').size() > 0) {
                    error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
                } else if (element.parents('.checkbox-list').size() > 0) {
                    error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
                } else if (element.parents('.checkbox-inline').size() > 0) {
                    error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },

            invalidHandler: function (event, validator) { //display error alert on form submit
                success1.hide();
                error1.show();
                //App.scrollTo(error1, -200);
            },

            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },

            unhighlight: function (element) { // revert the change done by hightlight
                $(element)
                    .closest('.form-group').removeClass('has-error'); // set error class to the control group
            },

            success: function (label) {
                label
                    .closest('.form-group').addClass('has-success');
            },

            submitHandler: function (form1) {
                success1.show();
                error1.hide();
                return true;
            }
        });
    }

    return {
        //main function to initiate the module
        init: function () {
            handleValidation();
            handleValidation1();
        }
    };
}();

jQuery(document).ready(function() {
    FormValidation.init();
});
