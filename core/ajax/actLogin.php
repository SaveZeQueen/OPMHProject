<?php
session_start();
    include_once("../cpfsu.php");
    $loginerr = $pfunc->loginUser($_GET['username'], $_GET["pass"]);
    if ($loginerr == true){
       // echo "<meta http-equiv='refresh' content='0.5;url=index.php' />";
        header( "refresh:0.5;url=index.php" );
        echo "
        <center>
    <div class='loadingContent'>
    <input type='hidden' value='DONE' id='LOGINCHK'/>
        <h2>Loading</h2>
        <img src='images/Double%20Ring.svg'/>
        </center>
    </div>";
    } else {?>
            <button onclick="openCloseLogin()">X</button>
            <h5>Sign IN</h5>
            <center style="color: #E94B35; position: absolute;"><i>Incorrect username or password</i></center>
            <!--Input Field-->
            <label>Username</label>
            <input type="text" id="LOGUSERNAME" onkeypress="subimLoginForm(event)" onfocus="clearUsernameBox(this)" value="<?php echo $_GET['username']; ?>"/>
            <!--Input Field-->
            <label>Password</label>
            <input type="password" id="LOGPASSWORD" onkeypress="subimLoginForm(event)" onfocus="clearPasswordBox(this)" value="<?php echo $_GET['pass']; ?>"/>
            <br/>
            <center><a href="forgotPassword.php">Forgot Password</a></center>
            <br/>
            <input type="submit" value="Sign In" onclick="loginUserAJ()"/>
<input type='hidden' value='INCO' id='LOGINCHK'/>
    <?php }
?>
