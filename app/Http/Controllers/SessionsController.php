<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SessionsController extends Controller
{
    public function __construct()
    {
        /**
         * 只让未登录用户访问登录页面：
         */
        $this->middleware('guest',[
            'only'=>['create']
        ]);
    }
    public function create(): Factory|View|Application
    {
        return view('sessions.create');
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required'
        ]);



//        if (Auth::attempt(['email' => $email, 'password' => $password])) {
//            // 该用户存在于数据库，且邮箱和密码相符合
//        }
        if (Auth::attempt($credentials,$request->has('remember'))) {
            if(Auth::user()->activated){
                // 登录成功后的相关操作
                session()->flash('success', '欢迎回来！');
//            return redirect()->route('users.show', [Auth::user()]);
                $fallback = route('users.show', Auth::user());
                return redirect()->intended($fallback);
            } else {
                Auth::logout();
                session()->flash('warning', '你的账号未激活，请检查邮箱中的注册邮件进行激活。');
                return redirect('/');
            }
        } else {
            // 登录失败后的相关操作
            session()->flash('danger', '很抱歉，您的邮箱和密码不匹配');
            return redirect()->back()->withInput();
        }

//        return;
//        $request->email
//        bcyst($request->password)

    }

    public function destroy(): Redirector|Application|RedirectResponse
    {
        Auth::logout();
        session()->flash('success', '您已成功退出！');
        return redirect('login');

    }
}
