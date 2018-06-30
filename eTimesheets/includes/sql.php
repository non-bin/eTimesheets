<?php

// Create connection to the MySQL server
$dbc = new mysqli($config['sql']['addr'], $config['sql']['uname'], $config['sql']['passwd'], $config['sql']['db']);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
