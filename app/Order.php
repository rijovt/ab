<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_no','color','size','qty','user_id'
    ];
    public function user() {
        return $this->belongsTo('App\User');
    }
}
