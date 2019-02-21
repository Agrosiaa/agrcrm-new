<html>
<head>
    <style>
        body{
            font-family: tahoma;
            margin: 5% 15%;
        }
        #invoice-details tr td{
            border:1px solid black;
        }

    </style>
</head>

<body>
<table>
    <tr>
        <td style="width:70%">
            <span style="font-weight: bolder; font-size: 18px">{{ucwords($documentParameters['seller_company'])}}</span><br>
            {{ucwords($documentParameters['seller_company_address']->shop_no_office_no_survey_no)}}, {{ucwords($documentParameters['seller_company_address']->name_of_premise_building_village)}}<br>
            {{ucwords($documentParameters['seller_company_address']->area_locality_wadi)}}, {{ucwords($documentParameters['seller_company_address']->road_street_lane)}} <br>
            {{ucwords($documentParameters['seller_company_address']->at_post)}}, {{ucwords($documentParameters['seller_company_address']->taluka)}},
            {{ucwords($documentParameters['seller_company_address']->district)}}, {{ucwords($documentParameters['seller_company_address']->state)}},{{ucwords($documentParameters['seller_company_address']->pincode)}}
        </td>
        <td>
            <img src="https://{{env('DOMAIN_NAME')}}/assets/global/frontend/img/logo.png" height="40px" width="140px">
        </td>
    </tr>
</table><br><br>
<br>
<table style="margin-top: 20%">
    <tr>
        <td style="text-align: center">
            <h2 style="text-decoration: underline">Receipt Voucher</h2>
        </td>
    </tr>
</table>
<br><br>
<table>
    <tr>
        <td style="text-align: left; font-size: 12px" >
            <span style="font-weight: bold"> Generated On:</span> {{$documentParameters['generated_on']}}<br>
            <span style="font-weight: bold"> Receipt Voucher Number:</span> {{$documentParameters['receipt_voucher_number']}}<br>
            <span style="font-weight: bold"> Receipt Advice Number:</span> {{$documentParameters['receipt_advice_number']}}
        </td>
    </tr>
</table>
<br><br>
<table id="invoice-details" style=" margin-top:1%; margin-left:1%; border:0.4px solid black; height: 20%;">
    <tr width="100%" style="font-weight: bold; border:0.4px solid black;text-align: center; height: 20%;">
        <td width="22%" style="border:0.4px solid black; height: 30px;">
            Invoice number
        </td>
        <td width="22%" style="border:0.4px solid black; height: 30px;">
            Invoice date
        </td>
        <td width="22%" style="border:0.4px solid black; height: 30px;">
            Invoice amount(INR)
        </td>
        <td  style="border:0.4px solid black; height: 30px; width: 34%">
            Complete timestamp
        </td>
    </tr>

    <tr width="100%" style="border:0.4px solid black; height: 30px; text-align: center;">
        <td width="22%" style="border:0.4px solid black; height: 30px;">
            {{$documentParameters['order_id']}}
        </td>
        <td width="22%" style="border:0.4px solid black; height: 30px;">
            {{$documentParameters['invoice_date']}}
        </td>
        <td width="22%" style="border:0.4px solid black; height: 30px;">
            {{$documentParameters['invoice_amount']}}
        </td>
        <td width="34%" style="border:0.4px solid black; height: 30px;">
            {{$documentParameters['complete_timestamp']}}
        </td>
    </tr>
</table>
<br><br><br>
<table>
    <tr>
        <td style="font-weight: bolder">
            Transaction Details
        </td>
    </tr>
</table>
<table id="transaction-details">

    <tr>
        <td>
            Transaction particulars
        </td>
        <td>
            : {{$transactionDetails['transaction_particulars']}}
        </td>
    </tr>
    <tr>
        <td>
            Reconciliation amount(INR)
        </td>
        <td>
            : {{$transactionDetails['reconciled_amount']}}

        </td>
    </tr>
    <tr>
        <td>
            {{$transactionDetails['transaction_number_label']}}
        </td>
        <td>
            : {{$transactionDetails['transaction_number']}}

        </td>
    </tr>
    <tr>
        <td>
            Deposit date
        </td>
        <td>
            : {{$transactionDetails['deposit_date']}}
        </td>
    </tr>
    <tr>
        <td>
            Expected Clearance Date
        </td>
        <td>
            : {{$transactionDetails['transaction_date']}}
        </td>
    </tr>
</table>
</body>
</html>
