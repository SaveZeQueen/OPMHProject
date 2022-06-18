<div id="nav">
<div class="nav_mobile">
    <a href="index.php"><img src="images/Logo_002.png" class="navLogo"/></a>

<div class='socialMediaBox'>
<a href='https://www.instagram.com/opmhproject/' title='Instagram' target="_blank"><img src='images/socialMedia_Instagram.svg'/></a>   
<a href='https://www.facebook.com/OPMHProject/' title='Facebook' target="_blank"><img src='images/socialMedia_Facebook.svg'/></a>   
</div>
    
<ul>
<a href="index.php"><li>About Us</li></a>
<a href="brands.php"><li>Brands</li></a>
<?php if ($pfunc->sessionSet() == true){ ?>
<a href="order.php"><li>Order Now</li></a>
<?php } ?>
<a href="contact.php"><li>Contact Us</li></a>
</ul>
<div class="login">
<?php if ($pfunc->sessionSet() == true){ ?>
<h5>Welcome Back! <?php echo $pfunc->getSessionUsr(); ?></h5>
<img src="images/myAccount.svg"/><a href="account.php">Account</a> | <a id="LOGOUT" href="#" onclick="logoutUserAJ()">Logout</a>
<?php } else { ?>
<a href="#" onclick="openCloseLogin()">LOGIN</a> | <a href="#" onclick="openCloseRegister()">Register</a>
<?php } ?>
    </div>
</div>
</div>
<br/>