<?php

namespace App\MdlProduct;

use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
