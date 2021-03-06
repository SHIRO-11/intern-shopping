@extends('layouts.app')

@section('content')
<div class="cart-wrap">
    <h2>カート</h2>
    <div class="cart-table-wrap">
        <table class="table table-striped">
            <thead>
                <tr align="center">
                    <th align="center" scope="col">商品画像</th>
                    <th scope="col">商品名</th>
                    <th scope="col">数量</th>
                    <th scope="col">価格</th>
                    <th scope="col"></th>
                </tr>
            </thead>

            <tbody>
                {{--  商品があるなら  --}}
                @if(!$cartItems->isEmpty())
                @foreach($cartItems as $item)
                <tr align="center">
                    <td><img src="data:image/png;base64,{{$item->product->image}}" width="30px" height="30px"></td>
                    <td>{{$item->product->name}}</td>
                    <td>
                        {!! Form::open(['route'=>['IncreaseSingleProduct','id' =>$item->product->id],'class'=>'d-inline-block']) !!}
                        {!! Form::submit('+') !!}
                        {!! Form::close() !!}

                        {{$item->quanity}}

                        {!! Form::open(['route'=>['DecreaseSingleProduct','id' =>$item->product->id],'class'=>'d-inline-block']) !!}
                        {!! Form::submit('-') !!}
                        {!! Form::close() !!}
                    </td>
                    <td>{{$item->product->price * $item->quanity}}円</td>
                    <td>
                        {!! Form::open(['route'=>['DeleteItemFromCart','id' => $item->product->id]]) !!}
                        {!! Form::submit('削除',['class'=>'btn btn-sm btn-danger btn-block']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
                @endforeach

                <tr align="center">
                    <td></td>
                    <th>合計</th>
                    <th>{{$total['quantity']}}</th>
                    <th>{{$total['price']}}円</th>
                    <td>
                        {!! Form::open(['route'=>'sample_pay']) !!}
                        <input type="hidden" class="form-control" name="value" value="{{$total['price']}}" required>
                        <button type="submit" id="payButton"
                            class="product-item-btn btn btn-primary btn-block">今すぐ購入</button>
                        {!! Form::close() !!}
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="product-btn-wrap">
    </div>
</div>

@endsection