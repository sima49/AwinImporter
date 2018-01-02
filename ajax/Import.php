<?php

//set the memory limit//
ini_set('memory_limit', '512M');
//raise the timeout//
ini_set('max_execution_time', '180');
//uncomment to display errors//
ini_set('display_errors', 1);

//create some folders//
if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/awinProductImport/tmp/zip/')) {
    mkdir($_SERVER['DOCUMENT_ROOT'] . '/awinProductImport/tmp/zip/', 0777, true);
}


//include config file as accessable variables//
$config = include($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');

//include database//
include $_SERVER['DOCUMENT_ROOT'] . '/DB/DB.php';

//create a zip file//
$zipFile = $_SERVER['DOCUMENT_ROOT'] . "tmp/" . $_GET['c'] . ".zip";

//url of feed//
$url = 'http://datafeed.api.productserve.com/datafeed/download/apikey/' . $config->api . '/language/en/cid/' . $_GET['i'] . "/columns/aw_deep_link,product_name,aw_product_id,merchant_product_id,merchant_image_url,description,merchant_category,search_price,brand_name,promotional_text,aw_image_url,category_name/format/csv/delimiter/%2C/compression/zip/";

//add zip resource//
$zipResource = fopen($zipFile, "w");

//use curl to get the feed and download the zip file//
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_FAILONERROR, true);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_AUTOREFERER, true);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_FILE, $zipResource);
$page = curl_exec($ch);
if (!$page) {
    echo "Error :- " . curl_error($ch);
}
curl_close($ch);

//make the file accessable probably not needed but i'll do it anyway//
chmod($zipFile, 0666);


/* Open the Zip file */

//create new zip object//
$zip = new ZipArchive;
//creat the path to extract the zip to//
$extractPath = $_SERVER['DOCUMENT_ROOT'] . "/awinProductImport/tmp/zip/";

//check if file exists//
if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/awinProductImport/tmp/' . $_GET['c'] . '.zip')) {
    //open the file//
    $res = $zip->open($_SERVER['DOCUMENT_ROOT'] . '/awinProductImport/tmp/' . $_GET['c'] . '.zip');
    //check if opened//
    if ($res === TRUE) {
        //extract the file//
        $zip->extractTo($extractPath);
        //close the object//
        $zip->close();
    } else {
        //if the file fails to open give failed code//
        echo 'failed, code:' . $res;
    }
}

//function to chunk the csv file so i don't run out of memory//
function file_get_contents_chunked($file, $chunk_size, $callback)
{
    try {
        $handle = fopen($file, "r");
        $i = 0;
        while (!feof($handle)) {
            call_user_func_array($callback, array(fread($handle, $chunk_size), &$handle, $i));
            $i++;
        }
        fclose($handle);
    } catch (Exception $e) {
        trigger_error("file_get_contents_chunked::" . $e->getMessage(), E_USER_NOTICE);
        return false;
    }
    return true;
}

$success = file_get_contents_chunked($_SERVER['DOCUMENT_ROOT'] . "/awinProductImport/tmp/zip/datafeed_" . $config->awinUser . ".csv", 20000000, function ($chunk, &$handle, $i) {

    //use array_map to convert to array//
    $array = array_map("str_getcsv", explode("\n", $chunk));
    //remove the first object//
    unset($array[0]);
    //filter blanks//
    $filter = array_filter($array);

    $replace = array(
        '/',
        '\\',
        '@',
        '='
    );
    //remove the last element of the array//
    $pop = array_pop($filter);

    //create a blank array to hold data//
    $item = [];

    //start looping//
    foreach ($filter as $itm) {
        //add the array data//
        $item[] = array(
            'aw_deep_link' => @$itm[0],
            'product_name' => str_replace($replace, '', @$itm[1]),
            'aw_product_id' => @$itm[2],
            'merchant_product_id' => @$itm[3],
            'merchant_image_url' => @$itm[4],
            'description' => @$itm[5],
            'merchant_category' => @$itm[6],
            'search_price' => @$itm[7],
            'brand_name' => @$itm[8],
            'promotional_text' => @$itm[9],
            'aw_image_url' => @$itm[10],
            'category_name' => @$itm[11],
        );
        //count total objects in array
        $count = count($item);
    }
    //return the amount of objects//
    echo '<br /> ' . $count . ' ';
    //create the db object//
    $db = new DB($_GET['c']);
    //insert data//
    $db->pdo_insert('Products', $item);
    //+1 on itteration//
    $i++;
}
);
print_r($success);
//remove the extracted file//
unlink($_SERVER['DOCUMENT_ROOT'] . "/awinProductImport/tmp/zip/datafeed_" . $config->awinUser . ".csv");
//remove the zip file//
unlink($_SERVER['DOCUMENT_ROOT'] . '/awinProductImport/tmp/' . $_GET['c'] . '.zip');

if (!$success) {
    //It Failed
    echo 'Failed!';
}
