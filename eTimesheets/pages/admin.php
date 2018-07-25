<?php

/// session ///

session_start(); // initiate the session

// if the session has been alive more that 2 minuites
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 120)) {
    error_log('session destroyed: session timed out');
    destroySession();
}


/// GET arguments ///

$adminPage = (isset($_GET['a'])) ? $_GET['a'] : 'login' ; // if a page was requested


/// login handler ///

if ($action == 'auth') { // authenticate a logon request
    # code...
}


/// authentication ///

// this is seperate from the login handeler
// because a user stays logged on, so actions
// can be performed without relogging in




/// admin page selection ///

switch ($adminPage) {
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
        # code...
        break;
}


/// finishing up ///

$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
