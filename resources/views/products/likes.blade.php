@extends('layouts.app')

@section('content')
<div class="main container">
    <div class="product-items-wrap">
        <h2>お気に入り商品一覧</h2>
        <div class="product-items row">
            @foreach ($like_products as $product)
            <div class="product-item col-md-3">
                <a href="{{route('products.show',['id'=>$product->product->id])}}" class="product-item-image-wrap"><img
                        class="product-item-image" src="/storage/product_images/{{$product->product->image}}" alt=""></a>
                <h3 class="product-item-name">{{$product->product->name}}</h3>
                <p class="product-item-price">{{$product->product->price}}</p>
                <p class="product-item-btn btn btn-primary btn-block">今すぐ購入</p>
                <a href=""
                    class="product-item-btn btn btn-secondary btn-block">カートに追加</a>
                @if($product->product->like_exist(\Auth::id(),$product->product->id))
                <a href="{{route('products.addLike',['id'=>$product->product->id])}}"
                    class="product-item-btn btn btn-danger btn-block">お気に入り解除</a>
                @else
                <a href="{{route('products.addLike',['id'=>$product->product->id])}}"
                    class="product-item-btn btn btn-danger btn-block">お気に入り登録</a>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection