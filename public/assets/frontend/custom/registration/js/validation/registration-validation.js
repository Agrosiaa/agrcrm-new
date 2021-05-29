$(document).ready(function(){
    var citiList = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('office_name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: "/address/post-office?office_name=%QUERY",
            replace: function (url, query) {
                var taluka = $('#taluka').val();
                var atPost = $('#at_post').val();
                var url = "/seller/get-post-offices?office_name="+atPost+"&taluka="+taluka;

                return url;
            },
            filter: function(x) {
                return $.map(x, function (data) {
                    return {
                        office_name: data.office_name,
                        pincode: data.pincode,
                        taluka: data.taluka,
                        district: data.district,
                        state: data.state
                    };
                });
            },
            wildcard: "%QUERY"
        }
    });
    var language = $('#language').val();
    citiList.initialize();
    $('#at-post .typeahead').typeahead(null, {
        display: 'office_name',
        source: citiList.ttAdapter(),
        templates: {
            empty: [
                '<div class="empty-message">',
                (language == 'mr') ? 'चालू क्वेरी जुळणारे कोणत्याही पोस्ट ऑफिसमध्ये शोधण्यात अक्षम' : 'Unable to find any Post office that match the current query9',
                '</div>'
            ].join('\n')
            //suggestion: Handlebars.compile('<div><strong>'+name+'</strong> - </div>')
        }
    }).on('typeahead:selected', function (obj, datum) {
        var POData = new Array();
        POData = $.parseJSON(JSON.stringify(datum));
        $('#pincode').val(POData["pincode"]);
    }).on('typeahead:open', function (obj, datum) {
        $('#pincode').val("");
    });
});

