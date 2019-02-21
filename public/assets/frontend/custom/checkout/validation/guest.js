var language = $('#language').val();
$(document).ready(function () {

    $.getScript("/assets/frontend/custom/rules/index.js");
    $('#form-step-1').validate({
        messages: {
            mobile: {
                required: (language == 'mr') ? " हे फील्ड आवश्यक आहे." : "This field is required.",
            },
        },
        rules: {
            mobile: {
                required: true,
                mobile:true,
            },
        },
        errorElement: "em",
        errorPlacement: function ( error, element ) {

            // Add the `help-block` class to the error element
            error.addClass( "help-block" );
            error.css("color","red");
            if ( element.prop( "type" ) === "checkbox" ) {
                error.insertAfter( element.parent( "label" ) );
            } else {
                error.insertAfter( element );
            }
        },
        highlight: function ( element, errorClass, validClass ) {
            $( element ).parents( ".col-sm-5" ).addClass( "has-error" ).removeClass( "has-success" );
        },
        unhighlight: function (element, errorClass, validClass) {
            $( element ).parents( ".col-sm-5" ).addClass( "has-success" ).removeClass( "has-error" );
        },
        submitHandler: function (form) { // for demo
            generateOtp();
            return false;
        }
    });

    $('#form-step-2-login').validate({
        rules: {
            password: {
                required: true,
                minlength:6,
                maxlength:20
            }
        },
        messages: {
            password: {
                required: (language == 'mr') ? " हे फील्ड आवश्यक आहे." : "This field is required.",
                minlength: jQuery.validator.format((language == 'mr') ? "कृपया किमान {0} वर्ण प्रविष्ट करा." : "Please enter at least {0} characters."),
                maxlength: jQuery.validator.format((language == 'mr') ? "कृपया {0} वर्णांपेक्षा अधिक प्रविष्ट करू नकाे." : "Please enter no more than {0} characters."),
            }
        },
        errorElement: "em",
        errorPlacement: function ( error, element ) {

            // Add the `help-block` class to the error element
            error.addClass( "help-block" );
            error.css("color","red");
            if ( element.prop( "type" ) === "checkbox" ) {
                error.insertAfter( element.parent( "label" ) );
            } else {
                error.insertAfter( element );
            }
        },
        highlight: function ( element, errorClass, validClass ) {
            $( element ).parents( ".col-sm-5" ).addClass( "has-error" ).removeClass( "has-success" );
        },
        unhighlight: function (element, errorClass, validClass) {
            $( element ).parents( ".col-sm-5" ).addClass( "has-success" ).removeClass( "has-error" );
        },
        submitHandler: function (form) { // for demo
            var rememberToken = $('meta[name="csrf_token"]').attr('content');
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN' : rememberToken } });
            $.ajax({
                url: "/user/auth",
                async:true,
                data: {'mobile':$('#mobile').val(),'password':$('#password').val()},
                error: function(data, textStatus, xhr) {
                    if(data.status==401){
                        $("#login-step2-user-true-error").html(data.responseJSON.message);
                    }else{
                        location.reload();
                    }
                },
                success: function(data, textStatus, xhr) {
                    if(xhr.status==200){
                        changeStep('#password-continue');
                        showUserAddress();
                    }else{
                        $("#login-step2-user-true-error").html(data.message);
                    }
                },
                type: 'POST'
            });
            return false;
        }
    });

    $('#form-step-2-registration').validate({
        messages: {
            mobile: {
                required: (language == 'mr') ? " हे फील्ड आवश्यक आहे." : "This field is required."
            },
            otp: {
                required: (language == 'mr') ? " हे फील्ड आवश्यक आहे." : "This field is required."
            }
        },
        rules: {
            mobile: {
                required: true,
                mobile:true
            },
            otp: {
                required: true
            },
        },
        errorElement: "em",
        errorPlacement: function ( error, element ) {

            // Add the `help-block` class to the error element
            error.addClass( "help-block" );
            error.css("color","red");
            if ( element.prop( "type" ) === "checkbox" ) {
                error.insertAfter( element.parent( "label" ) );
            } else {
                error.insertAfter( element );
            }
        },
        highlight: function ( element, errorClass, validClass ) {
            $( element ).parents( ".col-sm-5" ).addClass( "has-error" ).removeClass( "has-success" );
        },
        unhighlight: function (element, errorClass, validClass) {
            $( element ).parents( ".col-sm-5" ).addClass( "has-success" ).removeClass( "has-error" );
        },
        submitHandler: function (form) { // for demo
            var rememberToken = $('meta[name="csrf_token"]').attr('content');
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN' : rememberToken } });
            $.ajax({
                url: "/user/validate/otp",
                async:true,
                data: $("#form-step-2-registration").serializeArray(),
                error: function(data, textStatus, xhr) {
                    var obj = JSON.parse(data.responseText);

                    if(data.status==403){
                        $("#login-step2-user-false-error").html(obj[0].message).show();
                    }else{
                        location.reload();
                    }
                },
                success: function(data, textStatus, xhr) {
                    if(xhr.status==200){
                        $("#login-step2-user-false").hide();
                        $("#registration_mobile").val($('#mobile').val());
                        $("#step-registration").show();
                    }
                },
                type: 'POST'
            });
            return false;
        }
    });
    $('#form-step-registration').validate({
        rules: {
            mobile: {
                required: true,
                mobile:true
            },
            email: {
                chkMail:true,
                remote: {
                    type: "GET",
                    url: "/user/check-mail"
                }
            },
            password: {
                required: true,
                minlength:6,
                maxlength:20
            }
        },
        messages: {
            email:{
                chkMail : (language == "mr") ?  "वैध ई-मेल आयडी नमूद करा." : "Please enter valid email address.",
                remote: (language == "mr") ? "हा ईमेल पत्ता आधीपासूनच नोंदणीकृत आहे" : "This email address has already been registered"
            },
            password: {
                required: (language == 'mr') ? " हे फील्ड आवश्यक आहे." : "This field is required.",
                minlength: jQuery.validator.format((language == 'mr') ? "कृपया किमान {0} वर्ण प्रविष्ट करा." : "Please enter at least {0} characters."),
                maxlength: jQuery.validator.format((language == 'mr') ? "कृपया {0} वर्णांपेक्षा अधिक प्रविष्ट करू नकाे." : "Please enter no more than {0} characters."),
            }

        },
        errorElement: "em",
        errorPlacement: function ( error, element ) {

            // Add the `help-block` class to the error element
            error.addClass( "help-block" );
            error.css("color","red");
            if ( element.prop( "type" ) === "checkbox" ) {
                error.insertAfter( element.parent( "label" ) );
            } else {
                error.insertAfter( element );
            }
        },
        highlight: function ( element, errorClass, validClass ) {
            $( element ).parents( ".col-sm-5" ).addClass( "has-error" ).removeClass( "has-success" );
        },
        unhighlight: function (element, errorClass, validClass) {
            $( element ).parents( ".col-sm-5" ).addClass( "has-success" ).removeClass( "has-error" );
        },
        submitHandler: function (form) { // for demo
            var rememberToken = $('meta[name="csrf_token"]').attr('content');
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN' : rememberToken } });
            $.ajax({
                url: "/user/register",
                async:true,
                data: $("#form-step-registration").serializeArray(),
                error: function(data, textStatus, xhr) {
                    if(data.status==401){
                        $("#form-step-registration-error").html(data.responseJSON.message);
                    }else if(data.status==422){
                        $("#form-step-registration-error").html('validation failed');
                    }else{
                        $("#form-step-registration-error").html('something went wrong');
                    }
                },
                success: function(data, textStatus, xhr) {
                    if(xhr.status==200){
                        changeStep('#password-continue');
                        showUserAddress();
                    }else{
                        $("#form-step-registration-error").html(data.message);
                    }
                },
                type: 'POST'
            });
            return false;
        }
    });

    $("#resend_otp").on("click",function (e) {
        generateOtp();
    });
});

