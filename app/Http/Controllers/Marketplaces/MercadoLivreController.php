<?php

namespace App\Http\Controllers\Marketplaces;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;


use App\MdlProduct\Category;
use App\MdlMarketplace\Marketplace;

use App\Http\Controllers\Marketplaces\Meli\Meli;

class MercadoLivreController extends Controller
{
    private $marketplace_id;
    private $sellerId = '14210693';
    private $client_id;
    private $client_secret;
    private $url;
    private $token;
    private $orderController;
    private $stocks;
    private $prices;

    private $curl;
    private $redirUrl = 'http://painel/ml/loginML';

    public function __construct(){
        $mrkt = Marketplace::where('name', 'Mercado Livre')
                        ->first();
//var_dump($mrkt->id);
        $this->marketplace_id = $mrkt->id;
        $this->client_id = $mrkt->auth_key;
        $this->client_secret = $mrkt->auth_pass;
        $this->url = $mrkt->url;
        $this->curl = curl_init();


        curl_setopt($this->curl, CURLOPT_HEADER, false);
        curl_setopt($this->curl, CURLOPT_NOBODY, false);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($this->curl, CURLOPT_COOKIE, "cookiename=0");
        curl_setopt($this->curl, CURLOPT_REFERER, $_SERVER['REQUEST_URI']);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 0);
//IT IS DANGEOURS
//IT IS DANGEOURS
//IT IS DANGEOURS
        curl_setopt ($this->curl, CURLOPT_SSL_VERIFYPEER, FALSE); 
//IT IS DANGEOURS
//IT IS DANGEOURS
//IT IS DANGEOURS

    }

    public function loginML(){
        return view('admin.loginML');
    }

    public function login(){
        var_dump('step 01');
        $meli = new Meli(
                    '6233605376304887', 
                    'OvZqypai8jI39yzOPgjNv8cYZ3WswPgU');
        $redirectUrl = $meli->getAuthUrl($this->redirUrl,Meli::$AUTH_URL['MLB']);

        var_dump($redirectUrl);

    }

    public function getCategories(){
        $parent_ids = Array('-1');

        foreach ($parent_ids as $parent_id) {
            var_dump('Loading $categs ===>');
            $categs = $this->getMlCategories($parent_id);
            if (count($categs) == 0) {
                break;
            }
            foreach ($categs as $categ) {
var_dump($categ);

var_dump($categs);
                $cat = new Category;
                $cat->id_marketplace = $categ['id'];
                $cat->name           = $categ['name'];
                $cat->marketplace_id = $this->marketplace_id;
                $cat->categ_status_code = 'ATIVA';
                if ($parent_id !== '-1') {
                    $cat->parent_category_id = $parent_id;
                }
                $cat->save();
                array_push($parent_ids,$categ['id']);
            }
        }

    }

    public function getMlCategories($parent_id){
var_dump($parent_id);
        if ($parent_id == '-1') {
            $url = $this->url.'sites/MLB/categories';
        } else {
            $url = $this->url.'categories/'.$parent_id;
        }
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_POST, false);
        $response = curl_exec($this->curl);
        return json_decode($response, true);
    }


}