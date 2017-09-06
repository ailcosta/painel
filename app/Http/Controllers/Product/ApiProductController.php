<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\MdlProduct\ApiProduct;
use App\MdlProduct\ApiAttribute;
use App\MdlGeneral\Supplier;
use App\MdlProduct\Product;
use App\MdlProduct\ProductAttribute;
use App\MdlProduct\ProductImage;
use App\MdlProduct\ProductDimension;
use App\Http\Controllers\Controller;

class ApiProductController extends Controller
{
    private $imgUrl = 'https://s3-sa-east-1.amazonaws.com/villacoisa-img/';
    public function descrUpdated($prod){
    	//
    }
    public function priceUpdated($prod, $oldPrice){
    	//
    }
    public function stockUpdated($prod){
    	//
    }
    public function imageUpdated($prod){
        //
    }

    public function loadProductsFromApi($supplier_id = 1){
    	$supplier = Supplier::where('id', $supplier_id)
    				->first();
		if ((!isset($supplier->policy)) ||
			$supplier->policy === null ||
			$supplier->policy == 0
			) {
			dd('Supplier policy not defined!!!');
		}
    	$Api = new ApiProduct;
    	$Api->setConnection('mysqlApi');
    	$prodsApi = $Api
    				->where('supplier_id', $supplier_id)
    				->where('enabled', 1)
    				->get();
    	foreach ($prodsApi as $prodApi) {
            var_dump($prodApi->sku);
    		unset($prod);
    		$prod = Product::where('sku', $prodApi->sku)
    					->first();
    		
    		if ($prod === null) {
    			$prod = new Product;
				$prod->supplier_id    = $prodApi->supplier_id;
				$prod->sku            = $prodApi->sku;
				$prod->sku_supplier   = substr($prodApi->sku,strlen($supplier->alias)+2);
				$prod->name           = $prodApi->name;
				$prod->title          = $prodApi->name;
				$prod->bigTitle       = $prodApi->name;
				$prod->description    = $prodApi->description;
				$prod->bigDescription = $prodApi->description;
				$prod->external_qty   = $prodApi->qty;
				$prod->multiplicity   = $prodApi->multiplicity;
				$prod->lowest_price   = $prodApi->unit_price*$supplier->policy;
				$prod->lead_time      = $supplier->lead_time;
				$prod->prod_type_code = 'NORMAL';
				$prod->status_code    = 'INATIVO';
				$prod->uom_code       = 'peças';
				$prod->save();
    		} else {
    			if ($prod->description_updatable) {
    				if ($prod->name !== $prodApi->name) {
						$prod->name           = $prodApi->name;
						$prod->title          = $prodApi->name;
						$prod->bigTitle       = $prodApi->name;
    					$this->descrUpdated($prod);
    				}
    				if ($prod->description !== $prodApi->description) {
						$prod->description     = $prodApi->description;
						$prod->bigDescription  = $prodApi->description;
    					$this->descrUpdated($prod);
    				}
    			}
    			if ($prod->external_qty !== $prodApi->qty) {
						$prod->external_qty   = $prodApi->qty;
    					$this->stockUpdated($prod);
				}
    			if ($prod->lowest_price !== $prodApi->unit_price*$supplier->policy) {
    					$oldPrice = $prod->lowest_price;
						$prod->lowest_price   = $prodApi->unit_price*$supplier->policy;
    					$this->priceUpdated($prod, $oldPrice);
				}
				
				$prod->multiplicity   = $prodApi->multiplicity;
				$prod->lead_time      = $supplier->lead_time;
				$prod->save();    			
    		}

            $apiAttrib = new ApiAttribute;
            $apiAttrib->setConnection('mysqlApi');
            $attribsApi = $apiAttrib
                        ->where('product_id', $prodApi->id)
                        ->get();
            unset($productImages);
            foreach ($attribsApi as $attribApi) {
                if ($attribApi->name === 'image') {
                    if (!isset($productImages)){
                        $productImages = ProductImage::where('product_id', $prod->id)
                            ->get();
                        $maxOrder = ProductImage::where('product_id', $prod->id)
                                            ->max('order');
                        if ($maxOrder === null) {
                            $maxOrder = 1;
                        }
                    }
                    $found = 0;
                    foreach ($productImages as $productImage) {
            var_dump('==='.($productImage->reference === $attribApi->value));
            var_dump('=='.($productImage->reference == $attribApi->value));

                        if ($productImage->reference === $attribApi->value) {
                            $found = 1;
                            break;
                        }
                    }
                    if ($found === 0) {
                        $url = $this->imgUrl.$supplier->alias.'/'.$prod->sku.$maxOrder.'.jpg';
                        $prodImg = new ProductImage;
                        $prodImg->product_id    = $prod->id;
                        $prodImg->order         = $maxOrder;
                        $prodImg->url           = $url;
                        $prodImg->reference     = $attribApi->value;
                        $prodImg->name          = $prod->sku.'-'.$maxOrder;
                        $prodImg->save();
                        ++$maxOrder;
                    }

                } elseif (
                    $attribApi->name === 'diameter' ||
                    $attribApi->name === 'width' ||
                    $attribApi->name === 'height' ||
                    $attribApi->name === 'length'
                    ) {
                        $productDimension = ProductDimension::where('product_id', $prod->id)
                            ->first();
                        if ($productDimension === null) {
                            $productDimension = new ProductDimension;
                            $productDimension->product_id = $prod->id;
                        }
                        switch ($attribApi->name) {
                            case "diameter":
                                $productDimension->diameter = str_replace(',','.',$attribApi->value);
                                $productDimension->diameter_uom = $attribApi->complement;
                                break;
                            case "width":
                                $productDimension->width = str_replace(',','.',$attribApi->value);
                                $productDimension->width_uom = $attribApi->complement;
                                break;
                            case "height":
                                $productDimension->height = str_replace(',','.',$attribApi->value);
                                $productDimension->height_uom = $attribApi->complement;
                                break;
                            case "length":
                                $productDimension->length = str_replace(',','.',$attribApi->value);
                                $productDimension->length_uom = $attribApi->complement;
                                break;
                        }
                        $productDimension->save();
                }
            }
            
    	}
    }
}
