<?php
session_start(); ?><!--
    OPMH Design By Luke Clark
-->
<html>
    
<head>
<?php include("core/pageParts/HEAD.php"); ?>
<script type="text/javascript" src="core/pageParts/ADMIN/js/admpjs.js"></script>
<style type="text/css" media="print">
@page {
    size: auto;   /* auto is the initial value */
    margin: 0;  /* this affects the margin in the printer settings */
}
}
</style>
</head>
    
<body class="preload" style='background: #fff !important;'>
<?php 
    include("core/pageParts/ADMIN/AJAX/print_invoices.php"); 
    ?>  
</body>
</html>