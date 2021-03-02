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
//$stmt = $conn->prepare("INSERT INTO rugsitems (model_id, title, sm_img, big_img) VALUES (?, ?, ?, ?)");
//$stmt->bind_param("ssss", $rug_parent_model_id,$rug_title, $rug_sm_img, $rug_big_img);

// get directories names
$brends = scandir('img');
foreach ($brends as $brend) {  // enumerating directories
    if ($brend == '.' || $brend == '..') { continue; }  // check . and .. system folder
//    $brend_parent_id = $conn->query("SELECT id FROM rugsbrends WHERE title='$brend'")->fetch_assoc()['id'];
//    $model_title = '';
//    $model_logo = '';
//    $model_price = '';

    $models = scandir('img/'. $brend);
    foreach ($models as $model) {  // enumerating directory
        if ($model == '.' || $model == '..' || preg_match('/logo/', $model)) { continue; }

        $rugs = scandir('img/'. $brend . '/' . $model);
        foreach ($rugs as $rug) {
            if ($rug == '.' || $rug == '..' || preg_match('/^(logo\.)/', $rug) || preg_match('/(\.txt)$/', $rug)) { continue; }
            echo $model . '<br>' . $rug . '<hr>';

        }
//        echo $brend_parent_id . '<br>' . $model_title . '<br>' . $model_logo . '<br>' . $model_price . '<hr>';
//        $stmt->execute();
    }
}


//$stmt->close();
$conn->close();
