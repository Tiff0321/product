@extends('layouts.default')

@section('title', '首页')
@section('content')
    <div class="bg-light p-3 p-sm-5 rounded">
        <h1>Hello Product</h1>
        <p class="lead">
            你现在所看到的是 <a href="{{route('products.index')}}">商品项目</a> 的主页。
        </p>
        <p>
            一切，将从这里开始。
        </p>
        <p>
            <a class="btn btn-lg btn-success" href="{{route('users.create')}}" role="button">现在注册</a>
        </p>

    </div>
@stop
