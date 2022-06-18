<?php session_start(); ?>
<?php include_once("../../../cpfsu.php"); 
    $swit = $_POST['mode'];
    

    if ($swit == 'changeStatus') {
        $changedUsers = json_decode($_POST['editData']);        
        $pfunc->changeUserStatusData($changedUsers);
    } else {
        $toEdit = json_decode($_POST['toEdit']);
        $pfunc->adm_usrChngStatus($toEdit);
    } ?>
