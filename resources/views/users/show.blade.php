@extends('layouts.default')

@section('title',$user->name)
@section('content')
    <h1>用户详情</h1>
    <div class="row">
        <div class="offset-md-2 col-md-8">
            <div class="col-md-12">
                <div class="offset-md-2 col-md-8">
                    <section class="user_info">
                        @include('shared._user_info', ['user' => $user])
                        @include('shared._status', ['user' => $user])
                    </section>
                </div>
            </div>
        </div>
    </div>
        <table>
            <tr>
                <td>用户名：</td>
                <td>{{$user->name}}</td>
            </tr>
            <tr>
                <td>用户邮箱：</td>
                <td>{{$user->email}}</td>
            </tr>
            <tr>
                <td>用户密码：</td>
                <td>{{$user->password}}</td>
            </tr>
        </table>
@stop
