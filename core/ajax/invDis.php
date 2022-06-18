<?php session_start(); ?><?php
    include_once("../cpfsu.php");
    // Get Id of invoice to display 
    $invid = $_GET['id'];
    // Display Invoice
    $pfunc->displayInvoice($invid);
?>
