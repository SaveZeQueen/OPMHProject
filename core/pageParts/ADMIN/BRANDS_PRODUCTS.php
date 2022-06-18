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
        <button class='active' onclick="location.href='adm.php?p=brand_products'"><img src='images/adm_ManageProduct.svg'/><b>Brands / Products</b></button>
        <button onclick="location.href='adm.php?p=invoices'"><img src='images/adm_ManageInvoices.svg'/><b>Invoices</b></button>
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
    
    <div class='CPPAN'>
        <div class='cprPanel CPRTPL'>
            <b><input type="checkbox" name='brandChka' onClick="selectAllChk(this)"/></b>
            <input type='text' id='BRANDSEARCHINPUT' value='Search by Name, or ID' onfocus='this.value=(this.value==`Search by Name, or ID`) ? `` : this.value' onkeyup='brandSearch()'/>
            <button><img src='images/adm_Delete.svg' onclick='deleteSelBrand()'/>Delete Selected</button>
            <button onclick="popWindow('Add Brand', 'popAddBrand')"><img src='images/adm_Add.svg'/>Add Brand</button>
        </div>
        
        <div class='cprPanel cpRLP'>
            <ul id='BRANDSEARCHRESULTS'>
               
                <?php $pfunc->adm_BrandList(); ?>
                
            </ul>
        </div>
        
         <div name='RIGHTPAN' class='cprPanel CPRTPL CPRTPR CRPSPIN'><b><input type="checkbox" name='prodChka' onClick="selectAllChk(this)"/></b>
            <input type='text' value='Search by Name, ID, SKU, Brand ID, or Price' id='PRODUCTSEARCH' onfocus='this.value=(this.value==`Search by Name, ID, SKU, Brand ID, or Price`) ? `` : this.value' onkeyup='productSearch()'/>
            <button onclick='deleteSelProd()'><img src='images/adm_Delete.svg'/>Delete Selected</button>
            <button name='' id='ADDPROBTN' onclick='popWindow(`Add New Product`, `popAddProd`, [this.name])'><img src='images/adm_Add.svg'/>Add Product</button>
        </div>
        
        
        <div id='RIGHTPANELBRAND' name='RIGHTPAN' class='cprPanel cpRRP CRPSPIN'>
        </div>
    </div>
</div>
    
<div class="cpFooter"></div>
</div>