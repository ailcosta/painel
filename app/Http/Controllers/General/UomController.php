<?php

namespace App\Http\Controllers\General;

use Illuminate\Http\Request;
use App\MdlGeneral\Uom;
use App\Http\Controllers\Controller;

class UomController extends Controller
{
    public function store (Request $request) {
    	$data = $request->all();
    	
    	$this->validate(request(),[
    		'class' => 'required|max:15',
    		'code'  => 'required|max:15',
    		'name'  => 'required|max:30',
    		]);

        $code = strtolower($data['code']);
        $code = str_replace(' ','',$code);
        $name = strtolower($data['name']);

    	$uom = new Uom();
    	$uom->class = $data['class'];
    	$uom->code = $code;
    	$uom->name = $name;
    	$uom->save();
        return redirect()->back();    
    }
}
