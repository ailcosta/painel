<?php

namespace App\Http\Controllers\Marketplaces;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Curl;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\PriceController;
use App\Orders\Order;
use App\Shipping;
use App\invoice;
use App\Product\Products;

class CnovaController extends Controller
{
	private $url;
	private $key;
	private $pass;
	private $curl;
    private $orderController;
    private $stocks;
    private $prices;


	public function __construct(){
		$this->url = 'https://api.cnova.com/api/v2/';
		$this->clientid = 'LdPwyPCWanph';
		$this->pass = 'tccimgIULKn6';
		$this->curl = new \anlutro\cURL\cURL;
        $this->orderController = new OrdersController;
        $this->stocks = new StockController;
        $this->prices = new PriceController;
	}
    public function orders($status = 'approved', $bypass= false){
    	$url = $this->url.'orders/status/'.$status.'?_limit=50&_offset=0';
    	$limit = 50;

		$request = $this->curl->newRequest('get', $url)
    					->setHeader('client_id', $this->clientid)
   						->setHeader('access_token', $this->pass);
   		$response = $request->send();
    	$orders = json_decode($response->body);

    	$pages = floor($orders->metadata[0]->value/$limit);
    	for($n=0; $n<= $pages; $n++){
    		if($n != 0){
    			$url = $this->url.'orders/status/'.$status.'?_limit=50&_offset='.(int)$limit*$n;
    			$request = $this->curl->newRequest('get', $url)
    					->setHeader('client_id', $this->clientid)
   						->setHeader('access_token', $this->pass);
		   		$response = $request->send();
		    	$orders = json_decode($response->body);

    		}

    		foreach($orders->orders as $order){
    			if($bypass){
    				$ret[] = $order;
    				continue;
    			}
				$customer['name'] = $order->customer->name;
				$customer['document'] = $order->customer->documentNumber;
				$customer['document2'] = '';
				$customer['type'] = $order->customer->type;
				$customer['email'] = $order->customer->email;
				$customer['phone'] = $order->customer->phones[0]->number;
				$customer['phone2'] = (isset($order->customer->phones[1]->number))?$order->customer->phones[1]->number:'';

				$customer['zip_code'] = $order->shipping->zipCode;
				$customer['address'] = $order->shipping->address;
				$customer['number'] = $order->shipping->number;
				$customer['complement'] = $order->shipping->complement;
				$customer['quarter'] = $order->shipping->quarter;
				$customer['reference'] = $order->shipping->reference;
				$customer['city'] = $order->shipping->city;
				$customer['state'] = $order->shipping->state;

                $ordernew['code'] = $order->id;
				$ordernew['total'] = ($order->totalAmount- $order->freight->chargedAmount);
				$ordernew['freight'] = $order->freight->chargedAmount;
				$ordernew['comission'] = 0;
				$ordernew['origin'] = 'CNOVA';
				$ordernew['max_date'] = $order->purchasedAt;
                $n=0;
				foreach($order->items as $dataItem){
                    unset($item);
					$item[$n]['sku'] = $dataItem->skuSellerId;
					$item[$n]['name'] = $dataItem->name;
					$item[$n]['price'] = $dataItem->salePrice;
					$item[$n]['quantity'] = 1;
					$n++;
				}
                $this->orderController->create($customer, $ordernew, $item);
	    	}
    	}
    	if($bypass){
			return $ret;
		}
    }



