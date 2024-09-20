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
use Illuminate\Database\Eloquent\Builder;
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

    /**
     * 商品新增页面
     *
     * @return Factory|View|Application
     */
    public function create(): Factory|View|Application
    {
        $brands=Brand::select('id', 'name')->get();
        $categories=Category::select('id','name')->get();

        return view('products.create',compact('brands','categories'));
    }

    /**
     * 商品新增
     *
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


    /**
     * 商品详情页展示
     *
     * @param Product $product
     * @return Factory|View|Application
     */
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
        $query = Product::with(['category', 'brand']);

        // 搜索
        // 当搜索条件存在时，执行搜索逻辑
        $query->when($request->filled('search'), function (Builder $query) use ($request) {
            // 获取搜索关键词
            $searchTerm = $request->input('search');
            // 构建搜索查询
            $query->where(function ($q) use ($searchTerm) {
                // 在商品名称中搜索关键词
                $q->where('name', 'like', "%{$searchTerm}%");
                // 注释掉的代码：也可以在商品描述中搜索关键词
                //->orWhere('description', 'like', "%{$searchTerm}%");
            });
        });

        // 分类筛选
        $query->when($request->filled('category'), function (Builder $query) use ($request) {
            $query->where('category_id', $request->input('category'));
        });

        // 品牌筛选
        $query->when($request->filled('brand'), function (Builder $query) use ($request) {
            $query->where('brand_id', $request->input('brand'));
        });

        // 时间范围筛选
        $query->when($request->filled(['start_date', 'end_date']), function (Builder $query) use ($request) {
            $query->whereBetween('created_at', [
                $request->input('start_date') . ' 00:00:00',
                $request->input('end_date') . ' 23:59:59'
            ]);
        });

        // 价格范围筛选
        $query->when($request->filled(['min_price', 'max_price']), function (Builder $query) use ($request) {
            $query->whereBetween('price', [
                $request->input('min_price'),
                $request->input('max_price')
            ]);
        });

        // 排序
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);
        // 分页
        $products = $query->paginate($request->input('per_page', 15));

        // 获取所有分类和品牌，用于筛选选项
        $categories = Category::all();
        $brands = Brand::all();

        // 获取价格范围
        $priceRange = Product::selectRaw('MIN(price) as min_price, MAX(price) as max_price')->first();

        return view('products.index', compact('products', 'categories', 'brands', 'priceRange'));

    }

    /**
     * 商品更新页面
     *
     * @param Product $product
     * @return Factory|View|Application
     */

    public function edit(Product $product): Factory|View|Application
    {
        // 获取所有品牌
        $brands = Brand::all();

        // 获取所有分类
        $categories = Category::all();
         return view('products.edit',compact('product','brands','categories'));
    }

    /**
     * 商品更新
     *
     * @param Request $request
     * @param Product $product
     * @return void
     */
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
     * 商品删除
     *
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

    /**
     * 商品收藏
     *
     * @param Product $product
     * @return RedirectResponse
     */
    public function favorite(Product $product): RedirectResponse
    {

        if ( ! Auth::user()->isFavorite($product->id)) {
            Auth::user()->favorite($product->id);
        }
        session()->flash('success','收藏成功');

        return redirect()->route('products.index', $product->id);
    }

    /**
     * 商品取消收藏
     *
     * @param Product $product
     * @return RedirectResponse
     */
    public function unfavorite(product $product)
    {

        if (Auth::user()->isFavorite($product->id)) {
            Auth::user()->unFavorite($product->id);
        }

        session()->flash('success', '已经取消收藏');
        return redirect()->route('products.index', $product->id);
    }

    /**
     * 商品购买
     *
     * @param Product $product
     * @return RedirectResponse
     */
    public function purchased(product $product)
    {
        Auth::user()->purchased($product->id);
        DB::table('stocks')->decrement('quantity');
        session()->flash('success', '购买成功');
        return redirect()->route('products.index', $product->id);
    }



}
