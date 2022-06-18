
var customInput = [];
var selectedbrandID = [];
function countInArray(array, what) {
    var count = 0;
    for (var i = 0; i < array.length; i++) {
        if (array[i].includes(what)) {
            count++;
        }
    }
    return count;
}


function addCustomInput(event){
    if (event.keyCode == 13) {
        var output = document.getElementById('CUSTOMOUTPUT');
        var input = document.getElementById('CUSTOMINPUT');
        if (input.value != '' && input.value.length >= 3){
            addCustomInputLI(input.value);
        }
    }
}

function addCustomInputLI(ivalue){
            var output = document.getElementById('CUSTOMOUTPUT');
            var input = document.getElementById('CUSTOMINPUT');
            var node = document.createElement("LI");
            var c = countInArray(customInput, ivalue)
            var nid = (ivalue + c.toString()).toString();
            customInput[nid] = ivalue;
            node.id = nid;
            node.classList.className;
            node.innerHTML = ivalue + "<button onclick='removeCustomInput(`"+nid+"`)'>x</button>";
            output.appendChild(node);     
            input.value = '';
}

function removeCustomInput(id){
    // Check if Key Defined
    
    if (document.getElementById(id) && customInput[id] == null){
        customInput[id] = document.getElementById(id).className;
    }
    
    if (customInput[id] != null){
        ele = document.getElementById(id);
        ele.parentNode.removeChild(ele);
        delete customInput[id];
    }
    
}

function selectAllChk(parent){    
    var events = document.getElementsByName(parent.name.slice(0, -1));
    for (var i = 0; i<events.length; i++){
        events[i].checked = parent.checked;
    }
}

function outputVisibility(tfs){
    if (tfs == true || tfs == 1){
        return 'visible';
    } else {
        return 'hidden';
    }
}

function readURL(input) {
    console.log(input.files);
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                document.getElementById('SRCIMG').setAttribute('src', e.target.result);
                document.getElementById('SRCIMG').style.opacity = '1';
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }



// Show and hide pop-up
function showHideADMOverlay(winSize = ['50%', '50%'], forceShow = false){
    var swit = (document.getElementById('OVRL').style.visibility == "visible");
    var ovbx = document.getElementById('OVBOX');
    document.getElementById('OVRL').style.visibility = (forceShow == false) ? outputVisibility(!swit) : 'visible';
 
    if (document.getElementById('OVRL').style.visibility == 'visible'){
        ovbx.style.transform = "rotate3d(0, 1, 0, 0deg)";
        ovbx.style.opacity = "1";
        // Set up size
        if (winSize.length > 0){
        ovbx.style.width = winSize[0];
        ovbx.style.height = winSize[1];
        ovbx.style.maxWidth = winSize[0];
        ovbx.style.maxHeight = winSize[1];
        ovbx.style.minWidth = winSize[0];
        ovbx.style.minHeight = winSize[1];
        //if (ovbx.style.minWidth  < )
        // Set up position
        ovbx.style.top = "calc(50% - " + winSize[1] + " / 2)";
        ovbx.style.left = "calc(50% - " + winSize[0] + " / 2)";
        }
    } else {
        ovbx.style.transform = "rotate3d(0, 1, 0, 90deg)";
        ovbx.style.opacity = "0";
    }

}

function popWindow(titletext='New Window', fname = "", args, winSize=['50%', '50%']){
    showHideADMOverlay(winSize);
    if (fname != ""){
        window[fname](args);
    }
    customInput = [];
    document.getElementById('TITXT').innerHTML = titletext;
}

function popAddBrand(args=arguments[0]){
    var fdata = new FormData();
    fdata.append("mode", "new");
    
    
    
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("DATABOX").innerHTML = this.responseText;
        }
    };    
    xmlhttp.open("POST", "core/pageParts/ADMIN/AJAX/add_brand.php", true);
    xmlhttp.send(fdata);
}

function changeUserStatus(args=arguments[0]){
    // Check if any Invoices are Selected
    var ischecked = false;
    
    var checkboxes = document.getElementsByName('usrChk');
    var invList = [];
    for (var i = 0; i<checkboxes.length; i++){
        // Check arguments for invoice Number
        if (args.length > 0){
            // if it matches one of the check boxes check that one.
            if (checkboxes[i].value == args[0]){ 
                checkboxes[i].checked = true; 
            } else {
                checkboxes[i].checked = false;
            }
        }
        if (checkboxes[i].checked){
            ischecked = true;
            invList.push(checkboxes[i].value);
        }
    }
    if (ischecked == false){
        alert("No Users Selected.");
        showHideADMOverlay();
    } else {
    
    
    var fdata = new FormData();
    fdata.append("mode", "new");
    var chgStatus = JSON.stringify(invList);
    fdata.append('toEdit', chgStatus);
    
    
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("DATABOX").innerHTML = this.responseText;
        }
    };    
    xmlhttp.open("POST", "core/pageParts/ADMIN/AJAX/changeUsr_Status.php", true);
    xmlhttp.send(fdata);
    }
}

