
<?php include_once("../../../cpfsu.php"); 
    $swit = $_POST['mode'];
    

    if ($swit == 'changeStatus') {
        $changedInvoices = json_decode($_POST['editData']);        
        $pfunc->changeInvoiceStatusData($changedInvoices);
    } else {
        $toEdit = json_decode($_POST['toEdit']);
        $pfunc->adm_invChngStatus($toEdit);
    } ?>
