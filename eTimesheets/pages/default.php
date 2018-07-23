<?php

/// includes ///

require '../includes/pageDefault.php'; // load page specific functions


/// session ///

session_start(); // initiate the session
// if the session has been alive more that 2 minuites, destroy it
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 120)) {
    session_unset();   // unset $_SESSION variable for the run-time
    session_destroy(); // destroy session data in storage
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
$output['title']         = $config['main']['title'] . ' - home'; // define the title using the configured prefix
$output['style']         = '';
$output['header']        = '';
$output['main']          = '';
$output['footer']        = '';
$output['action']        = '';
$output['actionContent'] = '';
$output['loginError']    = '';

// conditional variable setting
$action        = (isset($_GET['act'])) ? $_GET['act'] : 'default' ; // if an action was specified use it, if not, use 'default'
$uid           = (isset($_GET['uid'])) ? $_GET['uid'] : 1 ;         // if a uid was specified use it, if not use 1
$output['uid'] = $uid;

// calculate the background and foregroung colour
$foreground = hueRotate('ff5d55', $uid);
$background = hueRotate('ffc6c6', $uid);
// use the generated colours in a style
$output['style'] .= 'body { background-color: #' . $background . '; } .foreground { background-color: #' . $foreground . '; }';


/// action log handler ///
if (isset($_SESSION['currentUser'])) {
    if (in_array($_GET['action'], ['in', 'ou', 'bl', 'el'])) { // if the user is logging an event
        $employee->addEvent(date("Y-m-d H:i:s"), $_GET['action']);
    }
}


/// login handler ///

if ($action == 'login') {
    if (isset($_POST['pin']) && $employee->checkPin($_POST['pin'])) { // verify the pin
        $_SESSION['currentUser'] = $uid; // save the fact that the user is logged in
    } else {
        $output['loginError'] = 'INCORRECT PIN!'; // create a login error for the main button generator
        $nextAction = 'login';
    }
}

if (isset($_SESSION['currentUser'])) { // if a user is logged in
    $nextAction = $employee->predictEvent(); // set the action to be interpreted by the main button creation
}


/// output generation ///

// generate the user selection aria
$empList = getEmployeeList();
foreach ($empList as $emp) {
    $output['header'] .= '
    <a class="avatar" href="?p=default&uid=' . $emp[0] . '">
        <img class="avatar" style="filter: hue-rotate(' . calcHueRotate($emp[0]) . 'deg)" src="./img/avatar.png" alt="username">
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
        <a class="action foreground" href="?p=default&uid=' . $output['uid'] . '&act=in">
            <span class="action-lable">
                Sign In
            </span>
        </a>';
        break;

    case 'ou': // sign out
        $output['actionContent'] = '
        <a class="action foreground" href="?p=default&uid=' . $output['uid'] . '&act=ou">
            <span class="action-lable">
                Sign Out
            </span>
        </a>';
        break;

    case 'bl': // begin lunch
        $output['actionContent'] = '
        <a class="action foreground" href="?p=default&uid=' . $output['uid'] . '&act=bl">
            <span class="action-lable">
                Begin Lunch
            </span>
        </a>';
        break;

    case 'el': // end lunch
        $output['actionContent'] = '
        <a class="action foreground" href="?p=default&uid=' . $output['uid'] . '&act=el">
            <span class="action-lable">
                Eng Lunch
            </span>
        </a>';
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