function EmailSelUsers(args=arguments[0]){
    // Check if any Invoices are Selected
    var ischecked = false;
    
    var checkboxes = document.getElementsByName('usrChk');
    var invList = [];
    for (var i = 0; i<checkboxes.length; i++){
        // Check arguments for invoice Number
        if (args.length > 0){
            // if it matches one of the check boxes check that one.
            if (checkboxes[i].value == args[0]){ 
                checkboxes[i].checked = true; 
            } else {
                checkboxes[i].checked = false;
            }
        }
        if (checkboxes[i].checked){
            ischecked = true;
            invList.push(checkboxes[i].value);
        }
    }
    if (ischecked == false){
        alert("No Users Selected.");
        showHideADMOverlay();
    } else {
    
    
    var fdata = new FormData();
    fdata.append("mode", "new");
    var chgStatus = JSON.stringify(invList);
    fdata.append('toEdit', chgStatus);
    
    
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("DATABOX").innerHTML = this.responseText;
        }
    };    
    xmlhttp.open("POST", "core/pageParts/ADMIN/AJAX/MassEmailUsers.php", true);
    xmlhttp.send(fdata);
    }
}

function changeInvoiceStatus(args=arguments[0]){
    // Check if any Invoices are Selected
    var ischecked = false;
    
    var checkboxes = document.getElementsByName('invChk');
    var invList = [];
    for (var i = 0; i<checkboxes.length; i++){
        // Check arguments for invoice Number
        if (args.length > 0){
            // if it matches one of the check boxes check that one.
            if (checkboxes[i].value == args[0]){ 
                checkboxes[i].checked = true; 
            } else {
                checkboxes[i].checked = false;
            }
        }
        if (checkboxes[i].checked){
            ischecked = true;
            invList.push(checkboxes[i].value);
        }
    }
    if (ischecked == false){
        alert("No Invoices Selected.");
        showHideADMOverlay();
    } else {
    
    
    var fdata = new FormData();
    fdata.append("mode", "new");
    var chgStatus = JSON.stringify(invList);
    fdata.append('toEdit', chgStatus);
    
    
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("DATABOX").innerHTML = this.responseText;
        }
    };    
    xmlhttp.open("POST", "core/pageParts/ADMIN/AJAX/changeInv_Status.php", true);
    xmlhttp.send(fdata);
    }
}

function goToPrintPage(){
    var locationString = "";
    var ext = "massPrintInvList%5B%5D=";
    var chkBxNodes = document.getElementsByName('invChk');
    for (var i = 0; i<chkBxNodes.length; i++){
        // Check if node is checked
        if (chkBxNodes[i].checked == true){
            if (locationString != "" && ext != "&massPrintInvList%5B%5D="){
                ext = "&massPrintInvList%5B%5D=";
            }
            locationString += (ext + chkBxNodes[i].value);
        }
    }
    window.open("adm_printInvoices.php?"+locationString, `_blank`);
}

function brandSearch(input = document.getElementById('BRANDSEARCHINPUT').value){
    var fdata = new FormData();
    fdata.append("search", input);
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("BRANDSEARCHRESULTS").innerHTML = this.responseText;
        }
    };    
    xmlhttp.open("POST", "core/pageParts/ADMIN/AJAX/brandSearch.php", true);
    xmlhttp.send(fdata);
}

