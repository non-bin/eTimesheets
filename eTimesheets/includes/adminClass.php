<?php

function getAdminUser(String $uname) // get the uid and password hash of an admin user
{
    global $dbc; // get access to the dbc

    $stmt = $dbc->prepare('SELECT * FROM admin_users WHERE uname = ?'); // prepare a request
    $stmt->bind_param('s', $uname); // bind the uname input to the statment
    $stmt->execute();

    $result = $stmt->get_result();
    if($result->num_rows !== 1) return false; // if 0, or >1 matchs were found, something's gome wrong so ret0

    $stmt->close();

    return $result->fetch_assoc(); // return the row
}
