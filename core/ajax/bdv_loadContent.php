<?php session_start(); ?><?php
    include_once("../cpfsu.php");
    $pfunc->getBrandDetails($_POST['bid']);
?>