<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "auto";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// prepare and bind
$stmt = $conn->prepare("INSERT INTO capemodels (cape_id, title, logo, price) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $brend_parent_id,$model_title, $model_logo, $model_price);

// get directories names
$brends = scandir('capes');
foreach ($brends as $brend) {  // enumerating directories
    if ($brend == '.' || $brend == '..') { continue; }  // check . and .. system folder
    $brend_parent_id = $conn->query("SELECT id FROM capes WHERE title='$brend'")->fetch_assoc()['id'];
    $model_title = '';
    $model_logo = '';
    $model_price = '';

    $models = scandir('capes/'. $brend);
    foreach ($models as $model) {  // enumerating directory
        if ($model == '.' || $model == '..') { continue; }  // check . and .. system folder
        if ( preg_match('/logo/', $model) ) { continue; }
        $model_title = $model;

        $model_inner = scandir('capes/'. $brend . '/' . $model);
        foreach ($model_inner as $model_inner_item) {
            if ($model_inner_item == '.' || $model_inner_item == '..') { continue; }
            if (preg_match('/(txt)$/', $model_inner_item)) {
                $model_price = explode('.', $model_inner_item)[0];
            }
            if (preg_match('/^(logo)/', $model_inner_item)) {
                $model_logo = 'capes/' . $brend . '/' . $model . '/' . $model_inner_item;
            }
        }
        echo $brend_parent_id . '<br>' . $model_title . '<br>' . $model_logo . '<br>' . $model_price . '<hr>';
        $stmt->execute();
    }
}


$stmt->close();
$conn->close();
