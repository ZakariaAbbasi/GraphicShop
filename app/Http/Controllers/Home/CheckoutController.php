<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;

class CheckoutController extends Controller
{
    public function show()
    {
        $product = is_null(Cookie::get('basket'))? [] : json_decode(Cookie::get('basket'), true);
        $sumPrice = $this->sum($product);
        return view('frontend.checkout', compact('product', 'sumPrice'));
    }

    private  function sum($product)
    {
        return !is_null($product) ? array_sum(array_column($product, 'price')): 0;
    }
}
