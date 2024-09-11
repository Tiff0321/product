<?php

namespace App\Http\Controllers;

use Illuminate\Console\View\Components\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class StaticPagesController extends Controller
{
    //
    /**
     * 静态页面 首页 关于 帮助
     *
     * @return Factory|View|Application
     */
    public function home(): Factory|View|Application
    {
        return view('static_pages.home');
    }
    public function about(): Factory|View|Application
    {
        return view('static_pages.about');
    }
    public function help(): Factory|View|Application
    {
        return view('static_pages.help');
    }
}
