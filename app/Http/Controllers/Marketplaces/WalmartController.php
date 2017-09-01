<?php

namespace App\Http\Controllers\Marketplaces;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\walmart;
use App\Order;
use App\OrderItem;
use App\Customer;
use App\Http\Controllers\SigepController;
use App\Http\Controllers\StockController;
use App\Product\Products;

use App\Http\Controllers\PriceController;

class WalmartController extends Controller
{

	private $sigep;
	private $stocks;
    private $url = 'https://api-mp.walmart.com.br';
    private $user = 'svc-adapter-tamarind';
    private $pass = 'b9@40N@I0e';
    private $sellerId = '3316';
    private $curl;
    private $prices;

	public function __construct(){

        $this->curl = new \anlutro\cURL\cURL;
		$this->sigep = new SigepController;
		$this->stocks = new StockController;
        $this->prices = new PriceController;
	}
    public function fulfillmentPreview(Request $request){
    	$data = json_decode($request->getContent());
    	
    	$n=0;
    	foreach($data->items as $item){
    		$shipping[$n]['itemId'] = $item->id;
    		$prazo = $this->sigep->calc($data->postalCode, $item->id, $item->quantity);
    		$stock = $this->stocks->getStock($item->id);
    		if($prazo){
    			$its[$n]['id'] = $item->id;
	    		$its[$n]['quantity'] = $item->quantity;
	    		$its[$n]['price'] = (double)number_format($prazo['price']*1.3,2,'.','');
	    		$its[$n]['listPrice'] = (double)number_format($prazo['price']*1.8,2,'.','');

	    		// $shipment[$n]['itemId'] = $item->id;
	    		$shipping[$n]['inventoryAvailable'] = $stock['total'];
				$shipping[$n]['quantity'] = $item->quantity;

				$shipping[$n]['categories'][0]['id'] = "Padrao";
				$shipping[$n]['categories'][0]['name'] = $prazo['Serviço'];
				$shipping[$n]['categories'][0]['shippingEstimate'] = $prazo['prazoEntrega'].'bd';
				$shipping[$n]['categories'][0]['price'] = $prazo['valor'];
				$shipping[$n]['categories'][0]['scheduledDeliveries'] = [];
    		}

    		$ret['items'] = $its;
    		$ret['country'] = 'BRA';
			$ret['postalCode'] = $data->postalCode;
			$ret['shipmentInfos'] = $shipping;
			\Log::info(json_encode($ret));

			return $ret;
    	}
    }

    public function price(){
        echo "\n\n\n ## Preços Walmart ## \n\n\n";
        $products = $this->prices->getUpdated();
        if(!$products){
            return 'Nenhum Produto para Atualizar';
         }
        foreach($products as $product){

            $por = number_format($product['mobly_wal'],2,'.','');
            $de = number_format(($product['mobly_wal']*1.2),2,'.','');

            $url = $this->url.'/ws/seller/'.$this->sellerId.'/catalog/offers/external/'.$product['sku'].'/price/'.$por.'/listPrice/'.$de;

            $request = $this->curl->newRequest('put', $url)
                            ->setHeader('Content-type', 'application/json;charset=UTF-8')
                            ->setUser($this->user)
                            ->setPass($this->pass);
            $response = $request->send();
            if($response->statusCode == 200){
                echo $product['sku']." Atualizado\n";
            }else{
                echo $product['sku']." Não Localizado\n";
            }
            sleep(1);
            flush();
            ob_flush();
        }
    }
    public function stock(){
        echo "\n\n\n ## Estoque Walmart ## \n\n\n";
        $products = $this->stocks->getUpdated();
        if(!$products){
            return 'Nenhum Produto para Atualizar';
         }
        foreach($products as $product){
            $url = $this->url.'/ws/seller/'.$this->sellerId.'/catalog/offers/external/'.$product['sku'].'/quantity/'.$product['total'];
            $request = $this->curl->newRequest('put', $url)
                            ->setHeader('Content-type', 'application/json;charset=UTF-8')
                            ->setUser($this->user)
                            ->setPass($this->pass);
            $response = $request->send();
            if($response->statusCode == 200){
                echo $product['sku']." Atualizado\n";
            }else{
                echo $product['sku']." Não Localizado\n";
            }
            sleep(1);
            flush();
            ob_flush();
        }
    }
    public function stockAll(){
        $url = $this->url.'/batch/sku/stock';
        $products = Products::get();
        foreach($products as $product){
            $stock = $this->stock->getStock($product->sku);
            $url = $this->url.'/ws/seller/'.$this->sellerId.'/catalog/offers/external/'.$product->sku.'/quantity/'.$stock['total'];
            $request = $this->curl->newRequest('put', $url)
                            ->setHeader('Content-type', 'application/json;charset=UTF-8')
                            ->setUser($this->user)
                            ->setPass($this->pass);
            $response = $request->send();
            if($response->statusCode == 200){
                echo $product->sku." Atualizado\n";
            }else{
                echo $product->sku." Não Localizado\n";
            }
            flush();
            ob_flush();
        }

    }
}
