var language = $('#language').val();
$(document).ready(function () {

    $.getScript("/assets/frontend/custom/rules/index.js");
    $('#check_login').validate({
        rules: {
            mobile: {
                required: true,
                mobile:true
            },
            password: {
                required: true,
                minlength:6,
                maxlength:20
            }
        },
        messages: {
            mobile:{
                required: (language == 'mr') ? " हे फील्ड आवश्यक आहे." : "This field is required."
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
                url: "/user/auth",
                async:true,
                data: {'mobile':$('#mobile').val(),'password':$('#password').val()},
                error: function(data, textStatus, xhr) {
                    if(data.status==401){
                        $("#response_message").html(data.responseJSON.message);
                    }else{
                        location.reload();
                    }
                },
                success: function(data, textStatus, xhr) {
                    if(xhr.status==200){
                        window.location.replace("/");
                    }else{
                        $("#response_message").html(data.message);
                    }
                },
                type: 'POST'
            });
            return false;
        }
    });
    $('#check_mobile_form').validate({
        rules: {
            mobile: {
                required: true,
                mobile:true
            }
        },
        messages: {
            mobile:{
                required: (language == 'mr') ? " हे फील्ड आवश्यक आहे." : "This field is required."
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
            generateOtp();
            return false;
        }
    });
    $('#validate_otp_form').validate({
        rules: {
            mobile: {
                required: true,
                mobile:true
            },
            otp: {
                required: true
            }
        },
        messages: {
            mobile: {
                required: (language == 'mr') ? " हे फील्ड आवश्यक आहे." : "This field is required."
            },
            otp: {
                required: (language == 'mr') ? " हे फील्ड आवश्यक आहे." : "This field is required."
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
                url: "/user/validate/otp",
                async:true,
                data: $("#validate_otp_form").serializeArray(),
                error: function(data, textStatus, xhr) {
                    var obj = JSON.parse(data.responseText);

                    if(data.status==403){
                        $("#response_message").html(obj[0].message).show();
                    }else{
                        location.reload();
                    }
                },
                success: function(data, textStatus, xhr) {
                    if(xhr.status==200){
                        $(".reg-step2").hide();
                        $(".reg-step3").show();
                        $("#step_3_mobile").val($('#otp_mobile').val());
                    }
                },
                type: 'POST'
            });
            return false;
        }
    });
    $('#registration_final').validate({
        rules: {
            mobile: {
                required: true,
                mobile:true,
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
            mobile: {
                required: (language == 'mr') ? " हे फील्ड आवश्यक आहे." : "This field is required."
            },
            email:{
                remote:(language == "mr") ? "हा ईमेल पत्ता आधीपासूनच नोंदणीकृत आहे" : "This email address has already been registered"
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
                data: $("#registration_final").serializeArray(),
                error: function(data, textStatus, xhr) {
                    if(data.status==401){
                        $("#response_message").html(data.responseJSON.message);
                    }else if(data.status==422){
                        $("#response_message").html('validation failed');
                    }else{
                        $("#response_message").html('something went wrong');
                    }
                },
                success: function(data, textStatus, xhr) {
                    if(xhr.status==200){
                        window.location.replace("/");
                    }else{
                        $("#response_message").html(data.message);
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
        data: {'mobile':$('#check_mobile').val()},
        error: function(data, textStatus, xhr) {

            if(data.status==401){
                $("#response_message").html(data.responseJSON.message).show().fadeOut(10000);
            }else if(data.status==403){
                $(".edit").css("display","none");
                var obj = JSON.parse(data.responseText);

                $(".reg-step1").hide();
                $(".reg-step2").show();
                $('#otp_mobile').val($('#check_mobile').val());
                $("#resend_otp").hide();
            }else{
                location.reload();
            }
        },
        success: function(data, textStatus, xhr) {
            $("#response_message").html(data.message).show().fadeOut(15000);
            if(xhr.status==201){
                $(".reg-step1").hide();
                $('#mobile').val($('#check_mobile').val());
                $(".login-screen").show();
            }else if(xhr.status==200){
                $(".reg-step1").hide();
                $('#otp_mobile').val($('#check_mobile').val());
                $(".reg-step2").show();
                $("#resend_otp").show();
                $('#otp_timer_output').show();
                $('#otp_timer_span').show();
                $('#otp-ProgressTimer').show();
                $("#otp-ProgressTimer").progressTimer({
                    timeLimit: data.data.waitingTime,
                    warningThreshold: 10,
                    baseStyle: 'progress-bar-success',
                    warningStyle: 'progress-bar-danger',
                    completeStyle: 'progress-bar-info',
                    onFinish: function() {
                        $('#otp-ProgressTimer').hide();
                        $('#otp_timer_output').hide();
                        $(".edit").css("display","block");
                        $('#otp_timer_span').hide();
                        $(".reg-step2").hide();
                        $(".reg-step1").show();
                    }
                });
                var sec = data.data.waitingTime;
                var timer = setInterval(function() {
                    $('#otp_timer_output').html(sec--);
                    if (sec == -1) {
                        clearInterval(timer);
                    }
                }, 1000);
            }else if(xhr.status==202){
                $(".reg-step1").hide();
                $('#step_3_mobile').val($('#check_mobile').val());
                $(".reg-step3").show();
            }else if(xhr.status == 203){
                $(".reg-step1").hide();
                $('#otp_mobile').val($('#check_mobile').val());
                $(".reg-step2").show();
                $('#otp_timer_output').show();
            }
        },
        type: 'POST'
    });
}
