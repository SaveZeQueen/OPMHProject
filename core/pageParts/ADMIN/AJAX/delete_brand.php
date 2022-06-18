<?php session_start(); ?>
<?php include_once("../../../cpfsu.php"); 

    $array = json_decode($_POST['todel']);
    $pfunc->deleteBrands($array);
    ?>


