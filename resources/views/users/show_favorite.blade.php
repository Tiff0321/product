@extends('layouts.default')
@section('title', '收藏')

@section('content')
    <div class="offset-md-2 col-md-8">
        <h2 class="mb-4 text-center">收藏</h2>

        <div class="list-group list-group-flush">
            @foreach ($products as $product)
                <div class="list-group-item">
{{--                    <img class="me-3" src="{{ $user->gravatar() }}" alt="{{ $user->name }}" width=32>--}}
                    <a class="text-decoration-none" href="{{ route('products.show', $product) }}">
                        {{ $product->name }}
                    </a>
                </div>

                <div class="list-group-item">
                <form action="{{ route('products.unfavorite', $product->id) }}" method="post">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button type="submit" class="btn btn-sm btn-outline-primary">取消收藏</button>
                </form>
                </div>

            @endforeach
        </div>

        <div class="mt-3">
            {!! $products->render() !!}
        </div>
    </div>
@stop
