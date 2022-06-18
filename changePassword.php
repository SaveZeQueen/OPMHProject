<?php
session_start(); ?><!--
    OPMH Design By Luke Clark
-->
<html>
<head>
<?php include("core/pageParts/HEAD.php"); ?>
</head>
<body class="preload">
<div id="content">  
    <?php include("core/pageParts/NAV.php"); ?>
    <?php include("core/pageParts/SIGNINOUT.php"); ?>
<div id="pageContent">
    <?php
            // Get User Logged In
            $user = $pfunc->getSessionUsr();
            if ($user == "" || isset($_GET['username']) == false || isset($_GET['email']) == false){
                 echo "<meta http-equiv='refresh' content='0;url=index.php' />";
            }
if ($user != "" && isset($_GET['username']) == true && isset($_GET['email']) == true && $_GET['username'] == $user){
?>
    <?php include("core/pageParts/CHANGEPASSWORD.php"); ?>
    <?php } else {
    echo "<meta http-equiv='refresh' content='0;url=index.php' />";
    echo "<center><br/><h2>You have entered this page incorrectly you're being redirected</h2></center>";} ?>
</div>
</div>
    
<?php include("core/pageParts/FOOTER.php"); ?>
    
</body>
</html>