    public function fixOrders(){
    	/*1 2 3 4 5 9*/
    	$orders = \DB::table('orders')->whereNotIn('id', function($q){
		    $q->select('orders_id')->from('order_items');
		})->get();

    	foreach($orders as $order){

    		$url = $this->url.'orders/'.$order->code;

			$response = $this->curl->newRequest('get', $url)
	    					->setHeader('client_id', $this->clientid)
	   						->setHeader('access_token', $this->pass)
							->send();
			$data = json_decode($response->body);
			foreach($data->items as $dataItem){
				unset($item);
				$item = new OrderItem();
				$item->sku = $dataItem->skuSellerId;
				$item->name = $dataItem->name;
				$item->price = $dataItem->salePrice;
				$item->quantity = 1;
				$item->orders_id = $order->id;
				$item->save();
			}


			/*if($data->status == 'DLV'){
				$inv = true;
				$order->order_statuses_id = 6;

			}else if($data->status == 'SHP'){
				$inv = true;
				$order->order_statuses_id = 3;
			}else if($data->status == 'PAY'){
				$inv = false;
				$order->order_statuses_id = 1;
			}else{
				dd($data);
			}
			$order->save();
			if($inv){
				$invoice = invoice::where('orders_id',$order->id)->get();
				if(count($invoice) == 0 && isset($data->invoiced)){
					unset($invoice);
					$invoice = new invoice();
					$invoice->orders_id = $order->id;
					$invoice->number = $data->invoiced->number;
					$invoice->serie = $data->invoiced->line;
					$invoice->key = (isset($data->invoiced->key))?$data->invoiced->key:'';
					$invoice->save();
				}
			}*/
    	}

    }



    public function tracking(){
    	$orders = $this->orders('approved', true);
    	foreach($orders as $o){

    		$order = Order::with('invoice','shipping')->where('code',$o->id)
    		->first();
    		if(isset($order->invoice->number) && isset($order->shipping->shipping_code)){
    			unset($data);
    			$url = $this->url.'orders/'.$o->id.'/trackings/sent';

    			foreach($o->items as $i){
    				$data['items'][] = $i->id;
    				//dd($i);
    			}
    			$data['occurredAt'] = date('Y-m-d\TH:i:s');
    			$data['number'] = $order->shipping->shipping_code;
    			$data['sellerDeliveryId'] = $order->id;
    			$data['carrier']['name'] = 'Correios';
    			$data['invoice']['cnpj'] = '12408070000162';
    			$data['invoice']['number'] = $order->invoice->number;
    			$data['invoice']['serie'] = $order->invoice->serie;
    			$data['invoice']['issuedAt'] = date('Y-m-d\TH:i:s');
    			$data['invoice']['accessKey'] = $order->invoice->key;
                //dd($order->invoice);

    			$request = $this->curl->newJsonRequest('post', $url, $data)
    					->setHeader('client_id', $this->clientid)
   						->setHeader('access_token', $this->pass)
    					->setHeader('Content-type', 'application/json;charset=UTF-8')
						->send();
				$request = json_decode($request->body);
				echo '<pre>';
				print_r($request);
				echo '</pre>';

    		}
    	}
    }

