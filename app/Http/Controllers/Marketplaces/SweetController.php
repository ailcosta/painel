<?php

namespace App\Http\Controllers\Marketplaces;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Product\Products;
use App\Product\ProductAttribute;
use App\Product\ProductDimension;
use App\Product\ProductExternalStock;
use App\Product\ProductFiscal;
use App\Product\ProductImage;
use App\Product\ProductPrices;
use App\Product\ProductOrigin;
use App\Product\ProductLog;
use App\Product\ProductCategory;
use App\Product\StockLog;
use DB;
class AppController extends Controller
{
    private $users  = array('a@a.com','','','maisaz@maisaz.com.br');
    private $passs = array('d3hd3hd3h','','','maisaz');
    private $url = 'https://api.fullhub.com.br/';
    private $curl;
    private $token;

    public function __construct(){
        $this->curl = new \anlutro\cURL\cURL;
        $this->generateToken(1);

    }
    private function generateToken($id){
        $url = $this->url.'auth?email='.$this->users[$id-1].'&password='.$this->passs[$id-1];

        var_dump($url);

        var_dump('</br>');

        $response = $this->curl->newRequest('get', $url)
                                ->send();

        $response = json_decode($response->body);

        $this->token = $response->token;

        var_dump($this->token);

        var_dump('</br>');
    }
    
    public function dev(){
        $url = $this->url.'/getUpdated?token='.$this->token;
        $response = $this->curl->newRequest('get', $url)
                                ->send();
        $data = json_decode($response->body);
        foreach($data as $p){
            $prod = Products::with('stocks')->where('sku', $p->sku)->first();
            if(count($prod) > 0){
                if($prod->stocks->quantity != $p->qty){
                     $prod->stocks->quantity =$p->qty;
                    if($prod->stocks->save()){
                        unset($log);
                        $log = ['product_id' => $prod->id, 'user_id' => 17, 'qty' => $p->qty, 'origin' => 'Planilha BookPartners'];
                        StockLog::create($log);
                        echo $p->sku." Atualizado\n";
                    }
                }else{
                    echo $p->sku." Já Atualizado\n";
                }
                
            }else{
                echo $p->sku." Não Localizado\n";
            }
                flush();
                ob_flush();
        }
    }

    public function fixImages(){
        $images = ProductImage::where('url','')->get();
        ob_start();
        foreach($images as $i){
            $product = Products::find($i->products_id);
            //if($product->providers_id == '64'){
                $url = $this->url.'/products/'.$product->sku.'?token='.$this->token;
                $response = $this->curl->newRequest('get', $url)
                                        ->send();
                 if($response->statusCode == 200){
                    $item = json_decode($response->body);
                    $i->url = $item->images[0]->external_url;    
                    $i->save();
                    echo $product->sku.' Imagem Corrigida';
                    flush();
                    ob_flush();
                 }
                
            //}
        }

    }
    public function fixRegister(){

        $qr = 'select distinct sku from order_items where sku not in (select sku from products) ';
        $res = DB::select($qr);
dd($res);

        foreach($res as $r){
            $url = $this->url.'/products/'.$r->sku.'?token='.$this->token;

            $response = $this->curl->newRequest('get', $url)
                                ->send();
            if($response->statusCode == 200){
                $item = json_decode($response->body);

                unset($product);
                unset($attrs);
                unset($dimensions);
                unset($stock);
                unset($fiscals);
                unset($image);
                unset($price);

                $product['sku'] = $item->sku;
                $product['cost'] = $item->cost;
                $product['name'] = $item->name;
                $product['description'] = $item->description;
                $product['providers_id'] = 64;
                $product['brand'] = $item->brand;
                $product['lead_time'] = 3;
                $product['status'] = 1;
                $product['nameml'] = substr($item->name,0,60);




                $product = Products::create($product);
                
                foreach($item->specifications as $specifications){
                    $attrs[] = ['name'=> $specifications->name,'value' => $specifications->value];
                }


                foreach($attrs as $attr){
                    $attr['products_id'] = $product->id;
                    ProductAttribute::create($attr);
                }
                $dimensions['weight'] = $item->weight;
                $dimensions['width'] = $item->width;
                $dimensions['height'] = $item->height;
                $dimensions['depth'] = $item->length;
                $dimensions['cube'] = 0;
                $dimensions['products_id'] = $product->id;
                ProductDimension::create($attr);

                $stock['quantity'] = $item->qty;
                $stock['products_id'] = $product->id;
                ProductExternalStock::create($stock);

                $fiscals['sku'] = $item->sku;
                $fiscals['name'] = $item->name;
                $fiscals['ean'] = $item->ean;
                $fiscals['ncm'] = $item->ncm;
                $fiscals['isbn'] = $item->isbn;
                $fiscals['origin'] = 1;
                $fiscals['products_id'] = $product->id;
                ProductFiscal::create($fiscals);

                $images['url'] = $item->images[0]->external_url;
                $images['products_id'] = $product->id;
                ProductImage::create($images);

                $price['marketplaces_id'] = 1;
                $price['price'] = $item->price;
                $price['price2'] = (floor($item->price*1.1)+0.9);
                $price['price3'] = $item->price;
                $price['products_id'] = $product->id;
                ProductPrices::create($price);

                echo $product->sku.' Cadastrado <br />';
            }else{
                echo $r->sku.' Não Localizado<br />';
            }
            
        }

    }

