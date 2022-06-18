<?php
            // Get User Logged In
            $user = $pfunc->getSessionUsr();
            if ($user == ""){
                 echo "<meta http-equiv='refresh' content='0;url=index.php' />";
            }
if ($user != ""){
?>
<div id="nav_OrderMode">
<div class="nav_mobile">
    <a href="index.php"><img src="images/Logo_002.png" class="navLogo"/></a>
    <div class="nav_ordergoBack">
    <a href="index.php">
    <img src="images/goBack_BLK.svg"/>
    Back
    </a>
    </div>
</div>
</div>
    
<br/>
    
    <div id="orderContent" class="orderContent_Override">
        
    <div id='COMMENTOVERLAY' class='orderCommentOverlay'>
        <div id='OUTPUTBOX' class='orderCommentOverlayBX'>
            <textarea onfocus="this.value=(this.value == 'Leave a Comment...') ? '' : this.value" id='CUSTOMERCOMMENT'>Leave a Comment...</textarea>
            <button class='finishButton' onclick="completeOrder()">Complete Order</button>
            <button class='cancelButton' onclick='showHideCommentBox()'>Edit Order</button>
        </div>
    </div>
        
    <div class="orderLeft">
    <h2>Brands</h2>
    <div id="BrandList">
    
    <ul>
    <li onclick="showAllProducts()">All Products</li>
    <?php $pfunc->brandList(); ?>
    </ul>
    </div>
        
    </div>
        
    <div class="orderCenter">
        <h2 class="brandTitle">
            <img id="BRANDIMAGE" src="images/brandLogos/ALLBRANDS.png"/>
            <br/>
            <b id="BRANDNAME">All Products</b>
            <br class="break"/>
        </h2>
        <br class="break"/>
        <div class='slotHolder'>
        <?php $pfunc->productList(); ?>

            </div>
      </div>          
        
    </div>

    <div class="orderRight ORDERCWIN_Closed" id="ORDERFWINDOW">
    <div class='menuButton' onclick="showHideFinalOrder()"><h5>Order Details</h5></div>
    <button class='finishButton' onclick="showHideCommentBox()">Finalize Order</button>
        <?php
            if (isset($_GET['invid'])){
                if ($pfunc->isEditMode($_GET['invid'])){
                    $pfunc->getInvoiceOrderRightDetails($_GET['invid']);
                } else {
                    ?>
                    <div class='orderRight_Title' id="ORDERCOST">Cost: $0.00</div>
                    <div class='orderRight_Title' id="ORDERQTY">Total Quanitity: 0</div>
                    <div class='orderRight_Title'>Order Details</div> 
                    <?php
                }
            } else {
                    ?>
                    <div class='orderRight_Title' id="ORDERCOST">Cost: $0.00</div>
                    <div class='orderRight_Title' id="ORDERQTY">Total Quanitity: 0</div>
                    <div class='orderRight_Title'>Order Details</div> 
                    <?php
                }
        ?>
    </div>
        
       
    
    
<?php } ?>