function submitChangeUserStatus(){
    var statusArrayEl = document.getElementsByName('changeStatusSEL');
    var verArrayEl = document.getElementsByName('changeVerSEL');
    var statusInvNumArray = document.getElementsByName('accountVerID');
    var valArray = []
    var userLists = "";
    for (var i = 0; i < statusArrayEl.length; i++){
        var narray = [statusInvNumArray[i].value, statusArrayEl[i].value, verArrayEl[i].value];
        valArray.push(narray);
        userLists += "\n - " + statusInvNumArray[i].value.toString();
        
    }
    console.log(valArray);
    // Check confirm changes
    if (confirm("You're about to change the status of the following users: " + userLists + "\n Are you sure you wish to continue with these changes?")) {
        // Get Form Data
        var fdata = new FormData();
        var ddata = JSON.stringify(valArray);
        fdata.append('editData', ddata);
        fdata.append('mode', 'changeStatus');
        document.getElementById('DATABOX').innerHTML = "<center>        <label>..Loading..</label><img src='images/adm_loading.svg'/></center>";
        showHideADMOverlay(['15%', '36%'], true);
        // Send Server Request
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("DATABOX").innerHTML = this.responseText;
                    showHideADMOverlay();
                    userSearch();
                    document.getElementsByName('usrChka')[0].checked = false;
                }
            };    
        xmlhttp.open("POST", "core/pageParts/ADMIN/AJAX/changeUsr_Status.php", true);
        xmlhttp.send(fdata);
        } else {
                // Do nothing!
        }
}

function submitChangeInvoiceStatus(){
    var statusArrayEl = document.getElementsByName('changeStatusSEL');
    var statusInvNumArray = document.getElementsByName('invoiceIndex');
    var valArray = []
    var invoiceLists = "";
    for (var i = 0; i < statusArrayEl.length; i++){
        var narray = [statusInvNumArray[i].value, statusArrayEl[i].value];
        valArray.push(narray);
        invoiceLists += "\n - " + statusInvNumArray[i].value.toString();
        
    }
    console.log(valArray);
    // Check confirm changes
    if (confirm("You're about to change the status of the following invoices: " + invoiceLists + "\n Are you sure you wish to continue with these changes?")) {
        // Get Form Data
        var fdata = new FormData();
        var ddata = JSON.stringify(valArray);
        fdata.append('editData', ddata);
        fdata.append('mode', 'changeStatus');
        document.getElementById('DATABOX').innerHTML = "<center>        <label>..Loading..</label><img src='images/adm_loading.svg'/></center>";
        showHideADMOverlay(['15%', '36%'], true);
        // Send Server Request
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("DATABOX").innerHTML = this.responseText;
                    showHideADMOverlay();
                    invoiceSearch();
                    document.getElementsByName('invChka')[0].checked = false;
                }
            };    
        xmlhttp.open("POST", "core/pageParts/ADMIN/AJAX/changeInv_Status.php", true);
        xmlhttp.send(fdata);
        } else {
                // Do nothing!
        }
}



function SendMassEmail(){
    var accountIdArray = document.getElementsByName('accountID');
    var emailArray = document.getElementsByName('accountEmail');
    var subject = document.getElementById('EMAILSUBJECT').value;
    var message = document.getElementById('EMAILMESSAGE').value;
    if (subject == "" || subject == "Subject" || message == "" || message == "Message"){
        alert("No Subject and/or Message Set. Please add a Subject and/or Message to continue.");
        return;
    }
    var valArray = []
    var userList = "";
    for (var i = 0; i < accountIdArray.length; i++){
        var narray = [accountIdArray[i].value, emailArray[i].value];
        valArray.push(narray);
        userList += "\n - " + accountIdArray[i].value.toString() + " - " + emailArray[i].value.toString();
        
    }
    console.log(valArray);
    // Check confirm changes
    if (confirm("You're about to email the following users: " + userList + "\n Do you wish to continue?")) {
        // Get Form Data
        var fdata = new FormData();
        var ddata = JSON.stringify(valArray);
        fdata.append('editData', ddata);
        fdata.append('subject', subject);
        fdata.append('message', message);
        fdata.append('mode', 'sendEmail');
        document.getElementById('DATABOX').innerHTML = "<center>        <label>..Sending Please Wait..</label><img src='images/adm_loading.svg'/></center>";
        showHideADMOverlay(['15%', '36%'], true);
        // Send Server Request
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("DATABOX").innerHTML = this.responseText;
                    showHideADMOverlay();
                    invoiceSearch();
                    document.getElementsByName('usrChka')[0].checked = false;
                }
            };    
        xmlhttp.open("POST", "core/pageParts/ADMIN/AJAX/MassEmailUsers.php", true);
        xmlhttp.send(fdata);
        } else {
                // Do nothing!
        }
}


function editInvoice(id){
    if (confirm("You're about to edit invoice #" + id + " and open it on a new page, do you wish to continue?")) {
        window.open("order.php?mode=edit&invid="+id, `_blank`);
    }
}


