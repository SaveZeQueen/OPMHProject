<div id="accountOverlay">

    <div class="loginBox">
        <div class="loginContent" id="LOGINCONTENT">
            <button onclick="openCloseLogin()">X</button>
            <h5>Sign In</h5>
            <!--Input Field-->
            <label>Username</label>
            <input type="text" id="LOGUSERNAME" value="Enter your username" onkeypress="subimLoginForm(event)" onfocus="clearUsernameBox(this)"/>
            <!--Input Field-->
            <label>Password</label>
            <input type="text" id="LOGPASSWORD" onkeypress="subimLoginForm(event)" onfocus="clearPasswordBox(this)" value="Enter your password"/>
            <br/>
            <center><a href="forgotPassword.php">Forgot Password</a></center>
            <br/>
            <input type="submit" value="Sign In" onclick="loginUserAJ()"/>
        </div>
    </div>
    
    <div class="loadingBox">
    <div class="loadingContent">
        <h2>Loading</h2>
        <img src="images/Double%20Ring.svg"/>
    </div>
    </div>
    
    <div class="registerBox">
        <!--Left Box displaying Helpful information-->
        <div class="registerBox_Left">
              <img src="images/favorite_WHITE.svg"/>
            <br/>
            <p><b>Let's Take a moment</b><br/><br/>
            To thank you for deciding to join Us.<br/> we here at <i>OPMH</i> strive to make the ordering experince quick, and easy with our custom ordering platform. Plus when you become a member you get instant access to <i>All</i> of your order history.</p>
        </div>
        <!--Right box displays Register Info as well as the exit button-->
        <div class="registerBox_Right">
            
        <button onclick="openCloseRegister()">X</button>
        <h5>Sign Up</h5>
        <div class="registerForm" id='REGFORM'>
            
            
        <div class="regFormleft">
        <!--Input Field-->
        <label id='REG_USERNAME'>Username</label>
        <input type="text" onfocus='clearTextBox(this)'  name='reg_userName' value="Enter a username" style="margin-bottom: 1px;"/>
        <div class="inputSplit">
        <div class="splitLeft">
        <!--Input Field-->
        <label id='REG_FIRSTNAME'>First Name</label>
        <input type="text" onfocus='clearTextBox(this)' name='reg_firstName' name='firstName' onClick='clearTextIfFirst(this)' value="What is your first name?"/>
        </div>
        <!--Input Field-->
        <div class="splitRight">
        <label id='REG_LASTNAME'>Last Name</label>
        <input type="text" onfocus='clearTextBox(this)' name='reg_lastName' value="And your last name?"/>
        </div>
        </div>
        <div class="inputSplit">
        <div class="splitLeft">
        <!--Input Field-->
        <label id='REG_PASSWORD'>Password</label>
        <input type="text" onfocus='clearTextBox(this, true)' name='reg_password' value="Enter a password"/>
        </div>
        <!--Input Field-->
        <div class="splitRight">
        <label id='REG_CONFPASSWORD'>Confirm Password</label>
        <input type="text" onfocus='clearTextBox(this, true)' name='reg_confPassword' value="One more time please"/>
        </div>
        </div>
            
        <div class="inputSplit">
        <div class="splitLeft">
        <!--Input Field-->
        <label id='REG_EMAIL'>Email</label>
        <input type="text" onfocus='clearTextBox(this)' name='reg_email' value="Enter your email"/>
        </div>
        <!--Input Field-->
        <div class="splitRight">
        <label id='REG_CONFEMAIL'>Confirm Email</label>
        <input type="text" onfocus='clearTextBox(this)' name='reg_confEmail' value="Once more please"/>
        </div>
        </div>
        <!--Input Field-->
        <label id='REG_COMPANYNAME'>Company Name</label>
        <input type="text" onfocus='clearTextBox(this)' name='reg_companyName' value="What is your business called?"/>
        <!--Input Field-->
        <label id='REG_PHONENUMBER'>Phone Number</label>
        <input type="text" onfocus='clearTextBox(this)' maxlength='10' onkeypress='return event.charCode >= 48 && event.charCode <= 57' name='reg_phoneNumber' value="Enter Phone Number"/>
        <!--Input Field-->
        <label id='REG_FAXNUMBER'>Fax Number (If available)</label>
        <input type="text" onfocus='clearTextBox(this)' maxlength='10' onkeypress='return event.charCode >= 48 && event.charCode <= 57' name='reg_faxNumber' value="Enter Fax Number"/>
        </div>
            
        <div class="regFormright">
        <!--Input Field-->
        <label id='REG_ADDRESS'>Address</label>
        <input type="text" onfocus='clearTextBox(this)' name='reg_streetAddress' value="Enter your street address"/>
        <!--Input Field-->
        <label id='REG_CITY'>City</label>
        <input type="text" onfocus='clearTextBox(this)' name='reg_city' value="Enter the city your business is located"/>
        <!--Input Split-->
        <div class="inputSplit">
        <div class="splitLeft">
        <!--Input Field-->
        <label id='REG_STATE'>State</label>
        <select name='reg_state'>
        <option value='empty' selected disabled hidden>Select a State</option>
        <?php $pfunc->getStates(); ?>
        </select>
        </div>
        <div class="splitRight">
        <!--Input Field-->
        <label id='REG_ZIPCODE'>Zip Code</label>
        <input type="text" onfocus='clearTextBox(this)' maxlength='5' name='reg_zipCode' onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="Enter your Zip Code"/>
        </div>
        </div>
        <!--Input Field-->
        <label id='REG_BIRTHDAY'>Birthdate (must be 18+)</label>
        <div class="inputTriSplit">
        <!--Month-->
        <select name='reg_birthMonth'>
        <option value='empty' selected disabled hidden>Month</option>
        <?php 
        $sel_mnth = 0;
            for ($m=1; $m<=12; $m++) {
                
    if($sel_mnth == $m)
        echo '  <option value="' . $m . '">' . date('M', mktime(0,0,0,$m)) . '</option>' . PHP_EOL;
    else   
        echo '  <option value="' . $m . '">' . date('M', mktime(0,0,0,$m)) . '</option>' . PHP_EOL;
}
            ?>
        </select>
        <!--Day-->
        <select name='reg_birthDay'>
        <option value='empty' selected disabled hidden>Day</option>
        <?php 
           for($r = 1; $r <= 31; $r++){
              echo "<option value='{$r}'>{$r}</option>";
           }
        ?>
        </select>
        <!--Year-->
        <select name='reg_birthYear'>
        <option value='empty' selected disabled hidden>Year</option>
        <?php 
           for($i = date('Y')-17 ; $i > date('Y')-100 ; $i--){
              echo "<option value='{$i}'>$i</option>";
           }
        ?>
        </select>  
        </div>
        <!--Terms-->
        <label>Terms Of Service</label>
       <center style="font-size: 12px;"> <input type="checkbox" name='reg_termsAgree' onclick='enableDisableReg()'/> By Checking this box you hereby agree to and have read the <a style='cursor: pointer;' onclick="window.open(`core/ajax/termsandConditions.php`, `_blank`, `location=yes,height=960,width=720,scrollbars=yes,status=yes`);">Terms and Conditions Of Service</a>.
        <br/>
        Once registration is complete a verification email will be sent to you.</center>
        <br/>
        <input type="submit" onclick='signUpAccount()' name='submitButton' value="Submit" disabled/>
        </div>
        </div>
        </div>
    </div>
    
</div>