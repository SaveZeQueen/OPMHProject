<?php session_start(); ?>
<?php include_once("../../../cpfsu.php"); 

if (isset($_POST['search'])){
$searchdata = $_POST['search'];
$pfunc->populateUserList($searchdata);
}

    
    ?>


