<?php

    include('./config/config.inc.php');
    include('./init.php');

    $csv_file = file_get_contents('products.csv');
    //dump($csv_file);
    $data = explode("\n", $csv_file);
    $data = array_filter(array_map("trim", $data));
    dump($data);
    $default_language = Configuration::get('PS_LANG_DEFAULT');

    $i = 0;
    foreach ($data as $csv) {
            $i++;
            if ($i < 2) {continue;}

            $csv_values = explode(",", $csv);
            //dump($csv_values);
            $name = $csv_values[0];
            $reference = $csv_values[1];
            $ean13 = $csv_values[2];
            $price = $csv_values[3];
            $wholesale_price = $csv_values[4];
            $ecotax = $csv_values[5];
            $quantity = $csv_values[6];
            $category = $csv_values[7];
            $manufacturer = $csv_values[8];

            $product = new Product();

        $product->name = [$default_language => $name];
        $product->reference = $reference;
        $product->ean13 =$ean13;
        $product->price = $price;
        $product->wholesale_price = $wholesale_price;
        $product->ecotax = $ecotax;
        $product->quantity = $quantity;
        $product->category = $category;

        $product->manufacturer_name = $manufacturer;

        $product->add();


        }
