<?php

namespace App\Http\Controllers\General;

use Illuminate\Http\Request;
use App\MdlGeneral\Supplier;
use App\Http\Controllers\Controller;

class SupplierController extends Controller
{
    public function store (Request $request) {
    	$data = $request->all();
    	
    	$this->validate(request(),[
    		'name' => 'required|min:5|max:120',
    		'cnpj' => 'required|min:18|max:18',
    		'alias' => 'required|max:5'
    		]);
        $alias = strtolower($data['alias']);
        $alias = str_replace(' ','_',$alias);

    	$supplier = new Supplier();
    	$supplier->name = $data['name'];
    	$supplier->cnpj = $data['cnpj'];
    	$supplier->alias = $alias;
    	$supplier->save();
        return redirect()->back();    
    }
}
