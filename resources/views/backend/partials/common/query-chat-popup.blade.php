<!--Begin Query Raised popup -->
<div id="query-raised" class="modal" role="dialog" data-dismiss="modal">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            @if($productStatus['slug'] == 'admin_approved' )
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4  class="modal-title">{{ $product['item_based_sku'] }} -History</h4>
                </div>
            @else
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4  class="modal-title"><span id="itembase_sku"></span> -History</h4>
                </div>
            @endif

            @if($productStatus['slug'] == 'admin_approved' )
            <div class="modal-header">
                    <h4 class="text-center"><span class="modal-subtitle">{{ $productStatus['admin_id'] }}</span> has approved - <span class="modal-subtitle">{{ $product['item_based_sku'] }}</span> on {{date('d-m-Y', strtotime($product['approved_date']))   }} </h4>
            </div>
            @endif
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">

                        <div class="portlet light ">

                            <div class="portlet-body">
                                <div class="scroller" style="height: 338px;" data-always-visible="1" data-rail-visible1="0" data-handle-color="#D7DCE2">
                                    <div class="general-item-list" id="conversation-list">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" id="query-form">
                    @if(Session::get('role_type')=='seller')
                    <form action="/product/query-resolved" method="POST">
                        <?php $buttonName = 'Resolve Query' ?>
                        @elseif(Session::get('role_type')=='superadmin')
                        <form action="/operational/products/query-raised" method="POST">
                            <?php $buttonName = 'Raise Query' ?>
                            @else
                            <form action="/verification/product/query-raised" method="POST">
                                <?php $buttonName = 'Raise Query' ?>
                                @endif
                            <div class="col-md-9">
                                <input type="text" name="conversation" id="conversation" required="required" maxlength="500" class="form-control" placeholder="reply">
                                <input type="hidden" name="product_id" id="product_id" value=""/>
                                {!! csrf_field() !!}
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-sm btn-success table-group-action-submit pull-right">{{$buttonName}}</button>
                            </div>
                        </form>
                </div>
            </div>

        </div>

    </div>
</div>
<!--End Query Raised popup -->