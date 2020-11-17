@extends('layouts.app')

@section('content')
<div class="product-items-wrap">
    <h2>新着商品一覧</h2>
    <div class="product-items row">
        @foreach ($products as $product)
        <div class="product-item col-md-3">
            <a href="{{route('products.show',['id'=>$product->id])}}" class="product-item-image-wrap"><img
                    class="product-item-image" src="data:image/png;base64,{{$product->image}}" alt=""></a>
            <h3 class="product-item-name">{{$product->name}}</h3>
            <p class="product-item-price">{{$product->price}}円</p>
            <a href="{{route('products.show',['id'=>$product->id])}}" class="btn btn-primary btn-block mb-1">詳細</a>
            {!! Form::open(['route'=>['AddToCart','id' => $product->id]]) !!}
                {!! Form::submit('カートに追加',['class'=>'btn btn-secondary btn-block mb-1']) !!}
            {!! Form::close() !!}

            @if($product->like_exist(\Auth::id(),$product->id))
                {!! Form::open(['route'=>['products.addLike','id' => $product->id]]) !!}
                    {!! Form::submit('お気に入り解除',['class'=>'btn btn-danger btn-block mb-1']) !!}
                {!! Form::close() !!}
            @else
                {!! Form::open(['route'=>['products.addLike','id' => $product->id]]) !!}
                    {!! Form::submit('お気に入り登録',['class'=>'btn btn-danger btn-block mb-1']) !!}
                {!! Form::close() !!}
            @endif
        </div>
        @endforeach
    </div>
    {{$products->links()}}
</div>

@endsection