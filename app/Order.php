<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable =['user_id','price','post_code','address','first_name','last_name','status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function buyer()
    {
        return $this->hasOne(Buyer::class);
    }

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }
}
