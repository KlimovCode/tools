<?php
// init library
require_once 'curl/curl.php';  // https://github.com/shuber/curl
require_once 'phpquery\phpQuery\phpQuery.php';  // https://github.com/TobiaszCudnik/phpquery

// create instance Curl
$curl = new Curl;

// get page
$response = $curl->get('avtopilot.shop/catalog');

// create new document
$doc = phpQuery::newDocument($response->body);

// parse interesting elements to array
$products = $doc->find('.brends-items');

// enumeration elements
foreach ($products as $product) {
    $pq = pq($product); // parse element

    $brend = $pq->find('.brends-name')->text();  // get one
    $url = $pq->find('.brends-img img')->attr('src');  // get two
    // echo $brend . '<br>' . $url . '<br><hr><br>'; // check data

    // create dir
    if ( !is_dir( 'img/' . $brend ) ) {  // check is dir exist
        mkdir('img/' . $brend, 0755, true);
    }

    // get extension
    $extension = parse_url($url, PHP_URL_PATH);
    $extension = pathinfo($extension, PATHINFO_EXTENSION);

    $path = './img/' . $brend . '/logo.' . $extension;  // set file name
    file_put_contents($path, file_get_contents($url));  // download file
    // echo  $brend . '<br>' . '<img src=' . $path . '>' . '<br><hr><br>';  // check
}