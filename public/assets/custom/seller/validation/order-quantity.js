var  CreateOrderQuantityInfo = function () {
    var handleCreate = function() {
        var form = $('#saveExpiryDateForm');
        var error = $('.alert-danger', form);
        var success = $('.alert-success', form);
        form.validate({
            ignore: "",
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {

            },
            messages: {

            },

            invalidHandler: function (event, validator) { //display error alert on form submit
                success.hide();
                error.show();
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
                    .closest('.form-group').addClass('has-success');
            },

            submitHandler: function (form) {
                $("button[type='submit']").prop('disabled', true);
                success.show();
                error.hide();
                form.submit();
            }
        });
    }

    return {
        init: function () {
            handleCreate();
        }
    };
}();


$(document).ready(function(){
    CreateOrderQuantityInfo.init();
    $(".order-info").each(function(){
        $(this).rules("add",{
            required: true
        })
    });
});

var logisticFormValidation = function () {

    // basic validation
    var logistichandleValidation = function() {
        // for more info visit the official plugin documentation:
        // http://docs.jquery.com/Plugins/Validation
        var form = $('#formData');
        var error = $('.alert-danger', form);
        var success = $('.alert-success', form);
        form.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",  // validate all fields including form hidden input
            messages: {
                trans_id: {
                    required: "Transaction id is required"
                },
                commission: {
                    required: "Commission field is required"
                },
                article_number: {
                    required: "Article No field is required"
                },
                barcode_number: {
                    required: "Barcode No field is required"
                },
                document_number: {
                    required: "Document No field is required"
                },
                payment_docket_number: {
                    required: "Payment Docket No field is required"
                },
                check_number: {
                    required: "Cheque No field is required"
                },
                payment_date: {
                    required: "Payment Date field is required"
                },
                collection_office: {
                    required: "Collection Office field is required"
                },
                collection_date: {
                    required: "Collection Date field is required"
                },
                logistic_number: {
                    required: "Logistic No field is required"
                },
                logistic_date: {
                    required: "Logistic date field is required"
                },
                note_name: {
                    required: "Note name field is required"
                },
                delivery_type: {
                    required: "Delivery Type field is required"
                },
                delivery_done_by : {
                    required: "Delivery done by field is required"
                },
                lr_number : {
                    required: "LR No field is required"
                },
                lr_date : {
                    required: "LR date field is required"
                },
                lr_amount : {
                    required: "LR amount field is required"
                },
                payment_mode : {
                    required: "Payment mode field is required"
                },
                bank_name : {
                    required: "Bank Name by field is required"
                },
                deposit_date : {
                    required: "Deposit date by field is required"
                },
                deposit_note : {
                    required: "Deposit note by field is required"
                },
                invoice_no :{
                  required: "Invoice Number field is required"
                },
                invoice_date :{
                  required: "Invoice date field is required"
                },
                invoice_amount :{
                    required: "Invoice amount field is required"
                }
            },
            rules: {
                trans_id: {
                    required: true
                },
                commission: {
                    required: true
                },
                article_number: {
                    required: true
                },
                barcode_number: {
                    required: true
                },
                document_number: {
                    required: true
                },
                payment_docket_number: {
                    required: true
                },
                check_number: {
                    required: true
                },
                payment_date: {
                    required: true
                },
                collection_office: {
                    required: true
                },
                logistic_number: {
                    required: true
                },
                logistic_date: {
                    required: true
                },
                note_name: {
                    required: true
                },
                delivery_type :  {
                    required: true
                },
                delivery_done_by :  {
                    required: true
                },
                lr_number :  {
                    required: true
                },
                lr_date :  {
                    required: true
                },
                lr_amount :  {
                    required: true
                },
                payment_mode :  {
                    required: true
                },
                bank_name :  {
                    required: true
                },
                deposit_date :  {
                    required: true
                },
                deposit_note : {
                    required: true
                },
                invoice_no :{
                    required: true
                },
                invoice_date :{
                    required: true
                },
                invoice_amount :{
                  required: true
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
    }

    // Validation for India Post Logistic Information Edit form
    var indiaPostLogisticInformationValidation = function() {
        var form = $('#editIndiaPostForm');
        var error = $('.alert-danger', form);
        var success = $('.alert-success', form);
        form.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",  // validate all fields including form hidden input
            messages: {
                trans_id: {
                    required: "Transaction id is required"
                },
                commission: {
                    required: "Commission field is required"
                },
                article_number: {
                    required: "Article No field is required"
                },
                barcode_number: {
                    required: "Barcode No field is required"
                },
                document_number: {
                    required: "Document No field is required"
                },
                payment_docket_number: {
                    required: "Payment Docket No field is required"
                },
                check_number: {
                    required: "Cheque No field is required"
                },
                payment_date: {
                    required: "Payment Date field is required"
                },
                collection_office: {
                    required: "Collection Office field is required"
                },
                collection_date: {
                    required: "Collection Date field is required"
                },
                logistic_number: {
                    required: "Logistic No field is required"
                },
                logistic_date: {
                    required: "Logistic date field is required"
                },
                note_name: {
                    required: "Note name field is required"
                },
                logistic_invoice_amount :{
                    required: "logistic invoice amount field is required"
                },
                invoice_payment_details:{
                    required: "invoice payment detail field is required"
                },
                actual_logistic_cost:{
                    required: "actual logistic cost field is required"
                },
                article_type:{
                    required: "article type field is required"
                }
            }
            ,
            rules: {
                trans_id: {
                    required: true
                },
                commission: {
                    required: true
                },
                article_number: {
                    required: true
                },
                barcode_number: {
                    required: true
                },
                document_number: {
                    required: true
                },
                payment_docket_number: {
                    required: true
                },
                check_number: {
                    required: true
                },
                payment_date: {
                    required: true
                },
                collection_office: {
                    required: true
                },
                logistic_number: {
                    required: true
                },
                logistic_date: {
                    required: true
                },
                note_name: {
                    required: true
                },
                logistic_invoice_amount :{
                    required: true
                },
                invoice_payment_details:{
                    required: true
                },
                actual_logistic_cost:{
                    required: true
                },
                article_type:{
                    required: true
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
                if($('#tab_6').find('div.has-error').length != 0){
                    $('#tab_6_a').css('color', 'red');
                }

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
    }
    //end
    var logisticInformationValidation = function(){
        var form1 = $('#editFormData');
        var error = $('.alert-danger', form1);
        var success = $('.alert-success', form1);
        form1.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",  // validate all fields including form hidden input
            messages: {
                payment_mode : {
                    required:"this field is required"
                },
                bank_name :{
                    required:"this field is required"
                },
                deposit_note :{
                    required:"this field is required"
                },
                deposit_date :{
                    required:"this field is required"
                }
            },
            rules: {
                payment_mode : {
                    required: true
                },
                bank_name :{
                    required:true
                },
                deposit_note:{
                    required:true
                },
                deposit_date:{
                    required:true
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
                if($('#tab_6').find('div.has-error').length != 0){
                    $('#tab_6_a').css('color', 'red');
                }
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
            submitHandler: function (form1) {
                success.show();
                error.hide();
                return true;
            }
        });
    }

    var changeInformationValidation = function() {
        // for more info visit the official plugin documentation:
        // http://docs.jquery.com/Plugins/Validation
        var form1 = $('#change_information');
        var error = $('.alert-danger', form1);
        var success = $('.alert-success', form1);
        form1.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",  // validate all fields including form hidden input
            messages: {
                consignment_date : {
                    required:"please enter the date in mm-dd-yyyy format",
                    date:'mm-dd-yyyy'
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
            submitHandler: function (form1) {
                success.show();
                error.hide();
                return true;
            }
        });
    }

    // RTV return
    var RtvReturnValidation = function() {
        // for more info visit the official plugin documentation:
        // http://docs.jquery.com/Plugins/Validation
        var form1 = $('#create-return-validation');
        var error = $('.alert-danger', form1);
        var success = $('.alert-success', form1);
        form1.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",  // validate all fields including form hidden input
            messages: {
                rma_reason : {
                    required:"Please select the Reason",
                }
            },
            rules: {
                rma_reason : {
                    required: true,
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
            submitHandler: function (form1) {
                success.show();
                error.hide();
                return true;
            }
        });
    }
    return {
        //main function to initiate the module
        init: function () {
            logistichandleValidation();
            changeInformationValidation();
            logisticInformationValidation();
            indiaPostLogisticInformationValidation();
            RtvReturnValidation();
        }
    };
}();

jQuery(document).ready(function() {
    logisticFormValidation.init();

});