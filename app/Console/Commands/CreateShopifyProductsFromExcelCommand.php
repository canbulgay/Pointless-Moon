<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Jobs\CreateProductsOnShopifyJob;
use App\DTO\Product;
use App\Helpers\TranslateHelper as TranslateClient;

class CreateShopifyProductsFromExcelCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shopify:fromexcel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Shopify Products From Excel File';

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
    public function handle(TranslateClient $translater)
    {
        /*
        ! Get Excell File
        */
        $spreadsheet = IOFactory::load(Storage::path('demo.xls'));
        $productsData = Product::createCollectionFromExcel($spreadsheet);
        
            foreach ($productsData as $productData) {

                $productToCreate = [
                    "title" =>
                    $translater->translate($productData->type) . " " .
                        $productData->collection . ", " .
                        $translater->translate(
                            $productData->color
                        ) . ", "
                        . $productData->sizeName,
    
                    "body_html" => "<strong>" . $translater->translate(
                        $productData->type . " " . $productData->category
                    ) . "!</strong>",
    
                    "vendor" => $productData->brand,
    
                    "product_type" => $translater->translate(
                        $productData->type
                    ),
    
                    "variants" => [
                        [
                            "sku" => $productData->code,
                            "price" => $productData->price,
                        ]
                    ],
    
                    "images" => $productData->images,
                ];

            $this->line($productToCreate["title"] . " creating with " . count($productToCreate["images"]) . " images...");

            $dispatchedJob = CreateProductsOnShopifyJob::dispatch(($productToCreate));

            $this->info("Create product job has been dispatched.");
            $this->line(" ");
            
            }

        $this->info("Finished");

        }
    }

