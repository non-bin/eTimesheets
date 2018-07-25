<?php

/**
 * Harry Jacka sql.php 1.0 (created 16/7/18)
 *
 * handles the connection to the MySQL server
 */

// Create connection to the MySQL server
$dbc = new mysqli($config['sql']['addr'], $config['sql']['uname'], $config['sql']['passwd'], $config['sql']['db']);

// Check connection
if ($dbc->connect_error) {
    die('<span style="color: red;">Connection to SQL server failed: ' . $dbc->connect_error . '</span>');
}
