$(document).ready(function(){

    var form = $("#receipt_transaction_details_form");
    var error = $('.alert-danger', form);
    var success = $('.alert-success', form);
    form.validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block help-block-error', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        ignore: "",
        rules:{
            transaction_number:{
                required: true,
            },
            amount:{
                required: true,
                digits: true
            },
            deposit_date:{
                required: true,
            }
        },
        messages:{
            transaction_number: {
                required: "This field is required."
            },
            amount: {
                required: "Please Enter amount.",
                digits: "Only numbers are allowed."
            },
            deposit_date:{
                required: "Please enter deposite date"
            }
        },
        invalidHandler:function(){
            success.hide();
            error.show();
        },
        highlight: function (element) { // hightlight error inputs
            $(element)
                .closest('.form-group').addClass('has-error');
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
        submitHandler:function(form){
            success.show();
            error.hide();
            form.submit();
        }

    });

    var form1 = $("#payment_transaction_details_form");
    var error1 = $('.alert-danger', form1);
    var success1  = $('.alert-success', form1);
    form1.validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block help-block-error', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        ignore: "",
        rules:{
            transaction_number:{
                required: true,
            },
            amount:{
                required: true,
                digits: true
            },
            deposit_date:{
                required: true,
            }
        },
        messages:{
            transaction_number: {
                required: "This field is required."
            },
            amount: {
                required: "Please Enter amount.",
                digits: "Only numbers are allowed."
            },
            deposit_date:{
                required: "Please enter deposite date"
            }
        },
        invalidHandler:function(){
            success1.hide();
            error1.show();
        },
        highlight: function (element) { // hightlight error inputs
            $(element)
                .closest('.form-group').addClass('has-error');
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
        submitHandler:function(form){
            success1.show();
            error1.hide();
            form1.submit();
        }

    });
});
$("#transaction-mode").on('change',function(){
    switch(this.value){
        case 'cash':
            $("#transaction-date").hide();
            $("#deposit-date").show();
            $("#transaction_date_input").rules('remove');
            $("#number").show();
            $("#amount").show();
            $("#number label").text("Slip number:");
            $("#submit").prop('disabled', false);
            break;

        case 'neft':
            $("#transaction-date").show();
            $("#transaction_date_input").rules('add',{
                required: true
            });
            $("#deposit-date").show();
            $("#number").show();
            $("#amount").show();
            $("#number label").text("NEFT number:");
            $("#submit").prop('disabled', false);
            break;

        case 'cheque':
            $("#transaction-date").show();
            $("#transaction_date_input").rules('add',{
                required: true
            });
            $("#deposit-date").show();
            $("#number").show();
            $("#amount").show();
            $("#submit").prop('disabled', false);
            $("#number label").text("Cheque number:");
            break;
        default:
            $("#transaction-date").hide();
            $("#deposit-date").hide();
            $("#transaction_date_input").rules('remove');
            $("#number").hide();
            $("#amount").hide();
            $("#submit").prop('disabled', true);
    }
});

$("#payment-transaction-mode").on('change',function(){
    switch(this.value){
        case 'cash':
            $("#payment-transaction-date").hide();
            $("#payment-deposit-date").show();
            $("#payment_transaction_date_input").rules('remove');
            $("#payment-number").show();
            $("#payment-amount").show();
            $("#payment-number label").text("Slip number:");
            break;

        case 'neft':
            $("#payment-transaction-date").show();
            $("#payment_transaction_date_input").rules('add',{
                required: true
            });
            $("#payment-deposit-date").show();
            $("#payment-number").show();
            $("#payment-amount").show();
            $("#payment-number label").text("NEFT number:");
            break;

        case 'cheque':
            $("#payment-transaction-date").show();
            $("#payment_transaction_date_input").rules('add',{
                required: true
            });
            $("#payment-deposit-date").show();
            $("#payment-number").show();
            $("#payment-amount").show();
            $("#payment-number label").text("Cheque number:");
            break;
        default:
            $("#payment-transaction-date").hide();
            $("#payment-deposit-date").hide();
            $("#payment_transaction_date_input").rules('remove');
            $("#payment-number").hide();
            $("#payment-amount").hide();
    }
});
