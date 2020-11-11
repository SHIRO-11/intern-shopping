@extends('layouts.app')

@section('content')
<div class="main">
    <div class="product-show-wrap">
        <h2>商品名</h2>
        <div class="product-item-show-wrap">
            <div class="product-item-show-img-wrap">
                <img class="product-item-show-img" src="/storage/product_images/{{$product->image}}">
            </div>
            <div class="product-item-table-wrap">
                <table class="table">
                    <tbody>
                        <tr>
                            <th scope="row">商品名</th>
                            <td>{{$product->name}}</td>
                        </tr>
                        <tr>
                            <th scope="row">価格</th>
                            <td>{{$product->price}}</td>
                        </tr>
                        <tr>
                            <th scope="row">説明</th>
                            <td>{{$product->description}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="product-btn-wrap">
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
    </div>
</div>

@endsection