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
    protected $baseUri;
    protected $clientId;
    protected $clientSecret;
    protected $credentials;

    public function __construct()
    {
        $this->baseUri = config('services.paypal.base_uri');
        $this->clientId = config('services.paypal.client_id');
        $this->clientSecret = config('services.paypal.client_secret');
        $this->credentials = base64_encode("{$this->clientId}:{$this->clientSecret}");
    }

    public function sample_pay(Request $request)
    {
        $headers['Authorization'] = $this->credentials;

        $client = new Client([
            'base_uri' => $this->baseUri,
        ]);

        $response = $client->request(
            'POST',
            '/v2/checkout/orders',
            [
                "headers" => [
                    "Content-Type" => "application/json",
                    'Authorization' =>"Basic {$this->credentials}"
                ],
                // GuzzleHttpでは「'json' => ['foo3' => 'bar3']」の形式でjsonを送信できる
                'json'=> [
                    'intent' => 'CAPTURE',
                    'purchase_units' =>[ //購入ユニットの配列（必須）
                        0 => [
                            'amount' => [
                                'currency_code' =>'JPY', //通過コード
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
        $client = new Client([
            'base_uri' => $this->baseUri,
        ]);

        $products = Product::orderby('created_at', 'desc')->paginate(10);


        if (session()->has('paypal_approvalId')) {
            $approvalId = session()->get('paypal_approvalId');
            $response = $client->request(
                'POST',
                "/v2/checkout/orders/{$approvalId}/capture",
                [
                "headers" => [
                    "Content-Type" => "application/json",
                    'Authorization' =>"Basic {$this->credentials}"
                ],
            ]
            );

            // PayPal APIから返ってきたcontentsを取得
            $response = $response->getBody()->getContents();
            // json型に変換
            $response = json_decode($response);

            // アドレスを取得
            $address = $response->purchase_units[0]->shipping->address;
            // 郵便番号
            $post_code = $address->postal_code;
            // アドレスの並び替え
            $address = $address->admin_area_1.' '.$address->admin_area_2.' '.$address->address_line_1;

            // paypaleの名前を取得
            $last_name = $response->payer->name->given_name;
            $first_name = $response->payer->name->surname;
            //
            $amount = $response->purchase_units[0]->payments->captures[0]->amount->value;

            // paypal_payment_id
            $paypal_payment_id =  $response->purchase_units[0]->payments->captures[0]->id;

            // paypal_payer_id
            $paypal_payer_id = $response->payer->payer_id;

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


            $cart_products = Cart::where('user_id', Auth::id())->get();
            foreach ($cart_products as $product) {
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


            return view('products.index', compact('products'));
        }

        return view('products.index', compact('products'));
    }



    public function cancelled()
    {
        $products = Product::orderby('created_at', 'desc')->paginate(10);

        return view('products.index', compact('products'));
    }
}
