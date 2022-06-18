<?php session_start(); ?>
<h3>Save Changes</h3>

<?php
    include_once("../cpfsu.php");
    if ($pfunc->passConf($pfunc->getSessionUsr(), $_GET['pass']) == false){
        echo "<b id='SAVEVERI'><center style='color: #E94B35;'>Incorrect Password</center></b>";
    } else {
        echo "<b id='SAVEVERI'><center style='color: #1FCE6D;'>Settings Saved</center></b>";
        $pfunc->updateAccount($pfunc->getSessionUsr(), $_GET['email'], $_GET['address'], $_GET['city'], $_GET['state'], $_GET['zip']);
    }
?>
<input type='password' value='' onkeypress='saveBoxPasswordType(event, this)' oninput='saveBoxPasswordType(event, this)' name='scPASS'/>
<input type='button' id="SAVECONFBTN" value='Confirm Changes' onClick='updateActInf()' disabled/>
<input type='button' class='inputCancel' onClick='toggleOverlaySave()' value='Cancel'/>