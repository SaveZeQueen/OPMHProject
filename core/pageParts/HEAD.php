<!-- Page Data -->

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Access-Control-Allow-Origin" content="*">
<header name = "Access-Control-Allow-Origin" value = "*" />
<title>OPMH</title>
<meta name="keywords" content="" />
<meta name="description" content="" />

<!-- link REL="SHORTCUT ICON" HREF="images/sitcon.ico" -->
<!-- Stylesheets -->
<link href="styles/main.css" rel="stylesheet" type="text/css" />
<!--// Target ios browsers.-->
<link rel="apple-touch-icon" sizes="180x180" href="images/logo.ico">
<!--// Target safari on MacOS.-->
<link rel="icon" type="image/png" sizes="32x32" href="images/logo.ico">
<!--// The classic favicon displayed in tabs.-->
<link rel="icon" type="image/png" sizes="16x16" href="images/logo.ico">
<!--// Used for Safari pinned tabs.-->
<link rel="mask-icon" href="images/logo.ico" color="#ffffff">
<!--// Target older browsers like IE 10 and lower.-->
<link rel="icon" href="images/logo.ico">
<link href="https://fonts.googleapis.com/css?family=Open+Sans|Open+Sans+Condensed:300|Roboto|Ubuntu|Anton|Zilla+Slab" rel="stylesheet">
<!-- Scripts -->
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/wfrem.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js"></script>
</head>
 <?php require_once("core/cpfsu.php"); ?>
<?php

    if (isset($_POST['DOB'])){
        if ($_POST['DOB'] != ""){
            $setDOBArray = json_decode($_POST['DOB']);
            $pfunc->setAge($setDOBArray);
        }
    }

    // Check if Age is 18+
    $ofage = $pfunc->getAgeVerified();

    if ($ofage == false){   ?>
        
        <div id='ageVari'>
            <img src='images/Header.png'/>
            <p>The products and services on this website are intended for adults who are of the legal smoking age (18+) only. Please enter your date of birth and click confirm if you're of legal smoking age.</p>
            <br/>
        <form action="index.php" method='post' id='POSTFORM'>
        <div class="inputTriSplit">
        <!--Month-->
        <select id='DOBMONTH'>
        <option selected disabled hidden>Month</option>
        <?php 
            for ($m=1; $m<=12; $m++) {
        echo '<option value="' . $m . '">' . date('M', mktime(0,0,0,$m)) . '</option>' . PHP_EOL;
            }
            ?>
        </select>
        <!--Day-->
        <select id='DOBDAY'>
        <option selected disabled hidden>Day</option>
        <?php 
           for($r = 1; $r <= 31; $r++){
              echo "<option>$r</option>";
           }
        ?>
        </select>
        <!--Year-->
        <select id='DOBYEAR'>
        <option selected disabled hidden>Year</option>
        <?php 
           for($i = date('Y') ; $i > date('Y')-117 ; $i--){
              echo "<option value='{$i}'>$i</option>";
           }
        ?>
        </select>  
        </div>
        <input type='hidden' name='DOB' id='DOBCH' value=""/>
        <input type='submit' onclick='verifyDOB()' value='Confirm'/>
            </form>
        </div>

<?php
    }

?>