    public function BookRegisterByUserId($id){
        generateToken($id);
        $this->BookRegister();
    }

    public function BookRegister($sku = false){


        var_dump($this->token.'</br>');

        $limit = 1000;
        if($sku){
            $url = $this->url.'/products/'.$sku.'?token='.$this->token;
            $response = $this->curl->newRequest('get', $url)
                                ->send();
            $data = json_decode($response->body);
        }else{
            var_dump(date('Y-m-d H:i:s').' without sku</br>');

            $url = $this->url.'/WithStockProducts?token='.$this->token;

            var_dump($url);

            var_dump('</br>');
            
            $response = $this->curl->newRequest('get', $url)
                                ->send();

            $data = json_decode($response->body);
        }

        $item = $data;

        dd($item);
        $prod = Products::with('stocks')->where('sku', $item->sku)->first();

        if(count($prod) == 0){
            if($item->images[0]->internal_url == ''){
                echo 'Produto Sem Imagem';
            }

            unset($product);
            unset($attrs);
            unset($dimensions);
            unset($stock);
            unset($fiscals);
            unset($image);
            unset($price);

            $product['sku'] = $item->sku;
            $product['cost'] = $item->cost;
            $product['name'] = $item->name;
            $product['description'] = $item->description;
            $product['providers_id'] = 64;
            $product['brand'] = $item->brand;
            $product['lead_time'] = 3;
            $product['status'] = 1;
            $product['nameml'] = substr($item->name,0,60);



            $product = Products::create($product);
            
            foreach($item->specifications as $specifications){
                $attrs[] = ['name'=> $specifications->name,'value' => $specifications->value];
            }


            foreach($attrs as $attr){
                $attr['products_id'] = $product->id;
                ProductAttribute::create($attr);
            }
            $dimensions['weight'] = $item->weight;
            $dimensions['width'] = $item->width;
            $dimensions['height'] = $item->height;
            $dimensions['depth'] = $item->length;
            $dimensions['cube'] = 0;
            $dimensions['products_id'] = $product->id;
            ProductDimension::create($attr);

            $stock['quantity'] = $item->qty;
            $stock['products_id'] = $product->id;
            ProductExternalStock::create($stock);

            $fiscals['sku'] = $item->sku;
            $fiscals['name'] = $item->name;
            $fiscals['ean'] = $item->ean;
            $fiscals['ncm'] = $item->ncm;
            $fiscals['isbn'] = $item->isbn;
            $fiscals['origin'] = 1;
            $fiscals['products_id'] = $product->id;
            ProductFiscal::create($fiscals);

            $images['url'] = $item->images[0]->external_url;
            $images['products_id'] = $product->id;
            ProductImage::create($images);

            $price['marketplaces_id'] = 1;
            $price['price'] = $item->price;
            $price['price2'] = (floor($item->price*1.1)+0.9);
            $price['price3'] = $item->price;
            $price['products_id'] = $product->id;
            ProductPrices::create($price);

            echo $product->sku.' Cadastrado <br />';
        }else{
            echo $prod->sku.' Produto já Cadastrado';
        }
        flush();
        ob_flush();

    }
    public function dev3(){
        $limit = 1000;
        $url = $this->url.'/products?token='.$this->token;
        $response = $this->curl->newRequest('get', $url)
                                ->send();
        $data = json_decode($response->body);

        for($n=1; $n<= $data[0]->last_page; $n++){
            if($n == 1){
                $items = $data[0]->data;
            }else{
                 $url = $this->url.'/products?page='.$n.'&token='.$this->token;
                 $response = $this->curl->newRequest('get', $url)
                                ->send();
                $data = json_decode($response->body);
                //dd($data);
                $items = $data[0]->data;
            }

            foreach($items as $item){
                $prod = Products::with('stocks')->where('sku', $item->sku)->first();

                if(count($prod) > 0){
                    $prod->stocks->quantity =$item->qty;
                    $prod->stocks->save();
                    echo $item->sku.' Atualizado<Br />';
                }else{
                    echo $item->sku.' Não Localizado<Br />';
                }
                flush();
                ob_flush();
            }
        }
    }
}
