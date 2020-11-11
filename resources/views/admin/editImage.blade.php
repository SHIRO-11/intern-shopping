@extends('layouts.app')

@section('content')
@can('admin-only')
<div class="product-create-table">
    <h2>画像編集</h2>

    {!! Form::open(['route'=>['admin.products.updateImage','id'=>$product->id],'enctype'=>'multipart/form-data']) !!}

    <div class='form-group'>
        {!! Form::label('image','商品画像') !!}
        {!! Form::file('image') !!}
    </div>
    {!! Form::submit('更新', ['class' => 'btn btn-primary btn-block']) !!}
    {!! Form::close() !!}

</div>
@endcan
@endsection