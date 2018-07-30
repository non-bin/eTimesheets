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
        $ret[] = new Employee($user[0]);
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

function getAllEvents($dateBegin = '0000-00-00 00:00:00', $dateEnd = '9999-12-31 23:59:59')
{
    global $dbc; // get access to the dbc

    $stmt = $dbc->prepare('SELECT * FROM `timesheet` `datetime` between ? AND ? ORDER BY `datetime`;'); // prepare a request
    $stmt->bind_param('ss', $dateBegin, $dateEnd);
    $stmt->execute();

    $result = $stmt->get_result();
    if($result->num_rows === 0) return false; // if no matchs were found, something's gome wrong so ret0

    $stmt->close();

    foreach ($result->fetch_all() as $row) { // loop through each row
        $ret[] = new Event($row[0], $row[1], $row[2], $row[3]); // and create an event object for each event found
    }

    return $ret; // return the array of events
}

function getSycleDates(String $date = null)
{
    $date = ($date === null) ? sqlDateTime() : $date ; // if no date was given, use the curent one
}

function sqlDateTime() // get the curent dateTime in mySQL format
{
    return date("Y-m-d H:i:s");
}
