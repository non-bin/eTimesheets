<?php

/**
 * Harry Jacka pageDefault.php 2.0 (created 16/7/18)
 *
 * contains functions and methods for the default page
 */

require '../includes/hslLib.php';

function hueRotate(String $hex, int $uid) // rotate the hue of a hex colour for a
{
    $hsl    = hexToHsl($hex);          // convert to hsl
    $hsl[0] = calcHueRotate($uid)/360; // rotate the hue

    return hslToHex($hsl); // convert back to hex and return
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
