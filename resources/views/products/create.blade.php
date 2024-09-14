@extends('layouts.default')

@section('title','商品新增')
@section('content')
    <h1>商品新增</h1>
    <div class="offset-md-2 col-md-8">
        <div class="card ">
            <div class="card-header">
                <h5>新增</h5>
            </div>
            <div class="card-body">
                @include('shared._errors')
                <form method="POST" action="{{ route('products.store') }}">
                    {{ csrf_field() }}
                    <div class="mb-3">
                        <label for="name">商品名称：</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                    </div>

                    <div class="mb-3">
                        <label for="description">商品描述：</label>
                        <input type="text" name="description" class="form-control" value="{{ old('description') }}">
                    </div>

                    <div class="mb-3">
                        <label for="price">商品价格：</label>
                        <input class="form-control"  type="number" name="price" id="price" value="{{ old('price') }}">
                    </div>

                    <div class="mb-3">
                        <label for="price">商品品牌：</label>
                        <select class="form-control" id="brand" name="brand" required>
                            <option value="">请选择</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="price">商品分类：</label>
                        <select class="form-control" id="category" name="category" required>
                            <option value="">请选择</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>



                    <button type="submit" class="btn btn-primary">新建</button>
                </form>
            </div>
        </div>
    </div>
@stop

