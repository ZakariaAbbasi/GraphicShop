<?php

namespace App\Http\Controllers\Home;

use App\Models\Product;
use App\Utilities\DiePages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;

class BasketController extends Controller
{
    private $minutes = 60;

    public function addToBasket($id)
    {
        $Product = Product::findOrFail($id);
        $basket = json_decode(Cookie::get('basket'), true);
        // $basket = ['sum'=> $this->sum($basket)];

        if (!$basket) {
            $basket = [
                $Product->id => [
                    'title' => $Product->title,
                    'price' => $Product->price,
                    'demo_url' => $Product->demo_url,
                   
                ]
            ];
        return $this->cookieBasket($basket, 'محصول به سبدخرید اضافه شد');
        }

        if (isset($basket[$Product->id]))
            
            return DiePages::messages('success', 'محصول قبلا به سبدخرید اضافه کردید');


        $basket[$Product->id] = [
            'title' => $Product->title,
            'price' => $Product->price,
            'demo_url' => $Product->demo_url,
            

        ];
        return $this->cookieBasket($basket, 'محصول به سبدخرید اضافه شد');
    }

    public function removeFromBasket($id)
    {
        $basket = json_decode(Cookie::get('basket'), true);

        if (isset($basket[$id]))
            unset($basket[$id]);

        return $this->cookieBasket($basket, 'محصول از سبدخرید حذف شد');

    }

    private  function cookieBasket($basket, $data)
    {
        $basket = json_encode($basket);
        Cookie::queue('basket', $basket, $this->minutes);
        return DiePages::messages('success', $data);
    }

    // private  function sum($basket)
    // {
    //     if ($basket != null)
    //         return array_sum(array_column($basket, 'price'));
    //     return 0;
    // }

}
