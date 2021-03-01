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
$stmt = $conn->prepare("INSERT INTO rugsbrends (title, logo) VALUES (?, ?)");
$stmt->bind_param("ss", $brend, $logouri);

// get directories names
$brends = scandir('img');
foreach ($brends as $brend) {  // enumerating directories
    if ($brend == '.' || $brend == '..') { continue; }  // check . and .. system folder
    $logos = scandir('img/'. $brend);
    foreach ($logos as $logo) {  // enumerating directory
        if ($logo == '.' || $logo == '..') { continue; }  // check . and .. system folder
        if ( preg_match('/logo/', $logo) ) {
            $logouri =  'img/'. $brend . '/' . $logo;
            echo $brend . '<br>' . $logouri . '<hr>';
            $stmt->execute();
        }
    }
}


$stmt->close();
$conn->close();
