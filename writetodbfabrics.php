<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "auto";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$chairs = scandir('chair');
foreach ($chairs as $chair) {
    if($chair == '.' || $chair == '..') { continue; }
    $id_chairs = $conn->query("SELECT id FROM chairs WHERE name='$chair'");
    while($row = $id_chairs->fetch_assoc()) {
        $id_chair = $row["id"];
        $colors = scandir('chair/' . $chair);
        foreach ($colors as $color) {
            if($color == '.' || $color == '..') { continue; }
            $fabric_imgs = scandir('chair/' . $chair . '/' . $color);
            $img_big = 'chair/' . $chair . '/' . $color . '/' . $fabric_imgs[2];
            $img_small = 'chair/' . $chair . '/' . $color . '/' . $fabric_imgs[3];
            $sql = "INSERT INTO `fabrics` (`id`, `name`, `img_big`, `img_small`, `id_chair`)
                    VALUES (NULL, '$color', '$img_big', '$img_small', '$id_chair')";
            if ($conn->query($sql) === TRUE) {
                echo "New record $color created successfully" . '<br>';
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }

}

$conn->close();
