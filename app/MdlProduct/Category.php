<?php

namespace App\MdlProduct;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
	protected $connection = 'mysqlPainel';
	protected $table = 'categories';
}
