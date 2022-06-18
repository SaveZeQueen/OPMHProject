<div id="cpContent">
    
<div class="cpTopBar">
    <img src="images/controlPanel.svg" style='left: auto; right: 10px;'/>
    <img src="images/Logo_TXT_001.png"/>
    
</div>
    
<div class="cpTopFuncBar"><button onclick="location.href='index.php';"><img src='images/adm_Exit.svg'/></button></div>
    
<div class="cpLeft">
    <ul>
        <button onclick="location.href='adm.php?p=dashboard'"><img src='images/adm_DashBoard.svg'/><b>Dashboard</b></button>
        <button class='active' onclick="location.href='adm.php?p=manage_users'"><img src='images/adm_ManageUser.svg'/><b>Manage Users</b></button>
        <button onclick="location.href='adm.php?p=brand_products'"><img src='images/adm_ManageProduct.svg'/><b>Brands / Products</b></button>
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
    
    <div class='CPPAN CPUPAN'>
        <div class='cprPanel CPRTPL'>
            <b><input type="checkbox" name='usrChka' onClick="selectAllChk(this)"/></b>
            <input type='text' value='Search by Username, ID, Email, Name, account type or Phone Number'  onfocus='this.value=(this.value==`Search by Username, ID, Email, Name, account type or Phone Number`) ? `` : this.value' onkeyup='userSearch(this.value)'/>
            <button onclick="popWindow('Change Status', 'changeUserStatus', [], ['20%', '65%'])"><img src='images/adm_Change.svg'/>Change Status</button>
            <button onclick="popWindow('Email Selected', 'EmailSelUsers', [], ['40%', '55%'])"><img src='images/adm_EMAIL.svg'/>Email Selected</button>
        </div>
        
        <div class='cprPanel cpRLP' id='INVOICESEARCHSLOT'>
            <table id='USERLIST'>
            
            
            <?php 
                $pfunc->populateUserList(); ?>
            
            </table>
        </div>
        
        
        
        <div class='cprPanel cpRRP cpRRPE'>
              
          
            <img src='images/adm_ManageUser.svg' class='cpRRP_BCKDROP'/>
            
            <ul id='USERDATABOX'>
            <!--To be filled-->
            </ul>
            
            
            
            
        </div>
    </div>
</div>
    
<div class="cpFooter"></div>
</div>