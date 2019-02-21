    $(document).ready(function(){
        var citiList = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('office_name'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                url: "/my-account/get-pincode?pincode=%QUERY",
                filter: function(x) {
                    return $.map(x, function (data) {
                        return {
                            pincode: data.pincode,
                            at_post: data.post_offices,
                            state: data.state
                        };
                    });
                },
               wildcard: "%QUERY"
            }
        });
        var language = $('#language').val();
        citiList.initialize();
        $('#pincode').typeahead(null, {
            display: 'pincode',
            source: citiList.ttAdapter(),
            templates: {
                suggestion: Handlebars.compile('<div><input type="text" class="form-control"  style=" border: solid 1px deepskyblue ;padding-top: 5px ; color: black;" value="{{pincode}}"></div>')
            }
        }).on('typeahead:selected', function (obj, datum) {
            var POData = $.parseJSON(JSON.stringify(datum));
            $('#pincode').val(POData["pincode"]);
            $('#atPost').html(POData["at_post"]);
            $('#stateName').val(POData["state"]);
            $('#atPost').trigger('change');
            $("#notifyMe").prop('disabled', true);
        }).on('typeahead:open', function (obj, datum) {
            $('#atPost').html('');
            $('#taluka').val('');
            $('#district').val('');
            $('#stateName').val('');
        });
    });

    $(document).ready(function(){
        $("#notifyMe").on('click', function(){
            var pincode = $('#edit_pincode').val();
            if(!(typeof pincode == 'undefined' || pincode == '')){
                $('#notify_pincode').val(pincode);
                $('#modal_notify_me').modal('show');
            }
        });
        $('#atPost').on('change', function(){
            var postId = $(this).val();
            var pincode = $("#pincode").val();
            $.ajax({
                url:'/my-account/get-post-office-info/'+postId+'/'+pincode,
                method: 'GET',
                async: false,
                success: function(data,textStatus,xhr){
                    $('#taluka').val(data.taluka);
                    $('#district').val(data.district);
                },
                error: function(data){

                }
            });
        });

        $(document).on("click",".btn-edit",function(){
            $(".logo-wrap").css("z-index","1");
            //$(this).next().slideToggle();
        });

        $(document).on("click",".add",function(){
            $(".logo-wrap").css("z-index","1");
            //$(this).next().slideToggle();
        });

        $('#add-new').on('hidden.bs.modal', function () {
            $(".logo-wrap").css("z-index","9999");
        });

        $('#edit-address').on('hidden.bs.modal', function () {
            $(".logo-wrap").css("z-index","9999");
        });

        $(document).on("click",'.btn-delete',function (e) {
            e.preventDefault();
            var id = $(this).data("delete");
            var rememberToken = $('meta[name="csrf_token"]').attr('content');
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN' : rememberToken } });
            $.ajax({
                url: "/address/delete",
                async:true,
                data: {'_method':'DELETE','id':id},
                error: function(data, textStatus, xhr) {

                },
                success: function(data, textStatus, xhr) {
                    if(xhr.status==200){
                        $("#address_"+id).remove();
                        location.reload();
                    }
                },
                type: 'POST'
            });

        });

        $(document).on("click",'.set_default',function (e) {
            e.preventDefault();
            var id = $(this).val();
            var rememberToken = $('meta[name="csrf_token"]').attr('content');
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN' : rememberToken } });
            $.ajax({
                url: "/address/set-default",
                async:true,
                data: {'id':id},
                error: function(data, textStatus, xhr) {

                },
                success: function(data, textStatus, xhr) {
                    if(xhr.status==200){
                        location.reload();
                    }
                },
                type: 'POST'
            });

        });

    });

