<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Like;
use App\Cart;


use Gate;

use Illuminate\Support\Facades\Auth;

class ProductsController extends Controller
{
    public function index()
    {
        $products = Product::orderby('created_at', 'desc')->paginate(10);

        if (Gate::allows('admin-only')) {
            return view('admin.index', compact('products'));
        } else {
            return view('products.index', compact('products'));
        }
    }

    public function delete($id)
    {
        // 商品を探す
        $product = Product::findOrFail($id);

        // 画像を取得
        $exists = Storage::disk('local')->exists("public/product_images/".$product->image);

        //画像があれば削除
        if ($exists) {
            Storage::delete('public/product_images/'.$product->image);
        }
        
        // データベースから商品を削除
        $product->delete();

        return redirect()->back();
    }

    public function show($id)
    {
        $product = Product::FindOrFail($id);

        return view('products.show', compact('product'));
    }

    public function search(Request $request)
    {
        $searchText = $request->get('search');
        $products = Product::where('name', "Like", $searchText."%")->paginate(3);
        if (Gate::allows('admin-only')) {
            return view('admin.index', compact('products'));
        } else {
            return view('products.index', compact('products'));
        }
    }

    public function addLike($id)
    {
        $user_id = Auth::id();
        
        $like_exist = Like::where('user_id', $user_id)->where('product_id', $id)->first();

        // レコードが空なら
        if (!$like_exist) {
            Like::create([
            'user_id'=> $user_id,
            'product_id'=>$id,
        ]);
        } else {
            // レコードが存在するなら
            $like_exist->delete();
        }
        return redirect()->back();
    }

    public function likeProducts()
    {
        $user = Auth::user();
        $like_products = $user->likes()->with('user', 'product')->get();

        return view('products.likes', compact('like_products'));
    }

    // カート用の処理

    public function cart()
    {
        $user_id = Auth::id();

        $carts =  Cart::where('user_id', $user_id)->get();

        $total = [
            'price'=>0,
            'quantity' =>0,
        ];

        foreach ($carts as $cart) {
            $total['price'] += $cart->product->price * $cart->quanity;
            $total['quantity'] += $cart->quanity;
        }

        return view('products.cart', ['cartItems'=> $carts,'total'=>$total]);
    }

    public function addToCart($id)
    {
        $user_id = Auth::id();
        $product = Product::findOrFail($id);

        // カートに既にその商品があれば取得
        $exist_product = Cart::where('user_id', $user_id)->where('product_id', $id)->first();

        if ($exist_product) {
            // カートに既に商品があれば数を1つ増やす
            $exist_product->update([
                'quanity' =>$exist_product->quanity + 1,
            ]);

        //dd($exist_product);
        } else {
            // カートに商品がなければ新しく代入
            // dd($exist_product);
            Cart::create([
                'user_id'=>$user_id,
                'product_id'=>$id,
                'quanity'=>1,
            ]);
        }

        return redirect()->back()->with('success', "カートに{$product->name}を追加しました。");
    }

    public function deleteItemFromCart($id)
    {
        $user_id = Auth::id();
        $cart = Cart::where('product_id', $id)->where('user_id', $user_id)->first();

        $cart->delete();

        return redirect()->back();
    }

    public function increaseSingleProduct($id)
    {
        $cart =Cart::where('user_id', Auth::id())->where('product_id', $id)->first();

        $cart->update([
                'quanity' =>$cart->quanity + 1,
            ]);
        //dump($cart);

        return redirect()->back();
    }


    public function decreaseSingleProduct($id)
    {
        $cart =Cart::where('user_id', Auth::id())->where('product_id', $id)->first();


        if ($cart->quanity > 1) {
            $cart->update([
                'quanity' =>$cart->quanity - 1,
            ]);
        }

        return redirect()->back();
    }
}
