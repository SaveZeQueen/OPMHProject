<!--
    
    Account Page Showing the Details of an account and invoices that go with it.

-->
<?php
            // Get User Logged In
            $user = $pfunc->getSessionUsr();
            if ($user == ""){
                 echo "<meta http-equiv='refresh' content='0;url=index.php' />";
            }
if ($user != ""){
?>
<div id="invoiceViewer">
    <!--Invoice Data will populate here-->
    LOADING
</div>


<div id="accountSplit">
<div class="accountSplit_left">
    <?php  $pfunc->accountLeftInfo();?>
</div>
<div class="accountSplit_right">
<h3>Invoices (Click for Details)</h3>    
    <ul id='ACCOUNTRIGHT'>
    <?php $pfunc->accountRightInfo();?>
    </ul>
</div>
</div>
<?php } ?>