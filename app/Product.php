<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Like;

class Product extends Model
{
    protected $fillable = ['name','price','description','image'];

    public function like()
    {
        return $this->hasMany(Like::class);
    }

    public function like_exist($user_id, $product_id)
    {
        $like_exist = Like::where('user_id', $user_id)->where('product_id', $product_id)->first();

        if (!$like_exist) {
            return false;
        } else {
            // レコードが存在するなら
            return true;
        }
    }
}
