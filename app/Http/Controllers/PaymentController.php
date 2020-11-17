<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

use App\Services\PayPalService;


use App\Cart;
use App\Order;
use App\OrderProduct;
use App\Buyer;
use App\Product;

class PaymentController extends Controller
{
    protected $paypalService;

    public function __construct(PayPalService $paypal_service)
    {
        $this->paypalService = $paypal_service;
    }

    public function sample_pay(Request $request)
    {
        $response = $this->paypalService->request_order($request);

        // PayPal APIから返ってきたcontentsを取得
        $response = $response->getBody()->getContents();
        // json型に変換
        $response = json_decode($response);

        // linksだけを取得
        $orderLinks = collect($response->links);

        // approve（ユーザーの遷移先を取得）
        $approve = $orderLinks->where('rel', 'approve')->first();

        session()->put('paypal_approvalId', $response->id);
        
        return redirect($approve->href);
    }


    public function paypal_approval()
    {
        $products = Product::orderby('created_at', 'desc')->paginate(10);


        if (session()->has('paypal_approvalId')) {
            $this->paypalService->paypal_approval($products);
        }

        return view('products.index', compact('products'));
    }



    public function cancelled()
    {
        $products = Product::orderby('created_at', 'desc')->paginate(10);

        return view('products.index', compact('products'));
    }
}
