<?php
namespace App\Http\Controllers\CustomTraits;

use App\BankDetails;
use App\Brand;
use App\CancelReason;
use App\Customer;
use App\DeliveryType;
use App\Helpers\NumberHelper;
use App\HSNCodes;
use App\HSNCodeTaxRelation;
use App\Invoice;
use App\Order;
use App\OrderCustomerRelation;
use App\OrderHistory;
use App\OrderQuantityInfo;
use App\OrderRma;
use App\OrderStatus;
use App\PaymentMethod;
use App\Product;
use App\RmaHistory;
use App\RmaReason;
use App\RmaStatus;
use App\RtvMicroStatusDetails;
use App\Seller;
use App\SellerAddress;
use App\ShippingMethod;
use App\Tax;
use App\User;
use App\VendorLicenses;
use App\VendorSettleMent;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Support\Facades\Session;


trait RmaTrait{

    use SendMessageTrait;
    use DeliveryTrait;
    public function viewRmaList($type){
        try{
            $rmaType = $type;
            if($this->userRoleType == 'superadmin' || $this->userRoleType == 'customersupport'|| $this->userRoleType == 'vendorsupport'){
                $roleType = $this->userRoleType;
                return view('backend.superadmin.rma.manage')->with(compact('rmaType','roleType'));
            }elseif($this->userRoleType == 'shipmentadmin' || $this->userRoleType == 'shipmentpartner'){
                return view('backend.shipmentAdmin.rma.manage')->with(compact('rmaType'));
            }elseif($this->userRoleType == 'seller'){
                return view('backend.seller.rma.manage')->with(compact('rmaType'));
            }
        }catch (\Exception $e){
            abort(500,$e->getMessage());
        }
    }

