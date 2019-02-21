<?php
/**
 * Created by PhpStorm.
 * User: amol
 * Date: 9/24/18
 * Time: 10:53 AM
 */
$users = \Illuminate\Support\Facades\Auth::user();
$accountAdminRoleId = \App\Role::where('id',$users->role_id)->where('slug','=','accountadmin')->pluck('id');
?>
<div id="cancel_order_rtv" class="modal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Return To Vendor Reason</h4>
            </div>
            <div class="modal-body">
                @if($users->role_id == $accountAdminRoleId)
                        <form class="form-horizontal" role="form" action="/vendor/order/cancel-order-rtv/return_to_vendor/{{$orderInfo->id}}" method="post">
                    @else
                        <form class="form-horizontal" role="form" action="/operational/order/cancel-order-rtv/return_to_vendor/{{$orderInfo->id}}" method="post">
                @endif
                    {!! csrf_field() !!}
                    <input type="hidden" name="current_status" value="{{$orderInfo->orderStatus->slug}}">
                    <div class="form-group">
                        <label class="col-md-4 control-label"> Reason:
                            <span class="required"> * </span>
                        </label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" value="" name="other" placeholder="" >

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

