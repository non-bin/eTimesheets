<?php

require '../includes/pageDefault.php';

if (isset($_GET['uid'])) {
    $uid = $_GET['uid'];
} else {
    $uid = 1;
}

$output['title'] = $config['main']['title'] . ' - home'; // define title

$output['style']  = '';
$output['header'] = '';
$output['main']   = '';
$output['footer'] = '';
$output['action'] = '';


$tmp = hexToHsl('ffc6c6');
$tmp[0] = calcHueRotate($uid)/360;
$output['style'] .= 'body { background-color: #' . hslToHex($tmp) . '; }';

$empList = getEmployeeList();
foreach ($empList as $emp) {
    $output['header'] .= '
    <a class="avatar" href="?p=default&uid= ' . $emp[0] . '">
        <img class="avatar" style="filter: hue-rotate(' . calcHueRotate($emp[0]) . 'deg)" src="./img/avatar.png" alt="username">
    </a>';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?=$output['title'] ?></title>
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/default.css">
    <?='<style>' . $output['style'] . '</style>' ?>
</head>
<body>

<span class="header">
    <?=$output['header'] ?>
</span>

<span class="main">
    <a class="avatar" href="?p=default&uid=<?=$uid ?>&act=<?=$output['action'] ?>">
        <span class="action">
            <span class="action-lable">Log In</span>
        </span>
</span>

<span class="footer">
    <?=$output['footer'] ?>
</span>

</body>
