<div id="pageContent">
    <div class="landingGoal">- Our Goals -</div>
    <ul class="pageContent_Goals">   
    
    <li>
    <center><img src="images/Consumer_BLK.svg"/></center>
    <h3>Customers</h3>
    <p>We strive to provide our customers with a personalized experience and excellent service.
    </p>
    </li>
        
    <li>
    <center><img src="images/Shipping_BLK.svg"/></center>
    <h3>Shipping</h3>
    <p>Every brand of E-Liquid is kept in stock at our warehouse, ensuring reliability and no shipping delays.</p>
    </li>
        
    <li>
    <center><img src="images/Invoice_BLK.svg"/></center>
    <h3>Orders</h3>
    <p>We work hard to make sure ordering the E-Liquid brands is simple and intuative, making each order hassle free. Each order will autoamtically send you an invoice on completion making tracking a breeze.</p>
    </li> 
    </ul>
    <?php if ($pfunc->sessionSet() == false){ ?>
    <div class="pageContent_Register" onclick="openCloseLogin()">- Login and Order today -</div>
    <?php } else { ?>
    <div class="pageContent_Register" onclick="location.href='order.php'">- Order Now -</div>
    <?php } ?>
</div>