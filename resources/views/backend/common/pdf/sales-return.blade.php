<html>
<body style="font-family:tahoma;">
@if($user->role->slug != "shipmentadmin" && $user->role->slug != "shipmentpartner")
<table>
    <tr>
        <td width="95%">
            <table>
                <tr>
                    <td width="100%">
                        <table style="font-size:10px;font-weight:bold;">
                            <tr>
                                <td>Sales Return for Order Id : AGR{{$order_id}} </td>
                            </tr>
                            <tr>
                                <td>RMA ID : AGR{{$order_id}}R </td>
                            </tr>
                            <tr>
                                <td>{{$date}} </td>
                            </tr>
                        </table>

                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td width="85%">
            <table style="font-size:7px;padding-top:10px;padding-bottom:10px;">
                <tr>
                    <td>
                        <table>
                            <tr>
                                <td style="font-size:8px;font-weight:bold;">Bill To : </td>
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
                                <td style="font-size:8px;font-weight:bold;">Ship To : </td>
                            </tr>
                            <tr>
                                <td style="font-size:6px;">{{ucwords($order['seller']->company)}}.<br/>
                                    {{ucwords($sellerAddress->shop_no_office_no_survey_no)}}, {{ucwords($sellerAddress->name_of_premise_building_village)}},<br/>
                                    {{ucwords($sellerAddress->area_locality_wadi)}}, {{ucwords($sellerAddress->road_street_lane)}}, {{ucwords($sellerAddress->at_post)}} ,{{ucwords($sellerAddress->taluka)}},<br/>
                                    {{ucwords($sellerAddress->district)}},{{ucwords($sellerAddress->state)}},{{ucwords($sellerAddress->pincode)}}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <table style="font-size:8px;font-weight:bold;padding-bottom:5px;">
                <tr>
                    <td  width="60%">Nature of Transaction : Sales Return</td>
                    @if($cod_payment_flag == true)
                        <td style="text-align:right;">Invoice number : AGRCR{{$order_id}}</td>
                    @else
                        <td style="text-align:right;">Invoice number : AGR{{$order_id}}</td>
                    @endif
                </tr>
            </table>
            <table border="1" width="110%" cellpadding="3">
                <tr style="font-size:9px;font-weight:bold;margin-left:100px;">
                    <td width="25%" style="text-align: center">Product Details</td>
                    <td width="10%" style="text-align:center;">HSN Code</td>
                    <td width="10%" style="text-align:center;">GST Rate (%)</td>
                    <td width="15%" style="text-align:center;">Display price <i class="fa fa-inr"></i></td>
                    <td width="10%" style="text-align:center;">Discount (%)</td>
                    <td width="20%" style="text-align:center;">Final Unit Price</td>
                    <td width="10%" style="text-align:center;">Qty</td>
                </tr>
                <tr style="font-size:6px;">
                    <td>{{ucwords($order['product']['product_name'])}}<br/>
                        SKU: {{$order['product']['item_based_sku']}}<br/>
                        Brand Name: {{ucwords($brand['name'])}}
                    </td>
                    <td style="text-align:right;">{!! $hsn_code !!}</td>
                    <td style="text-align:right;">{{$order->tax_rate}}</td>
                    <td style="text-align:right;">{{number_format($unitPrice,"2")}}</td>
                    <td style="text-align:right;">{{$orderInfo->discount}}</td>
                    <td style="text-align:right;">{{number_format($finalUnitPrice,"2")}}</td>
                    <td style="text-align:center;">{{$orderRma['return_quantity']}}</td>
                </tr>
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
                    <td colspan="6">Gross Total (INR)</td>
                    <td>{!! number_format($finaltotal,"2") !!}</td>
                </tr>
            </table>
            <table border="1" width="110%" cellpadding="5">
                <tr>
                    <td style="font-size:7px;"><b>Amount in words :</b> {!! $amountInWords !!} </td>
                </tr>
            </table>
            <table border="1" width="110%" cellpadding="5">
                <tr style="font-size:9px;font-weight:bold;">
                    <td style="text-align:right; padding-top: 100px;"><span><br><br><br>Authorized Signatory</span></td>
                </tr>
            </table>
            <table  style="text-align:center;padding-top:5px;">
                <tr style="font-weight:bold;font-size:8px;">
                    <td width="30%"></td>
                    <td>This is a computer generated invoice</td>
                </tr>
            </table>
            <table>
                <tr style="font-weight:bold;font-size:7px;">
                    <td>Declaration</td>
                </tr>
            </table>
            <table>
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
            <table style="padding-top:5px;">
                <tr style="font-weight:bold;font-size:7px;">
                    <td>Vendor License / Certificate Nos.</td>
                </tr>
            </table>
            <table>
                <tr style="font-size:7px;">
                    <td width="3%">1.</td>
                    <td width="40%">GSTIN</td>
                    <td>{{$order['seller']->gstin}}</td>
                </tr>
                <tr style="font-size:7px;">
                    <td>2.</td>
                    <td>Seed License</td>
                    <td>{{$license['seedLicense']}}</td>
                </tr>
                <tr style="font-size:7px;">
                    <td>3.</td>
                    <td>Pesticide License</td>
                    <td>{{$license['pesticidesLicense']}}</td>
                </tr>
                <tr style="font-size:7px;">
                    <td>4.</td>
                    <td>Fertilizer License</td>
                    <td>{{$license['fertilizerLicense']}}</td>
                </tr>
                <tr style="font-size:7px;">
                    <td>5.</td>
                    <td>Other License</td>
                    <td>{{$license['otherLicense']}}</td>
                </tr>
                <tr style="font-size:7px;">
                    <td>6.</td>
                    <td>PAN</td>
                    <td>{{$sellerBankDetails->pan_number}}</td>
                </tr>
                <tr style="font-size:7px;">
                    <td>7.</td>
                    <td>CIN</td>
                    <td>{{$sellerBankDetails->company_identification_number}}</td>
                </tr>
            </table>
        </td>
        <td width="5%"></td>

    </tr>
