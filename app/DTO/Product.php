<?php

namespace App\DTO;

use PhpOffice\PhpSpreadsheet\Spreadsheet;

/**
 * Code of (SKU) of product
 * @var 
 */

class Product
{
    public $code;
    public $brand;
    public $fullName;
    public $linkBrand;
    public $type;
    public $category;
    public $collection;
    public $barcode;
    public $madeIn;
    public $purchaseCountry;
    public $price;
    public $reservedStock;
    public $width;
    public $height;
    public $length;
    public $sizeName;
    public $color;
    public $images = [];
    
    /**
     * @param Spreadsheet $spreadsheet
     * @return array<Product>
     */

    public static function createCollectionFromExcel(Spreadsheet $spreadsheet)
    {
        /*
        ! excellden okuma işlemi
        ! her bir satır için new self;
        ! ilgili objenin içini Excel satırından okuduklarınla doldur.
        ! tümünü bir collection içine koy
        ! return
        */

        $worksheet = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow();

        $imageUrlColumns = ["AZ", "BA", "BB", "BC", "BD", "BE", "BF", "BG", "BH", "BJ"];

        $productsData = [];

        for ($i = 4; $i <= $highestRow; $i++) {

                $product = new static;

                $product->code = $worksheet->getCell("E" . $i)->getValue();
                $product->brand = $worksheet->getCell("F" . $i)->getValue();
                $product->type = $worksheet->getCell("I" . $i)->getValue();
                $product->width = $worksheet->getCell("Y" . $i)->getValue();
                $product->height = $worksheet->getCell("Z" . $i)->getValue();
                $product->length = $worksheet->getCell("X" . $i)->getValue();
                $product->color = explode("\n", $worksheet->getCell('AL' . $i)->getValue())[0];
                $product->collection = $worksheet->getCell("K" . $i)->getValue();
                $product->category = $worksheet->getCell("J" . $i)->getValue();
                $product->price = $worksheet->getCell("Q" . $i)->getValue();
                $product->images = [];

            foreach ($imageUrlColumns as $column) {
                if ($worksheet->getCell($column . $i)->getValue() != "") {
                    $product->images[] = ["src" => $worksheet->getCell($column . $i)->getValue()];
                }
            }
                        
            $sizeParts = [];
            foreach (["width", "height", "length"] as $column) {
                if ($product->$column != "") {
                    $sizeParts[] = $product->$column;
                }
            }
            $sizeBaseName = implode('x', $sizeParts);
            $product->sizeName = "";
            if ($sizeBaseName != "") $product->sizeName = $sizeBaseName . " cm";

            $productsData[] = $product;
        }
        return $productsData;
    }
}

