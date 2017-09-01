<?php

namespace App\Http\Controllers\Marketplaces;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Marketplaces;


class RicardoEletroController extends Controller
{
	private $marketplace;

	public function __construct(){
		$this->marketplace = Marketplaces::where('name', 'RICARDO ELETRO')->get();
		$this->curl = new \anlutro\cURL\cURL;
		$this->auth();
		//$token = Auth::first();
	}

	public function auth(){
		$url = $this->marketplace.'oauth/token?grant_type=refresh_token&client_id='.$this->client_id.'&client_secret='.$this->client_secret.'&refresh_token='.$auth->refresh_token;

var_dump($url);
         $response = $this->curl->newRequest('post', $url)
                    ->setOption(CURLOPT_SSL_VERIFYPEER, 0)
                    ->setOption(CURLOPT_SSL_VERIFYHOST, 0)
                        ->send();

    }
	public function teste(){
dd($this->marketplace);
		return 'valeu!';
	}
}
