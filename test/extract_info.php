<?php
$servername = "localhost";
$username = "root";
$password = "";
$databasename = "your_database_name";

$conn = new mysqli($servername, $username, $password, $databasename);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT * FROM `Student Details`;";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "Roll No: " . $row["Roll_No"] . " - Name: " . $row["Name"] . " | City: " . $row["City"] . " | Age: " . $row["Age"] . "<br>";
    }
} else {
    echo "0 results";
}

$conn->close();
?>
