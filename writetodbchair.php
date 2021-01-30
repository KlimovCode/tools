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
    $sql = "INSERT INTO `chairs` (`id`, `name`) VALUES (NULL, '$chair')";
    if ($conn->query($sql) === TRUE) {
        echo "New record $chair created successfully" . '<br>';
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