    public function rmaListing(Request $request,$rmaStatus){
        try{
            $tableData = $request->all();
            $searchData = NULL;
            $shipping_method_id = ShippingMethod::where('user_id',$this->user['id'])->pluck('id');
            $orders = OrderRma::where('shipping_method_id',$shipping_method_id)->lists('order_id');
            $rmaStatusId = RmaStatus::where('slug',$rmaStatus)->first();
            if($rmaStatusId !=null){
                if($this->userRoleType == 'seller'){
                    $sellerProducts = Product::where('seller_id',$this->seller->id)->lists('id');
                    $rmaIds = OrderRma::join('orders','orders.id','=','order_rma.order_id')
                        ->where('order_rma.rma_status_id',$rmaStatusId->id)
                        ->whereIn('orders.product_id',$sellerProducts)
                        ->lists('order_rma.id');
                }elseif($this->userRoleType == 'shipmentpartner'){
                    $rmaIds = OrderRma::where('rma_status_id',$rmaStatusId['id'])->where('shipping_method_id',$shipping_method_id)->lists('id');
                }else{
                    $rmaIds = OrderRma::where('rma_status_id',$rmaStatusId['id'])->lists('id');
                }
                $resultFlag = true;
                if($request->has('rma_id') && $tableData['rma_id']!=""){
                    $rmaIds = OrderRma::whereIn('id',$rmaIds)->where('rma_status_id',$rmaStatusId['id'])->where('order_id',$tableData['rma_id'])->lists('id')->toArray();
                    if(count($rmaIds) <= 0){
                        $resultFlag = false;
                    }
                }
                if($resultFlag == true && $request->has('order_id') && $tableData['order_id']!=""){
                    $rmaIds = OrderRma::whereIn('id',$rmaIds)->where('order_id',$tableData['order_id'])->lists('id')->toArray();
                    if(count($rmaIds) <= 0){
                        $resultFlag = false;
                    }
                }
                if($resultFlag == true && $request->has('order_customer_name') && $tableData['order_customer_name']!=""){
                    $orderCustomerName = trim($request->order_customer_name);
                    $rmaIds = OrderRma::join('customers','customers.id','=','order_rma.customer_id')
                        ->join('users','users.id','=','customers.user_id')
                        ->where(function ($query) use($orderCustomerName){
                            $query->where('users.first_name', 'ILIKE', '%'.$orderCustomerName.'%')
                                ->orWhere('users.last_name', 'ILIKE', '%'.$orderCustomerName.'%');
                        })
                        ->whereIn('order_rma.id',$rmaIds)
                        ->lists('order_rma.id')
                        ->toArray();
                    if(count($rmaIds) <= 0){
                        $resultFlag = false;
                    }
                }
                if($resultFlag == true && $request->has('order_product_name') && $tableData['order_product_name']!=""){
                    $productName = trim($request->order_product_name);
                    $rmaIds = OrderRma::join('orders','orders.id','=','order_rma.order_id')
                        ->join('products','products.id','=','orders.product_id')
                        ->whereIn('order_rma.id',$rmaIds)
                        ->where('products.product_name','ILIKE','%'.$productName.'%')
                        ->lists('order_rma.id');
                    if(count($rmaIds) <= 0){
                        $resultFlag = false;
                    }
                }
                if($resultFlag == true && $request->has('quantity') && $tableData['quantity']!=""){
                    $rmaIds = OrderRma::whereIn('id',$rmaIds)->where('return_quantity',$tableData['quantity'])->lists('id')->toArray();
                    if(count($rmaIds) <= 0){
                        $resultFlag = false;
                    }
                }
                $iTotalRecords = count($rmaIds);
                $iDisplayLength = intval($request->length);
                $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
                $iDisplayStart = intval($request->start);
                $sEcho = intval($request->draw);
                $records = array();
                $records["data"] = array();
                $end = $iDisplayStart + $iDisplayLength;
                $end = $end > $iTotalRecords ? $iTotalRecords : $end;
                $status_list = array(
                    array("success" => "Pending"),
                    array("info" => "Closed"),
                    array("danger" => "On Hold"),
                    array("warning" => "Fraud")
                );
                $limitedProducts = OrderRma::whereIn('id',$rmaIds)->take($iDisplayLength)->skip($iDisplayStart)->orderBy('created_at','desc')->get()->toArray();
                $requestedStatusId = RmaStatus::where('slug','requested')->pluck('id');
                $pickUpInProgressStatusId = RmaStatus::where('slug','pickup_in_progress')->pluck('id');
                $rejectedStatusId = RmaStatus::where('slug','rejected')->pluck('id');
                for($j=0,$i = $iDisplayStart; $i < $end && $j < $iTotalRecords; $i++,$j++) {
                    if($this->userRoleType == 'superadmin'){
                        $orderDetailView = "/operational/rma/view/".$limitedProducts[$j]["id"];
                    }elseif($this->userRoleType == 'shipmentadmin' || $this->userRoleType == 'shipmentpartner'){
                        $orderDetailView = "/shipment/rma/view/".$limitedProducts[$j]["id"];
                    }elseif($this->userRoleType == 'seller'){
                        $orderDetailView = "/rma/view/".$limitedProducts[$j]["id"];
                    }elseif($this->userRoleType == 'customersupport'){
                        $orderDetailView = "/customer-support/rma/view/".$limitedProducts[$j]["id"];
                    }elseif($this->userRoleType == 'vendorsupport'){
                        $orderDetailView = "/vendor-support/rma/view/".$limitedProducts[$j]["id"];
                    }
                    $id = ($i + 1);
                    $customerInfo = Customer::where('id',$limitedProducts[$j]['customer_id'])->with('user')->first();
                    if($this->userRoleType == 'superadmin'){
                        $orderLink = "/operational/order/view/".$limitedProducts[$j]["order_id"];
                    }elseif($this->userRoleType == 'shipmentadmin' || $this->userRoleType == 'shipmentpartner'){
                        $orderLink = "/shipment/order/view/".$limitedProducts[$j]["order_id"];
                    }elseif($this->userRoleType == 'seller'){
                        $orderLink = "/order/view/".$limitedProducts[$j]["order_id"];
                    }elseif($this->userRoleType == 'customersupport'){
                        $orderLink = "/customer-support/order/view/".$limitedProducts[$j]["order_id"];
                    }elseif($this->userRoleType == 'vendorsupport'){
                        $orderLink = "/vendor-support/order/view/".$limitedProducts[$j]["order_id"];
                    }
                    $structuredId = $this->getStructuredRmaId($limitedProducts[$j]['order_id']);
                    $orderDetails = Order::where('id',$limitedProducts[$j]['order_id'])->first();
                    if($orderDetails['is_configurable'] == true) {
                        $discountAmount = (($orderDetails->discounted_price * $limitedProducts[$j]['return_quantity']) * (($orderDetails["length"]) * ($orderDetails["width"])));
                    }else{
                        $discountAmount = ($orderDetails->discounted_price * $limitedProducts[$j]['return_quantity']);
                    }
                    if($limitedProducts[$j]["rma_status_id"] == $requestedStatusId || $limitedProducts[$j]["rma_status_id"] == $pickUpInProgressStatusId ){
                        $records["data"][] = array(
                            '<input type="checkbox" name="id[]" value="'.$limitedProducts[$j]['id'].'">',
                            "AGR".$structuredId."R",
                            '<a href='.$orderLink.'>'."AGR".$structuredId.'</a>',
                            $customerInfo->user->first_name.' '.$customerInfo->user->last_name,
                            ucwords($limitedProducts[$j]['product_name']),
                            date('d F Y',strtotime($limitedProducts[$j]['pick_up_date'])),
                            $discountAmount,
                            $limitedProducts[$j]['return_quantity'],
                            '<a href='.$orderDetailView.' class="btn btn-sm btn-default btn-circle btn-editable"><i class="fa fa-pencil"></i> View</a>',
                        );
                    }elseif ($limitedProducts[$j]["rma_status_id"] == $rejectedStatusId){
                        $records["data"][] = array(
                            '<input type="checkbox" name="id[]" value="'.$limitedProducts[$j]['id'].'">',
                            "AGR".$structuredId."RR",
                            '<a href='.$orderLink.'>'."AGR".$structuredId.'</a>',
                            $customerInfo->user->first_name.' '.$customerInfo->user->last_name,
                            ucwords($limitedProducts[$j]['product_name']),
                            $limitedProducts[$j]['return_quantity'],
                            '<a href='.$orderDetailView.' class="btn btn-sm btn-default btn-circle btn-editable"><i class="fa fa-pencil"></i> View</a>',
                        );
                    }
                    else{
                        $records["data"][] = array(
                            '<input type="checkbox" name="id[]" value="'.$limitedProducts[$j]['id'].'">',
                            "AGR".$structuredId."R",
                            '<a href='.$orderLink.'>'."AGR".$structuredId.'</a>',
                            $customerInfo->user->first_name.' '.$customerInfo->user->last_name,
                            ucwords($limitedProducts[$j]['product_name']),
                            $limitedProducts[$j]['return_quantity'],
                            '<a href='.$orderDetailView.' class="btn btn-sm btn-default btn-circle btn-editable"><i class="fa fa-pencil"></i> View</a>',
                        );
                    }
                }
                if (isset($request->customActionType) && $request->customActionType == "group_action") {
                    $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
                    $records["customActionMessage"] = "Group action successfully has been completed. Well done!"; // pass custom message(useful for getting status of group actions)
                }
                $records["draw"] = $sEcho;
                $records["recordsTotal"] = $iTotalRecords;
                $records["recordsFiltered"] = $iTotalRecords;
            }else{
                $records = '';
            }
        }catch(\Exception $e){
            $records = $e->getMessage();
        }
        return response()->json($records);
    }

