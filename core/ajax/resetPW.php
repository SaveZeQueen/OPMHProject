<?php session_start(); ?><?php
    include_once("../cpfsu.php");
    $pfunc->updatePassword($_POST['username'], $_POST['email'], $_POST['password']);
  echo "<meta http-equiv='refresh' content='0.25;url=index.php' />";
        header( "refresh:0.25;url=index.php" );
?>
<center><h3>Password Reset, You're being redirected.</h3></center>