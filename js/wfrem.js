// Global Variables
var modifyOriginalValues = {};



// Removes Nasty White Flash on Page load
$(document).ready(function() {
  $("body").removeClass('preload');
  
});
    
function focusObject(myobject) {
    myobject.focus();
};

function ShowHideOverlayDarken(){
    var celement = document.getElementById("accountOverlay");
    celement.style.visibility = (celement.style.visibility == "visible") ? 'hidden' : "visible";
    celement.style.opacity = (celement.style.visibility == "visible") ? 100 : 0;
    /*If Visible then Remove No Scroll Else Keep*/
    if (celement.style.visibility == 'visible'){
        $("body").addClass('noscroll');
    } else {
         $("body").removeClass('noscroll');
    }
}

function verifyDOB(){
    var month = document.getElementById('DOBMONTH').selectedIndex + 1;
    var day = document.getElementById('DOBDAY');
    var d = day.options[day.selectedIndex].value;
    var year = document.getElementById('DOBYEAR');
    var y = year.options[year.selectedIndex].value;
    var array = [y, month, d];
    console.log(array);
    var pdata = JSON.stringify(array);
    if (array[0] != "Year" && array[2] != "Day"){
        var fdata = document.getElementById('DOBCH').value = pdata;
    }
    
}

function openCloseLogin(){
    ShowHideOverlayDarken();
    ShowHideLogin();
}

function openCloseRegister(){
    ShowHideOverlayDarken();
    ShowHideRegister();
}

function closeLoginshowLoad(){
    ShowHideLogin();
    ShowHideLoading();
}

function closeRegistershowLoad(){
    ShowHideRegister();
    ShowHideLoading();
}

function openCloseLoading(){
    ShowHideOverlayDarken();
    ShowHideLoading();
}

function ShowHideRegister() {
    var relement = document.getElementsByClassName("registerBox")[0];
    relement.style.visibility = (relement.style.visibility == "visible") ? 'hidden' : "visible";
    relement.style.opacity = (relement.style.visibility == "visible") ? 100 : 0;
    relement.style.top = (relement.style.visibility == "visible") ? 'calc(45% - (480px / 2))' : '-100%';
    /**/
};

function ShowHideLoading() {
    var loelement = document.getElementsByClassName("loadingBox")[0];
    loelement.style.visibility = (loelement.style.visibility == "visible") ? 'hidden' : "visible";
    loelement.style.opacity = (loelement.style.visibility == "visible") ? 100 : 0;
    loelement.style.top = (loelement.style.visibility == "visible") ? 'calc(45% - (160px / 2))' : '-100%';
    /**/
};

function ShowHideLogin() {
    var lelement = document.getElementsByClassName("loginBox")[0];
    lelement.style.visibility = (lelement.style.visibility == "visible") ? 'hidden' : "visible";
    lelement.style.opacity = (lelement.style.visibility == "visible") ? 100 : 0;
    lelement.style.top = (lelement.style.visibility == "visible") ? 'calc(45% - (320px / 2))' : '-100%';
    /**/
};

/*Activate Save Button on object change*/
function highlightSaveBTN(){
    var sbtn = document.getElementById("SAVECHG");
    sbtn.disabled = false;
};

/*Disable Save BTN*/
function disableSaveBTN(){
    var sbtn = document.getElementById("SAVECHG");
    sbtn.disabled = true;
};
/*Save Overlay Toggle*/
function toggleOverlaySave(){
    var ovr = document.getElementById("saveOverlay");
    var txt = document.getElementById("SAVEVERI");
    var vis = (ovr.style.visibility == "visible") ? true : false;
    ovr.style.visibility = (ovr.style.visibility == "visible") ? 'hidden' : "visible";
    ovr.style.opacity = (vis == true) ? 0 : 100;
    ovr.style.height = (vis == true) ? '0px' : '100%';
    if (txt.innerHTML.toString().includes("Settings Saved")){
        clearAllButtons();
        disableSaveBTN();
    }
    txt.innerHTML = "<center>Password Confirmation</center>";
};
/* Check if Element has a class */
function hasClass(element, cls) {
    return (' ' + element.className + ' ').indexOf(' ' + cls + ' ') > -1;
};

