<?php

// Create connection to the MySQL server
$dbc = new mysqli($config['sql']['addr'], $config['sql']['uname'], $config['sql']['passwd'], $config['sql']['db']);

// Check connection
if ($dbc->connect_error) {
    die('<span style="color: red;">Connection to SQL server failed: ' . $dbc->connect_error . '</span>');
}
