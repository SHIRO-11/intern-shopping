<?php
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
