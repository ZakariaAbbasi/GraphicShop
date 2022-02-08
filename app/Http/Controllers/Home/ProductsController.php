<?php

namespace App\Http\Controllers\Home;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ProductsController extends Controller
{

    public function index()
    {
        $products = Product::all();
        return $this->viewData($products);
    }

    public function single($id)
    {
        $product = Product::findOrFail($id);
        $similarProducts = Product::where('category_id', $product->category_id)->where('id', '<>', $product->id)->take(4)->get();
        return view('frontend.products.single', compact('product', 'similarProducts'));
    }

    public function search(Request $request)
    {
        $validateData = $request->validate(['search' => 'required']);
        $products = Product::query()
            ->where('title', 'LIKE', "%{$validateData['search']}%")->get();
        return $this->viewData($products);

    }

    # جدیدترین ها 
    public function newest()
    {
         return $this->filter('created_at', 'desc');
    }
    # قیمت:‌ کم به زیاد
    public function lowestPrice()
    {
        return $this->filter('price', 'asc');
    }
    # قیمت:‌زیاد به کم
    public function highestPrice()
    {
        return $this->filter('price', 'desc');

    }
     
    public function tenToHundred()
    {
        return $this->lowestPriceToHighestPrice(10000, 100000);
    }

    private  function filter(string $column, $action)
    {
        $products = Product::orderBy($column, $action) ->get();
        return $this->viewData($products);

    }
    // The lowest price to the highest price
    private  function  lowestPriceToHighestPrice(int $min, int $max)
    {
        $products = Product::whereBetween('price', [$min, $max])->get();
        return $this->viewData($products);

    }
 
    private  function viewData($products)
    {
        $categories = Category::all();
        return view('frontend.products.all', compact('products', 'categories'));
    }

}
