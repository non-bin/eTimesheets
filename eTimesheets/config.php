<?php

/**
 * Harry Jacka config.php 1.0 (created 27/6/18)
 *
 * set config variable attributes for use throughout the program
 */

$config['main']['title'] = 'eTimesheets'; // custon title (eg page titles, headings etc)

/// MySQL database ///
$config['sql']['addr']   = 'localhost';   // server address
$config['sql']['uname']  = "application"; // username and
$config['sql']['passwd'] = "klNIdhU(75^"; // password of user
$config['sql']['db']     = 'eTimesheets'; // datamase name

/// random ///

// time untill session timeout, in seconds
// default 120 (2 minuites)
// set to 0 to disable timeouts
$config['misc']['sesTimeout'] = 120;

?>
