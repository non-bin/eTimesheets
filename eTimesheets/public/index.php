<?php

$page = (isset($_GET['p'])) ? $_GET['p'] : 'default';

require '../config.php';
require '../includes/sql.php';
require '../includes/employeeClass.php';

switch ($page) {
	case 'test':
		require '../pages/test.php';
		break;

	case 'alogin':
		require '../pages/admin-login.php';
		break;

	default:
		require '../pages/default.php';
		break;
}

$dbc->close(); // close the database connection
