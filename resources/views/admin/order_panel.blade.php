@extends('layouts.app')

@section('content')
<div class="main">
    <div class="cart-wrap">
        <h2>注文管理画面</h2>
        <div class="cart-table-wrap row col-md-12">
            <table class="table table-striped">
                <thead>
                    <tr align="center">
                        <th align="center" scope="col">名前</th>
                        <th scope="col">郵便番号</th>
                        <th scope="col">住所</th>
                        <th scope="col">商品 × 個数</th>
                        <th scope="col">状態</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($orders as $order)
                    <tr align="center">
                        <td>{{$order->last_name}} {{$order->first_name}}</td>
                        <td>{{$order->post_code}}</td>
                        <td>{{$order->address}}</td>
                        <td>
                            @foreach($order->orderProducts as $product)
                            <a href="{{route('products.show',['id'=>$product->product->id])}}">{{$product->product_name}}</a> × {{$product->quantity}}個<br>
                            @endforeach
                        </td>
                        <td>
                            {!! Form::open(['route'=>'order_status']) !!}
                                {!! Form::hidden('order_id', $order->id) !!}
                                @if($order->status == 'hold')
                                    {!! Form::hidden('status', 'hold') !!}
                                    {!! Form::submit('発送待ち', ['class'=>'btn btn-sm btn-danger']) !!}
                                @elseif($order->status == 'complete')
                                    {!! Form::hidden('status', 'complete') !!}
                                    {!! Form::submit('発送済み', ['class'=>'btn btn-sm btn-primary']) !!}
                                @endif
                            {!! Form::close() !!}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $orders->links() }}
        </div>
        <div class="product-btn-wrap">
        </div>
    </div>
</div>

@endsection