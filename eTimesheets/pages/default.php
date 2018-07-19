<?php

session_start();

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 120)) {
    // last request was more than 2 minutes ago
    session_unset();   // unset $_SESSION variable for the run-time
    session_destroy(); // destroy session data in storage
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

require '../includes/pageDefault.php'; // this file contains all functions needed in the default page

$uid    = (isset($_GET['uid'])) ? $_GET['uid'] : 1 ;               // if a uid was specified use it, if not, use 1
$action = (isset($_GET['action'])) ? $_GET['action'] : 'default' ; // if an action was specified use it, if not, use 'default'

$output['title'] = $config['main']['title'] . ' - home'; // define the title using the configured prefix
$output['uid']   = $uid;

$output['style']  = '';
$output['header'] = '';
$output['main']   = '';
$output['footer'] = '';
$output['action'] = '';

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
    <a class="avatar" href="?p=default&uid= ' . $emp[0] . '">
        <img class="avatar" style="filter: hue-rotate(' . calcHueRotate($emp[0]) . 'deg)" src="./img/avatar.png" alt="username">
    </a>';
}

// generate the main action button
switch ($action) {
    case 'si':
        $output['actionContent'] = '
        <a href="?p=default&uid=' . $output['uid'] . '&act=' . $output['action'] . '">
        </a>';
        break;

    default:
        $output['actionContent'] = '
        <span class="action foreground">
            <span class="action-lable">
                <form action="?p=default&uid=' . $output['uid'] . '&act=login" method="post">
                    <input type="text" name="pin" id="pin-input" placeholder="Enter Pin">
                </form>
            </span>
        </span>';
        break;
}

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
