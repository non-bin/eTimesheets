<?php

/**
 * Harry Jacka adminClass.php 0.1 (created 5/7/18)
 *
 * handle every action an admin may perform
 */

die('MAKE THE ADMIN A CLASS!');

function getAdminUser(String $uname)
{
    global $dbc; // get access to the dbc

    $stmt = $dbc->prepare('SELECT * FROM `admin_users` WHERE `uname` = ?'); // prepare a request
    $stmt->bind_param('s', $uname); // bind the uname input to the statment
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows !== 1) {
        return false; // if 0, or >1 matchs were found, something's gome wrong so ret0
    }

    $stmt->close();

    return $result->fetch_assoc(); // return the row
}
