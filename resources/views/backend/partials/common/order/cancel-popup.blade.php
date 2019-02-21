<div id="cancel_order" class="modal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Cancel Order</h4>
            </div>
            <div class="modal-body">
                @if(Session::get('role_type')=='seller')
                    <form class="form-horizontal" role="form" action="/order/cancel-order/{{$orderInfo->id}}" method="post">


                    {!! csrf_field() !!}
                    <input type="hidden" name="current_status" value="{{$orderInfo->orderStatus->slug}}">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="text-bold">Please select reason for cancellation</h4>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">Reason</label>
                                <div class="col-md-6">
                                    <select name="customer_cancel_reasons_id" class="form-control form-filter input-sm">
                                        @foreach($cancelReasons as $cancelReason)
                                        <option value={{$cancelReason['id']}}>{{$cancelReason['reason']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 col-md-offset-3">
                                    <p class="text-note">Note - Please note your item will be shown as “Out of stock” post cancellation.</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 pull-right">
                                <a href="javascript:;"><button class="btn btn-sm btn-success pull-right">Submit</button></a>
                            </div>
                        </div>
                    </div>
                </form>
                @elseif(Session::get('role_type')=='superadmin')
                <form class="form-horizontal" role="form" action="/operational/order/cancel-order/{{$orderInfo->id}}" method="post">
                    {!! csrf_field() !!}
                    <input type="hidden" name="current_status" value="{{$orderInfo->orderStatus->slug}}">
                    <div class="form-group">
                        <label class="col-md-4 control-label">Cancel Reason:
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
                @endif
            </div>
        </div>
    </div>
</div>
