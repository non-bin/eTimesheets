<?php

/**
 * Harry Jacka main.php 1.0 (created 25/7/18)
 *
 * contains functions and methods commonly used in
 * multiple main files
 */

function destroySession()
{
    session_unset();   // destroy the contents of $_SESSION
}

function getEmployeeList() // get a list of employee's uids and unames
{
    global $dbc; // get access to the dbc

    $stmt = $dbc->prepare('SELECT * FROM `employees`'); // prepare a request
    $stmt->execute();

    $result = $stmt->get_result();
    if($result->num_rows === 0) return false; // if no matchs were found, something's gome wrong so ret0

    $stmt->close();

    foreach ($result->fetch_all() as $user) { // loop through each user
        $ret[] = [$user[0], $user[1]]; // extract the uid and uname to a return array
    }

    return $ret; // return the list of uids and unames
}

function addAdminUser(String $uname, String $passwd) // add an event as this emp
{
    global $dbc; // get access to the dbc

    $hash = password_hash($passwd, PASSWORD_DEFAULT);

    $stmt = $dbc->prepare('INSERT INTO `eTimesheets`.`admin_users` (`uname`, `passwd`) VALUES (?, ?);');
    $stmt->bind_param('ss', $uname, $hash);
    $stmt->execute();

    if ($stmt->affected_rows == 1) { // if the request worked properly
        return true;
    }

    return false; // return an error
}
