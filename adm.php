<?php
session_start(); ?><!--
    OPMH Design By Luke Clark
-->
<html>
    
<head>
<?php include("core/pageParts/HEAD.php"); ?>
<script type="text/javascript" src="core/pageParts/ADMIN/js/admpjs.js"></script>
</head>
    
<body class="preload">
<?php 
    // Get Group and check if admin or Mod
    $ugrp = $pfunc->getUserGroup();
    if ($ugrp == "ADM" || $ugrp == "MOD"){
    // Get Page type
    $page = $_GET['p'];
    
        if ($page == 'dashboard'){
            include("core/pageParts/ADMIN/DASHBOARD.php"); 
        } else if ($page == 'manage_users'){
            include("core/pageParts/ADMIN/MANAGE_USERS.php"); 
        } else if ($page == 'brand_products'){
            include("core/pageParts/ADMIN/BRANDS_PRODUCTS.php"); 
        } else if ($page == 'invoices'){
            include("core/pageParts/ADMIN/MANAGE_INVOICES.php"); 
        } else {
            include("core/pageParts/ADMIN/DASHBOARD.php"); 
        }
    } else {
         echo "<meta http-equiv='refresh' content='0;url=index.php' />";
    }
    ?>  
</body>
</html>