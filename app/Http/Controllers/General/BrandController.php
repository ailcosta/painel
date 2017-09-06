<?php

namespace App\Http\Controllers\General;

use Illuminate\Http\Request;
use App\MdlGeneral\Brand;
use App\Http\Controllers\Controller;

class BrandController extends Controller
{
    public function store (Request $request) {
    	$data = $request->all();
    	
    	$this->validate(request(),[
    		'name'      => 'required|max:120',
    		'reference' => 'required|max:120'
    		]);
    	$brand = new Brand();
    	$brand->name = $data['name'];
    	$brand->reference = $data['reference'];
    	$brand->save();
        return redirect()->back();    
    }
}
