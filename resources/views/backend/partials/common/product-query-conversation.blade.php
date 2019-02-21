
@foreach($chatHistoryData as $chatHistory)
<div class="item">
    <div class="item-head">
        <div class="item-details">
            <img class="item-pic rounded" height="35" width="35" src="{{$chatHistory['image']}}">
            {{$chatHistory['userName']}}
            <span class="item-label">{{$chatHistory['time']}} Ago</span>
        </div>
        <div class="status pull-right">
            <span class="label label-{{$chatHistory['class']}}"> {{$chatHistory['status']}} </span>
        </div>
    </div>
    <div class="item-body">
        {{chunk_split( $chatHistory['message'],50,"\n")}}
    </div>
</div>
@endforeach
