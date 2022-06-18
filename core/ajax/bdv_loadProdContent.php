<?php session_start(); ?><?php
    include_once("../cpfsu.php");
    $pfunc->getBrandDetProd($_POST['sku']);
?>