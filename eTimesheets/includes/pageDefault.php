<?php

require '../includes/hslLib.php';

function loginButton($uid, String $error = '')
{
    return '
    <span class="action foreground">
        <span class="action-lable">
            <form action="?p=default&uid=' . $uid . '&act=login" method="post">
                <span class="error">' . $error . '</span></br>
                <input type="password" name="pin" id="pin-input" placeholder="Enter Pin" autofocus>
            </form>
        </span>
    </span>';
}

function hueRotate(string $hex, int $uid)
{
    $hsl    = hexToHsl($hex);
    $hsl[0] = calcHueRotate($uid)/360;

    return hslToHex($hsl);
}

function calcHueRotate(Int $uid) // calculate what value to hue-rotate the user avatar by
{
    $ret = 0;

    // a loop is used to ensure that with high uids the value returned is now massive
    for ($i=1; $i < $uid; $i++) {

        // this value is aproximatly = ((goldenRatio - 1) * 360) / 6
        // so that practicaly every user will have a unique colour scheme
        $ret += 51;

        if ($ret > 360) { // if the value excedes 360 (the largest accepted by hue-rotate) wrap back to 0
            $ret = $ret - 360;
        }
    }

    return $ret;
}
