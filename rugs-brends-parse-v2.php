<?php
// init library
require_once '../curl/curl.php';  // https://github.com/shuber/curl
require_once '../phpquery\phpQuery\phpQuery.php';  // https://github.com/TobiaszCudnik/phpquery

// create instance Curl
$curl = new Curl;

// get page catalog
$response = $curl->get('avtopilot1.ru/mates/');

// create new document catalog page
$doc = phpQuery::newDocument($response->body);

// parse catalog page
$brends = $doc->find('.tiles .item');
foreach ($brends as $brend) {  // get links for models
    $brend_item = pq($brend); // parse element

    $brend_item_title = $brend_item->find('h2')->text();
    $brend_item_img = $brend_item->find('.preview img')->attr('src');
    $brend_item_url = 'https://avtopilot1.ru' . $brend_item->attr('href');

    $brend_path = 'img/' . $brend_item_title;
    if (!is_dir($brend_path) ) {  // check is dir exist
        mkdir($brend_path, 0755, true);
    }

    $brend_item_img_extension = parse_url('https://avtopilot1.ru/' . $brend_item_img, PHP_URL_PATH);
    $brend_item_img_extension = pathinfo($brend_item_img_extension, PATHINFO_EXTENSION);

    $brend_item_img_local_path = $brend_path . '/logo.' . $brend_item_img_extension;
    if( !file_exists($brend_item_img_local_path) ) {
        file_put_contents($brend_item_img_local_path, file_get_contents('https://avtopilot1.ru/' . $brend_item_img));
    }

    $response_models = $curl->get($brend_item_url);
    $doc_models = phpQuery::newDocument($response_models->body);
    $models = $doc_models->find('.tiles .item');
    foreach ($models as $model) {
        $model_item = pq($model);
        $model_item_title = $model_item->find('h2')->text();
        $model_item_img = 'https://avtopilot1.ru' . $model_item->find('.preview img')->attr('src');
        $model_item_price = $model_item->find('.info .price')->text();
        $model_item_url = 'https://avtopilot1.ru' . $model_item->attr('href');


        $model_path = 'img/' . $brend_item_title . '/' . $model_item_title;
        $model_path = trim($model_path, '.');
        if (!is_dir($model_path) ) {  // check is dir exist
            mkdir($model_path, 0755, true);
        }
        $model_item_img_extension = parse_url($model_item_img, PHP_URL_PATH);
        $model_item_img_extension = pathinfo($model_item_img_extension, PATHINFO_EXTENSION);

        $model_item_img_local_path = $model_path . '/logo.' . $model_item_img_extension;
        if( !file_exists($model_item_img_local_path) ) {
            file_put_contents($model_item_img_local_path, file_get_contents($model_item_img));
        }

        $model_item_price_local_path = $model_path . '/' . explode(' ', $model_item_price)[0];
        if( !file_exists($model_item_price_local_path) ) {
            file_put_contents($model_item_price_local_path . '.txt', '');
        }
    }
}

