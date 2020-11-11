@extends('layouts.app')

@section('content')
@can('admin-only')
<div class="product-create-table">
    <h2>商品作成</h2>

    {!! Form::open(['route'=>'admin.products.store','enctype'=>'multipart/form-data']) !!}
    <div class="form-group">
        {!! Form::label('name', '商品名') !!}
        {!! Form::text('name','', ['class' => 'form-control']) !!}
    </div>

    <div class='form-group'>
        {!! Form::label('image','商品画像') !!}
        {!! Form::file('image') !!}
    </div>

    <div class="form-group">
        {!! Form::label('price', '価格') !!}
        {!! Form::text('price','', ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('description', '商品概要') !!}
        {!! Form::textarea('description','', ['class' => 'form-control']) !!}
    </div>
    {!! Form::submit('登録する', ['class' => 'btn btn-primary btn-block']) !!}
    {!! Form::close() !!}



</div>
@endcan
@endsection