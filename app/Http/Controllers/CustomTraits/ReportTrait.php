<?php
namespace App\Http\Controllers\CustomTraits;
use App\Http\Requests;
use App\LogisticAccountingAgrosiaaShipment;
use App\LogistingAccounting;
use App\ShippingMethod;
use App\Tax;
use App\User;
use App\Role;
use App\Seller;
use App\Brand;
use App\Product;
use App\OrderStatus;
use App\Order;
use App\ProductCategoryRelation;
use App\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;
use Carbon\Carbon;
use App\OrderHistory;
use App\PaymentMethod;
use App\OrderRma;
use App\RmaStatus;
use App\VendorLicenses;
use App\License;
use App\VendorSettleMent;
use App\Invoice;
use App\Customer;
use App\CustomerCancelReasons;
use Maatwebsite\Excel\Facades\Excel;

trait ReportTrait{
    use OrderTrait;
    public function timeDelay($actualDate,$estimatedDate){
        return Carbon::parse($actualDate)->diff(Carbon::parse($estimatedDate));
    }
    /*public function generateReport(Request $request){
        try{
            $message = "Success";
            $rowNumber = 1;
            $rowIndex = 2;
            $roleId = Role::where('slug','seller')->pluck('id');

            $date = Carbon::parse($request->date);
            $startDate = $date->subDays(30);
            $requestedDate = date('d', strtotime($request->selected_date));
            $selectedDate = Carbon::parse($request->selected_date);
            if($requestedDate <= 05){
                $year = date('Y',strtotime($selectedDate->subMonth()));
                $month = date('m',strtotime($selectedDate->subMonth()));
                $date ='05';
                $start_date = Carbon::create($year,$month, $date);
                $selected_date =  date('Y-m-d', strtotime($request->selected_date .' +1 day'));
            }else{
                $year = $selectedDate->year;
                $month = $selectedDate->month;
                $date ='05';
                $start_date = Carbon::create($year,$month, $date);
                $selected_date =  date('Y-m-d', strtotime($request->selected_date .' +1 day'));
            }

        switch($request->period){
            case 'quarter-1' :
                $start_time = date('01/'.substr($request->year, -2));
                $end_time = date('03/'.substr($request->year, -2));
            break;
            case 'quarter-2' :
                $start_time = date('04/'.substr($request->year, -2));
                $end_time = date('06/'.substr($request->year, -2));
            break;
            case 'quarter-3' :
                $start_time = date('07/'.substr($request->year, -2));
                $end_time = date('09/'.substr($request->year, -2));
            break;
            case 'quarter-4' :
                $start_time = date('10/'.substr($request->year, -2));
                $end_time = date('11/'.substr($request->year, -2));
            break;

            default :
            break;
        }
        $vendorName = User::where('role_id',$roleId)->where('is_active','1')->select('first_name','last_name','id')->get()->toArray();
        for($i = 0 ; $i< count($vendorName); $i++){
            $vendorName[$i]['seller_id'] = Seller::where('user_id',$vendorName[$i]['id'])->pluck('id');
        }

        if($request->report == 'taxation' || $request->report == 'settlement'){
            if($this->userRoleType == 'seller'){
                $sellerOrderIds = Order::where('seller_id',$this->seller->id)->lists('id');
                $vendorSettlement = VendorSettleMent::whereIn('order_id',$sellerOrderIds)->whereBetween('order_complete_date', [$start_date, $selected_date])->OrwhereBetween('rma_complete_date',[$start_date, $selected_date])->with('order')->get()->toArray();
            }elseif ($this->userRoleType == 'superadmin' || $this->userRoleType == 'accountadmin'){
                $from_date = Carbon::parse($request->from_date);
                $to_date = Carbon::parse($request->to_date);
                $vendorSettlement = VendorSettleMent::whereBetween('order_complete_date', [$from_date, $to_date])->OrwhereBetween('rma_complete_date',[$from_date, $to_date])->with('order')->get()->toArray();
            }
        }
        $logisticAccountingReport = array();
        if($request->report == 'logistic-accounting' && ($this->userRoleType == 'superadmin' || $this->userRoleType == 'accountadmin')){
            $ordeConfirmedStatusId = OrderStatus::where('slug','confirmed')->pluck('id');
            $from_date = Carbon::parse($request->from_date);
            $to_date = Carbon::parse($request->to_date);

            $confirmedOrders = Order::where('order_status_id',$ordeConfirmedStatusId)->whereBetween('updated_at',[$from_date, $to_date])->lists('id')->toArray();
            $logisticAccounting = LogistingAccounting::whereIn('order_id',$confirmedOrders)->get()->toArray();
            $logisticAccountingAgrosiaaShipment = LogisticAccountingAgrosiaaShipment::whereIn('order_id',$confirmedOrders)->get()->toArray();
            $logisticAccountingReport = array_merge($logisticAccounting, $logisticAccountingAgrosiaaShipment);
        }
        $row = 0;
        $ps_count = 0;
        $data[] = array();
        $Psdata[] = array();
        $date = date("F j, Y,  g:i a");
        switch($request->report){
        case 'taxation' :
            $reportTitle = "Taxation Report";
            $reportTitle1 = "PS Campaign";
            $name = "Taxation_report $date.xlsx";
            foreach($vendorSettlement as $key => $value){
              $paymentMethod=PaymentMethod::where('id',$value['order']['payment_method_id'])->first();
              $sellerName = Seller::where('id',$value['order']['seller_id'])->pluck('company');
              $invoice = Invoice::where('order_id',$value['order_id'])->first()->toArray();
              $order = Order::where('id',$value['order_id'])->first();
              $isPsFlag = ($order['is_ps_campaign']) ? true : false;
              if(!$isPsFlag){
                  $data[$row]['order_id'] = "AGR".$this->getStructuredOrderId($order['id']);  $sellerAddress = json_decode($value['order']['seller_address']);
                  $customerAddress = json_decode($order->ordersCustomerInfo->shipping_address);
                  if(strtoupper($sellerAddress->state) != strtoupper($customerAddress->state)){
                      $tax_igst_applied = true;
                  }else{
                      $tax_igst_applied = false;
                  }
                  if($paymentMethod['slug'] == 'cod'){
                      $data[$row]['invoice_number']="AGRCR".$this->getStructuredOrderId($value['order_id']);
                  }else{
                      $data[$row]['invoice_number']="AGR".$this->getStructuredOrderId($value['order_id']);
                  }
                  $data[$row]['order_complete_date'] = $value['order_complete_date'];
                  if($value['order']['is_configurable'] == true){
                      $area = $value['order']['length'] * $value['order']['width'];
                      $data[$row]['base_amount'] = ($value['order']['base_price'] - ($value['order']['base_price'] * ($value['order']['discount'] / 100 ))) * $area;
                      $taxID = Product::where('id',$order->product_id)->pluck('tax_id');
                      $data[$row]['gst'] = Tax::where('id',$taxID)->pluck('rate');
                      $subTotal = Product::where('id',$order->product_id)->pluck('subtotal');
                      $data[$row]['gst_amount'] =  round($subTotal * ($data[$row]['gst']/100),2);
                      $data[$row]['quantity'] = $value['order']['quantity'];
                      $data[$row]['order_total'] = ($data[$row]['base_amount'] ) * $data[$row]['quantity'];

                  }else{
                      $data[$row]['base_amount'] = $value['order']['base_price'] - ($value['order']['base_price'] * ($value['order']['discount'] / 100 ));
                      $taxID = Product::where('id',$order->product_id)->pluck('tax_id');
                      $data[$row]['gst'] = Tax::where('id',$taxID)->pluck('rate');
                      $subTotal = Product::where('id',$order->product_id)->pluck('subtotal');
                      $data[$row]['gst_amount'] =  round($subTotal * ($data[$row]['gst']/100),2);
                      $data[$row]['quantity'] = $value['order']['quantity'];
                      $data[$row]['order_total'] = $data[$row]['base_amount'] * $data[$row]['quantity'];
                  }
                  $data[$row]['invoice_date'] = $invoice['created_at'];
                  $data[$row]['payment_mode'] = PaymentMethod::where('id',$value['order']['payment_method_id'])->pluck('name');
                  $data[$row]['seller_name'] = $sellerName;
                  $data[$row]['gst_number'] = Seller::where('id',$value['order']['seller_id'])->pluck('gstin');
                  if($value['order']['is_configurable'] == true){
                      $area = $value['order']['length'] * $value['order']['width'];
                      $data[$row]['invoice_amount'] = ($value['order']['discounted_price'] * $area) * $value['order']['quantity'];
                  }else{
                      $data[$row]['invoice_amount'] = ($value['order']['discounted_price']) * $value['order']['quantity'];
                  }
                  $data[$row]['commission_percent'] = $value['commission_percent'] ;
                  $data[$row]['commission'] = ($value['commission_percent'] * $data[$row]['order_total'])/100;
                  $data[$row]['gst_percent_on_commission'] = 18;
                  $data[$row]['gst_commission'] = round(env('GST_PERCENT') * $data[$row]['commission'] , 2);
                  $data[$row]['delivery_charges'] = $value['delivery_charges'];
                  $data[$row]['logistic_percentage'] = $order['logistic_percent'];
                  $data[$row]['logictics/return_charges'] = ($data[$row]['logistic_percentage'] * $data[$row]['order_total'])/100;
                  $data[$row]['gst_percent_on_logistic_amount'] = 18;
                  $data[$row]['gst_logictics/return_charges'] = round(env('GST_PERCENT') * $data[$row]['logictics/return_charges'] , 2);
                  $data[$row]['GST'] = $invoice['vat_rate'];
                  if($order['tax_rate'] == 0){
                      $data[$row]['tcs_percent'] = 0;
                  }else{
                      $data[$row]['tcs_percent'] = 1;
                  }
                  if($tax_igst_applied){
                      $data[$row]['cgst_tcs'] = '-' ;
                      $data[$row]['sgst_tcs'] = '-';
                      $data[$row]['igst_tcs'] = $value['order_tcs_amount'];
                  }else{
                      $data[$row]['cgst_tcs'] = $value['order_tcs_amount']/2 ;
                      $data[$row]['sgst_tcs'] = $value['order_tcs_amount']/2;
                      $data[$row]['igst_tcs'] = '-';
                  }
                  $data[$row]['settlement_amount'] = $value['order_vendor_settlement_amount'];
                  $data[$row]['logistics name'] = ShippingMethod::where('id',$value['order']['shipping_method_id'])->pluck('name');
                  $data[$row]['AWB no'] = $value['order']['consignment_number'];
                  $row++;
                  if($value['rma_id'] != null){
                      $data[$row]['return_id'] = "AGR".$this->getStructuredOrderId($value['order_id'])."R";
                      if($paymentMethod['slug'] == 'cod'){
                          $data[$row]['invoice_number']="AGRCR".$this->getStructuredOrderId($value['order_id']);
                      }else{
                          $data[$row]['invoice_number']="AGR".$this->getStructuredOrderId($value['order_id']);
                      }
                      $data[$row]['rma_return_date'] = $value['rma_complete_date'];
                      $data[$row]['invoice_amount'] = $data[$row]['gst'] = $data[$row]['gst_amount'] = null;
                      $rmaReturnQuantity = OrderRma::where('id',$value['rma_id'])->pluck('return_quantity');
                      $data[$row]['quantity'] = $rmaReturnQuantity;
                      $data[$row]['commission'] =  $data[$row]['gst_percent_on_commission'] = $data[$row]['gst_commission'] = $data[$row]['delivery_charges'] = $data[$row]['seller_name'] = $data[$row]['gst_number'] = $data[$row]['payment_mode'] = $data[$row]['oder_quantity'] = $data[$row]['base_amount'] = $data[$row]['order'] = $data[$row]['invoice_date'] =null;
                      $data[$row]['logistic_percentage'] = $order['logistic_percent'];
                      $data[$row]['logictics/return_charges'] = $value['return_logistics_charges'];
                      $data[$row]['gst_percent_on_logistic_amount'] = 18;
                      $data[$row]['gst_logictics/return_charges'] = round(env('GST_PERCENT') * $value['return_logistics_charges'] , 2);
                      $data[$row]['GST']=null;
                      if($order['tax_rate'] == 0){
                          $data[$row]['tcs_percent'] = 0;
                      }else{
                          $data[$row]['tcs_percent'] = 1;
                      }
                      if($tax_igst_applied){
                          $data[$row]['cgst_tcs'] = '-' ;
                          $data[$row]['sgst_tcs'] = '-';
                          $data[$row]['igst_tcs'] = $value['return_tcs_amount'];
                      }else{
                          $data[$row]['cgst_tcs'] = $value['return_tcs_amount']/2 ;
                          $data[$row]['sgst_tcs'] = $value['return_tcs_amount']/2;
                          $data[$row]['igst_tcs'] = '-';
                      }
                      $data[$row]['settlement_amount'] = $value['return_vendor_settlement_amount'];
                      $row++;
                  }
              }else{
                  $Psdata[$ps_count]['order_id'] = "AGR".$this->getStructuredOrderId($order['id']);  $sellerAddress = json_decode($value['order']['seller_address']);
                  $customerAddress = json_decode($order->ordersCustomerInfo->shipping_address);
                  if(strtoupper($sellerAddress->state) != strtoupper($customerAddress->state)){
                      $tax_igst_applied = true;
                  }else{
                      $tax_igst_applied = false;
                  }
                  if($paymentMethod['slug'] == 'cod'){
                      $Psdata[$ps_count]['invoice_number']="AGRCR".$this->getStructuredOrderId($value['order_id']);
                  }else{
                      $Psdata[$ps_count]['invoice_number']="AGR".$this->getStructuredOrderId($value['order_id']);
                  }
                  $Psdata[$ps_count]['order_complete_date'] = $value['order_complete_date'];

                  if($value['order']['is_configurable'] == true){
                      $area = $value['order']['length'] * $value['order']['width'];
                      $Psdata[$ps_count]['base_amount'] = ($value['order']['base_price'] - ($value['order']['base_price'] * ($value['order']['discount'] / 100 ))) * $area;
                      $taxID = Product::where('id',$order->product_id)->pluck('tax_id');
                      $Psdata[$ps_count]['gst'] = Tax::where('id',$taxID)->pluck('rate');
                      $subTotal = Product::where('id',$order->product_id)->pluck('subtotal');
                      $Psdata[$ps_count]['gst_amount'] =  round($subTotal * ($Psdata[$ps_count]['gst']/100),2);
                      $Psdata[$ps_count]['quantity'] = $value['order']['quantity'];
                      $Psdata[$ps_count]['order_total'] = ($Psdata[$ps_count]['base_amount'] ) * $Psdata[$ps_count]['quantity'];

                  }else{
                      $Psdata[$ps_count]['base_amount'] = $value['order']['base_price'] - ($value['order']['base_price'] * ($value['order']['discount'] / 100 ));
                      $taxID = Product::where('id',$order->product_id)->pluck('tax_id');
                      $Psdata[$ps_count]['gst'] = Tax::where('id',$taxID)->pluck('rate');
                      $subTotal = Product::where('id',$order->product_id)->pluck('subtotal');
                      $Psdata[$ps_count]['gst_amount'] =  round($subTotal * ($Psdata[$ps_count]['gst']/100),2);
                      $Psdata[$ps_count]['quantity'] = $value['order']['quantity'];
                      $Psdata[$ps_count]['order_total'] = $Psdata[$ps_count]['base_amount'] * $Psdata[$ps_count]['quantity'];
                  }
                  $Psdata[$ps_count]['invoice_date'] = $invoice['created_at'];
                  $Psdata[$ps_count]['seller_name'] = $sellerName;
                  if($value['order']['is_configurable'] == true){
                      $area = $value['order']['length'] * $value['order']['width'];
                      $Psdata[$ps_count]['invoice_amount'] = ($value['order']['discounted_price'] * $area) * $value['order']['quantity'];
                  }else{
                      $Psdata[$ps_count]['invoice_amount'] = ($value['order']['discounted_price']) * $value['order']['quantity'];
                  }
                  $Psdata[$ps_count]['commission_percent'] = $value['commission_percent'] ;
                  $Psdata[$ps_count]['commission'] = ($value['commission_percent'] * $Psdata[$ps_count]['order_total'])/100;
                  $Psdata[$ps_count]['gst_percent_on_commission'] = 18;
                  $Psdata[$ps_count]['gst_commission'] = round(env('GST_PERCENT') * $Psdata[$ps_count]['commission'] , 2);
                  $Psdata[$ps_count]['logistic_percentage'] = $order['logistic_percent'];
                  $Psdata[$ps_count]['logictics/return_charges'] = ($Psdata[$ps_count]['logistic_percentage'] * $Psdata[$ps_count]['order_total'])/100;
                  $Psdata[$ps_count]['gst_percent_on_logistic_amount'] = 18;
                  $Psdata[$ps_count]['gst_logictics/return_charges'] = round(env('GST_PERCENT') * $Psdata[$ps_count]['logictics/return_charges'] , 2);
                  $Psdata[$ps_count]['GST'] = $invoice['vat_rate'];
                  if($order['tax_rate'] == 0){
                      $Psdata[$ps_count]['tcs_percent'] = 0;
                  }else{
                      $Psdata[$ps_count]['tcs_percent'] = 1;
                  }
                  if($tax_igst_applied){
                      $Psdata[$ps_count]['cgst_tcs'] = '-' ;
                      $Psdata[$ps_count]['sgst_tcs'] = '-';
                      $Psdata[$ps_count]['igst_tcs'] = $value['order_tcs_amount'];
                  }else{
                      $Psdata[$ps_count]['cgst_tcs'] = $value['order_tcs_amount']/2 ;
                      $Psdata[$ps_count]['sgst_tcs'] = $value['order_tcs_amount']/2;
                      $Psdata[$ps_count]['igst_tcs'] = '-';
                  }
                  $Psdata[$ps_count]['vendor_campaign_charge'] = $order['vendor_campaign_charges'];
                  $Psdata[$ps_count]['agrosiaa_campaign_charges'] = $order['agrosiaa_campaign_charges'];
                  $Psdata[$ps_count]['settlement_amount'] = $value['order_vendor_settlement_amount'];
                  $Psdata[$ps_count]['logistics name'] = ShippingMethod::where('id',$value['order']['shipping_method_id'])->pluck('name');
                  $ps_count++;
              }

            }
            if($this->userRoleType == 'seller' || $this->userRoleType == 'accountadmin'){
                $rows[1] = array("Order no/Return no","Invoice Number","Order/return Complete Date","Base Price Excluding GST","Product GST","Product GST Amount","Qty Ordered/return By Customer","Base Amount X Qty","Invoice Timestamp","Payment Mode","Company Name","GST No", "Invoice Amount", "Commission Percent", "Commission", "GST % on Commission(%)",
                    "GST on Commission","Delivery Charges","Logistic Percent(%)", "Logistics/Return Charges","GST % on Logistic Amount(%)",
                    "GST on Logistics","GST","TCS Percent(%)", "CGST(0.5%)" ,"SGST(0.5%)" ,"IGST(1%)", "Settlement Amount","Logistic Name","AWB No");
            }elseif($this->userRoleType == 'superadmin' ){
                $rows[1] = array("Order no/Return no","Invoice Number","Order/return Complete Date","Base Price Excluding GST","Product GST","Product GST Amount","Qty Ordered/return By Customer","Base Amount X Qty","Invoice Timestamp","Payment Mode","Company Name", "GST No", "Invoice Amount", "Commission Percent", "Commission", "GST % on Commission(%)",
                    "GST on Commission","Delivery Charges","Logistic Percent(%)", "Logistics/Return Charges","GST % on Logistic Amount(%)",
                    "GST on Logistics","GST" ,"TCS Percent(%)", "CGST(0.5%)" ,"SGST(0.5%)" ,"IGST(1%)", "Settlement Amount","Logistic Name","AWB No");
            }
            $ps_row[1] = array("Order no/Return no","Invoice Number","Order/return Complete Date","Base Price Excluding GST","Product GST","Product GST Amount","Qty Ordered/return By Customer","Base Amount X Qty","Invoice Timestamp","Company Name","Invoice Amount", "Commission Percent", "Commission", "GST % on Commission(%)",
                "GST on Commission","Logistic Percent(%)", "Logistics/Return Charges","GST % on Logistic Amount(%)",
                "GST on Logistics","GST" ,"TCS Percent(%)", "CGST(0.5%)" ,"SGST(0.5%)" ,"IGST(1%)","Vendor Campaign Charges","AGR Campaign Charges","Settlement Amount (Receivable to AGR)","Logistic Name");
            $rowNumber = 2;
            $rowIndex = 3;
            break;

        case 'logistic-accounting' :
              $reportTitle = "Logistic Accounting Report";
              $name = "Logistic Accounting $date.xlsx";
              foreach($logisticAccountingReport as $key => $value){
                  $data[$row]['order_no'] = "AGR".$this->getStructuredOrderId($value['order_id']);
                  $confirmedDate = Order::where('id',$value['order_id'])->select('updated_at')->get()->toArray();
                  $data[$row]['confirmed_date'] = date('m-d-Y',strtotime($confirmedDate[0]['updated_at']));
                  $data[$row]['biller_id'] = (array_key_exists('biller_id', $value)) ? $value['biller_id'] : null;
                  $data[$row]['transaction_id'] = (array_key_exists('trans_id', $value)) ? $value['trans_id'] : null;
                  $data[$row]['biller_name'] = (array_key_exists('biller_name', $value)) ? $value['biller_name'] : null;
                  $data[$row]['amount'] = (array_key_exists('amount', $value)) ? $value['amount'] : null;
                  $data[$row]['commission'] = (array_key_exists('commission', $value)) ? $value['commission'] : null;
                  $data[$row]['gst_amount'] = (array_key_exists('gst', $value)) ? $value['gst'] : null;
                  $data[$row]['net_payable'] = (array_key_exists('net_payable', $value)) ? $value['net_payable'] : null;
                  $data[$row]['article_number'] = (array_key_exists('article_number', $value)) ? $value['article_number'] : null;
                  $data[$row]['barcode_number'] = (array_key_exists('barcode_number', $value)) ? $value['barcode_number'] : null;
                  $data[$row]['document_number'] = (array_key_exists('document_number', $value)) ? $value['document_number'] : null;
                  $data[$row]['payment_docket_number'] = (array_key_exists('payment_docket_number', $value)) ? $value['payment_docket_number'] : null;
                  $data[$row]['collection_office'] = (array_key_exists('collection_office', $value)) ? $value['collection_office'] : null;
                  $data[$row]['collection_date'] = (array_key_exists('collection_date', $value)) ? date('d-m-Y',strtotime($value['collection_date'])) : null;
                  $data[$row]['article_type'] = (array_key_exists('article_type', $value)) ? $value['article_type'] : null;
                  $data[$row]['check_number'] = (array_key_exists('check_number', $value)) ? $value['check_number'] : null;
                  $data[$row]['logistic_number'] = (array_key_exists('logistic_number', $value)) ? $value['logistic_number'] : null;
                  $data[$row]['logistic_date'] = (array_key_exists('logistic_date', $value)) ? date('m-d-Y',strtotime($value['logistic_date'])) : null;
                  $data[$row]['logistic_invoice_amount'] = (array_key_exists('logistic_invoice_amount', $value)) ? $value['logistic_invoice_amount'] : null;
                  $data[$row]['invoice_payment_details'] = (array_key_exists('invoice_payment_details', $value)) ? $value['invoice_payment_details'] : null;
                  $data[$row]['actual_logistic_cost'] = (array_key_exists('actual_logistic_cost', $value)) ? $value['actual_logistic_cost'] : null;
                  $data[$row]['note_name'] = (array_key_exists('actual_logistic_cost', $value)) ? $value['actual_logistic_cost'] : null;
                  $data[$row]['payment_date'] = (array_key_exists('payment_date', $value)) ? date('m-d-Y',strtotime($value['payment_date'])) : null;
                  $data[$row]['deliver_by'] = (array_key_exists('deliver_by', $value)) ? $value['deliver_by'] : null;
                  $data[$row]['delivery_done_by'] = (array_key_exists('delivery_done_by', $value)) ? $value['delivery_done_by'] : null;
                  $data[$row]['lr_number'] = (array_key_exists('lr_number', $value)) ? $value['lr_number'] : null;
                  $data[$row]['lr_date'] = (array_key_exists('lr_date', $value)) ? ($value['lr_date']) : null;
                  $data[$row]['lr_amount'] = (array_key_exists('lr_amount', $value)) ? $value['lr_amount'] : null;
                  $data[$row]['payment_received_mode'] = (array_key_exists('payment_received_mode', $value)) ? $value['payment_received_mode'] : null;
                  $data[$row]['bank_name'] = (array_key_exists('bank_name', $value)) ? $value['bank_name'] : null;
                  $data[$row]['payment_deposit_date'] = (array_key_exists('payment_deposit_date', $value)) ? date('m-d-Y',strtotime($value['payment_deposit_date'])) : null;
                  $data[$row]['deposit_note'] = (array_key_exists('deposit_note', $value)) ? $value['deposit_note'] : null;
                  $data[$row]['invoice_number'] = (array_key_exists('invoice_number', $value)) ? $value['invoice_number'] : null;
                  $data[$row]['invoice_date'] = (array_key_exists('invoice_date', $value)) ? $value['invoice_date'] : null;
                  $data[$row]['invoice_amount'] = (array_key_exists('invoice_amount', $value)) ? $value['invoice_amount'] : null;
                  $row++;
              }
              if($this->userRoleType == 'superadmin' || $this->userRoleType == 'accountadmin') {
                      $rows[0] = array("Order No","Acc.confirmed date","Biller Id","Transaction Id","Biller Name","Amount","Commission","GST amount","Net Payable"
                                        ,"Article Number","Barcode Number","Document Number","Payment Docket Number","Collection Office","Collection Date","Article Type",
                                        "Cheque Number","Logistic Number","Logistic Date","Logistic Invoice Amount","Invoice Payment Details","Actual Logistic Cost","Note Name",
                                        "Payment Date","Delivery By","Delivery Done By","LR Number","LR Date","LR Amount","Payment Received Mode",
                                        "Bank Name","Payment Deposit Date","Deposit Note","Invoice Number","Invoice Date","Invoice Amount");
              }
            $rowNumber = 3;
            $rowIndex = 4;
            break;

        case 'settlement' :
            $reportTitle = "Settlement Report";
            $reportTitle1 = "PS Campaign";
            $name = "Settlement_report $date.xlsx";
            foreach($vendorSettlement as $key => $value){
                $order = Order::where('id',$value['order_id'])->first();
                if($order['is_ps_campaign'] == null){
                    if($this->userRoleType == 'superadmin' || $this->userRoleType == 'accountadmin'){
                        $SellerName = Seller::where('id',$value['order']['seller_id'])->pluck('company');
                        $data[$row]['seller_name'] = $SellerName;
                        $data[$row]['gst_number'] = Seller::where('id',$value['order']['seller_id'])->pluck('gstin');
                    }
                    $sellerAddress = json_decode($order->seller_address);
                    $customerAddress = json_decode($order->ordersCustomerInfo->shipping_address);
                    if(strtoupper($sellerAddress->state) != strtoupper($customerAddress->state)){
                        $tax_igst_applied = true;
                    }else{
                        $tax_igst_applied = false;
                    }
                    $data[$row]['order_completion_time'] = $value['order_complete_date'];
                    $data[$row]['order_no'] = "AGR".$this->getStructuredOrderId($value['order_id']);
                    if($value['order']['is_configurable'] == true){
                        $area = $value['order']['length'] * $value['order']['width'];
                        $data[$row]['invoice_total'] = (($value['order']['discounted_price'] * $area)* $value['order']['quantity']);
                    }else{
                        $data[$row]['invoice_total'] = ($value['order']['discounted_price'] * $value['order']['quantity']);
                    }
                    $taxID = Product::where('id',$order->product_id)->pluck('tax_id');
                    $data[$row]['gst'] = Tax::where('id',$taxID)->pluck('rate');
                    $subTotal = Product::where('id',$order->product_id)->pluck('subtotal');
                    $data[$row]['gst_amount'] =  round($subTotal * ($data[$row]['gst']/100),2);
                    if($tax_igst_applied){
                        $data[$row]['cgst_tcs'] = '-' ;
                        $data[$row]['sgst_tcs'] = '-';
                        $data[$row]['igst_tcs'] = $value['order_tcs_amount'];
                    }else{
                        $data[$row]['cgst_tcs'] = $value['order_tcs_amount']/2 ;
                        $data[$row]['sgst_tcs'] = $value['order_tcs_amount']/2;
                        $data[$row]['igst_tcs'] = '-';
                    }

                    $data[$row]['vendor_settlement_amount'] = $value['order_vendor_settlement_amount'];
                    $row++;
                    if($value['rma_id'] != null){
                        if($this->userRoleType == 'superadmin' || $this->userRoleType == 'accountadmin'){
                            $data[$row]['seller_name']= "";
                            $data[$row]['gst_number']= "";
                        }
                        $data[$row]['order_completion_time'] = $value['rma_complete_date'];
                        $data[$row]['return_id'] = "AGR".$this->getStructuredOrderId($value['order_id'])."R";
                        $data[$row]['invoice_total'] = null;
                        $data[$row]['gst'] = null;
                        $data[$row]['gst_amount'] = null;
                        if($tax_igst_applied){
                            $data[$row]['cgst_tcs'] = '-' ;
                            $data[$row]['sgst_tcs'] = '-';
                            $data[$row]['igst_tcs'] = $value['return_tcs_amount'];
                        }else{
                            $data[$row]['cgst_tcs'] = $value['return_tcs_amount']/2 ;
                            $data[$row]['sgst_tcs'] = $value['return_tcs_amount']/2;
                            $data[$row]['igst_tcs'] = '-';
                        }
                        $data[$row]['vendor_settlement_amount'] = $value['return_vendor_settlement_amount'];
                        $row++;
                    }
                }else{
                    if($this->userRoleType == 'superadmin' || $this->userRoleType == 'accountadmin'){
                        $SellerName = Seller::where('id',$value['order']['seller_id'])->pluck('company');
                        $Psdata[$ps_count]['seller_name'] = $SellerName;
                        $Psdata[$ps_count]['gst_number'] = Seller::where('id',$value['order']['seller_id'])->pluck('gstin');;
                    }
                    $sellerAddress = json_decode($order->seller_address);
                    $customerAddress = json_decode($order->ordersCustomerInfo->shipping_address);
                    if(strtoupper($sellerAddress->state) != strtoupper($customerAddress->state)){
                        $tax_igst_applied = true;
                    }else{
                        $tax_igst_applied = false;
                    }
                    $Psdata[$ps_count]['order_completion_time'] = $value['order_complete_date'];
                    $Psdata[$ps_count]['order_no'] = "AGR".$this->getStructuredOrderId($value['order_id']);
                    if($value['order']['is_configurable'] == true){
                        $area = $value['order']['length'] * $value['order']['width'];
                        $Psdata[$ps_count]['invoice_total'] = (($value['order']['discounted_price'] * $area)* $value['order']['quantity']);
                    }else{
                        $Psdata[$ps_count]['invoice_total'] = ($value['order']['discounted_price'] * $value['order']['quantity']);
                    }
                    $taxID = Product::where('id',$order->product_id)->pluck('tax_id');
                    $Psdata[$ps_count]['gst'] = Tax::where('id',$taxID)->pluck('rate');
                    $subTotal = Product::where('id',$order->product_id)->pluck('subtotal');
                    $Psdata[$ps_count]['gst_amount'] =  round($subTotal * ($Psdata[$ps_count]['gst']/100),2);
                    if($tax_igst_applied){
                        $Psdata[$ps_count]['cgst_tcs'] = '-' ;
                        $Psdata[$ps_count]['sgst_tcs'] = '-';
                        $Psdata[$ps_count]['igst_tcs'] = $value['order_tcs_amount'];
                    }else{
                        $Psdata[$ps_count]['cgst_tcs'] = $value['order_tcs_amount']/2 ;
                        $Psdata[$ps_count]['sgst_tcs'] = $value['order_tcs_amount']/2;
                        $Psdata[$ps_count]['igst_tcs'] = '-';
                    }
                    $Psdata[$ps_count]['vendor_settlement_amount'] = $value['order_vendor_settlement_amount'];
                    $ps_count++;
                }
            }
            if($this->userRoleType == 'seller'){
                $rows[1] = array("Order/Return completion timestamp", "Order no/Return no", "Invoice Total","Product GST","Product GST Amount", "CGST(0.5%)" ,"SGST(0.5%)" ,"IGST(1%)", "Vendor Settlement Amount", "Payment Voucher Id for Settlement");
                $ps_row[1] = array("Order/Return completion timestamp", "Order no/Return no", "Invoice Total","Product GST","Product GST Amount", "CGST(0.5%)" ,"SGST(0.5%)" ,"IGST(1%)", "Settlement Amount (Receivable to AGR)", "Payment Voucher Id for Settlement");
            }elseif($this->userRoleType == 'superadmin' || $this->userRoleType == 'accountadmin'){
                $rows[1] = array("Company Name","GST No","Order/Return completion timestamp", "Order no/Return no", "Invoice Total","Product GST","Product GST Amount", "CGST(0.5%)" ,"SGST(0.5%)" ,"IGST(1%)", "Vendor Settlement Amount",  "Payment Voucher Id for Settlement");
                $ps_row[1] = array("Company Name","GST No","Order/Return completion timestamp", "Order no/Return no", "Invoice Total","Product GST","Product GST Amount", "CGST(0.5%)" ,"SGST(0.5%)" ,"IGST(1%)", "Settlement Amount (Receivable to AGR)",  "Payment Voucher Id for Settlement");
            }
            $rowNumber = 2;
            $rowIndex = 3;
            break;

        case 'out-stock':
            if($request->vendor == 0){
              $sellerDetail = Product::whereNotNull('out_of_stock_date')->with('brand')->get()->toArray();
            }else{
              $sellerDetail = Product::where('seller_id',$request->vendor)->whereNotNull('out_of_stock_date')->with('brand')->get()->toArray();
            }
            $cancel_id = OrderStatus::where('slug','back_ordered')->pluck('id');
            $reportTitle = "Out of Stock Report";
            $name = "Out_of_stock_report $date.xlsx";
            foreach($sellerDetail as $key => $value){
              $data[$row]['product_name'] = ucfirst($value['product_name']);
              $data[$row]['sku'] = ucfirst($value['item_based_sku']);
              $data[$row]['brand_name'] = ucfirst($value['brand']['name']);
              $category = ProductCategoryRelation::where('product_id',$value['id'])->with('CategoryProductRel')->first()->toArray();
              $data[$row]['item_head'] = ucfirst($category['category_product_rel']['name']);
              $data[$row]['vendor_id'] = $value['seller_id'];
              for($iterator = 0 ; $iterator < count($vendorName) ; $iterator++){
                if($value['seller_id'] == $vendorName[$iterator]['seller_id']){
                  $data[$row]['vendor_name'] = ucwords($vendorName[$iterator]['first_name']." ".$vendorName[$iterator]['last_name']);
                }
              }
              $data[$row]['out_of_stock_date'] = $value['out_of_stock_date'];
              $order_id = Order::where('product_id',$value['id'])->where('order_status_id',$cancel_id)->orderBy('id','desc')->pluck('id');
              $cancel_reason_id = OrderHistory::where('order_id',$order_id)->where('order_status_id',$cancel_id)->pluck('customer_cancel_reasons_id');
              $reason = CustomerCancelReasons::where('id',$cancel_reason_id)->pluck('reason');
              $data[$row]['out_of_stock_reason'] = $reason;
                $row++;
            }
            $rows[0] = array("Product Name","Product SKU","Brand","Item Head","Vendor Id","Vendor Name",
            "Out of Stock Since","Out of Stock Reason");
            break;

        case 'return' :
            if($request->status != 'all'){
             $rmaStatus = RmaStatus::where('slug',$request->status)->lists('id')->toArray();
            }else{
             $rmaStatus = RmaStatus::whereNotIn('slug', ['rejected','canceled'])->lists('id')->toArray();
            }
            $reportTitle = "Return Report";
            $name = "return_report $date.xlsx";
            if($request->vendor == 0){
                $orderRma = OrderRma::wherein('rma_status_id',$rmaStatus)->whereBetween('created_at',[ $startDate , $request->date ])->with('rmaReason','rmaStatus','order')->get()->toArray();
            }else{
                $selectedVendorOrders = Order::where('seller_id',$request->vendor)->lists('id');
                $orderRma = OrderRma::wherein('order_id',$selectedVendorOrders)
                    ->wherein('rma_status_id',$rmaStatus)
                    ->whereBetween('created_at',[ $startDate , $request->date ])
                    ->orderBy('created_at','desc')
                    ->with('rmaReason','rmaStatus','order')->get()->toArray();
            }
            foreach($orderRma as $key => $value){
               $data[$row]['request_id'] = "AGR".$this->getStructuredOrderId($value['order_id'])."R";
               $data[$row]['request_date'] = $value['created_at'];
               $data[$row]['return_status'] = $value['rma_status']['status'];
               $data[$row]['return_reason'] = $value['rma_reason']['name'];
               $data[$row]['product_name'] = ucwords($value['product_name']);
               $data[$row]['quantity'] = $value['return_quantity'];
               $data[$row]['vendor_id'] = $value['order']['seller_id'];
               for($iterator = 0 ; $iterator < count($vendorName) ; $iterator++){
                 if($data[$row]['vendor_id'] == $vendorName[$iterator]['seller_id']){
                   $data[$row]['vendor_name'] = ucwords($vendorName[$iterator]['first_name']." ".$vendorName[$iterator]['last_name']);
                 }
               }
               $brand = Product::where('id',$value['order']['product_id'])->with('brand')->first()->toArray();
               $data[$row]['brand'] = ucfirst($brand['brand']['name']);
               $data[$row]['customer_id'] = Customer::where('id',$value['order']['customer_id'])->pluck('user_id');
               $data[$row]['customer_mobile_no'] = User::where('id',$data[$row]['customer_id'])->pluck('mobile');
                $row++;
             }
           $rows[0] = array("Return Id","Return Date","Return Status","Return Reason","Product Name","Quantity",
           "Vendor Id","Vendor Name","Brand","Customer ID","Customer Mobile");
           break;

        case 'delivery':
          $reportTitle = "Delivery Report";
          $name = "Delivery_report $date.xlsx";
          $orderStatusId = OrderStatus::where('slug','complete')->pluck('id');
          $orderDetails = Order::where('order_status_id',$orderStatusId)
          ->whereBetween('created_at',[ $startDate , $request->date ])
          ->orderBy('created_at','desc')
          ->with('DeliveryMethod','orderHistory','invoice')->orderBy('id')->get()->toArray();
          foreach($orderDetails as $key => $value){
            $data[$row]['order_id'] = "AGR".$this->getStructuredOrderId($value['id']);
            $data[$row]['order_time'] = $value['created_at'];
            $data[$row]['invoice_date'] = $value['invoice']['created_at'];
            $data[$row]['delivery_type'] = $value['delivery_method']['name'];
            $data[$row]['vendor_id'] = $value['seller_id'];
            for($iterator = 0 ; $iterator < count($vendorName) ; $iterator++){
              if($value['seller_id'] == $vendorName[$iterator]['seller_id']){
                $data[$row]['vendor_name'] = ucwords($vendorName[$iterator]['first_name']." ".$vendorName[$iterator]['last_name']);
              }
            }
            $data[$row]['dispatch_date_vendor'] = $value['order_history'][2]['created_at'];
            $data[$row]['expected_dispatch_date'] = $value['dispatch_date'];
            $data[$row]['procurement_sla_breach'] = ($value['procurement_sla_breach_vendor'] == true) ? "Yes" : "No";
            if($data[$row]['dispatch_date_vendor'] != null){
                $delay_hours = $this->timeDelay($value['dispatch_date'],$data[$row]['dispatch_date_vendor']);
                $data[$row]['delay_hours'] = $delay_hours->format("%R%a")."D ".$delay_hours->h."hr ".$delay_hours->i."m";
            }else{
                $data[$row]['delay_hours'] = null;
            }
            $data[$row]['pickup_date_ack'] = $value['notify_mark_as_pick_up_time_vendor'];
            $data[$row]['pickup_notification'] = $value['notify_pick_up_time_shipment'];
            $data[$row]['estimated_pick_up_date'] = $value['pick_up_date'];
            $data[$row]['pick_up_sla_breach'] = ($value['pick_up_sla_breach_shipment'] == true) ? "Yes" : "No";
            if($data[$row]['pickup_notification'] != null){
                $delay_hrs_pick = $this->timeDelay($data[$row]['estimated_pick_up_date'],$data[$row]['pickup_notification']);
                $data[$row]['delay_hrs_pick'] = $delay_hrs_pick->format("%R%a")."D ".$delay_hrs_pick->h."hr ".$delay_hrs_pick->i."m";
            }else{
                $data[$row]['delay_hrs_pick'] = null;
            }
            $data[$row]['order_completion'] = $value['created_at'];
            $data[$row]['estimated_delivery_date'] = $value['delivery_date'];
            $delivery_time = $value['delivery_date']."11:59:00";
            $data[$row]['delivery_sla_breach'] = ($value['delivery_sla_breach_shipment'] == true) ? "Yes" : "No";
            $delay_hrs_delivery = $this->timeDelay($delivery_time,$data[$row]['order_completion']);
            $data[$row]['delay_hrs_delivery'] = $delay_hrs_delivery->format("%R%a")."D ".$delay_hrs_delivery->h."hr ".$delay_hrs_delivery->i."m" ;
              $row++;
          }
          $rows[1] = array("Order ID","Order Time Stamp","Invoice Date","Delivery type","Vendor Id","Vendor Name","Dispatch date(vendor)",
          "Expected Dispatch date","Procurement sla breach","Delay hours","Pickup date Ack","Pickup notification","Estimated pickup date",
          "Pickup sla breach","Delay hours","Order completion date","Estimated delivery date(on or before)","Delivery sla breach","Delay hours");
          $rowNumber = 2;
          $rowIndex = 3;
          break;

        case 'vendor-licence' :
          $reportTitle = "Vendor Licence Expiry Report";
          $name = "Vendor_licence_expiry_report $date.xlsx";
          $licenceDetails = VendorLicenses::whereBetween('expiry_date',[$start_time,$end_time])->with('category','license','vendor')->get()->toArray();
          foreach($licenceDetails as $key => $value){
            $vendorName  = User::where('id',$value['vendor']['user_id'])->select('first_name','last_name')->get()->toArray();
            for($iterator = 0 ; $iterator < count($vendorName) ; $iterator++){
                $data[$row]['vendor_name'] = ucwords($vendorName[$iterator]['first_name']." ".$vendorName[$iterator]['last_name']);
            }
            $data[$row]['id'] = $value['license_number'];
            $data[$row]['license_type'] = $value['license']['name'];
            $data[$row]['category_name'] = ucfirst($value['category']['name']);
            $data[$row]['expiry_date'] = $value['expiry_date'];
            $data[$row]['upload_date'] = $value['created_at'];
            $data[$row]['vendor_approval_date'] = $value['vendor']['approval_date'];
              $row++;
          }
          $rows[0] = array("Vendor name", "ID", "License Type", "Category Name", "Expiry Date", "Upload Date", "Vendor Approval Date");
          break;

        case 'time-sale' :
          $reportTitle = "Time vs Sale Report";
          $name = "time_vs_sale_report $date.xlsx";
          $orderStatus = OrderStatus::whereNotIn('slug', ['back_ordered','cancel','declined','failed','abort'])->select('id')->get()->toArray();
          $orderDetails = Order::whereIn('order_status_id',$orderStatus)
          ->whereBetween('created_at',[ $startDate , $request->date ])
          ->orderBy('created_at','desc')
          ->with('DeliveryMethod','orderHistory','invoice','product','PaymentMethod','ShippingMethod')->get()->toArray();
            foreach($orderDetails as $key => $value){
            $data[$row]['shipment_partner_name'] = $value['shipping_method']['name'];
            $data[$row]['order_time'] = $value['created_at'];
            $data[$row]['order_id'] = "AGR".$this->getStructuredOrderId($value['id']);
            $data[$row]['invoice_date'] = $value['invoice']['created_at'];
            for($iterator = 0 ; $iterator < count($vendorName) ; $iterator++){
              if($value['seller_id'] == $vendorName[$iterator]['seller_id']){
                $data[$row]['vendor_name'] = ucwords($vendorName[$iterator]['first_name']." ".$vendorName[$iterator]['last_name']);
              }
            }
            $data[$row]['product_name'] = ucfirst($value['product']['product_name']);
            $data[$row]['item_based_sku'] = $value['product']['item_based_sku'];
            $data[$row]['quantity'] = $value['quantity'];
            if($value['is_configurable'] == true){
                $area = $value['length'] * $value['width'];
                $data[$row]['price'] = $value['selling_price'] * $area;
            }else{
                $data[$row]['price'] = $value['selling_price'];
            }
            $data[$row]['discount_percent'] = $value['discount'];
                if($value['is_configurable'] == true){
                    $area = $value['length'] * $value['width'];
                    $data[$row]['discount_amount'] = ($data[$row]['price'] * $value['quantity']) - (($value['discounted_price'] * $area)* $value['quantity']);
                }else{
                    $data[$row]['discount_amount'] = ($data[$row]['price'] * $value['quantity']) - ($value['discounted_price'] * $value['quantity']);
                }
            $data[$row]['delivery_charges'] = $value['delivery_amount'];
            $data[$row]['payment_method'] = $value['payment_method']['name'];
            $data[$row]['delivery_method'] = $value['delivery_method']['name'];
                if($value['is_configurable'] == true){
                    $area = $value['length'] * $value['width'];
                    $data[$row]['order_grand_total'] = (($value['discounted_price'] * $area)* $value['quantity']) + $value['delivery_amount'] - $value['coupon_discount'];
                }else{
                    $data[$row]['order_grand_total'] = ($value['discounted_price'] * $value['quantity']) + $value['delivery_amount'] - $value['coupon_discount'];
                }
              $row++;
          }
          $rows[1] = array("Shipment Partner","Order Time Stamp","Order ID","Invoice Date","Vendor Name","Product Name",
          "Item Based SKU","Quantity","Agrosiaa Selling Price","Discount Percent","Discount Amount","Delivery Charges","Payment Method","Delivery Method","Order Grand Total");
            break;

        case 'return-pick-up' :
          $reportTitle = "Return Pick Up Report";
          $name = "Return_Pick_Up_report $date.xlsx";
          $rmaStatus = RmaStatus::whereIn('slug', ['return_package_received','refund_initiated','refund_completed'])->lists('id')->toArray();
          $orderRma = OrderRma::whereBetween('created_at',[$startDate , $request->date])
              ->whereIn('rma_status_id',$rmaStatus)
              ->orderBy('created_at','desc')
              ->with('rmaReason','rmaStatus','order')->get()->toArray();
          $completeSlug = OrderStatus::where('slug','complete')->pluck('id');
          foreach($orderRma as $key => $value){
            $itemhead = ProductCategoryRelation::where('product_id',$value['order']['product_id'])->pluck('category_id');
            $subSubCategory = Category::where('id',$itemhead)->pluck('category_id');
            $subCategory = Category::where('id',$subSubCategory)->select('category_id','return_period')->get()->toArray();
            if($subCategory[0]['category_id'] != null){
               $rootCategory = Category::where('id',$subCategory[0]['category_id'])->select('category_id','return_period')->get()->toArray();
               $return_within = $rootCategory[0]['return_period'];
            }else{
               $return_within = $subCategory[0]['return_period'];
            }
            $data[$row]['return_id'] = "AGR".$this->getStructuredOrderId($value['order_id'])."R";
            $data[$row]['return_reason'] = $value['rma_reason']['name'];
            $data[$row]['return_status'] = $value['rma_status']['status'];
            $order_completion_date = OrderHistory::where('order_id',$value['order_id'])->where('order_status_id',$completeSlug)->pluck('created_at');
            $data[$row]['order_completion_date'] = $order_completion_date;
            $data[$row]['return_within'] = $return_within ."days before 6PM";
            $data[$row]['requested_date'] = $value['created_at'];
            $data[$row]['pickup_sla'] = $value['pick_up_date'];
            $data[$row]['pickup_completion_date'] = $value['notify_return_pick_up_time_shipment'];
            $data[$row]['return_pickup_sla_breach'] = ($value['return_pick_up_sla_breach_shipment'] == true) ? "Yes" : "No";
            if($data[$row]['pickup_completion_date'] != null){
                $return_pickup_delay = $this->timeDelay($data[$row]['pickup_sla'],$data[$row]['pickup_completion_date']);
                $data[$row]['return_pickup_delay'] = $return_pickup_delay->format("%R%a D");
            }else{
                $data[$row]['return_pickup_delay'] = null;
            }
            $data[$row]['return_package_acknowledge'] = $value['notify_acknowledge_time_vendor'];
            $data[$row]['return_package_received_notification'] = $value['notify_return_delivery_time_shipment'];
            $data[$row]['return_package_received_date'] = $value['return_delivery_date'];
            $data[$row]['return_delivery_sla_breach'] = ($value['return_delivery_sla_breach_shipment'] == true) ? "Yes" : "No";
            if($data[$row]['return_package_received_notification'] != null){
                $delivery_delay = $this->timeDelay($data[$row]['return_package_received_date'],$data[$row]['return_package_received_notification']);
                $data[$row]['delivery_delay'] = $delivery_delay->format("%R%a D");
            }else{
                $data[$row]['delivery_delay'] = null;
            }
              $row++;
          }
          $rows[0] = array("Return ID","Return Reason","Return Status","Order Completion Date","Return Within","Requested Date",
          "Pickup Sla","Pickup Completion Date","Return pickup sla breach","Delay days","Return package received acknowlegment",
           "Return package received notification","Return Package Received Date","Return delivery SLA breach","Delivery Delay days");

          break;

        case 'product':
              $seller_id = $this->user->seller()->first();
              $categoryName = Category::where('id',$request->item_head)->pluck('name');
              $reportTitle = "$categoryName";
              $name = "product_report $date.xlsx";
              $productId = ProductCategoryRelation::where('category_id',$request->item_head)->lists('product_id')->toArray();
              $product = Product::whereIn('id',$productId)->where('seller_id',$seller_id['id'])->get()->toArray();
              foreach($product as $key => $value){
                  $data[$row]['seller_sku'] = $value['seller_sku'];
                  $data[$row]['product_name'] = $value['product_name'];
                  $data[$row]['quantity'] = $value['quantity'];
                  $data[$row]['discounted_price'] = $value['discounted_price'];
                  $data[$row]['selling_price'] = $value['selling_price'];
                  $tax = Tax::where('id',$value['tax_id'])->pluck('name');
                  $data[$row]['tax'] = $tax;
                  $data[$row]['HSNCode'] = $value['hsn_code_tax_relation_id'];
                  if($value['is_active'] == true){
                      $data[$row]['status'] = 'Enable';
                  }else{
                      $data[$row]['status'] = 'Disable';
                  }
                  if($value['out_of_stock_date'] != null){
                      $data[$row]['stock'] = 'out_of_stock';
                  }else{
                      $data[$row]['stock'] = 'in_stock';
                  }
                  if($value['is_deleted'] == null){
                      $data[$row]['is_delete'] = 'No';
                  }else{
                      $data[$row]['is_delete'] = 'Yes';
                  }
                      $row++;
              }
              $rows[0] = array("Seller sku","Product Name","Quantity","Discounted Price","Selling Price","Tax","HSN Code","Status","Stock","Delete Status");
              break;
      }
        $objPHPExcel = new \PHPExcel();
        $objWorkSheet = $objPHPExcel->createSheet();
        $objPHPExcel->getSheet(0)->setTitle($reportTitle);
        $objPHPExcel->setActiveSheetIndex(0);
        $column = 'A';
        if($request->report == 'delivery'){
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells("G1:J1")
          ->mergeCells("K1:O1")
          ->mergeCells("P1:S1");
        $objPHPExcel->getActiveSheet()
          ->setCellValue('G1', 'Procurement sla')
          ->setCellValue('K1', 'Pickup sla')
          ->setCellValue('P1', 'Delivery sla');
        $objPHPExcel->getActiveSheet()
          ->getStyle('G1:J1')
          ->getFill()
          ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
          ->getStartColor()
          ->setARGB('FC852D');
        $objPHPExcel->getActiveSheet()
          ->getStyle('K1:O1')
          ->getFill()
          ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
          ->getStartColor()
          ->setARGB('4545FF');
        $objPHPExcel->getActiveSheet()
          ->getStyle('P1:S1')
          ->getFill()
          ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
          ->getStartColor()
          ->setARGB('FF5C5C');
        }
        $boldText = array(
            'font' => array(
                'bold' => true,
                'size'  => 20,
                'name'  => 'oblique'
            )
        );
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => \PHPExcel_Style_Border::BORDER_THIN
                ),
            )
        );
        if($request->report == 'logistic-accounting'){
          $objPHPExcel->setActiveSheetIndex(0)->mergeCells("A1:X1")
              ->mergeCells("Y1:AJ1")
              ->mergeCells("A2:P2")
              ->mergeCells("Q2:X2")
              ->mergeCells("Y2:AC2")
              ->mergeCells("AD2:AJ2");
          $objPHPExcel->getActiveSheet()
              ->setCellValue('A1', 'India Post')
              ->setCellValue('Y1', 'Agrosiaa Shipment')
              ->setCellValue('A2', 'Logistic Fields')
              ->setCellValue('Q2', 'Accounting Fields')
              ->setCellValue('Y2', 'Logistic Fields')
              ->setCellValue('AD2', 'Accounting Fields');
          $objPHPExcel->getActiveSheet()
              ->getStyle('A1:X1')->applyFromArray($styleArray,$boldText);
          $objPHPExcel->getActiveSheet()
              ->getStyle('Y1:AJ1')->applyFromArray($styleArray,$boldText);
          $objPHPExcel->getActiveSheet()
              ->getStyle('A2:P2')->applyFromArray($styleArray,$boldText);
          $objPHPExcel->getActiveSheet()
              ->getStyle('Q2:X2')->applyFromArray($styleArray,$boldText);
          $objPHPExcel->getActiveSheet()
              ->getStyle('Y2:AC2')->applyFromArray($styleArray,$boldText);
          $objPHPExcel->getActiveSheet()
              ->getStyle('AD2:AJ2')->applyFromArray($styleArray,$boldText);
          $objPHPExcel->getActiveSheet()
              ->getStyle('A3:AJ3')->applyFromArray($styleArray,$boldText);
        }
        if($request->report == 'settlement' && $this->userRoleType == 'seller'){
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells("D1:F1");
        $objPHPExcel->getActiveSheet()
          ->setCellValue('D1', 'TCS (% on Invoice amount)');
        $objPHPExcel->getActiveSheet()
          ->getStyle('D1:F1')
          ->getFill()
          ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
          ->getStartColor()
          ->setARGB('FC852D');
        }elseif ($this->userRoleType == 'superadmin' && $request->report == 'settlement')

        if($request->report == 'taxation'){
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells("W1:Y1");
        $objPHPExcel->getActiveSheet()
          ->setCellValue('W1', 'TCS (% on Invoice amount)');
        $objPHPExcel->getActiveSheet()
          ->getStyle('W1:Y1')
          ->getFill()
          ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
          ->getStartColor()
          ->setARGB('FC852D');
        }
        foreach ($rows as $row) {
            $objPHPExcel->getActiveSheet()->getRowDimension($rowNumber)->setRowHeight(-1);
            foreach ($row as $singleRow) {
                /* Align Center
                $objPHPExcel->getActiveSheet()
                    ->getStyle($objPHPExcel->getActiveSheet()->calculateWorksheetDimension())
                    ->getAlignment()
                    ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER)
                    ->setWrapText(true);
                /* Set Cell Width
                $objPHPExcel->getActiveSheet()->getColumnDimension($column)->setWidth(20);
                $objPHPExcel->getActiveSheet()->setCellValue($column . $rowNumber, $singleRow);
                $column++;
            }
            $column = 'A';
            $rowNumber++;
        }
        foreach($data as $key => $datavalues) {
            $columnForData = 0;
            foreach($datavalues as $datavalue => $value){
                /* Align Center
                $objPHPExcel->getActiveSheet()
                ->getStyle($objPHPExcel->getActiveSheet()->calculateWorksheetDimension())
                ->getAlignment()
                ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER)
                ->setWrapText(true);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columnForData, $rowIndex, $value);
                $columnForData++;
            }
            $rowIndex++;
        }
                            //Ps Campaign Order
            if($request->report == 'taxation' || $request->report == 'settlement'){
                $objWorkSheet = $objPHPExcel->createSheet();
                $objPHPExcel->getSheet(1)->setTitle($reportTitle1);
                $objPHPExcel->setActiveSheetIndex(1);
                // styling the sheet.
                $rowNumber = 2;
                $rowIndex = 3;
            foreach ($ps_row as $row) {
                $objPHPExcel->getActiveSheet()->getRowDimension($rowNumber)->setRowHeight(-1);
                foreach ($row as $singleRow) {
                    /* Align Center
                    $objPHPExcel->getActiveSheet()
                        ->getStyle($objPHPExcel->getActiveSheet()->calculateWorksheetDimension())
                        ->getAlignment()
                        ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                        ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER)
                        ->setWrapText(true);
                    /* Set Cell Width
                    $objPHPExcel->getActiveSheet()->getColumnDimension($column)->setWidth(20);
                    $objPHPExcel->getActiveSheet()->setCellValue($column . $rowNumber, $singleRow);
                    $column++;
                }
                $column = 'A';
                $rowNumber++;
            }
            foreach($Psdata as $key => $datavalues) {
                $columnForData = 0;
                foreach($datavalues as $datavalue => $value){
                   Align Center
                    $objPHPExcel->getActiveSheet()
                        ->getStyle($objPHPExcel->getActiveSheet()->calculateWorksheetDimension())
                        ->getAlignment()
                        ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                        ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER)
                        ->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columnForData, $rowIndex, $value);
                    $columnForData++;
                }
                $rowIndex++;
            }
         }

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
        $fileName = $name;
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment; filename=\"".$fileName."\"");
        ob_end_clean();
        $objWriter->save("php://output");
        exit();
        return redirect('operational/report/view');
    }catch(\Exception $e){
          $errorLog = [
              'request' => $request->all(),
              'action' => 'Report',
              'exception' => $e->getMessage()
          ];
          Log::critical(json_encode($errorLog));
          abort(500,$e->getMessage());
         }
    }*/
    public function generateReport(Request $request){
        try{
            $data = $Psdata = array();
            $roleId = Role::where('slug','seller')->pluck('id');
            $date = Carbon::parse($request->date);
            $startDate = $date->subDays(30);
            $requestedDate = date('d', strtotime($request->selected_date));
            $selectedDate = Carbon::parse($request->selected_date);
            if($requestedDate <= 05){
                $year = date('Y',strtotime($selectedDate->subMonth()));
                $month = date('m',strtotime($selectedDate->subMonth()));
                $date ='05';
                $start_date = Carbon::create($year,$month, $date);
                $selected_date =  date('Y-m-d', strtotime($request->selected_date .' +1 day'));
            }else{
                $year = $selectedDate->year;
                $month = $selectedDate->month;
                $date ='05';
                $start_date = Carbon::create($year,$month, $date);
                $selected_date =  date('Y-m-d', strtotime($request->selected_date .' +1 day'));
            }
            $vendorName = User::where('role_id',$roleId)->where('is_active','1')->select('first_name','last_name','id')->get()->toArray();
            for($i = 0 ; $i< count($vendorName); $i++){
                $vendorName[$i]['seller_id'] = Seller::where('user_id',$vendorName[$i]['id'])->pluck('id');
            }
            switch($request->period){
                case 'quarter-1' :
                    $start_time = date('01/'.substr($request->year, -2));
                    $end_time = date('03/'.substr($request->year, -2));
                    break;
                case 'quarter-2' :
                    $start_time = date('04/'.substr($request->year, -2));
                    $end_time = date('06/'.substr($request->year, -2));
                    break;
                case 'quarter-3' :
                    $start_time = date('07/'.substr($request->year, -2));
                    $end_time = date('09/'.substr($request->year, -2));
                    break;
                case 'quarter-4' :
                    $start_time = date('10/'.substr($request->year, -2));
                    $end_time = date('11/'.substr($request->year, -2));
                    break;

                default :
                    break;
            }
            $row = 0;
            $ps_count = 0;
            switch($request->report) {
                case 'taxation' :
                    $from_date = Carbon::parse($request->from_date);
                    $to_date = Carbon::parse($request->to_date);
                    if($this->userRoleType == 'seller'){
                        $sellerOrderIds = Order::where('seller_id',$this->seller->id)->lists('id');
                        $vendorSettlement = VendorSettleMent::whereIn('order_id',$sellerOrderIds)->whereBetween('order_complete_date', [$from_date, $to_date])->OrwhereBetween('rma_complete_date',[$from_date, $to_date])->with('order')->get()->toArray();
                    }elseif ($this->userRoleType == 'superadmin' || $this->userRoleType == 'accountadmin'){
                        $vendorSettlement = VendorSettleMent::whereBetween('order_complete_date', [$from_date, $to_date])->OrwhereBetween('rma_complete_date',[$from_date, $to_date])->with('order')->get()->toArray();
                    }
                    foreach ($vendorSettlement as $key => $value){
                        $paymentMethod=PaymentMethod::where('id',$value['order']['payment_method_id'])->first();
                        $sellerName = Seller::where('id',$value['order']['seller_id'])->pluck('company');
                        $invoice = Invoice::where('order_id',$value['order_id'])->first()->toArray();
                        $order = Order::where('id',$value['order_id'])->first();
                        $isPsFlag = ($order['is_ps_campaign']) ? true : false;
                        if(!$isPsFlag) {
                            $data[$row]['Order no / Return no'] = "AGR" . $this->getStructuredOrderId($order['id']);
                            $sellerAddress = json_decode($value['order']['seller_address']);
                            $customerAddress = json_decode($order->ordersCustomerInfo->shipping_address);
                            if (strtoupper($sellerAddress->state) != strtoupper($customerAddress->state)) {
                                $tax_igst_applied = true;
                            } else {
                                $tax_igst_applied = false;
                            }
                            if ($paymentMethod['slug'] == 'cod') {
                                $data[$row]['Invoice Number'] = "AGRCR" . $this->getStructuredOrderId($value['order_id']);
                            } else {
                                $data[$row]['Invoice Number'] = "AGR" . $this->getStructuredOrderId($value['order_id']);
                            }
                            $data[$row]['Order/return Complete Date'] = $value['order_complete_date'];
                            if ($value['order']['is_configurable'] == true) {
                                $area = $value['order']['length'] * $value['order']['width'];
                                $data[$row]['Base Price Excluding GST'] = ($value['order']['base_price'] - ($value['order']['base_price'] * ($value['order']['discount'] / 100))) * $area;
                                $taxID = Product::where('id', $order->product_id)->pluck('tax_id');
                                $data[$row]['Product GST'] = Tax::where('id', $taxID)->pluck('rate');
                                $data[$row]['Product GST Amount'] = Product::where('id', $order->product_id)->pluck('subtotal') * ($data[$row]['Product GST'] / 100);
                                $data[$row]['Qty Ordered/return By Customer'] = $value['order']['quantity'];
                                $data[$row]['Base Amount X Qty'] = ($data[$row]['Base Price Excluding GST']) * $data[$row]['Qty Ordered/return By Customer'];

                            } else {
                                $data[$row]['Base Price Excluding GST'] = $value['order']['base_price'] - ($value['order']['base_price'] * ($value['order']['discount'] / 100));
                                $taxID = Product::where('id', $order->product_id)->pluck('tax_id');
                                $data[$row]['Product GST'] = Tax::where('id', $taxID)->pluck('rate');
                                $data[$row]['Product GST Amount'] = Product::where('id', $order->product_id)->pluck('subtotal') * ($data[$row]['Product GST'] / 100);
                                $data[$row]['Qty Ordered/return By Customer'] = $value['order']['quantity'];
                                $data[$row]['Base Amount X Qty'] = $data[$row]['Base Price Excluding GST'] * $data[$row]['Qty Ordered/return By Customer'];
                            }
                            $data[$row]['Invoice Timestamp'] = $invoice['created_at'];
                            $data[$row]['Payment Mode'] = PaymentMethod::where('id', $value['order']['payment_method_id'])->pluck('name');
                            $data[$row]['Company Name'] = $sellerName;
                            $data[$row]['GST No'] = Seller::where('id', $value['order']['seller_id'])->pluck('gstin');
                            if ($value['order']['is_configurable'] == true) {
                                $area = $value['order']['length'] * $value['order']['width'];
                                $data[$row]['Invoice Amount'] = ($value['order']['discounted_price'] * $area) * $value['order']['quantity'];
                            } else {
                                $data[$row]['Invoice Amount'] = ($value['order']['discounted_price']) * $value['order']['quantity'];
                            }
                            $data[$row]['Commission Percent'] = $value['commission_percent'];
                            $data[$row]['Commission'] = ($value['commission_percent'] * $data[$row]['Base Amount X Qty']) / 100;
                            $data[$row]['GST % on Commission(%)'] = 18;
                            $data[$row]['GST on Commission'] = round(env('GST_PERCENT') * $data[$row]['Commission'], 2);
                            $data[$row]['Delivery Charges'] = $value['delivery_charges'];
                            $data[$row]['Logistic Percent(%)'] = $order['logistic_percent'];
                            $data[$row]['Logistics/Return Charges'] = ($data[$row]['Logistic Percent(%)'] * $data[$row]['Base Amount X Qty']) / 100;
                            $data[$row]['GST % on Logistic Amount(%)'] = 18;
                            $data[$row]['GST on Logistics'] = round(env('GST_PERCENT') * $data[$row]['Logistics/Return Charges'], 2);
                            $data[$row]['GST'] = $invoice['vat_rate'];
                            if ($order['tax_rate'] == 0) {
                                $data[$row]['TCS Percent(%)'] = '0';
                            } else {
                                $data[$row]['TCS Percent(%)'] = 1;
                            }
                            if ($tax_igst_applied) {
                                $data[$row]['CGST(0.5%)'] = '-';
                                $data[$row]['SGST(0.5%)'] = '-';
                                $data[$row]['IGST(1%)'] = $value['order_tcs_amount'];
                            } else {
                                $data[$row]['CGST(0.5%)'] = ($value['order_tcs_amount'] / 2);
                                $data[$row]['SGST(0.5%)'] = ($value['order_tcs_amount'] / 2);
                                if($data[$row]['CGST(0.5%)'] == 0 &&  $data[$row]['SGST(0.5%)'] == 0){
                                    $data[$row]['CGST(0.5%)'] = '0';
                                    $data[$row]['SGST(0.5%)'] = '0';
                                }
                                $data[$row]['IGST(1%)'] = '-';
                            }
                            $data[$row]['Settlement Amount'] = $value['order_vendor_settlement_amount'];
                            $data[$row]['Logistic Name'] = ShippingMethod::where('id', $value['order']['shipping_method_id'])->pluck('name');
                            $data[$row]['AWB No'] = $value['order']['consignment_number'];
                            $row++;
                            if ($value['rma_id'] != null) {
                                $data[$row]['Order no / Return no'] = "AGR" . $this->getStructuredOrderId($value['order_id']) . "R";
                                if ($paymentMethod['slug'] == 'cod') {
                                    $data[$row]['Invoice Number'] = "AGRCR" . $this->getStructuredOrderId($value['order_id']);
                                } else {
                                    $data[$row]['Invoice Number'] = "AGR" . $this->getStructuredOrderId($value['order_id']);
                                }
                                $data[$row]['Order/return Complete Date'] = $value['rma_complete_date'];
                                $data[$row]['Base Price Excluding GST'] = $data[$row]['Product GST'] = $data[$row]['Product GST Amount'] = null;
                                $rmaReturnQuantity = OrderRma::where('id', $value['rma_id'])->pluck('return_quantity');
                                $data[$row]['Qty Ordered/return By Customer'] = $rmaReturnQuantity;
                                $data[$row]['Base Amount X Qty'] = $data[$row]['Invoice Timestamp'] =  $data[$row]['Payment Mode'] = $data[$row]['Company Name'] = $data[$row]['GST No'] =$data[$row]['Invoice Amount'] =  $data[$row]['Commission Percent'] = $data[$row]['Commission'] = $data[$row]['GST % on Commission(%)'] = $data[$row]['GST on Commission'] = $data[$row]['Delivery Charges'] = null;
                                $data[$row]['Logistic Percent(%)'] = $order['logistic_percent'];
                                $data[$row]['Logistics/Return Charges'] = $value['return_logistics_charges'];
                                $data[$row]['GST % on Logistic Amount(%)'] = 18;
                                $data[$row]['GST on Logistics'] = round(env('GST_PERCENT') * $value['return_logistics_charges'], 2);
                                $data[$row]['GST'] = null;
                                if ($order['tax_rate'] == 0) {
                                    $data[$row]['TCS Percent(%)'] = '0';
                                } else {
                                    $data[$row]['TCS Percent(%)'] = 1;
                                }
                                if ($tax_igst_applied) {
                                    $data[$row]['CGST(0.5%)'] = '-';
                                    $data[$row]['SGST(0.5%)'] = '-';
                                    $data[$row]['IGST(1%)'] = $value['return_tcs_amount'];
                                } else {
                                    $data[$row]['CGST(0.5%)'] = ($value['return_tcs_amount'] / 2);
                                    $data[$row]['SGST(0.5%)'] = ($value['return_tcs_amount'] / 2);
                                    if($data[$row]['CGST(0.5%)'] == 0 &&  $data[$row]['SGST(0.5%)'] == 0){
                                        $data[$row]['CGST(0.5%)'] = '0';
                                        $data[$row]['SGST(0.5%)'] = '0';
                                    }
                                    $data[$row]['IGST(1%)'] = '-';
                                }
                                $data[$row]['Settlement Amount'] = $value['return_vendor_settlement_amount'];
                                $row++;
                            }
                        }else{
                            $Psdata[$ps_count]['Order/return Complete Date'] = "AGR".$this->getStructuredOrderId($order['id']);  $sellerAddress = json_decode($value['order']['seller_address']);
                            $customerAddress = json_decode($order->ordersCustomerInfo->shipping_address);
                            if(strtoupper($sellerAddress->state) != strtoupper($customerAddress->state)){
                                $tax_igst_applied = true;
                            }else{
                                $tax_igst_applied = false;
                            }
                            if($paymentMethod['slug'] == 'cod'){
                                $Psdata[$ps_count]['Invoice Number']="AGRCR".$this->getStructuredOrderId($value['order_id']);
                            }else{
                                $Psdata[$ps_count]['Invoice Number']="AGR".$this->getStructuredOrderId($value['order_id']);
                            }
                            $Psdata[$ps_count]['Order/return Complete Date'] = $value['order_complete_date'];

                            if($value['order']['is_configurable'] == true){
                                $area = $value['order']['length'] * $value['order']['width'];
                                $Psdata[$ps_count]['Base Price Excluding GST'] = ($value['order']['base_price'] - ($value['order']['base_price'] * ($value['order']['discount'] / 100 ))) * $area;
                                $taxID = Product::where('id',$order->product_id)->pluck('tax_id');
                                $Psdata[$ps_count]['Product GST'] = Tax::where('id',$taxID)->pluck('rate');
                                $Psdata[$ps_count]['Product GST Amount'] = Product::where('id',$order->product_id)->pluck('subtotal') * ($Psdata[$ps_count]['Product GST']/100);
                                $Psdata[$ps_count]['Qty Ordered/return By Customer'] = $value['order']['quantity'];
                                $Psdata[$ps_count]['Base Amount X Qty'] = ($Psdata[$ps_count]['Base Price Excluding GST'] ) * $Psdata[$ps_count]['Qty Ordered/return By Customer'];

                            }else{
                                $Psdata[$ps_count]['Base Price Excluding GST'] = $value['order']['base_price'] - ($value['order']['base_price'] * ($value['order']['discount'] / 100 ));
                                $taxID = Product::where('id',$order->product_id)->pluck('tax_id');
                                $Psdata[$ps_count]['Product GST'] = Tax::where('id',$taxID)->pluck('rate');
                                $Psdata[$ps_count]['Product GST Amount'] = Product::where('id',$order->product_id)->pluck('subtotal') * ($Psdata[$ps_count]['Product GST']/100);
                                $Psdata[$ps_count]['Qty Ordered/return By Customer'] = $value['order']['quantity'];
                                $Psdata[$ps_count]['Base Amount X Qty'] = $Psdata[$ps_count]['Base Price Excluding GST'] * $Psdata[$ps_count]['Qty Ordered/return By Customer'];
                            }
                            $Psdata[$ps_count]['Invoice Timestamp'] = $invoice['created_at'];
                            $Psdata[$ps_count]['Company Name'] = $sellerName;
                            if($value['order']['is_configurable'] == true){
                                $area = $value['order']['length'] * $value['order']['width'];
                                $Psdata[$ps_count]['Invoice Amount'] = ($value['order']['discounted_price'] * $area) * $value['order']['quantity'];
                            }else{
                                $Psdata[$ps_count]['Invoice Amount'] = ($value['order']['discounted_price']) * $value['order']['quantity'];
                            }
                            $Psdata[$ps_count]['Commission Percent'] = $value['commission_percent'] ;
                            $Psdata[$ps_count]['Commission'] = ($value['commission_percent'] * $Psdata[$ps_count]['Base Amount X Qty'])/100;
                            $Psdata[$ps_count]['GST % on Commission(%)'] = 18;
                            $Psdata[$ps_count]['GST on Commission'] = round(env('GST_PERCENT') * $Psdata[$ps_count]['Commission'] , 2);
                            $Psdata[$ps_count]['Logistic Percent(%)'] = $order['logistic_percent'];
                            $Psdata[$ps_count]['Logistics/Return Charges'] = ($Psdata[$ps_count]['Logistic Percent(%)'] * $Psdata[$ps_count]['Base Amount X Qty'])/100;
                            $Psdata[$ps_count]['GST % on Logistic Amount(%)'] = 18;
                            $Psdata[$ps_count]['GST on Logistics'] = round(env('GST_PERCENT') * $Psdata[$ps_count]['Logistics/Return Charges'] , 2);
                            $Psdata[$ps_count]['GST'] = $invoice['vat_rate'];
                            if($order['tax_rate'] == 0){
                                $Psdata[$ps_count]['TCS Percent(%)'] = '0';
                            }else{
                                $Psdata[$ps_count]['TCS Percent(%)'] = 1;
                            }
                            if($tax_igst_applied){
                                $Psdata[$ps_count]['CGST(0.5%)'] = '-' ;
                                $Psdata[$ps_count]['SGST(0.5%)'] = '-';
                                $Psdata[$ps_count]['IGST(1%)'] = $value['order_tcs_amount'];
                            }else{
                                $Psdata[$ps_count]['CGST(0.5%)'] = ($value['order_tcs_amount']/2);
                                $Psdata[$ps_count]['SGST(0.5%)'] = ($value['order_tcs_amount']/2);
                                if( $Psdata[$ps_count]['CGST(0.5%)'] == 0 &&   $Psdata[$ps_count]['SGST(0.5%)'] == 0){
                                    $Psdata[$ps_count]['CGST(0.5%)'] = '0';
                                    $Psdata[$ps_count]['SGST(0.5%)'] = '0';
                                }
                                $Psdata[$ps_count]['IGST(1%)'] = '-';
                            }
                            $Psdata[$ps_count]['Vendor Campaign Charge'] = $order['vendor_campaign_charges'];
                            $Psdata[$ps_count]['AGR Campaign Charges'] = $order['agrosiaa_campaign_charges'];
                            $Psdata[$ps_count]['Settlement Amount (Receivable to AGR)'] = $value['order_vendor_settlement_amount'];
                            $Psdata[$ps_count]['Logistic Name'] = ShippingMethod::where('id',$value['order']['shipping_method_id'])->pluck('name');
                            $ps_count++;
                        }
                    }
                    $curr_date = Carbon::now();
                    Excel::create($request->report."_".$curr_date, function($excel) use($data,$Psdata) {
                        $excel->getDefaultStyle()
                            ->getAlignment()
                            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $excel->sheet("Taxation", function($sheet) use($data) {
                            $sheet->setAutoSize(true);
                            $sheet->setAllBorders('thin');
                            $sheet->cells('A1:AD1', function ($cells) {
                                $cells->setAlignment('center');
                                $cells->setFontWeight('bold');
                                $cells->setFontSize(13);
                            });
                            $sheet->fromArray($data);
                            for( $intRowNumber = 1; $intRowNumber <= count($data) + 1; $intRowNumber++){
                                $sheet->setSize('A' . $intRowNumber, 25, 18);
                                $sheet->setSize('B' . $intRowNumber, 25, 18);
                                $sheet->setSize('C' . $intRowNumber, 25, 18);
                                $sheet->setSize('D' . $intRowNumber, 25, 18);
                                $sheet->setSize('E' . $intRowNumber, 25, 18);
                                $sheet->setSize('F' . $intRowNumber, 25, 18);
                                $sheet->setSize('G' . $intRowNumber, 25, 18);
                                $sheet->setSize('H' . $intRowNumber, 25, 18);
                                $sheet->setSize('I' . $intRowNumber, 25, 18);
                                $sheet->setSize('J' . $intRowNumber, 25, 18);
                                $sheet->setSize('K' . $intRowNumber, 25, 18);
                                $sheet->setSize('L' . $intRowNumber, 25, 18);
                                $sheet->setSize('M' . $intRowNumber, 25, 18);
                                $sheet->setSize('N' . $intRowNumber, 25, 18);
                                $sheet->setSize('O' . $intRowNumber, 25, 18);
                                $sheet->setSize('P' . $intRowNumber, 25, 18);
                                $sheet->setSize('Q' . $intRowNumber, 25, 18);
                                $sheet->setSize('R' . $intRowNumber, 25, 18);
                                $sheet->setSize('S' . $intRowNumber, 25, 18);
                                $sheet->setSize('T' . $intRowNumber, 25, 18);
                                $sheet->setSize('U' . $intRowNumber, 25, 18);
                                $sheet->setSize('V' . $intRowNumber, 25, 18);
                                $sheet->setSize('W' . $intRowNumber, 25, 18);
                                $sheet->setSize('X' . $intRowNumber, 25, 18);
                                $sheet->setSize('Y' . $intRowNumber, 25, 18);
                                $sheet->setSize('Z' . $intRowNumber, 25, 18);
                                $sheet->setSize('AA' . $intRowNumber, 25, 18);
                                $sheet->setSize('AB' . $intRowNumber, 25, 18);
                                $sheet->setSize('AC' . $intRowNumber, 25, 18);
                                $sheet->setSize('AD' . $intRowNumber, 25, 18);
                            }
                        });
                        $excel->sheet("PS Campaign", function($sheet) use($Psdata) {
                            $sheet->setAutoSize(true);
                            $sheet->setAllBorders('thin');
                            $sheet->cells('A1:AA1', function ($cells) {
                                $cells->setAlignment('center');
                                $cells->setFontWeight('bold');
                                $cells->setFontSize(13);
                            });
                            $sheet->fromArray($Psdata);
                            for( $intRowNumber = 1; $intRowNumber <= count($Psdata) + 1; $intRowNumber++){
                                $sheet->setSize('A' . $intRowNumber, 25, 18);
                                $sheet->setSize('B' . $intRowNumber, 25, 18);
                                $sheet->setSize('C' . $intRowNumber, 25, 18);
                                $sheet->setSize('D' . $intRowNumber, 25, 18);
                                $sheet->setSize('E' . $intRowNumber, 25, 18);
                                $sheet->setSize('F' . $intRowNumber, 25, 18);
                                $sheet->setSize('G' . $intRowNumber, 25, 18);
                                $sheet->setSize('H' . $intRowNumber, 25, 18);
                                $sheet->setSize('I' . $intRowNumber, 25, 18);
                                $sheet->setSize('J' . $intRowNumber, 25, 18);
                                $sheet->setSize('K' . $intRowNumber, 25, 18);
                                $sheet->setSize('L' . $intRowNumber, 25, 18);
                                $sheet->setSize('M' . $intRowNumber, 25, 18);
                                $sheet->setSize('N' . $intRowNumber, 25, 18);
                                $sheet->setSize('O' . $intRowNumber, 25, 18);
                                $sheet->setSize('P' . $intRowNumber, 25, 18);
                                $sheet->setSize('Q' . $intRowNumber, 25, 18);
                                $sheet->setSize('R' . $intRowNumber, 25, 18);
                                $sheet->setSize('S' . $intRowNumber, 25, 18);
                                $sheet->setSize('T' . $intRowNumber, 25, 18);
                                $sheet->setSize('U' . $intRowNumber, 25, 18);
                                $sheet->setSize('V' . $intRowNumber, 25, 18);
                                $sheet->setSize('W' . $intRowNumber, 25, 18);
                                $sheet->setSize('X' . $intRowNumber, 25, 18);
                                $sheet->setSize('Y' . $intRowNumber, 25, 18);
                                $sheet->setSize('Z' . $intRowNumber, 25, 18);
                                $sheet->setSize('AA' . $intRowNumber, 25, 18);
                            }
                        });
                    })->export('xls');
                    break;

                case 'settlement' :
                    $from_date = Carbon::parse($request->from_date);
                    $to_date = Carbon::parse($request->to_date);
                    if($this->userRoleType == 'seller'){
                        $sellerOrderIds = Order::where('seller_id',$this->seller->id)->lists('id');
                        $vendorSettlement = VendorSettleMent::whereIn('order_id',$sellerOrderIds)->whereBetween('order_complete_date', [$from_date, $to_date])->OrwhereBetween('rma_complete_date',[$from_date, $to_date])->with('order')->get()->toArray();
                    }elseif ($this->userRoleType == 'superadmin' || $this->userRoleType == 'accountadmin'){
                        $vendorSettlement = VendorSettleMent::whereBetween('order_complete_date', [$from_date, $to_date])->OrwhereBetween('rma_complete_date',[$from_date, $to_date])->with('order')->get()->toArray();
                    }
                    foreach($vendorSettlement as $key => $value){
                        $order = Order::where('id',$value['order_id'])->first();
                        if($order['is_ps_campaign'] == null){
                            if($this->userRoleType == 'superadmin' || $this->userRoleType == 'accountadmin'){
                                $SellerName = Seller::where('id',$value['order']['seller_id'])->pluck('company');
                                $data[$row]['Company Name'] = $SellerName;
                                $data[$row]['GST No'] = Seller::where('id',$value['order']['seller_id'])->pluck('gstin');;
                            }
                            $sellerAddress = json_decode($order->seller_address);
                            $customerAddress = json_decode($order->ordersCustomerInfo->shipping_address);
                            if(strtoupper($sellerAddress->state) != strtoupper($customerAddress->state)){
                                $tax_igst_applied = true;
                            }else{
                                $tax_igst_applied = false;
                            }
                            $data[$row]['Order/Return completion timestamp'] = $value['order_complete_date'];
                            $data[$row]['Order no/Return no'] = "AGR".$this->getStructuredOrderId($value['order_id']);
                            if($value['order']['is_configurable'] == true){
                                $area = $value['order']['length'] * $value['order']['width'];
                                $data[$row]['Invoice Total'] = (($value['order']['discounted_price'] * $area)* $value['order']['quantity']);
                            }else{
                                $data[$row]['Invoice Total'] = ($value['order']['discounted_price'] * $value['order']['quantity']);
                            }
                            $taxID = Product::where('id',$order->product_id)->pluck('tax_id');
                            $data[$row]['Product GST'] = Tax::where('id',$taxID)->pluck('rate');
                            $data[$row]['Product GST Amount'] = Product::where('id',$order->product_id)->pluck('subtotal') * ($data[$row]['Product GST']/100);
                            if($tax_igst_applied){
                                $data[$row]['CGST(0.5%)'] = '-' ;
                                $data[$row]['SGST(0.5%)'] = '-';
                                $data[$row]['IGST(1%)'] = $value['order_tcs_amount'];
                            }else{
                                $data[$row]['CGST(0.5%)'] = $value['order_tcs_amount']/2;
                                $data[$row]['SGST(0.5%)'] = $value['order_tcs_amount']/2;
                                if($data[$row]['CGST(0.5%)'] == 0 &&  $data[$row]['SGST(0.5%)'] == 0){
                                    $data[$row]['CGST(0.5%)'] = '0';
                                    $data[$row]['SGST(0.5%)'] = '0';
                                }
                                $data[$row]['IGST(1%)'] = '-';
                            }

                            $data[$row]['Vendor Settlement Amount'] = $value['order_vendor_settlement_amount'];
                            $row++;
                            if($value['rma_id'] != null){
                                if($this->userRoleType == 'superadmin' || $this->userRoleType == 'accountadmin'){
                                    $data[$row]['Company Name']= "";
                                    $data[$row]['GST No']= "";
                                }
                                $data[$row]['Order/Return completion timestamp'] = $value['rma_complete_date'];
                                $data[$row]['Order no/Return no'] = "AGR".$this->getStructuredOrderId($value['order_id'])."R";
                                $data[$row]['Invoice Total'] = null;
                                $data[$row]['Product GST'] = $data[$row]['Product GST Amount'] = null;
                                if($tax_igst_applied){
                                    $data[$row]['CGST(0.5%)'] = '-' ;
                                    $data[$row]['SGST(0.5%)'] = '-';
                                    $data[$row]['IGST(1%)'] = $value['return_tcs_amount'];
                                }else{
                                    $data[$row]['CGST(0.5%)'] = $value['return_tcs_amount']/2;
                                    $data[$row]['SGST(0.5%)'] = $value['return_tcs_amount']/2;
                                    if($data[$row]['CGST(0.5%)'] == 0 &&  $data[$row]['SGST(0.5%)'] == 0){
                                        $data[$row]['CGST(0.5%)'] = '0';
                                        $data[$row]['SGST(0.5%)'] = '0';
                                    }
                                    $data[$row]['IGST(1%)'] = '-';
                                }
                                $data[$row]['Vendor Settlement Amount'] = $value['return_vendor_settlement_amount'];
                                $row++;
                            }
                        }else{
                            if($this->userRoleType == 'superadmin' || $this->userRoleType == 'accountadmin'){
                                $SellerName = Seller::where('id',$value['order']['seller_id'])->pluck('company');
                                $Psdata[$ps_count]['Company Name'] = $SellerName;
                                $Psdata[$ps_count]['GST No'] = Seller::where('id',$value['order']['seller_id'])->pluck('gstin');;
                            }
                            $sellerAddress = json_decode($order->seller_address);
                            $customerAddress = json_decode($order->ordersCustomerInfo->shipping_address);
                            if(strtoupper($sellerAddress->state) != strtoupper($customerAddress->state)){
                                $tax_igst_applied = true;
                            }else{
                                $tax_igst_applied = false;
                            }
                            $Psdata[$ps_count]['Order/Return completion timestamp'] = $value['order_complete_date'];
                            $Psdata[$ps_count]['Order no/Return no'] = "AGR".$this->getStructuredOrderId($value['order_id']);
                            if($value['order']['is_configurable'] == true){
                                $area = $value['order']['length'] * $value['order']['width'];
                                $Psdata[$ps_count]['Invoice Total'] = (($value['order']['discounted_price'] * $area)* $value['order']['quantity']);
                            }else{
                                $Psdata[$ps_count]['Invoice Total'] = ($value['order']['discounted_price'] * $value['order']['quantity']);
                            }
                            $taxID = Product::where('id',$order->product_id)->pluck('tax_id');
                            $Psdata[$ps_count]['Product GST'] = Tax::where('id',$taxID)->pluck('rate');
                            $Psdata[$ps_count]['Product GST Amount'] = Product::where('id',$order->product_id)->pluck('subtotal') * ($Psdata[$ps_count]['Product GST']/100);
                            if($tax_igst_applied){
                                $Psdata[$ps_count]['CGST(0.5%)'] = '-' ;
                                $Psdata[$ps_count]['SGST(0.5%)'] = '-';
                                $Psdata[$ps_count]['IGST(1%)'] = $value['order_tcs_amount'];
                            }else{
                                $Psdata[$ps_count]['CGST(0.5%)'] = $value['order_tcs_amount']/2;
                                $Psdata[$ps_count]['SGST(0.5%)'] = $value['order_tcs_amount']/2;
                                if( $Psdata[$ps_count]['CGST(0.5%)'] == 0 &&   $Psdata[$ps_count]['SGST(0.5%)'] == 0){
                                    $Psdata[$ps_count]['CGST(0.5%)'] = '0';
                                    $Psdata[$ps_count]['SGST(0.5%)'] = '0';
                                }
                                $Psdata[$ps_count]['IGST(1%)'] = '-';
                            }
                            $Psdata[$ps_count]['Vendor Settlement Amount'] = $value['order_vendor_settlement_amount'];
                            $ps_count++;
                        }
                    }
                    $curr_date = Carbon::now();
                    Excel::create($request->report."_".$curr_date, function($excel) use($data,$Psdata) {
                        $excel->getDefaultStyle()
                            ->getAlignment()
                            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $excel->sheet("Settlement", function($sheet) use($data) {
                            $sheet->setAutoSize(true);
                            $sheet->setAllBorders('thin');
                            $sheet->cells('A1:AD1', function ($cells) {
                                $cells->setAlignment('center');
                                $cells->setFontWeight('bold');
                                $cells->setFontSize(13);

                            });
                            $sheet->fromArray($data);
                            for( $intRowNumber = 1; $intRowNumber <= count($data) + 1; $intRowNumber++){
                                $sheet->setSize('A' . $intRowNumber, 25, 18);
                                $sheet->setSize('B' . $intRowNumber, 25, 18);
                                $sheet->setSize('C' . $intRowNumber, 25, 18);
                                $sheet->setSize('D' . $intRowNumber, 25, 18);
                                $sheet->setSize('E' . $intRowNumber, 25, 18);
                                $sheet->setSize('F' . $intRowNumber, 25, 18);
                                $sheet->setSize('G' . $intRowNumber, 25, 18);
                                $sheet->setSize('H' . $intRowNumber, 25, 18);
                                $sheet->setSize('I' . $intRowNumber, 25, 18);
                                $sheet->setSize('J' . $intRowNumber, 25, 18);
                                $sheet->setSize('K' . $intRowNumber, 25, 18);
                                $sheet->setSize('L' . $intRowNumber, 25, 18);
                                $sheet->setSize('M' . $intRowNumber, 25, 18);
                                $sheet->setSize('N' . $intRowNumber, 25, 18);
                                $sheet->setSize('O' . $intRowNumber, 25, 18);
                                $sheet->setSize('P' . $intRowNumber, 25, 18);
                                $sheet->setSize('Q' . $intRowNumber, 25, 18);
                                $sheet->setSize('R' . $intRowNumber, 25, 18);
                                $sheet->setSize('S' . $intRowNumber, 25, 18);
                                $sheet->setSize('T' . $intRowNumber, 25, 18);
                                $sheet->setSize('U' . $intRowNumber, 25, 18);
                                $sheet->setSize('V' . $intRowNumber, 25, 18);
                                $sheet->setSize('W' . $intRowNumber, 25, 18);
                                $sheet->setSize('X' . $intRowNumber, 25, 18);
                                $sheet->setSize('Y' . $intRowNumber, 25, 18);
                                $sheet->setSize('Z' . $intRowNumber, 25, 18);
                                $sheet->setSize('AA' . $intRowNumber, 25, 18);
                                $sheet->setSize('AB' . $intRowNumber, 25, 18);
                                $sheet->setSize('AC' . $intRowNumber, 25, 18);
                                $sheet->setSize('AD' . $intRowNumber, 25, 18);
                            }
                        });
                        $excel->sheet("PS Campaign", function($sheet) use($Psdata) {
                            $sheet->setAutoSize(true);
                            $sheet->setAllBorders('thin');
                            $sheet->cells('A1:AD1', function ($cells) {
                                $cells->setAlignment('center');
                                $cells->setFontWeight('bold');
                                $cells->setFontSize(13);
                            });
                            $sheet->fromArray($Psdata);
                            for( $intRowNumber = 1; $intRowNumber <= count($Psdata) + 1; $intRowNumber++){
                                $sheet->setSize('A' . $intRowNumber, 25, 18);
                                $sheet->setSize('B' . $intRowNumber, 25, 18);
                                $sheet->setSize('C' . $intRowNumber, 25, 18);
                                $sheet->setSize('D' . $intRowNumber, 25, 18);
                                $sheet->setSize('E' . $intRowNumber, 25, 18);
                                $sheet->setSize('F' . $intRowNumber, 25, 18);
                                $sheet->setSize('G' . $intRowNumber, 25, 18);
                                $sheet->setSize('H' . $intRowNumber, 25, 18);
                                $sheet->setSize('I' . $intRowNumber, 25, 18);
                                $sheet->setSize('J' . $intRowNumber, 25, 18);
                                $sheet->setSize('K' . $intRowNumber, 25, 18);
                                $sheet->setSize('L' . $intRowNumber, 25, 18);
                                $sheet->setSize('M' . $intRowNumber, 25, 18);
                                $sheet->setSize('N' . $intRowNumber, 25, 18);
                                $sheet->setSize('O' . $intRowNumber, 25, 18);
                                $sheet->setSize('P' . $intRowNumber, 25, 18);
                                $sheet->setSize('Q' . $intRowNumber, 25, 18);
                                $sheet->setSize('R' . $intRowNumber, 25, 18);
                                $sheet->setSize('S' . $intRowNumber, 25, 18);
                                $sheet->setSize('T' . $intRowNumber, 25, 18);
                                $sheet->setSize('U' . $intRowNumber, 25, 18);
                                $sheet->setSize('V' . $intRowNumber, 25, 18);
                                $sheet->setSize('W' . $intRowNumber, 25, 18);
                                $sheet->setSize('X' . $intRowNumber, 25, 18);
                                $sheet->setSize('Y' . $intRowNumber, 25, 18);
                                $sheet->setSize('Z' . $intRowNumber, 25, 18);
                                $sheet->setSize('AA' . $intRowNumber, 25, 18);
                                $sheet->setSize('AB' . $intRowNumber, 25, 18);
                                $sheet->setSize('AC' . $intRowNumber, 25, 18);
                                $sheet->setSize('AD' . $intRowNumber, 25, 18);
                            }
                        });
                    })->export('xls');
                    break;

                case 'out-stock':
                    if($request->vendor == 0){
                        $sellerDetail = Product::whereNotNull('out_of_stock_date')->with('brand')->get()->toArray();
                    }else{
                        $sellerDetail = Product::where('seller_id',$request->vendor)->whereNotNull('out_of_stock_date')->with('brand')->get()->toArray();
                    }
                    $cancel_id = OrderStatus::where('slug','back_ordered')->pluck('id');
                    foreach($sellerDetail as $key => $value){
                        $data[$row]['Product Name'] = ucfirst($value['product_name']);
                        $data[$row]['Product SKU'] = ucfirst($value['item_based_sku']);
                        $data[$row]['Brand'] = ucfirst($value['brand']['name']);
                        $category = ProductCategoryRelation::where('product_id',$value['id'])->with('CategoryProductRel')->first()->toArray();
                        $data[$row]['Item Head'] = ucfirst($category['category_product_rel']['name']);
                        $data[$row]['Vendor Id'] = $value['seller_id'];
                        for($iterator = 0 ; $iterator < count($vendorName) ; $iterator++){
                            if($value['seller_id'] == $vendorName[$iterator]['seller_id']){
                                $data[$row]['Vendor Name'] = ucwords($vendorName[$iterator]['first_name']." ".$vendorName[$iterator]['last_name']);
                            }
                        }
                        $data[$row]['Out of Stock Since'] = $value['out_of_stock_date'];
                        $order_id = Order::where('product_id',$value['id'])->where('order_status_id',$cancel_id)->orderBy('id','desc')->pluck('id');
                        $cancel_reason_id = OrderHistory::where('order_id',$order_id)->where('order_status_id',$cancel_id)->pluck('customer_cancel_reasons_id');
                        $reason = CustomerCancelReasons::where('id',$cancel_reason_id)->pluck('reason');
                        $data[$row]['Out of Stock Reason'] = $reason;
                        $row++;
                    }
                    $curr_date = Carbon::now();
                    Excel::create("Out_of_stock_report"."_".$curr_date, function($excel) use($data) {
                        $excel->getDefaultStyle()
                            ->getAlignment()
                            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $excel->sheet("Out of Stock Report", function($sheet) use($data) {
                            $sheet->setAutoSize(true);
                            $sheet->setAllBorders('thin');
                            $sheet->cells('A1:H1', function ($cells) {
                                $cells->setAlignment('center');
                                $cells->setFontWeight('bold');
                                $cells->setFontSize(13);
                            });
                            $sheet->fromArray($data);
                            for( $intRowNumber = 1; $intRowNumber <= count($data) + 1; $intRowNumber++){
                                $sheet->setSize('A' . $intRowNumber, 25, 18);
                                $sheet->setSize('B' . $intRowNumber, 25, 18);
                                $sheet->setSize('C' . $intRowNumber, 25, 18);
                                $sheet->setSize('D' . $intRowNumber, 25, 18);
                                $sheet->setSize('E' . $intRowNumber, 25, 18);
                                $sheet->setSize('F' . $intRowNumber, 25, 18);
                                $sheet->setSize('G' . $intRowNumber, 25, 18);
                                $sheet->setSize('H' . $intRowNumber, 25, 18);
                            }
                        });
                    })->export('xls');
                    break;

                case 'return' :
                    if($request->status != 'all'){
                        $rmaStatus = RmaStatus::where('slug',$request->status)->lists('id')->toArray();
                    }else{
                        $rmaStatus = RmaStatus::whereNotIn('slug', ['rejected','canceled'])->lists('id')->toArray();
                    }
                    if($request->vendor == 0){
                        $orderRma = OrderRma::wherein('rma_status_id',$rmaStatus)->whereBetween('created_at',[ $startDate , $request->date ])->with('rmaReason','rmaStatus','order')->get()->toArray();
                    }else{
                        $selectedVendorOrders = Order::where('seller_id',$request->vendor)->lists('id');
                        $orderRma = OrderRma::wherein('order_id',$selectedVendorOrders)
                            ->wherein('rma_status_id',$rmaStatus)
                            ->whereBetween('created_at',[ $startDate , $request->date ])
                            ->orderBy('created_at','desc')
                            ->with('rmaReason','rmaStatus','order')->get()->toArray();
                    }
                    foreach($orderRma as $key => $value){
                        $data[$row]['Return Id'] = "AGR".$this->getStructuredOrderId($value['order_id'])."R";
                        $data[$row]['Return Date'] = $value['created_at'];
                        $data[$row]['Return Status'] = $value['rma_status']['status'];
                        $data[$row]['Return Reason'] = $value['rma_reason']['name'];
                        $data[$row]['Product Name'] = ucwords($value['product_name']);
                        $data[$row]['Quantity'] = $value['return_quantity'];
                        $data[$row]['Vendor Id'] = $value['order']['seller_id'];
                        for($iterator = 0 ; $iterator < count($vendorName) ; $iterator++){
                            if($data[$row]['Vendor Id'] == $vendorName[$iterator]['seller_id']){
                                $data[$row]['Vendor Name'] = ucwords($vendorName[$iterator]['first_name']." ".$vendorName[$iterator]['last_name']);
                            }
                        }
                        $brand = Product::where('id',$value['order']['product_id'])->with('brand')->first()->toArray();
                        $data[$row]['Brand'] = ucfirst($brand['brand']['name']);
                        $data[$row]['Customer ID'] = Customer::where('id',$value['order']['customer_id'])->pluck('user_id');
                        $data[$row]['Customer Mobile'] = User::where('id',$data[$row]['Customer ID'])->pluck('mobile');
                        $row++;
                    }
                    $curr_date = Carbon::now();
                    Excel::create("return_report"."_".$curr_date, function($excel) use($data) {
                        $excel->getDefaultStyle()
                            ->getAlignment()
                            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $excel->sheet("Return Report", function($sheet) use($data) {
                            $sheet->setAutoSize(true);
                            $sheet->setAllBorders('thin');
                            $sheet->cells('A1:K1', function ($cells) {
                                $cells->setAlignment('center');
                                $cells->setFontWeight('bold');
                                $cells->setFontSize(13);
                            });
                            $sheet->fromArray($data);
                            for( $intRowNumber = 1; $intRowNumber <= count($data) + 1; $intRowNumber++){
                                $sheet->setSize('A' . $intRowNumber, 25, 18);
                                $sheet->setSize('B' . $intRowNumber, 25, 18);
                                $sheet->setSize('C' . $intRowNumber, 25, 18);
                                $sheet->setSize('D' . $intRowNumber, 25, 18);
                                $sheet->setSize('E' . $intRowNumber, 25, 18);
                                $sheet->setSize('F' . $intRowNumber, 25, 18);
                                $sheet->setSize('G' . $intRowNumber, 25, 18);
                                $sheet->setSize('H' . $intRowNumber, 25, 18);
                                $sheet->setSize('I' . $intRowNumber, 25, 18);
                                $sheet->setSize('J' . $intRowNumber, 25, 18);
                                $sheet->setSize('K' . $intRowNumber, 25, 18);
                            }
                        });
                    })->export('xls');
                    break;

                case 'delivery':
                    $orderStatusId = OrderStatus::where('slug','complete')->pluck('id');
                    $orderDetails = Order::where('order_status_id',$orderStatusId)
                        ->whereBetween('created_at',[ $startDate , $request->date ])
                        ->orderBy('created_at','desc')
                        ->with('DeliveryMethod','orderHistory','invoice')->orderBy('id')->get()->toArray();
                    foreach($orderDetails as $key => $value){
                        $data[$row]['Order ID'] = "AGR".$this->getStructuredOrderId($value['id']);
                        $data[$row]['Order Time Stamp'] = $value['created_at'];
                        $data[$row]['Invoice Date'] = $value['invoice']['created_at'];
                        $data[$row]['Delivery type'] = $value['delivery_method']['name'];
                        $data[$row]['Vendor Id'] = $value['seller_id'];
                        for($iterator = 0 ; $iterator < count($vendorName) ; $iterator++){
                            if($value['seller_id'] == $vendorName[$iterator]['seller_id']){
                                $data[$row]['Vendor Name'] = ucwords($vendorName[$iterator]['first_name']." ".$vendorName[$iterator]['last_name']);
                            }
                        }
                        $data[$row]['Dispatch date(vendor)'] = $value['order_history'][2]['created_at'];
                        $data[$row]['Expected Dispatch date'] = $value['dispatch_date'];
                        $data[$row]['Procurement sla breach'] = ($value['procurement_sla_breach_vendor'] == true) ? "Yes" : "No";
                        if($data[$row]['Dispatch date(vendor)'] != null){
                            $delay_hours = $this->timeDelay($value['dispatch_date'],$data[$row]['Dispatch date(vendor)']);
                            $data[$row]['Delay hours'] = $delay_hours->format("%R%a")."D ".$delay_hours->h."hr ".$delay_hours->i."m";
                        }else{
                            $data[$row]['Delay hours'] = null;
                        }
                        $data[$row]['Pickup date Ack'] = $value['notify_mark_as_pick_up_time_vendor'];
                        $data[$row]['Pickup notification'] = $value['notify_pick_up_time_shipment'];
                        $data[$row]['Estimated pickup date'] = $value['pick_up_date'];
                        $data[$row]['Pickup sla breach'] = ($value['pick_up_sla_breach_shipment'] == true) ? "Yes" : "No";
                        if($data[$row]['Pickup notification'] != null){
                            $delay_hrs_pick = $this->timeDelay($data[$row]['Estimated pickup date'],$data[$row]['Pickup notification']);
                            $data[$row]['Delay hours'] = $delay_hrs_pick->format("%R%a")."D ".$delay_hrs_pick->h."hr ".$delay_hrs_pick->i."m";
                        }else{
                            $data[$row]['Delay hours'] = null;
                        }
                        $data[$row]['Order completion date'] = $value['created_at'];
                        $data[$row]['Estimated delivery date(on or before)'] = $value['delivery_date'];
                        $delivery_time = $value['delivery_date']."11:59:00";
                        $data[$row]['Delivery sla breach'] = ($value['delivery_sla_breach_shipment'] == true) ? "Yes" : "No";
                        $delay_hrs_delivery = $this->timeDelay($delivery_time,$data[$row]['Order completion date']);
                        $data[$row]['Delay hours'] = $delay_hrs_delivery->format("%R%a")."D ".$delay_hrs_delivery->h."hr ".$delay_hrs_delivery->i."m" ;
                        $row++;
                    }
                    $curr_date = Carbon::now();
                    Excel::create("Delivery_report"."_".$curr_date, function($excel) use($data) {
                        $excel->getDefaultStyle()
                            ->getAlignment()
                            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $excel->sheet("Delivery Report", function($sheet) use($data) {
                            $sheet->setAutoSize(true);
                            $sheet->setAllBorders('thin');
                            $sheet->cells('A1:Q1', function ($cells) {
                                $cells->setAlignment('center');
                                $cells->setFontWeight('bold');
                                $cells->setFontSize(13);
                            });
                            $sheet->fromArray($data);
                            for( $intRowNumber = 1; $intRowNumber <= count($data) + 1; $intRowNumber++){
                                $sheet->setSize('A' . $intRowNumber, 25, 18);
                                $sheet->setSize('B' . $intRowNumber, 25, 18);
                                $sheet->setSize('C' . $intRowNumber, 25, 18);
                                $sheet->setSize('D' . $intRowNumber, 25, 18);
                                $sheet->setSize('E' . $intRowNumber, 25, 18);
                                $sheet->setSize('F' . $intRowNumber, 25, 18);
                                $sheet->setSize('G' . $intRowNumber, 25, 18);
                                $sheet->setSize('H' . $intRowNumber, 25, 18);
                                $sheet->setSize('I' . $intRowNumber, 25, 18);
                                $sheet->setSize('J' . $intRowNumber, 25, 18);
                                $sheet->setSize('K' . $intRowNumber, 25, 18);
                                $sheet->setSize('L' . $intRowNumber, 25, 18);
                                $sheet->setSize('M' . $intRowNumber, 25, 18);
                                $sheet->setSize('N' . $intRowNumber, 25, 18);
                                $sheet->setSize('O' . $intRowNumber, 25, 18);
                                $sheet->setSize('P' . $intRowNumber, 25, 18);
                                $sheet->setSize('Q' . $intRowNumber, 25, 18);
                            }
                        });
                    })->export('xls');
                    break;

                case 'vendor-licence' :
                    $licenceDetails = VendorLicenses::whereBetween('expiry_date',[$start_time,$end_time])->with('category','license','vendor')->get()->toArray();
                    foreach($licenceDetails as $key => $value){
                        $vendorName  = User::where('id',$value['vendor']['user_id'])->select('first_name','last_name')->get()->toArray();
                        for($iterator = 0 ; $iterator < count($vendorName) ; $iterator++){
                            $data[$row]['Vendor name'] = ucwords($vendorName[$iterator]['first_name']." ".$vendorName[$iterator]['last_name']);
                        }
                        $data[$row]['ID'] = $value['license_number'];
                        $data[$row]['License Type'] = $value['license']['name'];
                        $data[$row]['Category Name'] = ucfirst($value['category']['name']);
                        $data[$row]['Expiry Date'] = $value['expiry_date'];
                        $data[$row]['Upload Date'] = $value['created_at'];
                        $data[$row]['Vendor Approval Date'] = $value['vendor']['approval_date'];
                        $row++;
                    }
                    $curr_date = Carbon::now();
                    Excel::create("Vendor_licence_expiry_report"."_".$curr_date, function($excel) use($data) {
                        $excel->getDefaultStyle()
                            ->getAlignment()
                            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $excel->sheet("Vendor Licence Expiry Report", function($sheet) use($data) {
                            $sheet->setAutoSize(true);
                            $sheet->setAllBorders('thin');
                            $sheet->cells('A1:G1', function ($cells) {
                                $cells->setAlignment('center');
                                $cells->setFontWeight('bold');
                                $cells->setFontSize(13);
                            });
                            $sheet->fromArray($data);
                            for( $intRowNumber = 1; $intRowNumber <= count($data) + 1; $intRowNumber++){
                                $sheet->setSize('A' . $intRowNumber, 25, 18);
                                $sheet->setSize('B' . $intRowNumber, 25, 18);
                                $sheet->setSize('C' . $intRowNumber, 25, 18);
                                $sheet->setSize('D' . $intRowNumber, 25, 18);
                                $sheet->setSize('E' . $intRowNumber, 25, 18);
                                $sheet->setSize('F' . $intRowNumber, 25, 18);
                                $sheet->setSize('G' . $intRowNumber, 25, 18);

                            }
                        });
                    })->export('xls');
                    break;

                case 'time-sale' :
                    $orderStatus = OrderStatus::whereNotIn('slug', ['back_ordered','cancel','declined','failed','abort'])->select('id')->get()->toArray();
                    $orderDetails = Order::whereIn('order_status_id',$orderStatus)
                        ->whereBetween('created_at',[ $startDate , $request->date ])
                        ->orderBy('created_at','desc')
                        ->with('DeliveryMethod','orderHistory','invoice','product','PaymentMethod','ShippingMethod')->get()->toArray();
                    foreach($orderDetails as $key => $value){
                        $data[$row]['Shipment Partner'] = $value['shipping_method']['name'];
                        $data[$row]['Order Time Stamp'] = $value['created_at'];
                        $data[$row]['Order ID'] = "AGR".$this->getStructuredOrderId($value['id']);
                        $data[$row]['Invoice Date'] = $value['invoice']['created_at'];
                        for($iterator = 0 ; $iterator < count($vendorName) ; $iterator++){
                            if($value['seller_id'] == $vendorName[$iterator]['seller_id']){
                                $data[$row]['Vendor Name'] = ucwords($vendorName[$iterator]['first_name']." ".$vendorName[$iterator]['last_name']);
                            }
                        }
                        $data[$row]['Product Name'] = ucfirst($value['product']['product_name']);
                        $data[$row]['Item Based SKU'] = $value['product']['item_based_sku'];
                        $data[$row]['Quantity'] = $value['quantity'];
                        if($value['is_configurable'] == true){
                            $area = $value['length'] * $value['width'];
                            $data[$row]['Agrosiaa Selling Price'] = $value['selling_price'] * $area;
                        }else{
                            $data[$row]['Agrosiaa Selling Price'] = $value['selling_price'];
                        }
                        $data[$row]['Discount Percent'] = $value['discount'];
                        if($value['is_configurable'] == true){
                            $area = $value['length'] * $value['width'];
                            $data[$row]['Discount Amount'] = ($data[$row]['Agrosiaa Selling Price'] * $value['quantity']) - (($value['discounted_price'] * $area)* $value['quantity']);
                        }else{
                            $data[$row]['Discount Amount'] = ($data[$row]['Agrosiaa Selling Price'] * $value['quantity']) - ($value['discounted_price'] * $value['quantity']);
                        }
                        $data[$row]['Delivery Charges'] = $value['delivery_amount'];
                        $data[$row]['Payment Method'] = $value['payment_method']['name'];
                        $data[$row]['Delivery Method'] = $value['delivery_method']['name'];
                        if($value['is_configurable'] == true){
                            $area = $value['length'] * $value['width'];
                            $data[$row]['Order Grand Total'] = (($value['discounted_price'] * $area)* $value['quantity']) + $value['delivery_amount'] - $value['coupon_discount'];
                        }else{
                            $data[$row]['Order Grand Total'] = ($value['discounted_price'] * $value['quantity']) + $value['delivery_amount'] - $value['coupon_discount'];
                        }
                        $row++;
                    }
                    $curr_date = Carbon::now();
                    Excel::create("time_vs_sale_report"."_".$curr_date, function($excel) use($data) {
                        $excel->getDefaultStyle()
                            ->getAlignment()
                            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $excel->sheet("Time vs Sale Report", function($sheet) use($data) {
                            $sheet->setAutoSize(true);
                            $sheet->setAllBorders('thin');
                            $sheet->cells('A1:Q1', function ($cells) {
                                $cells->setAlignment('center');
                                $cells->setFontWeight('bold');
                                $cells->setFontSize(13);
                            });
                            $sheet->fromArray($data);
                            for( $intRowNumber = 1; $intRowNumber <= count($data) + 1; $intRowNumber++){
                                $sheet->setSize('A' . $intRowNumber, 25, 18);
                                $sheet->setSize('B' . $intRowNumber, 25, 18);
                                $sheet->setSize('C' . $intRowNumber, 25, 18);
                                $sheet->setSize('D' . $intRowNumber, 25, 18);
                                $sheet->setSize('E' . $intRowNumber, 25, 18);
                                $sheet->setSize('F' . $intRowNumber, 25, 18);
                                $sheet->setSize('G' . $intRowNumber, 25, 18);
                                $sheet->setSize('H' . $intRowNumber, 25, 18);
                                $sheet->setSize('I' . $intRowNumber, 25, 18);
                                $sheet->setSize('J' . $intRowNumber, 25, 18);
                                $sheet->setSize('K' . $intRowNumber, 25, 18);
                                $sheet->setSize('L' . $intRowNumber, 25, 18);
                                $sheet->setSize('M' . $intRowNumber, 25, 18);
                                $sheet->setSize('N' . $intRowNumber, 25, 18);
                                $sheet->setSize('O' . $intRowNumber, 25, 18);
                                $sheet->setSize('P' . $intRowNumber, 25, 18);
                                $sheet->setSize('Q' . $intRowNumber, 25, 18);
                            }
                        });
                    })->export('xls');
                    break;

                case 'return-pick-up' :
                    $rmaStatus = RmaStatus::whereIn('slug', ['return_package_received','refund_initiated','refund_completed'])->lists('id')->toArray();
                    $orderRma = OrderRma::whereBetween('created_at',[$startDate , $request->date])
                        ->whereIn('rma_status_id',$rmaStatus)
                        ->orderBy('created_at','desc')
                        ->with('rmaReason','rmaStatus','order')->get()->toArray();
                    $completeSlug = OrderStatus::where('slug','complete')->pluck('id');
                    foreach($orderRma as $key => $value){
                        $itemhead = ProductCategoryRelation::where('product_id',$value['order']['product_id'])->pluck('category_id');
                        $subSubCategory = Category::where('id',$itemhead)->pluck('category_id');
                        $subCategory = Category::where('id',$subSubCategory)->select('category_id','return_period')->get()->toArray();
                        if($subCategory[0]['category_id'] != null){
                            $rootCategory = Category::where('id',$subCategory[0]['category_id'])->select('category_id','return_period')->get()->toArray();
                            $return_within = $rootCategory[0]['return_period'];
                        }else{
                            $return_within = $subCategory[0]['return_period'];
                        }
                        $data[$row]['Return ID'] = "AGR".$this->getStructuredOrderId($value['order_id'])."R";
                        $data[$row]['Return Reason'] = $value['rma_reason']['name'];
                        $data[$row]['Return Status'] = $value['rma_status']['status'];
                        $order_completion_date = OrderHistory::where('order_id',$value['order_id'])->where('order_status_id',$completeSlug)->pluck('created_at');
                        $data[$row]['Order Completion Date'] = $order_completion_date;
                        $data[$row]['Return Within'] = $return_within ."days before 6PM";
                        $data[$row]['Requested Date'] = $value['created_at'];
                        $data[$row]['Pickup Sla'] = $value['pick_up_date'];
                        $data[$row]['Pickup Completion Date'] = $value['notify_return_pick_up_time_shipment'];
                        $data[$row]['Return pickup sla breach'] = ($value['return_pick_up_sla_breach_shipment'] == true) ? "Yes" : "No";
                        if($data[$row]['Pickup Completion Date'] != null){
                            $return_pickup_delay = $this->timeDelay($data[$row]['pickup_sla'],$data[$row]['Pickup Completion Date']);
                            $data[$row]['Delay days'] = $return_pickup_delay->format("%R%a D");
                        }else{
                            $data[$row]['Delay days'] = null;
                        }
                        $data[$row]['Return package received acknowlegment'] = $value['notify_acknowledge_time_vendor'];
                        $data[$row]['Return package received notification'] = $value['notify_return_delivery_time_shipment'];
                        $data[$row]['Return Package Received Date'] = $value['return_delivery_date'];
                        $data[$row]['Return delivery SLA breach'] = ($value['return_delivery_sla_breach_shipment'] == true) ? "Yes" : "No";
                        if($data[$row]['Return package received notification'] != null){
                            $delivery_delay = $this->timeDelay($data[$row]['Return Package Received Date'],$data[$row]['Return package received notification']);
                            $data[$row]['Delivery Delay days'] = $delivery_delay->format("%R%a D");
                        }else{
                            $data[$row]['Delivery Delay days'] = null;
                        }
                        $row++;
                    }
                    $curr_date = Carbon::now();
                    Excel::create("Return_Pick_Up_report"."_".$curr_date, function($excel) use($data) {
                        $excel->getDefaultStyle()
                            ->getAlignment()
                            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $excel->sheet("Return Pick Up Report", function($sheet) use($data) {
                            $sheet->setAutoSize(true);
                            $sheet->setAllBorders('thin');
                            $sheet->cells('A1:Q1', function ($cells) {
                                $cells->setAlignment('center');
                                $cells->setFontWeight('bold');
                                $cells->setFontSize(13);
                            });
                            $sheet->fromArray($data);
                            for( $intRowNumber = 1; $intRowNumber <= count($data) + 1; $intRowNumber++){
                                $sheet->setSize('A' . $intRowNumber, 25, 18);
                                $sheet->setSize('B' . $intRowNumber, 25, 18);
                                $sheet->setSize('C' . $intRowNumber, 25, 18);
                                $sheet->setSize('D' . $intRowNumber, 25, 18);
                                $sheet->setSize('E' . $intRowNumber, 25, 18);
                                $sheet->setSize('F' . $intRowNumber, 25, 18);
                                $sheet->setSize('G' . $intRowNumber, 25, 18);
                                $sheet->setSize('H' . $intRowNumber, 25, 18);
                                $sheet->setSize('I' . $intRowNumber, 25, 18);
                                $sheet->setSize('J' . $intRowNumber, 25, 18);
                                $sheet->setSize('K' . $intRowNumber, 25, 18);
                                $sheet->setSize('L' . $intRowNumber, 25, 18);
                                $sheet->setSize('M' . $intRowNumber, 25, 18);
                                $sheet->setSize('N' . $intRowNumber, 25, 18);
                                $sheet->setSize('O' . $intRowNumber, 25, 18);
                                $sheet->setSize('P' . $intRowNumber, 25, 18);
                                $sheet->setSize('Q' . $intRowNumber, 25, 18);
                            }
                        });
                    })->export('xls');
                    break;

                case 'product':
                    $seller_id = $this->user->seller()->first();
                    $productId = ProductCategoryRelation::where('category_id',$request->item_head)->lists('product_id')->toArray();
                    $product = Product::whereIn('id',$productId)->where('seller_id',$seller_id['id'])->get()->toArray();
                    foreach($product as $key => $value){
                        $data[$row]['Seller sku'] = $value['seller_sku'];
                        $data[$row]['Product Name'] = $value['product_name'];
                        $data[$row]['Quantity'] = $value['quantity'];
                        $data[$row]['Discounted Price'] = $value['discounted_price'];
                        $data[$row]['Selling Price'] = $value['selling_price'];
                        $tax = Tax::where('id',$value['tax_id'])->pluck('name');
                        $data[$row]['Tax'] = $tax;
                        $data[$row]['HSN Code'] = $value['hsn_code_tax_relation_id'];
                        if($value['is_active'] == true){
                            $data[$row]['Status'] = 'Enable';
                        }else{
                            $data[$row]['Status'] = 'Disable';
                        }
                        if($value['out_of_stock_date'] != null){
                            $data[$row]['Stock'] = 'out_of_stock';
                        }else{
                            $data[$row]['Stock'] = 'in_stock';
                        }
                        if($value['is_deleted'] == null){
                            $data[$row]['Delete Status'] = 'No';
                        }else{
                            $data[$row]['Delete Status'] = 'Yes';
                        }
                        $row++;
                    }
                    $curr_date = Carbon::now();
                    Excel::create("product_report"."_".$curr_date, function($excel) use($data) {
                        $excel->getDefaultStyle()
                            ->getAlignment()
                            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $excel->sheet("Product", function($sheet) use($data) {
                            $sheet->setAutoSize(true);
                            $sheet->setAllBorders('thin');
                            $sheet->cells('A1:J1', function ($cells) {
                                $cells->setAlignment('center');
                                $cells->setFontWeight('bold');
                                $cells->setFontSize(13);
                            });
                            $sheet->fromArray($data);
                            for( $intRowNumber = 1; $intRowNumber <= count($data) + 1; $intRowNumber++){
                                $sheet->setSize('A' . $intRowNumber, 25, 18);
                                $sheet->setSize('B' . $intRowNumber, 25, 18);
                                $sheet->setSize('C' . $intRowNumber, 25, 18);
                                $sheet->setSize('D' . $intRowNumber, 25, 18);
                                $sheet->setSize('E' . $intRowNumber, 25, 18);
                                $sheet->setSize('F' . $intRowNumber, 25, 18);
                                $sheet->setSize('G' . $intRowNumber, 25, 18);
                                $sheet->setSize('H' . $intRowNumber, 25, 18);
                                $sheet->setSize('I' . $intRowNumber, 25, 18);
                                $sheet->setSize('J' . $intRowNumber, 25, 18);
                            }
                        });
                    })->export('xls');
                    break;

                case 'logistic-accounting' :
                    $logisticAccountingReport = array();
                    if($request->report == 'logistic-accounting' && ($this->userRoleType == 'superadmin' || $this->userRoleType == 'accountadmin')){
                        $ordeConfirmedStatusId = OrderStatus::where('slug','confirmed')->pluck('id');
                        $from_date = Carbon::parse($request->from_date);
                        $to_date = Carbon::parse($request->to_date);

                        $confirmedOrders = Order::where('order_status_id',$ordeConfirmedStatusId)->whereBetween('updated_at',[$from_date, $to_date])->lists('id')->toArray();
                        $logisticAccounting = LogistingAccounting::whereIn('order_id',$confirmedOrders)->get()->toArray();
                        $logisticAccountingAgrosiaaShipment = LogisticAccountingAgrosiaaShipment::whereIn('order_id',$confirmedOrders)->get()->toArray();
                        $logisticAccountingReport = array_merge($logisticAccounting, $logisticAccountingAgrosiaaShipment);
                    }
                    foreach($logisticAccountingReport as $key => $value){
                        $data[$row]['Order No'] = "AGR".$this->getStructuredOrderId($value['order_id']);
                        $confirmedDate = Order::where('id',$value['order_id'])->select('updated_at')->get()->toArray();
                        $shippingId = Order::where('id',$value['order_id'])->pluck('shipping_method_id');
                        $data[$row]['Logistic Name'] = ShippingMethod::where('id',$shippingId)->pluck('name');
                        $data[$row]['Acc.confirmed date'] = date('m-d-Y',strtotime($confirmedDate[0]['updated_at']));
                        $data[$row]['Biller Id'] = (array_key_exists('biller_id', $value)) ? $value['biller_id'] : null;
                        $data[$row]['Transaction Id'] = (array_key_exists('trans_id', $value)) ? $value['trans_id'] : null;
                        $data[$row]['Biller Name'] = (array_key_exists('biller_name', $value)) ? $value['biller_name'] : null;
                        $data[$row]['Amount'] = (array_key_exists('amount', $value)) ? $value['amount'] : null;
                        $data[$row]['Commission'] = (array_key_exists('commission', $value)) ? $value['commission'] : null;
                        $data[$row]['GST amount'] = (array_key_exists('gst', $value)) ? $value['gst'] : null;
                        $data[$row]['Net Payable'] = (array_key_exists('net_payable', $value)) ? $value['net_payable'] : null;
                        $data[$row]['Article Number'] = (array_key_exists('article_number', $value)) ? $value['article_number'] : null;
                        $data[$row]['Barcode Number'] = (array_key_exists('barcode_number', $value)) ? $value['barcode_number'] : null;
                        $data[$row]['Document Number'] = (array_key_exists('document_number', $value)) ? $value['document_number'] : null;
                        $data[$row]['Payment Docket Number'] = (array_key_exists('payment_docket_number', $value)) ? $value['payment_docket_number'] : null;
                        $data[$row]['Collection Office'] = (array_key_exists('collection_office', $value)) ? $value['collection_office'] : null;
                        $data[$row]['Collection Date'] = (array_key_exists('collection_date', $value)) ? date('d-m-Y',strtotime($value['collection_date'])) : null;
                        $data[$row]['Article Type'] = (array_key_exists('article_type', $value)) ? $value['article_type'] : null;
                        $data[$row]['Cheque Number'] = (array_key_exists('check_number', $value)) ? $value['check_number'] : null;
                        $data[$row]['Logistic Number'] = (array_key_exists('logistic_number', $value)) ? $value['logistic_number'] : null;
                        $data[$row]['Logistic Date'] = (array_key_exists('logistic_date', $value)) ? date('m-d-Y',strtotime($value['logistic_date'])) : null;
                        $data[$row]['Logistic Invoice Amount'] = (array_key_exists('logistic_invoice_amount', $value)) ? $value['logistic_invoice_amount'] : null;
                        $data[$row]['Invoice Payment Details'] = (array_key_exists('invoice_payment_details', $value)) ? $value['invoice_payment_details'] : null;
                        $data[$row]['Actual Logistic Cost'] = (array_key_exists('actual_logistic_cost', $value)) ? $value['actual_logistic_cost'] : null;
                        $data[$row]['Note Name'] = (array_key_exists('actual_logistic_cost', $value)) ? $value['actual_logistic_cost'] : null;
                        $data[$row]['Payment Date'] = (array_key_exists('payment_date', $value)) ? date('m-d-Y',strtotime($value['payment_date'])) : null;
                        $data[$row]['Delivery By'] = (array_key_exists('deliver_by', $value)) ? $value['deliver_by'] : null;
                        $data[$row]['Delivery Done By'] = (array_key_exists('delivery_done_by', $value)) ? $value['delivery_done_by'] : null;
                        $data[$row]['LR Number'] = (array_key_exists('lr_number', $value)) ? $value['lr_number'] : null;
                        $data[$row]['LR Date'] = (array_key_exists('lr_date', $value)) ? ($value['lr_date']) : null;
                        $data[$row]['LR Amount'] = (array_key_exists('lr_amount', $value)) ? $value['lr_amount'] : null;
                        $data[$row]['Payment Received Mode'] = (array_key_exists('payment_received_mode', $value)) ? $value['payment_received_mode'] : null;
                        $data[$row]['Bank Name'] = (array_key_exists('bank_name', $value)) ? $value['bank_name'] : null;
                        $data[$row]['Payment Deposit Date'] = (array_key_exists('payment_deposit_date', $value)) ? date('m-d-Y',strtotime($value['payment_deposit_date'])) : null;
                        $data[$row]['Deposit Note'] = (array_key_exists('deposit_note', $value)) ? $value['deposit_note'] : null;
                        $data[$row]['Invoice Number'] = (array_key_exists('invoice_number', $value)) ? $value['invoice_number'] : null;
                        $data[$row]['Invoice Date'] = (array_key_exists('invoice_date', $value)) ? $value['invoice_date'] : null;
                        $data[$row]['Invoice Amount'] = (array_key_exists('invoice_amount', $value)) ? $value['invoice_amount'] : null;
                        $row++;
                    }
                    $curr_date = Carbon::now();
                    Excel::create("logistic_accounting_report"."_".$curr_date, function($excel) use($data) {
                        $excel->getDefaultStyle()
                            ->getAlignment()
                            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $excel->sheet("Logistic Accounting Report", function($sheet) use($data) {
                            $sheet->setAutoSize(true);
                            $sheet->setAllBorders('thin');
                            $sheet->cells('A1:AK1', function ($cells) {
                                $cells->setAlignment('center');
                                $cells->setFontWeight('bold');
                                $cells->setFontSize(13);
                            });
                            $sheet->fromArray($data);
                            for( $intRowNumber = 1; $intRowNumber <= count($data) + 1; $intRowNumber++){
                                $sheet->setSize('A' . $intRowNumber, 25, 18);
                                $sheet->setSize('B' . $intRowNumber, 25, 18);
                                $sheet->setSize('C' . $intRowNumber, 25, 18);
                                $sheet->setSize('D' . $intRowNumber, 25, 18);
                                $sheet->setSize('E' . $intRowNumber, 25, 18);
                                $sheet->setSize('F' . $intRowNumber, 25, 18);
                                $sheet->setSize('G' . $intRowNumber, 25, 18);
                                $sheet->setSize('H' . $intRowNumber, 25, 18);
                                $sheet->setSize('I' . $intRowNumber, 25, 18);
                                $sheet->setSize('J' . $intRowNumber, 25, 18);
                                $sheet->setSize('K' . $intRowNumber, 25, 18);
                                $sheet->setSize('L' . $intRowNumber, 25, 18);
                                $sheet->setSize('M' . $intRowNumber, 25, 18);
                                $sheet->setSize('N' . $intRowNumber, 25, 18);
                                $sheet->setSize('O' . $intRowNumber, 25, 18);
                                $sheet->setSize('P' . $intRowNumber, 25, 18);
                                $sheet->setSize('Q' . $intRowNumber, 25, 18);
                                $sheet->setSize('R' . $intRowNumber, 25, 18);
                                $sheet->setSize('S' . $intRowNumber, 25, 18);
                                $sheet->setSize('T' . $intRowNumber, 25, 18);
                                $sheet->setSize('U' . $intRowNumber, 25, 18);
                                $sheet->setSize('V' . $intRowNumber, 25, 18);
                                $sheet->setSize('W' . $intRowNumber, 25, 18);
                                $sheet->setSize('X' . $intRowNumber, 25, 18);
                                $sheet->setSize('Y' . $intRowNumber, 25, 18);
                                $sheet->setSize('Z' . $intRowNumber, 25, 18);
                                $sheet->setSize('AA' . $intRowNumber, 25, 18);
                                $sheet->setSize('AB' . $intRowNumber, 25, 18);
                                $sheet->setSize('AC' . $intRowNumber, 25, 18);
                                $sheet->setSize('AD' . $intRowNumber, 25, 18);
                                $sheet->setSize('AE' . $intRowNumber, 25, 18);
                                $sheet->setSize('AF' . $intRowNumber, 25, 18);
                                $sheet->setSize('AG' . $intRowNumber, 25, 18);
                                $sheet->setSize('AH' . $intRowNumber, 25, 18);
                                $sheet->setSize('AI' . $intRowNumber, 25, 18);
                                $sheet->setSize('AJ' . $intRowNumber, 25, 18);
                                $sheet->setSize('AK' . $intRowNumber, 25, 18);
                            }
                        });
                    })->export('xls');
                    break;
            }
        }catch(\Exception $e){
            $errorLog = [
                'request' => $request->all(),
                'action' => 'Report',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($errorLog));
            abort(500,$e->getMessage());
        }
    }
}
