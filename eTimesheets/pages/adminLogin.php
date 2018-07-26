<?php

/**
 * Harry Jacka adminLogin.php 0.1 (created 25/7/18)
 *
 * serve the logon page for the admin portal
 */

/// variable definitions ///

// initiate output variables
$output['title'] = $config['main']['title'] . ' - admin'; // define the title using the configured prefix

?>

<!doctype html>
<html lang="en">
<head>
    <title><?=$output['title'] ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/admin.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">
</head>
<body>
    <nav class="breadcrumb">
        <a class="breadcrumb-item" href="?p=default">Default</a>
        <span class="breadcrumb-item active">Login</span>
    </nav>
    <form action="?p=admin&a=auth" method="post">
        <span class="center">
            <span class="login">
                <h1>Admin Portal Login</h1>

                <span>
                    <label class="sr-only" for="inlineFormInputGroup">Username</label>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">:)</div>
                        </div>
                        <input name="uname" type="text" class="form-control" id="inlineFormInputGroup" placeholder="Username" autofocus>
                    </div>
                </span>
                <span>
                    <label class="sr-only" for="inlineFormInputGroup">Password</label>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">#</div>
                        </div>
                        <input name="password" type="password" class="form-control" id="inlineFormInputGroup" placeholder="Password">
                    </div>
                </span>
                <button type="submit" class="btn btn-outline-dark mb-2">Login</button>
                <a href="?p=admin&a=signUp" class="btn btn-outline-dark mb-2">Sign Up</a><br>
                <span class="error"><?=$output['error'] ?></span>
            </span>
        </span>
    </form>



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js" integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em" crossorigin="anonymous"></script>
</body>
</html>
