/**
 * Created by Ameya Joshi on 7/4/18.
 */


var CreateKrishimitraFormValidation = function () {
    // basic validation
    var handleValidation = function() {
        // for more info visit the official plugin documentation:
        // http://docs.jquery.com/Plugins/Validation
        var form = $('#krishimitraCreate');
        var error = $('.alert-danger', form);
        var success = $('.alert-success', form);
        $.validator.addMethod('uniqueotp',
            function(value) {
                var mobile_number =  $( "#mobile" ).val();
                var otp = $( "#verificationCode" ).val();
                var result_flag = 'false';
                $.ajax({
                    type: "POST",
                    async:false,
                    url: "/operational/administration/krishimitra/validate-otp",
                    data:{ mobile: mobile_number, otp : otp},
                    dataType:"text",
                    error: function(data,xhr,err) {
                    },
                    success: function(data, textStatus, xhr) {
                        if(xhr.status==200){
                            $('#verificationCode').attr('readonly', true);
                            result_flag = 'true';
                            $('#vcode').show();
                        }else{

                        }
                    }
                });
                if(result_flag == 'true') return true; else return false;
            } , 'This OTP is either invalid or expired');
        $.validator.addMethod("pan", function(value, element) {
            return this.optional(element) || value == value.match(/^[a-zA-Z]{5}[0-9]{4}[A-Za-z]{1}$/);
        },"Please Enter valid PAN Number");
        form.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",  // validate all fields including form hidden input
            messages: {
                email:{
                    remote: 'This email is already used.'
                }
            },
            rules: {
                otp_code:{
                    required: true,
                    uniqueotp: true
                },
                first_name:{
                    required: true
                },
                last_name:{
                    required: true
                },
                email: {
                    required: true,
                    email: true,
                    remote: {
                        type: "POST",
                        url: "/operational/administration/krishimitra/check-email"
                    }
                },
                is_krishimitra:{
                    required: true,
                },
                mobile:{
                    required: true,
                    minlength: 10,
                    maxlength: 10
                },
                aadhar_card_number:{
                    required: true,
                    minlength:12,
                    maxlength:12,
                    number: true
                },
                pan_card_number:{
                    required: true,
                    pan: true,
                },
                name_of_premise_building_village:{
                    required: true
                },
                area_locality_wadi:{
                    required: true
                },
                road_street_lane:{
                    required: true
                },
                pincode:{
                    required: true
                },
                at_post:{
                    required: true
                },
                state:{
                    required: true
                },
                district:{
                    required: true
                },
                taluka:{
                    required: true
                },
                aadhar_card:{
                    required: true,
                    extension: "png|jpeg|jpg|pdf"
                },
                pan_card:{
                    required: true,
                    extension: "png|jpeg|jpg|pdf"
                },
                cancelled_cheque:{
                    required: true,
                    extension: "png|jpeg|jpg|pdf"
                },
                password: {
                    required: true,
                    minlength: 5,
                    maxlength: 20
                },
                confirm_password: {
                    required: true,
                    equalTo: "#password",
                    minlength: 5

                },
            },
            invalidHandler: function (event, validator) { //display error alert on form submit
                success.hide();
                error.show();
                App.scrollTo(error, -200);
                if($('#tab_general').find('div.has-error').length != 0){
                    $('#tab_general_a').css('color', 'red');
                }
                if($('#document_tab').find('div.has-error').length != 0){
                    $('#document_tab_a').css('color', 'red');
                }
            },
            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
                if($('#tab_general').find('div.has-error').length != 0){
                    $('#tab_general_a').css('color', 'red');
                }
                if($('#document_tab').find('div.has-error').length != 0){
                    $('#document_tab_a').css('color', 'red');
                }
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element)
                    .closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function (label) {
                label
                    .closest('.form-group').addClass('has-success').removeClass('has-error');

                if($('#tab_general').find('div.has-error').length == 0){
                    $('#tab_general_a').css('color', '#4d6b8a');
                }
                if($('#document_tab').find('div.has-error').length == 0){
                    $('#document_tab_a').css('color', '#4d6b8a');
                }
                //.closest('.form-group').removeClass('has-error'); // set success class to the control group
            },
            submitHandler: function (form) {
                success.show();
                error.hide();
                if($('#tab_general').find('div.has-error').length == 0){
                    $('#tab_general_a').css('color', '#4d6b8a');
                }
                if($('#document_tab').find('div.has-error').length == 0){
                    $('#document_tab_a').css('color', '#4d6b8a');
                }
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

var EditKrishimitraFormValidation = function () {
    // basic validation
    var handleValidation = function() {
        // for more info visit the official plugin documentation:
        // http://docs.jquery.com/Plugins/Validation
        var form = $('#krishimitraEdit');
        var error = $('.alert-danger', form);
        var success = $('.alert-success', form);
        var krishimitraId = $("#krishimitraId").val();
        $.validator.addMethod("pan", function(value, element) {
            return this.optional(element) || value == value.match(/^[a-zA-Z]{5}[0-9]{4}[A-Za-z]{1}$/);
        },"Please Enter valid PAN Number");
        form.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",  // validate all fields including form hidden input
            messages: {
                email:{
                    remote: 'This email is already used.'
                }
            },
            rules: {
                first_name:{
                    required: true
                },
                last_name:{
                    required: true
                },
                email: {
                    required: true,
                    email: true,
                    remote: {
                        type: "POST",
                        url: "/operational/administration/krishimitra/check-email?krishimitra_id="+krishimitraId
                    }
                },
                mobile:{
                    required: true,
                    minlength: 10,
                    maxlength: 10
                },
                aadhar_card_number:{
                    minlength:12,
                    maxlength:12,
                    number: true
                },
                pan_card_number:{
                    pan: true,
                },
                name_of_premise_building_village:{
                    required: true
                },
                area_locality_wadi:{
                    required: true
                },
                road_street_lane:{
                    required: true
                },
                pincode:{
                    required: true
                },
                at_post:{
                    required: true
                },
                state:{
                    required: true
                },
                district:{
                    required: true
                },
                taluka:{
                    required: true
                },
                aadhar_card:{
                    extension: "png|jpeg|jpg|pdf"
                },
                pan_card:{
                    extension: "png|jpeg|jpg|pdf"
                },
                cancelled_cheque:{
                    extension: "png|jpeg|jpg|pdf"
                },
            },
            invalidHandler: function (event, validator) { //display error alert on form submit
                success.hide();
                error.show();
                App.scrollTo(error, -200);
                if($('#tab_general').find('div.has-error').length != 0){
                    $('#tab_general_a').css('color', 'red');
                }
                if($('#document_tab').find('div.has-error').length != 0){
                    $('#document_tab_a').css('color', 'red');
                }
            },
            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
                if($('#tab_general').find('div.has-error').length != 0){
                    $('#tab_general_a').css('color', 'red');
                }
                if($('#document_tab').find('div.has-error').length != 0){
                    $('#document_tab_a').css('color', 'red');
                }
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element)
                    .closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function (label) {
                label
                    .closest('.form-group').addClass('has-success').removeClass('has-error');

                if($('#tab_general').find('div.has-error').length == 0){
                    $('#tab_general_a').css('color', '#4d6b8a');
                }
                if($('#document_tab').find('div.has-error').length == 0){
                    $('#document_tab_a').css('color', '#4d6b8a');
                }
                //.closest('.form-group').removeClass('has-error'); // set success class to the control group
            },
            submitHandler: function (form) {
                success.show();
                error.hide();
                if($('#tab_general').find('div.has-error').length == 0){
                    $('#tab_general_a').css('color', '#4d6b8a');
                }
                if($('#document_tab').find('div.has-error').length == 0){
                    $('#document_tab_a').css('color', '#4d6b8a');
                }
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