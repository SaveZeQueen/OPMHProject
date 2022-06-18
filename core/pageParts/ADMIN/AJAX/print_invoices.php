
<?php 
   // include_once("../../../cpfsu.php");
    // Get Id of invoice to display 
    $invid = $_GET['massPrintInvList'];
    // Display Invoice
    foreach ($invid as $id) {
    $pfunc->displayInvoice($id, true, true);
    }
?>





