<?php

include('./config/config.inc.php');
include('./init.php');

$csv_file = file_get_contents('products.csv');
//dump($csv_file);
$data = explode("\n", $csv_file);
$data = array_filter(array_map("trim", $data));
//dump($data);
$root_category = Category::getRootCategory();
//dump($root_category);
$id_lang = (int)Configuration::get('PS_LANG_DEFAULT');

$i = 0;
foreach ($data as $csv) {
    $i++;
    if ($i < 2) {continue;}

    $csv_values = explode(",", $csv);
    //dump($csv_values);
    $name = $csv_values[0];
    $reference = $csv_values[1];
    $ean13 = $csv_values[2];
    $wholesale_price = $csv_values[3];
    $price = $csv_values[4];
    $iva = floatval($csv_values[5]);
    $quantity= (int)$csv_values[6];
    dump($quantity);
    $brand = $csv_values[8];


$product = new Product();

$product->name = [$id_lang => $name];
$product->reference = $reference;
$product->ean13 =$ean13;
$product->link_rewrite = [$lang => 'product'];
$product->price = $price;
$product->wholesale_price = $wholesale_price;
$product->id_tax_rules_group = (int)getIVA($iva);
//dump((int)getIVA($iva));
$product->quantity = $quantity;
$product->miminal_quantity = 0;
$product->id_category = $root_category->id;
$product->id_category_default = $root_category->id;
$product->redirect_type = '404';
$product->show_price = 1;

if (!($brand_id = getBrand($brand))) {
    $brand_id = createBrand($brand);
}

$product->id_manufacturer = $brand_id;

$product->add();
$product->addToCategories([$root_category->id]);

StockAvailable::setQuantity((int)$product->id, 0, $product->quantity, Context::getContext()->shop->id);

$cat = addslashes(utf8_encode(trim($csv_values[7])));
//dump($cat);
$categories = explode(";", $cat);
//dump($categories);
$product->addCategories= addCategories($product, $categories);


}

function getIVA($iva)
{
    if ($iva == 10) {
        return TaxRulesGroup::getIdByName('ES Reduced Rate (10%)');
    } elseif ($iva == 21) { // 21% Iva
        return TaxRulesGroup::getIdByName('ES Standard rate (21%)');
    }

    // No iva
    return 0;

}

function getBrand($brand_name)
{
    $manufacturer = Manufacturer::getIdByName($brand_name);

    if (is_nan($manufacturer) && $manufacturer == false) {
        $manufacturer = null;
    } else {
        $manufacturer = new Manufacturer($manufacturer);
    }

    return $manufacturer->id;
}

function createBrand($brand_name)
{
    $manufacturer = new Manufacturer();
    $manufacturer->name = $brand_name;
    $manufacturer->active = 1;
    $manufacturer->save();

    return $manufacturer->id;
}

function addCategories($product, $categories)
{
    $id_lang = (int) Configuration::get('PS_LANG_DEFAULT');
    $root_category = Category::getRootCategory();

    foreach ($categories as $category) {

        $found_categories = Category::searchByNameAndParentCategoryId($id_lang, $category, $root_category->id);

        $new_category = null;
        if (!$found_categories) {
            $link = Tools::link_rewrite($category);

            $new_category = new Category();
            $new_category->name = [$id_lang => $category];
            $new_category->id_parent = $root_category->id;
            $new_category->is_root_category = false;
            $new_category->active = 1;
            $new_category->link_rewrite = [$id_lang => $link];
            $new_category->add();
            $new_category->save();
        } else {
            $new_category = new Category($found_categories['id_category']);
        }

        if ($new_category) {
            $product->addToCategories([$new_category->id]);
        }
    }
}
