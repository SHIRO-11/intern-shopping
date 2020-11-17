@extends('layouts.app')

@section('content')
<div class="main">
    <div class="product-show-wrap">
        <h2>商品名</h2>
        <div class="product-item-show-wrap">
            <div class="product-item-show-img-wrap">
                <img class="product-item-show-img" src="data:image/png;base64,{{$product->image}}">
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
    </div>
</div>

@endsection