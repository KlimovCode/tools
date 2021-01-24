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
$stmt = $conn->prepare("INSERT INTO brends (name, img) VALUES (?, ?)");
$stmt->bind_param("ss", $brend, $sqlstr);

// get directories names
$brends = scandir('img');
foreach ($brends as $brend) {  // enumerating directories
    if ($brend == '.' || $brend == '..') { continue; }  // check . and .. system folder
    $logos = scandir('img/'. $brend);
    foreach ($logos as $logo) {  // enumerating directory
        if ($logo == '.' || $logo == '..') { continue; }  // check . and .. system folder
        $sqlstr =  'img/'. $brend . '/' . $logo;
        $stmt->execute();
    }
}
// check success record
if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$stmt->close();
$conn->close();
