@extends('layouts.app')

@section('content')
<div class="main">
    <div class="cart-wrap">
        <h2>商品管理画面</h2>
        <div class="cart-table-wrap">
            <table class="table table-striped">
                <thead>
                    <tr align="center">
                        <th align="center" scope="col">商品画像</th>
                        <th scope="col">商品名</th>
                        <th scope="col">価格</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($products as $product)
                    <tr align="center">
                        <td><a href="{{route('admin.products.editImage',['id'=>$product->id])}}"><img
                                    src="/storage/product_images/{{$product->image}}" width="30px" height="30px"></a>
                        </td>
                        <td>{{$product->name}}</td>
                        <td>{{$product->price}}</td>
                        <td>
                            <a href="{{route('admin.products.edit',['id'=>$product->id])}}"
                                class="btn btn-sm btn-secondary">編集</a>
                        </td>
                        <td>
                            {!! Form::open(['route' => ['admin.products.delete', $product->id], 'method' => 'delete']) !!}
                            {!! Form::submit('削除', ['class' => 'btn btn-danger btn-sm']) !!}
                            {!! Form::close() !!}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="product-btn-wrap">
        </div>
    </div>
</div>

@endsection