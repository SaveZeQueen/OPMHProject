<?php session_start(); ?>
<?php include_once("../../../cpfsu.php"); 

$searchdata = $_POST['search'];
$pfunc->adm_GetInvoiceList($searchdata);


    
    ?>


