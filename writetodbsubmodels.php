<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "auto";

$link = mysqli_connect($servername, $username, $password, $dbname)
or die("Ошибка " . mysqli_error($link));

$brends = scandir('img');
foreach ($brends as $brend) {
    if ($brend == '.' || $brend == '..') { continue; }
    $models = scandir('img/' . $brend);
    foreach ($models as $model) {
        if ($model == '.' || $model == '..' || preg_match('/^logo/', $model)) { continue; }
        $id_models = $link->query("SELECT id FROM models WHERE name='$model'");
        while($row = $id_models->fetch_assoc()) {
            $id_model = $row["id"]; // get id of model
            $submodels = scandir('img/' . $brend . '/' . $model);
            foreach ($submodels as $submodel) {
                if ($submodel == '.' || $submodel == '..' || preg_match('/^logo/', $submodel)) { continue; }
                $submodel_imgs = scandir('img/' . $brend . '/' . $model . '/' . $submodel);
                foreach ($submodel_imgs as $submodel_img) {
                    if ($submodel_img == '.' || $submodel_img == '..') { continue; }
                    $submodel_img = 'img/' . $brend . '/' . $model . '/' . $submodel . '/' . $submodel_img . '<hr>';
                    $sql = "INSERT INTO `submodels` (`id`, `name`, `img`, `id_model`) VALUES (NULL, '$submodel', '$submodel_img', '$id_model')";
                    if ($link->query($sql) === TRUE) {
                        echo "New record $submodel created successfully" . '<br>';
                    } else {
                        echo "Error: " . $sql . "<br>" . $link->error;
                    }
                }
            }
        }
    }
}

mysqli_close($link);
