<?php session_start(); ?><?php
    include_once("../cpfsu.php");
    $pfunc->sendInvoiceEmail($pfunc->getSessionUsr());
?>
<img src='images/verified_BLK.svg' class='notvalidated'/>Verification Email Sent.