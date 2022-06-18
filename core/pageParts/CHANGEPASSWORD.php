<div id="pageContent">
    <br/>
    <div class="landingGoal"> Change Password </div>
    <hr style='border-bottom: 2px dotted rgba(0, 0, 0, 0.35);'/>
    <br/>
    <center><h3>Enter your new password</h3>
        <hr style='border-bottom: 2px dotted rgba(0, 0, 0, 0.35); width: 35%; margin-top: 10px; margin-bottom: 10px;'/>
        <input type='hidden' id='user' value='<?php echo $_GET['username']; ?>'/>
        <input type='hidden' id='email' value='<?php echo $_GET['email']; ?>'/>
        <input type='text' class='defTxt' onfocus='clearTextBox(this, true)' name='cp_password' value='Enter Password'/>
    <br/>
    <input type='text' class='defTxt' onkeyup='checkPWMatch(this)' name='cp_passwordConf' onfocus='clearTextBox(this, true)' value='Confirm Password'/>
        <br/>
        <input type='button' class='defBtn' id='cp_SendBtn' disabled value='Update Password' onclick='updatePassword()'/>
        <span id='PHPPROCESSOR'>
        <!--To be filled-->
        </span>
    </center>
</div>
