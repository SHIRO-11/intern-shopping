@extends('layouts.app')

@section('content')
<div class="product-items-wrap">
    <h2>新着商品一覧</h2>
    <div class="product-items row">
        @foreach ($products as $product)
        <div class="product-item col-md-3">
            <a href="{{route('products.show',['id'=>$product->id])}}" class="product-item-image-wrap"><img
                    class="product-item-image" src="/storage/product_images/{{$product->image}}" alt=""></a>
            <h3 class="product-item-name">{{$product->name}}</h3>
            <p class="product-item-price">{{$product->price}}円</p>
            <a href="{{route('products.show',['id'=>$product->id])}}" class="btn btn-primary btn-block">詳細</a>
            <a href="{{route('AddToCart',['id'=>$product->id])}}"
                class="product-item-btn btn btn-secondary btn-block">カートに追加</a>
            @if($product->like_exist(\Auth::id(),$product->id))
            <a href="{{route('products.addLike',['id'=>$product->id])}}"
                class="product-item-btn btn btn-danger btn-block">お気に入り解除</a>
            @else
            <a href="{{route('products.addLike',['id'=>$product->id])}}"
                class="product-item-btn btn btn-danger btn-block">お気に入り登録</a>
            @endif
        </div>
        @endforeach
    </div>
    {{$products->links()}}
</div>

@endsection