</table>
<div style="page-break-before: always;">
</div>
@endif
@if($user->role->slug == "superadmin" || $user->role->slug == "shipmentadmin" || $user->role->slug == "shipmentpartner")
    <table width="100%">
    <tr>
        <td width="50%">
            <table style="padding-bottom:20px;">
                <tr style="font-weight:bold;font-size:11px;">
                    <td>Order# AGR{{$structuredOrderId}}<br/>RMA# AGR{{$structuredOrderId}}R</td>
                </tr>
            </table>
        </td>
        <td style="font-weight:bold;text-align:right;font-size:11px;">Pickup Date:{{$rmaInfo['pick_up_date']}}</td>
    </tr>
    <tr>
        <td width="50%">
            <table>
                <tr>
                    <td width="50%" style="font-weight:bold;font-size:11px;">Shipped To:</td>
                </tr>
                <tr style="font-weight:bold;font-size:9px;">
                    <td width="50%">{{$rmaInfo['sellerInfo']['first_name']}}{{$rmaInfo['sellerInfo']['last_name']}}<br/>
                        {{$sellerAddress['shop_no_office_no_survey_no']}}{{$sellerAddress['name_of_premise_building_village']}}<br/>
                        {{$sellerAddress['road_street_lane']}} <br/>
                        {{$sellerAddress['area_locality_wadi']}} <br/>
                        {{$sellerAddress['at_post']}}<br/>
                        {{$sellerAddress['taluka']}}{{$sellerAddress['district']}}{{$sellerAddress['district']}}{{$sellerAddress['pincode']}}
                    </td>
                </tr>
            </table>
        </td>

        <td width="50%">
            <table>
                <tr>
                    <td width="50%" style="font-weight:bold;font-size:11px;">Shipped From:</td>
                </tr>
                <tr style="font-weight:bold;font-size:9px;">
                    <td width="50%">{{$rmaInfo['pickupAddress']->full_name}}<br/>
                        {{$rmaInfo['pickupAddress']->flat_door_block_house_no}}, {{$rmaInfo['pickupAddress']->name_of_premise_building_village}}<br/>
                        {{$rmaInfo['pickupAddress']->road_street_lane}}<br/>
                        {{$rmaInfo['pickupAddress']->area_locality_wadi}}<br/>
                        {{$rmaInfo['pickupAddress']->at_post}}<br/>
                        {{$rmaInfo['pickupAddress']->taluka}}  {{$rmaInfo['pickupAddress']->district}} {{$rmaInfo['pickupAddress']->state}}  {{$rmaInfo['pickupAddress']->pincode}}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table style="padding-top:5px;padding-bottom:5px;">
    <tr style="font-weight:bold;font-size:11px;">
        <td width="50%">&nbsp;GSTIN# {{$rmaInfo['gstin']}}</td>
    </tr>
