@extends('layouts.app')

@section('content')
@can('admin-only')
<div class="product-create-table">
    <h2>「{{$product->name}}」の商品情報を編集</h2>
    {{--  Form::modelにすることで初期値が反映される  --}}
    {!! Form::model($product,['route'=>['admin.products.update','id'=>$product->id],'method'=>'update']) !!}
    <div class="form-group">
        {!! Form::label('name', '商品名') !!}
        {!! Form::input('text','name',null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('price', '価格') !!}
        {!! Form::input('number','price',null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('description', '商品概要') !!}
        {!! Form::input('textarea','description',null, ['class' => 'form-control']) !!}
    </div>
    {!! Form::submit('更新', ['class' => 'btn btn-primary btn-block']) !!}
    {!! Form::close() !!}



</div>
@endcan
@endsection