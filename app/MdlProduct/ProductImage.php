<?php

namespace App\MdlProduct;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
