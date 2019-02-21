/**
 * Created by Ameya Joshi on 7/4/18.
 */

$(document).ready(function(){
    $("#send-otp").on('click', function(){
        var mobile = $('#mobile').val();
        var mobilePattern = /^[0-9]{10}$/;;
        if(mobilePattern.test(mobile)){
            $.ajax({
                url: "/operational/administration/krishimitra/check-mobile",
                data: {'mobile':mobile,"_token" : $("input[name='_token']").val()},
                async:false,
                type: 'POST',
                error: function(data,xhr,err) {
                    var obj = JSON.parse(data.responseText);
                    $("#otp").html(obj.message);
                    $("#otp_message").show();
                    if(data.status == 403){
                        if(obj.data.waitingTime != 0){
                            $('#send-otp').prop("disabled", true);
                            $('#progressTimer').show();
                            $('#timer_output').show();
                            $("#progressTimer").progressTimer({
                                timeLimit: obj.data.waitingTime,
                                warningThreshold: 10,
                                baseStyle: 'progress-bar-success',
                                warningStyle: 'progress-bar-danger',
                                completeStyle: 'progress-bar-info',
                                onFinish: function() {
                                    $('#send-otp').prop("disabled", false);
                                    $('#mobile').prop("disabled", false);
                                    $('#progressTimer').hide();
                                    $('#timer_output').hide();
                                    $('#otp').hide();
                                }
                            });
                            var sec = obj.data.waitingTime
                            var timer = setInterval(function() {
                                $('#timer_output').html(sec--);
                                if (sec == -1) {
                                    clearInterval(timer);
                                }
                            }, 1000);
                        }
                    }
                },
                success: function(data, textStatus, xhr) {
                    $("#verificationCode").prop('readonly', false);
                    if(xhr.status==200 || xhr.status == 203){
                        $("#otp_message").show();
                        $("#otp").show();
                        $("#otp").html(data.message);
                        $('#otp-div').show();
                        $('#verificationCode').attr('readonly', false);
                        $('#verificationCode').val('');
                        $('#mobile').attr("readonly", true);
                    }else if(xhr.status==202){ //Already Verified
                        $('#progressTimer').hide();
                        $('#timer_output').hide();
                        $("#otp_message").show();
                        $("#otp").html(data.message);
                        $('#verificationCode').attr('readonly', true);
                        $('#otp-div').hide();
                        $('#verificationCode').val(data.data.otp);
                    }else if(xhr.status == 201){
                        $('#progressTimer').hide();
                        $('#timer_output').hide();
                        $("#otp_message").show();
                        $("#otp").html('Account already exists !');
                        $("#password").closest('.form-group').prop('hidden', true);
                        $("#password").rules('remove','required');
                        $("#confirmPassword").closest('.form-group').prop('hidden', true);
                        $("#confirmPassword").rules('remove','required');
                    }
                }
            });
        }
    });

    $("#mobile").keyup(function(){
        $("#otp_message").hide();
    });

    $("select[name='is_krishimitra']").on('change', function(){
        var value = $(this).val();
        if((typeof value != 'undefined') && (value == 'false' || value == false)){
            $("#document_tab input").each(function(){
               $(this).rules('remove','required');
            });
        }else{
            $("#document_tab input").each(function(){
                $(this).rules('add', {
                    required: true
                });
            });
        }
    });
});