function viewUser(args){
    userID = args[0];
    if (userID == ""){
        return alert("Error: no user id found for this user contact server admin, or check database.\nError Code: 0001 - Missing/Corrupt Data");
    }
    var fdata = new FormData();
    fdata.append("userid", userID);
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("DATABOX").innerHTML = this.responseText;
        }
    };    
    xmlhttp.open("POST", "core/pageParts/ADMIN/AJAX/viewUser.php", true);
    xmlhttp.send(fdata);
}

function userSearch(input = ""){
    if (input == 'Search by Username, ID, Email, Name, account type or Phone Number' || input == ""){
        input = "%";
    }
    var fdata = new FormData();
    fdata.append("search", input);
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("USERLIST").innerHTML = this.responseText;
        }
    };    
    xmlhttp.open("POST", "core/pageParts/ADMIN/AJAX/userSearch.php", true);
    xmlhttp.send(fdata);
}

function invoiceSearch(input = document.getElementById('INVOICESEARCHINPUT').value){
    if (input == 'Search by anything, or use % to show all'){
        input = "%";
    }
    var fdata = new FormData();
    fdata.append("search", input);
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("INVOICESEARCHSLOT").innerHTML = this.responseText;
        }
    };    
    xmlhttp.open("POST", "core/pageParts/ADMIN/AJAX/invoiceSearch.php", true);
    xmlhttp.send(fdata);
}

function addNewBrand(){
    
    var brandName = document.getElementsByName('brandName')[0].value;
    var BRANDID = document.getElementsByName('brandID')[0].value;
    var gccfiles = document.getElementsByName('brandGCC')[0].files;
    var imagefiles = document.getElementsByName('brandImage')[0].files;
    var clear = true;
    
    if (brandName == "Input Brand Name" || brandName == ""){
        alert("You must input a name for the Brand");
        clear = false;
    }
    
     if (gccfiles.length == 0){
        alert("You must select a GCC .pdf file to upload.");
        clear = false;
    }
    
    if (imagefiles.length == 0){
        alert("You must select a brand logo image (png, gif, jpeg, jpg) file to upload.");
        clear = false;
    }
    
    if (clear == true){
    var fdata = new FormData();
    fdata.append("mode", "adding");
    fdata.append("brandName", brandName);
    fdata.append("brandID", BRANDID);
    fdata.append("gccFiles", gccfiles[0]);
    fdata.append("brandImg", imagefiles[0]);
    
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("DATABOX").innerHTML = this.responseText;
            showHideADMOverlay();
            brandSearch("");
        }
    };    
    xmlhttp.open("POST", "core/pageParts/ADMIN/AJAX/add_brand.php", true);
    xmlhttp.send(fdata);
    }
}

function deleteSelProd(){
    var ischecked = false;
    var checkboxes = document.getElementsByName('prodChk');
    var todel = [];
    for (var i = 0; i<checkboxes.length; i++){
        if (checkboxes[i].checked){
            ischecked = true;
            todel.push(checkboxes[i].value);
        }
    }
    if (ischecked == false){
        alert("No products Selected.");
    } else {
        if (confirm('You are about to Delete the selected products, are you sure you wish to continue?')) {
        var fdata = new FormData();
        var ddata = JSON.stringify(todel);
        document.getElementById('DATABOX').innerHTML = "<center>        <label>..Loading..</label><img src='images/adm_loading.svg'/></center>";
        showHideADMOverlay();
        fdata.append('todel', ddata);
         var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("DATABOX").innerHTML = this.responseText;
                    brandSearch("");
                    productSearch("");
                    showHideADMOverlay();
                    alert('Selected Products Deleted.')
                }
            };    
        xmlhttp.open("POST", "core/pageParts/ADMIN/AJAX/delete_product.php", true);
        xmlhttp.send(fdata);
        } else {
                // Do nothing!
        }
    }
}


function deleteSelBrand(){
    var ischecked = false;
    var checkboxes = document.getElementsByName('brandChk');
    var todel = [];
    for (var i = 0; i<checkboxes.length; i++){
        if (checkboxes[i].checked){
            ischecked = true;
            todel.push(checkboxes[i].value);
        }
    }
    if (ischecked == false){
        alert("No brands Selected.");
    } else {
        if (confirm('You are about to Delete the selected brands and all of their products, are you sure you wish to continue?')) {
        var fdata = new FormData();
        var ddata = JSON.stringify(todel);
        document.getElementById('DATABOX').innerHTML = "<center>        <label>..Loading..</label><img src='images/adm_loading.svg'/></center>";
        showHideADMOverlay();
        fdata.append('todel', ddata);
         var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("DATABOX").innerHTML = this.responseText;
                    brandSearch("");
                    productSearch("");
                    showHideADMOverlay();
                    alert('Selected Brands Deleted.')
                    
                }
            };    
        xmlhttp.open("POST", "core/pageParts/ADMIN/AJAX/delete_brand.php", true);
        xmlhttp.send(fdata);
        } else {
                // Do nothing!
        }
    }
}

