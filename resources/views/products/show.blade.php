@extends('layouts.default')

@section('title',$product->name)
@section('content')
    <h1>商品详情</h1>
    <div class="row">
        <div class="offset-md-2 col-md-8">
            <div class="col-md-12">
                <div class="offset-md-2 col-md-8">
                    <section class="user_info">
{{--                        @include('shared._product_info', ['product' => $product])--}}
                    </section>
                </div>
            </div>
        </div>
    </div>
    <table>
        <tr>
            <td>商品名：</td>
            <td>{{$product->name}}</td>
        </tr>
        <tr>
            <td>商品描述：</td>
            <td>{{$product->description}}</td>
        </tr>
        <tr>
            <td>商品价格：</td>
            <td>{{$product->price}}</td>
        </tr>
        <tr>
            <td>商品分类：</td>
            <td>{{$category}}</td>
        </tr>
        <tr>
            <td>商品品牌：</td>
            <td>{{$brand}}</td>
        </tr>
    </table>

    @can('update',$product)
        <a href="{{ route('products.edit', $product) }}" class="btn btn-info">更新</a>
    @endcan
@stop

