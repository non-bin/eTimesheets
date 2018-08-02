<?php

/**
 * Harry Jacka admin.php 0.1 (created 27/7/18)
 *
 * handle login requests, session timeouts,
 * and redirect to specific admin pages
 */

/// variable declarations ///

$output['error'] = $error;

$action = (isset($_GET['a'])) ? $_GET['a'] : '' ;


/// login and sign up handler ///

if ($action == 'auth') { // authenticate a logon request
    if (isset($_POST['uname'], $_POST['password'])) {
        $user = new Admin($_POST['uname']);

        if (isset($user->uname)) {
            if ($user->checkPassword($_POST['password'])) {
                $_SESSION['loggedOnAdmin'] = $_POST['uname'];
                $action = 'home';

                error_log('admin auth success: for uname "' . $_POST['uname'] . '"');
            } else {
                $output['error'] = 'admin auth failed: incorrect password';
                error_log($output['error']);
            }
        } else {
            $output['error'] = 'admin auth failed: incorrect username';
            error_log($output['error']);
        }
    } else {
        $output['error'] = 'admin auth failed: one or more feilds missing';
        error_log($output['error']);
    }
}

if ($action == 'su') { // process a sign up request
    if (isset($_POST['uname'], $_POST['password'], $_POST['confirm']) && $_POST['uname'] != '' && $_POST['password'] != '' && $_POST['confirm'] != '') { // check all values recieved
        if ($_POST['password'] === $_POST['confirm']) {                  // check passwords identical
            if (addAdminUser($_POST['uname'], $_POST['password'])) {     // add the user
                error_log('sign up sucess: for uname "' . $_POST['uname'] . '"');
                $action = 'login';
            } else {
                $output['error'] = 'sign up failed: database error'; // save an error to be reported to the user
                error_log($output['error']);                         // and log it to console
                $action = 'signUp';
            }
        } else {
            $output['error'] = 'sign up failed: passwords do not match';
            error_log($output['error']);
            $action = 'signUp';
        }
    } else {
        $output['error'] = 'sign up failed: one or more feilds missing';
        error_log($output['error']);
        $action = 'signUp';
    }
}


/// authentication ///

// this is seperate from the login handeler
// because a user stays logged on, so actions
// can be performed without relogging in

if (isset($_SESSION['loggedOnAdmin'])) { // if a user is logged on
    $action = ($action != '') ? $action : 'home' ; // give them the page they request, or default to home
} else {
    $action = ($action == 'signUp') ? $action : 'login' ; // only allow them to visit signup, else default to login
}



if (isset($_SESSION['loggedOnAdmin'])) {
    $uid = $_SESSION['loggedOnAdmin'];
} elseif (!in_array($action, ['login', 'signUp'])) {
    $action = 'login';
}

/// admin page selection ///

switch ($action) {
    case 'home': // home page
        require '../pages/adminHome.php';
        break;

    case 'individual': // individual summary page
        # code...
        break;

    case 'amend': // information amment page
        # code...
        break;

    case 'signUp':
        require '../pages/adminSignUp.php';
        break;

    case 'login': // display the login screen
    default:
        destroySession();
        require '../pages/adminLogin.php';
        break;
}


/// finishing up ///

$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
