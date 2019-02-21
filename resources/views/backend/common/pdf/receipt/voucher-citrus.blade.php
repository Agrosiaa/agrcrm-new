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
            <span style="font-weight: bolder; font-size: 18px"> Citrus Payment Solutions Pvt. Ltd </span><br>
            5th Floor,Icon Tower,<br>
            Opposite KFC Restaurent,<br>
            Baner Road,Baner,<br>
            Pune 411045
        </td>
        <td>
            <img src="https://{{env('DOMAIN_NAME')}}/assets/global/frontend/img/logo.png" height="40px" width="140px">
        </td>
    </tr>
</table>
<br><br>
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
            <span style="font-weight: bold"> Receipt Voucher Number:</span> {{$documentParameters['receipt_voucher_number']}}
        </td>
    </tr>
</table>
<br><br>
    <table id="invoice-details" style=" margin-top:1%; margin-left:1%; border:1px solid black; height: 20%;">
        <tr width="100%" style="font-weight: 600;border:1px solid black; height: 20%;">
            <td width="22%" style="border:1px solid black; height: 30px;">
                Invoice number
            </td>
            <td width="22%" style="border:1px solid black; height: 30px;">
                Invoice date
            </td>
            <td width="22%" style="border:1px solid black; height: 30px;">
                Invoice amount
            </td>
            <td  style="border:1px solid black; height: 30px; width: 34%">
                Complete timestamp
            </td>
        </tr>

        <tr width="100%" style="border:1px solid black; height: 30px;">
            <td width="22%" style="border:1px solid black; height: 30px;">
                {{$documentParameters['order_id']}}
            </td>
            <td width="22%" style="border:1px solid black; height: 30px;">
                {{$documentParameters['invoice_date']}}
            </td>
            <td width="22%" style="border:1px solid black; height: 30px;">
                {{$documentParameters['invoice_amount']}}
            </td>
            <td width="34%" style="border:1px solid black; height: 30px;">
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
                Transaction date
            </td>
            <td>
                : {{$transactionDetails['transaction_date']}}
            </td>
        </tr>
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
                : {{$transactionDetails['reconciliation_amount']}}
            </td>
        </tr>
        <tr>
            <td>
                Transaction Reference Number
            </td>
            <td>
                : {{$transactionDetails['transaction_reference_number']}}
            </td>
        </tr>
    </table>
</body>
</html>