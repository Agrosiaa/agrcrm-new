<html>
<head>
    <style>

    </style>
</head>
<body style="font-family:tahoma;">
  <table>
    <tr>
      <td><img src="http://{{env('DOMAIN_NAME')}}/assets/frontend/global/images/logo.png" width="150" height="40"></td>
      <td style="font-size:28px;">Packing Checklist</td>
    </tr>
  </table>
  <table style="padding-top:20px;padding-bottom:10px;">
      <tr>
          <td><span>Generated On:</span> {{$date}}</td>
      </tr>
  </table>
  <table width="100%" style="padding-bottom:10px;">
    <tr>
      <td width="10%"><span>Tick on </span></td>
      <td width="3%"><img src="http://{{env('DOMAIN_NAME')}}/assets/custom/common/images/tick.png" width="80" height="50"></td>
      <td width="50%"><span>&nbsp;For Packed Product</span></td>
      <td width="30%"><span>Total Items to be packed : {{$orders->count()}}</span></td>
    </tr>
  </table>


  <table style="width:100%" border="1" cellpadding="3" cellspacing="0">
      <tr style="font-weight:bold;font-size:10px;text-align: center">
          <td width="5%"></td>
          <td width="10%">Sr No.</td>
          <td width="35%">Product</td>
          <td width="30%">Key Specs</td>
          <td width="15%">Quantity</td>
      </tr>
      <?php $index = 1; ?>
      @if(!$orders->isEmpty())
          @foreach($orders as $orders)
          <tr style="font-size:9px;">
              <td><img src="http://{{env('DOMAIN_NAME')}}/assets/custom/common/images/checkbox.png" width="10" height="10"></td>
              <td>{{$index}}</td>
              <td>{{ucwords($orders->product->product_name)}}
                  <div>SKU:{{$orders->product->item_based_sku}}</div>
              </td>
              <td>{{$orders->product->key_specs_1}}
                  <div>{{$orders->product->key_specs_2}}</div>
                  <div>{{$orders->product->key_specs_3}}</div>
              </td>
              <td>{{$orders->quantity}}</td>
          </tr>
              <?php $index++ ?>
          @endforeach
      @endif
  </table>
</body>
</html>