function modifyElement(id, idself){
    var eself = document.getElementsByName($(idself).attr("name"))[0];
    enableDisableElement(id);
    if (hasClass(eself, "inputCancel")){
        $(eself).removeClass("inputCancel");
        eself.value = modifyOriginalValues[$(idself).attr("name")];
        resetDefaultValues(id);
    } else {
        $(eself).addClass("inputCancel");
        modifyOriginalValues[$(idself).attr("name")] = eself.value;
        eself.value = "Cancel";
        getDefaultValues(id);
    }
    var celm = document.getElementsByClassName("inputCancel");
    var dsbtnArray = [];
    
    for (i=0; i<celm.length;i++){        
        var anode = findAncestor(celm[i], "sbContent");
        if (anode == null){
            dsbtnArray.push(celm[i]);
        }
    }
    
    if (dsbtnArray.length <= 0){
            disableSaveBTN();
    }    
};

function findAncestor (el, cls) {
    while ((el = el.parentElement) && !el.classList.contains(cls));
    return el;
};


function clearAllButtons(){
    var celm = document.getElementsByClassName("inputCancel");
    var atxt = document.getElementsByClassName("ADDRESSTXT");
    var etxt = document.getElementsByClassName("EMAILTXT");
    
    for (i=0; i < celm.length; i++){
        celm[i].value = modifyOriginalValues[celm[i].getAttribute("name")];
    }
    
    for (i=0; i<atxt.length; i++){
        atxt[i].disabled = true;
    }
    for (i=0; i<etxt.length; i++){
       etxt[i].disabled = true; 
    }
    
    $('.inputCancel').removeClass("inputCancel");
};

function saveBoxPasswordType(e, telement) {
    
    document.getElementById("SAVECONFBTN").disabled = !(telement.value != '');
    if(e.keyCode === 13){
        // Ensure it is only this code that rusn
        e.preventDefault(); 
        // Run Event
        updateActInf();
    }
};

function updateActInf(){
    var xmlhttp = new XMLHttpRequest();
    var email = document.getElementsByClassName("EMAILTXT")[0].value;
    var address = document.getElementsByClassName("ADDRESSTXT")[0].value;
    var city = document.getElementsByClassName("ADDRESSTXT")[1].value;
    var state = document.getElementsByClassName("ADDRESSTXT")[2].value;
    var zip = document.getElementsByClassName("ADDRESSTXT")[3].value;
    var pass = document.getElementsByName("scPASS")[0].value;
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("SAVECH").innerHTML = this.responseText;
            var txt = document.getElementById("SAVEVERI");
    if (txt.innerHTML.toString().includes("Settings Saved")){
            toggleOverlaySave();
    }
        }
    };
    xmlhttp.open("GET", "core/ajax/actSaveChg.php?email="+email+"&address="+address+"&city="+city+"&state="+state+"&zip="+zip+"&pass="+pass, true);
    xmlhttp.send();
};

function logoutUserAJ(){
    
    var xmlhttp = new XMLHttpRequest();
    openCloseLoading();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("LOGOUT").innerHTML = this.responseText;
            window.setTimeout(function() {
                window.location.href = "index.php";
            }, 500);
        }
    };
    xmlhttp.open("GET", "core/ajax/actLogout.php", true);
    xmlhttp.send();
};

function loginUserAJ(){
    
    var xmlhttp = new XMLHttpRequest();
    var usr = document.getElementById("LOGUSERNAME").value;
    var passwrd = document.getElementById("LOGPASSWORD").value;
    
    
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("LOGINCONTENT").innerHTML = this.responseText;
            var cbx = document.getElementById('LOGINCHK').value;
            console.log(cbx);
            if (cbx == 'DONE'){
                window.setTimeout(function() {
                window.location.href = "index.php";
            }, 500);
            }
        }
    };
    xmlhttp.open("GET", "core/ajax/actLogin.php?username="+usr+"&pass="+passwrd, true);
    xmlhttp.send();
};

function closeInvoiceWindow(){
  document.getElementById('invoiceViewer').style.display = 'none';  
}

