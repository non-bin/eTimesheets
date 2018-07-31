<?php

/**
 * Harry Jacka adminHome.php 0.1 (created 26/7/18)
 *
 * generate a general summary of all employees
 */

/// variable definitions ///

// initiate output variables
$output['title'] = $config['main']['title'] . ' - admin home'; // define the title using the configured prefix


/// output generation ///

$output['table'] = '';

$empList = getEmployeeList();

foreach ($empList as $emp) {
    $EMHours = $emp->extraHoursInCycle();
    if ($EMHours > 1) { // select the colour for the extra/missed cell
        $EMColour = 'success';
    } elseif ($EMHours < 1) {
        $EMColour = 'danger';
    } else {
        $EMColour = 'info';
    }

    $output['table'] .= '
    <tr>
        <td scope="row"><a href="#">' . $emp->name . '</a></td>
        <td>' . $emp->hoursInCycle() . '</td>
        <td>' . $emp->projectHours() . '</td>
        <td class="table-' . $EMColour . '">' . $EMHours . '</td>
        <td>' . $emp->currentStatus() . '</td>
    </tr>';
}

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
        <a class="breadcrumb-item" href="?p=admin&a=login">Login</a>
        <span class="breadcrumb-item active">Home</span>
    </nav>

    <div class="container">
        <div class="row mt-5">
            <div class="btn-toolbar" role="toolbar">
                <div class="input-group mr-2">
                    <input type="text" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="search">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit" id="search">Go</button>
                    </div>
                </div>

                <div class="btn-group mr-2" role="group">
                    <div class="btn-group" role="group">
                        <button id="sortDropdown" type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Sort By
                        </button>
                        <div class="dropdown-menu" aria-labelledby="sortDropdown">
                            <a class="dropdown-item" href="#">Name</a>
                            <a class="dropdown-item" href="#">Hours This Cycle</a>
                            <a class="dropdown-item" href="#">Predicted Hours</a>
                            <a class="dropdown-item" href="#">Extra/Missed Hours</a>
                            <a class="dropdown-item" href="#">Current Status</a>
                        </div>
                    </div>

                    <a href="" type="button" class="btn btn-outline-secondary">Invert</a>
                </div>

                <a href="?p=admin&a=download" class="btn btn-outline-secondary mr-2">Download</a>
                <a href="?p=admin&a=home" class="btn btn-outline-secondary">Refresh</a>
            </div>
        </div>

        <div class="row mt-3">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Hours This Cycle</th>
                        <th>Projected Hours</th>
                        <th>Extra/Missed Hours</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?=$output['table'] ?>
                </tbody>
            </table>
        </div>
    </div>



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js" integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em" crossorigin="anonymous"></script>
</body>
</html>
