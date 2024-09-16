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

                <form method="POST" action="{{ route('ProductImages.update', $product->id) }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('POST') }}
                    <div class="mb-3">
                        <label for="images">商品图片：</label>
                        <input class="form-control" type="file" name="images[]" id="images" multiple>
                    </div>
                    <button type="submit" class="btn btn-primary">上传</button>
                </form>
            </div>
        </div>
    </div>
@stop

