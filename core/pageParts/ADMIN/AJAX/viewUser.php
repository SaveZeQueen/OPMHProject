<?php session_start(); ?>
<?php include_once("../../../cpfsu.php"); 

if (isset($_POST['userid'])){
$searchdata = $_POST['userid'];
$pfunc->adm_ViewSelUser($searchdata);
}

    
    ?>


