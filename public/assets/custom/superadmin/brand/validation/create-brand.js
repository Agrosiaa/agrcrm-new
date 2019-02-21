var FormValidation = function () {

    // basic validation
    var handleValidation1 = function() {
        // for more info visit the official plugin documentation:
        // http://docs.jquery.com/Plugins/Validation
        var form = $('#create_brand');
        var brandId = $("#brand_id").val();
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
                    required: "brand name is required.",
                    remote: "Brand name already exists"
                },
                edit_name: {
                    required: "brand name is required.",
                    remote: "Brand name already exists"
                },
                category_id: {
                    required: "please select category."
                }
            },
            rules: {
                name: {
                    required: true,
                    regex:  /^[a-zA-Z]+[ A-Za-z0-9.-]*$/,
                    remote: {
                        url: "/operational/brand/validate-name",
                        type: "POST"
                    }
                },
                edit_name: {
                    required: true,
                    remote: {
                        url: "/operational/brand/validate-name/"+brandId,
                        type: "POST"
                    }
                },
                category_id: {
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
            }
        });
    }
    var handleValidation2 = function() {
        // for more info visit the official plugin documentation:
        // http://docs.jquery.com/Plugins/Validation
        var form = $('#edit_brand');
        var brandId = $("#brand_id").val();
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
                    required: "brand name is required.",
                    remote: "Brand name already exists"
                },
                edit_name: {
                    required: "brand name is required.",
                    remote: "Brand name already exists"
                }
            },
            rules: {
                name: {
                    required: true,
                    remote: {
                        url: "/operational/brand/validate-name",
                        type: "POST"
                    }
                },
                edit_name: {
                    required: true,
                    regex:  /^[a-zA-Z]+[ A-Za-z0-9.-]*$/,
                    remote: {
                        url: "/operational/brand/validate-name/"+brandId,
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
                form.submit();
            }
        });
    }
    return {
        //main function to initiate the module
        init: function () {
            handleValidation1();
            handleValidation2();
        }
    };
}();

jQuery(document).ready(function() {
    FormValidation.init();

});