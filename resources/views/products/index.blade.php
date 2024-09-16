@extends('layouts.default')
@section('title', '所有商品')

@section('content')
    <div class="container mt-4">
        <h1 class="mb-4">商品列表</h1>

        <div class="row">
            <div class="col-md-3">
                <div class="card mb-4">
                    <div class="card-header">筛选</div>
                    <div class="card-body">
                        <form action="{{ route('products.index') }}" method="GET">
                            <div class="mb-3">
                                <label for="search" class="form-label">搜索</label>
                                <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}">
                            </div>
                            <div class="mb-3">
                                <label for="category" class="form-label">分类</label>
                                <select class="form-select" id="category" name="category">
                                    <option value="">全部分类</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="brand" class="form-label">品牌</label>
                                <select class="form-select" id="brand" name="brand">
                                    <option value="">全部品牌</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">应用筛选</button>
                        </form>
                    </div>
                </div>
            </div>


            <div class="col-md-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        共 {{ $products->total() }} 个商品
                        @if(Auth::user()->is_admin)
                        <!-- 显示更新按钮或表单 -->
                            点击<a href='{{route('products.create')}}'>新增商品</a>
                        @endif


                    </div>

                    <div>
                        <select class="form-select" onchange="location = this.value;">
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => 'desc']) }}" {{ request('sort') == 'created_at' && request('direction') == 'desc' ? 'selected' : '' }}>
                                最新
                            </option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price', 'direction' => 'asc']) }}" {{ request('sort') == 'price' && request('direction') == 'asc' ? 'selected' : '' }}>
                                价格从低到高
                            </option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price', 'direction' => 'desc']) }}" {{ request('sort') == 'price' && request('direction') == 'desc' ? 'selected' : '' }}>
                                价格从高到低
                            </option>
                        </select>
                    </div>
                </div>

{{--                商品展示列表--}}
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    @foreach($products as $product)
                        <div class="col">
                            <div class="card h-100 shadow-sm">
                                <img src="{{ asset('storage/' . $product->getMainImageAttribute()) }}"
                                     class="card-img-top"
                                     alt="{{ $product->name }}"
                                     style="height: 200px; object-fit: cover;">

                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title text-truncate">{{ $product->name }}</h5>
                                    <p class="card-text flex-grow-1">{{ Str::limit($product->description, 80) }}</p>
                                    <div class="mt-auto">
                                        <p class="card-text mb-1">
                                            <strong>价格:</strong>
                                            <span class="text-primary">￥{{ number_format($product->price, 2) }}</span>
                                        </p>
                                        <p class="card-text mb-1">
                                            <small class="text-muted">{{ $product->brand->name }} | {{ $product->category->name }}</small>
                                        </p>
                                        <p class="card-text mb-0">
                                            <small class="text-muted">
                                                库存：
                                                @if($product->stock->quantity <= 0)
                                                    <span class="text-danger">商品已经告罄</span>
                                                @else
                                                    {{ $product->stock->quantity }}
                                                @endif
                                            </small>
                                        </p>
                                    </div>
                                </div>


                                <div class="card-footer" >
                                    <div class="row g-2">
                                        <div class="col-12">
                                            <a href="{{ route('products.show', $product) }}" class="btn btn-primary btn-sm w-100">查看详情</a>
                                        </div>
                                        @can('update', $product)
                                            <div class="col-12">
                                                <a href="{{ route('products.edit', $product) }}" class="btn btn-secondary btn-sm w-100">编辑</a>
                                            </div>
                                        @endcan
                                        <div class="col-12">
                                            @if (Auth::user()->isFavorite($product->id))
                                                <form action="{{ route('products.unfavorite', $product->id) }}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-primary btn-sm w-100">取消收藏</button>
                                                </form>
                                            @else
                                                <form action="{{ route('products.favorite', $product->id) }}" method="post">
                                                    @csrf
                                                    <button type="submit" class="btn btn-primary btn-sm w-100">收藏</button>
                                                </form>
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            <form action="{{ route('products.purchased', $product->id) }}" method="post">
                                                @csrf
                                                @if($product->stock->quantity <= 0)
                                                    <button type="button" class="btn btn-secondary btn-sm w-100" disabled>商品已经告罄</button>
                                                @else
                                                    <button type="submit" class="btn btn-success btn-sm w-100">购买</button>
                                                @endif
                                            </form>
                                        </div>
                                        @can('destroy', $product)
                                            <div class="col-12">
                                                <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                                      onsubmit="return confirm('您确定要删除本条商品吗？');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm w-100">删除</button>
                                                </form>
                                            </div>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
@stop
