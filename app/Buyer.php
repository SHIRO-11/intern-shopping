<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Buyer extends Model
{
    protected $fillable = ['order_id','paypal_payment_id','paypal_payer_id'];

    public function orders()
    {
        return $this->belongsTo(Order::class);
    }
}
