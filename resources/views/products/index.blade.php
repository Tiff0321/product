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
                            <div class="card h-100">
                                <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $product->name }}</h5>
                                    <p class="card-text">{{ Str::limit($product->description, 100) }}</p>
                                    <p class="card-text"><strong>价格:</strong> ￥{{ number_format($product->price, 2) }}</p>
                                    <p class="card-text"><small class="text-muted">{{ $product->brand->name }} | {{ $product->category->name }}</small></p>
                                    <p class="card-text">库存：<small class="text-muted">
                                            @if($product->stock->quantity <= 0)
                                                商品已经告罄
                                            @else
                                             {{ $product->stock->quantity }}
                                            @endif</small></p>



                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-primary">查看详情</a>
                                    @can('update',$product)
                                        <a href="{{ route('products.edit', $product) }}">更新</a>
                                    @endcan


                                    <div class="text-center mt-2 mb-4">
                                        @if (Auth::user()->isFavorite($product->id))
                                            <form action="{{ route('products.unfavorite', $product->id) }}" method="post">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                                <button type="submit" class="btn btn-sm btn-outline-primary">取消收藏</button>
                                            </form>
                                        @else
                                            <form action="{{ route('products.favorite', $product->id) }}" method="post">
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-sm btn-primary">收藏</button>
                                            </form>
                                        @endif
                                    </div>

                                    <form action="{{ route('products.purchased', $product->id) }}" method="post" class="btn">
                                        {{ csrf_field() }}
                                        @if($product->stock->quantity <= 0)
                                            商品已经告罄
                                            @else
                                            <button type="submit" class="btn btn-sm btn-outline-primary">购买</button>
                                            @endif

                                    </form>






                                    @can('destroy',$product)
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                              onsubmit="return confirm('您确定要删除本条商品吗？');">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <button type="submit" class="btn btn-sm btn-danger status-delete-btn">删除</button>
                                        </form>
                                    @endcan
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
