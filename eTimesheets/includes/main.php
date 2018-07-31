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

// todo before 1/1/3001: increase this number   \           /
function getAllEvents($dateBegin = 0, $dateEnd = 32535215999)
{
    global $dbc; // get access to the dbc

    $dateBegin = sqlDateTime($dateBegin);
    $dateEnd   = sqlDateTime($dateEnd);

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

function getCycleInfo(Int $now = null)
{
    defaultTo($now, time()); // if no date was given, use the curent one

    $startDif = $now - $GLOBALS['config']['cycle']['start'];

    while ($startDif > $GLOBALS['config']['cycle']['length']) {
        $startDif -= $GLOBALS['config']['cycle']['length'];
    }

    $endDif   = $GLOBALS['config']['cycle']['length'];
    $begin    = $now - $startDif;
    $end      = $begin + $GLOBALS['config']['cycle']['length'];

    return [
        'begin'    => $begin,
        'end'      => $end,
        'startDif' => $startDif,
        'endDif'   => $endDif
    ];
}

function sqlDateTime(Int $now = null) // get the curent dateTime in mySQL format
{
    defaultTo($now, time()); // if no date was given, use the curent one

    return date("Y-m-d H:i:s", $now);
}

function defaultTo(&$in, $default) // if a variable is null, set it to the given default value
{
    $in = ($in === null) ? $default : $in ;
}
