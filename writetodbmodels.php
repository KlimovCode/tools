<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "auto";

$link = mysqli_connect($servername, $username, $password, $dbname)
or die("Ошибка " . mysqli_error($link));

$brends = scandir('img');
foreach ($brends as $brend) {  // enumerating directories
    if ($brend == '.' || $brend == '..') { continue; }  // check . and .. system folder
    $id_brends = $link->query("SELECT id FROM brends WHERE name='$brend'");
    while($row = $id_brends->fetch_assoc()) {
        $id_brend = $row["id"]; // get id of brend

        $models = scandir('img/' . $brend);
        foreach ($models as $model) {
            if ($model == '.' || $model == '..' || preg_match('/^logo/', $model)) { continue; }

            $model_imgs = scandir('img/' . $brend . '/' . $model);
            foreach ($model_imgs as $model_img) {
                if (!preg_match('/^logo/', $model_img)) { continue; }
                $sql = "INSERT INTO `models` (`id`, `name`, `img`, `id_brend`) VALUES (NULL, '$model', '$model_img', '$id_brend')";
                if ($link->query($sql) === TRUE) {
                    echo "New record $model created successfully" . '<br>';
                } else {
                    echo "Error: " . $sql . "<br>" . $link->error;
                }
            }
        }
    }
}


mysqli_close($link);
