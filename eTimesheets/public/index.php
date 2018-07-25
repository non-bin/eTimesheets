<?php

/**
 * Harry Jacka index.php 1.1 (created 27/6/18)
 *
 * handel main page redirection
 */

$page = (isset($_GET['p'])) ? $_GET['p'] : 'default'; // if a page was specified, use it. if not, use the default

require '../config.php';
require '../includes/main.php';
require '../includes/sql.php';
require '../includes/employeeClass.php';
require '../includes/eventClass.php';
// require '../includes/adminClass.php';

switch ($page) { // select the requested page
    case 'test':
        require '../pages/test.php';
        break;

    case 'admin':
        require '../pages/admin.php';
        break;

    default:
        require '../pages/default.php';
        break;
}

$dbc->close(); // close the database connection
