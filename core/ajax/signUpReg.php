<?php session_start(); ?><?php
    include_once("../cpfsu.php");
    if($_POST['mode'] == 'check'){
        
        $data = [$_POST['userName'], $_POST['firstName'], $_POST['lastName'], $_POST['password'], $_POST['confPassword'], $_POST['email'], $_POST['confEmail'], $_POST['companyName'], $_POST['phoneNumber'], $_POST['faxNumber'], $_POST['streetAddress'], $_POST['city'], $_POST['state'], $_POST['zipCode'], $_POST['month'], $_POST['day'], $_POST['year']];
        
        $pfunc->checkReg($data);
    } else {
        // Data
        $data = [$_POST['userName'], $_POST['firstName'], $_POST['lastName'], $_POST['password'], $_POST['email'], $_POST['companyName'], $_POST['phoneNumber'], $_POST['faxNumber'], $_POST['streetAddress'], $_POST['city'], $_POST['state'], $_POST['zipCode'], $_POST['month'], $_POST['day'], $_POST['year']];
        
        $pfunc->createAccount($data);
                  
    }
?>
