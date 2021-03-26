<?php
// init library
require_once 'curl/curl.php';  // https://github.com/shuber/curl
require_once 'phpquery\phpQuery\phpQuery.php';  // https://github.com/TobiaszCudnik/phpquery

// create instance Curl
$curl = new Curl;

// get page catalog
$response = $curl->get('avtopilot1.ru/capes');

// create new document catalog page
$doc = phpQuery::newDocument($response->body);

// parse catalog page
$products = $doc->find('.item');

foreach ($products as $product) {
    $pq = pq($product); // prepare

    // Get data
    $brend = $pq->find('.item-title')->text();
    $img = $pq->find('.screen-reader-text')->attr('src');
    $url = $pq->attr('href');

    // Processing data
    $brend = str_replace('/', '-', $brend);
    $path = 'capes/' . $brend;
    $extension = parse_url($img, PHP_URL_PATH);
    $extension = pathinfo($extension, PATHINFO_EXTENSION);
    $img_path = $path . '/logo.' . $extension;
    $img_url = 'https://avtopilot1.ru' . $img;

    // Output data
    if ( !is_dir($path) ) {  // check is dir exist
        mkdir($path, 0755, true);
    }
    if( !file_exists($img_path) ) {
        file_put_contents($img_path, file_get_contents($img_url));  // download img
    }

    /*
     * Go inside each category
     */
    $cat_response = $curl->get('avtopilot1.ru' . $url);
    $cat_doc = phpQuery::newDocument($cat_response->body);
    $cat_products = $cat_doc->find('.item');

    foreach ($cat_products as $cat_product) {
        $pq = pq($cat_product); // prepare

        // Get data
        $cat_brend = $pq->find('.item-title')->text();
        $cat_img = $pq->find('.screen-reader-text')->attr('src');
        $cat_url = $pq->attr('href');
        $cat_price = $pq->find('.price')->text();

        // Processing data
        $cat_brend = str_replace('/', '-', $cat_brend);
        $cat_brend = str_replace('&quot;', '', $cat_brend);
        $cat_brend = str_replace('"', '', $cat_brend);
        $cat_path = 'capes/' . $brend . '/' . $cat_brend;
        $extension = parse_url($cat_img, PHP_URL_PATH);
        $extension = pathinfo($extension, PATHINFO_EXTENSION);
        $cat_img_path = $cat_path . '/logo.' . $extension;
        $cat_img_url = 'https://avtopilot1.ru' . $cat_img;
        $cat_price = explode(' ', $cat_price)[0] . '.txt';
        $cat_price_path = $cat_path . '/' . $cat_price;

        // Output data
        if ( !is_dir($cat_path) ) {  // check is dir exist
            mkdir($cat_path, 0755, true);
        }
        if( !file_exists($cat_img_path) ) {
            file_put_contents($cat_img_path, file_get_contents($cat_img_url));  // download img
        }
        if( !file_exists($cat_price_path) ) {
            touch($cat_price_path);
        }

        /*
         * Go inside each cape
         */
        $cape_response = $curl->get('avtopilot1.ru' . $cat_url);
        $cape_doc = phpQuery::newDocument($cape_response->body);
        $cape_products = $cape_doc->find('.product-color');

        foreach ($cape_products as $cape_product) {
            $cape_pq = pq($cape_product); // prepare

            // Get data
            $cape_brend = $cape_pq->attr('value');
            $cape_brend_id = $cape_pq->attr('id');
            $cape_big_img = $cape_pq->attr('data-image');
            $cape_small_img = $cape_doc->find('.colors label[for='. $cape_brend_id .'] img')->attr('src');

            // Processing data
            $cape_brend = str_replace('/', '-', $cape_brend);
            $cape_brend = str_replace('&quot;', '', $cape_brend);
            $cape_brend = str_replace('"', '', $cape_brend);
            $cape_path = 'capes/' . $brend . '/' . $cat_brend;

            $cape_big_img_extension = parse_url($cape_big_img, PHP_URL_PATH);
            $cape_big_img_extension = pathinfo($cape_big_img_extension, PATHINFO_EXTENSION);
            $cape_big_img_path = $cape_path . '/big-' . $cape_brend . '.' . $cape_big_img_extension;
            $cape_big_img_url = 'https://avtopilot1.ru' . $cape_big_img;

            $cape_small_img_extension = parse_url($cape_small_img, PHP_URL_PATH);
            $cape_small_img_extension = pathinfo($cape_small_img_extension, PATHINFO_EXTENSION);
            $cape_small_img_path = $cape_path . '/small-' . $cape_brend . '.' . $cape_small_img_extension;
            $cape_small_img_url = 'https://avtopilot1.ru' . $cape_small_img;

            // Output data
            if( !file_exists($cape_big_img_path) ) {
                file_put_contents($cape_big_img_path, file_get_contents($cape_big_img_url));  // download img
            }
            if( !file_exists($cape_small_img_path) ) {
                file_put_contents($cape_small_img_path, file_get_contents($cape_small_img_url));  // download img
            }
        }
    }
}
