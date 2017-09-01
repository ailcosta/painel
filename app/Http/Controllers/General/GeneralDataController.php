<?php

namespace App\Http\Controllers\General;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GeneralDataController extends Controller
{
    public function dataEntry() {
        return view('dataEntry/generalData');
    }
}


