<?php
session_start(); ?><!--
    OPMH Design By Luke Clark
-->

<html>
<head>
<?php require_once("core/pageParts/HEAD.php"); 
    ?>
</head>
<body class="preload">
<div id="content">  
    <?php require_once("core/pageParts/NAV.php"); ?>
    <?php require_once("core/pageParts/SIGNINOUT.php"); ?>
<div id="pageContent">
    <?php require_once("core/pageParts/ABOUT.php"); ?>
</div>
</div>
    
<?php include("core/pageParts/FOOTER.php"); ?>
    
</body>
</html>