<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Product;


use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index()
    {
        $products = Product::all();

        return view('admin.index', compact('products'));
    }

    public function create()
    {
        return view('admin.create');
    }

    

    //商品を作成する
    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'name'=>'required|max:255',
            'image'=>'file|mimes:jpeg,png,jpg,bmb|max:5000',
            'price'=>'required|max:255',
            'description'=>'required|max:3000',
        ]);
        
        // // 拡張子の取得
        // $extension =  $request->file("image")->getClientOriginalExtension();
        // //スペースをなくす
        // $stringImageReFormat = str_replace(" ", "", $request->input('name'));
        // // スペースを無くした画像名にする（画像名に空白があると上手く処理できないことがある）
        // $imageName = $stringImageReFormat.".".$extension; //blackdress.jpg

        // // ファイル名を取得
        // $imageEncoded = File::get($request->image);
        // Storage::disk('local')->put('public/product_images/'.$imageName, $imageEncoded);

        $imageName = base64_encode(file_get_contents($request->image->getRealPath()));

        $created = Product::create([
            'name'=>$request->name,
            'image'=>$imageName,
            'price'=>$request->price,
            'description'=>$request->description,
        ]);


        if ($created) {
            return redirect()->back()->with('success', "{$request->name}を登録しました。");
        } else {
            return "Product was not Created";
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


    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.edit', compact('product'));
    }

    public function editImage($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.editImage', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'=>'required|max:255',
            'price'=>'required|max:255',
            'description'=>'required|max:3000',
        ]);


        $product = Product::findOrFail($id);
        $product->name = $request->name;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->save();

        return redirect()->back()->with('success', "商品情報を更新しました。");
    }

    public function updateImage(Request $request, $id)
    {
        $request->validate([
            'image'=>'file|mimes:jpeg,png,jpg,bmb|max:5000',
        ]);

        if ($request->hasFile("image")) {
            $product = Product::find($id);
            $exists = Storage::disk('local')->exists("public/product_images/".$product->image);

            //delete old image
            if ($exists) {
                Storage::delete('public/product_images/'.$product->image);
            }
            $path = $request->file('image')->store('public/product_images');
            $product->image = basename($path);
            $product->save();

            return redirect()->back()->with('success', "画像を更新しました。");
        } else {
            $error = "NO Image was Selected";
            return $error;
        }
    }
}
