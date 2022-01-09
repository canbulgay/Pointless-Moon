<?php

use Illuminate\Support\Facades\Route;
use Shopify\Clients\Rest;
use Google\Cloud\Translate\V2\TranslateClient;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('shopifytest', function (Rest $client) {

    $response = $client->get('products');
    return $response->getDecodedBody();
});

Route::get('translatetest', function (TranslateClient $translate) {
    
    $result = $translate->translate(
        'Convert Daily Grasshoper'
    );

    return $result['text'];
});

/*
! Boş bir php projesi açın ve composerla kurulumu yapın.
? Örnekleri incele
*/


Route::get('spreadsheettest', function () {

    $reader = IOFactory::createReaderForFile(Storage::path(''));
    $reader->setReadDataOnly(true);
    $spreadsheet = $reader->load(Storage::path(''));

    $text = "";
    for ($i=6; $i < 104 ; $i++) { 
        $text .= $spreadsheet->getActiveSheet()->getCell('E' . $i). "<br>";
    }
    
    return $text;
});