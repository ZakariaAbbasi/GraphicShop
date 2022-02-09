<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;

class CheckoutController extends Controller
{
    public function show()
    {
        $product = json_decode(Cookie::get('basket'), true);
        $sumPrice = $this->sum($product);
        return view('frontend.checkout', compact('product', 'sumPrice'));
    }

    private  function sum($product)
    {
        if ($product != null)
            return array_sum(array_column($product, 'price'));
        return 0;
    }
}