function showInvoice(invoiceID){
    
    document.getElementById('invoiceViewer').style.display = 'block';
    var id = invoiceID;
    var xmlhttp = new XMLHttpRequest();    
    
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("invoiceViewer").innerHTML = this.responseText;
        }
    };
    xmlhttp.open("GET", "core/ajax/invDis.php?id="+id, true);
    xmlhttp.send();
};

function deleteInvoice(invoiceID){
    if (confirm("You're about to delete invoice: " + invoiceID + " forever are you sure you wish to continue?")) {
    var fdata = new FormData();
    fdata.append('invoiceID', invoiceID);
    
    var xmlhttp = new XMLHttpRequest();    
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("invoiceViewer").innerHTML = this.responseText;
            closeInvoiceWindow();
            reloadAccountRight();
        }
    };
    xmlhttp.open("POST", "core/ajax/deleteInvoice.php", true);
    xmlhttp.send(fdata);
    }
};

function reloadAccountRight(){
    var xmlhttp = new XMLHttpRequest();    
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("ACCOUNTRIGHT").innerHTML = this.responseText;
        }
    };
    xmlhttp.open("POST", "core/ajax/reloadAccountRight.php", true);
    xmlhttp.send();
};

function clearPasswordBox(that){
    if (that.value == "Enter your password"){
        that.value = "";
    } else {
        that.select();
    }
    that.type='password';
}

function clearUsernameBox(that){
    if (that.value == "Enter your username"){
        that.value = "";
    } else {
        that.select();
    }
}

var inputLog = []
function clearTextBox(that, changeToPass=false){
    if (inputLog[that.name] == null){
        inputLog[that.name] = that.value;
    }
    // Change
    if (changeToPass == true){
        that.type = 'password';
    }
    
    // Do Check
    if (that.value == inputLog[that.name]){
        that.value = "";
    } else {
        that.select();
    }
}

function setBtnActive(){
    var f1 = document.getElementsByName('fp_Username')[0].value;
    var f2 = document.getElementsByName('fp_Email')[0].value;
    var enable = false;
    if (f1 != 'Enter username' && f1 != '' && f1.length >= 3){
        enable = true;
    } else {
        enable = false;
    }
    
    if (f2 != 'Enter email address' && f2 != '' && f2.length >= 3 && isValidEmailAddress(f2)){
        enable = true;
    } else {
        enable = false;
    }

    document.getElementById('fp_SendBtn').disabled = !enable;
}

var gloResCd = '';
function sendResetCode(){
            
            var code = Math.random().toString(36).substring(2);
            gloResCd = code;
            var f1 = document.getElementsByName('fp_Username')[0].value;
            var f2 = document.getElementsByName('fp_Email')[0].value;
            var fat = new FormData();
            fat.append('code', code);
            fat.append('username', f1);
            fat.append('email', f2);
            openCloseLoading();
            var xhttp2 = new XMLHttpRequest();
            xhttp2.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("PHPPROCESSOR").innerHTML = this.responseText;
                openCloseLoading();
                document.getElementsByName('fp_Code')[0].disabled = false;
                document.getElementById('fpSubBTN').disabled = false;
                }
            };
    
            xhttp2.open("POST", "core/ajax/resetPwEmail.php", true);
            xhttp2.send(fat);  
}

function updatePassword(){    
            var f1 = document.getElementById('user').value;
            var f2 = document.getElementById('email').value;
            var f3 = document.getElementsByName('cp_password')[0].value;
            var fat = new FormData();
            fat.append('password', f3);
            fat.append('username', f1);
            fat.append('email', f2);
            openCloseLoading();
            var xhttp2 = new XMLHttpRequest();
            xhttp2.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("PHPPROCESSOR").innerHTML = this.responseText;
                openCloseLoading();
                
                }
            };
    
            xhttp2.open("POST", "core/ajax/resetPW.php", true);
            xhttp2.send(fat);  
}

function checkPWMatch(t){
    f1 = document.getElementsByName('cp_password')[0].value;
    if (t.value == f1){
        document.getElementById('cp_SendBtn').disabled = false;
         t.style.borderColor = 'rgba(0, 0, 0, 0.25)';
    } else {
        document.getElementById('cp_SendBtn').disabled = true;
        t.style.borderColor = 'rgba(255, 0, 0, 1)';
    }
}

