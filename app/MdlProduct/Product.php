<?php

namespace App\MdlProduct;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function supplier() {
        return $this->hasOne('App\Supplier');
    }

    public function ProductVariation() {
        return $this->hasMany('App\ProductVariation');
    }

    public function ProductAttribute() {
        return $this->hasMany('App\ProductAttribute');
    }

    public function ProductImage() {
        return $this->hasMany('App\ProductImage');
    }

    public function ProductDimensions() {
        return $this->hasOne('App\ProductDimensions');
    }

    public function brand() {
        return $this->hasOne('App\Brand');
    }
}


