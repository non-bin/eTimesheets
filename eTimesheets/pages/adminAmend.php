<?php

/**
 * Harry Jacka adminHome.php 0.1 (created 26/7/18)
 *
 * generate a general summary of all eventloyees
 */

/// variable definitions ///

$cycle    = getCycleInfo();

// initiate output variables
$output['title']    = $config['main']['title'] . ' - admin home'; // define the title using the configured prefix
$output['table']    = '';
$output['error']    = '';
$output['selected'] = ['in' => '', 'bl' => '', 'el' => '', 'ou' => ''];


/// output generation ///

if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    header('Location: ?p=admin&a=home');
    die();
}

$event = new Event($id);

$output['selected'][$event->type] = 'selected';

$output['table'] .= '
<tr>
    <td scope="row">
        <div class="form-group">
            <input type="text" class="form-control" name="datetime" value="' . $event->dateTime . '">
        </div>
    </td>
    <td>
        <div class="form-group">
            <select class="form-control" name="type" value=>
                <option value="in" ' . $output['selected']['in'] . '>In</option>
                <option value="bl" ' . $output['selected']['bl'] . '>Begin Lunch</option>
                <option value="el" ' . $output['selected']['el'] . '>End Lunch</option>
                <option value="ou" ' . $output['selected']['ou'] . '>Out</option>
            </select>
        </div>
    </td>
</tr>';

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
            <form action="?p=admin&a=update&id=<?=$event->id ?>&uid=<?=$event->uid ?>" method="post">
                <table class="table table-bordered table-striped" style="width: unset">
                    <thead>
                        <tr>
                            <th>Datetime</th>
                            <th>Event</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?=$output['table'] ?>
                    </tbody>
                </table>
                <div class="btn-toolbar" role="toolbar">
                    <button type="button" onclick="history.back();return false;" class="btn btn-outline-secondary mr-2">Cancel</button>
                    <button type="submit" class="btn btn-outline-danger">Save</button>
                </div>
            </form>
        </div>
    </div>



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js" integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em" crossorigin="anonymous"></script>
</body>
</html>