function verifyCode(){
    var f1 = document.getElementsByName('fp_Code')[0].value;
    var f3 = document.getElementsByName('fp_Username')[0].value;
    var f2 = document.getElementsByName('fp_Email')[0].value;
    if (f1 == gloResCd){
        openCloseLoading();
        var xhttp2 = new XMLHttpRequest();
            xhttp2.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("INNERCONTENT").innerHTML = this.responseText;
                openCloseLoading();
                }
            };
    
            xhttp2.open("GET", "core/pageParts/CHANGEPASSWORD.php?username="+f3+"&email="+f2, true);
            xhttp2.send();  
    } else {
        alert('Incorrect Reset Code.')
    }
}

function reSendVer(){
            var inpt = document.getElementById('HUserName').value;
            var emai = document.getElementsByName('emlTXT')[0].value;
            var fdata = new FormData();
            fdata.append('user', inpt);
            fdata.append('email', emai);
            openCloseLoading();
            var xhttp2 = new XMLHttpRequest();
            xhttp2.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("PHPPROCESSOR").innerHTML = this.responseText;
                openCloseLoading();
                }
            };
    
            xhttp2.open("POST", "core/ajax/resendVerEmail.php", true);
            xhttp2.send(fdata);  
}

function viewBrandDetails(brandID){
            var el = document.getElementById('BDV_PANEL');
           
            
                el.style.visibility = 'visible';
                el.style.opacity = '1';
                el.style.top = 'calc(50% - (390px / 2))';
            
            
            var fdata = new FormData();
            fdata.append('bid', brandID);
            var xhttp2 = new XMLHttpRequest();
            xhttp2.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("BDV_CONTENT").innerHTML = this.responseText;
                }
            };
    
            xhttp2.open("POST", "core/ajax/bdv_loadContent.php", true);
            xhttp2.send(fdata);  
}

function changeBrandDetProd(sku){
            var fdata = new FormData();
            fdata.append('sku', sku);
            var xhttp2 = new XMLHttpRequest();
            xhttp2.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("BDVDISPLAYWINDOW").innerHTML = this.responseText;
                }
            };
    
            xhttp2.open("POST", "core/ajax/bdv_loadProdContent.php", true);
            xhttp2.send(fdata);  
}

function closeBrandDetailViewer(){
     var el = document.getElementById('BDV_PANEL');
           
            
                el.style.visibility = 'hidden';
                el.style.opacity = '0';
                el.style.top = 'calc(50% - (390px))';
}


function subimLoginForm(e){
    if(e.keyCode === 13){
            e.preventDefault(); // Ensure it is only this code that rusn
            loginUserAJ();
        }
}

/* Get Defualt Values of Elements */
function getDefaultValues(id){
    var delement = document.getElementsByClassName(id);
    for (i=0; i < delement.length; i++){
        modifyOriginalValues[id+i] = delement[i].value;
    }
};

/* Reset Defualt Values of Elements */
function resetDefaultValues(id){
    var delement = document.getElementsByClassName(id);
    for (i=0; i < delement.length; i++){
       delement[i].value = modifyOriginalValues[id+i];
    }
};

/* Enables and Disables Target Element */
function enableDisableElement(id){
    var delement = document.getElementsByClassName(id);
    for (i=0; i < delement.length; i++){
        delement[i].disabled = !delement[i].disabled;
    }
};

/* Handle Product Change */

function showAllProducts(){
    var slots = document.getElementsByClassName("productSlot");
    for (i=0; i<slots.length; i++){
        slots[i].style.visibility = 'visible';
            slots[i].style.opacity = 100;
            slots[i].style.height = "auto";
            slots[i].style.width = "256px";
            slots[i].style.margin = "2px";
            slots[i].style.marginRight = "0px";
            slots[i].style.marginTop = "0px";
            slots[i].style.position = "relative";
        var tslot = slots[i].getElementsByTagName('INPUT');
        for (n=0; n<tslot.length; n++){
                tslot[n].disabled = false;
        }
    }
    document.getElementById("BRANDIMAGE").src = "images/brandLogos/ALLBRANDS.png";
    document.getElementById("BRANDNAME").innerHTML = "All Products";
}




