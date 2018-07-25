<?php

/**
 * Harry Jacka admin.php 0.1 (created 27/7/18)
 *
 * handle login requests, session timeouts,
 * and redirect to specific admin pages
 */

/// session ///

session_start(); // initiate the session

// if the session has been alive more that 2 minuites
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 120)) {
    error_log('session destroyed: session timed out');
    destroySession();
}


/// GET arguments ///

$action = (isset($_GET['a'])) ? $_GET['a'] : 'login' ; // if a page was requested


/// login handler ///

if ($action == 'auth') { // authenticate a logon request
    # code...
}


/// authentication ///

// this is seperate from the login handeler
// because a user stays logged on, so actions
// can be performed without relogging in


/// admin page selection ///

switch ($action) {
    case 'home': // home page
        # code...
        break;

    case 'individual': // individual summary page
        # code...
        break;

    case 'amend': // information amment page
        # code...
        break;

    case 'login': // display the login screen
    default:
        destroySession();
        require '../pages/adminLogin.php';
        break;
}


/// finishing up ///

$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
