<div id="cpContent">
    
<div class="cpTopBar">
    <img src="images/controlPanel.svg" style='left: auto; right: 10px;'/>
    <img src="images/Logo_TXT_001.png"/>
    
</div>
    
<div class="cpTopFuncBar"><button onclick="location.href='index.php';"><img src='images/adm_Exit.svg'/></button></div>
    
<div class="cpLeft">
    <ul>
        <button onclick="location.href='adm.php?p=dashboard'"><img src='images/adm_DashBoard.svg'/><b>Dashboard</b></button>
        <button onclick="location.href='adm.php?p=manage_users'"><img src='images/adm_ManageUser.svg'/><b>Manage Users</b></button>
        <button onclick="location.href='adm.php?p=brand_products'"><img src='images/adm_ManageProduct.svg'/><b>Brands / Products</b></button>
        <button class='active' onclick="location.href='adm.php?p=invoices'"><img src='images/adm_ManageInvoices.svg'/><b>Invoices</b></button>
    </ul>
</div>
    
    
<div class="cpRight">
    
     <div id='OVRL' class='admBxOvrLay'>
        <div id='OVBOX' class='admOvrlayBx'>
        <div class='titleBar'><b id='TITXT'>Title</b><button onclick="showHideADMOverlay()"><img src='images/adm_CANCEL.svg'/>Close</button></div>
        <div id='DATABOX' class='admBXCont'>
<!--            To be filled with Data-->
        </div>
        </div>
    </div>
    
    <div class='CPPAN CPUPAN'>
        
        <div class='cprPanel CPRTPL'>
            <b><input type="checkbox" name='invChka' onClick="selectAllChk(this)"/></b>
            <input type='text' id='INVOICESEARCHINPUT' value='Search by anything, or use % to show all' onfocus='this.value=(this.value==`Search by anything, or use % to show all`) ? `` : this.value' onkeyup='invoiceSearch()'/>
            <button onclick="popWindow('Change Status', 'changeInvoiceStatus', [], ['20%', '65%'])"><img src='images/adm_Change.svg'/>Change Status</button>
            
            <button type='submit' onclick="goToPrintPage()"><img src='images/adm_Print.svg'/>Print Selected</button>
            
        </div>
        
        <div class='cprPanel cpRLP' id='INVOICESEARCHSLOT'>
             
                <input type='hidden' name='p' value='invoices'/>
                
                <?php $pfunc->adm_GetInvoiceList(); ?>
                
            
        </div>
            
        <div class='' name='RIGHTPAN' id='RIGHTPANINV'>

        </div>
    </div>
</div>
    
<div class="cpFooter"></div>
</div>