function submiteditBrand(){
    var fdata = new FormData();
    var brandName = document.getElementsByName('brandName')[0].value;
    var BRANDID = document.getElementsByName('brandID')[0].value;
    if (document.getElementsByName('brandGCC')[0].files.length != 0){
        var gccfiles = document.getElementsByName('brandGCC')[0].files[0];
        fdata.append("gccFiles", gccfiles);
        $gccchk = "G";
    } else {
        $gccchk = "";
    }
    if (document.getElementsByName('brandImage')[0].files.length != 0){
    var imagefiles = document.getElementsByName('brandImage')[0].files[0];
        fdata.append("brandImg", imagefiles);
        $bimgchk = "I";
    } else {
        $bimgchk = "";
    }
    
    
    fdata.append("mode", "edit");
    fdata.append("brandName", brandName);
    fdata.append("brandID", BRANDID);
    fdata.append("hasFiles", $gccchk + $bimgchk);
    
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("DATABOX").innerHTML = this.responseText;
            showHideADMOverlay();
            brandSearch("");
        }
    };    
    xmlhttp.open("POST", "core/pageParts/ADMIN/AJAX/edit_brand.php", true);
    xmlhttp.send(fdata);
}


function popEditBrand(args=arguments[0]){
    
    var id = args[0];
    
    var fdata = new FormData();
    fdata.append("mode", "new");
    fdata.append("id", id);
    
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("DATABOX").innerHTML = this.responseText;
        }
    };    
    xmlhttp.open("POST", "core/pageParts/ADMIN/AJAX/edit_brand.php", true);
    xmlhttp.send(fdata);
}

function admloadProductList(id){
    
    var el = document.getElementsByName('RIGHTPAN');
    for (var i = 0; i < el.length; i++){
        el[i].style.transform = "rotate3d(0, 0, 1, 2deg)";
    }
    
    document.getElementById('ADDPROBTN').name = id;
    
    setTimeout(function(){
        admLoadProdList(id);
    }, 150); 
}

function productSearch(search = document.getElementById('PRODUCTSEARCH').value){
    admLoadProdList(selectedbrandID, search);
}

function admLoadProdList(id, search=""){
    
    var el = document.getElementsByName('RIGHTPAN');
    for (var i = 0; i < el.length; i++){
        el[i].style.transform = "rotate3d(0, 0, 1, 0deg)";
    }
    selectedbrandID = id;
     var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("RIGHTPANELBRAND").innerHTML = this.responseText;
        }
    };
    xmlhttp.open("GET", "core/pageParts/ADMIN/AJAX/productList.php?id="+id+"&psearch="+search, true);
    xmlhttp.send();
}

function adm_loadInvoiceData(invoiceNumber){
     
    var el = document.getElementsByName('RIGHTPAN');
    for (var i = 0; i < el.length; i++){
        el[i].style.transform = "rotate3d(0, 0, 1, 0deg)";
    }

     var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("RIGHTPANINV").innerHTML = this.responseText;
        }
    };
    xmlhttp.open("GET", "core/pageParts/ADMIN/AJAX/view_invoice.php?invID="+invoiceNumber, true);
    xmlhttp.send();    
}

function popAddProd(args=arguments[0]){
    
    var id = args[0];
     var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("DATABOX").innerHTML = this.responseText;
        }
    };
    xhttp.open("POST", "core/pageParts/ADMIN/AJAX/add_product.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("mode=new&id="+id);
}




function editProd(args=arguments[0]){
    var sku = args[0];
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("DATABOX").innerHTML = this.responseText;
        }
    };
    xhttp.open("POST", "core/pageParts/ADMIN/AJAX/edit_product.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("mode=edit&sku="+sku);
}


