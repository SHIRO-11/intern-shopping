<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

use App\Cart;
use App\Order;
use App\OrderProduct;
use App\Buyer;
use App\Product;

class PaymentController extends Controller
{
    public function sample_pay(Request $request)
    {
        // dump($request->value);


        $baseUri = config('services.paypal.base_uri');
        $clientId = config('services.paypal.client_id');
        $clientSecret = config('services.paypal.client_secret');

        // base64にエンコード
        $credentials = base64_encode("{$clientId}:{$clientSecret}");
        $headers['Authorization'] = $credentials;

        $client = new Client([
            'base_uri' => $baseUri,
        ]);

        $response = $client->request(
            'POST',
            '/v2/checkout/orders',
            [
                "headers" => [
                    "Content-Type" => "application/json",
                    'Authorization' =>"Basic {$credentials}"
                ],
                // GuzzleHttpでは「'json' => ['foo3' => 'bar3']」の形式でjsonを送信できる
                'json'=> [
                    'intent' => 'CAPTURE',
                    'purchase_units' =>[ //購入ユニットの配列（必須）
                        0 => [
                            'amount' => [
                                'currency_code' =>'JPY', //通過コードを大文字にする
                                'value' => round($request->value),
                            ]
                        ]
                    ],
                    'application_context' => [
                        'brand_name' => config('app.name'), //PayPalサイトのPayPalアカウントの会社名を上書きするラベル。
                        'shipping_preference' => 'GET_FROM_FILE',//配送設定：NO_SHIPPING。PayPalサイトから配送先住所を編集します。デジタルグッズにおすすめ。
                        //設定を続行または今すぐ支払うが流れてチェックアウト。PAY_NOW。顧客をPayPal支払いページにリダイレクトすると、[今すぐ支払う]ボタンが表示されます。このオプションは、チェックアウトの開始時に最終金額がわかっていて、顧客が[今すぐ支払う]をクリックしたときにすぐに支払いを処理する場合に使用します。
                        'user_action' => 'PAY_NOW',
                        'return_url' => route('paypal_approval'),//顧客が支払いを承認した後に顧客がリダイレクトされるURL。
                        'cancel_url' => route('paypal_cancelled'),//顧客が支払いをキャンセルした後に顧客がリダイレクトされるURL。
                    ]
                ],
            ]
        );

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
        $baseUri = config('services.paypal.base_uri');
        $clientId = config('services.paypal.client_id');
        $clientSecret = config('services.paypal.client_secret');

        $credentials = base64_encode("{$clientId}:{$clientSecret}");



        $client = new Client([
            'base_uri' => $baseUri,
        ]);


        if (session()->has('paypal_approvalId')) {
            $approvalId = session()->get('paypal_approvalId');
            $payment = $client->request(
                'POST',
                "/v2/checkout/orders/{$approvalId}/capture",
                [
                "headers" => [
                    "Content-Type" => "application/json",
                    'Authorization' =>"Basic {$credentials}"
                ],
            ]
            );

            // PayPal APIから返ってきたcontentsを取得
            $payment = $payment->getBody()->getContents();

            // json型に変換
            $payment = json_decode($payment);

            // アドレスを取得
            $address = $payment->purchase_units[0]->shipping->address;
            // 郵便番号
            $post_code = $address->postal_code;
            // アドレスの並び替え
            $address = $address->admin_area_1.' '.$address->admin_area_2.' '.$address->address_line_1;

            // paypaleの名前を取得
            $last_name = $payment->payer->name->given_name;
            $first_name = $payment->payer->name->surname;
            //
            $amount = $payment->purchase_units[0]->payments->captures[0]->amount->value;

            // paypal_payment_id
            $paypal_payment_id =  $payment->purchase_units[0]->payments->captures[0]->id;

            // dump($approvalId);
            // dd($paypal_payment_id);

            // paypal_payer_id
            $paypal_payer_id = $payment->payer->payer_id;

            $order = Order::create([
                'user_id'=>Auth::id(),
                'price'=>$amount,
                'post_code'=>$post_code,
                'address'=>$address,
                'first_name'=>$first_name,
                'last_name'=>$last_name,
                'status'=>'hold',
            ]);

            Buyer::create([
                'order_id'=>$order->id,
                'paypal_payment_id'=>$paypal_payment_id,
                'paypal_payer_id'=>$paypal_payer_id
            ]);

            $products = Cart::where('user_id', Auth::id())->get();

            foreach ($products as $product) {
                OrderProduct::create([
                    'user_id' =>Auth::id(),
                    'product_id'=>$product->product->id,
                    'order_id'=>$order->id,
                    'product_name'=>$product->product->name,
                    'product_price'=>$product->product->price,
                    'quantity'=>$product->quanity,
                ]);

                $product->delete();
            }

            $products = Product::orderby('created_at', 'desc')->paginate(10);


            return view('products.index', compact('products'));
        }

        $products = Product::orderby('created_at', 'desc')->paginate(10);

        return view('products.index', compact('products'));
    }

    public function cancelled()
    {
        $products = Product::orderby('created_at', 'desc')->paginate(10);

        return view('products.index', compact('products'));
    }
}