var FormValidation1 = function () {
var handleValidation5 = function() {
      var language = $('#language').val();
    // for more info visit the official plugin documentation:
    // http://docs.jquery.com/Plugins/Validation
    var form1 = $('#add_address');
    var error1 = $('.alert-danger', form1);
    var success1 = $('.alert-success', form1);
    if(language == 'en'){
      var alpha_num_space_sym = "Only alpha num & / and - are allowed";
      var alpha_num_space_allow = "Only alpha num & space are allowed and starting with space and special char not allowed";
        var mobile = "Mobile number should be 10 digits only.";
    }else{
      var alpha_num_space_sym = "केवळ अक्षरे संख्या & / आणि - अनुमती आहेत";
      var alpha_num_space_allow = "केवळ अल्फा क्रमांक आणि जागा परवानगी दिली आणि जागा परवानगी नाही सुरू आहेत";
        var mobile = "मोबाइल अंकी असणे आवश्यक आहे आणि तो 10 अंकी असावा";
    }
    $.validator.addMethod("alphanumeric", function(value, element) {
        return this.optional(element) || /^[a-zA-Z0-9]+$/.test(value);
    });
    $.validator.addMethod("alpha", function(value, element) {
        return this.optional(element) || /^[a-zA-Z ]+([a-zA-Z])*$/.test(value);
    });
    $.validator.addMethod("alphanumsymbols", function(value, element) {
        return this.optional(element) || /^[ A-Za-z0-9-,.]*$/i.test(value);
    });
    $.validator.addMethod("alpha_num_space_sym", function(value, element) {
        return this.optional(element) || /^[a-zA-Z0-9]+([A-Za-z0-9/-])*$/.test(value);
    }, alpha_num_space_sym);
    $.validator.addMethod("alpha_num_space_allow", function(value, element) {
        return this.optional(element) || /^[a-zA-Z0-9]+([ A-Za-z0-9@./#',&-])*$/.test(value);
    }, alpha_num_space_allow);
    $.validator.addMethod("mobile", function(value, element) {
        return this.optional(element) || value == value.match(/^[0-9]{10}$/);
    }, mobile);
    form1.validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block help-block-error', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        ignore: "",  // validate all fields including form hidden input
        messages: {
            full_name:{
                required: (language == 'en') ? " Name is required." : " नाव क्षेत्र आवश्यक आहे.",
                minlength: jQuery.validator.format((language == 'mr') ? "कृपया किमान {0} वर्ण प्रविष्ट करा." : "Please enter at least {0} characters."),
                maxlength: jQuery.validator.format((language == 'mr') ? "कृपया {0} वर्णांपेक्षा अधिक प्रविष्ट करू नकाे." : "Please enter no more than {0} characters.")
             //   alpha: (language == 'en') ? "Only alpha is allowed." : "केवळ अक्षरे अनुमत आहेत"
            },
            mobile:{
                required: (language != 'en') ? "मोबाइल नंबर आवश्यक आहे" : "Mobile number is required."
            },
            at_post: {
                required: (language == 'en') ? "At Post is required." : "पोस्ट आवश्यक आह"
            },
            taluka: {
                required: (language == 'en') ? "Taluka is required." : "तालुका आवश्यक आह"
            },
            district: {
                required: (language == 'en') ? "District is required." : "जिल्हा आवश्यक आहे"
            },
            state: {
                required: (language == 'en') ? "State is required." : "राज्य आवश्यक आह"
            },
            pincode: {
                required: (language == 'en') ? "Zip code is required." : "पिन कोड आवश्यक  आहे."
            },
            name_of_premise_building_village: {
                minlength: jQuery.validator.format((language == 'mr') ? "कृपया किमान {0} वर्ण प्रविष्ट करा." : "Please enter at least {0} characters."),
                maxlength: jQuery.validator.format((language == 'mr') ? "कृपया {0} वर्णांपेक्षा अधिक प्रविष्ट करू नकाे." : "Please enter no more than {0} characters.")
            },
            flat_door_block_house_no: {
                minlength: jQuery.validator.format((language == 'mr') ? "कृपया किमान {0} वर्ण प्रविष्ट करा." : "Please enter at least {0} characters."),
                maxlength: jQuery.validator.format((language == 'mr') ? "कृपया {0} वर्णांपेक्षा अधिक प्रविष्ट करू नकाे." : "Please enter no more than {0} characters.")
            },
            area_locality_wadi: {
                minlength: jQuery.validator.format((language == 'mr') ? "कृपया किमान {0} वर्ण प्रविष्ट करा." : "Please enter at least {0} characters."),
                maxlength: jQuery.validator.format((language == 'mr') ? "कृपया {0} वर्णांपेक्षा अधिक प्रविष्ट करू नकाे." : "Please enter no more than {0} characters.")
            },
            road_street_lane: {
                minlength: jQuery.validator.format((language == 'mr') ? "कृपया किमान {0} वर्ण प्रविष्ट करा." : "Please enter at least {0} characters."),
                maxlength: jQuery.validator.format((language == 'mr') ? "कृपया {0} वर्णांपेक्षा अधिक प्रविष्ट करू नकाे." : "Please enter no more than {0} characters.")
                //alphanumsymbols: (language == 'en') ? "Only alpha numeric and - , . is allowed." : "केवळ अक्षरे संख्या  - ,  आणि . अनुमती आहेत"
            }

        },
        rules: {
            full_name:{
                required: true,
               // alpha: true,
                minlength: 1,
                maxlength: 25
            },
            mobile:{
                required: true,
                mobile: true
            },
            at_post: {
                required: true
            },
            taluka: {
                required: true
            },
            district: {
                required: true
            },
            state: {
                required: true
            },
            pincode: {
                required: true
            },
            name_of_premise_building_village: {
                minlength: 1,
                maxlength: 25
               // alpha_num_space_allow:true
            },
            flat_door_block_house_no: {
                minlength: 1,
                maxlength: 25
               // alpha_num_space_sym:true
            },
            area_locality_wadi: {
                minlength: 1,
                maxlength: 25
                //alpha_num_space_allow:true
            },
            road_street_lane: {
                minlength: 1,
                maxlength: 25
                //alpha_num_space_allow:true
            }
        },

        errorPlacement: function (error, element) { // render error placement for each input type
            if (element.parent(".input-group").size() > 0) {
                error.insertAfter(element.parent(".input-group"));
            } else if (element.attr("data-error-container")) {
                error.appendTo(element.attr("data-error-container"));
            }  else {
                error.insertAfter(element); // for other inputs, just perform default behavior
            }
        },
        invalidHandler: function (event, validator) { //display error alert on form submit
            success1.hide();
            error1.show();
            App.scrollTo(error1, -200);
        },

        highlight: function (element) { // hightlight error inputs
            $(element)
                .closest('.form-group').addClass('has-error'); // set error class to the control group
        },

        unhighlight: function (element) { // revert the change done by hightlight
            $(element)
                .closest('.form-group').removeClass('has-error'); // set error class to the control group
        },

        success: function (label) {
            label
                .closest('.form-group').removeClass('has-error'); // set success class to the control group
        },

        submitHandler: function (form) {
            success1.show();
            error1.hide();
            return true;
        }
    });


}
    return {
        //main function to initiate the module
        init: function () {
            handleValidation5();
        }
    };

}();

jQuery(document).ready(function() {
    FormValidation1.init();
});