function admaddProducts(){
    // Get data
    
    var canAdd = true;
    var productName = document.getElementsByName('productName')[0].value;
    
    if (productName == "" || productName == "Input Product Name"){
        canAdd = false;
        alert("A Name must be given to your Product.");
    }
    
    var productSKU = document.getElementsByName('productSKU')[0].value.slice(0, -3);
    
    var brid = document.getElementsByName('brandID')[0];
    var brandID = brid.options[brid.selectedIndex].value;
    
    var productCost = document.getElementsByName('productCost')[0].value;
    if (productCost == 0.0){
        canAdd = false;
        alert("Make Sure to Set the price of your Product.");
    }
    var productWSCost = document.getElementsByName('productWSCost')[0].value;
    if (productWSCost == 0.0){
        canAdd = false;
        alert("Make Sure to Set the whole sale price of your Product.");
    }
    var fileData = document.getElementsByName('productImage')[0].files;
    if (fileData.length == 0){
        canAdd = false;
        alert("No Image selected for Product.");
    }
    var productDesc = document.getElementsByName('productDesc')[0].value;
    if (productDesc == "" || productDesc == "Enter a Description here."){
        canAdd = false;
        alert("Your product Needs a description.");
    }
    
    var nicotineLevels = document.getElementsByName('nicotineLevels')[0].value;
    if (nicotineLevels == "" || nicotineLevels == "EX: 0, 8, 12 (Produces products for 0mg, 8mg, 12mg)"){
        canAdd = false;
        alert("Please set your nicotine levels. Remember to separate by a comma.");
    }
    var PG = document.getElementsByName('PG')[0].value;
    var VG = document.getElementsByName('VG')[0].value;
    if (PG == -1 || VG == -1){
        canAdd = false;
        alert("The Product PG/VG is not set");
    }
    
    var botsize = document.getElementsByName('botSize')[0].value;
    if (botsize == 0){
        canAdd = false;
        alert("The Product Bottle Size has not been set.");
    }
    var prodFlavors = "";
    for (var i in customInput){
        var txt = "";
        if (prodFlavors != ""){txt += ", ";}
        txt += customInput[i];
        console.log(customInput[i]);
        prodFlavors += txt;
    }
    
    if (canAdd == true){
    var formData = new FormData();
    formData.append("mode", "adding");
    formData.append("pn", productName);
    formData.append("psku", productSKU);
    formData.append("bid", brandID);
    formData.append("pcost", productCost);
    formData.append("pwscost", productWSCost);
    formData.append("pdesc", productDesc);
    formData.append("pnic", nicotineLevels);
    formData.append("pg", PG);
    formData.append("vg", VG);
    formData.append("botsize", botsize);
    formData.append("pfla", prodFlavors);
    formData.append("file", fileData[0]);
    
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("DATABOX").innerHTML = this.responseText;
            showHideADMOverlay();
            productSearch("");
        }
    };
    
    xhttp.open("POST", "core/pageParts/ADMIN/AJAX/add_product.php", true);
    xhttp.send(formData);
    }
}

function editProduct(){
    // Get data
    
    var canAdd = true;
    var productName = document.getElementsByName('productName')[0].value;    
    var brid = document.getElementsByName('brandID')[0];
    var brandID = brid.options[brid.selectedIndex].value;
    var productCost = document.getElementsByName('productCost')[0].value;
    var prodSKU = document.getElementsByName('productSKU')[0].value;
    var productWSCost = document.getElementsByName('productWSCost')[0].value;
    var productDesc = document.getElementsByName('productDesc')[0].value;
    var nicotineLevels = document.getElementsByName('nicotineLevels')[0].value;
    var PG = document.getElementsByName('PG')[0].value;
    var VG = document.getElementsByName('VG')[0].value;
    var botsize = document.getElementsByName('botSize')[0].value;
    var prodfla = document.getElementById('FLAVORS').value;
    
    if (canAdd == true){
    var formData = new FormData();
    formData.append("mode", "adding");
    formData.append("pn", productName);
    formData.append("bid", brandID);
    formData.append("sku", prodSKU);
    formData.append("pcost", productCost);
    formData.append("pwscost", productWSCost);
    formData.append("pdesc", productDesc);
    formData.append("pnic", nicotineLevels);
    formData.append("pg", PG);
    formData.append("vg", VG);
    formData.append("botsize", botsize);
    formData.append("pfla", prodfla);
    var fileData = document.getElementsByName('productImage')[0].files;
    if (fileData.length == 0){
    formData.append("hasfile", "false");
    } else {
    formData.append("hasfile", "true");
    formData.append("file", fileData[0]);
    }
    
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("DATABOX").innerHTML = this.responseText;
            showHideADMOverlay();
            productSearch("");
        }
    };
    
    xhttp.open("POST", "core/pageParts/ADMIN/AJAX/edit_product.php", true);
    xhttp.send(formData);
    }
}