function showhideprodInfPan(tid){
    var el = document.getElementById('INFOPANEL_' + tid);
    if (el.style.visibility == 'hidden'){
        el.style.visibility = 'visible';
        el.style.opacity = '1';
        el.style.height = 'calc(100% - 64px)';
    } else {
        el.style.visibility = 'hidden';
        el.style.opacity = '0';
        el.style.height = '0px';
    }
}

function updateProductValues(thatname){
    var cid = thatname;
    var tid = thatname.slice(0, -2);
    var element = document.getElementById(tid);
    var price = parseFloat(document.getElementById("PRICE_"+tid).value);
    var pname = document.getElementById('PNAME_' + tid).value;
    var nodes = element.getElementsByTagName('INPUT');
     var val = 0;
    for (i=0; i<nodes.length; i++){
        if (nodes[i].type == 'number'){
            if (nodes[i].getAttribute("name").includes(tid)){
                val += Number(nodes[i].value);
            }
        }
    }
    
    if (!document.getElementById("ITEM_"+cid)){
        var val1 = document.getElementsByName(cid)[0].value;
        createInvRightEle(cid, tid, val1, price, pname);
    } else {
        var val1 = document.getElementsByName(cid)[0].value;
        document.getElementById("DOPLEINP_"+cid).value = val1;
    }
    
    var totalQTY = 0;
    var totalCost = 0.00;
    var tpItems = document.getElementsByClassName("DOPLEITEMS");
    for (n=0; n<tpItems.length; n++){
        var pstring = tpItems[n].getAttribute('name');
        var sarry = pstring.split("_");
        var price = parseFloat(sarry[0]);
        totalQTY += Number(tpItems[n].value);
        totalCost += (price * Number(tpItems[n].value));
    }
    document.getElementById("ORDERQTY").innerHTML = "Total Quantity: "+totalQTY;
    document.getElementById("ORDERCOST").innerHTML = "Cost: $"+totalCost.toFixed(2);
    
    var total = (val*price).toFixed(2);
    document.getElementById("TOTAL_"+tid).innerHTML = "$" + total;
}

Element.prototype.remove = function() {
    this.parentElement.removeChild(this);
}
NodeList.prototype.remove = HTMLCollection.prototype.remove = function() {
    for(var i = this.length - 1; i >= 0; i--) {
        if(this[i] && this[i].parentElement) {
            this[i].parentElement.removeChild(this[i]);
        }
    }
}

function changeProdValue(nid, value){
    var dopEle = document.getElementsByName(nid)[0];
    dopEle.value = value;
    updateProductValues(nid);
}

function deleteProduct(nid){
     var dopEle = document.getElementsByName(nid)[0];
    var delEle = document.getElementById("ITEM_" + nid);
    dopEle.value = 0;
    updateProductValues(nid);
    delEle.remove();
}
function showHideCommentBox(){
    var overlay = document.getElementById('COMMENTOVERLAY');
    var vis = (overlay.style.visibility == 'hidden');
    if (vis == true){
        overlay.style.visibility = 'visible';
        overlay.style.opacity = '1';
    } else {
        overlay.style.visibility = 'hidden';
        overlay.style.opacity = '0';
    }
}

function enableDisableReg(){
    var checked = document.getElementsByName('reg_termsAgree')[0].checked;
    console.log(checked);
    document.getElementsByName('submitButton')[0].disabled = !checked;
    document.getElementsByName('submitButton')[0].style.opacity = (checked == true) ? '1' : '0.5';
}


function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^(("[\w-+\s]+")|([\w-+]+(?:\.[\w-+]+)*)|("[\w-+\s]+")([\w-+]+(?:\.[\w-+]+)*))(@((?:[\w-+]+\.)*\w[\w-+]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][\d]\.|1[\d]{2}\.|[\d]{1,2}\.))((25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\.){2}(25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\]?$)/i);
    return pattern.test(emailAddress);
};

