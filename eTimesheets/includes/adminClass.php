<?php

/**
 * Alice Jacka adminClass.php 1.0 (created 5/7/18)
 *
 * handle every action an admin may perform
 */

class Admin
{
    private $passwdHash; // the password hash should only be accessable to functions inside the class

    public $uname;

    public function __construct(String $uname) {
        global $dbc; // get access to the dbc

        $stmt = $dbc->prepare('SELECT * FROM `admin_users` WHERE `uname` = ?'); // prepare a request
        $stmt->bind_param('s', $uname); // bind the uname input to the statment
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows !== 1) {
            return false; // if 0, or >1 matchs were found, something's gome wrong so ret0
        }

        $stmt->close();

        $this->passwdHash  = $result->fetch_assoc()['passwd'];
        $this->uname       = $uname;
    }

    public function checkPassword(String $password) // check the password against the hash
    {
        if (password_verify($password, $this->passwdHash)) {
            return true;
        }

        return false;
    }
}
