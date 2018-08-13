<?php

/**
 * Alice Jacka adminHome.php 1.0 (created 26/7/18)
 *
 * generate a general summary of all employees
 */

/// variable definitions ///

$empTable = [];

// initiate output variables
$output['search']  = '';
$output['title']        = $config['main']['title'] . ' - Admin Home'; // define the title using the configured prefix
$output['sortInverted'] = false;
$output['sortBy']       = 'name';
$output['table']        = '';
$output['error']        = '';


/// output generation ///

if (isset($_GET['search'])) { // if a search is requested
    $output['search'] .= '&search=' . $_GET['search']; // save it
}

$empList = getEmployeeList();
foreach ($empList as $emp) { // create an array of employees to enable sorting before output is generated
    $EMHours = $emp->extraWorkInCycle();
    if ($EMHours > 7200) { // select the colour for the extra/missed cell
        $EMColour = 'success';
    } elseif ($EMHours < -7200) {
        $EMColour = 'danger';
    } else {
        $EMColour = 'info';
    }

    $empTable[] = [$emp->uid, $emp->name, $emp->workInCycle(), $emp->projectWork(), $EMColour, $EMHours, $emp->currentStatus()];
}

/// sort the table ///

// set the sort direction (-1 = inverse)
if (isset($_GET['invert']) && $_GET['invert'] == 1) {
    $direction = -1;
    $output['sortInverted'] = 1;
} else {
    $direction = 1;
    $output['sortInverted'] = 0;
}

if (isset($_GET['sortBy'])) { // set the index of the arrtivute to sort by
    $output['sortBy'] = $_GET['sortBy'];

    switch ($_GET['sortBy']) {
        case 'hours':
            $sortBy = 2;
            break;

        case 'status':
            $sortBy = 6;
            break;

        case 'name':
        default:
            $sortBy = 1;
            break;
    }
} else { // or default to name
    $output['sortBy'] = 'name';
    $sortBy = 0;
}

// if a search is requested
if (isset($_GET['search']) && $_GET['search'] != '') {
    $tmp      = $empTable; // create a temporary backup so that empTable can be re-writen
    $empTable = []; // and empty empTable
    foreach ($tmp as $emp) {
        if (stripos($emp[1], $_GET['search']) !== false) { // if a user matches the search
            $empTable[] = $emp; // add them back to the list
        }
    }
}
if ($empTable === []) { // if no matches were found
    $output['error'] = 'no results found'; // register an error
    $empTable = $tmp; // and put all employees back into the list
}

usort($empTable, function($a, $b) { // sort the array with usort
	return ($a[$GLOBALS['sortBy']] <=> $b[$GLOBALS['sortBy']]) * $GLOBALS['direction']; // accessing the first attribute
});

foreach ($empTable as $emp) { // output each user to the table
    $output['table'] .= '
    <tr>
        <td scope="row"><a href="?p=admin&a=individual&uid=' . $emp[0] . '">' . $emp[1] . '</a></td>
        <td>' . secondsToHoursMins($emp[2]) . '</td>
        <td>' . secondsToHoursMins($emp[3]) . '</td>
        <td class="table-' . $emp[4] . '">' . secondsToHoursMins($emp[5]) . '</td>
        <td>' . $emp[6] . '</td>
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
                <form action="" method="get">
                    <input type="hidden" name="p" value="admin">
                    <input type="hidden" name="a" value="home">
                    <input type="hidden" name="sortBy" value="<?=$output['sortBy'] ?>">
                    <input type="hidden" name="Invert" value="<?=$output['sortInverted'] ?>">
                    <div class="input-group mr-2">
                        <input type="text" name="search" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="search" autofocus>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit" id="search">Go</button>
                        </div>
                        <div class="input-group-append">
                            <a href="?p=admin&a=home&sortBy=<?=$output['sortBy'] ?>&invert=<?=$output['sortInverted'] ?>" class="btn btn-outline-secondary" id="search">Clear</a>
                        </div>
                    </div>
                </form>

                <div class="btn-group mr-2" role="group">
                    <div class="btn-group" role="group">
                        <button id="sortDropdown" type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Sort By
                        </button>
                        <div class="dropdown-menu" aria-labelledby="sortDropdown">
                            <a class="dropdown-item" href="?p=admin&a=home&sortBy=name&invert=0<?=$output['search'] ?>">Name</a>
                            <a class="dropdown-item" href="?p=admin&a=home&sortBy=hours&invert=0<?=$output['search'] ?>">Hours This Cycle</a>
                            <a class="dropdown-item" href="?p=admin&a=home&sortBy=status&invert=0<?=$output['search'] ?>">Current Status</a>
                        </div>
                    </div>

                    <a href="?p=admin&a=home&sortBy=<?=$output['sortBy'] ?>&invert=<?=($output['sortInverted'] == 1) ? 0 : 1 ; ?><?=$output['search'] ?>" type="button" class="btn btn-outline-secondary">Invert</a>
                </div>

                <a href="?p=admin&a=home&sortBy=<?=$output['sortBy'] ?>&invert=<?=$output['sortInverted'] ?><?=$output['search'] ?>" class="btn btn-outline-secondary">Refresh</a>

                <span class="error"><?=$output['error'] ?></span>
            </div>
        </div>

        <div class="row mt-3">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Hours This Cycle</th>
                        <th>Projected Hours</th>
                        <th>Over/Under Time</th>
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