</table>
<table>
    <tr>
        <td width="95%">
            <table border="1" cellpadding="3">
                <tr style="font-weight:bold;font-size:11px;">
                    <td width="40%">Product Name</td>
                    <td width="30%">Quantity</td>
                    <td width="30%">Price (INR)</td>
                </tr>
                <tr style="font-size:9px;">
                    <td>{{$rmaInfo['product_name']}}
                    </td>
                    <td>{{$rmaInfo['return_quantity']}}</td>
                    @if($orderInfo['is_configurable'] == true)
                        <td>{{($orderInfo['discounted_price'] * ($orderInfo['length'] * $orderInfo['width'])) * $rmaInfo['return_quantity']}}</td>
                    @else
                        <td>{{$orderInfo['discounted_price'] * $rmaInfo['return_quantity']}}</td>
                    @endif
                </tr>
            </table>
            <table style="padding-top:5px;padding-bottom:5px;">
                <tr>
                    <td width="50%"></td>
                    <td width="50%" style="text-align:right;font-weight:bold;font-size:11px;">Invoice# AGRCR{{$rmaInfo['invoice']}}</td>
                </tr>
            </table>
            <br><br>
            <table>

                <tr>
                    <td width="100%" style="font-size:9px;"><span style="font-weight:bold;">RMA Reason - </span>@if($rmaInfo['rmaReason'] != null) {{$rmaInfo['rmaReason']->name}} @endif</td>
                </tr>
            </table>
            <br><br>
            <table>
                <tr>
                    <td width="100%" style="font-size:9px;"><span style="font-weight:bold;">Customer Self Declaration - </span>I, {{$rmaInfo['pickupAddress']->full_name}} hereby declare that
                        the content of this package are being returned for replacement/return/warranty purpose.
                    </td>
                </tr>
            </table>
            <table width="100%">
                <tr>
                    <td style="text-align:right;">
                        Signature&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                </tr>
            </table>

            <table width="100%">
                <tr>
                    <td width="100%" style="text-align:right;">
                        <img src="http://{{env('DOMAIN_NAME')}}/assets/custom/common/images/sign.jpg" width="100" height="40">
                    </td>
                </tr>
            </table>
            <table width="100%" style="padding-left:120px;">
                <tr>
                    <td style="font-size:11px;font-weight:bold;">
                        [ TO BE FILLED BY DELIVERY EXECUTIVE ]
                    </td>
                </tr>
            </table>
            <table width="100%" style="padding-bottom:5px;padding-top:5px;">
                <tr style="font-weight:bold;font-size:10px;">
                    <td width="50%">Acceptance Criteria</td>
                    <td>Rejection Criteria</td>
                </tr>
            </table>
            <table width="100%">
                <tr style="font-size:9px;">
                    <td width="50%">
                        <table width="100%">
                            <tr>
                                <td width="10%"><img src="http://{{env('DOMAIN_NAME')}}/assets/custom/common/images/checkbox.png" width="10" height="10"></td>
                                <td>_____________________________________</td>
                            </tr>
                            <tr><td></td></tr>
                            <tr>
                                <td><img src="http://{{env('DOMAIN_NAME')}}/assets/custom/common/images/checkbox.png" width="10" height="10"></td>
                                <td>_____________________________________</td>
                            </tr>
                            <tr><td></td></tr>
                            <tr>
                                <td><img src="http://{{env('DOMAIN_NAME')}}/assets/custom/common/images/checkbox.png" width="10" height="10"></td>
                                <td>_____________________________________</td>
                            </tr>
                        </table>
                    </td>
                    <td width="50%">
                        <table width="100%">
                            <tr>
                                <td width="10%"><img src="http://{{env('DOMAIN_NAME')}}/assets/custom/common/images/checkbox.png" width="10" height="10"></td>
                                <td>_____________________________________</td>
                            </tr>
                            <tr><td></td></tr>
                            <tr>
                                <td><img src="http://{{env('DOMAIN_NAME')}}/assets/custom/common/images/checkbox.png" width="10" height="10"></td>
                                <td>_____________________________________</td>
                            </tr>
                            <tr><td></td></tr>

                            <tr>
                                <td><img src="http://{{env('DOMAIN_NAME')}}/assets/custom/common/images/checkbox.png" width="10" height="10"></td>
                                <td>_____________________________________</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <table width="100%" style="padding-left:50px;padding-top:30px;">
                <tr style="font-size:9px;">
                    <td width="60%"></td>
                    <td>
                        <table>
                            <tr>
                                <td>_______________________</td>
                            </tr>
                            <tr>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DE Signature</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
        <td>
            <table style="padding-top:50px;">
                <tr>
                    <td>
                        <img src="http://{{env('DOMAIN_NAME')}}/assets/custom/common/images/text-rma.jpg" width="20" height="240">
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
@endif
</body>
</html>