function signUpAccount(){
    var doAjax = true;
    var userName = document.getElementsByName('reg_userName')[0].value;
    var firstName = document.getElementsByName('reg_firstName')[0].value;
    var lastName = document.getElementsByName('reg_lastName')[0].value;
    var password = document.getElementsByName('reg_password')[0].value;
    var confirmPassword = document.getElementsByName('reg_confPassword')[0].value;
    
    var email = document.getElementsByName('reg_email')[0].value;
    if (!isValidEmailAddress(email)){
        document.getElementById('REG_EMAIL').style.color = '#F95B4B';
        doAjax = false;
    } else {
        document.getElementById('REG_EMAIL').style.color = '#ffffff';
    }
    
    var confEmail = document.getElementsByName('reg_confEmail')[0].value;
    if (!isValidEmailAddress(confEmail) || confEmail != email){
        document.getElementById('REG_CONFEMAIL').style.color = '#F95B4B';
        doAjax = false;
    } else {
        document.getElementById('REG_CONFEMAIL').style.color = '#ffffff';
    }
    
    
    var companyName = document.getElementsByName('reg_companyName')[0].value;
    var phoneNumber = document.getElementsByName('reg_phoneNumber')[0].value;
    var faxNumber = document.getElementsByName('reg_faxNumber')[0].value;
    var streetAddress = document.getElementsByName('reg_streetAddress')[0].value;
    var city = document.getElementsByName('reg_city')[0].value;
    var st = document.getElementsByName('reg_state')[0];
    var state = st.options[st.selectedIndex].value;
    var zipCode = document.getElementsByName('reg_zipCode')[0].value;
    var mo = document.getElementsByName('reg_birthMonth')[0];
    var month = mo.options[mo.selectedIndex].value;
    var d = document.getElementsByName('reg_birthDay')[0];
    var day = d.options[d.selectedIndex].value;
    var y = document.getElementsByName('reg_birthYear')[0];
    var year = y.options[y.selectedIndex].value;
   
    var fdata = new FormData();
    fdata.append('userName', userName);
    fdata.append('firstName', firstName);
    fdata.append('lastName', lastName);
    fdata.append('password', password);
    fdata.append('confPassword', confirmPassword);
    fdata.append('email', email);
    fdata.append('confEmail', confEmail);
    fdata.append('companyName', companyName);
    fdata.append('phoneNumber', phoneNumber);
    fdata.append('faxNumber', faxNumber);
    fdata.append('streetAddress', streetAddress);
    fdata.append('city', city);
    fdata.append('state', state);
    fdata.append('zipCode', zipCode);
    fdata.append('month', month);
    fdata.append('day', day);
    fdata.append('year', year);
    
    if (doAjax == true){
        //F95B4B
     fdata.append('mode', 'check');   
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("REGFORM").innerHTML = this.responseText;
            canSubmit = document.getElementsByName('canSubmit')[0].value;
            fdata.set('mode', 'adding');
            if (canSubmit == 'true'){
                openCloseLoading();
            var xhttp2 = new XMLHttpRequest();
            xhttp2.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
            document.getElementById("REGFORM").innerHTML = this.responseText;
                openCloseLoading();
                }
            };
    
            xhttp2.open("POST", "core/ajax/signUpReg.php", true);
            xhttp2.send(fdata);  
            }
        }
    };
    
    xhttp.open("POST", "core/ajax/signUpReg.php", true);
    xhttp.send(fdata);
        
    }

}

function completeOrder(){
    var items = document.getElementsByClassName('orderRight_Item');
    var skuArray = [];
    for (var i = 0; i<items.length; i++){
        var id = items[i].id.replace('ITEM_', '');
        var count = document.getElementById("DOPLEINP_" + id).value;
        skuArray.push([id, count]);
    }
    if (skuArray.length > 0){
    var comment = document.getElementById('CUSTOMERCOMMENT').value;
    var phparray = JSON.stringify(skuArray);
    // Check if Order has Items
    var fdata = new FormData();
    if (skuArray.length > 0){
    document.getElementById('OUTPUTBOX').style.height = '25%';
    document.getElementById('OUTPUTBOX').innerHTML = "<center><br/>        <h3>Processing Order</h3><br/><img style='background: #1D1F21; border-radius: 100%; padding: 0px;' src='images/adm_loading.svg'/></center>";  
    fdata.append("itemArray", phparray);
    fdata.append("custComment", comment);
      
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("OUTPUTBOX").innerHTML = this.responseText;
                }
            };    
        xmlhttp.open("POST", "core/ajax/orderSubmit.php", true);
        xmlhttp.send(fdata);
        
    } else {
        alert('No products selected to order.')
    }
    } else {
        return alert('No Products Selected.');
    }
}

