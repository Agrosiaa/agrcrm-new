<html>
<head>
    <style>

    </style>
</head>
<body style="font-family:tahoma;">
  <div>
        <table width="100%">
          <tr>
            <td width="80%">
              <table style="font-size:10px;font-weight:bold;padding-top:5px;padding-bottom:5px;">
                <tr>
                <td>
                  <img src="https://{{env('DOMAIN_NAME')}}/assets/pages/img/login/agrosia.png" alt="logo" width="1500%" height="400%" class="logo-default">
                </td>
                </tr>
                <tr>
                  <td style="font-size:8px;font-weight:bold;"> Tax Invoice / Bill of Supply / Cash Memo</td>
                </tr>
              </table>
              <table>
                <tr>
                  <td style="font-size:8px;font-weight:bold;">&nbsp;Sold By</td>
                </tr>
                <tr>
                  <td style="font-size:6px;">
                    {{ucwords($seller->company)}}.
                  </td>
                </tr>
                <tr>
                  <td style="font-size:6px;">
                    {{ucwords($sellerAddress->shop_no_office_no_survey_no)}}, {{ucwords($sellerAddress->name_of_premise_building_village)}},
                  </td>
                </tr>
                <tr>
                  <td style="font-size:6px;">
                    {{ucwords($sellerAddress->area_locality_wadi)}}, {{ucwords($sellerAddress->road_street_lane)}}, {{ucwords($sellerAddress->at_post)}} ,{{ucwords($sellerAddress->taluka)}},
                  </td>
                </tr>
                <tr>
                  <td style="font-size:6px;">
                    {{ucwords($sellerAddress->district)}},{{ucwords($sellerAddress->state)}},{{ucwords($sellerAddress->pincode)}}
                  </td>
                </tr>
              </table>
            </td>
            <td>
              @if($paymentMethod['slug'] == "citrus")
               <img src="https://{{env('DOMAIN_NAME')}}/assets/custom/common/images/paid.jpg" width="100" height="100">
              @else
               <img src="https://{{env('DOMAIN_NAME')}}/assets/custom/common/images/cod.jpg" width="100" height="100">
              @endif
            </td>
          </tr>
        </table>

        <table style="font-size:7px;padding-top:10px;padding-bottom:10px;" width="100%">
          <tr>
            <td>
              <table>
                <tr>
                  <td style="font-size:8px;font-weight:bold;">Billing Address</td>
                </tr>
                <tr>
                  <td style="font-size:6px;">{{ucwords($customerAddress->full_name)}}<br/>
                        {{ucwords($customerAddress->flat_door_block_house_no)}}, {{ucwords($customerAddress->name_of_premise_building_village)}}, {{ucwords($customerAddress->area_locality_wadi)}},<br/>
                        {{ucwords($customerAddress->road_street_lane)}}, {{ucwords($customerAddress->at_post)}},    <br/>
                        {{ucwords($customerAddress->taluka)}}, {{ucwords($customerAddress->district)}}, {{ucwords($customerAddress->state)}},{{$customerAddress->pincode}}.
                  </td>
                </tr>
              </table>
            </td>
            <td>
              <table>
                <tr>
                  <td style="font-size:8px;font-weight:bold;">Shipping Address</td>
                </tr>
                <tr>
                  <td style="font-size:6px;">{{ucwords($customerAddress->full_name)}}<br/>
                    {{ucwords($customerAddress->flat_door_block_house_no)}}, {{ucwords($customerAddress->name_of_premise_building_village)}}, {{ucwords($customerAddress->area_locality_wadi)}},<br/>
                        {{ucwords($customerAddress->road_street_lane)}}, {{ucwords($customerAddress->at_post)}},    <br/>
                        {{ucwords($customerAddress->taluka)}}, {{ucwords($customerAddress->district)}}, {{ucwords($customerAddress->state)}},{{$customerAddress->pincode}}.
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
        <table style="font-size:8px;font-weight:bold;padding-bottom:5px;" width="100%">
          <tr>
            <td>Order No. : {{$orderNo}}</td>
            @if($paymentMethod['slug'] == "cod")
              <td style="text-align: right">Invoice No : AGRCR{{$orderNo}}</td>
            @else
              <td style="text-align: right">Invoice No : AGR{{$orderNo}}</td>
            @endif
          </tr>
          <tr>
              <td>Order Date : {{$orderDateChangeFormat}}</td>
              <td style="text-align: right">Invoice Date : {{$invoiceCreatedDate}}</td>
          </tr>
        </table>

        <table border="1" cellpadding="3" width="100%">
          <tr style="font-size:9px;font-weight:bold;">
            <td width="25%" style="text-align: center">Product Details</td>
            <td width="10%" style="text-align:center;">HSN Code</td>
            <td width="10%" style="text-align:center;">GST Rate (%)</td>
            <td width="15%" style="text-align:center;">Display price <i class="fa fa-inr"></i></td>
            <td width="10%" style="text-align:center;">Discount (%)</td>
            <td width="20%" style="text-align:center;">Final Unit Price</td>
            <td width="10%" style="text-align:center;">Qty</td>
          </tr>

          <tr style="font-size:6px;">
            <td>{{ucwords($product->product_name)}}<br/>
              @if($order['is_configurable'] == true)
                ({{$order['length']}} mtr * {{$order['width']}} mtr )
              @endif
                {{--SKU: {{$product->item_based_sku}}<br/>--}}
                {{--Brand Name: {{strtoupper($brand->name)}}--}}
            </td>
            <td style="text-align:center;">{{$hsn_code}}</td>
            <td style="text-align:right;">{{$order->tax_rate}}</td>
            <td style="text-align:right;">{{number_format($unitPrice,"2")}}</td>
            <td style="text-align:center;">{{$order->discount}}</td>
            <td style="text-align:right;">{{number_format($finalUnitPrice,"2")}}</td>
            <td style="text-align:right;">{{$order->quantity}}</td>
          </tr>
         {{-- <tr style="font-size:6px;text-align: right">
            <td colspan="6">Discount %</td>
            <td>{!! $order['discount'] !!}%</td>
          </tr>
          <tr style="font-size:6px;text-align: right">
            <td colspan="6">Discounted Amount</td>
            <td>{!! $discountAmount !!}</td>
          </tr>--}}
          <tr style="font-size:6px;text-align: right">
            <td colspan="6">Sub Total</td>
            <td>{!! $newsubtotal!!} </td>
          </tr>
            @if($tax_igst_applied == true)
              <tr style="font-size:6px;text-align: right">
                <td colspan="6">IGST ({!! $tax_rate !!}%) : </td>
                <td>{!! $gst !!}</td>
              </tr>
            @else
              <tr style="font-size:6px;text-align: right">
                <td colspan="6">CGST ({!! $tax_rate !!}%) : </td>
                <td>{!! $gst !!}</td>
              </tr>
              <tr style="font-size:6px; text-align: right">
                <td colspan="6">SGST ({!! $tax_rate !!}%) : </td>
                <td>{!! $gst !!}</td>
              </tr>
            @endif
            <tr style="font-size:6px; text-align: right">
              <td colspan="6">Invoice Amount</td>
              <td>{!! number_format($finaltotal,"2") !!}</td>
            </tr>
        </table>
        <table border="1" width="100%" cellpadding="5">
          <tr>
            <td style="font-size:7px;"><b>Amount in words :</b> {!! $amountInWords !!} </td>
          </tr>
        </table>
        @if($userRole != 'customer')
        <table border="1" width="100%" cellpadding="5">
          <tr style="font-size:9px;font-weight:bold;">
            <td style="text-align:right; padding-top: 100px;"><span><br><br><br>Authorized Signatory</span></td>
          </tr>
        </table>
        @endif
        {{--<table style="text-align:right;padding-top:5px;">
          <tr style="font-weight:bold;font-size:8px;">
            --}}{{--<td width="55%"></td>
            <td>This is a computer generated invoice</td>--}}{{--
          </tr>
        </table>--}}
        <table style="padding-top: 10px;" width="98%">
          <tr style="font-size:6px;">
            <td>GST Declaration :- "I/We certify that our registration certificate under the <b>GST Act, 2017</b> is in force on the date on which the supply of goods specified in this tax invoice is made
              by me/us & the transaction of supply covered by this Tax invoice had been effected by me/us & it shall be accounted for in the turnover of supplies while filling
              of return & the due tax if any payable on the supplies has been paid or shall be paid. Further certified that the particulars given above are true and correct & the
              amount indicated represents the prices actually charged and that there is no flow additional consideration directly or indirectly from the buyer.Interest @15% p.a.
              charged on all outstanding more than one month after invoice has been rendered"
            </td>
          </tr>
        </table>
        <br><br>
        @if(count($orderQuantityInfo) > 0)
          <table border="1" cellpadding="3" style="padding-bottom: 6px" width="100%">
            <tr style="font-size:9px;font-weight:bold;text-align:center;">
              <th>Quantity<span style="width: 30px"></span></th>
              <th>Batch Number</th>
              <th>Lot Number</th>
              <th>MFG Date</th>
              <th>Expiry Date</th>
            </tr>
            @for($iterator = 0 ; $iterator < count($orderQuantityInfo) ; $iterator++)
              <tr style="font-size:6px;text-align:center;">
                <td>{!! $iterator+1 !!}</td>
                <td>{!! $orderQuantityInfo[$iterator]['batch_number']  !!}</td>
                <td>{!! $orderQuantityInfo[$iterator]['lot_number']  !!}</td>
                <td>{!! $orderQuantityInfo[$iterator]['mfg_date']  !!}</td>
                <td>{!! $orderQuantityInfo[$iterator]['expiry_date']  !!}</td>
              </tr>
            @endfor
          </table>
        @endif

        <table style="padding-top:5px;" width="100%">
          <tr style="font-weight:bold;font-size:7px;">
            <td>Vendor License / Certificate Nos.</td>
          </tr>
        </table>
        <table style="padding-top: 5px;" width="100%">
          <tr style="font-size:7px;">
            <td>
              <table>
                <tr>
                  <td width="30%">GSTIN</td>
                  @if($seller['gstin'] != null)
                    <td>{{$seller['gstin']}}</td>
                  @else
                    <td><b>-</b></td>
                  @endif
                </tr>
                <tr>
                  <td>Other License</td>
                  @if($license['otherLicense'] != null)
                  <td>{{$license['otherLicense']}}</td>
                  @else
                    <td><b>-</b></td>
                  @endif
                </tr>
                <tr>
                  <td>PAN</td>
                  @if($sellerBankDetails['pan_number'] != null)
                    <td>{{$sellerBankDetails['pan_number']}}</td>
                  @else
                    <td><b>-</b></td>
                  @endif
                </tr>
                <tr>
                  <td>CIN</td>
                  @if($sellerBankDetails['company_identification_number'] != null)
                    <td>{{$sellerBankDetails['company_identification_number']}}</td>
                  @else
                    <td><b>-</b></td>
                  @endif
                </tr>
              </table>
            </td>
            <td>
              <table>
                <tr>
                  <td width="40%">Seed License</td>
                  @if($license['seedLicense'] != null)
                    <td>{{$license['seedLicense']}}</td>
                  @else
                    <td><b>-</b></td>
                  @endif
                </tr>
                <tr>
                  <td>Pesticide License</td>
                  @if($license['pesticidesLicense'] != null)
                    <td>{{$license['pesticidesLicense']}}</td>
                  @else
                    <td><b>-</b></td>
                  @endif
                </tr>
                <tr>
                  <td>Fertilizer License</td>
                  @if($license['fertilizerLicense'] != null)
                    <td>{{$license['fertilizerLicense']}}</td>
                  @else
                    <td><b>-</b></td>
                  @endif
                </tr>
              </table>
            </td>
        </tr>
        </table>
        <br/>
        <hr style="width: 100%"/>
        <br/>
          <table style="font-weight:bold;font-size:9px;" width="100%">
            <tr style="text-align: center">
              <td>Agrosiaa.com Declaration Letter
              </td>
            </tr>
            <tr style="text-align: center">
              <td>To Whomesoever It May concern</td>
            </tr>
          </table>
          <table style="padding-top: 7px;" width="100%">
            <tr style="font-size:8px;">
              <td style="color: grey">
                I, {{ucwords($customerAddress->full_name)}}, hereby confirm that said above goods are being purchased for my internal or personal purpose and not for re-sale.i have read & understand and I am legally bound by terms and conditions of sale available at agrosiaa.com or upon request.
              </td>
            </tr>
          </table>
        <table style="padding-bottom:5px;padding-top:5px; " width="100%">
          <tr>
            <td style="font-weight:bold;font-size:8px;">To return an item,visit http://www.agrosiaa.com/return-policy
                <br/>For more information on your orders, visit http://www.agrosiaa.com/your-account
            </td>
          </tr>
        </table>
          <table style="padding-top:5px;text-align: center" width="100%;">
            <tr style="font-size: 9px;font-weight:bold;">
              <td>Thank you for buying on agrosiaa.com.</td>
            </tr>
          </table>

      <div style="page-break-before: always;">
      </div>

      @if($userRole != 'customer')
      <table>
        <tr>
          <td>
            <img src="https://{{env('DOMAIN_NAME')}}/assets/custom/common/images/cut.jpg" width="1000" height="15">
          </td>
        </tr>
      </table>
      @endif
  </div>
  @if($userRole != 'customer')
  <table style="padding-left:220px;" width="100%">
    <tr style="font-weight:bold;font-size:8px;">
      <td>Shipping Label</td>
    </tr>
  </table>
  <table width="100%">
    <tr>
      <td width="80%" style="font-weight:bold;font-size:8px;">Date {{$invoiceCreatedDate}}</td>
      <!--<td style="text-align:right;font-weight:bold;font-size:8px;">COD Collectible Amount: Rs.{{$order->discounted_price+$order->delivery_amount}}</td>-->
    </tr>
  </table>
  <table style="padding-top:10px;" width="100%">
    <tr>
      <td  width="40%"><p style="font-size:7px;"><span style="font-weight:bold;font-size:8px;">Ship To</span><br/>
        {{ucwords($customerAddress->full_name)}}.<br/>
            {{ucwords($customerAddress->flat_door_block_house_no)}}, {{ucwords($customerAddress->name_of_premise_building_village)}}, {{ucwords($customerAddress->area_locality_wadi)}},<br/>
            {{ucwords($customerAddress->road_street_lane)}}, {{ucwords($customerAddress->at_post)}},    <br/>
            {{ucwords($customerAddress->taluka)}}, {{ucwords($customerAddress->district)}}, {{ucwords($customerAddress->state)}},{{$customerAddress->pincode}}.</p>
      </td>
      <td width="40%"><p style="font-size:7px;"><span style="font-weight:bold;font-size:8px;">Delivery Address</span><br/>
        {{ucwords($customerAddress->full_name)}}.<br/>
            {{ucwords($customerAddress->flat_door_block_house_no)}}, {{ucwords($customerAddress->name_of_premise_building_village)}}, {{ucwords($customerAddress->area_locality_wadi)}},<br/>
            {{ucwords($customerAddress->road_street_lane)}}, {{ucwords($customerAddress->at_post)}},    <br/>
            {{ucwords($customerAddress->taluka)}}, {{ucwords($customerAddress->district)}}, {{ucwords($customerAddress->state)}},{{$customerAddress->pincode}}.</p>
      </td>
      <td width="20%" style="text-align:right;font-weight:bold;font-size:10px;">
        <table border="1" cellpadding="3">
          <tr>
            <td style="text-align:center;">{{$displayName}} in INR</td>
          </tr>
          <tr>
            <td style="text-align:center;">{{$paymentType}} <br/> {{$finaltotal}}/-</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <br/><br/>
  <table width="100%">
  <tr style="font-size:8px;font-weight:bold;">
    <td>Order ID: AGR{{$orderNo}}<br/>
       Order Date: {{$orderDateChangeFormat}}<br/>
       Delivery Type: {{$order['DeliveryMethod']['name']}}
     </td>
  </tr>
  </table>

  <!--<table style="padding-bottom:5px;padding-top:5px;font-weight:bold;font-size:7px;">
    <tr style="font-size:8px;font-weight:bold;">
      <td>Order ID: AGR000000001</td>
    </tr>
  </table>-->
  <!--<table style="padding-bottom:5px;">
    <tr style="font-size:7px;font-weight:bold;">
      <td>Thank you for buying from {{ucwords($seller->company)}}.</td>
    </tr>
  </table>-->

  <table border="1" cellpadding="3" width="100%">
    <tr style="font-size:8px;font-weight:bold;">
      <td width="40%">Product Details</td>
      <td width="15%" style="text-align:center;">Quantity</td>
      <td width="15%" style="text-align:right;">Unit price (INR)</td>
      <td width="30%" style="text-align:right;">Total (INR)</td>
    </tr>
    <tr style="font-size:6px;">
      <td>{{ucwords($product['product_name'])}}<br/>
          @if($order['is_configurable'] == true)
           ({{$order['length']}} mtr * {{$order['width']}} mtr )
          @endif
          SKU: {{$product['item_based_sku']}}<br/>
          Brand Name: {{strtoupper($brand['name'])}}

      </td>
      <td style="text-align:center;">{{$order['quantity']}}</td>
      @if($order['is_configurable'] == true)
        <td style="text-align:right;">{{$order['discounted_price'] * ($order['length'] * $order['width'])}}</td>
        <td style="text-align:right;">Subtotal: {{($order['discounted_price'] * ($order['length'] * $order['width'])) * $order['quantity']}}<br/>
      @else
        <td style="text-align:right;">{{$order['discounted_price']}}</td>
        <td style="text-align:right;">Subtotal: {{$order['discounted_price'] * $order['quantity']}}<br/>
      @endif
        Shipping: {{$order['delivery_amount']}}<br/>

          @if($couponDiscount!=null)
          Coupon Discount: {{$couponDiscount}}<br/>
          @endif
          Total:{{$finaltotal}}
      </td>
    </tr>
  </table>
  <table style="padding-top:5px;" width="100%">
    <tr>
      <td style="text-align:right;font-weight:bold;font-size:8px;">{{$displayAmountMethod}} (INR): {{$finaltotal}}</td>
    </tr>
  </table>
  <table style="font-weight:bold;font-size:8px;" width="100%">
    <tr style="text-align: center">
      <td>Agrosiaa.com Declaration Letter
      </td>
    </tr>
    <tr style="text-align: center">
      <td>To Whomesoever It May concern</td>
    </tr>
  </table>
  <table width="100%">
    <tr style="font-size:7px;">
      <td>
        I, {{ucwords($customerAddress->full_name)}}, hearby confirm that said above goods are being purchased for my internal or personal purpose and not for re-sale.
        I have read & understand and I am legally bound by terms and conditions of sale available at agrosiaa.com or upon request.
      </td>
    </tr>
  </table>
  <table style="text-align:center;padding-top:5px;" width="100%">
    <tr style="font-size:10px;font-weight:bold;">
      <td>Thank you for buying on agrosiaa.com.</td>
    </tr>
  </table>
  @endif
</body>
</html>
