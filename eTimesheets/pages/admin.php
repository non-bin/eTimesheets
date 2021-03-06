<?php

/**
 * Alice Jacka admin.php 1.0 (created 27/7/18)
 *
 * handle login requests, session timeouts,
 * and redirect to specific admin pages
 */

/// variable declarations ///

$output['error'] = $error;

$action = (isset($_GET['a'])) ? $_GET['a'] : '' ;


/// login and sign up handler ///

if ($action == 'auth') { // authenticate a logon request
    if (isset($_POST['uname'], $_POST['password'])) { // if all feilds were sent
        $user = new Admin($_POST['uname']);

        if (isset($user->uname)) {                            // if the user exists
            if ($user->checkPassword($_POST['password'])) {   // check the password
                $_SESSION['loggedOnAdmin'] = $_POST['uname']; // save the login to the session
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
    $uid = $_SESSION['loggedOnAdmin'];
} elseif (!in_array($action, ['login', 'signUp'])) {
    $action = 'login';
} else {
    $action = ($action == 'signUp') ? $action : 'login' ; // only allow them to visit signup, else default to login
}

if ($action == 'update') { // if an event update is requested
    $action = 'individual'; // give the individual page

    $event = new Event($_GET['id']); // get the event
    $event->update($_POST['datetime'], $_POST['type']); // and update it
}

if ($action == 'delete') { // if an event deletion is requested
    $action = 'individual'; // give the individual page

    $event = new Event($_GET['id']); // get the event
    $event->delete(); // delete it
}


/// admin page selection ///

switch ($action) {
    case 'home': // home page
        require '../pages/adminHome.php';
        break;

    case 'individual': // individual summary page
        require '../pages/adminIndividual.php';
        break;

    case 'amend': // information ammend page
        require '../pages/adminAmend.php';
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

$_SESSION['LAST_ACTIVITY'] = $config['debug']['timeOverride']; // update last activity time stamp
