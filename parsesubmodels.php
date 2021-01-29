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
    $models = $models_doc->find('.auto-covers_series-element');  // found each card with model

    foreach ($models as $model) {  // enumerate models link
        $pq = pq($model); // parse element
        $model_name = $pq->find('.text-block')->text();  // get name of model
        $model_url = $pq->find('a')->attr('href');  // get url of sub models

        $submodels_list = $curl->get('avtopilot.shop/'. $model_url); // sub models catalog page
        $submodels_doc = phpQuery::newDocument($submodels_list->body);
        $submodels = $submodels_doc->find('.auto-covers_series-element');

        foreach ($submodels as $submodel) { // enumerate sub models catalog
            $pq = pq($submodel); // parse element
            $submodel_name = $pq->find('.text-block')->text();  // get name of sub model
            $submodel_img = $pq->find('.img-block img')->attr('src');  // get img of sub model

            $submodel_name = substr($submodel_name, 0,-7);

            $path = 'img/' . $brend . '/' . $model_name . '/' . $submodel_name . '/';

            if ( !is_dir($path) ) {  // check is dir exist
                mkdir($path, 0755, true);
            }

            // get extension
            $extension = parse_url($submodel_img, PHP_URL_PATH);
            $extension = pathinfo($extension, PATHINFO_EXTENSION);

            $img_local_path = $path . 'logo.' . $extension;

            if( !file_exists($img_local_path) ) {
                file_put_contents($img_local_path, file_get_contents($submodel_img));  // download img
            }
        }
    }
}
echo 'done';