function generateOtp(){
    var rememberToken = $('meta[name="csrf_token"]').attr('content');
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN' : rememberToken } });
    $.ajax({
        url: "/user/validate/mobile",
        async:true,
        data: {'mobile':$('#mobile').val()},
        error: function(data, textStatus, xhr) {
            if(data.status==401){
                $("#form-step-1-error").html(data.responseJSON.message).show().fadeOut(10000);
            }else if(data.status==403){
                var obj = JSON.parse(data.responseText);
                $("#login-step1").hide();
                $("#login-step2-user-false").show();
                $('#otp_mobile').val($('#mobile').val());
                $("#login-step2-user-false-error").html(obj.message).show().fadeOut(20000);
                $('#progressTimer').show();
                $('#timer_output').show();
                $("#progressTimer").progressTimer({
                    timeLimit: obj.data.waitingTime,
                    warningThreshold: 10,
                    baseStyle: 'progress-bar-success',
                    warningStyle: 'progress-bar-danger',
                    completeStyle: 'progress-bar-info',
                    onFinish: function() {
                        $('#progressTimer').hide();
                        $('#timer_output').hide();
                    }
                });
                var sec = obj.data.waitingTime
                var timer = setInterval(function() {
                    $('#timer_output').html(sec--);
                    if (sec == -1) {
                        clearInterval(timer);
                    }
                }, 1000);
            }else{
                location.reload();
            }
        },
        success: function(data, textStatus, xhr) {
            if(xhr.status==201){
                $("#login-step1").hide();
                $('#login_step2_mobile').val($('#mobile').val());
                $("#login-step2-user-true").show();
            }else if(xhr.status==200){
                $("#login-step1").hide();
                $("#login-step2-user-false").show();
                $('#otp_mobile').val($('#mobile').val());
                $("#login-step2-user-false-error").html(data.message).show().fadeOut(15000);
            }else if(xhr.status==202){
                $("#login-step1").hide();
                $("#form-step-registration-error").html(data.message);
                $("#registration_mobile").val($('#mobile').val());
                $("#step-registration").show();
            }
        },
        type: 'POST'
    });
}
