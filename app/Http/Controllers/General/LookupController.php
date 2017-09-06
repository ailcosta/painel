<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LookupController extends Controller
{
    //
}

namespace App\Http\Controllers\General;

use Illuminate\Http\Request;
use App\MdlGeneral\Lookup;
use App\Http\Controllers\Controller;

class LookupController extends Controller
{
    public function store (Request $request) {
    	$data = $request->all();
    	
    	$this->validate(request(),[
    		'type' => 'required',
    		'code' => 'required',
    		'meaning' => 'required|min:5|max:100',
    		]);
        $code = strtoupper($data['code']);
        $code = str_replace(' ','_',$code);

    	$lookup = new Lookup();
    	$lookup->meaning = $data['meaning'];
    	$lookup->type = $data['type'];
    	$lookup->code = $code;
    	$lookup->save();
        return redirect()->back();    
    }
}
