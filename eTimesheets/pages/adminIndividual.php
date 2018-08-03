<?php

/**
 * Harry Jacka adminIndividual.php 1.0 (created 02/08/18)
 *
 * generate a general summary of a single employee
 */

/// variable definitions ///

$empTable = [];
$cycle    = getCycleInfo();

// initiate output variables
$output['title']        = $config['main']['title'] . ' - Admin Individual'; // define the title using the configured prefix
$output['infoTable']    = '';
$output['eventTable']   = '';
$output['error']        = '';
$output['cycleBegin']   = '';
$output['cycleEnd']     = '';


/// output generation ///

$output['cycleBegin'] = date("d/m/Y", $cycle['begin']);
$output['cycleEnd']   = date("d/m/Y", $cycle['end']);

$empList = getEmployeeList(); // get a list of valid employee uids
foreach ($empList as $emp) {
    $empIds[] = $emp->uid;
}
if (isset($_GET['uid']) && in_array($_GET['uid'], $empIds)) { // if a uid was requested and it exists
    $uid = $_GET['uid'];
} else {
    header('Location: ?p=admin&a=home'); // redirect to home
    die('invalid uid, please go back to <a href="?p=admin&a=home">home</a>');
}

$emp = new Employee($uid); // create an instance for the employee

$EMHours = $emp->extraWorkInCycle();
if ($EMHours > 7200) { // select the colour for the extra/missed cell
    $EMColour = 'success';
} elseif ($EMHours < -7200) {
    $EMColour = 'danger';
} else {
    $EMColour = 'info';
}

$empOutput = [$emp->uid, $emp->name, $emp->workInCycle(), $emp->projectWork(), $EMColour, $EMHours, $emp->currentStatus()];

$output['infoTable'] .= '
<tr>
    <td scope="row"><a href="?p=admin&a=individual&uid=' . $empOutput[0] . '">' . $empOutput[1] . '</a></td>
    <td>' . secondsToHoursMins($empOutput[2]) . '</td>
    <td>' . secondsToHoursMins($empOutput[3]) . '</td>
    <td class="table-' . $empOutput[4] . '">' . secondsToHoursMins($empOutput[5]) . '</td>
    <td>' . $empOutput[6] . '</td>
</tr>';

$events = $emp->eventsInCycle(); // get all events from the emp, and reverse them, because they are retrived in the wrong order
if ($events) { // if the employee has logged any events
    $events = array_reverse($events);

    foreach ($events as $event) { // translate the event type into something nicer
        switch ($event->type) {
            case 'in':
                $type = 'In';
                break;

            case 'ou':
                $type = 'Out';
                break;

            case 'bl':
                $type = 'Begin Lunch';
                break;

            case 'el':
                $type = 'End Lunch';
                break;
        }

        $output['eventTable'] .= '
        <tr>
            <td scope="row"><a href="?p=admin&a=amend&id=' . $event->id . '">' . $event->dateTime . '</a></td>
            <td>' . $type . '</td>
        </tr>';
    }
} else {
    $output['eventTable'] .= '
    <tr>
        <td scope="row" colspan="2">No Events</td>
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
        <a class="breadcrumb-item" href="?p=admin&a=home">Home</a>
        <span class="breadcrumb-item active">Individual</span>
    </nav>

    <div class="container">
        <div class="row mt-5">
            <div class="btn-toolbar" role="toolbar">
                <a href="?p=admin&a=home" class="btn btn-outline-secondary mr-2">Back</a>
                <a href="?p=admin&a=individual&uid=<?=$uid ?>" class="btn btn-outline-secondary">Refresh</a>

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
                        <th>Over/Uner Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?=$output['infoTable'] ?>
                </tbody>
            </table>
        </div><br>

        <h2>Current Cycle (<?=$output['cycleBegin'] ?> - <?=$output['cycleEnd'] ?>)</h2>
        <div class="row mt-3">
            <table class="table table-bordered table-striped" style="width: unset">
                <thead>
                    <tr>
                        <th>Datetime</th>
                        <th>Event</th>
                    </tr>
                </thead>
                <tbody>
                    <?=$output['eventTable'] ?>
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
