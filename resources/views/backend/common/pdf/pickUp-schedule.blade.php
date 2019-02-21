<html>
<head>
    <style>
        .vl {
            border-left: 1px solid black;
            height: 10px;
        }
    </style>
</head>
    <body style="font-family:tahoma;">
    <?php $counter = 0 ?>
    @foreach($orders as $sellerId => $order)
            <?php $userId = \App\Seller::where('id',$sellerId)->pluck('user_id');
            $sellerName = \App\User::where('id',$userId)->get()->first();
            $sellerAddress = \App\SellerAddress::where('seller_id',$sellerId)->get()->first();
            $counter ++ ?>
                <div style="margin-right: 10%;">
                    <table width="100%">
                        <tr>
                            <td style="font-size: 95%"><b>PICKUP SCHEDULE DOCUMENT</b></td>
                            <td style="text-align: right"><img src="https://{{env('DOMAIN_NAME')}}/assets/frontend/global/images/logo.png" alt="logo" width="1000%" height="260%" class="logo-default"></td>
                        </tr>
                        <tr>
                            <td style="font-size: 65%">Date: &nbsp;{{date('dS-F-Y'),strtotime($date)}}</td>
                        </tr>
                    </table>
                </div>
                    <table width="100%">
                        <tr>
                            <td width="50%">
                                <table style="padding-right: 10%;font-size: 65%;">
                                    <tr>
                                        <td>Vendor Name: {{$sellerName['first_name'] ." ".$sellerName['last_name']}}</td>
                                    </tr>
                                    <tr>
                                        <td>Address: {{ucwords($sellerAddress->shop_no_office_no_survey_no)}}, {{ucwords($sellerAddress->name_of_premise_building_village)}},</td>
                                    </tr>
                                    <tr>
                                        <td>{{ucwords($sellerAddress->area_locality_wadi)}}, {{ucwords($sellerAddress->road_street_lane)}}, {{ucwords($sellerAddress->at_post)}} ,</td>
                                    </tr>
                                    <tr>
                                        <td>{{ucwords($sellerAddress->district)}},{{ucwords($sellerAddress->state)}},{{ucwords($sellerAddress->pincode)}}</td>
                                    </tr>
                                </table>
                            </td>
                            <td width="50%">
                                <table style="text-align: center;font-size: 65%;">
                                    <tr>
                                        <td>
                                            <table style="border-right:1px solid black;">
                                                <tr>
                                                    <td>Date confirm Pickup</td>
                                                </tr>
                                                <br>
                                                <tr>
                                                    <td>{{date('dS-F-Y'),strtotime($date)}}</td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td>
                                            <table style="border-right:1px solid black;">
                                                <tr>
                                                    <td>Pickup Items</td>
                                                </tr>
                                                <br>
                                                <tr>
                                                    <td>{{count($order)}}</td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td>
                                            <table>
                                                <tr>
                                                    <td>Signature - Agrosiaa Admin</td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <br><br>
                    <table border="1px" style="text-align: center;font-size: 62%;width: 100%" cellpadding="5">
                        <tr>
                            <th style="width: 12%">Order No.</th>
                            <th style="width: 50%">Product Name</th>
                            <th style="width: 8%">Quantity</th>
                            <th style="width: 20%">Package List & Accessories (If Applicable)</th>
                            <th style="width: 10%">Put Tick (if applicable)</th>
                        </tr>
                        @foreach($order as $data)
                            <tr>
                                <td style="width: 12%">{{"AGR".str_pad($data['id'], 9, "0", STR_PAD_LEFT)}}</td>
                                <td style="width: 50%">{{$data['product']['product_name']}}</td>
                                <td style="width: 8%">{{$data['quantity']}}</td>
                                <td style="width: 20%">{{$data['product']['sales_package_or_accessories']}}</td>
                                <td style="width: 10%"></td>
                            </tr>
                        @endforeach
                    </table>
                    <br><br>
                    <table border="1" width="100%" cellpadding="5">
                        <tr>
                            <td style="text-align:left;font-size: 70%;"><span>Note:<br><br></span></td>
                        </tr>
                    </table>
                    <br><br>
                    <table style="font-size: 70%;" width="100%" cellpadding="5">
                        <tr>
                            <td style="text-align:left;"><span><br><br>________________________<br>Authorized Signatory - Vendor</span></td>
                            <td style="text-align:right;"><span><br><br>________________________<br>Signature - Pickup Authority</span></td>
                        </tr>
                    </table>
                @if($counter < count($orders))
                <div  style="page-break-before: always;"></div>
                @endif
    @endforeach
    </body>
</html>