function showHideFinalOrder(){
    var ordcont = document.getElementsByClassName('orderRight')[0];
    if (ordcont.classList.contains('ORDERCWIN_Closed')){
        ordcont.classList.remove('ORDERCWIN_Closed');
        ordcont.style.right = '0px'
    } else {
        ordcont.classList.add('ORDERCWIN_Closed');
        ordcont.style.right = '-320px'
    }
}

function createInvRightEle(nid, tid, val, price, name){
    var parent = document.getElementById("ORDERFWINDOW");
    // Create Child and Append to Parent
    var node = document.createElement('div');
    node.id = 'ITEM_' + nid;
    node.className = 'orderRight_Item';
    parent.appendChild(node);
    // Create Node's Child and append to node
    var child1 = document.createElement('div');
    child1.className = 'orderRight_Item_Graphic';
    var imgsrc = document.getElementById("PRODUCTIMAGE_" + tid).src;
    child1.innerHTML = "<img src='"+imgsrc+"'/>";
    node.appendChild(child1);
    // Create and Append SKU to Node
    var child2 = document.createElement('h4');
    child2.innerHTML = nid;
    node.appendChild(child2);
    // Create and Append Title to Node
    var child3 = document.createElement('h5');
    child3.innerHTML = name;
    node.appendChild(child3);
    // Create and Append Table to Node
    var child4 = document.createElement('table');
    node.appendChild(child4);
    var c1 = document.createElement('tr');
    child4.appendChild(c1);
    var c2 = document.createElement('td');
    c2.innerHTML = document.getElementById("NICLVLD_"+nid).innerHTML;
    c1.appendChild(c2);
    var c3 = document.createElement('td');
    c3.innerHTML = "<input id='DOPLEINP_"+nid+"' class='DOPLEITEMS' type='number' value='"+val+"' onClick='this.select();' onInput='changeProdValue(`"+nid+"`, this.value)' name='"+price+"_"+nid+"'/>";
    c1.appendChild(c3);
    // Create and Append Delete Button to Node
    var child4 = document.createElement('input');
    child4.setAttribute('type', 'submit');
    child4.setAttribute('onClick', 'deleteProduct(`'+nid+'`)');
    child4.setAttribute('value', 'x');
    node.appendChild(child4);
}

function changeProduct(that){
    var bid = that.replace("BL_", "");
    var element = document.getElementById(that);
    var nodes = element.getElementsByTagName('INPUT');
    var chgImg = "images/brandLogos/ALLBRANDS.png";
    var chgNme = element.getAttribute('name');
    for (i=0; i<nodes.length; i++){
        if (nodes[i].type == 'hidden'){
            if (nodes[i].getAttribute("name") == "brandImage"){
                chgImg = nodes[i].value;
            }            
        }
    }
    /*Set Name and Brand Image*/
    document.getElementById("BRANDIMAGE").src = chgImg;
    document.getElementById("BRANDNAME").innerHTML = chgNme;
    /*De-activate all Products*/
    var slots = document.getElementsByClassName("productSlot");
    for (i=0; i<slots.length; i++){
        
        var tslot = slots[i].getElementsByTagName('INPUT');
    
        if (slots[i].getAttribute('name') != bid){
        slots[i].style.visibility = 'hidden';
        slots[i].style.opacity = 0;
        slots[i].style.height = "0px";
        slots[i].style.width = "0px";
        slots[i].style.margin = "0px";
             slots[i].style.position = "absolute";
            for (n=0; n<tslot.length; n++){
                tslot[n].disabled = true;
            }
        } else {
            slots[i].style.visibility = 'visible';
            slots[i].style.opacity = 100;
            slots[i].style.height = "auto";
            slots[i].style.width = "256px";
            slots[i].style.margin = "2px";
            slots[i].style.marginRight = "0px";
            slots[i].style.marginTop = "0px";
            slots[i].style.position = "relative";
            for (n=0; n<tslot.length; n++){
                tslot[n].disabled = false;
            }
        }
    }
    
    

}

