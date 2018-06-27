<?php

require '../includes/sql.php';

switch ($_GET['p']) {
	case 'alogin':
		require '../pages/admin-login.php';
		break;

	default:
		require '../pages/default.php';
		break;
}
