<?php

namespace App\MdlProduct;

use Illuminate\Database\Eloquent\Model;

class ProductDimension extends Model
{
    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
