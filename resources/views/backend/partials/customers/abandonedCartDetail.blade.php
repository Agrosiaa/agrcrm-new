<div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet green-meadow box">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs"></i>Cart Information </div>
            </div>
            <div class="portlet-body">
                <div class="row static-info">
                    <table  cellspacing="10" cellpadding="10" width="100%">
                        <tr style="font-size:13px;font-weight:bold;  padding: 5px; background-color: #d2d2d2">
                            <td width="5%" style="text-align: center;padding: 5px;">Sr No</td>
                            <td width="10%" style="text-align: center;padding: 5px;">Product Name</td>
                            <td width="10%" style="text-align:center;">Category Name</td>
                            <td width="10%" style="text-align:center;">SKU</td>
                            <td width="15%" style="text-align:center;">Product added on</td>
                            <td width="15%" style="text-align:center;">Product updated on</td>
                            <td width="10%" style="text-align:center;">Vendor Name</td>
                            <td width="5%" style="text-align:center;">Qty</td>
                            <td width="10%" style="text-align:center;">Discounted price <i class="fa fa-inr"></i></td>
                            <td width="10%" style="text-align:center;">Total <i class="fa fa-inr"></i></td>
                        </tr>
                    <?php $counter = 1; ?>
                    @foreach($cartDetails->cartData as $key => $value)
                        @if($counter % 2 == 0)
                                <tr style="font-size:13px; background-color: #F2F2F2">
                            @else
                                <tr style="font-size:13px;">
                                    @endif
                                    <td style="text-align: center ;padding: 5px;">{{$key + 1}}</td>
                                    <td style="text-align: center;padding: 5px;"><a href="/operational/products/preview/{{$value->product_id}}">{{$value->product_name}}</a></td>
                                    <td style="text-align: center;padding: 5px;">{{$value->category_name}}</td>
                                    <td style="text-align: center">{{$value->sku}}</td>
                                    <td style="text-align: center">{{$value->created_at}}</td>
                                    <td style="text-align: center">{{$value->updated_at}}</td>
                                    <td style="text-align: center">{{$value->company}}</td>
                                    <td style="text-align: center">{{$value->quantity}}</td>
                                    <td style="text-align: center"> <a href="#" data-toggle="tooltip" title="{{$value->discount}} %">{{$value->unit_price}}</a> </td>
                                    <td style="text-align: center">{{(($value->unit_price * $value->quantity))}}</td>
                                </tr>
                                <?php $counter++; ?>
                        @endforeach
                            <tr style="font-size:15px;text-align: right">
                                <td colspan="7"></td>
                                <td style="text-align: center"><b>Grand Total :</b></td>
                                <td style="text-align: center"> {{$cartDetails->grandTotal}}</td>
                            </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script>
$(document).ready(function(){
$('[data-toggle="tooltip"]').tooltip();
});
</script>