    public function rmaDetailView(Request $request,$rmaId){
        try{
            $rmaInfo = OrderRma::where('id',$rmaId)->with('rmaReason','rmaStatus','order','shippingMethod')->first();
            $allCarrier = ShippingMethod::get();
            $rmaOtherReasonId= RmaReason::where('slug','other')->pluck('id');
            $customerId = Customer::where('id',$rmaInfo->order->customer_id)->first();
            $customerInfo = User::where('id',$customerId->user_id)->first();
            $rmaInfo['customerInfo'] = $customerInfo;
            $rmaInfo['structured_id'] = $this->getStructuredRmaId($rmaInfo['order_id']);
            $sellerId = Seller::where('id',$rmaInfo->order->seller_id)->first();
            $sellerInfo = User::where('id',$sellerId->user_id)->first();
            $rmaInfo['sellerInfo'] = $sellerInfo;
            $address = OrderCustomerRelation::where('id',$rmaInfo->order->order_customer_info_id)->first();
            $rmaInfo['rma_date'] = $rmaInfo->created_at->format('d F Y H:i:s');
            $pickupAddress = json_decode($address->billing_address);
            $rmaInfo['pickupAddress'] = $pickupAddress;
            $productInfo = Product::where('id',$rmaInfo->order->product_id)->with('brand')->first();
            $rmaInfo['sellerProductPickAddress'] = SellerAddress::where('id',$productInfo->seller_address_id)->first();
            $rmaInfo['productInfo'] = $productInfo;
            $rmaInfo['orderRmaStatus'] = $rmaInfo->rmaStatus->slug;
            $rmaInfo['pick_up_date'] = date('d F Y',strtotime($rmaInfo['pick_up_date']));
            $rmaInfo['return_delivery_date'] = date('d F Y',strtotime($rmaInfo['return_delivery_date']));
            if($rmaInfo->rma_reason_id == $rmaOtherReasonId){
                $rmaInfo->rmaReason->name = $rmaInfo->reason;
            }
            $rmasHistory = RmaHistory::where('rma_id',$rmaInfo->id)->get();
            $masterHistory = array();
            foreach($rmasHistory as $rmaHistory){
                $status = RmaStatus::where('id',$rmaHistory['rma_status_id'])->first();
                $rmaHistory['date'] = $rmaHistory['created_at']->format('d F Y H:i:s');;
                $rmaHistory['status'] = $status->status;
                $rmaHistory['cancel_reason'] = OrderRma::where('id',$rmaId)->pluck('rma_cancel_text');
                if($rmaHistory['is_email_sent'] == 0){
                    $rmaHistory['email_notification'] = 'Customer Not Notified';
                }else{
                    $rmaHistory['email_notification'] = 'Customer Notified';
                }
                array_push($masterHistory,$rmaHistory);
            }
            $rtvMicroStatus = RtvMicroStatusDetails::where('order_id',$rmaInfo['order']['id'])->first();
            if($this->userRoleType =='superadmin' || $this->userRoleType == 'customersupport' || $this->userRoleType == 'vendorsupport'){
                return view('backend.superadmin.rma.view')->with(compact('rmaInfo','masterHistory','allCarrier','rtvMicroStatus'));
            }elseif($this->userRoleType =='shipmentadmin' || $this->userRoleType == 'shipmentpartner'){
                return view('backend.shipmentAdmin.rma.view')->with(compact('rmaInfo','masterHistory','allCarrier'));
            }elseif($this->userRoleType=='seller'){
                return view('backend.seller.rma.view')->with(compact('rmaInfo','masterHistory'));
            }
        }catch (\Exception $e){
            $data = [
                'user' => $this->user,
                'role' => $this->userRoleType,
                'order_id' => $rmaId,
                'action' => 'rma Detail view',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }

    public function changeRmaStatus(Request $request,$rmaStatus,$rmaId,$currentStatus){
        try{
            $rmaInfo = OrderRma::findOrFail($rmaId);
            $currentTime = Carbon::now();
            $rmaStatusId = RmaStatus::where('slug',$rmaStatus)->first();
            $is_email_sent = 0;
            $customerInfo = User::where('id',$this->user->id)->first();
            $rmaInfo['customerInfo'] = $customerInfo;
            $address = OrderCustomerRelation::where('id',$rmaInfo->order->order_customer_info_id)->first();
            $customerId = Customer::findOrFail($rmaInfo['customer_id']);
            $customer = User::findOrFail($customerId->user_id);
            $customer = $customer->toArray();
            $pickupDate = DeliveryTrait::getReturnDate($rmaInfo->created_at);
            $pickupDate = date('d F Y', strtotime($pickupDate));
            $customerName = $customer['first_name']." ".$customer['last_name'];
            $pickupAddress = json_decode($address->billing_address);
            $pickupInProgressId = RmaStatus::where('slug','return_package_received')->pluck('id');
            $alreadyPresent = RmaHistory::where('rma_status_id',$pickupInProgressId)->where('rma_id',$rmaId)->count();
            if($alreadyPresent > 0){
              $message = 'Information updated successfully';
            }else{
              $message = 'Rma status updated successfully';
            }
            $structuredRmaId = $this->getStructuredRmaId($rmaInfo->order_id);
            if($rmaStatusId->slug == "pickup_in_progress" || $rmaStatusId->slug == "return_package_received" ||$rmaStatusId->slug == "rejected"){
                if($rmaStatusId->id > $rmaInfo->rma_status_id){
                    OrderRma::where('id',$rmaId)->update(array('rma_status_id'=> $rmaStatusId->id,'updated_at' => $currentTime));
                    if($customer['is_email'] == true){
                        $is_email_sent = 1;
                        $mailParameters = array('product_name'=>$rmaInfo->product_name,'pickupDate'=>$pickupDate,'customer_name'=>$customerName,'pickupAddress'=>$pickupAddress);
                        Mail::send('emails.Customer.rma.accept',$mailParameters, function($message) use ($customer,$rmaInfo,$structuredRmaId){
                            $message->subject('Your return AGR'.$structuredRmaId.'R of '.ucwords($rmaInfo->product_name));
                            $message->to($customer['email']);
                            $message->from(env('FROM_EMAIL'));
                        });
                    }
                    $rmaHistoryArray = array(
                        'is_email_sent'=>$is_email_sent,
                        'rma_id' => $rmaId,
                        'rma_status_id' => $rmaStatusId->id,
                        'user_id' => $this->user->id,
                        'created_at' => $currentTime,
                        'updated_at' => $currentTime,
                    );
                    RmaHistory::insert($rmaHistoryArray);
                }
            }
                if($rmaStatus == 'return_package_received'){
                    OrderRma::where('id',$rmaId)->update(array('notify_acknowledge_time_vendor'=>$currentTime));
                }
              if($customer['mobile'] != null)
              {
                $pickupDate = date('jS F Y', strtotime($pickupDate));
                $smsMessage="Your return pickup is scheduled for ".$pickupDate." and will be picked up from your delivered address. For details please check My Returns in your Agrosiaa account.";
                $this->sendOrderSms($customer['mobile'],$smsMessage);
              }
            $structuredRmaId = $this->getStructuredRmaId($rmaInfo->order_id);
            if($rmaStatusId->slug == "rejected"){
              if ($customer['mobile'] != null) {
                $smsMessage = "Agrosiaa was unable to process your return #AGR".$structuredRmaId."R due to some reasons.For Queries please contact: 020-46917000";
                $sendSMS = $this->sendOrderSms($customer['mobile'],$smsMessage);
              }
              if($customer['is_email'] == true){
                  $mailParameters = array('product_name'=>$rmaInfo->product_name,'reason'=>$rmaInfo->reason,'customer_name'=>$customerName,'rmaId'=>$structuredRmaId,'returnDate'=>date('jS F Y', strtotime($rmaInfo->created_at)));
                  Mail::send('emails.Customer.rma.rejected',$mailParameters, function($message) use ($customer,$structuredRmaId){
                      $message->subject('Your return Order AGR'.$structuredRmaId.'R has been Rejected ');
                      $message->to($customer['email']);
                      $message->from(env('FROM_EMAIL'));
                  });
              }
            }
            $request->session()->flash('success', $message);
            if($this->userRoleType=='seller'){
                return redirect('rma/view/'.$rmaId);
            }elseif($this->userRoleType=='superadmin'){
                return redirect('operational/rma/view/'.$rmaId);
            }
        }catch (\Exception $e){
            $data = [
                'user' => $this->user,
                'role' => $this->userRoleType,
                'action' => 'order change status',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }

    public function initiateRma(Request $request){
        try{
            $rmaInfo = OrderRma::where('id',$request->rma_id)->with('rmaReason','rmaStatus','order')->first();
            $vendorSettleMent = VendorSettleMent::where('order_id',$rmaInfo['order_id'])->first();
            $orderInfo = Order::where('id',$rmaInfo['order_id'])->first();
            if($orderInfo['is_configurable'] == true){
                $area = $orderInfo['length'] * $orderInfo['width'];
                $returnOrderAmount = round(($orderInfo->discounted_price * $area) * $rmaInfo['return_quantity']);
                $discountedBasePrice =($orderInfo->base_price - ($orderInfo->base_price * ($orderInfo->discount / 100))) * $area ;
            }else{
                $returnOrderAmount = round($orderInfo->discounted_price * $rmaInfo['return_quantity']);
                $discountedBasePrice = $orderInfo->base_price - ($orderInfo->base_price * ($orderInfo->discount / 100));
            }
            $successfulOrderQuantity = $vendorSettleMent['order_quantity']- $rmaInfo['return_quantity'];
            $successfulOrderPrice = $successfulOrderQuantity * $discountedBasePrice;
            $commissionPercent = $orderInfo['commission_percent'];
            $commissionAmount = round($successfulOrderPrice * ($commissionPercent/100),2);
            $gstCommissionAmount = round(env('GST_PERCENT') * $commissionAmount , 2);
            $logisticsAmount = round(($orderInfo['logistic_percent'] / 100) * $successfulOrderPrice,2);
            $gstLogisticsAmount = round(env('GST_PERCENT') * $logisticsAmount,2);
            $returnLogisticsAmount = round(($orderInfo['logistic_percent'] / 100) * ($discountedBasePrice * $rmaInfo['return_quantity'] * 2),2);
            $returnGstLogisticsAmount = round(env('GST_PERCENT') * $returnLogisticsAmount,2);
            if($orderInfo['tax_rate'] == 0){
                $tcs = $return_tcs_amount= 0;
            }else{
                $tcs = round(((1 / 100) * ($vendorSettleMent['order_amount'] * $successfulOrderQuantity)),2);
                $return_tcs_amount = round(((1 / 100) * ($vendorSettleMent['order_amount'] * $rmaInfo['return_quantity'])),2);
            }
            if($orderInfo['is_configurable'] == true){
                $area = $orderInfo['length'] * $orderInfo['width'];
                $finalSettleMentAmount = round(($successfulOrderQuantity * ($orderInfo->discounted_price)* $area) -
                    (
                        ($commissionAmount + $gstCommissionAmount) +
                        ($logisticsAmount + $gstLogisticsAmount) +
                        ($returnLogisticsAmount + $returnGstLogisticsAmount) +
                        ($tcs)
                    ),2);
            }else{
                $finalSettleMentAmount = round(($successfulOrderQuantity * $orderInfo->discounted_price) -
                    (
                        ($commissionAmount + $gstCommissionAmount) +
                        ($logisticsAmount + $gstLogisticsAmount) +
                        ($returnLogisticsAmount + $returnGstLogisticsAmount) +
                        ($tcs)
                    ),2);
            }
            $finalReturnSettleMentAmount = round(- $vendorSettleMent['order_vendor_settlement_amount'] + $finalSettleMentAmount + $return_tcs_amount,2);
            $rmaSettleMentInfo = array(
                'return_order_quantity' => $rmaInfo['return_quantity'],
                'return_order_amount' => $returnOrderAmount,
                'return_vendor_settlement_amount' => $finalReturnSettleMentAmount,
                'return_logistics_charges' => $returnLogisticsAmount,
                'return_tcs_amount' => $return_tcs_amount,
                'final_vendor_settlement_amount' => $finalSettleMentAmount,
                'rma_complete_date'=>Carbon::now()->addDay(7)->toDateString(),
                'rma_id'=>$request->rma_id
            );
            if($vendorSettleMent != null){
                $vendorSettleMent->update($rmaSettleMentInfo);
            }
            $sellerId = Seller::where('id',$rmaInfo->order->seller_id)->with('user')->first();
            $currentTime = Carbon::now();
            $rmaCompleteDate = Carbon::now()->addDay(7)->toDateString();
            $rmaStatusId = RmaStatus::where('slug',$request->rmaStatus)->first();
            $rmaInfo->update(array('rma_status_id'=> $rmaStatusId->id,'neft_number' => $request->neft_number,'rma_complete_date' => $rmaCompleteDate,'updated_at' => $currentTime));
            $rmaHistoryArray = array(
                'is_email_sent' => 1,
                'rma_id' => $request->rma_id,
                'rma_status_id' => $rmaStatusId->id,
                'user_id' => $this->user->id,
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
            );
            $rmaHistory = RmaHistory::insert($rmaHistoryArray);
            /* Send RMA status update  Email to customer*/
            $customer = Customer::where('id',$rmaInfo['customer_id'])->with('user')->first();
            $customer = $customer->toArray();
            $customerName = $customer['user']['first_name']." ".$customer['user']['last_name'];
            $refundAmountInfo = Order::select('discounted_price','quantity','is_configurable','length','width')->where('id',$rmaInfo->order_id)->first();
            if($refundAmountInfo['is_configurable'] == true){
                $area=$refundAmountInfo['length'] * $refundAmountInfo['width'];
                $refundAmount = ($refundAmountInfo['discounted_price'] * $area) * $rmaInfo->return_quantity;
            }else{
                $refundAmount = ($refundAmountInfo['discounted_price']) * $rmaInfo->return_quantity;
            }
            if($rmaInfo->rmaReason->slug == 'other'){
              $reason = $rmaInfo->reason;
            }else{
              $reason = $rmaInfo->rmaReason->name;
            }
            $structuredRmaId = $this->getStructuredRmaId($rmaInfo->order_id);
            $mailParameters = array('order_id'=>$structuredRmaId,'productName'=>$rmaInfo->product_name,'productSku'=>$rmaInfo->product_sku,'returnQuantity'=>$rmaInfo->return_quantity,'reason'=>$reason,'customerName'=>$customerName,'refundAmount'=>$refundAmount,'neft_number'=>$rmaInfo->neft_number);
            if($customer['user']['is_email'] == true){
                Mail::send('emails.Customer.rma.refundInitiated',$mailParameters, function($message) use ($customer,$structuredRmaId){
                    $message->subject('Refund on order '.$structuredRmaId.'R');
                    $message->to($customer['user']['email']);
                    $message->from(env('FROM_EMAIL'));
                });
            }
            if($customer['user']['mobile'] != null)
            {
              $smsMessage = "Dear Customer, the seller ".$sellerId->company." has received your returned product in proper condition. We have initiated a refund to your bank for amount Rs.".$refundAmount;
              $sendSMS = $this->sendOrderSms($customer['user']['mobile'],$smsMessage);

            }
            $message = 'Rma status updated successfully';
            $request->session()->flash('success', $message);
            if($this->userRoleType=='seller'){
                return redirect('rma/view/'.$request->rma_id);
            }elseif($this->userRoleType=='superadmin'){
                return redirect('operational/rma/view/'.$request->rma_id);
            }
        }catch (\Exception $e){
            $data = [
                'user' => $this->user,
                'role' => $this->userRoleType,
                'action' => 'rma initiate',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }

    public function updateNeftNumber(Request $request,$id){
        try{
            $data = $request->all();
            $currentTime = Carbon::now();
            $rmaInfo = OrderRma::findOrFail($id);
            $rmaInfo->update(array('neft_number' => $request->neft_number,'updated_at' => $currentTime));
            $message = 'NEFT Number updated successfully';
            $request->session()->flash('success', $message);

            return redirect('operational/rma/view/'.$id);
        }catch (\Exception $e){
            $data = [
                'user' => $this->user,
                'role' => $this->userRoleType,
                'action' => 'updated neft number',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }

    public function updateConsignmentNumber(Request $request,$id){
        try{
            $data = $request->all();
            $currentTime = Carbon::now();
            $rmaInfo = OrderRma::findOrFail($id);
            $rmaInfo->update(array('consignment_number' => $data['consignment_number'],'shipping_method_id' => $data['shipping_method_id'],'updated_at' => $currentTime));
            $message = 'Carrier Information updated successfully';
            $request->session()->flash('success', $message);
            if($this->userRoleType =='shipmentadmin'){
                return redirect('shipment/rma/view/'.$id);
            }else{
                return redirect('operational/rma/view/'.$id);
            }
        }catch (\Exception $e){
            $data = [
                'user' => $this->user,
                'role' => $this->userRoleType,
                'action' => 'Update Consignment Number',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }
    public function generateInvoice($rmaId){
        try{
            $user = Auth::user();
            $rmaInfo = OrderRma::where('id',$rmaId)->with('rmaReason','rmaStatus','order')->first();

            $customerId = Customer::where('id',$rmaInfo->order->customer_id)->first();
            $customerInfo = User::where('id',$customerId->user_id)->first();
            $structuredOrderId = $this->getStructuredRmaId($rmaInfo['order_id']);
            $rmaInfo['customerInfo'] = $customerInfo;
            $sellerId = Seller::where('id',$rmaInfo->order->seller_id)->first();
            $sellerInfo = User::where('id',$sellerId->user_id)->first();
            $rmaInfo['sellerInfo'] = $sellerInfo;
            $rmaInfo['gstin'] = $sellerId->gstin;
            $rmaInfo['invoice'] = $this->getStructuredRmaId($rmaInfo->order_id);
            $rmaInfo['pick_up_date']= DeliveryTrait::getReturnDate($rmaInfo['created_at']);
            $address = OrderCustomerRelation::where('id',$rmaInfo->order->order_customer_info_id)->first();
            $rmaInfo['rma_date'] = $rmaInfo->created_at->format('M d, Y H:i:s');
            $pickupAddress = json_decode($address->billing_address);
            $rmaInfo['pickupAddress'] = $pickupAddress;
            $productInfo = Product::where('id',$rmaInfo->order->product_id)->with('brand')->first();
            $rmaInfo['productInfo'] = $productInfo;
            $sellerAddress = SellerAddress::where('id',$productInfo['seller_address_id'])->first();
            $orderInfo = Order::where('id',$rmaInfo['order_id'])->first();
            $orderQuantityInfo = OrderQuantityInfo::where('order_id',$orderInfo->id)->get()->toArray();

            $orderRma = OrderRma::where('id',$rmaId)->first();
            $order = Order::where('id',$orderRma['order_id'])->with('product','invoice','seller')->first();
            $paymentMethod = PaymentMethod::where('id',$order['payment_method_id'])->first();
            if($paymentMethod['slug'] == "cod"){
                $cod_payment_flag = true;
            }else{
                $cod_payment_flag = false;
            }
            $order_id = $this->getStructuredOrderId($orderRma['order_id']);
            $brand = $order['product']->brand;
            $hsnCodeId = HSNCodeTaxRelation::where('id',$order->hsn_code_tax_relation_id)->pluck('hsn_code_id');
            $hsn_code = HSNCodes::where('id',$hsnCodeId)->pluck('hsn_code');
            $license = array();
            $license['seedLicense'] = VendorLicenses::where('vendor_id',$order['seller']['id'])->where('license_id' , 1)->pluck('license_number');
            $license['fertilizerLicense'] = VendorLicenses::where('vendor_id',$order['seller']['id'])->where('license_id' , 2)->pluck('license_number');
            $license['pesticidesLicense'] = VendorLicenses::where('vendor_id',$order['seller']['id'])->where('license_id' , 3)->pluck('license_number');
            $license['otherLicense'] = VendorLicenses::where('vendor_id',$order['seller']['id'])->where('license_id' , 4)->pluck('license_number');
            $customerAddress = json_decode($order->ordersCustomerInfo->shipping_address);
            if($order['is_configurable'] == true){
                $area = $order['length'] * $order['width'];
                $unitPrice = round(($order->selling_price * $area) / (($order->tax_rate / 100)+1),2);
                $discountAmount = (($order->selling_price - $order->discounted_price) * $area) * $orderRma['return_quantity'];
                $grossTotal = ($order['discounted_price'] * $area) * $orderRma['return_quantity'];
            }else{
                $unitPrice = round($order->selling_price / (($order->tax_rate / 100)+1),2);
                $discountAmount = ($order->selling_price - $order->discounted_price) * $orderRma['return_quantity'];
                $grossTotal = $order['discounted_price'] * $orderRma['return_quantity'];
            }
            if(strtoupper($sellerAddress->state) != strtoupper($customerAddress->state)){
                $tax_igst_applied = true;
            }else{
                $tax_igst_applied = false;
            }
            if( $tax_igst_applied == true){
                $tax_rate = $order->tax_rate;
                $tax = (($tax_rate)/100)+1 ;
            }else{
                $tax_rate = $order->tax_rate / 2;
                $tax = (($tax_rate * 2)/100)+1 ;
            }
            $finaltotal = $grossTotal;
            $newsubtotal = round($finaltotal / $tax,'2');
            $finalUnitPrice = round($newsubtotal/$rmaInfo->return_quantity,'2');
            $gst = round(($tax_rate /100) * $newsubtotal,'2');
            $tax_amount = round(($tax_rate / 100) * $unitPrice,2);
            $invoiceId = $this->getStructuredOrderId($order['invoice']['id']);
            $sellerBankDetails = BankDetails::where('seller_id',$order->seller->id)->first();
            $requested_id = RmaStatus::where('slug','requested')->pluck('id');
            $requested_date = RmaHistory::where('rma_status_id',$requested_id)->where('rma_id',$rmaId)->pluck('created_at');
            $date = date('l d F Y', strtotime($requested_date));
            $amountInWords = ucwords(NumberHelper::getIndianCurrency($grossTotal));
            TCPDF::AddPage();
            TCPDF::writeHTML(view('backend.common.pdf.sales-return')->with(compact('structuredOrderId','rmaInfo','sellerAddress','orderInfo','hsn_code','tax_rate','tax_amount','tax_igst_applied','cod_payment_flag','date','orderRma','order_id','order','customerAddress','sellerAddress','brand','unitPrice','discountAmount','grossTotal','invoiceId','license','sellerBankDetails','user','amountInWords','finalUnitPrice','newsubtotal','finaltotal','gst','orderQuantityInfo'))->render());
            TCPDF::Output("Sales-return".date('Y-m-d_H_i_s').".pdf", 'D');
            //return view('backend.common.pdf.invoice')->with(compact('order','orderNo','seller','sellerAddress','customerAddress','product','brand','unitPrice','taxPrice','orderDate','sellerBankDetails','invoiceId','invoice','invoiceCreatedDate','orderDateChangeFormat'));
        }catch (\Exception $e){
            $data = [
                'user' => $this->user,
                'role' => $this->userRoleType,
                'action' => 'Generate Invoice',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }

    public function getStructuredRmaId($rmaId){
        return str_pad($rmaId, 9, "0", STR_PAD_LEFT);
    }
    public function getStructuredOrderId($orderId){
        return str_pad($orderId, 9, "0", STR_PAD_LEFT);
    }
}
