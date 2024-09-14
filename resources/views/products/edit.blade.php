@extends('layouts.default')

@section('title',$product->name)
@section('content')
    <h1>商品修改</h1>
    <div class="offset-md-2 col-md-8">
        <div class="card ">
            <div class="card-header">
                <h5>修改</h5>
            </div>
            <div class="card-body">
                @include('shared._errors')
                <form method="POST" action="{{ route('products.update',$product->id) }}">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <div class="mb-3">
                        <label for="name">商品名称：</label>
                        <input type="text" name="name" class="form-control" value="{{ $product->name }}">
                    </div>

                    <div class="mb-3">
                        <label for="description">商品描述：</label>
                        <input type="text" name="description" class="form-control" value="{{ $product->description }}">
                    </div>

                    <div class="mb-3">
                        <label for="price">商品价格：</label>
                        <input class="form-control"  type="number" name="price" id="price" value="{{$product->price}}">
                    </div>

                    <div class="mb-3">
                        <label for="price">商品品牌：</label>
                        <select class="form-control" id="brand" name="brand" required>
                            <option value="">请选择</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ (old('brand', $product->brand_id) == $brand->id) ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="price">商品分类：</label>
                        <select class="form-control" id="category" name="category" required>
                            <option value="">请选择</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ (old('brand', $product->category_id) == $category->id) ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>



                    <button type="submit" class="btn btn-primary">修改</button>
                </form>
            </div>
        </div>
    </div>
@stop

