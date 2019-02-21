$(document).ready(function () {
    $("#f-pass-mobile").keyup(function(){
            $("#f-pass-step1-message").text('');
    });


    $("#f-pass-step2-mobile").keyup(function(){
        $("#f-pass-step2-mobile-message").text('');
    });

    $("#f-pass-continue").click(function(){
        var language = $('#language').val();
        var mobile = $('#f-pass-mobile').val();
        var mobilePattern = /^[0-9]{10}$/;;
        if(mobilePattern.test(mobile)){
            $.ajax({
                url:'/user/forgot-password/mobile',
                data:{'mobile': mobile,'new': true},
                error: function(data, textStatus, xhr){
                        switch(data.status){
                            case 403 :  $("#f-pass-step1-message").text( (language == 'mr') ? 'हा नंबर वैद्य नाही' : 'This mobile number does not exists' );
                                        break;
                            case 406 :  $("#f-pass-step1-message").text( 'Wait');
                                        break;
                        }
                },
                success: function(data, textStatus, xhr){
                    if(xhr.status == 200){
                        $("#resend_otp_password").show();
                        $("#f-pass-message").text(data.message);
                        $(".reset-pass-form1").css("display","none");
                        $(".reset-pass-form2").css("display","block");
                        $("#f-pass-step2-mobile").val(mobile);
                        $('#forgot_otp_timer_output').show();
                        $('#forgot_otp_timer_span').show();
                        $('#forgot-otp-ProgressTimer').show();
                        $("#forgot-otp-ProgressTimer").progressTimer({
                            timeLimit: data.data.waitingTime,
                            warningThreshold: 10,
                            baseStyle: 'progress-bar-success',
                            warningStyle: 'progress-bar-danger',
                            completeStyle: 'progress-bar-info',
                            onFinish: function() {
                                $('#forgot-otp-ProgressTimer').hide();
                                $('#forgot_otp_timer_output').hide();
                                $(".edit").css("display","block");
                                $('#forgot_otp_timer_span').hide();
                                $(".reset-pass-form2").hide();
                                $(".reset-pass-form1").show();
                            }
                        });
                        var sec = data.data.waitingTime
                        var timer = setInterval(function() {
                            $('#forgot_otp_timer_output').html(sec--);
                            if (sec == -1) {
                                clearInterval(timer);
                            }
                        }, 1000);
                    }else if(xhr.status == 203){

                    }
                },
                type: "POST",
                async: false
            });
        }else{
            $("#f-pass-step1-message").text( (language == 'mr') ?  "आपला मोबाइल नंबर प्रविष्ट करा" : 'Enter valid mobile number.');
        }
    });

    $("#f-pass-verify").click(function(){
        var mobilePattern = /^[0-9]{10}$/;
        var otp = $("#otp").val();
        var mobile = $("#f-pass-step2-mobile").val();
        if(mobilePattern.test(mobile)){
          if(otp != ''){
            $.ajax({
                url: '/user/forgot-password/otp',
                data:{'otp':otp,'mobile':mobile},
                error:function(data, textStatus, xhr){

                },
                success:function(data, textStatus, xhr){
                    if(xhr.status == 200){
                        $(".reset-pass-form2").css("display","none");
                        $(".reset-pass-form3").css("display","block");
                        $("#f-pass-last-mobile").val(mobile);
                    }
                },
                type: "POST",
                async: false
            });
          }else{
            $("#f-pass-otp-message").text((language == 'mr') ? ' कृपया पहिला पूर्ण फॉर्म भरा' : 'Please fill out whole form first.');
          }
        }else{
           $("#f-pass-step2-mobile-message").text( (language == 'mr') ?  'आपला मोबाइल नंबर प्रविष्ट करा' : 'Enter valid mobile number.');
        }
    });

    $("#f-pass-last-mobile").keyup(function(){
        $("#f-pass-last-mobile-message").text('');
    });
    $("#f-pass-password").keyup(function(){
        $("#f-pass-password-message").text("");
    });
    $("#f-pass-save").click(function(){
        var mobile = $("#f-pass-last-mobile").val();
        var password = $("#f-pass-password").val();
        var mobilePattern = /^[0-9]{10}$/;
        var passwordPattern = /^.{6,20}$/;
        if(mobilePattern.test(mobile)){
            if(passwordPattern.test(password)){
                $.ajax({
                    url: '/user/forgot-password/change',
                    data:{'password':password,'mobile':mobile},
                    error:function(data, textStatus, xhr){

                    },
                    success:function(data, textStatus, xhr){
                        if(xhr.status == 200){
                            location.reload();
                        }
                    },
                    type: "POST",
                    async: false
                });
            }else{
                $("#f-pass-password-message").text("Your password should greater than 6 characters and less than 20 characters.");
            }
        }else{
                $("#f-pass-last-mobile-message").text((language == 'mr') ? "आपला मोबाइल नंबर प्रविष्ट करा" : 'Enter valid mobile number.');
        }
    });

    $("#resend_otp_password").click(function(){
        var mobilePattern = /^[0-9]{10}$/;
        var mobile = $("#f-pass-step2-mobile").val();
        if(mobilePattern.test(mobile)){
            var rememberToken = $('meta[name="csrf_token"]').attr('content');
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN' : rememberToken } });
            $.ajax({
                url:'/user/forgot-password/mobile',
                data:{'mobile': mobile},
                error: function(data, textStatus, xhr){
                            switch(data.status){
                                case 403 :  $("#f-pass-step1-message").text( (language == 'mr') ? 'हा नंबर वैद्य नाही ' : 'This mobile number does not exists');
                                            break;
                                case 406 :  $("#resend_otp_password").hide();
                                            break;
                            }

                },
                success: function(data, textStatus, xhr){
                    if(xhr.status == 200){
                        $("#f-pass-message").text((language == 'mr') ?  'प्रविष्ट केलेल्या नंबरवर ओ टी पी पाठविला आहे!' : 'OTP is sent to your mobile again.');
                    }
                },
                type: "POST",
                async: false
            });
        }else{
            $("#f-pass-step2-mobile-message").text((language == 'mr') ? "आपला मोबाइल नंबर प्रविष्ट करा" : 'Enter valid mobile number.');
        }
    });

});
