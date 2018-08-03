<?php

ob_start();

require '../config.php';

$dbc = new mysqli($config['sql']['addr'], $config['sql']['uname'], $config['sql']['passwd']);

// Check connection
if ($dbc->connect_error) {
    die('<span style="color: red;">Connection to SQL server failed: ' . $dbc->connect_error . '</span>');
}

if (isset($_GET['createDB'])) {
    if ($dbc->query('CREATE DATABASE `eTimesheets` /*!40100 DEFAULT CHARACTER SET utf8 */;') !== true) {
        echo 'somet\' went wrong creating the database<br>';
    }
    if ($dbc->query('CREATE TABLE `eTimesheets`.`admin_users` (
        `uid` int(11) NOT NULL AUTO_INCREMENT,
        `uname` varchar(45) NOT NULL,
        `passwd` varchar(100) NOT NULL,
        PRIMARY KEY (`uid`,`uname`),
        UNIQUE KEY `uid_UNIQUE` (`uid`),
        UNIQUE KEY `uname_UNIQUE` (`uname`)
      ) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
      ') !== true) {
        echo 'sorry, I couldn\'t create the admin_users table<br>';
    }
    if ($dbc->query('CREATE TABLE `eTimesheets`.`employees` (
        `uid` int(11) NOT NULL AUTO_INCREMENT,
        `uname` varchar(45) NOT NULL,
        `pin` varchar(45) NOT NULL,
        `name` varchar(45) NOT NULL,
        PRIMARY KEY (`uid`,`uname`),
        UNIQUE KEY `uname_UNIQUE` (`uname`),
        UNIQUE KEY `uid_UNIQUE` (`uid`)
      ) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
      ') !== true) {
        echo 'now the employees table wont work either :(<br>';
    }
    if ($dbc->query('CREATE TABLE `eTimesheets`.`timesheet` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `uid` int(11) NOT NULL,
        `datetime` datetime NOT NULL,
        `event` varchar(2) NOT NULL,
        PRIMARY KEY (`id`,`uid`,`datetime`,`event`)
      ) ENGINE=InnoDB AUTO_INCREMENT=154 DEFAULT CHARSET=utf8;
      ') !== true) {
        echo 'I put too much effort into something no one should see... oh yeah the timesheets one failed too<br>';
    }
}

if (isset($_GET['addEmp'])) {
    if ($dbc->query('INSERT INTO `eTimesheets`.`employees` (`uname`, `pin`, `name`) VALUES (\'' . $_POST['uname'] . '\', \'' . $_POST['pin'] . '\', \'' . $_POST['name'] . '\');') !== true) {
        echo 'I\'m really sad, cos it\'s midnight and I was about to submit this exacly on time, then I accidently deleted this file, so now im up late, and this is going to submited late :(. and I couldn\'t create the employee either.<br>';
    }
}

$output = ob_get_clean();

?>

<!doctype html>
<html lang="en">
<head>
    <title>eTimesheets Initialization</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">
</head>
<body>

    <div class="container mt-5">
        I've already writen this entire file once so this will be much less enthusiactic than I orrigonaly planed. <br>
        press <a href="?createDB">here</a> to make the database,<br><br>

        then make some eployees here (the colour for employee id 2 is terrible, the rest are ok. just keep that in mind yeah?) <br>
        <form action="?addEmp" method="post">
            <input type="text" class="form-control" name="uname" style="display: unset; width: unset;" placeholder="Username (eg alexj)">
            <input type="text" class="form-control" name="pin" style="display: unset; width: unset;" placeholder="Pin (eg 1234)">
            <input type="text" class="form-control" name="name" style="display: unset; width: unset;" placeholder="Full Name (eg Alex Jones)">
            <button type="submit" class="btn btn-outline-dark">Create</button>
        </form><br>

        <?=$output ?>

    </div>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js" integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em" crossorigin="anonymous"></script>
</body>
</html>
