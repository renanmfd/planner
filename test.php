<?php

$first_day = new DateTime('first day of last month');
$first_day->setTime(0, 0, 0);
$first_day_stamp = $first_day->format('U');
$last_day = new DateTime('last day of last month');
$last_day->setTime(23, 59, 59);
$last_day_stamp = $last_day->format('U');

print 'Test: test.php';
print '<hr>';
print $first_day->format('Y-m-d H:i:s');
print '<hr>';
print $first_day_stamp;
print '<hr>';
print $last_day->format('Y-m-d H:i:s');
print '<hr>';
print $last_day_stamp;

print '<hr>';

print_r($_SESSION);

session_destroy();

print '<hr>';

print_r($_SESSION);
