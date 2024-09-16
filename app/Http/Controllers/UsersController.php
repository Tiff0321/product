<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class UsersController extends Controller
{
    public function __construct()
    {
        /**
         * 不用登录也能访问 详情页展示，用户注册界面
         */
        $this->middleware('auth',[
            'except'=>['show','create','store','index','confirmEmail']
        ]);
        /**
         * 只让未登录用户访问注册页面
         */
        $this->middleware('guest', [
            'only' => ['create']
        ]);

        // 限流 一个小时内只能提交 10 次请求；
        $this->middleware('throttle:10,60', [
            'only' => ['store']
        ]);

    }

    public function create(): View|Factory|Application
    {
        return view('users.create');
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required|unique:users|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);

        $user= User::create([
                'name'=> $request->name,
                'email'=>$request->email,
                'password'=>bcrypt($request->password)]
        );

        $this->sendEmailConfirmationTo($user);
        session()->flash('success', '验证邮件已发送到你的注册邮箱上，请注意查收。');
        return redirect('/');

// //       $data=$request->all();--获取用户输入的所有数据
//        Auth::login($user);
//        session()->flash('success','用户创建成功');
//        return redirect()->route('users.show',[$user->id]);
    }

    protected function sendEmailConfirmationTo($user)
    {
        $view = 'emails.confirm';
        $data = compact('user');
//        $from = '2325287709@qq.com';
//        $name = 'tiff';
        $to = $user->email;
        $subject = "感谢注册 Product 应用！请确认你的邮箱。";

        Mail::send($view, $data, function ($message) use ($to, $subject) {
            $message->to($to)->subject($subject);
        });
    }

    public function show(User $user): Factory|View|Application
    {
        return view('users.show',compact('user'));

    }

    /**
     * @throws AuthorizationException
     */
    public function edit(User $user): Factory|View|Application
    {
        $this->authorize('update', $user);
        return view('users.edit',compact('user'));
    }

    /**
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function update(User $user, Request $request): RedirectResponse
    {
        $this->authorize('update', $user);
        $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'nullable|confirmed|min:6'
        ]);

//        $user = User::find($request->id);
//        $user->name = $request->name;
//        $user->password = bcrypt($request->password);
//        $user->save();

//        $user->update([
//            'name' => $request->name,
//            'password' => bcrypt($request->password),
//        ]);
        $data = [];
        $data['name'] = $request->name;
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);

        session()->flash('success', '个人资料更新成功！');
        return redirect()->route('users.show',$user->id);
    }

    public function index(): Factory|View|Application
    {
        //$users=User::all();
        $users=User::paginate(6);
        return view('users.index',compact('users'));
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success', '成功删除用户！');
        return back();
    }

//    public function gravatar($size = '100'): string
//    {
//        $hash = md5(strtolower(trim($this->attributes['email'])));
//        return "https://cdn.v2ex.com/gravatar/$hash?s=$size";
//
//    }

    public function confirmEmail($token): RedirectResponse
    {
        $user = User::where('activation_token', $token)->firstOrFail();

        $user->activated = true;
        $user->activation_token = null;
        $user->save();

        Auth::login($user);
        session()->flash('success', '恭喜你，激活成功！');
        return redirect()->route('users.show', [$user]);

}

    public function favorite(User $user): Factory|View|Application
    {
        $products=$user->favoriteProducts()->paginate(5);
        return view('users.show_favorite',compact('products'));

}

    public function purchased(User $user): Factory|View|Application
    {
        $products=$user->purchasedProducts()->paginate(5);
        return view('users.show_purchased',compact('products'));

}

}
