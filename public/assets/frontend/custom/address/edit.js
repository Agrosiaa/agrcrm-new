var language = $('#language').val();
$(document).ready(function () {
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
    $('#edit_pincode').typeahead(null, {
        display: 'pincode',
        source: citiList.ttAdapter(),
        templates: {
            suggestion: Handlebars.compile('<div><input type="text" class="form-control"  style=" border: solid 1px deepskyblue ;padding-top: 5px ; color: black;" value="{{pincode}}"></div>')
        }
    }).on('typeahead:selected', function (obj, datum) {
        var POData = new Array();
        POData = $.parseJSON(JSON.stringify(datum));
        $('#edit_pincode').val(POData["pincode"]);
        $('#edit_at_Post').html(POData["at_post"]);
        $('#edit_state').val(POData["state"]);
        $('#edit_at_Post').trigger('change');
        $("#edit_notifyMe").prop('disabled', true);
    }).on('typeahead:open', function (obj, datum) {
        $('#edit_at_Post').html('');
        $('#edit_taluka').val('');
        $('#edit_state').val('');
        $('#edit_district').val('');
    });
    $("#edit_notifyMe").on('click', function(){
        var pincode = $('#edit_pincode').val();
        if(!(typeof pincode == 'undefined' || pincode == '')){
            $('#notify_pincode').val(pincode);
            $('#modal_notify_me').modal('show');
        }
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


    $(document).on("click",'.btn-edit',function (e) {
        e.preventDefault();
        $("#edit_is_default").prop("checked", false);
        $("#edit_is_default").removeAttr("disabled");
        var id = $(this).data("edit");
        var rememberToken = $('meta[name="csrf_token"]').attr('content');
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN' : rememberToken } });
        $.ajax({
            url: "/address/get",
            async:true,
            data: {'id':id},
            error: function(data, textStatus, xhr) {

            },
            success: function(data, textStatus, xhr) {
                if(xhr.status==200){
                    $("#modal_edit_address").modal('show');
                    $("#form_edit_address").html(data);
                    $('#edit_at_Post').trigger('change');
                }
            },
            type: 'POST'
        });
    });
});

function showUserAddress(){
    $.ajax({
        url: "/address/get",
        async:false,
        error: function(data, textStatus, xhr) {
            $('#address-list').html(data);
        },
        success: function(data, textStatus, xhr) {
            if(xhr.status==200){
                $('#address-list').html(data);
            }else{
                $('#address-list').html(data);
            }
        },
        type: 'GET'
    });
}


var FormValidation = function () {
    var handleValidation6 = function() {
        // for more info visit the official plugin documentation:
        // http://docs.jquery.com/Plugins/Validation
        var form1 = $('#form_edit_address');
        var error1 = $('.alert-danger', form1);
        var success1 = $('.alert-success', form1);
        $.getScript("/assets/frontend/custom/rules/index.js");
        form1.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",  // validate all fields including form hidden input
            messages: {
                full_name:{
                    required: (language == 'mr') ? "नाव आवश्यक आहे." : " Name is required.",
                    minlength: jQuery.validator.format((language == 'mr') ? "कृपया किमान {0} वर्ण प्रविष्ट करा." : "Please enter at least {0} characters."),
                    maxlength: jQuery.validator.format((language == 'mr') ? "कृपया {0} वर्णांपेक्षा अधिक प्रविष्ट करू नकाे." : "Please enter no more than {0} characters.")
                },
                mobile:{
                    required: (language == 'mr') ? "मोबाइल नंबर आवश्यक आहे" : "Mobile number is required."
                },
                at_post: {
                    required: (language == 'mr') ? "पोस्ट आवश्यक आहे" : "At Post is required."
                },
                taluka: {
                    required: (language == 'mr') ? "तालुका आवश्यक आहे " : "Taluka is required."
                },
                district: {
                    required: (language == 'mr') ? "जिल्हा आवश्यक आहे" : "District is required."
                },
                state: {
                    required: (language == 'mr') ? "राज्य आवश्यक आहे" : "State is required."
                },
                pincode: {
                    required: (language == 'mr') ? "पिन कोड आवश्यक  आहे." : "Zip code is required."
                },
                name_of_premise_building_village: {
                    //required: (language == 'mr') ? "घर / इमारत / गावचे नाव आवश्यक आहे." : "Name of premise /building/village is required.",
                    minlength: jQuery.validator.format((language == 'mr') ? "कृपया किमान {0} वर्ण प्रविष्ट करा." : "Please enter at least {0} characters."),
                    maxlength: jQuery.validator.format((language == 'mr') ? "कृपया {0} वर्णांपेक्षा अधिक प्रविष्ट करू नकाे." : "Please enter no more than {0} characters.")
                },
                flat_door_block_house_no: {
                    //required: (language == 'mr') ? "फ्लॅट / दरवाजा / ब्लॉक / घर नं आवश्यक आहे." : "Flat/door/block/house no is required.",
                    minlength: jQuery.validator.format((language == 'mr') ? "कृपया किमान {0} वर्ण प्रविष्ट करा." : "Please enter at least {0} characters."),
                    maxlength: jQuery.validator.format((language == 'mr') ? "कृपया {0} वर्णांपेक्षा अधिक प्रविष्ट करू नकाे." : "Please enter no more than {0} characters.")
                },
                area_locality_wadi: {
                    required: (language == 'mr') ? "क्षेत्र / परिसर / वाडी आवश्यक आहे" : "Area/locality/wadi is required.",
                    minlength: jQuery.validator.format((language == 'mr') ? "कृपया किमान {0} वर्ण प्रविष्ट करा." : "Please enter at least {0} characters."),
                    maxlength: jQuery.validator.format((language == 'mr') ? "कृपया {0} वर्णांपेक्षा अधिक प्रविष्ट करू नकाे." : "Please enter no more than {0} characters.")
                },
                road_street_lane: {
                    required: (language == 'mr') ? "रस्ता / मार्ग / लेन आवश्यक आहे." : "Road/street/lane is required.",
                    minlength: jQuery.validator.format((language == 'mr') ? "कृपया किमान {0} वर्ण प्रविष्ट करा." : "Please enter at least {0} characters."),
                    maxlength: jQuery.validator.format((language == 'mr') ? "कृपया {0} वर्णांपेक्षा अधिक प्रविष्ट करू नकाे." : "Please enter no more than {0} characters.")
                }

            },
            rules: {
                full_name:{
                    required: true,
                    minlength: 1,
                    maxlength: 50                   // alphaSpace:true
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
                    maxlength: 50
                   // alpha_num_space_allow:true,
                },
                flat_door_block_house_no: {
                    minlength: 1,
                    maxlength: 50
                  //  alphaSpaceSpecial:true
                },
                area_locality_wadi: {
                    minlength: 1,
                    maxlength: 50
                  //  areaLocality:true,
                },
                road_street_lane: {
                    minlength: 1,
                    maxlength: 50
                  //  areaLocality:true
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
                var rememberToken = $('meta[name="csrf_token"]').attr('content');
                $.ajaxSetup({ headers: { 'X-CSRF-TOKEN' : rememberToken } });
                $.ajax({
                    url: "/address/edit",
                    async:true,
                    data: $("#form_edit_address").serializeArray(),
                    error: function(data, textStatus, xhr) {

                    },
                    success: function(data, textStatus, xhr) {
                        if(xhr.status==200){
                            $("#modal_edit_address").modal('hide');
                            $('#form_edit_address')[0].reset();
                            showUserAddress();
                            location.reload();
                        }
                    },
                    type: 'POST'
                });
                return false;
            }
        });


    }
    return {
        //main function to initiate the module
        init: function () {
            handleValidation6();
        }
    };

}();

jQuery(document).ready(function() {
    FormValidation.init();
});

