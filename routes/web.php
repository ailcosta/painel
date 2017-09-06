<?php
Route::group(['middleware' => 'web'], function () {
    Route::auth();
    Route::get('/'      , 'HomeController@index');
    Route::get('/home'  , 'HomeController@index');
});
Route::group(['prefix' => 'data'    , 'middleware'=>'web'], function() {
    Route::get('/products'          , 'Product\ProductController@dataEntry');
    Route::get('/loadProducts'      , 'Product\ApiProductController@loadProductsFromApi');
    Route::get('/general'           , 'General\GeneralDataController@dataEntry');
    Route::post('/general/supplier' , 'General\SupplierController@store');
    Route::post('/general/lookup'   , 'General\LookupController@store');
    Route::post('/general/brand'    , 'General\BrandController@store');
    Route::post('/general/uom'      , 'General\UomController@store');
});
Route::group(['prefix' => 'ml'  , 'middleware'=>'web'], function() {
    Route::get('/callback'      , 'Marketplaces\MercadoLivreController@callback');
    Route::get('/redirect'      , 'Marketplaces\MercadoLivreController@redirect');
    Route::get('/login'         , 'Marketplaces\MercadoLivreController@login');
    Route::get('/loginML'       , 'Marketplaces\MercadoLivreController@loginML');
    Route::get('/getCategories' , 'Marketplaces\MercadoLivreController@getCategories');
});

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'auth'], function () {
    //    Route::get('/link1', function ()    {
//        // Uses Auth Middleware
//    });

    //Please do not remove this if you want adminlte:route and adminlte:link commands to works correctly.
    #adminlte_routes
});
*/
