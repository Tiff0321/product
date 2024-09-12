@extends('layouts.default')

@section('title','用户注册')
@section('content')
    <h1>用户注册</h1>
    <form action="{{route('users.store')}}" method="post">
        {{csrf_field()}}
        <table>
            <tr>
                <td>用户名：</td>
                <td><input type="text" name="name" id="name"></td>
            </tr>
            <tr>
                <td>用户邮箱：</td>
                <td><input type="text" name="email" id="email"></td>
            </tr>
            <tr>
                <td>用户密码：</td>
                <td><input type="password" name="password" id="password"></td>
            </tr>
            <tr>
                <td colspan="2"><button type="submit">提交</button></td>
            </tr>
        </table>
    </form>
@stop
