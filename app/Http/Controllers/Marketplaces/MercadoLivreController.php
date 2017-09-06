<?php

namespace App\Http\Controllers\Marketplaces;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\MdlProduct\Category;
use App\MdlMarketplace\Marketplace;
use App\MdlMarketplace\Marketplace_session_info;

//use App\Http\Controllers\Marketplaces\Meli\Meli;

class MercadoLivreController extends Controller
{
    private $marketplace_name = 'Mercado Livre';
    private $mrkt;
    private $auth;
    private $orderController;
    private $stocks;
    private $prices;
    private $curl;
    private $redirUrl = 'https://painel.villacoisa.com.br/ml/callback';


    public function __construct(){
        $this->mrkt = Marketplace::where('name', $this->marketplace_name)
                        ->first();
        if ($this->mrkt === null) {
            dd('Marketplace '.$this->marketplace_name.' não cadastrado!');
        }
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_HEADER, false);
        curl_setopt($this->curl, CURLOPT_NOBODY, false);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($this->curl, CURLOPT_COOKIE, "cookiename=0");
        curl_setopt($this->curl, CURLOPT_REFERER, $_SERVER['REQUEST_URI']);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt ($this->curl, CURLOPT_SSL_VERIFYPEER, FALSE);  //IT IS DANGEOURS
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 0);
        $this->checkAuth();

    }

    public function getTestUser(){

        $url = $this->mrkt->url.'users/test_user?access_token='
            .$this->mrkt->access_token;
        $response = $this->curl->newRequest('get', $url)
                                ->send();
            $data = json_decode($response->body);
            $limit = 50;
            $total = floor($data->paging->total/$limit);
            ob_start();
    }

/*
    public function login(){
        var_dump('step 01');
        $meli = new Meli(
                    '6233605376304887', 
                    'OvZqypai8jI39yzOPgjNv8cYZ3WswPgU');
        $redirectUrl = $meli->getAuthUrl($this->redirUrl,Meli::$AUTH_URL['MLB']);

        var_dump($redirectUrl);

    }

    public function getCategories(){
        $nextParent_ids = Array('-1');
        do {
            $parent_ids = $nextParent_ids;
            unset($nextParent_ids);
            $nextParent_ids = Array();
            foreach ($parent_ids as $parent_id) {
                var_dump('Loading $categs ===>'.$parent_id);

                if ($parent_id !== '-1'){
                    $categParentId = Category::select('id')
                                        ->where('id_marketplace',$parent_id)
                                        ->first()['id'];

    var_dump($categParentId);
                }

                $categs = $this->getMlCategories($parent_id);
                if (count($categs) == 0) {
                    break;
                }
                foreach ($categs as $categ) {
                    $cat = new Category;
                    $cat->id_marketplace = $categ['id'];
                    $cat->name           = $categ['name'];
                    $cat->marketplace_id = $this->marketplace_id;
                    $cat->categ_status_code = 'ATIVA';
                    if ($parent_id !== '-1') {
                        $cat->parent_category_id = $categParentId;
                    }
                    $cat->save();
    var_dump($cat);
                    array_push($nextParent_ids,$categ['id']);
                }
            }
        } while (count($nextParent_ids) > 0);
    }

    public function getMlCategories($parent_id){
        if ($parent_id == '-1') {
            $url = $this->url.'sites/MLB/categories';
        } else {
            $url = $this->url.'categories/'.$parent_id;
        }
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_POST, false);
        $response = curl_exec($this->curl);
        if ($parent_id == '-1') {
            return json_decode($response, true);
        } else {
            return json_decode($response, true)['children_categories'];
        }
    }
*/

  
// BEGIN AUTHENTICATION AREA

    public function checkAuth() {
        $this->auth = Marketplace_session_info::where('marketplace_id', $this->mrkt->id)
              ->first();

        if ($this->auth === null) {
            var_dump('Marketplace '.$this->marketplace_name.' token não cadastrado!');
        } else {
            if(round(abs(strtotime($this->auth->updated_at) - time()) / 60) >= 300){

                $url = $this->url
                        .'oauth/token?grant_type=refresh_token&client_id='
                        .$this->mrkt->auth_key
                        .'&client_secret='.$this->mrkt->auth_pass
                        .'&refresh_token='.$this->auth->refresh_token;

                curl_setopt($this->curl, CURLOPT_URL, $url);
                curl_setopt($this->curl, CURLOPT_POST, true);
                $response = curl_exec($this->curl);                    

                $data = json_decode($response->body);
                $this->auth->access_token = $data->access_token;
                $this->auth->refresh_token = $data->refresh_token;
                $this->auth->save();            
            }            
        }
    }

    public function loginML(){
        return view('admin.loginML');
    }

    public function callback(Request $request){
        dd($request);
    }

    public function redirect(Request $request){
        $code =$request->input('code');
        $url = 'https://api.mercadolibre.com/oauth/token?grant_type=authorization_code'
            .'&client_id='.$this->mrkt->auth_key
            .'&client_secret='.$this->mrkt->auth_pass
            .'&code='.$code
            .'&redirect_uri=https://painel.villacoisa.com.br/ml/redirect';

        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_POST, true);
        $response = curl_exec($this->curl);

        $this->auth =  Marketplace_session_info::where('marketplace_id', 
                                        $this->mrkt->id)
                    ->first();
        if ($this->auth === null) {
            $this->auth = new Marketplace_session_info;
            $this->auth->marketplace_id = $this->mrkt->id;
        }

        $response = json_decode($response);
        //dd($response);
        $this->auth->access_token = $response->access_token;
        $this->auth->refresh_token = $response->refresh_token;
        if($this->auth->save()){
            return \Redirect::back()->with(['msg', 'Acesso concluido!']);
        } else {
            var_dump('Failed to generate token');
            dd($response);
        }

    }

}