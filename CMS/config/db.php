<?php
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'cms';
$DATABASE_PASS = '9hAf9crSQxRz9BZF';
$DATABASE_NAME = 'cms_';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
