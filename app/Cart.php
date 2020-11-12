<?php
/**
 * Created by IntelliJ IDEA.
 * User: mostafa
 * Date: 5/15/2018
 * Time: 1:11 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id','product_id','quanity'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
