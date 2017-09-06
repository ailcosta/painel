<?php

namespace App\Http\Controllers\Marketplaces;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Marketplaces;


class MagaLuController extends Controller
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

    public function calculateFreight(Request $request){
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
	    		$its[$n]['listPrice'] = number_format($prazo['price']*1.8,2,'.','');

	    		// $shipment[$n]['itemId'] = $item->id;
	    		$shipping[$n]['inventoryAvailable'] = $stock['total'];
				$shipping[$n]['quantity'] = $item->quantity;

				$shipping[$n]['categories'][0]['id'] = "Padrao";
				$shipping[$n]['categories'][0]['name'] = $prazo['Serviço'];
				$shipping[$n]['categories'][0]['shippingEstimate'] = $prazo['prazoEntrega'].'bd';
				$shipping[$n]['categories'][0]['price'] = $prazo['price'];
				$shipping[$n]['categories'][0]['scheduledDeliveries'] = [];
    		}

    		$ret['items'] = $its;
    		$ret['country'] = 'BRA';
			$ret['postalCode'] = $data->postalCode;
			$ret['shipmentInfos'] = $shipping;

			\Log::info(json_encode($ret));
			return $ret;
    	}

    	dd($data);
    }





}
