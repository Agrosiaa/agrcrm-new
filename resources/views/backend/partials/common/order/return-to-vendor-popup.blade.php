<?php
/**
 * Created by PhpStorm.
 * User: Nishank Rathod
 * Date: 9/6/18
 * Time: 10:03 AM
 */
$rmaReasons = \App\RmaReason::all();
$currentLanguage = \App\Helpers\LanguageHelper::currentLanguage();
$users = \Illuminate\Support\Facades\Auth::user();
$accountAdminRoleId = \App\Role::where('id',$users->role_id)->where('slug','=','accountadmin')->pluck('id');
$rtvMicroStatus = \App\RtvMicroStatus::get()->toArray();
$rtvMicroStatusDetails = \App\RtvMicroStatusDetails::where('order_id',$orderInfo->id)->first();
$returnReason = \App\OrderRma::where('order_id',$orderInfo->id)->with('rmaReason')->first();
?>
<style>
    .checkbox-inline+.checkbox-inline, .radio-inline+.radio-inline{
        margin-top: 0;
        margin-left: 0px!important;
    }
    .radio-inline {
        margin-top: 0;
        margin-bottom: 0;
        padding-top: 7px;
        padding-left: 0px;
    }
</style>
<div id="return_to_vendor" class="modal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Return Order</h4>
            </div>
            <div class="modal-body">
                @if($users->role_id == $accountAdminRoleId)
                    <form id="create-return-validation" class="form-horizontal" role="form" action="/vendor/order/return-to-vendor-rma/{{$orderInfo->id}}" method="post">
                  @else
                    <form id="create-return-validation" class="form-horizontal" role="form" action="/operational/order/return-to-vendor-rma/{{$orderInfo->id}}" method="post">
                @endif
                    {{csrf_field()}}
                <input type="hidden" name="order_id" id="order_id" value="{{$orderInfo['id']}}"/>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="reason" class="col-sm-4 control-label">@lang('message.Reason_for_return_text')</label>
                                <div class="col-sm-8">
                                    <select class="form-control reason_select" name="rma_reason" id="rma_reason" required>
                                        @if($returnReason == null)
                                            @if($currentLanguage == 'mr')
                                                @foreach($rmaReasons as $rmaReason)
                                                    <option  value="" ></option>
                                                    <option  value="{{$rmaReason['slug']}}" >{{$rmaReason['name_mr']}}</option>
                                                @endforeach
                                            @else
                                                @foreach($rmaReasons as $rmaReason)
                                                    <option  value="" ></option>
                                                    <option  value="{{$rmaReason['slug']}}" >{{$rmaReason['name']}}</option>
                                                @endforeach
                                            @endif
                                         @else
                                            @foreach($rmaReasons as $rmaReason)
                                                @if($rmaReason['slug'] == $returnReason['rmaReason']['slug'])
                                                <option  value="{{$rmaReason['slug']}}" selected>{{$rmaReason['name']}}</option>
                                                    @else
                                                <option  value="{{$rmaReason['slug']}}">{{$rmaReason['name']}}</option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            @if($users->role_id != $accountAdminRoleId)
                            <div class="form-group">
                                <label for="reason" class="col-sm-4 control-label">@lang('message.move_to_status')</label>
                                <div class="col-sm-8">
                                    @foreach( $rtvMicroStatus as $key=>$status)
                                        <label class="radio-inline">
                                            @if($rtvMicroStatusDetails != null && $status['id'] == $rtvMicroStatusDetails['rtv_micro_status_id'])
                                                <input type="radio" class="form-control rtv_micro_status" name="rtv_micro_status" value="{{$status['id']}}" checked>{{$status['name']}}
                                            @else
                                                <input type="radio" class="form-control rtv_micro_status" name="rtv_micro_status" value="{{$status['id']}}">{{$status['name']}}
                                            @endif
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="form-group" id="show-me" hidden>
                                <label for="reason" class="col-sm-4 control-label">@lang('message.reconcile_order'):</label>
                                <div class="col-sm-8">
                                    @if($rtvMicroStatusDetails != null)
                                       <input type="text" class="form-control" name="reconcile_order_number" value="{{$rtvMicroStatusDetails['reconcile_order_number']}}">
                                    @else
                                       <input type="text" class="form-control" name="reconcile_order_number" value="">
                                    @endif
                                </div>
                            </div>
                            @endif
                            <div class="form-group">
                                <label for="reason" class="col-sm-4 control-label">@lang('message.Product_Name_text'):</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="product_name" value="{{$orderInfo->product->product_name}}" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="reason" class="col-sm-4 control-label">@lang('message.sku_text'):</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="product_sku" value="{{$orderInfo->product->item_based_sku}}" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="reason" class="col-sm-4 control-label">@lang('message.quantity_text'):</label>
                                <div class="col-sm-8">
                                    <select class="form-control reason_select" name="return_quantity" readonly="">
                                            <option  value="{{$orderInfo->quantity}}" >{{$orderInfo->quantity}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 pull-right">
                            <a href="javascript:;"><button class="btn btn-sm btn-success pull-right">Submit</button></a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
