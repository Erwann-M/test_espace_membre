<?php

session_start();

$_SESSION = array();
session_destroy();

if (isset($_COOKIE['pseudo']) && isset($_COOKIE['pass'])) {
    setcookie('pseudo', '');
    setcookie('pass', '');
}

header('Location:connection.php');

?>