<?php

namespace App\Http\Controllers\Home;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductsController extends Controller
{

    public function index(Request $request)
    {
        $products = Product::all();
     
        $categories = Category::all();
        return view('frontend.products.all', compact('products', 'categories'));
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
        $categories = Category::all();
        return view('frontend.products.all', compact('products', 'categories'));
    }

    public function filter(Request $request)
    {
        if (!isset($request->filter, $request->action))
            return;
        $products = $this->findFilter($request?->filter, $request?->action);
        $categories = Category::all();
        return view('frontend.products.all', compact('products', 'categories'));
    }

    private  function findFilter(string $className, string $methodName)
    {
        $baseNamespace = 'App\\Http\\Controllers\\Filters\\';

        $className = $baseNamespace . (ucfirst($className) . 'Filter');

        if (!class_exists($className)) {
            return null;
        }

        $obj = new $className;

        if (!method_exists($obj, $methodName)) {
            return null;
        }
        return $obj->{$methodName}();
    }
}
