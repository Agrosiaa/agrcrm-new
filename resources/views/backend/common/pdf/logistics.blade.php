
<html>
<head>
    <style>

    </style>
</head>
<body style="font-family:tahoma;">
  <table>
    <tr>
      <td style="font-size:28px;">Logistics Manifest</td>
      <td><img src="https://{{env('DOMAIN_NAME')}}/assets/frontend/global/images/logo.png" width="150" height="40"></td>
    </tr>
  </table>
  <table style="padding-top:20px;padding-bottom:10px;">
    <tr>
      <td><span>Generated On:</span> {{$generatedOn}}</td>
    </tr>
  </table>
  <table border="1" cellpadding="3">
      <tr style="font-weight:bold;font-size:10px;text-align:center">
          <td>Sr No.</td>
          <td>Order</td>
          <td>RTS Done On</td>
          <td>Notes</td>
      </tr>
      @for($i = 0,$srNo = 1; $i < count($mOrders); $i++,$srNo++)
      <tr style="font-size:9px;padding-left: 1%">
          <td>{{$srNo}}</td>
          <td>AGR{{$mOrders[$i]['id']}}</td>
          <td>{{$mOrders[$i]['rts']}}</td>
          <td><div style="word-wrap:break-word;">{{$mOrders[$i]['note']}}</div></td>
      </tr>
      @endfor


  </table>
  <table style="padding-top:20px;padding-left:120px;">
    <tr style="font-size:12px;font-weight:bold;">
      <td style="padding-top:25px;padding-left: 20px;">[To Be filled by Shipment Field Executive]</td>
    </tr>
  </table>

  <table style="padding-top:20px;">
      <tr style="font-size:9px;">
          <td width="15%"><span>Pickup In Time :</span></td>
          <td width="40%"><span>_ _ _ _ _ _ _ _ _ _ _ _ _ _ _</span></td>
          <td width="12%"><span>Total Items :</span></td>
          <td width="30%"><span>_ _ _ _ _ _ _ _ _ _ _ _ _ _ _</span></td>
      </tr>

  </table>
  <table style="padding-top:20px;font-size:9px;">
      <tr>
          <td width="15%"><span>Pickup Out Time :</span></td>
          <td width="40%"><span>_ _ _ _ _ _ _ _ _ _ _ _ _ _ _</span></td>
          <td width="12%"><span>Seller Name :</span></td>
          <td width="30%"><span>{{($company_name)}}</span></td>

      </tr>
  </table>
  <table style="padding-top:20px;font-size:9px;">
      <tr>
          <td width="15%"><span>Receiver Name :</span></td>
          <td><span>_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ </span></td>
      </tr>
  </table>
  <table style="padding-top:80px;">
      <tr>


           <td width="50%">
             <table>
               <tr>
                 <td>________________________________</td>
               </tr>
               <tr>
                 <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Receiver Signature and Stamp</td>
               </tr>
             </table>
           </td>
            <td>
              <table>
                <tr>
                  <td>__________________________________</td>
                </tr>
                <tr>
                  <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Seller Authorized Signature</td>
                </tr>
              </table>
           </td>
      </tr>
  </table>
</body>
</html>
