<div id="pageContent">
    <?php if ($pfunc->accountVeri($_GET['user']) == false){ 
    $pfunc->verifyAccount($_GET['user'], $_GET['id']);
    ?>
    <br/>
    <div class="landingGoal"> Congradulations! </div>
    <hr style='border-bottom: 2px dotted rgba(0, 0, 0, 0.35);'/>
    <br/>
    <center><h3><?php echo $_GET['user']; ?>, Your email address has been varified, you may now begin ordering products from us! Remember that you can log in anytime to view your invoices, or make changes to your account.</h3>
    <br/><hr style='border-bottom: 2px dotted rgba(0, 0, 0, 0.35); width: 50%;'/><br/>
        
    <h4>Also a huge <i>'Thank You!'</i> from us here at OPMH, without you none of this would be possible. If you ever have any questions or need any help feel free to <a href='contact.php' title='Contact US'>contact us.</a></h4>
        
    <br/>
    <br/>
        <hr style='border-bottom: 2px dotted rgba(0, 0, 0, 0.35); width: 30%; margin-top: 10px; margin-bottom: 10px;'/>
    <h2>Thank You!</h2>
        <hr style='border-bottom: 2px dotted rgba(0, 0, 0, 0.35); width: 30%; margin-top: 10px; margin-bottom: 10px;'/>
    <br/>
    <br/>
    <h4>Make sure to keep up with us on Social Media!</h4>
    <ul class='socialMediaVeri'>
        <li><a href='https://www.instagram.com/opmhproject/' title='Instagram' target="_blank"><img src='images/socialMedia_Instagram.svg'/></a></li>
<li><a href='https://www.facebook.com/OPMHProject/' title='Facebook' target="_blank"><img src='images/socialMedia_Facebook.svg'/></a></li>  
        </ul>
    </center>
    <?php } else { ?>
    <br/>
    <div class="landingGoal"> NOTICE: </div>
    <hr style='border-bottom: 2px dotted rgba(0, 0, 0, 0.35);'/>
    <br/>
    <center><h3><?php echo $_GET['user']; ?>, Your email has already been verified and validated.</h3>
    <br/><hr style='border-bottom: 2px dotted rgba(0, 0, 0, 0.35); width: 50%;'/>
    <br/>
    <br/>
    <h4>Make sure to keep up with us on Social Media!</h4>
    <ul class='socialMediaVeri'>
        <li><a href='https://www.instagram.com/opmhproject/' title='Instagram' target="_blank"><img src='images/socialMedia_Instagram.svg'/></a></li>
<li><a href='https://www.facebook.com/OPMHProject/' title='Facebook' target="_blank"><img src='images/socialMedia_Facebook.svg'/></a></li>  
        </ul>
    </center>
    <?php } ?>
</div>