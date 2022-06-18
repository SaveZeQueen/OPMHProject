<?php session_start(); ?><?php
    include_once("../cpfsu.php");
    $pfunc->sendPWReset($_POST['username'], $_POST['email'], $_POST['code']);
?>