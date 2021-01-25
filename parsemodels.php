<?php
// init library
require_once 'curl/curl.php';  // https://github.com/shuber/curl
require_once 'phpquery\phpQuery\phpQuery.php';  // https://github.com/TobiaszCudnik/phpquery

// create instance Curl
$curl = new Curl;

// get page catalog
$response = $curl->get('avtopilot.shop/catalog');

// create new document catalog page
$doc = phpQuery::newDocument($response->body);

// parse catalog page
$products = $doc->find('.brends-items');
foreach ($products as $product) {  // get links for models
    $pq = pq($product); // parse element

    $brend = $pq->find('.brends-name')->text();  // get name of brend
    $url = $pq->attr('href');  // get url of models
    $models_list = $curl->get('avtopilot.shop/'. $url); // models catalog page

    $models_doc = phpQuery::newDocument($models_list->body);  // create new document models catalog page
    $models = $models_doc->find('.auto-covers_series-element');

    foreach ($models as $model) {
        $pq = pq($model); // parse element

        $name = $pq->find('.text-block')->text();  // get one
        $img = $pq->find('.img-block img')->attr('src');  // get two

        $path = 'img/' . $brend . '/' . $name;

        if ( !is_dir($path) ) {  // check is dir exist
            mkdir($path, 0755, true);
        }

        // get extension
        $extension = parse_url($img, PHP_URL_PATH);
        $extension = pathinfo($extension, PATHINFO_EXTENSION);

        $img_local_path = $path . '/logo.' . $extension;
        if( !file_exists($img_local_path) ) {
            file_put_contents($img_local_path, file_get_contents($img));  // download img
        }
    }
}

