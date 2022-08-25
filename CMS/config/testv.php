<?php
$password = "1234567890";
$option = [ "cost" => 15 ];
$pwhash = password_hash($password, PASSWORD_BCRYPT, $option);
echo $pwhash;
?>