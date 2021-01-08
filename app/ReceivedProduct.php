<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReceivedProduct extends Model
{
    protected $guarded = [];

    public function receipt()
    {
        return $this->belongsTo('App\Receipt');
    }

    public function item()
    {
        return $this->belongsTo('App\Item');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
