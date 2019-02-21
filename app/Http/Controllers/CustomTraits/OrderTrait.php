<?php
namespace App\Http\Controllers\CustomTraits;
use App\BankDetails;
use App\Brand;
use App\CancelReason;
use App\Category;
use App\Customer;
use App\CustomerCancelReasons;
use App\DeliveryType;
use App\FinanceDocument;
use App\FinanceDocumentOrderInfo;
use App\FinanceTransactionDetail;
use App\Helpers\NumberHelper;
use App\Holidays;
use App\HSNCodes;
use App\HSNCodeTaxRelation;
use App\Invoice;
use App\LogisticAccountingAgrosiaaShipment;
use App\LogistingAccounting;
use App\Order;
use App\OrderHistory;
use App\OrderQuantityInfo;
use App\OrderRma;
use App\OrderStatus;
use App\PaymentMethod;
use App\Product;
use App\RmaHistory;
use App\RmaReason;
use App\RmaStatus;
use App\Role;
use App\RtvMicroStatus;
use App\RtvMicroStatusDetails;
use App\Seller;
use App\SellerAddress;
use App\ShippingMethod;
use App\Tax;
use App\TransactionMode;
use App\User;
use App\UserNotification;
use App\VendorSettleMent;
use App\WorkOrderStatusDetail;
use App\WorkOrderStatusMaster;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Elibyy\TCPDF\Facades\TCPDF;
use App\VendorLicenses;
use App\CustomerAddress;
use App\OrderCustomerRelation;
use Illuminate\Support\Facades\Session;

