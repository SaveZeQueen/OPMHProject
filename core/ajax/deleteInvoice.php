<?php session_start(); ?><?php
    include_once("../cpfsu.php");
    // Get Id of invoice to display 
    if (isset($_POST['invoiceID'])){
    // Display Invoice
    $pfunc->deleteInvoice($_POST['invoiceID']);
    }
?>
