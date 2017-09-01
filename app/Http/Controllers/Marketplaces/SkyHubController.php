<?php

namespace App\Http\Controllers\Marketplaces;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SkyHubController extends Controller
{
    private $apiKey =  'db61fd136fc527fb977e6b3e1487eb8423627fa999433099c90449c644c21e76';
    private $token = '_SHhEsvknp9cZtQSM3fH';

    public function dev(){
        echo 'oi';
    }
}
