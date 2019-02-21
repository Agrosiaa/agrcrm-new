<?php
namespace App\Http\Controllers\CustomTraits;

use App\FinanceDocument;
use App\FinanceDocumentOrderInfo;
use App\Order;
use App\OrderHistory;
use App\OrderStatus;
use App\Product;
use App\Role;
use App\Seller;
use App\ShippingMethod;
use App\UserNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait NotificationTrait{

    public function getStructuredOrderId($orderId){
        return str_pad($orderId, 9, "0", STR_PAD_LEFT);
    }

    public function readNotification(Request $request){
        try{
            $status = 200;
            $userNotify = UserNotification::where('user_id',$this->user->id)->first();
            if($userNotify != null){
                $userNotify->update(['last_login' => Carbon::now()]);
            }else{
                UserNotification::create([
                    'user_id' => $this->user->id,
                    'last_login' => Carbon::now()
                ]);
            }
            return response()->json("success",$status);
        }catch(\Exception $e){
            $data = [
                'user' => $this->user,
                'role' => $this->userRoleType,
                'action' => 'Read Notification',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }

    public function getNotification(Request $request){
        try{
            $status = 200;
            $user = $this->user;
            $userLastLogin = UserNotification::where('user_id',$user->id)->pluck('last_login');
            $roleSlug = Role::where('id',$user->role_id)->first()->toArray();
            $notificationList = array();
            if($roleSlug['slug'] == 'financeadmin'){
                    $notifications = FinanceDocumentOrderInfo::orderBy('created_at','desc')->get()->toArray();
                    if($userLastLogin == null){
                        $notificationCount = count($notifications);
                    }else{
                        $notificationCount = FinanceDocumentOrderInfo::where('created_at','>=',$userLastLogin)->count();
                    }
                    $notificationTitle = "Notifications";
                    foreach($notifications as $notification){
                        $document = FinanceDocument::where('id',$notification['finance_doc_id'])->select('name','abbreviation','created_at','slug')->first()->toArray();
                        $structured_order_id = str_pad($notification['order_id'], 9, "0", STR_PAD_LEFT);
                        $markedDate = date('d F Y H:i:s',strtotime($notification['created_at']));
                        $link = "/finance/order/document/".$document['slug']."/".$notification['order_id'];
                        $notificationList[] = "<tr><td style='background-color: #e1f2cb; height: auto;'><br>
                                                   <div style='height: auto;width:inherit; margin-left:20px; font-size: 12px'>
                                                        ".$document['name']." ".$document['abbreviation'].$structured_order_id." is generated for Order ID AGR$structured_order_id<br>

                                                        <div style='margin-top: 10px; width:90%'>
                                                        <span style=' font-size: 10px'; color:#90949c;>$markedDate</span>
                                                        <a href=$link class='btn btn-sm btn-success' style='float:right'>View</a></div>

                                                   </div>
                                                   <br><div style='height: 10px;'></div>
                                                   <center> <div style='border-bottom:2px solid #d3d3d3; width:85%'>
                                                    </div></center>
                                                </td></tr>";
                    }
            }elseif($roleSlug['slug'] == 'seller'){
                $seller_id = Seller::where('user_id',$this->user['id'])->pluck('id');
                $notifications = Order::where('seller_id',$seller_id)->orderBy('created_at','desc')->get()->toArray();
                foreach($notifications as $notification){
                    $structured_order_id = str_pad($notification['id'], 9, "0", STR_PAD_LEFT);
                    $markedDate = date('d F Y H:i:s',strtotime($notification['created_at']));
                    $product_name = ucwords(Product::where('id',$notification['product_id'])->pluck('product_name'));
                    $notificationList[] = "<tr><td style='background-color: #e1f2cb; height: auto;'><br>
                                                <div style='height: auto;width:inherit; margin-left: 20px; font-size:12px;'>
                                                    Order AGR$structured_order_id has been placed for product $product_name.
                                                        <div style='margin-top: 10px; font-size: 10px'; color:#90949c;>$markedDate</div>
                                                </div><div style='height: 10px;'></div>
                                                <center> <div style='border-bottom:2px solid #d3d3d3; width:85%'>
                                                </div></center>
                                                </td></tr>";
                }
                if($userLastLogin == null){
                    $notificationCount = count($notifications);
                }else{
                    $notificationCount = Order::where('seller_id',$seller_id)->where('created_at','>=',$userLastLogin)->select('order_id','created_at')->count();
                }
                $notificationTitle = "Notifications";
            }elseif($roleSlug['slug'] == 'shipmentadmin'){
                $orderStatus = OrderStatus::wherein('slug',['back_ordered','cancel','declined','ready_to_ship'])->lists('id');
                $orderHistoryInfo = OrderHistory::whereIn('order_status_id',$orderStatus)->select('order_id','created_at','order_status_id')->orderBy('created_at','desc')->get()->toArray();
                foreach($orderHistoryInfo as $historyInfo){
                    $orderInfo = Order::where('id',$historyInfo['order_id'])->first();
                    $structuredOrderId = $this->getStructuredOrderId($orderInfo->id);
                    $markedDate = date('d F Y H:i:s',strtotime($historyInfo['created_at']));
                    $currentStatus = OrderStatus::where('id',$historyInfo['order_status_id'])->pluck('slug');
                    switch($currentStatus){
                        case 'ready_to_ship':
                            $sellerCompany = $orderInfo->seller->company;
                            $notificationList[] = "<tr><td style='background-color: #e1f2cb; height: auto;'><br>
                                                <div style='height: auto;width:inherit; margin-left: 20px; font-size:12px;'>
                                                    Order AGR$structuredOrderId marked as <span style='color:black;'>\"Ready To Ship\"</span> by $sellerCompany.
                                                        <div style='margin-top: 10px; font-size: 10px'; color:#90949c;>$markedDate</div>
                                                </div><div style='height: 10px;'></div>
                                                <center> <div style='border-bottom:2px solid #d3d3d3; width:85%'>
                                                </div></center>
                                                </td></tr>";
                                              break;

                        case 'cancel':
                            $notificationList[] = "<tr><td style='background-color: #e1f2cb; height: auto;'><br>
                                                <div style='height: auto;width:inherit; margin-left: 20px; font-size:12px;'>
                                                    Order AGR$structuredOrderId has been <span style='color:black;'>\"Cancelled\"</span> by customer.
                                                        <div style='margin-top: 10px; font-size: 10px'; color:#90949c;>$markedDate</div>
                                                </div><div style='height: 10px;'></div>
                                                <center> <div style='border-bottom:2px solid #d3d3d3; width:85%'>
                                                </div></center>
                                                </td></tr>";
                                              break;

                        case 'declined':
                            $notificationList[] = "<tr><td style='background-color: #e1f2cb; height: auto;'><br>
                                                <div style='height: auto;width:inherit; margin-left: 20px; font-size:12px;'>
                                                    Order AGR$structuredOrderId has been <span style='color:black;'>\"Declined\"</span> by admin due to some reason.
                                                        <div style='margin-top: 10px; font-size: 10px'; color:#90949c;>$markedDate</div>
                                                </div><div style='height: 10px;'></div>
                                                <center> <div style='border-bottom:2px solid #d3d3d3; width:85%'>
                                                </div></center>
                                                </td></tr>";
                            break;

                        case 'back_ordered':
                            $notificationList[] = "<tr><td style='background-color: #e1f2cb; height: auto;'><br>
                                                <div style='height: auto;width:inherit; margin-left: 20px; font-size:12px;'>
                                                    Order AGR$structuredOrderId has been <span style='color:black;'>\"Backordered\"</span> by vendor due to some reason.
                                                        <div style='margin-top: 10px; font-size: 10px'; color:#90949c;>$markedDate</div>
                                                </div><div style='height: 10px;'></div>
                                                <center> <div style='border-bottom:2px solid #d3d3d3; width:85%'>
                                                </div></center>
                                                </td></tr>";
                            break;
                    }
                }
                if($userLastLogin == null){
                    $notificationCount = count($orderHistoryInfo);
                }else{
                    $notificationCount = OrderHistory::whereIn('order_status_id',$orderStatus)->where('created_at','>=',$userLastLogin)->select('order_id','created_at')->count();
                }
                $notificationTitle = "Notifications";
            }elseif($roleSlug['slug'] == 'shipmentpartner'){
                $shipping_method_id = ShippingMethod::where('user_id',$this->user['id'])->pluck('id');
                $orderIds = Order::where('shipping_method_id',$shipping_method_id)->lists('id');
                $orderStatus = OrderStatus::whereIn('slug',['back_ordered','cancel','declined','ready_to_ship'])->lists('id');
                $orderHistoryInfo = OrderHistory::whereIn('order_status_id',$orderStatus)->whereIn('order_id',$orderIds)->select('order_id','order_status_id','created_at')->get()->toArray();
                foreach($orderHistoryInfo as $historyInfo){
                    $orderInfo = Order::where('id',$historyInfo['order_id'])->first();
                    $structuredOrderId = $this->getStructuredOrderId($orderInfo->id);
                    $markedDate = date('d F Y H:i:s',strtotime($historyInfo['created_at']));
                    $currentStatus = OrderStatus::where('id',$historyInfo['order_status_id'])->pluck('slug');
                    switch($currentStatus){
                        case 'ready_to_ship':
                            $sellerCompany = $orderInfo->seller->company;
                            $notificationList[] = "<tr><td style='background-color: #e1f2cb; height: auto;'><br>
                                                <div style='height:auto;width:inherit; margin-left: 20px; font-size:12px;'>
                                                    Order AGR$structuredOrderId marked as <span style='color:black;'>\"Ready To Ship\"</span> by $sellerCompany.
                                                        <div style='margin-top:10px; font-size: 10px; color:#90949c;'>$markedDate</div>
                                                </div><div style='height: 10px;'></div>
                                               <center> <div style='border-bottom:2px solid #d3d3d3; width:85%'>
                                                </div></center>
                                            </td></tr>";
                            break;

                        case 'cancel':
                            $notificationList[] = "<tr><td style='background-color: #e1f2cb; height: auto;'><br>
                                                <div style='height: auto;width:inherit; margin-left: 20px; font-size:12px;'>
                                                    Order AGR$structuredOrderId has been <span style='color:black;'>\"Cancelled\"</span> by customer.
                                                        <div style='margin-top: 10px; font-size: 10px'; color:#90949c;>$markedDate</div>
                                                </div><div style='height: 10px;'></div>
                                                <center> <div style='border-bottom:2px solid #d3d3d3; width:85%'>
                                                </div></center>
                                                </td></tr>";
                            break;

                        case 'declined':
                            $notificationList[] = "<tr><td style='background-color: #e1f2cb; height: auto;'><br>
                                                <div style='height: auto;width:inherit; margin-left: 20px; font-size:12px;'>
                                                    Order AGR$structuredOrderId has been <span style='color:black;'>\"Declined\"</span> by admin due to some reason.
                                                        <div style='margin-top: 10px; font-size: 10px'; color:#90949c;>$markedDate</div>
                                                </div><div style='height: 10px;'></div>
                                                <center> <div style='border-bottom:2px solid #d3d3d3; width:85%'>
                                                </div></center>
                                                </td></tr>";
                            break;

                        case 'back_ordered':
                            $notificationList[] = "<tr><td style='background-color: #e1f2cb; height: auto;'><br>
                                                <div style='height: auto;width:inherit; margin-left: 20px; font-size:12px;'>
                                                    Order AGR$structuredOrderId has been <span style='color:black;'>\"Backordered\"</span> by vendor due to some reason.
                                                        <div style='margin-top: 10px; font-size: 10px'; color:#90949c;>$markedDate</div>
                                                </div><div style='height: 10px;'></div>
                                                <center> <div style='border-bottom:2px solid #d3d3d3; width:85%'>
                                                </div></center>
                                                </td></tr>";
                            break;

                    }

                }
                if($userLastLogin == null){
                    $notificationCount = count($orderHistoryInfo);
                }else{
                    $notificationCount = OrderHistory::whereIn('order_status_id',$orderStatus)->whereIn('order_id',$orderIds)->where('created_at','>=',$userLastLogin)->select('order_id','created_at')->count();
                }
                $notificationTitle = "Notifications";
            }
            $data = [
                'notificationCount' => $notificationCount,
                'notificationList' => $notificationList,
                'notificationTitle' => $notificationTitle
            ];
            return response()->json($data,$status);
        }catch(\Exception $e){
            $data = [
                'user' => $this->user,
                'role' => $this->userRoleType,
                'action' => 'Get Notification',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }
}