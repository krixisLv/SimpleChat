<?php
/**
 * Created by PhpStorm.
 * User: Kristaps
 * Date: 11/28/2015
 * Time: 4:12 PM
 */

// simple function that combines current seconds with microseconds for more exact time
function getMicrotime()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec)*100;
}

?>