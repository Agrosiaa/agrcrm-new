var language = $('#language').val();
var FormValidation = function () {

    // basic js
    var handleValidation = function() {
        // for more info visit the official plugin documentation:
        // http://docs.jquery.com/Plugins/Validation

        var form1 = $('#update_profile');
        var error1 = $('.alert-danger', form1);
        var userId = $("#current-id").val();
        var success1 = $('.alert-success', form1);
        $.validator.addMethod("alpha", function(value, element) {
            return this.optional(element) || /^[A-z]+$/.test(value);
        });
        $.validator.addMethod("chkMail", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i.test(value);
        });
        form1.validate({

            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: ":hidden",  // validate all fields including form hidden input
            messages: {
                first_name: {
                    required: (language == 'mr') ? " प्रथम नाव आवश्यक आहे." : "First Name is required.",
                    alpha: (language == 'mr')  ? "नावात फक्त अक्षरे असणे आवश्यक आहे" : "First name must contain only letters.",
                    minlength: jQuery.validator.format((language == 'mr') ? "वर्तमान संकेतशब्दामध्ये किमान {0} वर्ण असणे आवश्यक आहे." : "Please enter at least {0} characters."),
                    maxlength: jQuery.validator.format((language == 'mr') ?  "वर्तमान संकेतशब्दामध्ये किमान {0} वर्ण असणे आवश्यक आहे." : "Please enter no more than {0} characters.")
                },
                last_name: {
                    required: (language == 'mr') ? "आडनाव आवश्यक आहे" : "Last Name is required.",
                    alpha: (language == 'mr') ? "आडनावात केवळ अक्षरे असणे आवश्यक आहे." : "Last name must contain only letters.",
                    minlength: jQuery.validator.format((language == 'mr') ? "वर्तमान संकेतशब्दामध्ये किमान {0} वर्ण असणे आवश्यक आहे." : "Please enter at least {0} characters."),
                    maxlength: jQuery.validator.format((language == 'mr') ?  "वर्तमान संकेतशब्दामध्ये किमान {0} वर्ण असणे आवश्यक आहे." : "Please enter no more than {0} characters.")
        },
                email:{
                    chkMail: (language == 'mr') ?  "कृपया वैध ईमेल प्रविष्ट करा."  :  "Please enter valid email.",
                    remote:(language == 'mr') ? "हा ईमेल पत्ता आधीपासूनच नोंदणीकृत आहे"  :  "This email address has already been registered"

        },
                profile_image: {
                    accept: (language == 'mr') ? "कृपया केवळ jpeg | jpg | png स्वरुपात प्रतिमा प्रविष्ट करा." :  "Please enter image only in jpeg|jpg"
                }
            },
            rules: {
                first_name: {
                    minlength: 3,
                    maxlength: 15,
                    required: true,
                    alpha: true
                },
                last_name: {
                    minlength: 3,
                    maxlength: 15,
                    required: true,
                    alpha: true
                },
                email:{
                    chkMail:true,
                    remote: {
                        type: "POST",
                        url: "/my-account/check-email/"+userId
                    }
                },
                profile_image: {
                    accept:"jpeg|jpg|png"
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
                    .closest('.form-group').removeClass('has-error'); // set success class to the control group
            },

            submitHandler: function (form1) {
                success1.show();
                error1.hide();
                return true;
            }
        });


    }
    var handleValidation1 = function() {
        // for more info visit the official plugin documentation:
        // http://docs.jquery.com/Plugins/Validation

        var form1 = $('#change_password');
        var error1 = $('.alert-danger', form1);
        var success1 = $('.alert-success', form1);

        form1.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",  // validate all fields including form hidden input
            messages: {
                current_password: {
                    required: (language == 'mr') ? "वर्तमान संकेतशब्द आवश्यक आहे." : "Current password is required.",
                    minlength: jQuery.validator.format((language == 'mr') ? "वर्तमान संकेतशब्दामध्ये किमान {0} वर्ण असणे आवश्यक आहे." : "Current Password must contain at least {0} characters."),
                    remote: (language == 'mr') ? "वर्तमान संकेतशब्द चुकीने जुळला" : "Current password mis-matched"
                },
                password: {
                    required: (language == 'mr') ? "नवीन पासवर्ड आवश्यक आहे." : "New password is required.",
                    minlength: jQuery.validator.format((language == 'mr') ? "संकेतशब्दामध्ये किमान {0} वर्ण असणे आवश्यक आहे." : "Password must contain at least {0} characters."),
                    maxlength: jQuery.validator.format((language == 'mr') ? "संकेतशब्दामध्ये किमान {0} वर्ण असणे आवश्यक आहे." : "Password must contain at least {0} characters.")
                },
                password_confirmation: {
                    required: (language == 'mr') ? "पासवर्डची पुष्टी आवश्यक आहे" : "Confirm password is required.",
                    minlength: jQuery.validator.format((language == 'mr') ? "वर्तमान संकेतशब्दामध्ये किमान {0} वर्ण असणे आवश्यक आहे." : "Confirm password must contain at least {0} characters."),
                    maxlength: jQuery.validator.format((language == 'mr') ?  "वर्तमान संकेतशब्दामध्ये किमान {0} वर्ण असणे आवश्यक आहे." : "Confirm password must contain at least {0} characters."),
                    passwordEqual: (language == 'mr') ? "Please enter the new password again." : "Please enter the new password again."
                }
            },
            rules: {
                current_password: {
                    required: true,
                    minlength: 6,
                    remote:{
                        type: "POST",
                        url: "/my-account/check-password"
                    }
                },
                password: {
                    required: true,
                    minlength: 6,
                    maxlength:20
                },
                password_confirmation: {
                    required: true,
                    minlength: 6,
                    maxlength:20,
                    passwordEqual: "#password"
                }
            },

            invalidHandler: function (event, validator) { //display error alert on form submit
                success1.hide();
                error1.show();
               // App.scrollTo(error1, -200);
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

            submitHandler: function (form) {
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
    $.getScript("/assets/global/plugins/jquery-validation/js/additional-methods.min.js");
    FormValidation.init();
});
