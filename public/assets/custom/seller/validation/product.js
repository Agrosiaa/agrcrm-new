
$.validator.addMethod("regex", function(value, element, regexpr) {
    return regexpr.test(value);
});
$.validator.addMethod("alpha_num_space", function(value, element) {
    return this.optional(element) || /^[a-zA-Z0-9]+([a-zA-Z0-9-_\s%:.()/\\+])*$/.test(value);
}, "only alpha num & space are allowed and starting with space not allowed");
$.validator.addMethod("alpha_num_space_sym", function(value, element) {
    return this.optional(element) || /^[a-zA-Z0-9]+([ A-Za-z0-9-_@./#',&%:()/\\+])*$/.test(value);
}, "only alpha num & space are allowed and starting with space and special char not allowed");
$.validator.addMethod("alpha_num", function(value, element) {
    return this.optional(element) || /^[a-zA-Z0-9]+$/.test(value);
}, "only alpha num characters are allowed");
$.validator.addMethod("float2left", function(value, element) {
    return this.optional(element) || /^[0-9]?[0-9]?[0-9]?[0-9]?(\.[0-9][0-9]?)?$/.test(value);
}, "The field should be max 4 digit long with a precision of 2.");
$.validator.addMethod("float7left", function(value, element) {
    return this.optional(element) || /^[0-9]?[0-9]?[0-9]?[0-9]?[0-9]?[0-9]?[0-9]?(\.[0-9][0-9]?)?$/.test(value);
}, "The field should be max 7 digit long with a precision of 2.");
$.validator.addMethod("float9left", function(value, element) {
    return this.optional(element) || /^[0-9]?[0-9]?[0-9]?[0-9]?[0-9]?[0-9]?[0-9]?[0-9]?[0-9]?(\.[0-9][0-9]?)?$/.test(value);
}, "The field should be max 9 digit long with a precision of 2.");
$.validator.addMethod("alpha_specialchars", function(value, element) {
    return this.optional(element) || /^[a-zA-Z]+[ A-Za-z0-9-_.,&()+%/]*$/.test(value);
}, "The field may only contain alphabets and special characters like , & . - _");
$.validator.addMethod("float5left", function(value, element) {
    return this.optional(element) || /^[0-9]?[0-9]?[0-9]?[0-9]?[0-9]?(\.[0-9][0-9]?)?$/.test(value);
}, "The field should be max 7 digit long with a precision of 2.");
$.validator.addMethod("zip", function(value, element) {
    return this.optional(element) || /^[0-9]{6}(\-[0-9]{4})?$/.test(value);
}, "The field must be 6 digit.");
$.validator.addMethod('lessThan', function(value, element, param) {
    return this.optional(element) || parseInt(value) <= parseInt($(param).val());
}, "This value must be less than or equal to Quantity value.");
$.validator.addMethod('greaterThan', function(value, element, param) {
    return this.optional(element) || parseInt(value) >= parseInt($(param).val());
}, "This value must be greater than or equal to Minimum Quantity in shopping Cart.");
$.validator.addMethod("alpha_specialchars_feature", function(value, element) {
    return this.optional(element) || /[a-zA-Z0-9]+([ A-Za-z0-9-,.&%:()/\\+]+$)+/.test(value);
}, "The field may only contain alphabets, numbers and special characters like , .");

var FormValidation = function () {
    // basic validation
    var handleValidation = function() {
        // for more info visit the official plugin documentation:
        // http://docs.jquery.com/Plugins/Validation
        var form = $('#product');
        var error = $('.alert-danger', form);
        var success = $('.alert-success', form);
        var userRole = $('role_type');
        form.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",  // validate all fields including form hidden input
            messages: {
                tax_id:{
                    required: "Please select tax"
                },
                hsn_code: {
                    required: "Please select hsn codes"
                },
                discount_percent: {
                    min: 'Discount must be greater than or equal to 0',
                    max: 'Discount must not be greater than 100'
                }

            },
            rules: {
                product_name: {
                    required: true,
                    maxlength:90
                    //alpha_num_space: true

                },
                product_description: {
                    required: true,
                    maxlength:255
                    //alpha_num_space_sym: true
                },
                seller_sku: {
                    required: true,
                    maxlength:255
                    //alpha_num: true
                },
                model_name: {
                    required: true,
                    maxlength:255
                },
                key_specs_1: {
                    required: true,
                    maxlength:255
                    //alpha_num_space_sym: true
                },
                key_specs_2: {
                    required: true,
                    maxlength:255
                    //alpha_num_space_sym: true
                },
                key_specs_3: {
                    maxlength:255
                    //alpha_num_space_sym: true
                },
                weight: {
                    required: true,
                    float7left: true
                },
                weight_measuring_unit: {
                    required: true
                },
                height: {
                    required: true,
                    float7left: true
                },
                width: {
                    required: true,
                    float7left: true
                },
                length: {
                    required: true,
                    float7left: true
                },
                packaging_dimensions_measuring_unit: {
                    required: true
                },
                final_weight_of_packed_material: {
                    required: true,
                    float7left: true
                },
                final_weight_measuring_unit: {
                    required: true
                },
                product_pick_up_address: {
                    required: true,
                    maxlength:255
                    //alpha_num_space: true
                },
                search_keywords: {
                    required: true,
                    maxlength:10000
                    //alpha_specialchars: true
                },
                selling_price_without_discount:{
                    required:true
                },
                base_price: {
                    float9left: true
                },
                discount_percent: {
                    required: true,
                    digits :true,
                    min: 0,
                    max: 100
                },
                subtotal_final:{
                    required: true
                },
                base_price_final:{
                    required: true
                },
                discounted_price:{
                    required:true
                },
                tax_id: {
                    required: true
                },
                hsn_code: {
                    required: true
                },
                quantity: {
                    required: true,
                    number:true
                },
                minimum_quantity: {
                    required: true,
                    number:true,
                    lessThan:"#quantity",
                    min : 1
                },
                maximum_quantity: {
                    required: true,
                    number:true,
                    lessThan:"#quantity",
                    greaterThan:"#minimum_quantity",
                    min : 1
                },
                product_pick_up_pin_code:{
                    required: true,
                    zip: true
                },
                brand_id: {
                    required: true,
                    //alpha_num_space: true,
                    maxlength: 255
                },
                domestic_warranty: {
                    //alpha_num_space: true,
                    maxlength: 255
                },
                warranty_summary: {
                    //alpha_num_space_sym: true,
                    maxlength: 255
                },
                warranty_service_type: {
                    //alpha_num_space_sym: true,
                    maxlength: 255
                },
                warranty_items_covered: {
                    //alpha_num_space_sym: true,
                    maxlength: 255
                },
                warranty_items_not_covered: {
                    //alpha_num_space_sym: true,
                    maxlength: 255
                },
                sales_package_or_accessories: {
                  //  alpha_num_space_sym: true,
                    maxlength: 255
                },
                other_features_and_applications:{
                    //alpha_num_space_sym: true,
                    required:true
                },
                configurable_width:{
                    min:1
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit
                success.hide();
                error.show();
                App.scrollTo(error, -200);
                if(userRole == 'admin'){
                    $("#aprrove-submit").attr('disabled','disabled');
                }
            },
            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group

                if($('#tab_general').find('div.has-error').length != 0){
                    $('#tab_general_a').css('color', 'red');
                }
                if($('#tab_meta').find('div.has-error').length != 0){
                    $('#tab_meta_a').css('color', 'red');
                }
                if($('#tab_price').find('div.has-error').length != 0){
                    $('#tab_price_a').css('color', 'red');
                }
                if($('#tab_inventory').find('div.has-error').length != 0){
                    $('#tab_inventory_a').css('color', 'red');
                }
                if($('#tab_logistics').find('div.has-error').length != 0){
                    $('#tab_logistics_a').css('color', 'red');
                }
                if($('#tab_features').find('div.has-error').length != 0){
                    $('#tab_features_a').css('color', 'red');
                }

            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element)
                    .closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function (label) {
                label
                .closest('.form-group').addClass('has-success');
                if($('#tab_general').find('div.has-error').length == 0){
                    $('#tab_general_a').css('color', '#4d6b8a');
                }
                if($('#tab_meta').find('div.has-error').length == 0){
                    $('#tab_meta_a').css('color', '#4d6b8a');
                }
                if($('#tab_price').find('div.has-error').length == 0){
                    $('#tab_price_a').css('color', '#4d6b8a');
                }
                if($('#tab_inventory').find('div.has-error').length == 0){
                    $('#tab_inventory_a').css('color', '#4d6b8a');
                }
                if($('#tab_logistics').find('div.has-error').length == 0){
                    $('#tab_logistics_a').css('color', '#4d6b8a');
                }
                if($('#tab_features').find('div.has-error').length == 0){
                    $('#tab_features_a').css('color', '#4d6b8a');
                }
                //.closest('.form-group').removeClass('has-error'); // set success class to the control group
            },
            submitHandler: function (form) {
                success.show();
                error.hide();
                //form.submit();

                if ($(".product-image-name")[0] && $(".product-image-type")[0]){
                    // Do something if class exists
                    var productImageTypeidArray = [];
                    $('.product-image-type').each(function () {
                        productImageTypeidArray.push(this.id);
                    });
                    var productImageTypeValueArray = [];
                    $.each(productImageTypeidArray, function(index, value) {
                        productImageTypeValueArray.push($("#"+value+":checked").val());
                    });
                    /* Check Duplicates */
                    var uniqueNames = [];
                    var flag = true;
                    var sorted_arr = productImageTypeValueArray.sort();
                    var results = [];
                    var arrayLength = productImageTypeValueArray.length;
                    for (var i = 0; i < arrayLength - 1; i++) {
                        if (sorted_arr[i + 1] == sorted_arr[i]) {
                            flag = false;
                        }
                    }
                    if(flag){
                        if(arrayLength==1){
                            if(jQuery.inArray("1", sorted_arr) !== -1){
                                $('#custom-error').hide();
                                $("#submit").attr("disabled", true);
                                form.submit();
                                return true;
                            }
                            else{
                                $('#custom-error').show();
                                $('#custom-message').html('Please select Main Image');
                                $("#submit").attr("disabled", false);
                                return false;
                            }
                        } else if(arrayLength==2){
                            if(jQuery.inArray("1", sorted_arr) !== -1 && jQuery.inArray("2", sorted_arr) !== -1){
                                $('#custom-error').hide();
                                $("#submit").attr("disabled", true);
                                form.submit();
                                return true;
                            }
                            else{
                                $('#custom-error').show();
                                $('#custom-message').html('Please select Main Image and Extra Image 1');
                                $("#submit").attr("disabled", false);
                                return false;
                            }
                        } else if(arrayLength==3){
                            if(jQuery.inArray("1", sorted_arr) !== -1 && jQuery.inArray("2", sorted_arr) !== -1 && jQuery.inArray("3", sorted_arr) !== -1){
                                $('#custom-error').hide();
                                $("#submit").attr("disabled", true);
                                form.submit();
                                return true;
                            }
                            else{
                                $('#custom-error').show();
                                $('#custom-message').html('Please select Main Image, Extra Image 1 and Extra Image 2');
                                $("#submit").attr("disabled", false);
                                return false;
                            }
                        } else if(arrayLength==4){
                            if(jQuery.inArray("1", sorted_arr) !== -1 && jQuery.inArray("2", sorted_arr) !== -1 && jQuery.inArray("3", sorted_arr) !== -1 && jQuery.inArray("4", sorted_arr) !== -1){
                                $('#custom-error').hide();
                                $("#submit").attr("disabled", true);
                                form.submit();
                                return true;
                            }
                            else{
                                $('#custom-error').show();
                                $('#custom-message').html('Please select Main Image, Extra Image 1, Extra Image 2 and Extra Image 3');
                                $("#submit").attr("disabled", false);
                                return false;
                            }
                        }
                        return false; //true
                    }else{
                        $('#custom-error').show();
                        $('#custom-message').html('Same option not allowed for multiple images');
                        $("#submit").attr("disabled", false);
                        return false;
                    }
                } else {
                    $('#custom-error').show();
                    $('#custom-message').html('Please upload atleast one image');
                    $("#submit").attr("disabled", false);
                    return false;   //Please upload atleast image
                }
            }
        });
    }
    return {
        //main function to initiate the module
        init: function () {
            handleValidation();
            $('#features_input :input.rrequired').each(function () {
                $(this).rules('add', {
                    required: true,
                    maxlength: 255
                });
            });
            $('#features_input :input.nrequired').each(function () {
                $(this).rules('add', {
                    maxlength: 255
                });
            });


        }
    };
}();

$("#aprrove-submit").on('click',function(){
    $('<input>').attr({
        'type':'hidden',
        'name':'approve',
        'value':'true'}).appendTo('#product');
    $("#submit").removeAttr('type');
    $("#approve-submit").prop('type','submit');
    FormValidation.init();
});

$("#submit").on('click',function(){

    $("#product input[name='approve']").remove();
    $("#approve-submit").removeAttr('type');
    $("#submit").prop('type','submit');
    FormValidation.init();
});

jQuery(document).ready(function() {
    FormValidation.init();
});
