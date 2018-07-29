<?php

/**
 * Harry Jacka default.php 1.0 (created 25/7/18)
 *
 * serve and perform the actions available from
 * the default page
 */

/// includes ///

require '../includes/pageDefault.php'; // load page specific functions


/// session ///

session_start(); // initiate the session

// if the session has been alive more that the set timeout setting minuites
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $config['misc']['sesTimeout']) && $config['misc']['sesTimeout'] !== 0) {
    error_log('session destroyed: session timed out');
    destroySession();
}

// if a new user has been selected
if (isset($_SESSION['currentUser']) && isset($_GET['uid']) && $_SESSION['currentUser'] != $_GET['uid']) {
    error_log('session destroyed: new user request');
    destroySession();
}


/// uid selection and employee creation ///

// in essence, if a user is logged in, and a new one has not been requested
// if a user is currently logged in,     and       either a uid was not specified, or      is the same as in the session
if ( isset($_SESSION['currentUser']) /**/ && /**/ (!isset($_GET['uid']) /*      */ || /**/ $_SESSION['currentUser'] == $_GET['uid'])) {
    $loggedIn = true;
    $uid = $_SESSION['currentUser'];
} else {
    $loggedIn = false;
    $uid = (isset($_GET['uid'])) ? $_GET['uid'] : 1 ; // set the uid to either the requested on, or default to 1
}

$employee = new Employee($uid); // create an instance of the employee object for the selected uid


/// variable definitions ///

// initiate output variables
$output['title']         = $config['main']['title']; // define the title using the configured prefix
$output['style']         = '';
$output['header']        = '';
$output['main']          = '';
$output['footer']        = '';
$output['action']        = '';
$output['actionContent'] = '';
$output['error']    = '';

$empIds = [];

$empList = getEmployeeList();
foreach ($empList as $emp) {
    $empIds[] = $emp->uid;
}

// conditional variable setting
$action        = (isset($_GET['act'])) ? $_GET['act'] : 'default' ; // if an action was specified use it, if not, use 'default'
$uid           = (isset($_GET['uid']) && in_array($_GET['uid'], $empIds)) ? $_GET['uid'] : 1 ;         // if a uid was specified use it, if not use 1
$output['uid'] = $uid;


/// action log handler ///

// if the user is logged in and trying to log an event
if (isset($_SESSION['currentUser'], $_GET['action'], $_GET['event']) && $_GET['action'] == 'log' && in_array($_GET['event'], ['in', 'ou', 'bl', 'el'])) {
    error_log('new event: ' . sqlDateTime() . ' - ' . $_GET['event']);
    $result = $employee->addEvent(sqlDateTime(), $_GET['event']);
    if ($result !== true) {
        $nextAction = 'error'; // tell the output generation there was an error
        $output['error'] = $result; // save the error
        error_log($result); // log the error to the console
    }

    destroySession(); // the user has logged an event, so log them out
}


/// login handler ///

if ($action == 'login') {
    if (isset($_POST['pin']) && $employee->checkPin($_POST['pin'])) { // verify the pin
        error_log('login: success for uid ' . $_GET['uid']);
        $_SESSION['currentUser'] = $uid; // save the fact that the user is logged in
    } else {
        error_log('login: failed for uid ' . $_GET['uid']);
        $output['error'] = 'INCORRECT PIN!'; // create a login error for the main button generator
        $nextAction = 'login';
    }
}

if (isset($_SESSION['currentUser'])) { // if a user is logged in
    $nextAction = $employee->predictEvent(); // set the action to be interpreted by the main button creation
}


/// output generation ///

// calculate the background and foregroung colour
$foreground = hueRotate('ff5d55', $uid);
$background = hueRotate('ffc6c6', $uid);
// use the generated colours in a style
$output['style'] .= 'body { background-color: #' . $background . '; } .foreground { background-color: #' . $foreground . '; }';

// generate the user selection aria
foreach ($empIds as $id) {
    $output['header'] .= '
    <a class="avatar" href="?p=default&uid=' . $id . '">
        <img class="avatar" style="filter: hue-rotate(' . calcHueRotate($id) . 'deg)" src="./img/avatar.png" alt="username">
    </a>';
}

// if no next action has been set, default to 'login'
if (!isset($nextAction)) {
    $nextAction = 'login';
}

// create the main button
switch ($nextAction) {
    case 'in': // sign in
        $output['actionContent'] = '
        <a class="action foreground" href="?p=default&uid=' . $output['uid'] . '&action=log&event=in">
            <span class="action-lable">
                <span class="small">Currently Out</span><br>
                Sign In</i>
            </span>
        </a>';
        break;

    case 'ou': // sign out
        $output['actionContent'] = '
        <a class="action foreground" href="?p=default&uid=' . $output['uid'] . '&action=log&event=ou">
            <span class="action-lable">
                <span class="small">Currently Working</span><br>
                Sign Out</i>
            </span>
        </a>';
        break;

    case 'bl': // begin lunch
        $output['actionContent'] = '
        <a class="action foreground" href="?p=default&uid=' . $output['uid'] . '&action=log&event=bl">
            <span class="action-lable">
                <span class="small">Currently Working</span><br>
                Begin Lunch</i>
            </span>
        </a>';
        break;

    case 'el': // end lunch
        $output['actionContent'] = '
        <a class="action foreground" href="?p=default&uid=' . $output['uid'] . '&action=log&event=el">
            <span class="action-lable">
                <span class="small">Currently at Lunch</span><br>
                Sign In</i>
            </span>
        </a>';
        break;

    case 'error':
    $output['actionContent'] = '
        <span class="action foreground">
            <span class="action-lable">
                <span class="error">' . $output['error'] . '</span>
            </span>
        </span>';
        break;

    case 'login':
    default: // login button
        $output['actionContent'] = '
        <span class="action foreground">
            <span class="action-lable">
                <form action="?p=default&uid=' . $output['uid'] . '&act=login" method="post">
                    <span class="error">' . $output['error'] . '</span></br>
                    <input type="password" name="pin" id="pin-input" placeholder="Enter Pin" autofocus>
                </form>
            </span>
        </span>';
        break;
}

/// finishing up ///

$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?=$output['title'] ?></title>
    <link rel="shortcut icon" href="./img/default.ico" type="image/x-icon" sizes="64x64">
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
    <!-- <a href="?p=default&action=advanced" class="footerButton foreground">Advanced</a> -->
    <a href="?p=admin" class="footerButton foreground">Admin</a>
</span>

</body>
