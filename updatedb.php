<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "auto";

$link = mysqli_connect($servername, $username, $password, $dbname)
or die("Ошибка " . mysqli_error($link));

$models_imgs = $link->query("SELECT id, name, img FROM submodels");
while($row = $models_imgs->fetch_assoc()) {
    $models_img = $row["img"]; // get id of brend
    $models_img = substr($models_img, 0,-4);
    $temp = $row["id"];
    $temp2 = $row["name"];
    $sql = "UPDATE submodels SET img='$models_img' WHERE id='$temp'";
    if ($link->query($sql) === TRUE) {
        echo "New record $temp2 update successfully" . '<br>';
    } else {
        echo "Error: " . $sql . "<br>" . $link->error;
    }
}