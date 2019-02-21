var FormValidation = function () {

    // basic validation
    var handleValidation1 = function() {
        // for more info visit the official plugin documentation:
        // http://docs.jquery.com/Plugins/Validation
        var form = $('#create_root_category');
        var error = $('.alert-danger', form);
        var success = $('.alert-success', form);
        $.validator.addMethod(
            "regex",
            function(value, element, regexp) {
                var check = false;
                var re = new RegExp(regexp);
                return this.optional(element) || re.test(value);
            },
            "Only alphabets & numbers & space are allowed! don't use space at start & end of the name"
        );
        form.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",  // validate all fields including form hidden input
            messages: {
                name: {
                    required: "category name is required.",
                    regex:"Only alphabets & numbers & space are allowed! don't use space at start & end of the name",
                    maxlength: "category name can't be more than 90 character.",
                    remote :"category name already exits"
                },
                return_period: {
                    required: "return period is required.",
                    number: "return period must be days in number."
                },
                image: {
                    required: "category image required",
                    accept:"please enter image only in jpeg|jpg|png format."
                },
                tab_name: {
                    required: "excel sheet name required",
                    maxlength: "excel sheet name should not greater than 31 character.",
                    remote :"excel tab already exits"

                }
            },
            rules: {
                name: {
                    required: true,
                    regex:  /^([a-zA-Z0-9-]+\s)*[a-zA-Z0-9-\(\)\/]+$/,
                    maxlength: 90,
                    remote: {
                        url: "/operational/category/check-category",
                        type: "POST"
                    }
                },
                return_period: {
                    required: true,
                    number: true
                },
                image: {
                    required: true,
                    accept:"jpeg|jpg|png"
                },
                tab_name: {
                    required: true,
                    maxlength: 31,
                    regex:  /^([a-zA-Z0-9-]+\s)*[a-zA-Z0-9-]+$/,
                    remote: {
                        url: "/operational/category/check-excel-tab",
                        type: "POST"
                    }
                }


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
                $("#submit").attr("disabled", true);
                form.submit();
            }
        });
    }
    var handleValidation2 = function() {
        // for more info visit the official plugin documentation:
        // http://docs.jquery.com/Plugins/Validation
        var form = $('#create-sub-category');
        var error = $('.alert-danger', form);
        var success = $('.alert-success', form);
        $.validator.addMethod(
            "regex",
            function(value, element, regexp) {
                var check = false;
                var re = new RegExp(regexp);
                return this.optional(element) || re.test(value);
            },
            "No special Characters allowed here. & don't use space at start & end of the name"
        );
        $(document).ready(function(){
            form.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input

                messages: {
                    name: {
                        required: "category name is required.",
                        regex:"Only alphabets & numbers & space are allowed! don't use space at start & end of the name",
                        maxlength: "category name can't be more than 90 character.",
                        remote :"category name already exits"
                    },
                    image: {
                        required: "category image required",
                        accept:"please enter image only in jpeg|jpg|png format."
                    },
                    is_item_head: {
                        required: "please select item head "
                    },
                    commission: {
                      required: "commission is required"
                    },
                    logistic_percentage: {
                      required:"logistic is required"
                    }

                },
                rules: {
                    name: {
                        required: true,
                        regex:  /^([a-zA-Z0-9-]+\s)*[a-zA-Z0-9-\(\)\/]+$/,
                        maxlength:90,
                        remote: {
                            url: "/operational/category/check-category",
                            type: "POST"
                        }
                    },
                    image: {
                        required: true,
                        accept:"jpeg|jpg|png"
                    },
                    is_item_head:{
                        required: true
                    },
                    commission: {
                        required: true
                    },
                    logistic_percentage: {
                        required: true
                    }
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
                    $("#sub_category").attr("disabled", true);
                    $('button[type=submit], input[type=submit]').attr('disabled',true);
                }
            });

            if($("#is_item_head").val() == 1){
                $("#createHsnId").rules('add',{
                    required: true
                });
            }


        });




    }
    var handleValidation3 = function() {
        // for more info visit the official plugin documentation:
        // http://docs.jquery.com/Plugins/Validation
        var form = $('#edit_category');
        var error = $('.alert-danger', form);
        var success = $('.alert-success', form);
        var categoryId = $("#current-category-id").val();
        $.validator.addMethod(
            "regex",
            function(value, element, regexp) {
                var check = false;
                var re = new RegExp(regexp);
                return this.optional(element) || re.test(value);
            },
            "No special Characters allowed here. & don't use space at start & end of the name"
        );
        $(document).ready(function(){

            if($('#is_item_value').val()== 1){
                form.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input

                    messages: {
                        name: {
                            required: "category name is required.",
                            regex:"Only alphabets & numbers & space are allowed! don't use space at start & end of the name",
                            remote :"category name already exits"
                        },
                        sku:{
                            required: "sku id required",
                            regex:"Only alphabets & numbers & space are allowed! don't use space at start & end of the name",
                        },
                        imgSource: {
                            required: "category image required",
                            accept:"please enter image only in jpeg|jpg|png format."
                        },
                        commission: {
                            required: "please enter commision ",
                            number:"allow only numbers",
                            maxlength: "commission should not greater then 30 character."
                        },
                        item_head_abbreviation:{
                            required: "item head abbreviation is required",
                            maxlength: "item head abbreviation should not greater then 2 character."

                        },
                        logistic_percentage: {
                            required:"logistic is required"
                        }


                    },
                    rules: {
                        name: {
                            required: true,
                            regex:  /^([a-zA-Z0-9-]+\s)*[a-zA-Z0-9-\(\)\/]+$/,
                            remote: {
                                url: "/operational/category/check-category/"+categoryId,
                                type: "POST"
                            }
                         },
                        sku: {
                            required: true,
                            regex:  /^([a-zA-Z0-9]+\s)*[a-zA-Z0-9]+$/,
                        },
                        imgSource: {
                            required: true,
                            accept:"jpeg|jpg|png"
                        },
                        commission: {
                            required: true,
                            number:true,
                            maxlength: 30
                        },
                        hsn_code_id: {
                            required: true
                        },
                        hsn_code: {
                            required: true
                        },
                        is_item_head:{
                            required: true
                        },
                        logistic_percentage: {
                            required: true
                        }
                    },
                    invalidHandler: function (event, validator) { //display error alert on form submit
                        success.hide();
                        error.show();
                        App.scrollTo(error, -200);
                    },
                    highlight: function (element) { // hightlight error inputs
                        $(element)
                            .closest('.form-group').addClass('has-error').removeClass('has-success'); // set error class to the control group
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
            else {
                form.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input

                    messages: {
                        name: {
                            required: "category name is required.",
                            regex:"Only alphabets & numbers & space are allowed! don't use space at start & end of the name",
                            remote :"category name already exits"
                        },
                        imgSource: {
                            required: "category image required",
                            accept:"please enter image only in jpeg|jpg|png format."
                        },
                        return_period: {
                            required: "return period is required.",
                            number: "return period must be days in number."
                        }
                    },
                    rules: {
                        name: {
                            required: true,
                            regex:  /^([a-zA-Z0-9-]+\s)*[a-zA-Z0-9-\(\)\/]+$/,
                            remote: {
                                url: "/operational/category/check-category/"+categoryId,
                                type: "POST"
                            }
                        },
                        imgSource: {
                            required: true,
                            accept:"jpeg|jpg|png"
                        },
                        return_period: {
                            required: true,
                            number: true
                        }
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
        });
    }
    return {
        //main function to initiate the module
        init: function () {
            handleValidation1();
            handleValidation2();
            handleValidation3();
            $.validator.addMethod(
                "regex",
                function(value, element, regexp) {
                    var check = false;
                    var re = new RegExp(regexp);
                    return this.optional(element) || re.test(value);
                },
                "No special Characters allowed here. & don't use space at start & end of the name"
            );
            $('#is_item_head').on('change', function() {
                if($('#is_item_head').val()== 1){
                    $("#tab1").show();
                    $("#tab2").show();
                    $("#tab3").show();
                    $("#tab4").show();
                    $("#tab5").show();
                    $("#item_head_abbreviation").rules("add", {
                        required: true,
                        maxlength: 2,
                        regex: /^[A-Za-z]{0,2}$/,
                        remote: {
                            url: "/operational/category/check-item-abbreviation",
                            type: "POST"
                        }
                    });
                    $("#sku").rules("add", {
                        required: true,
                        regex:  /^([a-zA-Z0-9]+\s)*[a-zA-Z0-9]+$/,
                        remote: {
                            url: "/operational/category/check-sku",
                            type: "POST"
                        }
                    });
                    $("#commission").rules("add", {
                        required: true,
                        number:true,
                        maxlength: 30
                    });
                    $("#logistic_percentage").rules("add", {
                        required: true,
                        maxlength: 30
                    });
                    $("#createHsnId").rules("add", {
                        required: true
                    });
                    $("#tab").rules("add", {
                        required: true,
                        maxlength: 31,
                        regex:  /^([a-zA-Z0-9-]+\s)*[a-zA-Z0-9-]+$/,
                        remote: {
                            url: "/operational/category/check-excel-tab",
                            type: "POST",
                            data: {
                                tab_name: function() {
                                    return $("#tab").val();
                                }
                            }
                        }
                    });
                }
                else {
                    $("#item_head_abbreviation").rules("remove");
                    $("#sku").rules("remove");
                    $("#commission").rules("remove");
                    $("#logistic_percentage").rules("remove");
                    $("#tab").rules("remove");
                    $("#tab1").hide();
                    $("#tab1").css("display","none");
                    $("#tab2").hide();
                    $("#tab2").css("display","none");
                    $("#tab3").hide();
                    $("#tab3").css("display","none");
                    $("#tab4").hide();
                    $("#tab4").css("display","none");
                    $("#tab5").hide();
                    $("#tab5").css("display","none");
                    $("#createHsnId").rules('remove');
                }
            });
        }
    };
}();

jQuery(document).ready(function() {
    FormValidation.init();

});
