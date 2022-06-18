<?php
session_start(); ?><html>
<head>
<?php include("core/pageParts/HEAD.php"); ?>
    <style type="text/css" media="print">
@page {
    size: auto;   /* auto is the initial value */
    margin: 0;  /* this affects the margin in the printer settings */
}
}
</style>
</head>
<body class="preload" style='background: #fff !important;'>
    <!--Invoice Data will populate here-->


<?php
    include_once("core/cpfsu.php");
    // Get Id of invoice to display 
    $invid = $_GET['id'];
    // Display Invoice
    $pfunc->displayInvoice($invid, true);
?>
</body>
</html>