    public function finish(){
    	$orders = $this->orders('sent', true);

    	foreach($orders as $o){
    		$order = Order::with('invoice','shipping')->where('code',$o->id)->where('order_statuses_id',6)
    		->first();

    		if(count($order) > 0){
    			unset($data);
    			$url = $this->url.'orders/'.$o->id.'/trackings/delivered';

    			foreach($o->items as $i){
    				$data['items'][] = $i->id;
    				//dd($i);
    			}
    			$data['occurredAt'] = date('Y-m-d\TH:i:s');
    			$data['number'] = $order->shipping->shipping_code;
    			$data['sellerDeliveryId'] = $o->trackings[0]->sellerDeliveryId;
    			$data['carrier']['name'] = 'Correios';
    			$data['invoice']['cnpj'] = '12408070000162';
    			$data['invoice']['number'] = $order->invoice->number;
    			$data['invoice']['serie'] = $order->invoice->serie;
    			$data['invoice']['issuedAt'] = date('Y-m-d\TH:i:s');
    			$data['invoice']['accessKey'] = $order->invoice->key;



    			$request = $this->curl->newJsonRequest('post', $url, $data)
    					->setHeader('client_id', $this->clientid)
   						->setHeader('access_token', $this->pass)
    					->setHeader('Content-type', 'application/json;charset=UTF-8')
						->send();
				$request = json_decode($request->body);

				echo '<pre>';
				print_r($request);
				echo '</pre>';

    		}
    	}
    }
    public function price(){
        echo "\n\n\n ## Preços CNOVA ## \n\n\n";
        $url = $this->url.'loads/sellerItems/prices';
        $products = $this->prices->getUpdated();

         if(!$products){
            return 'Nenhum Produto para Atualizar';
         }
        $n=0;
        $c =0;
        foreach($products as $product){

            $por = number_format($product['b2w_tam_cnova'],2,'.','');
            $de = number_format(($product['b2w_tam_cnova']*1.2),2,'.','');

            $data[$c][$n]['skuSellerId'] = $product['sku'];
            $data[$c][$n]['prices'][0] =  ['default'=> $de,
                                           'offer' => $por
                                          ];
            $n++;
            if($n == 150){
                $n=0;
                $c++;
            }
        }
        $n= 0;
        foreach($data as $d){
            $request = $this->curl->newJsonRequest('put', $url, $d)
                            ->setHeader('Content-type', 'application/json;charset=UTF-8')
                            ->setHeader('client_id', $this->clientid)
                            ->setHeader('access_token', $this->pass);
            $response = $request->send();
            if($response->statusCode == 204){
                echo $n." Produtos Atualizados\n";
            }else{

                echo $n." Produtos Não Atualizados\n";
            }
            $n++;
            sleep(3);
            flush();
            ob_flush();
        }
    }
    public function stock(){
        echo "\n\n\n ## Estoque CNOVA ## \n\n\n";
        $url = $this->url.'loads/sellerItems/stocks';
        $products = $this->stocks->getUpdated();

         if(!$products){
            return 'Nenhum Produto para Atualizar';
         }
        $n=0;
        $c =0;
        foreach($products as $product){
            $data[$c][$n]['skuSellerId'] = $product['sku'];
            $data[$c][$n]['stocks'][0] =  ['quantity'=> $product['total'],
                                           'crossDockingTime' => 5,
                                            'warehouse' => 0];
            $n++;
            if($n == 150){
                $n=0;
                $c++;
            }
        }
        $n= 0;
        foreach($data as $d){
            $request = $this->curl->newJsonRequest('put', $url, $d)
                            ->setHeader('Content-type', 'application/json;charset=UTF-8')
                            ->setHeader('client_id', $this->clientid)
                            ->setHeader('access_token', $this->pass);
            $response = $request->send();
            if($response->statusCode == 204){
                echo $n." Produtos Atualizados\n";
            }else{

                echo $n." Produtos Não Atualizados\n";
            }
            $n++;
            sleep(5);
            flush();
            ob_flush();
        }
    }
    public function stockall(){
        $url = $this->url.'loads/sellerItems/stocks';
        $products = Products::get();
        $n=0;
        $c =0;
        foreach($products as $product){
            $stock = $this->stocks->getStock($product->sku);
            $data[$c][$n]['skuSellerId'] = $product->sku;


            $data[$c][$n]['stocks'][0] =  ['quantity'=> $stock['total'], 'crossDockingTime' => 5,'warehouse' => 0];
            $n++;
            if($n == 190){
                $n=0;
                $c++;
            }
        }
        $n= 0;
        foreach($data as $d){
            $request = $this->curl->newJsonRequest('put', $url, $d)
                            ->setHeader('Content-type', 'application/json;charset=UTF-8')
                            ->setHeader('client_id', $this->clientid)
                            ->setHeader('access_token', $this->pass);
            $response = $request->send();
            if($response->statusCode == 204){
                echo $n.' Produtos Atualizados<Br />';
            }else{
                echo $n.' Produtos Não Atualizados<br />';
            }
            sleep(5);
            flush();
            ob_flush();
        }
    }
}
