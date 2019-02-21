var FormValidation = function () {

    // basic validation
    var handleValidation1 = function() {
        // for more info visit the official plugin documentation:
        // http://docs.jquery.com/Plugins/Validation

        var form1 = $('#personal_information');
        var error1 = $('.alert-danger', form1);
        var success1 = $('.alert-success', form1);

        $.validator.addMethod("alpha", function(value, element) {
            return this.optional(element) || /^[A-z]+$/.test(value);
        });
        $.validator.addMethod("mobileNumber", function(value, element) {
            return this.optional(element) || /^[0-9]{10}(\-[0-9]{4})?$/.test(value);
        });
        $.validator.addMethod("alphanumeric", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9]+$/.test(value);
        });
        $.validator.addMethod("chkMail", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i.test(value);
        });
        $.validator.addMethod("alphaSpace", function(value, element) {
            return this.optional(element) || value == value.match(/^[a-zA-Z]+[a-zA-Z\s]+$/);
        });
        $.validator.addMethod("addressValidate", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9]+[a-zA-Z0-9\s]+$/.test(value);
        });
        $.validator.addMethod("zip", function(value, element) {
            return this.optional(element) || /^[0-9]{6}$/.test(value);
        });

        form1.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: ":hidden",  // validate all fields including form hidden input
            messages: {
                first_name: {
                    required: "First Name is required.",
                    alpha: "First name must contain only letters."
                },
                last_name: {
                    required: "Last Name is required.",
                    alpha: "Last name must contain only letters."
                },
                state_id: {
                    required: "Please select State."
                },
                city_id: {
                    required:"Please select City."
                },
                mobile: {
                    required: "Mobile number is required.",
                    mobileNumber: "Please enter proper mobile number."
                },
                address: {
                    required: "Address is required.",
                    addressValidate: "Only alphabet, number & space are allowed."
                },
                pincode: {
                    required: "Zip code is required.",
                    number: "Zip code must be numeric.",
                    zip: "Zip code must be 6 digits only."
                },
                company: {
                    required: "Company name is required.",
                    alphaSpace: "Company name only contain alphabets and space."
                },
                gstin: {
                    required : "GSTIN is required",
                    alphaSpaceNumber: "GSTIN only contains alpha ,number and space and starting with space not allowed."
                },
                seller_name_abbreviation: {
                    required: "Seller name abbreviation is required.",
                    alpha: "Seller name abbreviation must contain only letters.",
                    remote: "Seller name abbreviation already used."
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
                state_id: {
                    required: true
                },
                city_id: {
                    required: true
                },
                mobile: {
                    required: true,
                    mobileNumber: true
                },
                address: {
                    required: true,
                    minlength: 5,
                    maxlength: 100,
                    addressValidate: true
                },
                pincode: {
                    required: true,
                    number: true,
                    zip: true
                },
                company: {
                    required: true,
                    alphaSpace: true
                },
                gstin: {
                    required : true,
                    alphaSpaceNumber: true,
                    minlength: 15,
                    maxlength: 15
                },
                seller_name_abbreviation: {
                    required: true,
                    maxlength: 3,
                    minlength: 3,
                    alpha: true,
                    remote: {
                        url: "/operational/vendor/check-abbreviation",
                        type: "POST"
                    }
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
                App.scrollTo(error1, -200);
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


    var handleValidation2 = function() {
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
                    required: "Current password is required.",
                    minlength: jQuery.validator.format("Current Password must contain at least {0} characters."),
                    maxlength: jQuery.validator.format("Current Password must contain less than {0} characters.")
                },
                password: {
                    required: "New password is required.",
                    minlength: jQuery.validator.format("Password must contain at least {0} characters."),
                    maxlength: jQuery.validator.format("Password must contain less than {0} characters.")
                },
                password_confirmation: {
                    required: "Confirm password is required.",
                    maxlength: jQuery.validator.format("Confirm Password must contain less than {0} characters."),
                    minlength: jQuery.validator.format("Confirm password must contain at least {0} characters.")
                }
            },
            rules: {
                current_password: {
                    required: true,
                    minlength: 5,
                    maxlength: 20
                },
                password: {
                    required: true,
                    minlength: 5,
                    maxlength: 20
                },
                password_confirmation: {
                    required: true,
                    minlength: 5,
                    maxlength: 20,
                    equalTo: "#password"
                }
            },

            invalidHandler: function (event, validator) { //display error alert on form submit
                success1.hide();
                error1.show();
                App.scrollTo(error1, -200);
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

    var handleValidation3 = function() {
        // for more info visit the official plugin documentation:
        // http://docs.jquery.com/Plugins/Validation

        var form1 = $('#bank_details');
        var error1 = $('.alert-danger', form1);
        var success1 = $('.alert-success', form1);

        $.validator.addMethod("alpha", function(value, element) {
            return this.optional(element) || /^[A-z]+$/.test(value);
        });
        $.validator.addMethod("alphanumeric", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9]+$/.test(value);
        });
        $.validator.addMethod("alphaSpace", function(value, element) {
            return this.optional(element) || value == value.match(/^[a-zA-Z]+[a-zA-Z\s]+$/);
        });
        $.validator.addMethod("alphaSpaceNumber", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9]+([a-zA-Z0-9\s])*$/.test(value);
        });
        $.validator.addMethod("ifsc", function(value, element) {
            return this.optional(element) || value == value.match(/^[A-Za-z0-9]{11}$/);
        });
        $.validator.addMethod("pan", function(value, element) {
            return this.optional(element) || value == value.match(/^[a-zA-Z]{5}[0-9]{4}[A-Za-z]{1}$/);
        });
        $.validator.addMethod("tan", function(value, element) {
            return this.optional(element) || value == value.match(/^[a-zA-Z]{4}[0-9]{5}[A-Za-z]{1}$/);
        });


        form1.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",  // validate all fields including form hidden input
            messages: {
                account_no: {
                    required: "Account number is required.",
                    number: "Account number must be numeric."
                },
                beneficiary_name: {
                    required: "Beneficiary name is required.",
                    alphaSpaceNumber: "Beneficiary name only contains alpha ,number and space and starting with space not allowed."
                },
                bank_name: {
                    required: "Bank name is required.",
                    alphaSpace: "Bank name only contains alpha and space and starting with alphabet."
                },
                branch_name: {
                    required: "Branch name is required.",
                    alphaSpaceNumber: "Branch name only contains alpha ,number and space and starting with space not allowed."
                },
                company_identification_number: {
                    alphanumeric: "Company registration only contains alpha ,number."
                },
                ifsc_code: {
                    required: "IFSC code is required.",
                    ifsc: "Provide proper IFSC code."
                },
                pan_number: {
                    required: "PAN number is required.",
                    pan: "Provide proper PAN number."
                },
                tan_number: {
                    tan: "Provide proper TAN number."
                },
                account_type: {
                    required: "Please select Account type."
                }
            },
            rules: {
                account_no: {
                    required: true,
                    number: true,
                    minlength: 9,
                    maxlength: 16

                },
                beneficiary_name: {
                    required: true,
                    alphaSpaceNumber: true,
                    minlength: 5,
                    maxlength: 50
                },
                bank_name: {
                    required: true,
                    alphaSpace: true,
                    minlength: 3,
                    maxlength: 50
                },
                branch_name: {
                    required: true,
                    alphaSpaceNumber: true,
                    minlength: 5,
                    maxlength: 50
                },
                company_identification_number: {
                    alphanumeric: true,
                    minlength: 5,
                    maxlength: 50
                },
                ifsc_code: {
                    required: true,
                    ifsc: true
                },
                pan_number: {
                    required: true,
                    pan: true
                },
                tan_number: {
                    tan: true
                },
                account_type: {
                    required: true
                }
            },

            invalidHandler: function (event, validator) { //display error alert on form submit
                success1.hide();
                error1.show();
                App.scrollTo(error1, -200);
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

    var handleValidation4 = function() {
        // for more info visit the official plugin documentation:
        // http://docs.jquery.com/Plugins/Validation

        var form1 = $('#profile_image');
        var error1 = $('.alert-danger', form1);
        var success1 = $('.alert-success', form1);

        form1.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",  // validate all fields including form hidden input
            messages: {
                profile_image: {
                    required: "Profile image is required.",
                    accept: "Please upload .jpeg, .jpg or .png image."
                }
            },
            rules: {
                profile_image: {
                    required: true,
                    accept:"jpeg|jpg|png"
                }
            },

            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").size() > 0) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.attr("data-error-container")) {
                    error.appendTo(element.attr("data-error-container"));
                }  else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit
                success1.hide();
                error1.show();
                App.scrollTo(error1, -200);
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

    var handleValidation5 = function() {
        // for more info visit the official plugin documentation:
        // http://docs.jquery.com/Plugins/Validation
        var role = $('#user_role').val();
        if(role == 'superadmin'){
            url = "/operational/vendor/check-address";
        }else{
            url = "/check-address";
        }
        var form1 = $('#add_address');
        var error1 = $('.alert-danger', form1);
        var success1 = $('.alert-success', form1);
        $.validator.addMethod("alphanumeric", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9]+$/.test(value);
        });
        $.validator.addMethod("alphanumeric_hyphen", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9-]+$/.test(value);
        }, "Please enter alpha num and - only");
        $.validator.addMethod("alphanumsymbols", function(value, element) {
            return this.optional(element) || /^[ A-Za-z0-9-,.]*$/i.test(value);
        });
        $.validator.addMethod("alpha_num_space_sym", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9]+([A-Za-z0-9/-])*$/.test(value);
        }, "only alpha num & / and - are allowed");
        $.validator.addMethod("alpha_num_space_allow", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9]+([ A-Za-z0-9@./#',&-])*$/.test(value);
        }, "only alpha num & space are allowed and starting with space and special char not allowed");
        form1.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",  // validate all fields including form hidden input
            messages: {
                address_unique_name: {
                    required: "Unique Address name is required.",
                    maxlength: "Maximum 20 characters allowed.",
                    alphanumeric_hyphen: "Only alpha numeric - is allowed.",
                    remote: "Address abbreviation is already used."
                },
                at_post: {
                    required: "At Post is required."
                },
                taluka: {
                    required: "Taluka is required."
                },
                district: {
                    required: "District is required."
                },
                state: {
                    required: "State is required."
                },
                pincode: {
                    required: "Pincode is required."
                },
                name_of_premise_building_village: {
                    required: "Name of premise /building/village is required."
                },
                shop_no_office_no_survey_no: {
                    required: "Shop_no/office_no/survey_no is required."
                },
                area_locality_wadi: {
                    required: "Area/locality/wadi is required."
                },
                road_street_lane: {
                    required: "Road/street/lane is required."
                    //alphanumsymbols: "Only alpha numeric and - , . is allowed."
                }

            },
            rules: {
                address_unique_name: {
                    required: true,
                    minlength: 10,
                    maxlength: 20,
                    alphanumeric_hyphen: true,
                    remote: {
                        url: url,
                        type: "POST",
                        data: {
                            user_id: function() {
                                return $( "#user_id" ).val();
                            }
                        }
                    }

                },
                at_post: {
                    required: true
                },
                taluka: {
                    required: true
                },
                district: {
                    required: true
                },
                state: {
                    required: true
                },
                pincode: {
                    required: true
                },
                name_of_premise_building_village: {
                    required: true,
                    minlength: 1,
                    maxlength: 25
                    //alpha_num_space_allow:true
                },
                shop_no_office_no_survey_no: {
                    required: true,
                    minlength: 1,
                    maxlength: 25
                    //alpha_num_space_sym:true
                },
                area_locality_wadi: {
                    required: true,
                    minlength: 1,
                    maxlength: 25
                    //alpha_num_space_allow:true
                },
                road_street_lane: {
                    required: true,
                    minlength: 1,
                    maxlength: 25
                    //alpha_num_space_allow:true
                }
            },

            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").size() > 0) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.attr("data-error-container")) {
                    error.appendTo(element.attr("data-error-container"));
                }  else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit
                success1.hide();
                error1.show();
                App.scrollTo(error1, -200);
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


    var handleValidation6 = function() {
        // for more info visit the official plugin documentation:
        // http://docs.jquery.com/Plugins/Validation

        var form1 = $('#vendor_approval');
        var error1 = $('.alert-danger', form1);
        var success1 = $('.alert-success', form1);

        $.validator.addMethod("alpha", function(value, element) {
            return this.optional(element) || /^[A-z]+$/.test(value);
        });
        form1.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",  // validate all fields including form hidden input
            messages: {
                seller_name_abbreviation: {
                    required: "Seller name abbreviation is required.",
                    alpha: "Seller name abbreviation must contain only letters."
                }
            },
            rules: {
                seller_name_abbreviation: {
                    required: true,
                    maxlength: 3,
                    minlength: 3,
                    alpha: true
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
                App.scrollTo(error1, -200);
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

    var handleValidation7 = function() {
        // for more info visit the official plugin documentation:
        // http://docs.jquery.com/Plugins/Validation

        var form1 = $('#company_details');
        var error1 = $('.alert-danger', form1);
        var success1 = $('.alert-success', form1);

        $.validator.addMethod("alpha", function(value, element) {
            return this.optional(element) || /^[A-z]+$/.test(value);
        });
        $.validator.addMethod("vat", function(value, element) {
            return this.optional(element) || value == value.match(/^[0-9]{11}[Vv]{1}$/);
        });
        $.validator.addMethod("cst", function(value, element) {
            return this.optional(element) || value == value.match(/^[0-9]{11}[Cc]{1}$/);
        });

        form1.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",  // validate all fields including form hidden input
            messages: {
                company_identification_number: {
                    alphanumeric: "Company registration only contains alpha ,number."
                },
                company: {
                    required: "Company name is required.",
                    alphaSpace: "Company name only contain alphabets and space."
                },
                gstin: {
                    required: "GSTIN is required.",
                },
            },
            rules: {
                company_identification_number: {
                    alphanumeric: true,
                    minlength: 5,
                    maxlength: 50
                },
                company: {
                    required: true,
                    alphaSpace: true
                },
                gstin: {
                    required : true,
                    alphaSpaceNumber: true,
                    maxlength: 15,
                    minlength: 15
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
                App.scrollTo(error1, -200);
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


    var handleValidation8 = function() {
        // for more info visit the official plugin documentation:
        // http://docs.jquery.com/Plugins/Validation

        var form1 = $('#edit_licence');
        var error1 = $('.alert-danger', form1);
        var success1 = $('.alert-success', form1);

        $.validator.addMethod("alpha", function(value, element) {
            return this.optional(element) || /^[A-z]+$/.test(value);
        });
        $.validator.addMethod("vat", function(value, element) {
            return this.optional(element) || value == value.match(/^[0-9]{11}[Vv]{1}$/);
        });
        $.validator.addMethod("cst", function(value, element) {
            return this.optional(element) || value == value.match(/^[0-9]{11}[Cc]{1}$/);
        });
        form1.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",  // validate all fields including form hidden input
            messages: {

            },
            rules: {
                seeds_lic_number: {
                    required: function(element){
                        return $("#seeds_image").val()!="" || $("#seeds_exp_date").val()!="";
                    }
                },
                seeds_exp_date: {
                    required: function(element){
                        return $("#seeds_image").val()!="" || $("#seeds_lic_number").val()!="";
                    }
                },
                seeds_licence: {
                    required: function(element){
                        return $("#seeds_lic_number").val()!="" || $("#seeds_exp_date").val()!="";
                    }
                },
                fertilizers_lic_number: {
                    required: function(element){
                        return $("#fertilizers_licence").attr('value')!="" || $("#fertilizers_exp_date").val()!="";
                    }
                },
                fertilizers_exp_date: {
                    required: function(element){
                        return $("#fertilizers_licence").attr('value')!="" || $("#fertilizers_lic_number").val()!="";
                    }
                },
                fertilizers_licence: {
                    required: function(element){
                        return $("#fertilizers_lic_number").val()!="" || $("#fertilizers_exp_date").val()!="";
                    }
                },
                pesticides_lic_number: {
                    required: function(element){
                        return $("#pesticides_licence").attr('value')!="" || $("#pesticides_exp_date").val()!="";
                    }
                },
                pesticides_exp_date: {
                    required: function(element){
                        return $("#pesticides_licence").attr('value')!="" || $("#pesticides_lic_number").val()!="";
                    }
                },
                pesticides_licence: {
                    required: function(element){
                        return $("#pesticides_lic_number").val()!="" || $("#pesticides_exp_date").val()!="";
                    }
                },
                others_lic_number: {
                    required: function(element){
                        return $("#others_licence").attr('value')!="" || $("#others_exp_date").val()!="";
                    }
                },
                others_exp_date: {
                    required: function(element){
                        return $("#others_licence").attr('value')!="" || $("#others_lic_number").val()!="";
                    }
                },
                others_licence: {
                    required: function(element){
                        return $("#others_lic_number").val()!="" || $("#others_exp_date").val()!="";
                    }
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
                App.scrollTo(error1, -200);
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


    var handleValidation9 = function() {
        // for more info visit the official plugin documentation:
        // http://docs.jquery.com/Plugins/Validation

        var form1 = $('#add_license');
        var error1 = $('.alert-danger', form1);
        var success1 = $('.alert-success', form1);
        $.validator.addMethod("alphanumeric", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9]+$/.test(value);
        });
        $.validator.addMethod("alphanumsymbols", function(value, element) {
            return this.optional(element) || /^[ A-Za-z0-9-,.]*$/i.test(value);
        });
        $.validator.addMethod("alpha_num_space_sym", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9]+([A-Za-z0-9/-])*$/.test(value);
        }, "only alpha num & / and - are allowed");
        $.validator.addMethod("alpha_num_space_allow", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9]+([ A-Za-z0-9@./#',&-])*$/.test(value);
        }, "only alpha num & space are allowed and starting with space and special char not allowed");
        form1.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",  // validate all fields including form hidden input
            messages: {
                license_number: {
                    required: "License  number is required."
                },
                license_image: {
                    required: "License image is required.",
                    accept: "Please upload .jpeg, .jpg or .png image."
                }


            },
            rules: {

                license_number: {
                    required: true
                },
                license_image: {
                    required: true,
                    accept:"jpeg|jpg|png"
                }
            },

            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").size() > 0) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.attr("data-error-container")) {
                    error.appendTo(element.attr("data-error-container"));
                }  else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit
                success1.hide();
                error1.show();
                App.scrollTo(error1, -200);
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

            handleValidation1();
            handleValidation2();
            handleValidation3();
            handleValidation4();
            handleValidation5();
            handleValidation6();
            handleValidation7();
            handleValidation8();
            handleValidation9();
            $('.date-picker').datepicker({
                rtl: App.isRTL(),
                autoclose: true
            });
        }
    };

}();

jQuery(document).ready(function() {
    FormValidation.init();
});
