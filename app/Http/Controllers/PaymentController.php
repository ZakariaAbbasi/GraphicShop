<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Services\Payment\PaymentService;
use App\Http\Requests\Payment\PayRequest;
use App\Models\Payment;
use App\Services\Payment\Requests\IDPayRequest;

use function PHPSTORM_META\map;

class PaymentController extends Controller
{

    public function pay(PayRequest $request)
    {
        // dd($request->all());
        $validateData = $request->validated();
        $user = User::firstOrCreate(
            ['email' => $validateData['email']],
            [
                'name' => $validateData['fullName'],
                'mobi$refCode = le' => $validateData['mobile']
            ]

        );

        try {
            $orderItem = json_decode(Cookie::get('basket'), true);

            $products = Product::findMany(array_keys($orderItem));

            $productPrice = $products->sum('price');
            
            $refCode = $this->intCodeRandom();

            $createOrder = Order::create(
                [
                    'user_id' => $user->id,
                    'amount' => $productPrice,
                    'ref_code' => $refCode,
                    'status' => 'unpaid',

                ]
            );

            $orderItemForCreatedOrder = $products->map(function ($products) {
                $currentProduct = $products->only('price', 'id');

                $currentProduct['product_id'] = $currentProduct['id'];
                unset($currentProduct['id']);
                return $currentProduct;
            });
            $createOrder->orderItems()->createMany($orderItemForCreatedOrder->toArray());
            $payments = Payment::create(
                [
                    'order_id' => $createOrder->id,
                    'geteway' => 'id_pay',
                    'res_id' => $refCode,
                    'ref_id' => $refCode,
                    'status' => 'unpaid',
                ]
            );
            $idPayRequest = new  IDPayRequest(
                [
                    'amount' => $productPrice,
                    'user' => $user,
                    'orderId' => $refCode
                ]
            );
            $paymentService = new PaymentService(PaymentService::ID_PAY, $idPayRequest);
            return $paymentService->pay();
            
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }

    }

    public function callback()
    {
    }

    private function intCodeRandom($length = 10)
    {
        $intMin = (10 ** $length) / 10; // 100...
        $intMax = (10 ** $length) - 1;  // 999...

        $codeRandom = mt_rand($intMin, $intMax);

        return $codeRandom;
    }
}
