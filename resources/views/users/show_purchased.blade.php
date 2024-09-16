@extends('layouts.default')
@section('title', '购买')

@section('content')
    <div class="offset-md-2 col-md-8">
        <h2 class="mb-4 text-center">购买</h2>

        <div class="list-group list-group-flush">
            @foreach ($products as $product)
                <div class="list-group-item">
                    {{--                    <img class="me-3" src="{{ $user->gravatar() }}" alt="{{ $user->name }}" width=32>--}}
                    <a class="text-decoration-none" href="{{ route('products.show', $product) }}">
                        {{ $product->name }}
                    </a>
                </div>

            @endforeach
        </div>

        <div class="mt-3">
            {!! $products->render() !!}
        </div>
    </div>
@stop
