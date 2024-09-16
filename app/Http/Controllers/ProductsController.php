<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProductsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(): Factory|View|Application
    {
        $brands=Brand::select('id', 'name')->get();
        $categories=Category::select('id','name')->get();

//        var_dump($brands);
        return view('products.create',compact('brands','categories'));
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // 验证数据
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'brand' => 'required|exists:brands,id',
            'category' => 'required|exists:categories,id',
        ]);

        // 创建新商品
        Product::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'price' => $validatedData['price'],
            'brand_id' => $validatedData['brand'],
            'category_id' => $validatedData['category'],
        ]);

        session()->flash('success', '发布成功！');
        return redirect()->back();
        // 重定向到商品列表页面或者商品详情页面
//        return redirect()->route('products.index')->with('success', '商品创建成功！');
    }


    public function show(Product $product): Factory|View|Application
    {
        $category=$product->category()->value('name');
        $brand=$product->brand()->value('name');
        return view('products.show',compact('product','category','brand'));
    }

    /**
     * 商品展示功能
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
//        $products=Product::all();
//        return view('products.index',compact('products'));
        //预加载
        $query = Product::with(['brand', 'category']);

        // 搜索
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // 分类筛选
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        // 品牌筛选
        if ($request->has('brand')) {
            $query->where('brand_id', $request->brand);
        }

        // 排序
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $query->orderBy($sort, $direction);

        $products = $query->paginate(12)->appends($request->all());
        $categories = Category::all();
        $brands = Brand::all();

        return view('products.index', compact('products', 'categories', 'brands'));
    }


    public function edit(Product $product): Factory|View|Application
    {
        // 获取所有品牌
        $brands = Brand::all();

        // 获取所有分类
        $categories = Category::all();
         return view('products.edit',compact('product','brands','categories'));
    }

    public function update(Request $request,Product $product)
    {
        $validatedData=$request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'brand' => 'required|exists:brands,id',
            'category' => 'required|exists:categories,id',
        ]);

        $product->update([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'price' => $validatedData['price'],
            'brand_id' => $validatedData['brand'],
            'category_id' => $validatedData['category'],
        ]);

        session()->flash('success','商品信息更新成功');
        return redirect()->route('products.show', $product);


    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Product $product): RedirectResponse
    {
        //与policy一起使用
        $this->authorize('destroy', $product);
        $product->delete();
        session()->flash('success', '产品已被成功删除！');
        return redirect()->back();
    }

    public function favorite(Product $product): RedirectResponse
    {

        if ( ! Auth::user()->isFavorite($product->id)) {
            Auth::user()->favorite($product->id);
        }
        session()->flash('success','收藏成功');

        return redirect()->route('products.index', $product->id);
    }

    public function unfavorite(product $product)
    {

        if (Auth::user()->isFavorite($product->id)) {
            Auth::user()->unFavorite($product->id);
        }

        session()->flash('success', '已经取消收藏');
        return redirect()->route('products.index', $product->id);
    }

    public function purchased(product $product)
    {
        Auth::user()->purchased($product->id);
        session()->flash('success', '购买成功');
        return redirect()->route('products.index', $product->id);
    }



}
