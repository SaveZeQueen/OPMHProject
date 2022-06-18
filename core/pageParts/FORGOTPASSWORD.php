<div id='INNERCONTENT'>
<div id="pageContent">
    <br/>
    <div class="landingGoal"> Can't access your account? </div>
    <hr style='border-bottom: 2px dotted rgba(0, 0, 0, 0.35);'/>
    <br/>
    <center><h3>Don't worry, there is a simple solution to fix this.</h3>
    <br/><hr style='border-bottom: 2px dotted rgba(0, 0, 0, 0.35); width: 75%;'/><br/>
        
    <h4>Enter your username, and email address, then press <i>Send Reset Code</i>. <br/>We will email you a reset code to recover your account.
    <br/>
        <hr style='border-bottom: 2px dotted rgba(0, 0, 0, 0.35); width: 40%; margin-top: 10px; margin-bottom: 10px;'/>
        <input type='text' class='defTxt' onkeyup='setBtnActive()' onfocus='clearTextBox(this)' name='fp_Username' value='Enter username'/>
    <br/>
    <input type='text' class='defTxt' onkeyup='setBtnActive()' name='fp_Email' onfocus='clearTextBox(this)' value='Enter email address'/>
        <br/>
        <input type='button' class='defBtn' id='fp_SendBtn' disabled value='Send Reset Code' onclick='sendResetCode()'/>
        <hr style='border-bottom: 2px dotted rgba(0, 0, 0, 0.35); width: 40%; margin-top: 10px; margin-bottom: 10px;'/>
        Once it has been sent just enter it in the code box at the bottom of this page. <h5>(Note: make sure not to leave this page or the code won't work and you will have to try again)</h5></h4>
        <span id='PHPPROCESSOR' style='visibility: hidden;'>
        <!--To be filled-->
        </span>
        <hr style='border-bottom: 2px dotted rgba(0, 0, 0, 0.35); width: 30%; margin-top: 10px; margin-bottom: 10px;'/>
        <input type='text' name='fp_Code' disabled onfocus='clearTextBox(this)' class='defTxt' style='border-top-right-radius: 0px; border-bottom-right-radius: 0px; height: 64px; width: 256px; line-height: 32px;' value='Code'/><input type='button' id='fpSubBTN' disabled style='width: 128px; height: 64px; border-top-left-radius: 0px; border-bottom-left-radius: 0px;' class='defBtn' value="Submit" onclick='verifyCode()'/>
    <br/>
    <br/>
        <h6><i>If you're still having problems please use the <a href='contact.php' target="_blank">contact us</a> page, to get in contact with one of us at OPMH.</i></h6>
    </center>
</div>
</div>