<?php
    session_start();
    include_once("../cpfsu.php");
    $pfunc->endSession();
    //echo "<meta http-equiv='refresh' content='0.25;url=index.php' />";
header( "refresh:0.25;url=index.php" );
?>
Logout
