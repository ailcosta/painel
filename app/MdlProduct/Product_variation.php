<?php

namespace App\MdlProduct;

use Illuminate\Database\Eloquent\Model;

class Product_variation extends Model
{
    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
