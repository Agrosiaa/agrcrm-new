
function getPackingCheckListData(){
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN' : $("#tokenss").val() } });
    $.ajax({
        url: "/order/get-packing-checklist-data",
        async:false,
        error: function(xhr,err) {
        },
        success: function(data, textStatus, xhr) {
            if(xhr.status==200){
                $('#packingSlipData').html(data);
            }

        },
        type: 'POST'
    });
}



function getManifestCheckListData(){
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN' : $("#tokenss").val() } });
    $.ajax({
        url: "/order/get-manifest-checklist-data",
        async:false,
        error: function(xhr,err) {
        },
        success: function(data, textStatus, xhr) {
            if(xhr.status==200){
                $('#manifestData').html(data);
            }

        },
        type: 'POST'
    });
}


//validations for Change information consignment date

var changeInformationFormValidation = function () {
    // basic validation
    var changeInformationValidation = function() {
        var form = $('#shipment_change_information');
        var error = $('.alert-danger', form);
        var success = $('.alert-success', form);
        form.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",  // validate all fields including form hidden input
            messages: {
                consignment_date : {
                    required: "please enter the date in mm-dd-yyyy format",
                    date: 'mm-dd-yyyy'
                }
            },
            rules: {
                consignment_date : {
                    required: true,
                    date: true
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit
                success.hide();
                error.show();
                App.scrollTo(error, -200);
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
                    .closest('.form-group').addClass('has-success').removeClass('has-error');

                //.closest('.form-group').removeClass('has-error'); // set success class to the control group
            },
            submitHandler: function (form) {
                success.show();
                error.hide();
                return true;
            }
        });
    };
    return {
        //main function to initiate the module
        init: function () {
            changeInformationValidation();
        }
    };
}();
jQuery(document).ready(function() {
    changeInformationFormValidation.init();
});


