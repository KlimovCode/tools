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

/*
 * stmt prepare
 *
 * get "$model_id" from db by model name
 * chain get title color, and uri sm_img + big_img
 *
 * stmt execute
 */

// prepare and bind
$stmt = $conn->prepare("INSERT INTO capeitem (capemodel_id, title, sm_img, big_img) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $model_id,$temp_rug, $rug_sm_img, $rug_big_img);

// parse imgs directory
$brends = scandir('capes');
foreach ($brends as $brend) {
    if ($brend == '.' || $brend == '..') { continue; }
    $model_id = '';
    $rug_title = '';
    $rug_big_img = '';
    $rug_sm_img = '';

    $models = scandir('capes/'. $brend);
    foreach ($models as $model) {  // enumerating directory
        if ($model == '.' || $model == '..' || preg_match('/logo/', $model)) { continue; }

        // GET model $model_id
        $model_id = $conn->query("SELECT id FROM capemodels WHERE title='$model'")->fetch_assoc()['id'];
        if($model_id) {
            $rugs = scandir('capes/'. $brend . '/' . $model);
            // GET color title $rug_title to $temp_rugs = [Strings => 'Черный', 'Серый', 'Белый']
            $temp_rugs = [];
            foreach ($rugs as $rug) {
                if ($rug == '.' || $rug == '..' || preg_match('/^(logo\.)/', $rug) || preg_match('/(\.txt)$/', $rug)) { continue; }
                if( preg_match('/^[big-]/', $rug) ) {
                    $temp_rugs[] = substr($rug, 4, -4);
                }
            }
            // GET images uri
            foreach ($temp_rugs as $temp_rug) {
                foreach ($rugs as $rug) {
                    if ($rug == '.' || $rug == '..' || preg_match('/^(logo\.)/', $rug) || preg_match('/(\.txt)$/', $rug)) { continue; }
                    if( preg_match('/big-'. $temp_rug . '/', $rug) ) {
                        $rug_big_img = 'capes/' . $brend . '/' . $model . '/' . $rug;
                    }
                    if( preg_match('/small-'. $temp_rug . '/', $rug) ) {
                        $rug_sm_img = 'capes/'. $brend . '/' . $model . '/' . $rug;
                    }
                }
                $stmt->execute();
                echo $model_id . '<br>' . $temp_rug . '<br>' . $rug_big_img . '<br>' . $rug_sm_img . '<br>';
            }
            echo '<hr>';
        }
    }
}


$stmt->close();
$conn->close();
