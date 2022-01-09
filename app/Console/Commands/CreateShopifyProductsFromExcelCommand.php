<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Shopify\Clients\Rest as ShopifyAPI;

class CreateShopifyProductsFromExcelCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shopify:fromexcell';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(ShopifyAPI $shopify)
    {
            /*
    ! Get Excell File
    ! 
    */
    $spreadsheet = IOFactory::load(Storage::path('demo.xls'));
    $worksheet = $spreadsheet->getActiveSheet();
    $highesRow = $worksheet->getHighestRow();

    $this->line("Excel has ". $highesRow ." lines it means it has ". ((int)$highesRow - 3). " products.");

    $imageUrlColumns = ["AZ","BA","BB","BC","BD","BE","BF","BG","BH","BJ"];

    for ($i=6; $i <= $highesRow ; $i++) { 
        $productToCreate = [
            
            "title" => 
            $worksheet->getCell('F'.  $i)->getValue()." ". 
            $worksheet->getCell('I' . $i)->getValue(). " ".
            $worksheet->getCell('J' . $i)->getValue(). " - ".
            $worksheet->getCell('E' . $i)->getValue(),
            
            "body_html" => "<strong>Good" . $worksheet->getCell('I' . $i)->getValue() . " " .
            $worksheet->getCell('J' . $i)->getValue() . "!</strong>" , 
            "vendor" => $worksheet->getCell('F'.  $i)->getValue() ,
            "product_type" => $worksheet->getCell('I' . $i)->getValue() ,
            "variants" => [
                "sku" => $worksheet->getCell('E' . $i)->getValue(),
                "price" => $worksheet->getCell('Q' . $i)->getValue() ,
            ],
            
            "images" => []
        ];
        foreach ($imageUrlColumns as $column) {
            
            if($worksheet->getCell($column . $i)->getValue() != ""){
                $productToCreate["images"][] = ["src" => $worksheet->getCell('AZ' . $i)->getValue()];
            }
        }

        $this->line($productToCreate["title"] . " creating with ". count($productToCreate["images"]) . " images...");

        $response = $shopify->post(
            "products",
            [
                "product"
            ]
            );
        
        $this->info("Create process resulted with " . $response->getStatusCode() . " code.");
        $this->line("...");

        $this->info("Finished.");

    }
    }
}
