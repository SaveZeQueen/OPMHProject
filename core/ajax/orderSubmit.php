<?php session_start(); ?><?php
    include_once("../cpfsu.php");
    $array = json_decode($_POST['itemArray']);
    $pfunc->submitOrder($array, $_POST['custComment']);
    echo "<center><br/><h3>Order Completed!</h3><br/>
    <button class='finishButton' onclick='location.href=`account.php`'>Go to My Account</button>
    <br/>
    <button class='finishButton' onclick='location.href=`order.php`'>Start Another Order</button></center>";
?>
