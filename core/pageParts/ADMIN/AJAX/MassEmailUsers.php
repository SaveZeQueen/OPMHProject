<?php session_start(); ?>
<?php include_once("../../../cpfsu.php"); 
    $swit = $_POST['mode'];
    

    if ($swit == 'sendEmail') {
        $emailedUsers = json_decode($_POST['editData']);        
        $pfunc->sendMassEmail($emailedUsers, $_POST['subject'], $_POST['message']);
    } else {
        $toEdit = json_decode($_POST['toEdit']);
        $pfunc->adm_sendMassEmail($toEdit);
    } ?>
