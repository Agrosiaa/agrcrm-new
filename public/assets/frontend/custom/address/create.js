$(document).ready(function () {
    var citiList = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('office_name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: "/address/post-office?office_name=%QUERY",
            replace: function (url, query) {
                var taluka = $('#taluka').val();
                var atPost = $('#at_post').val();
                var url = "/my-account/get-post-offices?office_name="+atPost+"&taluka="+taluka;
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
                (language == 'mr') ? 'चालू क्वेरी जुळणारे कोणत्याही पोस्ट ऑफिसमध्ये शोधण्यात अक्षम' : 'Unable to find any Post office that match the current query.',
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
    $.getScript("/assets/frontend/custom/rules/index.js");
    $('#add_new_address').validate({
        messages:{
            full_name : {
                required: (language == 'mr') ? " हे फील्ड आवश्यक आहे." : "This field is required.",
                minlength: jQuery.validator.format((language == 'mr') ? "कृपया किमान {0} वर्ण प्रविष्ट करा." : "Please enter at least {0} characters."),
                maxlength: jQuery.validator.format((language == 'mr') ? "कृपया {0} वर्णांपेक्षा अधिक प्रविष्ट करू नकाे." : "Please enter no more than {0} characters.")
            },
            mobile:{
                required: (language == 'mr') ? " हे फील्ड आवश्यक आहे." : "This field is required.",
            },
            name_of_premise_building_village: {
                required: (language == 'mr') ? " हे फील्ड आवश्यक आहे." : "This field is required.",
                minlength: jQuery.validator.format((language == 'mr') ? "कृपया किमान {0} वर्ण प्रविष्ट करा." : "Please enter at least {0} characters."),
                maxlength: jQuery.validator.format((language == 'mr') ? "कृपया {0} वर्णांपेक्षा अधिक प्रविष्ट करू नकाे." : "Please enter no more than {0} characters.")
            },
            flat_door_block_house_no: {
                required: (language == 'mr') ? " हे फील्ड आवश्यक आहे." : "This field is required.",
                minlength: jQuery.validator.format((language == 'mr') ? "कृपया किमान {0} वर्ण प्रविष्ट करा." : "Please enter at least {0} characters."),
                maxlength: jQuery.validator.format((language == 'mr') ? "कृपया {0} वर्णांपेक्षा अधिक प्रविष्ट करू नकाे." : "Please enter no more than {0} characters.")
            },
            area_locality_wadi: {
                required: (language == 'mr') ? " हे फील्ड आवश्यक आहे." : "This field is required.",
                minlength: jQuery.validator.format((language == 'mr') ? "कृपया किमान {0} वर्ण प्रविष्ट करा." : "Please enter at least {0} characters."),
                maxlength: jQuery.validator.format((language == 'mr') ? "कृपया {0} वर्णांपेक्षा अधिक प्रविष्ट करू नकाे." : "Please enter no more than {0} characters.")
            },
            road_street_lane: {
                required: (language == 'mr') ? " हे फील्ड आवश्यक आहे." : "This field is required.",
                minlength: jQuery.validator.format((language == 'mr') ? "कृपया किमान {0} वर्ण प्रविष्ट करा." : "Please enter at least {0} characters."),
                maxlength: jQuery.validator.format((language == 'mr') ? "कृपया {0} वर्णांपेक्षा अधिक प्रविष्ट करू नकाे." : "Please enter no more than {0} characters.")
            },
            taluka:{
                required: (language == 'mr') ? " हे फील्ड आवश्यक आहे." : "This field is required.",
            },
            district:{
                required: (language == 'mr') ? " हे फील्ड आवश्यक आहे." : "This field is required.",
            },
            pincode:{
                required: (language == 'mr') ? " हे फील्ड आवश्यक आहे." : "This field is required.",
            },
            state:{
                required: (language == 'mr') ? " हे फील्ड आवश्यक आहे." : "This field is required.",
            },
            at_post:{
                required: (language == 'mr') ? " हे फील्ड आवश्यक आहे." : "This field is required.",
            }
        },
        rules: {
            full_name: {
                required: true,
               // alphaSpace:true,
                minlength: 1,
                maxlength: 50
            },
            mobile:{
                required: true,
                mobile: true
            },
            name_of_premise_building_village: {
              //  alpha_num_space_allow:true,
                minlength: 1,
                maxlength: 50
            },
            flat_door_block_house_no: {
                minlength: 1,
                maxlength: 50
               // alphaSpaceSpecial:true
            },
            area_locality_wadi: {
              //  areaLocality:true,
                minlength: 1,
                maxlength: 50
            },
            road_street_lane: {
              //  areaLocality:true,
                minlength: 1,
                maxlength: 50
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
            at_post:{
                required: true
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
                url: "/address/create",
                async:true,
                data: $("#add_new_address").serializeArray(),
                error: function(data, textStatus, xhr) {

                },
                success: function(data, textStatus, xhr) {
                    if(xhr.status==200){
                        $('#add_new_address')[0].reset();
                        $('#address-list').append(data);
                    }else if(xhr.status==201){
                        $('#add_new_address')[0].reset();
                        alert('Maximum 3 address allowed');
                    }
                },
                type: 'POST'
            });
            return false;
        }
    });
});