trait OrderTrait{
    use SendMessageTrait;
    use DeliveryTrait;
    public function viewOrderList($type){
        try{
            $orderType = $type;
            if($this->userRoleType == 'seller'){
                return view('backend.seller.order.manage')->with(compact('orderType'));
            }elseif($this->userRoleType == 'superadmin' || $this->userRoleType == 'customersupport' || $this->userRoleType == 'vendorsupport'){
                $roleType = $this->userRoleType;
                return view('backend.superadmin.order.manage')->with(compact('orderType','roleType'));
            }elseif($this->userRoleType == 'shipmentadmin' || $this->userRoleType == 'shipmentpartner') {
                return view('backend.shipmentAdmin.order.manage')->with(compact('orderType'));
            }elseif($this->userRoleType == 'financeadmin') {
                return view('backend.financeAdmin.order.manage')->with(compact('orderType'));
            }elseif( $this->userRoleType == 'accountadmin'){
                $roleType = $this->userRoleType;
                return view('backend.accountAdmin.order.manage')->with(compact('orderType','roleType'));
            }
        }catch (\Exception $e){
            abort(500,$e->getMessage());
        }
    }
    public function orderListing(Request $request,$orderStatus){
        try{
            $tableData = $request->all();
            $searchData = NULL;
            $orderName=null;
            $shipping_method_id = ShippingMethod::where('user_id',$this->user['id'])->pluck('id');
            $orderStatusId = OrderStatus::where('slug', $orderStatus)->first();
            $orderstatusArray = array();
            if ($orderStatus == 'complete') {
                $orderstatusArray = OrderStatus::whereIn('slug',['complete','close','confirmed','pending'])->lists('id');
            } else {
                $orderstatusArray[] = $orderStatusId['id'];
            }
            if($orderStatusId !=null){
                if($this->userRoleType == 'seller'){
                    $sellerProducts = Product::where('seller_id',$this->seller->id)->lists('id');
                    $orderIds = Order::whereIn('order_status_id',$orderstatusArray)->whereIn('product_id',$sellerProducts)->lists('id');
                }elseif($this->userRoleType == 'shipmentpartner'){
                    $orderIds = Order::whereIN('order_status_id', $orderstatusArray)->where('shipping_method_id', $shipping_method_id)->lists('id');
                } elseif ($this->userRoleType == 'shipmentadmin'){
                    $orderIds = Order::whereIN('order_status_id', $orderstatusArray)->lists('id');
                } else {
                    $orderstatusArray = array();
                    $orderstatusArray[] = $orderStatusId['id'];
                    $orderIds = Order::whereIn('order_status_id',$orderstatusArray)->lists('id');
                }
                $resultFlag = true;
                if($request->has('order_id') && $tableData['order_id']!=""){
                    $orderIds = Order::whereIn('id',$orderIds)->whereIn('order_status_id',$orderstatusArray)->where('id',$tableData['order_id'])->lists('id')->toArray();
                    if(count($orderIds) <= 0){
                        $resultFlag = false;
                    }
                }
                if($resultFlag == true && $request->has('order_customer_name') &&  $tableData['order_customer_name']!="" ){
                    $productName = trim($request->order_customer_name);
                    $orderIds = Order::join('products','products.id','=','orders.product_id')
                        ->whereIn('orders.id',$orderIds)
                        ->where('product_name','ILIKE','%'.$productName.'%')
                        ->lists('orders.id');
                    if(count($orderIds) <= 0){
                        $resultFlag = false;
                    }
                }
                if($resultFlag == true && $request->has('order_ship_to') &&  $tableData['order_ship_to']!="" ){
                    $discountPrice= trim($tableData['order_ship_to']);
                    $orderIds = Order::whereIn('id',$orderIds)
                        ->where('discounted_price',$discountPrice)
                        ->lists('id')->toArray();
                    if(count($orderIds) <= 0){
                        $resultFlag = false;
                    }
                }
                if($resultFlag == true && $request->has('quantity') &&  $tableData['quantity']!="" ){
                    $quantity = trim($tableData['quantity']);
                    $orderIds = Order::whereIn('id',$orderIds)
                        ->where('quantity',$quantity)
                        ->lists('id')->toArray();
                    if(count($orderIds) <= 0){
                        $resultFlag = false;
                    }
                }
                $iTotalRecords = count($orderIds);
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
                $limitedProducts = Order::whereIn('order_status_id',$orderstatusArray)->whereIn('id',$orderIds)->take($iDisplayLength)->skip($iDisplayStart)->orderBy('created_at','desc')->get()->toArray();
                for($i=0,$j = $iDisplayStart; $j < $end; $i++,$j++) {
                    if($this->userRoleType == 'seller'){
                        $orderDetailView = "/order/view/".$limitedProducts[$j]["id"];
                    }elseif($this->userRoleType == 'superadmin'){
                        $orderDetailView = "/operational/order/view/".$limitedProducts[$j]["id"];
                    }elseif($this->userRoleType == 'shipmentadmin' || $this->userRoleType == 'shipmentpartner') {
                        $orderDetailView = "/shipment/order/view/".$limitedProducts[$j]["id"];
                    }elseif($this->userRoleType == 'financeadmin') {
                        $orderDetailView = "/shipment/order/view/".$limitedProducts[$j]["id"];
                    }elseif($this->userRoleType == "customersupport"){
                        $orderDetailView = "/customer-support/order/view/".$limitedProducts[$j]["id"];
                    }elseif($this->userRoleType == "vendorsupport"){
                        $orderDetailView = "/vendor-support/order/view/".$limitedProducts[$j]["id"];
                    }elseif($this->userRoleType == "accountadmin"){
                        $orderDetailView = "/vendor/order/view/".$limitedProducts[$j]["id"];
                    }
                    $structuredId = $this->getStructuredOrderId($limitedProducts[$j]['id']);
                    if($orderStatusId['slug'] == 'ready_to_pick' || $orderStatusId['slug'] == 'transit'){
                        $dateDisplay = date('d F Y',strtotime(Order::where('id',$limitedProducts[$j]["id"])->pluck('delivery_date')));
                    }elseif($orderStatusId['slug'] == 'complete'){
                        $dateDisplay = date('d F Y',strtotime(OrderHistory::where('order_id',$limitedProducts[$j]["id"])->where('order_status_id',$orderStatusId['id'])->pluck('created_at')));
                    }elseif($orderStatusId['slug'] == 'close' || $orderStatusId['slug'] == 'confirmed'){
                        $id = OrderStatus::where('slug','=','complete')->pluck('id');
                        $dateDisplay = date('d F Y',strtotime(OrderHistory::where('order_id',$limitedProducts[$j]["id"])->where('order_status_id',$id)->pluck('created_at')));
                    }else{
                        $dateDisplay = $limitedProducts[$j]['updated_at'];
                    }
                    $deliveryType = DeliveryType::where('id',$limitedProducts[$j]['delivery_type_id'])->pluck('slug');
                    $dispatchDate = date('d F Y H:i:s',strtotime($limitedProducts[$i]['dispatch_date']));
                    $datetime = $dispatchDate;
                    if($orderStatusId['slug'] == 'ready_to_ship'){
                        $dispatchDate = $this->getPickUpDate($datetime,$deliveryType);
                    }
                    $productName = Product::where('id',$limitedProducts[$j]['product_id'])->first();
                    if($limitedProducts[$j]['is_web_order'] == true){
                        $orderFrom = 'Web';
                    }elseif($limitedProducts[$j]['is_web_order'] == false){
                        $orderFrom = 'Mobile';
                    }else{
                        $orderFrom = '';
                    }
                    if($limitedProducts[$j]['is_configurable'] == true){
                        $displayPrice = (($limitedProducts[$j]["discounted_price"]+$limitedProducts[$j]["delivery_amount"]-$limitedProducts[$j]['coupon_discount']) * (($limitedProducts[$j]["length"]) * ($limitedProducts[$j]["width"])) * $limitedProducts[$j]['quantity']);
                    }else{
                        $displayPrice = (($limitedProducts[$j]["discounted_price"]+$limitedProducts[$j]["delivery_amount"]-$limitedProducts[$j]['coupon_discount']) * $limitedProducts[$j]['quantity']);
                    }
                    if($this->userRoleType == 'superadmin' || $this->userRoleType == 'accountadmin'){
                        if($orderStatusId['slug'] == 'ready_to_pick' || $orderStatusId['slug'] == 'transit' || $orderStatusId['slug'] == 'complete' || $orderStatusId['slug'] == 'return_to_vendor'){
                            $records["data"][] = array(
                                '<input type="checkbox" name="id[]" value="'.$limitedProducts[$j]['id'].'">',
                                "AGR".$structuredId,
                                $orderFrom,
                                date('d F Y H:i:s',strtotime($limitedProducts[$j]['created_at'])),
                                ucwords($productName['product_name']),
                                $limitedProducts[$j]['quantity'],
                                $displayPrice,
                                date('d F Y',strtotime($dateDisplay)),
                                '<a href='.$orderDetailView.' class="btn btn-sm btn-default btn-circle btn-editable"><i class="fa fa-pencil"></i> View</a>',
                            );
                        }elseif($orderStatusId['slug'] == 'declined' || $orderStatusId['slug'] == 'back_ordered' || $orderStatusId['slug'] == 'failed' || $orderStatusId['slug'] == 'cancel' || $orderStatusId['slug'] == 'abort' || $orderStatusId['slug'] == 'refused' ){
                            $records["data"][] = array(
                                '<input type="checkbox" name="id[]" value="'.$limitedProducts[$j]['id'].'">',
                                "AGR".$structuredId,
                                $orderFrom,
                                date('d F Y H:i:s',strtotime($limitedProducts[$j]['created_at'])),
                                ucwords($productName['product_name']),
                                $limitedProducts[$j]['quantity'],
                                $displayPrice,
                                '<a href='.$orderDetailView.' class="btn btn-sm btn-default btn-circle btn-editable"><i class="fa fa-pencil"></i> View</a>',
                            );
                        }elseif($orderStatusId['slug'] == 'close' || $orderStatusId['slug'] == 'confirmed' || $orderStatusId['slug'] == "pending"){
                            $records["data"][] = array(
                                '<input type="checkbox" name="id[]" value="'.$limitedProducts[$j]['id'].'">',
                                "AGR".$structuredId,
                                $orderFrom,
                                date('d F Y H:i:s',strtotime($limitedProducts[$j]['created_at'])),
                                ucwords($productName['product_name']),
                                $limitedProducts[$j]['quantity'],
                                $displayPrice,
                                date('d F Y',strtotime($dateDisplay)),
                                '<a href='.$orderDetailView.' class="btn btn-sm btn-default btn-circle btn-editable"><i class="fa fa-pencil"></i> View</a>',
                            );
                        }else{
                            $records["data"][] = array(
                                '<input type="checkbox" name="id[]" value="'.$limitedProducts[$j]['id'].'">',
                                "AGR".$structuredId,
                                $orderFrom,
                                date('d F Y',strtotime($limitedProducts[$j]['created_at'])),
                                ucwords($productName['product_name']),
                                $dispatchDate,
                                $limitedProducts[$j]['quantity'],
                                $displayPrice,
                                '<a href='.$orderDetailView.' class="btn btn-sm btn-default btn-circle btn-editable"><i class="fa fa-pencil"></i> View</a>',
                            );
                        }
                    }else{
                        if($orderStatusId['slug'] == 'ready_to_pick' || $orderStatusId['slug'] == 'transit' || $orderStatusId['slug'] == 'complete'){
                            $records["data"][] = array(
                                '<input type="checkbox" name="id[]" value="'.$limitedProducts[$j]['id'].'">',
                                "AGR".$structuredId,
                                date('d F Y H:i:s',strtotime($limitedProducts[$j]['created_at'])),
                                ucwords($productName['product_name']),
                                $limitedProducts[$j]['quantity'],
                                $displayPrice,
                                date('d F Y',strtotime($dateDisplay)),
                                '<a href='.$orderDetailView.' class="btn btn-sm btn-default btn-circle btn-editable"><i class="fa fa-pencil"></i> View</a>',
                            );
                        }elseif($orderStatusId['slug'] == 'declined' || $orderStatusId['slug'] == 'back_ordered' || $orderStatusId['slug'] == 'failed' || $orderStatusId['slug'] == 'cancel' || $orderStatusId['slug'] == 'abort' || $orderStatusId['slug'] == 'refused' ){
                            $records["data"][] = array(
                                '<input type="checkbox" name="id[]" value="'.$limitedProducts[$j]['id'].'">',
                                "AGR".$structuredId,
                                date('d F Y H:i:s',strtotime($limitedProducts[$j]['created_at'])),
                                ucwords($productName['product_name']),
                                $limitedProducts[$j]['quantity'],
                                $displayPrice,
                                '<a href='.$orderDetailView.' class="btn btn-sm btn-default btn-circle btn-editable"><i class="fa fa-pencil"></i> View</a>',
                            );
                        }else{
                            $records["data"][] = array(
                                '<input type="checkbox" name="id[]" value="'.$limitedProducts[$j]['id'].'">',
                                "AGR".$structuredId,
                                date('d F Y',strtotime($limitedProducts[$j]['created_at'])),
                                ucwords($productName['product_name']),
                                $dispatchDate,
                                $limitedProducts[$j]['quantity'],
                                $displayPrice,
                                '<a href='.$orderDetailView.' class="btn btn-sm btn-default btn-circle btn-editable"><i class="fa fa-pencil"></i> View</a>',
                            );
                        }
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
    public function getPackingCheckListData(){
        try{
            $orderStatusId = OrderStatus::where('slug','to_pack')->first();
            if($this->userRoleType=='seller'){
                $sellerProducts = Product::where('seller_id',$this->seller->id)->lists('id');
                $totalRecords = Order::where('order_status_id',$orderStatusId['id'])->whereIn('product_id',$sellerProducts)->with('product')->get()->toArray();
            }elseif($this->userRoleType=='superadmin'){
                $totalRecords = Order::where('order_status_id',$orderStatusId['id'])->get();
            } elseif($this->userRoleType=='shipmentadmin'){
                $totalRecords = Order::where('order_status_id',$orderStatusId['id'])->get();
            }
            return view('backend.partials.seller.order.packing-slip-table')->with(compact('totalRecords'));
        }catch(\Exception $e){
            $data = [
                'user' => $this->user,
                'role' => $this->userRoleType,
                'action' => 'get oder data packing slip',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }
    public function orderDetailView(Request $request,$orderId){
        try{
            $orderInfo = Order::where('id',$orderId)->with('product','customer','shippingMethod','PaymentMethod','DeliveryMethod','orderStatus','orderHistory','ordersCustomerInfo')->first();
            $rootCategory['category_id'] = $orderInfo->product->productCategory[0]->category_id;
            for(;$rootCategory['category_id'] != null;){
                $rootCategory = Category::where('id',$rootCategory['category_id'])->select('category_id','slug')->first()->toArray();
            }
            $needExpiryDateCategories = env('NEED_PRODUCT_EXPIRY_DATE_CATEGORIES');
            $needExpiryDateCategories = explode(';',$needExpiryDateCategories);
            if(in_array($rootCategory['slug'],$needExpiryDateCategories)){
                $needProductExpiryDate = true;
            }else{
                $needProductExpiryDate = false;
            }
            if(Session::get('role_type') == 'shipmentadmin' || Session::get('role_type') == 'superadmin' || Session::get('role_type')=='seller' || Session::get('role_type')=='accountadmin'){
                $allCarrier = ShippingMethod::get();
            }
            elseif(Session::get('role_type') == 'shipmentpartner'){
                $allCarrier = ShippingMethod::where('user_id',$this->user->id)->get();
            }else{
                $allCarrier = array();
            }
            $currentEmailStatus = OrderHistory::where('order_id',$orderId)->where('order_status_id',$orderInfo->order_status_id)->first();
            $orderInfo['currentEmailStatus'] = $currentEmailStatus;
            $bAddress = json_decode($orderInfo->ordersCustomerInfo->billing_address);
            $sAddress = json_decode($orderInfo->ordersCustomerInfo->shipping_address);
            $citrusData = json_decode($orderInfo->payment_gateway_data);
            $orderInfo['sellerProductPickAddress'] = json_decode($orderInfo->seller_address);
            $orderInfo['billingAddress'] = $bAddress;
            $orderInfo['shippingAddress'] = $sAddress;
            if(strtoupper($orderInfo['sellerProductPickAddress']->state) != strtoupper($orderInfo['shippingAddress']->state)){
                $tax_igst_applied = true;
            }else{
                $tax_igst_applied = false;
            }
            $orderInfo['citrusData'] = $citrusData;
            $orderInfo['order_number'] = $this->getStructuredOrderId($orderId);
            $orderInfo['order_date'] = $orderInfo->created_at->format('d F Y H:i:s');
            $customerInfo = User::where('id',$orderInfo->customer->user_id)->first();
            $seller = Seller::where('id',$orderInfo->product->seller_id)->first();
            $sellerInfo = User::where('id',$seller['user_id'])->first();
            $orderInfo['customerInfo'] = $customerInfo;
            $orderInfo['sellerInfo'] = $sellerInfo;
            $ordersHistory = $orderInfo->orderHistory->toArray();
            $masterHistory = array();
            foreach($ordersHistory as $orderHistory){
                $status = OrderStatus::where('id',$orderHistory['order_status_id'])->first();
                $orderHistory['date'] = date('d F Y H:i:s',strtotime($orderHistory['created_at']));
                $orderHistory['status'] = $status->display_name;
                $orderHistory['cancel_reason'] = CustomerCancelReasons::where('id',$orderHistory['customer_cancel_reasons_id'])->pluck('reason');
                if($orderHistory['is_email_sent'] == 0){
                    $orderHistory['email_notification'] = 'Customer Not Notified';
                }else{
                    $orderHistory['email_notification'] = 'Customer Notified';
                }
                array_push($masterHistory,$orderHistory);
            }
            $currentTime = Carbon::now();
            $orderInfo['History'] = $masterHistory;
            if($orderInfo->product->brand_id != null){
                $brandInfo = Brand::where('id',$orderInfo->product->brand_id)->first();
                $brandName = $brandInfo->name;
            }else{
                $brandName = null;
            }
            $orderStatusId = OrderStatus::where('slug','to_pack')->first();
            $orderPrice =  $orderInfo->selling_price;
            if($orderInfo['is_configurable'] == true){
                $area = $orderInfo['length'] * $orderInfo['width'];
                $orderSellingTotal = ($orderPrice * $area) * $orderInfo->quantity;
                $subtotal = ($orderInfo->discounted_price * $area) * $orderInfo->quantity;
            }else{
                $orderSellingTotal = $orderPrice * $orderInfo->quantity;
                $subtotal = $orderInfo->discounted_price * $orderInfo->quantity;
            }
            $discountAmount = $orderSellingTotal - $subtotal;
            $paymentType = PaymentMethod::find($orderInfo->payment_method_id);
            if($paymentType->slug == 'cod'){
                $total_paid = 0;
                $total_due = ($subtotal + $orderInfo->delivery_amount) + $total_paid;
            }elseif($paymentType->slug == 'citrus'){
                $total_paid = $subtotal + $orderInfo->delivery_amount;
                $total_due = 0 ;
            }
            if($orderInfo->coupon_discount==NULL){
                $orderInfo->coupon_discount = 0;
            }
            $coupon_discount = 0;
            $itemsOrdered = array(
                'product_id' => $orderInfo->product->id,
                'product_name' => $orderInfo->product->product_name,
                'sku' => $orderInfo->product->item_based_sku,
                'brand_name' => $brandName,
                'price' => $orderPrice,
                'quantity' => $orderInfo->quantity,
                'discount_percent' => $orderInfo->discount,
                'discount_amount' => $discountAmount,
                'row_total' => $subtotal,
                'subtotal' => $orderSellingTotal,
                'delivery_amount' => $orderInfo->delivery_amount,
                'grand_total' => $subtotal + $orderInfo->delivery_amount,
                'total_paid' => $total_paid,
                'total_due' => $total_due,
                'coupon_discount' => $coupon_discount
            );
            $orderInfo['itemsOrdered'] = $itemsOrdered;
            if($orderInfo['order_status_id'] != $orderStatusId->id){
                $itemsInvoicedPriceExTax = round($orderInfo->discounted_price / (($orderInfo->tax_rate / 100)+1),2);
                $discountAmount = $subtotal * ($orderInfo->discount /100);
                if($tax_igst_applied == true){
                    $tax_rate = $orderInfo->tax_rate;
                    $tax = (($tax_rate)/100)+1 ;
                }else{
                    $tax_rate = $orderInfo->tax_rate / 2;
                    $tax = (($tax_rate * 2)/100)+1 ;
                }
                $finaltotal = $orderInfo['itemsOrdered']['grand_total'];
                $subtotal = $finaltotal / $tax;
                $finalUnitPrice = $subtotal/$orderInfo['quantity'];
                $gst = ($tax_rate /100) * $subtotal;
                $itemsInvoiced = array(
                    'product_id' => $orderInfo->product->id,
                    'product_name' => $orderInfo->product->product_name,
                    'sku' => $orderInfo->product->item_based_sku,
                    'brand_name' => $brandName,
                    'price' => $itemsInvoicedPriceExTax,
                    'quantity' => $orderInfo->quantity,
                    'tax_rate' => $tax_rate,
                    'final_unit_price' => round($finalUnitPrice,'2'),
                    'tax_amount' => round($gst,'2'),
                    'selling_price' => $orderInfo->selling_price,
                    'discount' => $orderInfo->discount,
                    'discount_amount' => round($discountAmount),
                    'delivery_amount' => $orderInfo->delivery_amount,
                    'sub_total' => round($subtotal,'2'),
                    'tax_igst_applied' => $tax_igst_applied,
                    'grand_total' => $finaltotal,
                    'coupon_discount' => $coupon_discount
                );
                $orderInfo['itemsInvoiced'] = $itemsInvoiced;
            }
            $transactionModes = array();
            if($this->userRoleType=='financeadmin' && $orderInfo->orderStatus->slug == 'complete'){
                $receiptVoucherId = FinanceDocument::where('slug','receipt_voucher')->pluck('id');
                $paymentAdviceId = FinanceDocument::where('slug','payment_advice')->pluck('id');
                $paymentVoucherId = FinanceDocument::where('slug','payment_voucher')->pluck('id');
                $financeDocOrderInfo = FinanceDocumentOrderInfo::where('order_id',$orderInfo->id)->where('finance_doc_id',$receiptVoucherId)->first();
                $transactionModes = TransactionMode::get()->toArray();
                if($financeDocOrderInfo != null && $financeDocOrderInfo->transactionDetails != null){
                    $transactionDetails = $financeDocOrderInfo->transactionDetails->toArray();
                    $transactionDetails['deposit_date'] = date('j F Y',strtotime($transactionDetails['deposit_date']));
                    $transactionDetails['transaction_date'] = date('j F Y',strtotime($transactionDetails['transaction_date']));
                    $transactionDetails['reconciled_amount'] = $orderInfo['itemsInvoiced']['grand_total'];
                    $transactionDetails['transaction_particulars'] = TransactionMode::where('id',$transactionDetails['transaction_mode_id'])->pluck('name');
                }
                $paymentAdviceInfo = FinanceDocumentOrderInfo::where('order_id',$orderId)->where('finance_doc_id',$paymentAdviceId)->first();
                if(!empty($paymentAdviceInfo)){
                    $paymentAdviceInfo['date'] = date('d F Y H:i:s',strtotime($paymentAdviceInfo['created_at']));
                }
                $paymentVoucherInfo = FinanceDocumentOrderInfo::where('order_id',$orderId)->where('finance_doc_id',$paymentVoucherId)->first();
                if($paymentVoucherInfo != null && $paymentVoucherInfo->transactionDetails != null){
                    $paymentVoucherInfo = $paymentVoucherInfo->toArray();
                    $paymentVoucherInfo['date'] = date('d F Y H:i:s',strtotime($paymentVoucherInfo['created_at']));
                    $settlementTransactionDetails = $paymentVoucherInfo['transaction_details'];
                    $settlementTransactionDetails['deposit_date'] = date('j F Y',strtotime($settlementTransactionDetails['deposit_date']));
                    $settlementTransactionDetails['transaction_date'] = date('j F Y',strtotime($settlementTransactionDetails['transaction_date']));
                    $settlementTransactionDetails['reconciled_amount'] = $orderInfo['itemsInvoiced']['grand_total'];
                    $settlementTransactionDetails['transaction_particulars'] = TransactionMode::where('id',$settlementTransactionDetails['transaction_mode_id'])->pluck('name');
                }
            }
            $orderInfo['lastUpdated'] = date('d F Y H:i:s',strtotime($orderInfo['updated_at']));
            $cacnelOrderStatusId = OrderStatus::wherein('slug',['cancel','back_ordered','declined'])->get()->pluck('id');
            $cancelOrderHistory = OrderHistory::where('order_id',$orderId)->wherein('order_status_id',$cacnelOrderStatusId)->with('cancel')->first();
            $cancelReasons = CancelReason::where('role','seller')->get();
            if($orderInfo['orderStatus']['slug'] == 'ready_to_pick'){
                $orderInfo['orderStatus']['display_name'] = 'Pickup Acknowledgement';
            }
            if($this->userRoleType == 'superadmin' || $this->userRoleType == 'shipmentadmin' || $this->userRoleType == 'shipmentpartner' || $this->userRoleType == 'customersupport' || $this->userRoleType == 'vendorsupport'){
                $receiptVoucherId = FinanceDocument::where('slug','receipt_voucher')->pluck('id');
                $financeDocOrderDetail = FinanceDocumentOrderInfo::where('finance_doc_id',$receiptVoucherId)->where('order_id',$orderId)->first();
                if($financeDocOrderDetail != null){
                    $transactionDetails = FinanceTransactionDetail::where('id',$financeDocOrderDetail['finance_transaction_details_id'])->first();
                    if($transactionDetails != null){
                        $transactionDetails = $transactionDetails->toArray();
                        $transactionMode = TransactionMode::where('id',$transactionDetails['transaction_mode_id'])->pluck('name');
                        $orderIds = FinanceDocumentOrderInfo::where('finance_transaction_details_id',$financeDocOrderDetail['finance_transaction_details_id'])->lists('order_id')->toArray();
                        for($i = 0 ; $i < count($orderIds) ; $i++){
                            $orderIds[$i] = 'AGR'.$this->getStructuredOrderId($orderIds[$i]);
                        }
                        $structuredOrderData = implode(',',$orderIds);
                    }else{
                        $transactionDetails = null;
                        $transactionMode = null;
                        $structuredOrderData = null;
                    }
                }else{
                    $transactionDetails = null;
                    $transactionMode = null;
                    $structuredOrderData = null;
                }
            }
            $orderHistoryListing = array();
            $countOfClose = OrderHistory::where('order_id', $orderId)->where('order_status_id', OrderStatus::where('slug','close')->pluck('id'))->count();
            $countOfPending = OrderHistory::where('order_id', $orderId)->where('order_status_id', OrderStatus::where('slug','pending')->pluck('id'))->count();
            $i = 0;
            $countofCloseLoop = 0;
            $countOfPendingLoop = 0;
            foreach($orderInfo['History'] as $history){
                $rtvMicroStatusHistory = RtvMicroStatusDetails::join('rtv_micro_status','rtv_micro_status.id','=','rtv_micro_status_details.rtv_micro_status_id')
                    ->where('rtv_micro_status_details.order_id',$orderId)
                    ->select('rtv_micro_status.name','rtv_micro_status.slug','rtv_micro_status_details.rtv_micro_status_id','rtv_micro_status_details.reconcile_order_number','rtv_micro_status_details.created_at')->first();
                $orderHistoryListing[$i]['date'] = $history['date'];
                if($this->userRoleType == 'shipmentadmin' || $this->userRoleType == 'shipmentpartner'){
                    if($history['status'] == 'Complete'){
                        $orderHistoryListing[$i]['message'] = "<p>".$history['date']." | ". $history['status'] ."| ".$history['email_notification']." | Shipment has acknowledged for receipt of collection of cash for this order</p>";
                    }elseif($history['status'] == 'Cancel' || $history['status'] == 'Back Ordered' || $history['status'] == 'Declined'){
                        $orderHistoryListing[$i]['message'] = "<p>".$history['date']." | ".$history['status']." <a href='javascript:void(0)' data-toggle='modal' data-target='#order-cancel-detail' class='btn btn-success btn-sm'> View </a> </p>";
                    }elseif ($history['status'] == 'Close' || $history['status'] == 'Confirmed' || $history['status'] == 'Pending')  {
                        $orderHistoryListing[$i]['message'] = "";
                    }else{
                        $orderHistoryListing[$i]['message'] = "<p>".$history['date']." | ". $history['status'] ." | ".$history['email_notification']." </p>";
                    }
                    if($history['status'] == 'Refused'){
                        $orderHistoryListing[$i]['message'] = "<p>".$history['date']." | ". $history['status'] ." | Reason for Cancellation |<b>".$history['comment']."</b> </p>";
                    }
                }else{
                    if(($this->userRoleType == 'superadmin' || $this->userRoleType == 'seller' || $this->userRoleType == 'customersupport') && $history['status'] == 'Complete'){
                        $orderHistoryListing[$i]['message'] = "<p>".$history['date']." | ". $history['status'] ." | Shipment has acknowledged for receipt of collection of cash for this order</p>";
                    }elseif($history['status'] == 'Cancel' || $history['status'] == 'Back Ordered' || $history['status'] == 'Declined'){
                        $orderHistoryListing[$i]['message'] = "<p>".$history['date']." | ".$history['status']." <a href='javascript:void(0)' data-toggle='modal' data-target='#order-cancel-detail' class='btn btn-success btn-sm'> View </a> </p>";
                    }elseif($history['status'] == 'Pending' && $history['created_at'] == $history['updated_at']){
                        if ($this->userRoleType != 'seller' ) {
                            if ($countOfPendingLoop == $countOfPending - 1) {
                                $orderHistoryListing[$i]['message'] = "<p>" . $history['date'] . " | " . "Account Pending" . " |  Logistics Accounting details updated for the order number AGR$orderInfo->order_number <a id='view' href='#tab_6' data-toggle='tab' class='btn btn-success'> View </a></p>";
                            }else{
                                $orderHistoryListing[$i]['message'] = "<p>" . $history['date'] . " | " . "Account Pending" . " |  Logistics Accounting details updated for the order number AGR$orderInfo->order_number </p>";
                            }
                            $countOfPendingLoop++;
                        } else {
                            $orderHistoryListing[$i]['message'] = "";
                        }
                    }elseif($history['status'] == 'Pending' && $history['created_at'] != $history['updated_at']){
                        if ($this->userRoleType != 'seller' ) {
                                $orderHistoryListing[$i]['message'] = "<p>" . $history['date'] . " | " . "Account Pending (Updated)" . " |  Logistics Accounting details updated for the order number AGR$orderInfo->order_number </p>";
                            $countOfPendingLoop++;
                        } else {
                            $orderHistoryListing[$i]['message'] = "";
                        }
                    }elseif($history['status'] == 'Close' && $history['created_at'] == $history['updated_at']){
                        if ($this->userRoleType != 'seller' ) {
                            if ($countofCloseLoop == $countOfClose - 1) {
                                $orderHistoryListing[$i]['message'] = "<p>" . $history['date'] . " | " . "Account Closed" . " | Logistics Accounting details updated for the order number AGR$orderInfo->order_number <a id='view' href='#tab_6' data-toggle='tab' class='btn btn-success'> View </a></p>";
                            } else {
                                $orderHistoryListing[$i]['message'] = "<p>" . $history['date'] . " | " . "Account Closed" . " | Logistics Accounting details updated for the order number AGR$orderInfo->order_number</p>";
                            }
                            $countofCloseLoop++;
                        } else {
                            $orderHistoryListing[$i]['message'] = "";
                        }
                    }elseif($history['status'] == 'Close' && $history['created_at'] != $history['updated_at']){
                        if ($this->userRoleType != 'seller' ) {
                            $updated_date = date_create($history['updated_at']);
                            $updated_date = date_format($updated_date, "d F Y H:i:s");
                            if ($countofCloseLoop == $countOfClose - 1) {
                                $orderHistoryListing[$i]['message'] = "<p>" . $updated_date . " | " . "Account Closed (Updated)" . " | Logistics Accounting details updated for the order number AGR$orderInfo->order_number <a id='view' href='#tab_6' data-toggle='tab' class='btn btn-success'> View </a></p>";
                            } else {
                                $orderHistoryListing[$i]['message'] = "<p>" . $updated_date . " | " . "Account Closed (Updated)" . " | Logistics Accounting details updated for the order number AGR$orderInfo->order_number</p>";
                            }
                            $countofCloseLoop++;
                        } else {
                                $orderHistoryListing[$i]['message'] = "";
                        }
                   }elseif($history['status'] == 'Confirmed'){
                        if ($this->userRoleType != 'seller' ) {

                            $orderHistoryListing[$i]['message'] = "<p>" . $history['date'] . " | " . "Account Confirmed" . " | Shipment COD & Bill details confirmed by accounts department </p>";
                        } else {
                            $orderHistoryListing[$i]['message'] = "";
                        }
                    }elseif($history['status'] == 'RTV'){
                        $rtv_updated_date = date_create($rtvMicroStatusHistory['created_at']);
                        $rtv_updated_date = date_format($rtv_updated_date, "d F Y H:i:s");
                        $orderHistoryListing[$i]['message'] = "<p>" . $history['date'] . " | " . "RTV" . " | Order number AGR$orderInfo->order_number was marked as Return To Vendor "." <a href='javascript:void(0)' data-toggle='modal' data-target='#order-cancel-detail' class='btn btn-success btn-sm'> View </a>"."</p>";
                        if($this->userRoleType == 'superadmin'){
                            if($rtvMicroStatusHistory['rtv_micro_status_id'] == 1 || $rtvMicroStatusHistory['rtv_micro_status_id'] == 2){
                                $orderHistoryListing[$i]['message1'] = "<p>" . $rtv_updated_date . " | " . "RTV" . " | $rtvMicroStatusHistory->name"."<a href='javascript:void(0)' class=\"btn red\" type=\"submit\" data-toggle=\"modal\" data-target=\"#return_to_vendor\"> Edit </a> </p>";
                            }elseif($rtvMicroStatusHistory['rtv_micro_status_id'] == 3 ){
                                $orderHistoryListing[$i]['message1'] = "<p>" . $rtv_updated_date . " | " . "RTV" . " | $rtvMicroStatusHistory->name"." "."reconcile against order no"." "."$rtvMicroStatusHistory[reconcile_order_number] "."<a href='javascript:void(0)' class=\"btn red\" type=\"submit\" data-toggle=\"modal\" data-target=\"#return_to_vendor\"> Edit </a> </p>";
                            }
                        }else{
                            if($rtvMicroStatusHistory['rtv_micro_status_id'] == 1 || $rtvMicroStatusHistory['rtv_micro_status_id'] == 2){
                                $orderHistoryListing[$i]['message1'] = "<p>" . $rtv_updated_date . " | " . "RTV" . " | $rtvMicroStatusHistory->name". "</p>";
                            }elseif($rtvMicroStatusHistory['rtv_micro_status_id'] == 3 ){
                                $orderHistoryListing[$i]['message1'] = "<p>" . $rtv_updated_date . " | " . "RTV" . " | $rtvMicroStatusHistory->name"." "."reconcile against order no"." "."$rtvMicroStatusHistory[reconcile_order_number] "."</p>";
                            }
                        }

                    }elseif($history['work_order_status_id'] != null){
                        $orderHistoryListing[$i]['message'] = "<p>".'Super Admin'." | ".$history['date']." | ".WorkOrderStatusMaster::where('id',$history['work_order_status_id'])->pluck('name')."</p>";
                    }else{
                        $orderHistoryListing[$i]['message'] = "<p>".$history['date']." | ". $history['status'] ."</p>";
                    }
                }
                $i++;
            }
            if($orderInfo['notify_pick_up_time_shipment'] != null){
                $orderHistoryListing[$i]['date'] = $orderInfo['notify_pick_up_time_shipment'];
                $orderHistoryListing[$i]['message'] = "<p>".date('d F Y H:i:s',strtotime($orderHistoryListing[$i]['date']))." | Shipment has notified for successful product pickup</p>";
                $i++;
            }
            if($orderInfo['notify_mark_as_pick_up_time_vendor'] != null){
                $orderHistoryListing[$i]['date'] = $orderInfo['notify_mark_as_pick_up_time_vendor'];
                $orderHistoryListing[$i]['message'] = "<p>".date('d F Y H:i:s',strtotime($orderHistoryListing[$i]['date']))." | Vendor has acknowledged successful product pickup</p>";
                $i++;
            }
            if($orderInfo['delivery_time_shipment'] != null){
                $orderHistoryListing[$i]['date'] = $orderInfo['delivery_time_shipment'];
                $orderHistoryListing[$i]['message'] = "<p>".date('d F Y H:i:s',strtotime($orderHistoryListing[$i]['date']))." | Shipment Partner ".$orderInfo['shippingMethod']['name']." Courier has notified successful order delivery</p>";
                $i++;
            }
            if(($this->userRoleType == 'superadmin' || $this->userRoleType == 'shipmentadmin' || $this->userRoleType == 'shipmentpartner') && $orderInfo['slip_number'] != null){
                $orderHistoryListing[$i]['date'] = $orderInfo['lastUpdated'];
                $orderHistoryListing[$i]['message'] = "<p>".$orderHistoryListing[$i]['date']." | Shipment has acknowledged for receipt of collection of cash for this order against deposit slip number := ".$orderInfo['slip_number']."</p>";
                $i++;
            }
            if(($this->userRoleType == 'superadmin' || $this->userRoleType == 'shipmentadmin' || $this->userRoleType == 'shipmentpartner' || $this->userRoleType == 'customersupport') && $financeDocOrderDetail['created_at'] != null){
                $orderHistoryListing[$i]['date'] = $financeDocOrderDetail['created_at'];
                if($transactionDetails == null){
                    $orderHistoryListing[$i]['message'] = "<p>".date('d F Y H:i:s',strtotime($financeDocOrderDetail['created_at'])). " | Shipment ".$orderInfo['shippingMethod']['name']." Couriers has deposited cash amounting to INR ".$transactionDetails['amount']." against orders ".$structuredOrderData.". <br>
                      &nbsp;&nbsp;&nbsp; Transaction details are as follows : <br>
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Transaction Mode : ".$transactionMode." <br>
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Transaction No. :  <br>
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Transaction Date :<br>
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Deposit Date :  <br>
                    </p>";
                }else{
                    $orderHistoryListing[$i]['message'] = "<p>".date('d F Y H:i:s',strtotime($financeDocOrderDetail['created_at'])). " | Shipment ".$orderInfo['shippingMethod']['name']." Couriers has deposited cash amounting to INR ".$transactionDetails['amount']." against orders ".$structuredOrderData.". <br>
                      &nbsp;&nbsp;&nbsp; Transaction details are as follows : <br>
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Transaction Mode : ".$transactionMode." <br>
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Transaction No. : ".$transactionDetails['transaction_number']." <br>
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Transaction Date : ".date('d F Y',strtotime($transactionDetails['transaction_date']))." <br>
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Deposit Date : ".date('d F Y',strtotime($transactionDetails['deposit_date']))." <br>
                    </p>";
                }
                $i++;
            }
            usort($orderHistoryListing, function($date1,$date2){
                $sortedDate1 = strtotime($date1['date']);
                $sortedDate2 = strtotime($date2['date']);
                return $sortedDate1 - $sortedDate2;
            });
            $rtvMicroStatus = RtvMicroStatusDetails::join('rtv_micro_status','rtv_micro_status.id','=','rtv_micro_status_details.rtv_micro_status_id')
                                                        ->where('rtv_micro_status_details.order_id',$orderId)
                                                        ->select('rtv_micro_status.name','rtv_micro_status_details.rtv_micro_status_id','rtv_micro_status_details.reconcile_order_number')->first();

            $logisticInfo = LogistingAccounting::where('order_id',$orderId)->first();
            $logisticInfoAgrosiaaShipment = LogisticAccountingAgrosiaaShipment::where('order_id',$orderId)->first();
            $workStatus = WorkOrderStatusMaster::get();
            if($this->userRoleType == 'seller'){
                return view('backend.seller.order.view')->with(compact('orderHistoryListing','orderInfo','allCarrier','cancelReasons','cancelOrderHistory','needProductExpiryDate','logisticInfo','rtvMicroStatus'));
            } elseif($this->userRoleType == 'superadmin' || $this->userRoleType == 'customersupport' || $this->userRoleType == 'vendorsupport'){
                $roleType = $this->userRoleType;
                return view('backend.superadmin.order.view')->with(compact('workStatus','orderHistoryListing','financeDocOrderDetail','transactionModes','transactionDetails','structuredOrderData','orderInfo','allCarrier','cancelReasons','cancelOrderHistory','needProductExpiryDate','roleType','logisticInfo','logisticInfoAgrosiaaShipment','rtvMicroStatus'));
            }elseif($this->userRoleType == 'shipmentadmin' || $this->userRoleType == 'shipmentpartner'){
                return view('backend.shipmentAdmin.order.view')->with(compact('orderHistoryListing','financeDocOrderDetail','transactionModes','transactionDetails','structuredOrderData','orderInfo','allCarrier','cancelReasons','cancelOrderHistory','logisticInfoAgrosiaaShipment','logisticInfo','rtvMicroStatus'));
            }elseif($this->userRoleType == 'financeadmin'){
                return view('backend.financeAdmin.order.view')->with(compact('orderHistoryListing','orderInfo','allCarrier','cancelReasons','cancelOrderHistory','financeDocOrderInfo','transactionModes','transactionDetails','paymentAdviceInfo','paymentVoucherInfo','settlementTransactionDetails','rtvMicroStatus'));
            }elseif($this->userRoleType == 'accountadmin'){
                $roleType = $this->userRoleType;
                return view('backend.accountAdmin.order.view')->with(compact('orderHistoryListing','financeDocOrderDetail','transactionModes','transactionDetails','structuredOrderData','orderInfo','allCarrier','cancelReasons','cancelOrderHistory','needProductExpiryDate','roleType','logisticInfo','logisticInfoAgrosiaaShipment','rtvMicroStatus'));
            }
        }catch (\Exception $e){
            $data = [
                'user' => $this->user,
                'role' => $this->userRoleType,
                'order_id' => $orderId,
                'action' => 'order Deatil view',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }
    public function changeOrderStatus(Request $request,$orderStatus,$orderId,$currentStatus){
        try{
            $currentTime = Carbon::now();
            $citrusId = PaymentMethod::where('slug','citrus')->pluck('id');
            if($orderStatus == 'complete'){
                $orderInfo = Order::where('id',$orderId)->first();
                $totalOrderSuccessQuantity = $totalOrderSuccessAmount = 0;
                if($orderInfo['is_configurable'] == true){
                    $discountedBasePrice = ($orderInfo->base_price * ($orderInfo['width'] * $orderInfo['length'])) - (($orderInfo->base_price * ($orderInfo['width'] * $orderInfo['length'])) * ($orderInfo->discount / 100));
                }else{
                    $discountedBasePrice = $orderInfo->base_price  - ($orderInfo->base_price * ($orderInfo->discount / 100));
                }
                if($orderInfo['commission_percent'] != null){
                    $commissionPercent = $orderInfo['commission_percent'];
                }else{
                    $commissionPercent = $orderInfo->product->productCategoryRel->CategoryProductRel->commission;
                }
                $commissionAmount = round(($commissionPercent / 100) * $discountedBasePrice * $orderInfo->quantity,2);
                $gstCommissionAmount = round(env('GST_PERCENT') * $commissionAmount , 2);
                $logisticsAmount = round(($orderInfo['logistic_percent'] / 100) * $discountedBasePrice * $orderInfo->quantity , 2);
                $gstLogisticsAmount = round(env('GST_PERCENT') * $logisticsAmount , 2);
                /*$sellersOrder = Order::where('seller_id',$orderInfo->seller_id)->where('id','<=',$orderInfo->id)->get();
                $sellerOrderIdAboveLimit = Seller::where('id',$orderInfo->seller_id)->pluck('tcs_started_on');
                if($sellerOrderIdAboveLimit == null) {
                    foreach ($sellersOrder as $key => $order) {
                        $rmaInfo = OrderRma::where('order_id', $order->id)->first();
                        if ($rmaInfo != null) {
                            $orderSuccessQuantity = $order->quantity - $rmaInfo->return_quantity;
                        } else {
                            $orderSuccessQuantity = $order->quantity;
                        }
                        if ($order['is_configurable'] == true) {
                            $orderSuccessAmount = $orderSuccessQuantity * ($order->discounted_price * ($order['length'] * $order['width']));
                        } else {
                            $orderSuccessAmount = $orderSuccessQuantity * $order->discounted_price;
                        }
                        $totalOrderSuccessQuantity += $orderSuccessQuantity;
                        $totalOrderSuccessAmount += $orderSuccessAmount;
                    }
                    if ($totalOrderSuccessAmount < 250000) {
                        $tcs = 0;
                    } elseif ($totalOrderSuccessAmount > 250000 && $sellerOrderIdAboveLimit == null) {
                        if ($orderInfo['is_configurable'] == true) {
                            $tcs = round((((1 / 100) * ($orderInfo->quantity * ($orderInfo->subtotal * ($orderInfo['width'] * $orderInfo['length'])))) + ((1 / 100) * 250000)), 2);
                        } else {
                            $tcs = round((((1 / 100) * ($orderInfo->quantity * $orderInfo->subtotal)) + ((1 / 100) * 250000)), 2);
                        }
                        Seller::where('id', $orderInfo->seller_id)->update(['tcs_started_on' => $orderInfo->id]);
                    }
                }else{
                    if($orderInfo['is_configurable'] == true){
                        $tcs = round(((1 / 100) * ($orderInfo->quantity * ($orderInfo->subtotal * ($orderInfo['width'] * $orderInfo['length'])))),2);
                    }else{
                        $tcs = round(((1 / 100) * ($orderInfo->quantity * $orderInfo->subtotal)),2);
                    }
                }*/
                if($orderInfo['is_configurable'] == true){
                    $orderDiscountedPrice = $orderInfo['discounted_price']  * ($orderInfo['length'] * $orderInfo['width']);
                    $invoiceTotal = $orderDiscountedPrice * $orderInfo->quantity;
                    if($orderInfo['tax_rate'] == 0){
                        $tcs = 0;
                    }else{
                        $tcs = round((1/100 * $invoiceTotal),2);
                    }
                    if($orderInfo['is_ps_campaign'] == true){
                        $settleMentAmount = round((($commissionAmount + $logisticsAmount + $gstCommissionAmount + $gstLogisticsAmount + $tcs + $orderInfo['vendor_campaign_charges'] + ($orderInfo['vendor_campaign_charges'] * (18/100)))) , 2);
                    }else{
                        $settleMentAmount = round(((($orderInfo['discounted_price'] * ($orderInfo['length'] * $orderInfo['width'])) * $orderInfo->quantity) - ($commissionAmount + $logisticsAmount + $gstCommissionAmount + $gstLogisticsAmount + $tcs)) , 2);
                    }
                }else{
                    $orderDiscountedPrice = $orderInfo['discounted_price'];
                    $invoiceTotal = $orderInfo['discounted_price'] * $orderInfo->quantity;
                    if($orderInfo['tax_rate'] == 0){
                        $tcs = 0;
                    }else{
                        $tcs = round((1/100 * $invoiceTotal),2);
                    }
                    if($orderInfo['is_ps_campaign'] == true){
                        $settleMentAmount = round((($commissionAmount + $logisticsAmount + $gstCommissionAmount + $gstLogisticsAmount + $tcs + $orderInfo['vendor_campaign_charges'] + ($orderInfo['vendor_campaign_charges'] * (18/100)))) , 2);
                    }else{
                        $settleMentAmount = round((($orderInfo['discounted_price'] * $orderInfo->quantity) - ($commissionAmount + $logisticsAmount + $gstCommissionAmount + $gstLogisticsAmount + $tcs)) , 2);
                    }
                }

                $settleMentArray = array(
                    'order_amount' => $orderDiscountedPrice,
                    'order_quantity' => $orderInfo->quantity,
                    'delivery_charges'=>$orderInfo->delivery_amount,
                    'commission_percent'=>$commissionPercent,
                    'commission_amount' => $commissionAmount,
                    'order_tcs_amount' => $tcs,
                    'order_logistics_charges'=> $logisticsAmount,
                    'order_vendor_settlement_amount' => $settleMentAmount,
                    'final_vendor_settlement_amount' => $settleMentAmount,
                    'order_id' =>$orderId,
                    'order_complete_date' => $currentTime,
                    'created_at' => $currentTime,
                    'updated_at' => $currentTime,
                );
                $receiptAdviceId = FinanceDocument::where('slug','receipt_advice')->pluck('id');
                $alreadyPresentVendorSettlement = VendorSettleMent::where('order_id',$orderId)->first();
                if(count($alreadyPresentVendorSettlement) > 0){
                    $alreadyPresentVendorSettlement->update($settleMentArray);
                }else{
                    VendorSettleMent::create($settleMentArray);
                }

                $financeDocData = [
                    'order_id' => $orderId,
                    'finance_doc_id' => $receiptAdviceId
                ];
                FinanceDocumentOrderInfo::create($financeDocData);

                if($citrusId == $orderInfo->payment_method_id){
                    $receiptVoucherId = FinanceDocument::where('slug','receipt_voucher')->pluck('id');
                    $receiptVoucherData = [
                        'order_id' => $orderId,
                        'finance_doc_id' => $receiptVoucherId,
                        'reconciled_on' => Carbon::now()
                    ];
                    FinanceDocumentOrderInfo::create($receiptVoucherData);
                }
            }
            $is_email_sent = 0;
            $structuredOrderId = $this->getStructuredOrderId($orderId);
            $order = Order::findOrFail($orderId);
            if($orderStatus == "ready_to_ship"){
                $currentDate = date('d F Y H:i:s',strtotime($currentTime));
                if(strtotime($order->dispatch_date)<strtotime($currentDate))
                    Order::where('id',$orderId)->update(array('procurement_sla_breach_vendor'=>1));
            }
            /*Create Invoice */
            if($orderStatus=='packed'){
                $taxData = json_decode($order->tax_information);
                $invoiceData = array(
                    'order_id'=>$order->id,
                    'vat_rate'=>$taxData->rate,
                    'vat_name'=>$taxData->name,
                    'final_amount'=>$order->discounted_price,
                    'created_at'=>$currentTime,
                    'updated_at'=>$currentTime,
                );
                Invoice::create($invoiceData);
            }
            /* Send Registration Email */
            $user = $this->user->toArray();
            $customerId = Customer::findOrFail($order['customer_id']);
            $customer = User::findOrFail($customerId->user_id);
            $customer = $customer->toArray();
            $readyToPickStatusId = OrderStatus::where('slug','ready_to_pick')->pluck('id');
            $alreadyPresent = OrderHistory::where('order_status_id',$readyToPickStatusId)->where('order_id',$orderId)->count();
            if($alreadyPresent > 0){
                $message = 'Information updated successfully';
            }else{
                $message = 'Order status updated successfully';
            }
            $currentStatusSlug = OrderStatus::where('display_name',$currentStatus)->pluck('slug');
            if($currentStatusSlug == 'ready_to_pick' || 'transit'){
                $message = 'Order status updated successfully';
            }
            $orderStatus = OrderStatus::where('slug',$orderStatus)->first();
            if($currentStatus == 'pending'){
                $order->update(array('order_status_id'=> $orderStatus->id,'updated_at' => $currentTime));
                $orderHistoryArray = array(
                    'is_email_sent'=>$is_email_sent,
                    'order_id' => $orderId,
                    'order_status_id' => $orderStatus->id,
                    'user_id' => $this->user->id,
                    'created_at' => $currentTime,
                    'updated_at' => $currentTime,
                );
                OrderHistory::insert($orderHistoryArray);
            }
            if($orderStatus['id'] > $order['order_status_id']){
                $order->update(array('order_status_id'=> $orderStatus->id,'updated_at' => $currentTime));
                $orderHistoryArray = array(
                    'is_email_sent'=>$is_email_sent,
                    'order_id' => $orderId,
                    'order_status_id' => $orderStatus->id,
                    'user_id' => $this->user->id,
                    'created_at' => $currentTime,
                    'updated_at' => $currentTime,
                );
                if($orderStatus['slug'] == 'refused'){
                    $orderHistoryArray['comment']= $request->comment;
                }

                OrderHistory::insert($orderHistoryArray);
            }
            $mailParameters['paymentMethod'] = PaymentMethod::where('id',$order['payment_method_id'])->first();
            $customerInfo = OrderCustomerRelation::where('id',$order->order_customer_info_id)->first();
            $mailParameters['address'] = json_decode($customerInfo['billing_address']);
            $mailParameters['orderedOn'] = date('l, d M',strtotime($customerInfo['created_at']));
            $mailParameters['customer'] = $customer;
            $mailParameters['order'] = $order;
            $mailParameters['structuredOrderId'] = $structuredOrderId;
            if($order['is_configurable'] == true){
                $itemPrice = ($order->discounted_price *($order['length'] * $order['width'])) * $order->quantity;
                $grandTotal = (($order->discounted_price *($order['length'] * $order['width'])) * $order->quantity) + $order->delivery_amount;
            }else{
                $itemPrice = $order->discounted_price * $order->quantity;
                $grandTotal = ($order->discounted_price * $order->quantity) + $order->delivery_amount;
            }
            $totalBeforeTax = round($itemPrice / (($order->tax_rate / 100)+1),2);
            $order['sellerProductPickAddress'] = json_decode($order->seller_address);
            if(strtoupper($order['sellerProductPickAddress']->state) != strtoupper(json_decode($order->ordersCustomerInfo->shipping_address)->state)){
                $mailParameters['tax_igst_applied'] = true;
            }else{
                $mailParameters['tax_igst_applied'] = false;
            }
            $mailParameters['taxAmount'] = round($itemPrice - $totalBeforeTax,2);
            $mailParameters['totalBeforeTax'] = $totalBeforeTax;
            $mailParameters['grandTotal'] = $grandTotal;
            $mailParameters['shipping_method'] = ShippingMethod::where('id',$order['shipping_method_id'])->first();
            if($customer['is_email'] == true && $customer['email'] != "" && $customer['email'] != null){
                if($orderStatus->slug == 'packed'){
                    Mail::send('emails.Customer.order.processing', $mailParameters, function($message) use ($customer,$order){
                        $message->subject('Your Agrosiaa.com order of '.ucwords($order->product->product_name).' is confirmed.');
                        $message->to($customer['email']);
                        $message->from(env('FROM_EMAIL'));
                    });
                }elseif($orderStatus->slug == 'ready_to_ship'){
                    Mail::send('emails.Customer.order.readyToShip', $mailParameters, function($message) use ($customer,$order,$structuredOrderId){
                        $message->subject('Your Agrosiaa.com order AGR'.$structuredOrderId.' of '.ucwords($order->product->product_name).' has been dispatched! ');
                        $message->to($customer['email']);
                        $message->from(env('FROM_EMAIL'));
                    });
                }elseif($orderStatus->slug == 'transit'){
                    Mail::send('emails.Customer.order.inShip', $mailParameters, function($message) use ($customer,$order,$structuredOrderId){
                        $message->subject('Your Order AGR'.$structuredOrderId.' Tracking information. ');
                        $message->to($customer['email']);
                        $message->from(env('FROM_EMAIL'));
                    });
                }elseif($orderStatus->slug == 'complete'){
                    Mail::send('emails.Customer.order.complete', $mailParameters, function($message) use ($customer,$order,$structuredOrderId){
                        $message->subject('Your Order AGR'.$structuredOrderId.' has been delivered');
                        $message->to($customer['email']);
                        $message->from(env('FROM_EMAIL'));
                    });
                }
                $is_email_sent = true;
                OrderHistory::where('order_id',$orderId)->update(array('is_email_sent'=>$is_email_sent));
            }
            if($orderStatus['slug'] == 'ready_to_pick'){
                Order::where('id',$orderId)->update(array('notify_mark_as_pick_up_time_vendor'=>$currentTime));
                if($order->payment_method_id == $citrusId){
                    $this->sendOrderSms($customer['mobile'],"Your package with ".ucwords($order->product->product_name)."... has been dispatched and will be delivered on or before ".$order->delivery_date);
                }else{
                    $this->sendOrderSms($customer['mobile'],"Your package with ".ucwords($order->product->product_name)."... has been dispatched and will be delivered on or before ".$order->delivery_date.". Keep COD amount of Rs ".$grandTotal." ready.");
                }
            }
            if($orderStatus['slug'] == 'ready_to_ship'){
                $this->sendOrderSms($customer['mobile'],"Your Agrosiaa package with ".ucwords($order->product->product_name)." is ready for shipment & will reach you by ".$order->delivery_date.".");
            }
            if($orderStatus['slug'] == 'transit'){
                $this->sendOrderSms($customer['mobile'],"Update: Hi ".$customer['first_name']."! ".ucwords($order->product->product_name)." from your AGROSIAA order AGR".$structuredOrderId." has been shipped by the seller via Agrosiaa Logistics Partner. Estimated date of delivery for your item is ".$order->delivery_date.".");
            }
            if($orderStatus['slug'] == 'complete'){
                $this->sendOrderSms($customer['mobile'],"Hi ".$customer['first_name'].", Your Order AGR".$structuredOrderId." with item ".ucwords($order->product->product_name)." was successfully delivered by Agrosiaa Logistics Partner.");
            }
            $request->session()->flash('success', $message);
            if($this->userRoleType=='seller'){
                if($currentStatus == 'to_pack'){
                    return redirect('order/manage/'.$currentStatus);
                }else{
                    return redirect('order/view/'.$orderId);
                }
            }elseif($this->userRoleType=='superadmin'){
                if($currentStatus == 'to_pack'){
                    return redirect('operational/order/manage/'.$currentStatus);
                }else{
                    return redirect('operational/order/view/'.$orderId);
                }
            }elseif($this->userRoleType == 'shipmentadmin' || $this->userRoleType == 'shipmentpartner'){
                if($currentStatus == 'to_pack'){
                    return redirect('shipment/order/view/'.$orderId);
                }else{
                    return redirect('shipment/order/view/'.$orderId);
                }
            }elseif($this->userRoleType=='accountadmin'){
                return redirect('vendor/order/view/'.$orderId);
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
    public function cancelOrder(Request $request,$orderId){
        try{
            $currentStatus = $request->current_status;
            $other='';
            if($request->has('other')){
                $other = $request->other ;
            }
            $order = Order::findOrFail($orderId);
            $currentTime = Carbon::now();
            if($this->userRoleType=='seller'){
                $orderStatusId = OrderStatus::where('slug','back_ordered')->first();
            }elseif($this->userRoleType=='superadmin'){
                $orderStatusId = OrderStatus::where('slug','declined')->first();
            }
            $product = Product::findOrFail($order->product_id);
            $product->update(array('quantity'=> 0,'updated_at' => $currentTime));
            $order->update(array('order_status_id'=> $orderStatusId->id,'updated_at' => $currentTime));
            $orderHistoryArray = array(
                'is_email_sent' => 1,
                'order_id' => $orderId,
                'order_status_id' => $orderStatusId->id,
                'customer_cancel_reasons_id' => $request->customer_cancel_reasons_id,
                'reason' => $other,
                'user_id' => $this->user->id,
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
            );
            $orderHistory = OrderHistory::insert($orderHistoryArray);
            /* Send Registration Email */
            $user = $this->user->toArray();
            $customerId = Customer::findOrFail($order['customer_id']);
            $customer = User::findOrFail($customerId->user_id);
            $customer = $customer->toArray();
            $structuredOrderId = $this->getStructuredOrderId($orderId);
            $mailParameters['orderedOn'] = date('l, d M',strtotime($customer['created_at']));
            $mailParameters['paymentMethod'] = PaymentMethod::where('id',$order['payment_method_id'])->first();
            $deliveryAmount = DeliveryType::where('id',$order['delivery_type_id'])->pluck('amount') ;
            $order['sellerProductPickAddress'] = json_decode($order->seller_address);
            if(strtoupper($order['sellerProductPickAddress']->state) != strtoupper(json_decode($order->ordersCustomerInfo->shipping_address)->state)){
                $mailParameters['tax_igst_applied'] = true;
            }else{
                $mailParameters['tax_igst_applied'] = false;
            }
            $itemPrice = $order->discounted_price * $order->quantity;
            $totalBeforeTax = round($itemPrice / (($order->tax_rate / 100)+1),2);
            $mailParameters['taxAmount'] = round($itemPrice - $totalBeforeTax,2);
            $mailParameters['totalBeforeTax'] = $totalBeforeTax;
            $mailParameters['structuredOrderId'] = $structuredOrderId;
            $mailParameters['customer'] = $customer;
            $mailParameters['order'] = $order;
            $grandTotal = ($order->discounted_price * $order->quantity) + $order->delivery_amount;
            $mailParameters['grandTotal'] = $grandTotal;
            if($customer['is_email'] == true && $customer['email'] != "" && $customer['email'] != null){
                Mail::send('emails.Customer.order.order_declined', $mailParameters, function($message) use ($customer,$structuredOrderId){
                    $message->subject('Your Order AGR'.$structuredOrderId.' CANCELLATION');
                    $message->to($customer['email']);
                    $message->from(env('FROM_EMAIL'));
                });
                $is_email_sent = 1;
            }
            $this->sendOrderSms($customer['mobile'],"Agrosiaa was unable to process your order AGR".$structuredOrderId." due to some reasons. For Queries please contact: 020-46917000");
            $message = 'Order status updated successfully';
            $request->session()->flash('success', $message);
            if($this->userRoleType=='seller'){
                return redirect('order/view/'.$orderId);
            }elseif($this->userRoleType=='superadmin'){
                return redirect('operational/order/view/'.$orderId);
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
    public function getManifestCheckListData(){
        try{
            $orderStatusId = OrderStatus::where('slug','ready_to_ship')->first();
            if($this->userRoleType=='seller'){
                $sellerProducts = Product::where('seller_id',$this->seller->id)->lists('id');
                $totalRecords = Order::where('order_status_id',$orderStatusId['id'])->whereIn('product_id',$sellerProducts)->with('orderHistory')->get()->toArray();
            }elseif($this->userRoleType=='superadmin'){
                $totalRecords = Order::where('order_status_id',$orderStatusId['id'])->get();
            }
            for($i = 0 ; $i < count($totalRecords) ; $i++ ){
                $totalRecords[$i]['id'] = $this->getStructuredOrderId($totalRecords[$i]['id']);
            }
            return view('backend.partials.seller.order.manifest-checklist-table')->with(compact('totalRecords','orderStatusId'));
        }catch(\Exception $e){
            $data = [
                'user' => $this->user,
                'role' => $this->userRoleType,
                'action' => 'get oder data packing slip',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }
    public function checklistPreview(Request $request){
        try{
            $date = Carbon::now();
            if($request->has('orders') && !empty($request->orders)){
                $orders = Order::whereIn('id',$request->orders)->with('product')->get();
                //TCPDF::SetTitle('Hello World');
                TCPDF::AddPage();
                //TCPDF::Write(0, 'Hello World');
                TCPDF::writeHTML(view('backend.common.pdf.checklist')->with(compact('orders','date'))->render());
                TCPDF::Output("Packing_Checklist_".date('Y-m-d_H_i_s').".pdf", 'D');
                //return view('backend.common.pdf.checklist')->with(compact('orders'));
            }else{
                $request->session()->flash('error','Please select orders, before printing it!');
                return redirect()->back();
            }
        }catch(\Exception $e){
            $data = [
                'user' => $this->user,
                'role' => $this->userRoleType,
                'action' => 'packaging checklist preview',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }
    public function getStructuredOrderId($orderId){
        return str_pad($orderId, 9, "0", STR_PAD_LEFT);
    }
    public function generateInvoice($orderId){
        try{
            $order = Order::findOrFail($orderId);
            $orderQuantityInfo = OrderQuantityInfo::where('order_id',$order->id)->get()->toArray();
            /*$couponDiscount = $order->coupon_discount;
            if($couponDiscount==NULL){
                $couponDiscount = 0;
            }*/
            $couponDiscount = 0;
            $paymentMethod = PaymentMethod::where('id',$order['payment_method_id'])->first();
            if($paymentMethod['slug'] == "citrus"){
                $displayName = "Paid Amount";
                $paymentType = " ";
                $displayAmountMethod = "Paid Amount";
            }else{
                $displayName = "Amount";
                $paymentType = "COD";
                $displayAmountMethod = "COD Collectible Amount";
            }
            $orderNo = $this->getStructuredOrderId($orderId);
            $seller = $order->seller;
            $license = array();
            $license['seedLicense'] = VendorLicenses::where('vendor_id',$seller['id'])->where('license_id' , 1)->pluck('license_number');
            $license['fertilizerLicense'] = VendorLicenses::where('vendor_id',$seller['id'])->where('license_id' , 2)->pluck('license_number');
            $license['pesticidesLicense'] = VendorLicenses::where('vendor_id',$seller['id'])->where('license_id' , 3)->pluck('license_number');
            $license['otherLicense'] = VendorLicenses::where('vendor_id',$seller['id'])->where('license_id' , 4)->pluck('license_number');
            $sellerAddress = json_decode($order->seller_address);
            $customerAddress = json_decode($order->ordersCustomerInfo->shipping_address);
            $product = $order->product;
            $hsnCodeId = HSNCodeTaxRelation::where('id',$order->hsn_code_tax_relation_id)->pluck('hsn_code_id');
            $hsn_code = HSNCodes::where('id',$hsnCodeId)->pluck('hsn_code');
            $brand = $product->brand;
            if($order['is_configurable'] == true){
                $area = $order['length'] * $order['width'];
                $unitPrice = round(($order->selling_price * $area) / (($order->tax_rate / 100)+1),2);
                $discountAmountPerUnit = ($order->selling_price - $order->discounted_price) * $area;
            }else{
                $unitPrice = round($order->selling_price / (($order->tax_rate / 100)+1),2);
                $discountAmountPerUnit = $order->selling_price - $order->discounted_price;
            }
            if(strtoupper($sellerAddress->state) != strtoupper($customerAddress->state)){
                $tax_igst_applied = true;
            }else{
                $tax_igst_applied = false;
            }

            $orderPrice =  $order->selling_price;
            if($order['is_configurable'] == true){
                $area = $order['length'] * $order['width'];
                $orderSellingTotal = ($orderPrice * $area) * $order->quantity;
                $subtotal = ($order->discounted_price * $area) * $order->quantity;
            }else{
                $orderSellingTotal = $orderPrice * $order->quantity;
                $subtotal = $order->discounted_price * $order->quantity;
            }
            if( $tax_igst_applied == true){
                $tax_rate = $order->tax_rate;
                $tax = (($tax_rate)/100)+1 ;
            }else{
                $tax_rate = $order->tax_rate / 2;
                $tax = (($tax_rate * 2)/100)+1 ;
            }
            $finaltotal = $subtotal;
            $newsubtotal = round($finaltotal / $tax,'2');
            $finalUnitPrice = round($newsubtotal/$order['quantity'],'2');
            $gst = round(($tax_rate /100) * $newsubtotal,'2');

            $discountAmount = $orderSellingTotal - $subtotal;

            $tax_amount = round($tax_rate / 100 * $subtotal,2);
            $grossTotal = $subtotal + $tax_amount+$tax_amount ;
            $grossTotal = ceil($grossTotal);
            $orderDate = $order->created_at->formatLocalized('%A %d %B %Y');
            $orderDateChangeFormat = $order->created_at->formatLocalized('%d-%m-%Y');
            $sellerBankDetails = BankDetails::where('seller_id',$order->seller->id)->first();
            $invoice = $order->invoice;
            $invoiceId = $this->getStructuredOrderId($invoice->id);
            $invoiceCreatedDate = $invoice->created_at->formatLocalized('%d-%m-%Y');
            $userRole = $this->userRoleType;
            $amountInWords = ucwords(NumberHelper::getIndianCurrency($finaltotal));
            TCPDF::AddPage();
            TCPDF::SetAutoPageBreak(true);
            TCPDF::writeHTML(view('backend.common.pdf.invoice')->with(compact('orderQuantityInfo','hsn_code','tax_rate','tax_amount','tax_igst_applied','userRole','order','orderNo','seller','sellerAddress','customerAddress','product','brand','unitPrice','taxPrice','orderDate','sellerBankDetails','invoiceId','invoice','invoiceCreatedDate','orderDateChangeFormat','discountAmountPerUnit','grossTotal','license','paymentMethod','paymentType','displayAmountMethod','displayName','couponDiscount','amountInWords','discountAmount','subtotal','gst','finalUnitPrice','newsubtotal','finaltotal'))->render());
            TCPDF::Output("Invoice".date('Y-m-d_H_i_s').".pdf", 'D');

            //return view('backend.common.pdf.invoice')->with(compact('tax_amount','igst_applied','userRole','order','orderNo','seller','sellerAddress','customerAddress','product','brand','unitPrice','taxPrice','orderDate','sellerBankDetails','invoiceId','invoice','invoiceCreatedDate','orderDateChangeFormat','discountAmount','grossTotal','license','paymentMethod','paymentType','displayAmountMethod','displayName','couponDiscount','grossTotal'));
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
    public function logisticsPreview(Request $request){
        try{
            if($request->has('orders') && !empty($request->orders)){
                $orders = $request->orders;
                $generatedOn = date('jS M, Y H:i:s');
                $mOrders = array();
                foreach($orders as $order){
                    if (array_key_exists("id",$order)){
                        $order['rts'] = date('d M Y',strtotime($order['rts']));
                        $order['id'] = $this->getStructuredOrderId($order['id']);
                        array_push($mOrders,$order);
                    }
                }
                $user = $this->user;
                $company_name = Seller::where('user_id',$user['id'])->pluck('company');
                TCPDF::AddPage();
                TCPDF::writeHTML(view('backend.common.pdf.logistics')->with(compact('mOrders','generatedOn','company_name'))->render());
                TCPDF::Output("Manifest_Checklist_".date('Y-m-d_H_i_s').".pdf", 'D');
//                return view('backend.common.pdf.logistics')->with(compact('mOrders','generatedOn','sellerName'));
            }else{
                $request->session()->flash('error','Please select orders, before printing it!');
                return redirect()->back();
            }
        }catch(\Exception $e){
            $data = [
                'user' => $this->user,
                'role' => $this->userRoleType,
                'action' => 'packaging checklist preview',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }

    public function saveExpiryDates(Request $request){
        try{
            foreach($request->order_info as $key => $order_info){
                $order_info['order_id'] = $request->order_id;
                OrderQuantityInfo::create($order_info);
            }
            if($this->userRoleType == 'superadmin'){
                return redirect('/operational/order/change-status/packed/'.$request->order_id.'/'.$request->order_status_slug.'\'');
            }elseif ($this->userRoleType == 'seller'){
                return redirect('/order/change-status/packed/'.$request->order_id.'/'.$request->order_status_slug.'\'');
            }
        }catch(\Exception $e){
            $data = [
                'user' => $this->user,
                'role' => $this->userRoleType,
                'action' => 'Save product Expiry dates',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }
    public function pendingOrder(Request $request,$id){
        try{
            $indiaPostData['order_id']= $id;
            $indiaPostData['biller_id']= $request->biller_id;
            $indiaPostData['trans_id']= $request->trans_id;
            $indiaPostData['biller_name']= $request->biller_name;
            $indiaPostData['amount']= $request->amount;
            $indiaPostData['commission']= $request->commission;
            $indiaPostData['gst']= $request->gst;
            $indiaPostData['net_payable']= $request->net_payable;
            $indiaPostData['article_number']= $request->article_number;
            $indiaPostData['barcode_number']= $request->barcode_number;
            $indiaPostData['document_number']= $request->document_number;
            $indiaPostData['payment_docket_number']= $request->payment_docket_number;
            $indiaPostData['collection_office']= $request->collection_office;
            $indiaPostData['collection_date']= $request->collection_date;
            $indiaPostData['article_type']= $request->article_type;
            $indiaPostData['payment_date']= Carbon::now();
            $indiaPostData['logistic_date']= Carbon::now();
            $query = LogistingAccounting::create($indiaPostData);
            $orderStatus = OrderStatus::where('slug','pending')->pluck('id');
            Order::where('id',$id)->update(array('order_status_id' => $orderStatus));

            //Adding status change to Order History
            $currentTime = Carbon::now();
            $orderHistoryArray = array(
                'is_email_sent' => 0,
                'order_id' => $id,
                'order_status_id' => $orderStatus,
                'customer_cancel_reasons_id' => null,
                'reason' => null,
                'user_id' => $this->user->id,
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
            );
            $orderHistory = OrderHistory::create($orderHistoryArray);
            if($query){
                $message = "logistic details updated successfully";
                $request->session()->flash('success', $message);
                return redirect('operational/order/manage/close');
            }else{
                $message = "Something went wrong";
                $request->session()->flash('error', $message);
                return redirect('operational/order/manage/close');
            }
        }catch(\Exception $e){
            $data=[
                'action' => 'Logistic account information Created',
                'data' => $request->all(),
                'message' => $e->getMessage(),
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }
    public function agrosiaaShipmentPendingOrder(Request $request,$id){
        try{
            $agrosiaaShipmentData['order_id']= $id;
            $agrosiaaShipmentData['deliver_by'] = $request->delivery_type;
            $agrosiaaShipmentData['payment_deposit_date'] = Carbon::now();
            if($request->delivery_done_by != null){
                $agrosiaaShipmentData['delivery_done_by'] = $request->delivery_done_by;
            }
            if($request->lr_number != null) {
                $agrosiaaShipmentData['lr_number'] = $request->lr_number;
                $agrosiaaShipmentData['lr_date'] = $request->lr_date;
                $agrosiaaShipmentData['lr_amount'] = $request->lr_amount;
                $agrosiaaShipmentData['invoice_number'] = $request->invoice_no;
                $agrosiaaShipmentData['invoice_date'] = $request->invoice_date;
                $agrosiaaShipmentData['invoice_amount'] = $request->invoice_amount;
            }else{
                $agrosiaaShipmentData['lr_number'] = null;
                $agrosiaaShipmentData['lr_date'] = null;
                $agrosiaaShipmentData['lr_amount'] = null;
                $agrosiaaShipmentData['invoice_number'] = null;
                $agrosiaaShipmentData['invoice_date'] = null;
                $agrosiaaShipmentData['invoice_amount'] = null;
            }
            $query = LogisticAccountingAgrosiaaShipment::create($agrosiaaShipmentData);
            $status = OrderStatus::where('slug','pending')->pluck('id');
            Order::where('id',$id)->update(array('order_status_id' => $status));

            $currentTime = Carbon::now();
            $orderHistoryArray = array(
                'customer_cancel_reasons_id' => null,
                'is_email_sent' => 0,
                'order_id' => $id,
                'order_status_id' => $status,
                'user_id' => $this->user->id,
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
                'reason'=> null,
                'comment' => null
            );
            $orderHistory = OrderHistory::create($orderHistoryArray);
            if($query){
                $message = "logistic details updated successfully";
                $request->session()->flash('success', $message);
                return redirect()->back();
            }else{
                $message = "Something went wrong";
                $request->session()->flash('error', $message);
                return redirect()->back();
            }

        }catch(\Exception $e){
            $data = [
                'action' => 'Logistic account information Updated',
                'data' => $request->all(),
                'message' => $e->getMessage(),
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }
    public function updateCloseOrder(Request $request,$id){
        try{
        $data = array();
        $data['order_id'] = $id;
        $data['biller_id'] = $request->biller_id;
        $data['trans_id'] = $request->trans_id;
        $data['biller_name'] = $request->biller_name;
        $data['amount'] = $request->amount;
        $data['commission'] = $request->commission;
        $data['gst'] = $request->gst;
        $data['net_payable'] = $request->net_payable;
        $data['article_number'] = $request->article_number;
        $data['barcode_number'] = $request->barcode_number;
        $data['document_number'] = $request->document_number;
        $data['payment_docket_number'] = $request->payment_docket_number;
        $data['check_number'] = $request->check_number;
        $data['payment_date'] = $request->payment_date;
        $data['collection_office'] = $request->collection_office;
        $data['collection_date'] = $request->collection_date;
        $data['logistic_number'] = $request->logistic_number;
        $data['logistic_date'] = $request->logistic_date;
        $data['logistic_invoice_amount'] = $request->logistic_invoice_amount;
        $data['invoice_payment_details'] = $request->invoice_payment_details;
        $data['actual_logistic_cost'] = $request->actual_logistic_cost;
        $data['article_type'] = $request->article_type;
        $data['note_name'] = $request->note_name;
        $query = LogistingAccounting::where('order_id',$id)->update($data);
        $status = OrderStatus::where('slug','close')->pluck('id');
        $history = OrderHistory::where('order_id',$id)->where('order_status_id',$status)->first();
        $currentTime = Carbon::now();
        $orderHistoryArray = array(
            'customer_cancel_reasons_id' => null,
            'reason'=> null,
            'comment' => null,
            'is_email_sent' => 0,
            'order_id' => $id,
            'order_status_id' => $history->order_status_id,
            'user_id' => $this->user->id,
            'created_at' => $history->created_at,
            'updated_at' => $currentTime,
        );
        $orderHistory = OrderHistory::create($orderHistoryArray);
        if($query){
            $message = "logistic details updated successfully";
            $request->session()->flash('success', $message);
            return redirect()->back();
        }else{
            $message = "Something went wrong";
            $request->session()->flash('error', $message);
            return redirect()->back();
        }
    }catch(\Exception $e){
        $data=[
        'action' => 'Logistic account information Updated',
        'data' => $request->all(),
        'message' => $e->getMessage(),
        ];
        Log::critical(json_encode($data));
        abort(500,$e->getMessage());
        }
    }
    public function updatePendingOrder(Request $request,$id)
    {
        try{
            $data = array();
            $data['order_id'] = $id;
            $data['biller_id'] = $request->biller_id;
            $data['trans_id'] = $request->trans_id;
            $data['biller_name'] = $request->biller_name;
            $data['amount'] = $request->amount;
            $data['commission'] = $request->commission;
            $data['gst'] = $request->gst;
            $data['net_payable'] = $request->net_payable;
            $data['article_number'] = $request->article_number;
            $data['barcode_number'] = $request->barcode_number;
            $data['document_number'] = $request->document_number;
            $data['payment_docket_number'] = $request->payment_docket_number;
            $data['check_number'] = $request->check_number;
            $data['payment_date'] = $request->payment_date;
            $data['collection_office'] = $request->collection_office;
            $data['collection_date'] = $request->collection_date;
            $data['logistic_number'] = $request->logistic_number;
            $data['logistic_date'] = $request->logistic_date;
            $data['logistic_invoice_amount'] = $request->logistic_invoice_amount;
            $data['invoice_payment_details'] = $request->invoice_payment_details;
            $data['actual_logistic_cost'] = $request->actual_logistic_cost;
            $data['article_type'] = $request->article_type;
            $data['note_name'] = $request->note_name;
            $query = LogistingAccounting::where('order_id',$id)->update($data);
            $status = OrderStatus::where('slug','pending')->pluck('id');
            $history = OrderHistory::where('order_id',$id)->where('order_status_id',$status)->first();
            $currentTime = Carbon::now();
            $orderHistoryArray = array(
                'customer_cancel_reasons_id' => null,
                'reason'=> null,
                'comment' => null,
                'is_email_sent' => 0,
                'order_id' => $id,
                'order_status_id' => $history->order_status_id,
                'user_id' => $this->user->id,
                'created_at' => $history->created_at,
                'updated_at' => $currentTime,
            );
            $orderHistory = OrderHistory::where('order_id',$id)->where('order_status_id',$history->order_status_id)->update($orderHistoryArray);
            if($query){
                $message = "logistic details updated successfully";
                $request->session()->flash('success', $message);
                return redirect()->back();
            }else{
                $message = "Something went wrong";
                $request->session()->flash('error', $message);
                return redirect()->back();
            }
        }catch(\Exception $e){
            $data=[
                'action' => 'Logistic account information Updated',
                'data' => $request->all(),
                'message' => $e->getMessage(),
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }
    public function updateCloseOrderAgrosiaaShipment(Request $request,$id)
    {
        try{
            $data = array();
            $data['order_id'] = $id;
            $data['deliver_by'] = $request->delivery_type;
            if($request->delivery_done_by != null){
                $data['delivery_done_by'] = $request->delivery_done_by;
            }else{
                $data['delivery_done_by'] = null;
            }
            if($request->lr_number != null || $request->lr_date != null || $request->lr_amount != null || $request->invoice_no != null || $request->invoice_date != null || $request->invoice_amount != null){
                $data['lr_number'] = $request->lr_number;
                $data['lr_date'] = date('Y/m/d',strtotime($request->lr_date));
                $data['lr_amount'] = $request->lr_amount;
                $data['invoice_number'] = $request->invoice_no;
                $data['invoice_date'] = date('Y/m/d',strtotime($request->invoice_date));
                $data['invoice_amount'] = $request->invoice_amount;
            }else{
                $data['lr_number'] = null;
                $data['lr_date'] = null;
                $data['lr_amount'] = null;
                $data['invoice_number'] = null;
                $data['invoice_date'] = null;
                $data['invoice_amount'] = null;
            }
            $data['payment_received_mode'] = $request->payment_mode;
            $data['bank_name'] = $request->bank_name;
            $data['payment_deposit_date'] = $request->deposit_date;
            $data['deposit_note'] = $request->deposit_note;
            $query = LogisticAccountingAgrosiaaShipment::where('order_id',$id)->update($data);

            //Order Status Change To Close
            $status = OrderStatus::where('slug','close')->pluck('id');
            $history = OrderHistory::where('order_id',$id)->where('order_status_id',$status)->first();
            $currentTime = Carbon::now();
            $orderHistoryArray = array(
                'customer_cancel_reasons_id' => null,
                'reason'=> null,
                'comment' => null,
                'is_email_sent' => 0,
                'order_id' => $id,
                'order_status_id' => $history->order_status_id,
                'user_id' => $this->user->id,
                'created_at' => $history->created_at,
                'updated_at' => $currentTime,
            );
            $orederHistory = new OrderHistory();
            $orederHistory->create($orderHistoryArray);
            if($query){
                $message = "logistic details updated successfully";
                $request->session()->flash('success', $message);
                return redirect()->back();
            }else {
                $message = "Something went wrong";
                $request->session()->flash('error', $message);
                return redirect()->back();
            }
        }catch(\Exception $e){
            $data=[
                'action' => 'Logistic account information Updated',
                'data' => $request->all(),
                'message' => $e->getMessage(),
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }
    public function updateAgrosiaaShipmentPendingOrder(Request $request,$id){
        try{
            $data = array();
            $data['order_id'] = $id;
            $data['deliver_by'] = $request->delivery_type;
            if($request->delivery_done_by != null){
                $data['delivery_done_by'] = $request->delivery_done_by;
            }else{
                $data['delivery_done_by'] = null;
            }
            if($request->lr_number != null || $request->lr_date != null || $request->lr_amount != null || $request->invoice_no != null || $request->invoice_date != null || $request->invoice_amount != null){
                $data['lr_number'] = $request->lr_number;
                $data['lr_date'] = date('Y/m/d',strtotime($request->lr_date));
                $data['lr_amount'] = $request->lr_amount;
                $data['invoice_number'] = $request->invoice_no;
                $data['invoice_date'] = date('Y/m/d',strtotime($request->invoice_date));
                $data['invoice_amount'] = $request->invoice_amount;
            }else{
                $data['lr_number'] = null;
                $data['lr_date'] = null;
                $data['lr_amount'] = null;
                $data['invoice_number'] = null;
                $data['invoice_date'] = null;
                $data['invoice_amount'] = null;
            }
            $data['payment_received_mode'] = $request->payment_mode;
            $data['bank_name'] = $request->bank_name;
            $data['payment_deposit_date'] = $request->deposit_date;
            $data['deposit_note'] = $request->deposit_note;
            $query = LogisticAccountingAgrosiaaShipment::where('order_id',$id)->update($data);

            //Order Status Change To Close
            $status = OrderStatus::where('slug','pending')->pluck('id');
            $history = OrderHistory::where('order_id',$id)->where('order_status_id',$status)->first();
            $currentTime = Carbon::now();
            $orderHistoryArray = array(
                'customer_cancel_reasons_id' => null,
                'reason'=> null,
                'comment' => null,
                'is_email_sent' => 0,
                'order_id' => $id,
                'order_status_id' => $history->order_status_id,
                'user_id' => $this->user->id,
                'created_at' => $history->created_at,
                'updated_at' => $currentTime,
            );
            $orederHistory = new OrderHistory();
            $orederHistory->where('order_id',$id)->where('order_status_id',$history->order_status_id)->update($orderHistoryArray);
            if($query){
                $message = "logistic details updated successfully ";
                $request->session()->flash('success', $message);
                return redirect()->back();
            }else {
                $message = "";
                $request->session()->flash('error', $message);
                return redirect()->back();
            }
        }catch(\Exception $e){
            $data=[
                'action' => 'Logistic account information Updated',
                'data' => $request->all(),
                'message' => $e->getMessage(),
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }
    public function returnToVendorRma(Request $request,$id){
        try{
            $rtvMicroStatusDetailsPresent = RtvMicroStatusDetails::where('order_id',$id)->first();

            $data = $request->all();
            $status = 200;
            $user = Auth::user();
            $customerId = Order::where('id',$id)->pluck('customer_id');
            $product_id = Order::where('id',$data['order_id'])->pluck('product_id');
            $data['product_name'] = Product::where('id',$product_id)->pluck('product_name');
            $currentTime = Carbon::now();
            $data['customer_id'] = $customerId;
            $rmaReasonId = RmaReason::where('slug', $data['rma_reason'])->first();
            $data['rma_reason_id'] = $rmaReasonId['id'];
            $rmaRequest = RmaStatus::where('slug', 'requested')->first();
            $data['rma_status_id'] = $rmaRequest['id'];
            $data['pick_up_date'] = DeliveryTrait::getReturnDate(Carbon::now());
            $data['return_delivery_date'] = date('d F Y', strtotime($data['pick_up_date'] . " + 7 day"));
            $data['shipping_method_id'] = Order::where('id', $data['order_id'])->pluck('shipping_method_id');
            if($rtvMicroStatusDetailsPresent == null){
                $order = OrderRma::create($data);
                $rmaHistory['rma_id'] = $order['id'];
                $rmaHistory['rma_status_id'] = $rmaRequest['id'];
                $rmaHistory['user_id'] = $user['id'];
                $rmaHistory['created_at'] = $currentTime;
                $rmaHistory['updated_at'] = $currentTime;
                $query = RmaHistory::create($rmaHistory);
            }else{
                $presentRmaReason = OrderRma::where('order_id',$id)->first();
                $rmaReasonId = RmaReason::where('slug', $request['rma_reason'])->first();
                $reason['rma_reason_id'] = $rmaReasonId['id'];
                OrderRma::where('id',$presentRmaReason['id'])->update($reason);
            }
            // Rtv Micro Status
            if(array_key_exists('rtv_micro_status',$data)){
                $rtvMicroStatus['order_id'] = $data['order_id'];
                $rtvMicroStatus['rtv_micro_status_id'] = $data['rtv_micro_status'];
                $rtvMicroStatus['reconcile_order_number'] = $data['reconcile_order_number'];
                if($rtvMicroStatusDetailsPresent != null){
                    $rtvQuery = RtvMicroStatusDetails::where('order_id',$id)->update($rtvMicroStatus);
                    if($rtvQuery){
                        $message = "RTV status update successfully";
                        $request->session()->flash('success', $message);
                        return back();
                    }
                }else{
                    RtvMicroStatusDetails::create($rtvMicroStatus);
                }
            }
            $accountAdminRoleId = Role::where('id',$user->role_id)->where('slug','=','accountadmin')->pluck('id');
            if($query && $user->role_id != $accountAdminRoleId){
                $message = "Return Created successfully";
                $request->session()->flash('success', $message);
                return redirect('operational/rma/manage/'.$rmaRequest->slug);
            }elseif($query && $user->role_id == $accountAdminRoleId){
                $message = "Return Created successfully";
                $request->session()->flash('success', $message);
                return back();
            }else {
                $message = "Something went wrong";
                $request->session()->flash('error', $message);
                return redirect()->back();
            }

        } catch(\Exception $e){
            $message = "Something went wrong";
            $status = 500;
            $data = [
                'data' => $request->all(),
                'action' => 'order return status',
                'exception' => $e->getMessage(),
                'message' => $message,
            ];
        }
        Log::critical(json_encode($data,$status));
    }
    public function cancleOrderReturnToVendor(Request $request,$orderStatus,$orderId)
    {
        try {
            $is_email_sent = 0;
            $currentTime = Carbon::now();
            $order = Order::findOrFail($orderId);
            $orderStatus = OrderStatus::where('slug', $orderStatus)->first();
            $order->update(array('order_status_id'=> $orderStatus->id,'updated_at' => $currentTime));
            $orderHistoryArray = array(
                'is_email_sent' => $is_email_sent,
                'order_id' => $orderId,
                'order_status_id' => $orderStatus->id,
                'user_id' => $this->user->id,
                'reason' => $request->other,
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
            );
            $query = OrderHistory::insert($orderHistoryArray);
            $message = "Status Changed successfully";
            if($query && $this->userRoleType=='superadmin'){
                $request->session()->flash('success', $message);
                return redirect('operational/order/view/'.$orderId);
            }else{
                $request->session()->flash('success', $message);
                return redirect('vendor/order/view/'.$orderId);
            }
        } catch (\Exception $e) {
            $message = "Something went wrong";
            $status = 500;
            $data = [
                'data' => $request->all(),
                'action' => 'order return status',
                'exception' => $e->getMessage(),
                'message' => $message,
            ];
            Log::critical(json_encode($data, $status));
        }
    }
    public function getPickupScheduleListData(Request $request,$orderType){
         try{
             $orderStatusId = OrderStatus::where('slug',$orderType)->first();
             $totalRecords = Order::where('order_status_id',$orderStatusId['id'])->get();
             for($i = 0 ; $i < count($totalRecords) ; $i++ ){
                 $totalRecords[$i]['id'] = $this->getStructuredOrderId($totalRecords[$i]['id']);
             }
             return view('backend.partials.seller.order.packing-slip-table')->with(compact('totalRecords','orderStatusId'));
         }catch(\Exception $e){
             $message = "Something went wrong";
             $status = 500;
             $data = [
                 'data' => $request->all(),
                 'action' => 'Pick up schedule document',
                 'exception' => $e->getMessage(),
                 'message' => $message,
             ];
             Log::critical(json_encode($data, $status));
         }
    }
    public function checklistPickupSchedulePreview(Request $request){
        try{
            $date = Carbon::now();
            if($request->has('orders') && !empty($request->orders)){
                $orders = Order::whereIn('id',$request->orders)->with('product','seller')->get()->groupBy('seller.id')->toArray();
                TCPDF::AddPage();
                TCPDF::writeHTML(view('backend.common.pdf.pickUp-schedule')->with(compact('orders','date'))->render());
                TCPDF::Output("Pickup-Schedule_".date('Y-m-d_H_i_s').".pdf", 'D');
            }else{
                $request->session()->flash('error','Please select orders, before printing it!');
                return redirect()->back();
            }
        }catch(\Exception $exception){
            $message = "Something went wrong";
            $status = 500;
            $data = [
                'data' => $request->all(),
                'action' => 'Pick up schedule print',
                'exception' => $exception->getMessage(),
                'message' => $message,
            ];
            Log::critical(json_encode($data, $status));
        }
    }
    public function updateOrderWorkStatus(Request $request,$id){
        try{
            dd($request->all());
            $currentTime = Carbon::now();
            $presentData = WorkOrderStatusDetail::where('order_id',$id)->first();
            $orderCurrentStatus = Order::where('id',$id)->pluck('order_status_id');
            $userId = Role::where('slug',$this->userRoleType)->pluck('id');
            $data['role_id'] = $userId;
            $data['order_id'] = $id;
            $data['work_order_status_id'] = $request['work_type'];
            if($presentData == null){
                $query = WorkOrderStatusDetail::create($data);
            }else{
                $query = WorkOrderStatusDetail::where('order_id',$id)->update($data);
            }
            $is_email_sent = 0;
            $orderHistoryArray = array(
                'is_email_sent'=>$is_email_sent,
                'order_id' => $id,
                'order_status_id' => $orderCurrentStatus,
                'work_order_status_id' => $request['work_type'],
                'user_id' => $this->user->id,
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
            );
            if($presentData == null){
                OrderHistory::insert($orderHistoryArray);
            }else{
                OrderHistory::where('order_id',$id)->where('work_order_status_id',$request['work_type'])->update($orderHistoryArray);
            }
            if($query){
                $request->session()->flash('success','Work status updated successfully');
                return redirect()->back();
            }else{
                $request->session()->flash('error','Something went wrong');
                return redirect()->back();
            }
        }catch(\Exception $e){
            $message = "Something went wrong";
            $status = 500;
            $data = [
                'data' => $request->all(),
                'action' => 'work status update',
                'exception' => $e->getMessage(),
                'message' => $message,
            ];
            Log::critical(json_encode($data, $status));
        }
    }
}