var validator = $("#commentForm").validate(true);
$(document).ready(function(){
    var language = $('#language').val();
    $( "#exp_date1" ).datepicker({
        changeMonth: true,//this option for allowing user to select month
        changeYear: true, //this option for allowing user to select from year range
        dateFormat: 'mm/y'
    });
    $( "#exp_date2" ).datepicker({
        changeMonth: true,//this option for allowing user to select month
        changeYear: true, //this option for allowing user to select from year range
        dateFormat: 'mm/y'
    });
    $( "#exp_date3" ).datepicker({
        changeMonth: true,//this option for allowing user to select month
        changeYear: true, //this option for allowing user to select from year range
        dateFormat: 'mm/y'
    });
    $( "#exp_date4" ).datepicker({
        changeMonth: true,//this option for allowing user to select month
        changeYear: true, //this option for allowing user to select from year range
        dateFormat: 'mm/y'
    });
    $("#seedsDoc" ).hide();
    $("#fertilizerDoc").hide();
    $("#pesticideDoc").hide();
    $("#otherDoc").hide();
    $(".wizard-progress ul li").eq(0).addClass("in-progress");
    $(".wizard-tab-wrap .wizard-tab").eq(0).show();
    var index = $(this).parent(".wizard-tab").index();
    if(language == 'en'){
      var lettersonly = "Please enter only letters";
      var alphanum = "Please enter only alphabets and digits";
      var alpha_num_space_sym = "Only alpha num & / and - are allowed";
      var alpha_num_space_allow = "Only alpha num & space are allowed and starting with space and special char not allowed";
      var chkMail = "Please enter valid email address.";
      var alpha_num_space = "Only alpha num & space are allowed and starting with space not allowed";
      var alphaSpace = "Please enter only contains alpha and space and starting with alphabet";
    }else{
      var lettersonly = "केवळ अक्षरे प्रविष्ट करा";
      var alphanum = "केवळ अक्षरे आणि जागा अनुमत आहेत";
      var alpha_num_space_sym = "केवळ अक्षरे संख्या & / आणि - अनुमती आहेत";
      var alpha_num_space_allow = "केवळ अल्फा क्रमांक आणि जागा परवानगी दिली आणि जागा परवानगी नाही सुरू आहेत";
      var chkMail = "वैद्य असलेला ई- मेल ID आवश्यक आहे";
      var alpha_num_space = "केवळ अक्षरे आणि जागा अनुमत आहेत";
      var alphaSpace = "केवळ अक्षरे आणि जागा अनुमत आहेत";
    }
    /* some custom methos for validations */
    jQuery.validator.addMethod("lettersonly", function(value, element)
    {
        return this.optional(element) || /^[A-z]+$/i.test(value);
    },lettersonly);

    jQuery.validator.addMethod("alphanum", function(value, element)
    {
        return this.optional(element) || /^[a-zA-Z0-9]*$/i.test(value);
    }, alphanum);
    jQuery.validator.addMethod("alphanumeric", function(value, element)
    {
        return this.optional(element) || /^([a-zA-Z ]{4})+([0-9 ]{7})$/i.test(value);
    }, "Please enter in this format name0123456");
    jQuery.validator.addMethod("alpha_num_space_sym", function(value, element)
    {
        return this.optional(element) || /^[a-zA-Z0-9]+([A-Za-z0-9/-])*$/.test(value);
    }, alpha_num_space_sym);
    jQuery.validator.addMethod("alpha_num_space_allow", function(value, element)
    {
        return this.optional(element) || /^[a-zA-Z0-9]+([ A-Za-z0-9@./#',&-])*$/.test(value);
    }, alpha_num_space_allow);
    jQuery.validator.addMethod("alphanumsymbols", function(value, element)
    {
        return this.optional(element) || /^[ A-Za-z0-9-,.]*$/i.test(value);
    }, "Please enter valid data");
    jQuery.validator.addMethod("alphanumsymbolslash", function(value, element)
    {
        return this.optional(element) || /^[ A-Za-z0-9-/,.]*$/i.test(value);
    }, "Please enter valid data");
    jQuery.validator.addMethod("alphanumsymbolsquote", function(value, element)
    {
        return this.optional(element) || /^[ A-Za-z0-9-/',.]*$/i.test(value);
    }, "Please enter valid data");

    $.validator.addMethod("ifsc", function(value, element) {
        return this.optional(element) || value == value.match(/^[A-Za-z0-9]{11}$/);
    },"Please enter in this format asdc0123456");
    $.validator.addMethod("alphaSpace", function(value, element) {
        return this.optional(element) || value == value.match(/^[a-zA-Z]+[a-zA-Z\s]+$/);
    }, alphaSpace);
    $.validator.addMethod("chkMail", function(value, element) {
        return this.optional(element) || /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i.test(value);
    },chkMail);
    $.validator.addMethod("alpha_num_space", function(value, element) {
        return this.optional(element) || /^[a-zA-Z0-9]+([a-zA-Z0-9\s])*$/.test(value);
    },alpha_num_space );

    $.validator.addMethod("pan", function(value, element) {
        return this.optional(element) || value == value.match(/^[a-zA-Z]{5}[0-9]{4}[A-Za-z]{1}$/);
    },"Please Enter valid PAN Number");
    $.validator.addMethod("tan", function(value, element) {
        return this.optional(element) || value == value.match(/^[a-zA-Z]{4}[0-9]{5}[A-Za-z]{1}$/);
    },"Please Enter valid TAN Number");

    jQuery.extend(jQuery.validator.messages, {

        name:{required: "Please enter First name"}

    });


    $.validator.addMethod('unique',
        function(value) {
            var result = $.ajax({
                type: "POST",
                async:false,
                url: "/seller/check-email",
                data:'email='+ value,
                dataType:"text" });
            if(result .responseText == 'true') return true; else return false;
        } , "This email address has already been  registered");
    $.validator.addMethod('uniqueotp',
        function(value) {
            var mobile_number =  $( "#mobile_no" ).val();
            var otp = $( "#code" ).val();
            var result_flag = 'false';
            $.ajax({
                type: "POST",
                async:false,
                url: "/seller/check-otp",
                data:{ mobile: mobile_number, otp : otp},
                dataType:"text",
                error: function(data,xhr,err) {
                },
                success: function(data, textStatus, xhr) {
                    if(xhr.status==200){
                        $('#code').attr('readonly', true);
                        result_flag = 'true';
                        $('#vcode').show();
                    }else{

                    }
                }
            });
            if(result_flag == 'true') return true; else return false;
        } , 'This OTP is either invalid or expired');

    $.validator.addMethod("FRequired", $.validator.methods.required,
        "First name required");
    $.validator.addMethod("LRequired", $.validator.methods.required,
        "Last name required");

    /* add validation rules to all input elements */
    jQuery.validator.addClassRules({
        name: {
            FRequired: true,
            lettersonly: true,
            minlength: 3,
            maxlength: 15
        },
        lname: {
            LRequired: true,
            lettersonly: true,
            minlength: 3,
            maxlength: 15
        },
        mail: {
            required: true,
            chkMail: true,
            remote: {
                type: "POST",
                url: "/seller/check-email"
            }
        },
        mobile: {
            required: true,
            digits:true,
            minlength: 10,
            maxlength: 10
        },
        verification: {
            required: true,
            uniqueotp: true
        },
        company: {
            required: true,
            alphaSpace: true,
            minlength: 5
        },
        shop: {
            required: true,
            //alpha_num_space_sym:true,
            minlength:1,
            maxlength:25
        },
        premises: {
            required: true,
            //alpha_num_space_allow:true,
            minlength: 1,
            maxlength: 25
        },
        area: {
            required: true,
            //alpha_num_space_allow:true,
            minlength: 1,
            maxlength: 25
        },
        zip: {
            required: true,
            digits:true,
            minlength: 6,
            maxlength: 6
        },
        password: {
            required: true,
            minlength: 5,
            maxlength: 20
        },
        conf_password: {
            required: true,
            equalTo: ".password",
            minlength: 5

        },
        pan_number: {
            required: true,
            pan: true
        },
        tan_number: {
            tan: true
        },

        gstin_number: {
            required : true,
            alpha_num_space: true,
            minlength: 15,
            maxlength: 15
        },
        bank: {
            required: true,
            alphaSpace: true,
            minlength: 3,
            maxlength: 50
        },
        branch: {
            required: true,
            alpha_num_space: true,
            minlength: 5,
            maxlength: 50
        },
        benificiary_name: {
            required: true,
            alpha_num_space: true,
            minlength: 5,
            maxlength: 50
        },
        ifsc_code: {
            required: true,
            ifsc: true
        },
        acc_no: {
            required: true,
            digits: true,
            minlength: 9,
            maxlength: 16
        },
        company_reg: {
            alphanum: true,
            minlength: 5,
            maxlength: 50
        },
        comp_name: {
            required: true,
            alpha_num_space: true,
            minlength: 3
        },
        adress: {
            required: true,
            alphanumsymbols: true
        },
        adress1: {
            alphanumsymbols: true
        },
        license_no1: {
            digits: true,
            required: {
                depends: function(element){
                    if ($('#exp_date1').val() == '') {
                        return false;
                    } else {
                        return true;
                    }
                }
            }
        },
        license_no2: {
            digits: true,
            required: {
                depends: function(element){
                    if ($('#exp_date2').val() == '') {
                        return false;
                    } else {
                        return true;
                    }
                }
            }
        },
        license_no3: {
            digits: true,
            required: {
                depends: function(element){
                    if ($('#exp_date3').val() == '') {
                        return false;
                    } else {
                        return true;
                    }
                }
            }
        },
        license_no4: {
            digits: true,
            required: {
                depends: function(element){
                    if ($('#exp_date4').val() == '') {
                        return false;
                    } else {
                        return true;
                    }
                }
            }
        },
        typeahead: {
            required: true
        },
        taluka:{
            required: true
        },
        district:{
            required: true
        },
        pincode:{
            required: true
        },
        state:{
            required: true
        },
        road_street_lane:{
            required: true,
            //alpha_num_space_allow:true,
            minlength: 1,
            maxlength: 25
        },
        exp_date1:{
            required: true
        },
        exp_date2:{
            required: true
        },
        exp_date3:{
            required: true
        },
        exp_date4:{
            required: true
        }
    });
    $.validator.messages.remote = 'This email address has already been  registered';
    var validate = function(ids,step){
        var status=true;
        for(i=0;i<ids.length;i++){
            if(!validator.element(ids[i]))
                status = false;
        }
        return status
    }

    $("#send-otp").click(function(){
        var mobileID = ["#mobile_no"];
        if(validate(mobileID)){
            generateotp();
            //$('#send-otp').prop("disabled", false);
        }else{
            //$('#send-otp').prop("disabled", true);
        }
    });
    $(document).on("click",".wizard-tab .custom-button",function(){
        var ids = ["#cname","#lastname","#email","#mobile_no","#company","#shop","#premises","#area","#at_post","#taluka","#district","#state","#pincode","#road_street_lane"];
        if(validate(ids)){
          step1();
        }
        $('html,body').animate({scrollTop: $(".wizard-progress").offset().top},'slow');
    });

    $(document).on("click",".wizard-tab .custom-button1",function(){

        var ids1 = ["#pass","#pass1","#pan","#registration_no","#bank_name","#branch_name","#ifsc_no","#benificiary","#account","#tan","#gstin","#license1","#license2","#license3","#license4"];

        if(validator.element("#license1") && $("#license1").val() != ""){
            $("#seedsDoc").show();
            //validator.element("#exp_date1");
            ids1.push("#exp_date1");
        }else{
            $("#seedsDoc" ).hide();
            document.getElementById("file_name5").innerHTML = " ";
            $("#seed_licence").val('');


        }
        if(validator.element("#license2") && $("#license2").val() != ""){
            $("#fertilizerDoc").show();
            ids1.push("#exp_date2");
        }else{
            $("#fertilizerDoc").hide();
            document.getElementById("file_name6").innerHTML = " ";
            $("#fertilizer_licence").val('');

        }
        if(validator.element("#license3") && $("#license3").val() != ""){
            $("#pesticideDoc").show();
            ids1.push("#exp_date3");
        }else{
            $("#pesticideDoc").hide();
            document.getElementById("file_name7").innerHTML = " ";
            $("#pesticide_licence").val('');

        }
        if(validator.element("#license4") && $("#license4").val() != ""){
            $("#otherDoc").show();
            ids1.push("#exp_date4");
        }else{
            $("#otherDoc").hide();
            document.getElementById("file_name8").innerHTML = " ";
            $("#other_licence").val('');
        }
        if(validate(ids1)){
            step2();
        }
        $('html,body').animate({scrollTop: $(".wizard-progress").offset().top},'slow');

    });

    $(document).on("click",".wizard-tab .custom-button2",function(){
        if(!$("#pan_card").val()){
            $("#pancard_error").html("");
            $("#pancard_error").html("Pancard is required");
        } else if($("#pan_card").val()){
            checkDocument("#pan_card","#pancard_error");
        }

        if(!$("#shop_act").val()){
            $("#shopact_error").html("");
            $("#shopact_error").html("Shop act is required");
        } else if($("#shop_act").val()){
            checkDocument("#shop_act","#shopact_error");
        }

        if(!$("#gstin_certificate").val()){
            $("#gstin_certificate_error").html("");
            $("#gstin_certificate_error").html("GSTIN certificate is required");
        } else if($("#gstin_certificate").val()){
            checkDocument("#gstin_certificate","#gstin_certificate_error");
        }

        if(!$("#cancelled_cheque").val()){
            $("#cheque_error").html("");
            $("#cheque_error").html("Cancelled Cheque is required");
        } else if($("#cancelled_cheque").val()){
            checkDocument("#cancelled_cheque","#cheque_error");
        }

        if(validator.element("#license1") && $("#license1").val() != ""){
            if(!$("#seed_licence").val()){
                $("#seed_licence_error").html("");
                $("#seed_licence_error").html("Seeds Licence is required");
            } else if($("#seed_licence").val()){
                checkDocument("#seed_licence","#seed_licence_error");
            }
        }else{
            $("#seed_licence_error").html("");
        }

        if(validator.element("#license2") && $("#license2").val() != ""){
            if(!$("#fertilizer_licence").val()){
                $("#fertilizer_licence_error").html("");
                $("#fertilizer_licence_error").html("Fertilizer Licence is required");
            } else if($("#fertilizer_licence").val()){
                checkDocument("#fertilizer_licence","#fertilizer_licence_error");
            }
        }else{
            $("#fertilizer_licence_error").html("");
        }


        if(validator.element("#license3") && $("#license3").val() != ""){
            if(!$("#pesticide_licence").val()){
                $("#pesticide_licence_error").html("");
                $("#pesticide_licence_error").html("Pesticides Licence is required");
            } else if($("#pesticide_licence").val()){
                checkDocument("#pesticide_licence","#pesticide_licence_error");
            }
        }else{
            $("#pesticide_licence_error").html("");
        }
        if(validator.element("#license4") && $("#license4").val() != ""){
            if(!$("#other_licence").val()){
                $("#other_licence_error").html("");
                $("#other_licence_error").html("Other Licence is required");
            } else if($("#other_licence").val()){
                checkDocument("#other_licence","#other_licence_error");
            }
        }else{
            $("#other_licence_error").html("");
        }


        function checkDocument(docId,msgDiv){
            var fileExtension = ['png', 'jpg', 'jpeg', 'pdf'];
            var iSize = ($(docId)[0].files[0].size / 1024);
            iSize = (Math.round((iSize / 1024) * 100) / 100);
            if ($.inArray($(docId).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
                $(msgDiv).html("");
                $(msgDiv).html("Only formats are allowed : "+fileExtension.join(', '));
            } else if (iSize > 10) {
                $(msgDiv).html("");
                $(msgDiv).html("File exceeds maximum size limit of 10 MB");
            } else {
                $(msgDiv).html("");
            }
        }

        if($('#pancard_error').is(':empty') && $('#cheque_error').is(':empty') && $('#gstin_certificate_error').is(':empty') && $('#shopact_error').is(':empty') && $('#seed_licence_error').is(':empty') && $('#fertilizer_licence_error').is(':empty') && $('#pesticide_licence_error').is(':empty') && $('#other_licence_error').is(':empty')){

            $("#commentForm").submit();
        } else {
            return false;
        }
        $('html,body').animate({scrollTop: $(".wizard-progress").offset().top},'slow');
    });

    $(document).on("click",".wizard-tab .btn-back",function(){
      step1();
    });


    $( "#clear" ).click(function() {
        document.getElementById("file_name1").innerHTML = " ";
        document.getElementById("file_name2").innerHTML = " ";
        document.getElementById("file_name3").innerHTML = " ";
        document.getElementById("file_name4").innerHTML = " ";
        document.getElementById("file_name5").innerHTML = " ";
        document.getElementById("file_name6").innerHTML = " ";
        document.getElementById("file_name7").innerHTML = " ";
        document.getElementById("file_name8").innerHTML = " ";

        $('#uploadDocuments').find('input').val('');
    });

    $("#clearSecondStep").click(function(){
        $('#bankInfo').find('input').val('');
        $('#passwordInfo').find('input').val('');
        $('#licenceInfo').find('input').val('');


    });

    function step1(){
        $(".wizard-tab-wrap .wizard-tab").hide();
        $(".wizard-tab-wrap .wizard-tab").eq(index+2).show();
        $(".wizard-progress ul li").eq(index+2).removeClass("initial").addClass("in-progress");
        $(".wizard-progress ul li").eq(index+2).prevAll().removeClass("in-progress initial").addClass("completed");
    }

    function step2(){
        $(".wizard-tab-wrap .wizard-tab").hide();
        $(".wizard-tab-wrap .wizard-tab").eq(index+3).show();
        $(".wizard-progress ul li").eq(index+3).removeClass("initial").addClass("in-progress");
        $(".wizard-progress ul li").eq(index+3).prevAll().removeClass("in-progress initial").addClass("completed");
    }

    $("#mobile_no").keyup(function(){
        $("#otp_message").hide();
    });
});
