<?php

namespace App\Http\Controllers\Home;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $products= Product::all();
        return view('frontend.products.all', compact('products'));
    }
}
