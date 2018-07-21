<?php

session_start();

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 120)) {
    // last request was more than 2 minutes ago
    session_unset();   // unset $_SESSION variable for the run-time
    session_destroy(); // destroy session data in storage
}

require '../includes/pageDefault.php'; // this file contains all functions needed in the default page

$uid    = (isset($_GET['uid'])) ? $_GET['uid'] : 1 ;         // if a uid was specified use it, if not, use 1

if (isset($_GET['uid'])) {
    $uid = $_GET['uid'];

    // destroy the session as a new user was requested
    session_unset();
    session_destroy();
}

$action = (isset($_GET['act'])) ? $_GET['act'] : 'default' ; // if an action was specified use it, if not, use 'default'

$output['title'] = $config['main']['title'] . ' - home'; // define the title using the configured prefix
$output['uid']   = $uid;

$output['style']         = '';
$output['header']        = '';
$output['main']          = '';
$output['footer']        = '';
$output['action']        = '';
$output['actionContent'] = '';

// calculate the background colour
$backHsl    = hexToHsl('ffc6c6');
$backHsl[0] = calcHueRotate($uid)/360; // rotate the hue to the user's unique one
$background = hslToHex($backHsl);

// do the same for the foreground
$foreHsl    = hexToHsl('ff5d55');
$foreHsl[0] = calcHueRotate($uid)/360;
$foreground = hslToHex($foreHsl);

// use the generated colours in a style
$output['style'] .= 'body { background-color: #' . $background . '; } .foreground { background-color: #' . $foreground . '; }';

// generate the user selection aria
$empList = getEmployeeList();
foreach ($empList as $emp) {
    $output['header'] .= '
    <a class="avatar" href="?p=default&uid=' . $emp[0] . '">
        <img class="avatar" style="filter: hue-rotate(' . calcHueRotate($emp[0]) . 'deg)" src="./img/avatar.png" alt="username">
    </a>';
}

// handel login request
if ($action == 'login') {
    $employee = new Employee($uid); // create an instance of the employee object

    if (isset($_POST['pin']) && $employee->checkPin($_POST['pin'])) { // verify the pin
        $_SESSION['currentUser'] = $uid; // save the fact that the user is logged in
    } else {
        $output['actionContent'] = loginButton($output['uid'], 'INCORRECT PIN!'); // output the login button with the 'inforrect pin' error
        $action = 'none'; // prevent the button from being recreated
    }
}

// create the main button
switch ($action) {
    case 'in': // sign in
        $output['actionContent'] = '
        <a href="?p=default&uid=' . $output['uid'] . '&act=' . $output['action'] . '"></a>';
        break;

    case 'ou': // sign out
        $output['actionContent'] = '
        <a href="?p=default&uid=' . $output['uid'] . '&act=' . $output['action'] . '"></a>';
        break;

    case 'bl': // begin lunch
        $output['actionContent'] = '
        <a href="?p=default&uid=' . $output['uid'] . '&act=' . $output['action'] . '"></a>';
        break;

    case 'el': // end lunch
        $output['actionContent'] = '
        <a href="?p=default&uid=' . $output['uid'] . '&act=' . $output['action'] . '"></a>';
        break;

    case 'none':
        // do nothing
        break;

    default: // login button
        $output['actionContent'] = loginButton($output['uid']);
        break;
}

$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?=$output['title'] ?></title>
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/default.css">
    <?='<style>' . $output['style'] . '</style>' // for some reason having the style tags outside of php messed with the formating ?>
</head>
<body>

<span class="header">
    <?=$output['header'] ?>
</span>

<span class="main">
        <?=$output['actionContent'] ?>
</span>

<span class="footer">
    <?=$output['footer'] ?>
</span>

</body>
