var language = $('#language').val();
$('#writeUsSubmit').on('click',function(){
    var name = $('#name').val();
    var email = $('#email').val();
    var message = $('#message').val();
    var name_pattern =  /^[ A-z]+$/i;
    var email_pattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i;
    var error_flag = false;
    if(!name_pattern.test(name)){
        $('#name-error').text((language == 'mr') ? "वैध नाव प्रविष्ट करा" : "Enter valid name");
        error_flag = true;
    }
    if(!email_pattern.test(email)){
        $('#email-error').text((language == 'mr') ? "वैध ईमेल पत्ता प्रविष्ट करा" : "Enter valid email address");
        error_flag = true;
     }
    if(message == ""){
        $('#message-error').text((language == 'mr') ? "वैध संदेश प्रविष्ट करा" :"Enter valid message");
        error_flag = true;
    }
    if(!error_flag){
        $.ajax({
            type:"POST",
            url : "/write-to-us/",
            async:false,
            data :{
                'email':email,
                'message':message,
                'name':name
            },
            error:function(data,xhr,err){

            },
            success:function(data, textStatus, xhr){
                if(xhr.status == 200){                              //Success
                    $('#confirm-message').text(xhr.responseText);
                    $('#name').val("");
                    $('#email').val("");
                    $('#message').val("");
                }else if(xhr.status == 403){                        //mail didn't send.
                    $('#confirm-message').text(xhr.responseText);
                }else if(xhr.status == 500){                        //Some exception occured.
                    $('#confirm-message').text(xhr.responseText);
                }
            }

        });
    }
});

$("#subscribe-submit").on('click',function(){
    enteredData = $('#subscribe-email').val();
    email_pattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
    mobile_pattern = /^[0-9]{10}$/;
    if(email_pattern.test(enteredData) || mobile_pattern.test(enteredData) ){
        $.ajax({
            url:'/subscribe/',
            type:'POST',
            async: false,
            data:{
                'enteredData': enteredData
            },
            error:function(data,xhr,err){

            },
            success:function(data, textStatus, xhr){
                if(xhr.status == 200){
                    $('#subscribe-message').text(xhr.responseText);
                }else if(xhr.status == 202){
                    $('#subscribe-message').text(xhr.responseText);
                }else{
                    $('#subscribe-message').text(xhr.responseText);
                }
                $('#subscribe-email').val("");
            }
        });
    }else{
        $('#subscribe-message').text( (language == 'mr') ? "योग्य स्वरूपात मूल्य प्रविष्ट करा" :"Enter value in proper format");
    }
});

$('#subscribe-email').focus(function(){
    $('#subscribe-message').text("");
});
$('#name,#email,#message').focus(function(){
    $('#name-error').text("");
    $('#email-error').text("");
    $('#message-error').text("");
    $('#confirm-message').text("");

});
