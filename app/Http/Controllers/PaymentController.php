<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use function PHPSTORM_META\map;
use Illuminate\Support\Facades\Cookie;
use App\Services\Payment\PaymentService;
use App\Http\Requests\Payment\PayRequest;
use App\Mail\OrderShipped;
use App\Services\Payment\Requests\IDPayRequest;
use App\Services\Payment\Requests\IDPayVerifyRequest;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{

    public function pay(PayRequest $request)
    {

        $user = $this->setUserInPay($request);

        try {
            $orderItem = json_decode(Cookie::get('basket'), true);

            if (count($orderItem) <= 0) {
                throw new \InvalidArgumentException('سبد خرید شما خالی  است');
            }

            $products = Product::findMany(array_keys($orderItem));

            $productPrice = $products->sum('price');

            $refCode = $this->intCodeRandom();

            $createOrder = $this->setOrderInPay($user->id, $productPrice, $refCode);

            $this->setOrderItemInPay($products, $createOrder);

            $this->setPaymentInPay($createOrder->id,  $refCode);

            $idPayRequest = new  IDPayRequest(
                [
                    'amount' => $productPrice,
                    'user' => $user,
                    'orderId' => $refCode,
                    'apiKey' => config('services.geteways.id_pay.api_key')
                ]
            );
            $paymentService = new PaymentService(PaymentService::ID_PAY, $idPayRequest);
            return $paymentService->pay();
        } catch (\Throwable $th) {
            return back()->with('faild', $th->getMessage());
        }
    }

    public function callback(Request $request)
    {
        $paymentInfo = $request->all();
        $idPayVerifyRequest = new IDPayVerifyRequest(
            [
                'id' => $paymentInfo['id'],
                'orderId' => $paymentInfo['order_id'],
                'apiKey' => config('services.geteways.id_pay.api_key')
            ]
        );
        $paymentService = new PaymentService(PaymentService::ID_PAY, $idPayVerifyRequest);
        $result = $paymentService->verify();
        // dd($result);
        if (!$result) {
            return $this->callbackFailed();
        }

        if ($result['statusCode'] == 101) {
            return redirect()->route('home.checkout')->with('faild', ' پرداخت قبلا تایید شده است و تصاویر برای شما ایمیل شده است');
        }
        if ($result['statusCode'] == 53) {
            return redirect()->route('home.checkout')->with('faild', ' تایید پرداخت امکان پذیر نیست.');
        }
        $currentPayment = Payment::where('ref_code', $result['data']['order_id'])->first();

        $currentPayment->update(
            [
                'res_id' => $result['data']['track_id'],
                'status' => 'paid'
            ]
        );
        $currentPayment->order()->update(['status' => 'paid']);
        $currentUser = $currentPayment->order->user;
        $reservedImages = $currentPayment->order->orderItems->map(function ($orderItems) {
            return $orderItems->product->source_url;
        });

        Mail::to($currentUser)->send(new OrderShipped($reservedImages->toArray(), $currentUser));
        Cookie::queue('basket', null);
        return $this->callbackSuccess();
    }

    public function callbackSuccess()
    {
        return view('frontend.callback-success',);
    }
    public function callbackFailed()
    {
        return view('frontend.callback-failed',);
    }

    private function intCodeRandom($length = 10)
    {
        $intMin = (10 ** $length) / 10; // 100...
        $intMax = (10 ** $length) - 1;  // 999...

        $codeRandom = mt_rand($intMin, $intMax);

        return $codeRandom;
    }

    private function setUserInPay($request)
    {
        $validateData = $request->validated();
        $user = User::firstOrCreate(
            ['email' => $validateData['email']],
            [
                'name' => $validateData['fullName'],
                'mobile' => $validateData['mobile']
            ]
        );
        return $user;
    }

    private function setOrderInPay($userId, $productPrice, $refCode)
    {
        return Order::create(
            [
                'user_id' => $userId,
                'amount' => $productPrice,
                'ref_code' => $refCode,
                'status' => 'unpaid',

            ]
        );
    }

    private function setPaymentInPay($createOrderId,  $refCode)
    {
        return Payment::create(
            [
                'order_id' => $createOrderId,
                'geteway' => 'id_pay',
                'ref_code' => $refCode,
                'status' => 'unpaid',
            ]
        );
    }

    private function setOrderItemInPay($products, $createOrder)
    {
        $orderItemForCreatedOrder = $products->map(function ($products) {
            $currentProduct = $products->only('price', 'id');

            $currentProduct['product_id'] = $currentProduct['id'];
            unset($currentProduct['id']);
            return $currentProduct;
        });
        return  $createOrder->orderItems()->createMany($orderItemForCreatedOrder->toArray());
    }
}
