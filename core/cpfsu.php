<?php
/*
===================================================================================
    Core PHP Fucntions
    ---------------------
    @author Luke A Clark
    lclark4825@gmail.com
    08/06/2017
    ---------------------
    This code was written for use only by myself (Luke) and OPMH and its affiliates.
    If you want to request use of any of it feel free to email me; however, if you choose to use this code without permission legal action may be taken, for it contains sensative information on OPMH and it's Customers. This code is protected under the Creative Commons license.
==================================================================================
*/
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
    require 'PHPMailer.php';
    require 'Exception.php';
    $path = dirname(__FILE__);
    require_once "{$path}/conf.inc.php";
    /*Start a Session*/
    //if ( ! session_id() ) @ session_start();
    
    /*
        OPMH Site class
        -----------------
        Handles all functions associated with the site.
    */
    class OPMH extends Config {
        // Handles construction of Class
        function __construct() {
            parent::__construct();
        }
        
        // Check Login Session
        function sessionSet(){
            return isset($_SESSION['usrLgn']);
        }
        
        // Set Age Verification
        function setAge($dob){
            // Check DOB to age
            if ($dob[1] > 12){
                $dob[1] = 12;
            }
            $from = new DateTime("{$dob[0]}-{$dob[1]}-{$dob[2]}");
            $to   = new DateTime('today');
            $age = $from->diff($to)->y;
            // If Above 18 Set True
            if ($age >= 18){
                $_SESSION['OFAGE'] = true;
            } else {
                $_SESSION['OFAGE'] = false;
            }
        }
        
        // Get Age Verification
        function getAgeVerified(){
            if (isset($_SESSION['OFAGE']) == true){ 
                return $_SESSION['OFAGE'];
            } else {
                return true;
            }
        }
        
        
        // Set Session
        function setSession($user){
            $_SESSION['usrLgn'] = $user;
        }
        
        // Get Session User
        function getSessionUsr(){
            if ($this->sessionSet() == true){
                return $_SESSION['usrLgn'];
            } else {
                return "";
            }
        }
        
        // End Session
        function endSession(){
            $os = $_SESSION['OFAGE'];
            setcookie(session_name(), '', 100);
            session_unset();
            session_destroy();
            $_SESSION = array();
            //if ( ! session_id() ) @ session_start();
            $_SESSION['OFAGE'] = $os;
        }
        

        
        // Get Check if can edit specified invoice
        function isEditMode($invID){
            // Check if a user is logged in
            if ($this->sessionSet() == true){
            // Check if current user is same as Invoice 
            $user = $this->getSessionUsr();
            // Connect to Database
            $this->dbConnect();
            // Perform Query
            $query1 = mysqli_query($this->link, "SELECT invoiceUser FROM invoices WHERE invoiceNumber='$invID' LIMIT 1");
            // Get Results
            $res1 = mysqli_fetch_array($query1); 
            // Check results
            $invUser = $res1['invoiceUser'];
            // Close Connection
            $this->dbCloseConnection();
            // Return Check
                return ($invUser == $user || $this->getUserGroup($user) == "ADM" || $this->getUserGroup($user) == "MOD");
            } else {
                return false;
            }
        }
        
         // Get Session User Group
        function getUserGroup($user = ""){
            if ($this->sessionSet() == true || $user != ""){
            $user = ($this->sessionSet() == true) ? $this->getSessionUsr() : $user;
             // Connect to Database
            $this->dbConnect();
            // Perform Query
            $query = mysqli_query($this->link, "SELECT accountType FROM accounts WHERE username='$user' LIMIT 1");
            // Get Results
            $row = mysqli_fetch_array($query); 
            // Check results
            $grp = $row['accountType'];
            // Close Connection
            $this->dbCloseConnection();
            // Return Check
            return $grp;
            } else {
                return "DEF";
            }
        }
        
        // Check Password to user name
        function passConf($user, $password){
             // Connect to Database
            $this->dbConnect();
            // Perform Query
            $query = mysqli_query($this->link, "SELECT hashKey FROM accounts WHERE username='$user' LIMIT 1");
            // Get Results
            $row = mysqli_fetch_array($query); 
            // Check results
            $errchk = password_verify($password, $row['hashKey']);
            // Close Connection
            $this->dbCloseConnection();
            // Return Check
            return $errchk;
        }
        
        // Check if account verified
        function accountVeri($user){
            // Connect to Database
            $this->dbConnect();
            // Perform Query
            $query = mysqli_query($this->link, "SELECT accountVerified FROM accounts WHERE username='$user' LIMIT 1");
            // Get Results
            $row = mysqli_fetch_array($query); 
            if ($row['accountVerified'] == 'T'){
                return true;
            } else {
                return false;
            }
           // Close Connection
            $this->dbCloseConnection(); 
        }
        
        // update Account Verified
        function verifyAccount($user, $veriID){
            // Connect to Database
            $this->dbConnect();
            // Perform Query
            $query = mysqli_query($this->link, "UPDATE accounts SET accountVerified='T' WHERE username='$user' AND accountVerID='$veriID'");
           // Close Connection
            $this->dbCloseConnection(); 
        }
        
        // Login user
        function loginUser($user, $password){
            // Connect to Database
            $this->dbConnect();
            // Perform Query
            $query = mysqli_query($this->link, "SELECT hashKey FROM accounts WHERE username='$user' LIMIT 1");
            // Get Results
            $row = mysqli_fetch_array($query); 
            // Check results
            $errchk = password_verify($password, $row['hashKey']);
            //
            if ($errchk == true){
                // if Successful
                $refreshedSalt = $this->outputSalt($password);
                $ldate = date("Y\-m\-d");
                mysqli_query($this->link, "UPDATE accounts SET hashKey='$refreshedSalt', logginAttempts=0, lastLogin='$ldate' WHERE username='$user'");
                // chk
                $chkq = mysqli_query($this->link, "SELECT * FROM `loginLog` WHERE date='$ldate' LIMIT 1");
                
                if (mysqli_num_rows($chkq)==0) {
                 mysqli_query($this->link, "INSERT INTO loginlog (date, count) VALUES ('$ldate', 1)");
                } else {
                    mysqli_query($this->link, "UPDATE loginlog SET count=count+1 WHERE date='$ldate'");
                }
                // Start Session
                $this->setSession($user);
            }
            // Close Connection
            $this->dbCloseConnection();
            return $errchk;
        }
        
        // Get Login Data
        function getADMLoginData($m = 0, $y = 0){
            // Connect to Database
            $this->dbConnect();
            // Set ouput array
            $output = [];
            // Get Month and year current
            $m = ($m == 0) ? date('n') : $m;
            $y = ($y == 0) ? date('Y') : $y;
            // Get Number of Days in Month And set up Array
            $days_in_month = cal_days_in_month(CAL_GREGORIAN, $m, $y);
            // Days Array
            $day_array = array();
            $color_array = array();
            $count_array = array();
            // Do for each Day
            for ($d=1; $d<=$days_in_month;$d++){
                // Set Count 0 and date
                $count = 0;
                $date = "{$y}-{$m}-{$d}";
                // Set Array
                array_push($day_array, $d);
                array_push($color_array, "rgba(75,192,192, 0.85)");
                // Perform Query
                $query = mysqli_query($this->link, "SELECT count FROM loginlog WHERE date='$date'");
                // Get Results
                while($row = mysqli_fetch_array($query)){
                    $count = $row['count'];
                } 
                // Add Count for Day
                array_push($count_array, $count);
            }
            $user_count = 0;
            $query2 = mysqli_query($this->link, "SELECT id FROM accounts");
            while($row = mysqli_fetch_array($query2)){
                    $user_count += 1;
             } 
            // Set output
            $output = [$day_array, $color_array, $count_array, $user_count];
            // Close Connection
            $this->dbCloseConnection();
            // Return Obtained Data
            return $output;
        }
        
        
        // Create Salt key
        function outputSalt($input, $cost=11){
        // A higher "cost" is more secure but consumes more processing power
        $options = [
            'cost' => 11
        ];
        // Add Salt
        $passHash = password_hash($input, PASSWORD_BCRYPT, $options);

        // Return Hash + Salt
        return $passHash;
        }

        // Generate SKU
        function generateSKU($length){
            // Set ouput
            $output = "";
            // Generate each number
            for ($i = 0; $i < $length; $i++){
                $k = rand(0, 9);
                $output .= "{$k}";
            }
            return $output;
        }
        
        // Generate Unique Key
        function generateKey($length=9, $strength=4) {
            $vowels = 'aeuoy';
            $consonants = 'bdghjmnpqrstvz';
            if ($strength & 1) {
                $consonants .= 'BDGHJLMNPQRSTVWXZ';
            }
            if ($strength & 2) {
                $vowels .= "AEUOY";
            }
            if ($strength & 4) {
                $consonants .= '23456789';

            }
            if ($strength & 8) {
                $consonants .= 'BDGHJLMNPQRSTVWXZ';
                $consonants .= '23456789';
                $consonants .= '0123456789';
                $vowels .= "EOY";
                $vowels .= "AEUOY";
                $consonants .= 'aBcDeFgHiJkLmNoPqRsTuVwXyZ';
            }
            if ($strength & 10) {
                $consonants .= 'CLMEDLD';

            }
            $key = '';
            $alt = time() % 2;
            for ($i = 0; $i < $length; $i++) {
                if ($alt == 1) {
                    $key .= $consonants[(rand() % strlen($consonants))];
                    $alt = 0;
                } else {
                    $key .= $vowels[(rand() % strlen($vowels))];
                    $alt = 1;
                }
            }
            return $key;
        }
        
        
        // Get Brands list
        function brandList(){
          // Connect to Database
            $this->dbConnect();
            // Perform Query
            $query = mysqli_query($this->link, "SELECT brandName, brandID, brandImageURL FROM brands ORDER BY brandName");
            // Get Results
            while ($row = mysqli_fetch_array($query)){
                echo "<li name='{$row['brandName']}' id='BL_{$row['brandID']}' onclick='changeProduct(this.id)'>
                <input type='hidden' name='brandImage' value='{$row['brandImageURL']}'>
                <input type='hidden' name='brandID' value='{$row['brandID']}'>
                {$row['brandName']}
                </li>";
            } 
             // Close Connection
            $this->dbCloseConnection();
        }
        
        // Get % Data
        function getPercent($old = 0, $new = 0) {
            $inc = $new - $old;
            $num_total = ($old != 0) ? $old : 1;
            $count1 = $inc / $num_total;
            $count2 = $count1 * 100;
            return number_format($count2, 0, '.', '');
        }
        
        // Get user List
        function populateUserList($search = ""){
             // Connect to Database
            $this->dbConnect();
            $search = mysqli_real_escape_string($this->link, $search);
            // Perform Query
            if ($search == ""){
            $query = mysqli_query($this->link, "SELECT username, ID, email, firstName, lastName, companyName, accountVerID, phoneNumber, faxNumber, accountType FROM accounts ORDER BY FIELD(accountType, 'ADM', 'MOD', 'DEF', 'BAN') ASC");
            } else {
            $query = mysqli_query($this->link, "SELECT username, ID, email, firstName, lastName, companyName, accountVerID, phoneNumber, faxNumber, accountType FROM accounts WHERE 
            `username` LIKE '%{$search}%'
            OR `ID` LIKE '%{$search}%'
            OR `email` LIKE '%{$search}%'
            OR `firstName` LIKE '%{$search}%'
            OR `lastName` LIKE '%{$search}%'
            OR `phoneNumber` LIKE '%{$search}%'
            OR `faxNumber` LIKE '%{$search}%'
            OR `companyName` LIKE '%{$search}%'
            OR `accountType` LIKE '%{$search}%'
            OR `accountVerID` LIKE '%{$search}%' ORDER BY FIELD(accountType, 'ADM', 'MOD', 'DEF', 'BAN') ASC");
            }
            
            // Echo Table Titles
            echo "<tr class='titleTop'>
            <td></td>
            <td>ID</td>
            <td>Name</td>
            <td>Email</td>
            <td>Act Typ</td>
            <td>Phone #</td>
            <td></td>
            <td></td>
            <td></td>
            </tr>";
            // Get Results
            while ($row = mysqli_fetch_array($query)){
                echo "
                <tr>
                <td class='inputTableChkBx'><input name='usrChk' value='{$row['accountVerID']}' type='checkbox'/></td>
                <td>{$row['accountVerID']}</td>
                <td><b>{$row['username']}</b><br/>{$row['firstName']}, {$row['lastName']}</td>
                <td>{$row['email']}</td>
                <td>{$row['accountType']}</td>
                <td>";
                echo '('.substr($row['phoneNumber'], 0, 3).') '.substr($row['phoneNumber'], 3, 3).'-'.substr($row['phoneNumber'],6);
                echo "</td>
                <td class='btnSlot'><button onclick='popWindow(`User: {$row['username']}`, `viewUser`, [`{$row['accountVerID']}`], [`35%`, `40%`])'><img src='images/adm_VIEW.svg'/>View</button></td>
                <td class='btnSlot'><button onclick='popWindow(`Change Status`, `changeUserStatus`, [`{$row['accountVerID']}`], [`20%`, `65%`])'><img src='images/adm_Change.svg'/>Change Status</button></td>
                </tr>";
            }
                // Close Connection
            $this->dbCloseConnection();
        }
        
        
        
        // Get user List
        function adm_ViewSelUser($accountID = ""){
            if ($accountID == ""){
                echo "Error - Missing information in database.";
                return;
            }
             // Connect to Database
            $this->dbConnect();
            // Perform Query
            $query = mysqli_query($this->link, "SELECT username, ID, email, firstName, lastName, companyName, accountVerID, phoneNumber, faxNumber, accountType, logginAttempts, streetAddress, cityName, stateName, zipCode, accountVerified, dateEST, birthDate, lastLogin FROM accounts WHERE accountVerID='$accountID' LIMIT 1");

            // Get Results
            $row = mysqli_fetch_array($query);
            // Post data
            echo "
            <div class='adm_userID'>{$row['ID']}</div>
            <div class='adm_userName'>Username: {$row['username']} - Account ID: {$row['accountVerID']}</div>
            <div class='adm_userAccountVerified_{$row['accountVerified']}'></div>
            
            <div class='adm_userAccountLastLog'>Logged In: {$row['lastLogin']}</div>
            
            <div class='adm_userNameAddressInfo'>
            {$row['companyName']}<br/>
            {$row['firstName']}, {$row['lastName']}<br/>
            <i style='color: #3498db;'>{$row['email']}</i><br/>
            {$row['streetAddress']} 
            <br/> {$row['cityName']} {$row['stateName']}
            <br/> {$row['zipCode']}<br/><br/>
            <i class='adm_userAccountType_{$row['accountType']}'><!-- Filled by CSS --></i><br/><i class='adm_userLoginAttempts'>Attempts: {$row['logginAttempts']}</i></div>
            <div class='adm_userPhone'><img src='images/phone_BLK.svg'/>{$row['phoneNumber']}</div>
            <div class='adm_userFax'><img src='images/fax_BLK.svg'/>{$row['faxNumber']}</div>
            
            <div class='adm_userAccountCreatedDt'>Created: {$row['dateEST']}</div>
            ";
            
            // Close Connection<li></li>
//            <li>{$row['faxNumber']}</li>
//            <li>{$row['accountType']}</li>
//            <li>Loggin Attempts: {$row['logginAttempts']}</li>
            $this->dbCloseConnection();
        }
        
        
        // Get Sales Stats for Dashboard
        function getSalesData(){
            // Connect to Database
            $this->dbConnect();
            // Perform Query
            $query = mysqli_query($this->link, "SELECT dateIssued, invoiceTotalCost, invoiceStatus FROM invoices");
            // Set variables
            $y = date('Y');
            $m = date('n');
            $monthly_Data = array();
            $prev_year_monthly_Data = array();
            $invoice_total = 0.0;
            $invoice_total_unpaid = 0.0;
            $perc_from_prev_month = 0.0;
            $output = [];
            for ($i=0; $i<12; $i++){
                $montly_Data[$i] = 0.0;
                $prev_year_monthly_Data[$i] = 0.0;
            }
            
            // Do Loop1 Previous Year
            while ($row = mysqli_fetch_array($query)){
                
                if ((intval(substr($row['dateIssued'], 0, 4)) == ($y-1)) && $row['invoiceStatus'] == "PAID"){
                    $month_index = intval(substr($row['dateIssued'], 5, 2));
                    $prev_year_monthly_Data[$month_index-1] += floatval($row['invoiceTotalCost']);
                }
            
                if ((intval(substr($row['dateIssued'], 0, 4)) == ($y)) && $row['invoiceStatus'] == "PAID"){
                    $month_index = intval(substr($row['dateIssued'], 5, 2));
                    $montly_Data[$month_index-1] += floatval($row['invoiceTotalCost']);
                }
                
                if ($row['invoiceStatus'] == "PAID"){
                    $invoice_total += floatval($row['invoiceTotalCost']);
                } else {
                    $invoice_total_unpaid += floatval($row['invoiceTotalCost']);
                }
            }
            
            $data_1 = $montly_Data[$m-1];
            $data_2 = (($m-2) >= 0) ? $montly_Data[$m-2] : $prev_year_monthly_Data[11];
            
            $perc_from_prev_month = ($data_1 > 0) ? $this->getPercent($data_2, $data_1) : 100.00;
  
            $output = [$montly_Data, $invoice_total, $invoice_total_unpaid, $perc_from_prev_month];
            
            // Close Connection
            $this->dbCloseConnection();
            
            return $output;
        }
        
        // Get Product List
        function adm_ProdList($brandID, $search=""){
            // Connect to Database
            $this->dbConnect();
            // Perform Query
            if ($search == ""){
            $query = mysqli_query($this->link, "SELECT * FROM products WHERE brandID='$brandID' ORDER BY productName DESC");
            } else {
            $query = mysqli_query($this->link, "SELECT * FROM products WHERE brandID='$brandID' AND (uID LIKE '%{$search}%' OR productName LIKE '%{$search}%' OR productSKU LIKE '%{$search}%' OR productCost LIKE '%{$search}%' OR productWholeSaleCost LIKE '%{$search}%') ORDER BY productName DESC");
            }
            // Start Table
            echo "<table>
                <tr class='titleBar'>
                    <td></td>
                    <td>Image</td>
                    <td>Name</td>
                    <td>Nic Lvl</td>
                    <td>SKU</td>
                    <td>Price</td>
                    <td>WS Price</td>
                    <td>BTL Size</td>
                    <td></td>
                </tr>";
            // Do Loop
            while ($row = mysqli_fetch_array($query)){
            // Add Row
            echo "<tr>
                    <td class='chkBox'><input type='checkbox' value='{$row['productSKU']}' name='prodChk'/></td>
                    <td><img src='{$row['productImageURL']}' class='prodImg'/></td>
                    <td>{$row['productName']}</td>
                    <td>{$row['productNicLVL']} mg</td>
                    <td>{$row['productSKU']}</td>
                    <td>{$row['productCost']}</td>
                    <td>{$row['productWholeSaleCost']}</td>
                    <td>{$row['productBotSize']} ML</td>
                    <td class='ebtn'><button onclick='popWindow(`Edit {$row['productName']}`, `editProd`, [`{$row['productSKU']}`])'><img src='images/adm_Edit.svg'>Edit</button></td>
                </tr>";
            }
            // End Table
            echo "</table>";
            // Close Connection
            $this->dbCloseConnection();
        }
        
        function editProd($dat, $tfs){
            // Connect to Database
            $hasFile = ($tfs == "true");
            $this->dbConnect();
            // Get Each Nic LVL
            $sarray = explode(",", str_replace(" ", "", preg_replace("/[^0-9,.]/", "", $dat[6])));
           
            // Query Brand Data
            $bquery = mysqli_query($this->link, "SELECT brandProdDir FROM brands WHERE brandID='{$dat[1]}'");
            $bres = mysqli_fetch_array($bquery);
            // Set Location
            $target_location = "../../../../" . $bres['brandProdDir'];
            if ($hasFile == true){
                $imgurl = $bres['brandProdDir'] . $dat[4]['name'];
                // Upload Image
                $this->uploadFile($dat[4], $target_location, ["png", "gif", "jpeg", "jpg"]);
            }
            // Do For each MG
            $name = mysqli_real_escape_string($this->link, $dat[0]);
            $brid = $dat[1];
            $desc = mysqli_real_escape_string($this->link, $dat[5]);
            $fla = mysqli_real_escape_string($this->link, $dat[9]);
            $pcost = $dat[2];
            $pwscost = $dat[3];
            $nic = $dat[6];
            $pg = $dat[7];
            $vg = $dat[8];
            $botsize = $dat[10];
            $sku = $dat[11];
            // Do Update
            if ($hasFile == true){
            mysqli_query($this->link, "UPDATE `products` SET brandID='$brid', productName='$name', productNicLVL='$nic', productDescription='$desc', productFlavors='$fla', productCost='$pcost', productWholeSaleCost='$pwscost', productBotSize='$botsize', productPG='$pg', productVG='$vg', productImageUrl='$imgurl' WHERE productSKU='$sku'");
            } else {
                mysqli_query($this->link, "UPDATE `products` SET brandID='$brid', productName='$name', productNicLVL='$nic', productDescription='$desc', productFlavors='$fla', productCost='$pcost', productWholeSaleCost='$pwscost', productBotSize='$botsize', productPG='$pg', productVG='$vg' WHERE productSKU='$sku'");
            }
            // Close Connection
            $this->dbCloseConnection(); 
        }
        
        function addNewProd($dat){
            // Connect to Database
            $this->dbConnect();
            // Get Each Nic LVL
            $sarray = explode(",", str_replace(" ", "", preg_replace("/[^0-9,.]/", "", $dat[7])));
           
            // Query Brand Data
            $bquery = mysqli_query($this->link, "SELECT brandProdDir FROM brands WHERE brandID='{$dat[2]}'");
            $bres = mysqli_fetch_array($bquery);
            // Set Location
            $target_location = "../../../../" . $bres['brandProdDir'];

            $imgurl = $bres['brandProdDir'] . $dat[5]['name'];
            // Upload Image
            $this->uploadFile($dat[5], $target_location, ["png", "gif", "jpeg", "jpg"]);
            // Do For each MG
            $name = mysqli_real_escape_string($this->link, $dat[0]);
            $brid = $dat[2];
            $desc = mysqli_real_escape_string($this->link, $dat[6]);
            $fla = mysqli_real_escape_string($this->link, $dat[10]);
            $pcost = $dat[3];
            $pwscost = $dat[4];
            $pg = $dat[8];
            $vg = $dat[9];
            $botsize = $dat[11];
            // Do Loop
            for ($i=0; $i<sizeof($sarray);$i++){
                $sku = $dat[1] . sprintf("%02d", $i);
                $v = $sarray[$i];
                // Perform a query, check for error
		if (!mysqli_query($this->link, "INSERT INTO `products` (`brandID`, `productName`, `productSKU`, `productNicLVL`, `productDescription`, `productFlavors`, `productCost`, `productWholeSaleCost`, `productBotSize`, `productPG`, `productVG`, `productImageUrl`) VALUES ('$brid', '$name', '$sku', '$v', '$desc', '$fla', '$pcost', '$pwscost', '$botsize', '$pg', '$vg', '$imgurl')"))
		  {
		  echo("Error description: " . mysqli_error($this->link));
		  }
            }
            // Close Connection
            $this->dbCloseConnection(); 
        }
        
        // Upload a file
        function uploadFile($file, $target_location, $type_limit=[], $size_limit=500000){
            // Set Data For Transfer and checks
            $target_file = $target_location . $file["name"];
            $uploadOk = 1;
            $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
            // Check if image file is a actual image or fake image
//            if(isset($_POST["submit"])) {
//                $check = getimagesize($file["tmp_name"]);
//                if($check !== false) {
//                    $uploadOk = 1;
//                } else {
//                    $uploadOk = 0;
//                }
//            }
            
            // Check if Director Exists if not create it
            if (!file_exists($target_location)){
                mkdir($target_location);     
                echo 'Making Director';
            }
            
            // Check if file already exists
            if (file_exists($target_file)) {
                $uploadOk = 0;
                echo 'File Exists';
        
            }
            // Check file size
            if ($file["size"] > $size_limit) {
                $uploadOk = 0;
                echo 'File size too damn high';
            }
            
            $isMatch = false;
            
            // Allow certain file formats
            for ($i = 0; $i < sizeof($type_limit); $i++){
                if($imageFileType != $type_limit[$i] && $isMatch == false) {
                    $uploadOk = 0;
                    echo 'Incorrect File Type <br/>';
                } else {
                    $isMatch = true;
                    $uploadOk = 1;
                }
            }
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
            // if everything is ok, try to upload file
            } else {
               move_uploaded_file($file["tmp_name"], $target_file);
            }
        }
        
        function addnewBrand($data){
            // Upload Files and Make Directories
            $this->uploadFile($data[2], "../../../../GCC/", ["pdf"]);
            $this->uploadFile($data[3], "../../../../images/brandLogos/", ["png", "jpg", "jpeg", "gif"]);
            
            if (!file_exists("../../../../images/ProductPictures/{$data[0]}/")){
                mkdir("../../../../images/ProductPictures/{$data[0]}/");
            }
            
            $brandprd = "images/ProductPictures/{$data[0]}/";
            
            $brandurl = "images/brandLogos/" . $data[3]['name'];
            $gccurl = "GCC/" . $data[2]['name'];

            // Connect to Database
            $this->dbConnect();
            // Perform Query
            $bn = $data[0];
            $bid = $data[1];
            $query = mysqli_query($this->link, "INSERT INTO `brands` (`brandName`, `brandID`, `brandGCC`, `brandImageURL`, `brandProdDir`) VALUES ('$bn', '$bid', '$gccurl', '$brandurl', '$brandprd')");
            
            // Close Connection
            $this->dbCloseConnection();
        }
        
        function submitOrder($data, $comment){
            // For each Product
            $totalCost = 0.0;
            $totalQty = 0;
            $timeStamp = date('Y-m-d H:i:s');
            $prodString = "";
            $status = "PENDING";
            $dueDate = date('Y-m-d', strtotime('+30 days'));
            $invoiceNumber = $this->generateSKU(15);
            $username = $this->getSessionUsr();
            // Connect to Database
            $this->dbConnect();
            // Do User Query
            $uquery = mysqli_query($this->link, "SELECT ID, accountVerID FROM accounts WHERE username='$username' LIMIT 1");
            $ures = mysqli_fetch_array($uquery);
            $invuid = $ures['ID'];
            $invuaid = $ures['accountVerID'];
            $comment = mysqli_real_escape_string($this->link, $comment);
            // Get Price and Qty Data
            for ($i = 0; $i < sizeof($data); $i++){
                $sku = $data[$i][0];
                $totalQty += $data[$i][1];
                $pquery = mysqli_query($this->link, "SELECT productWholeSaleCost FROM products WHERE productSKU='$sku' LIMIT 1");
                $pres = mysqli_fetch_array($pquery);
                $totalCost += floatval($pres['productWholeSaleCost']) * floatval($totalQty);
                
                if ($i != 0){
                    $prodString .= "|";
                }
                $prodString .= $sku . "," . $data[$i][1];
                
            }
            // Add Invoice
            mysqli_query($this->link, "INSERT INTO `invoices` (`invoiceNumber`, `dateIssued`, `dueDate`, `invoiceStatus`, `invoiceUser`, `invoiceUserID`, `invoiceUserVerID`, `invoiceTotalQTY`, `invoiceTotalCost`, `invoiceProducts`, `customerComments`) VALUES ('$invoiceNumber', '$timeStamp', '$dueDate', '$status', '$username', '$invuid', '$invuaid', '$totalQty', '$totalCost', '$prodString', '$comment')");
            // Close Connection
            $this->dbCloseConnection();
        }
        
        function updateeditBrand($data){
            // Connect to Database
            $this->dbConnect();
            $bn = $data[0];
            $bid = $data[1];
            echo $bid;
            $query1 = mysqli_query($this->link, "SELECT * FROM brands WHERE brandID='$bid'");
            $row = mysqli_fetch_array($query1);
            $gccurl = $row['brandGCC'];
            $brandurl = $row['brandImageURL'];
            echo $gccurl;
            echo $brandurl;
            // Upload Files and Make Directories
            if ($data[2] != ""){
                    if (file_exists("../../../../" . $row['brandGCC'])){
                        unlink("../../../../" . $row['brandGCC']);
                    }
                    $this->uploadFile($data[2], "../../../../GCC/", ["pdf"]);
                    $gccurl = "GCC/" . $data[2]['name'];
            }
            
            if ($data[3] != ""){
                if (file_exists("../../../../" . $row['brandImageURL'])){
                        unlink("../../../../" . $row['brandImageURL']);
                }
                $this->uploadFile($data[3], "../../../../images/brandLogos/", ["png", "jpg", "jpeg", "gif"]);
                    $brandurl = "images/brandLogos/" . $data[3]['name'];
            }

            
            // Perform Query
            
            $query = mysqli_query($this->link, "UPDATE brands SET brandName='{$bn}', brandGCC='{$gccurl}', brandImageURL='{$brandurl}' WHERE brandID='{$data[1]}'");
            echo "<center>Brand Update.</center><br/>";
            // Close Connection
            $this->dbCloseConnection();
        }
        
        // Get brand list for adm panel
        function editProdWin($id){
            // Connect to Database
            $this->dbConnect();
            // Perform Query
            $query = mysqli_query($this->link, "SELECT brandName, brandID FROM brands ORDER BY brandName DESC");
             $query2 = mysqli_query($this->link, "SELECT * FROM products WHERE productSKU='$id' LIMIT 1");
            // Get brand list for adm panel
            $prod = mysqli_fetch_array($query2);
            $sku = $id;
            
            echo "
            <label>Product Name:</label>
            <input type='text' onfocus='this.value=(this.value==`{$prod['productName']}`) ? `` : this.value' name='productName' value='{$prod['productName']}'/>
            <label>Product SKU:</label>
            <input type='text' maxlength='6' name='productSKU' value='{$sku}' readonly/>
            <label>Choose Brand:</label>
            <select name='brandID'>";
            while ($row = mysqli_fetch_array($query)){
                echo "<option name='brandOpt[]' value='{$row['brandID']}'
                "; 
                
                if ($row['brandID'] == $prod['brandID']){
                    echo " selected";
                }
                
                echo ">{$row['brandName']}</option>";
            }
            
            echo "
            </select>
            <label>Cost:</label>
            <input type='number' name='productCost' value='{$prod['productCost']}' step='0.10' min='0'>
            <label>Wholesale Cost:</label>
            <input type='number' name='productWSCost' value='{$prod['productWholeSaleCost']}' step='0.10' min='0'>
            <label>Product Image (Recommended size 100x150):</label>
            <input type='file' id='IMGUPLD' value='{$prod['productImageURL']}' accept='.gif,.jpg,.jpeg,.png' onchange='readURL(this)' name='productImage'/>
            <img id='SRCIMG' style='opacity: 1;' src='{$prod['productImageURL']}' class='prodprevImg'/>
            <label>Product Description:</label>
            <textarea name='productDesc'>{$prod['productDescription']}</textarea>
            <label>Product Flavors:</label>
            <input type='text' value='{$prod['productFlavors']}' id='FLAVORS'/>
            <label>Product PG/VG:</label>
            <input type='number' name='PG' value='{$prod['productPG']}' step='5' min='0'/> 
            <input type='number' name='VG' value='{$prod['productVG']}' step='5' min='0'/> 
            <label>Product Bottle Size (ML):</label>
            <input type='number' name='botSize' value='{$prod['productBotSize']}' step='5' min='0'/> 
            <label>Nicotine Level:</label>
            <input type='text' name='nicotineLevels' onfocus='this.value=``' value='{$prod['productNicLVL']}'/> 
            <input type='submit' value='Submit' onclick='editProduct()'/>";
            
            // Close Connection
            $this->dbCloseConnection();
        }
        
        // Get brand list for adm panel
        function addProdWin($id){
            // Connect to Database
            $this->dbConnect();
            // Perform Query
            $query = mysqli_query($this->link, "SELECT brandName, brandID FROM brands ORDER BY brandName DESC");
            // Get brand list for adm panel
            
            $sku = $this->generateSKU(6);
            
            echo "
            <label>Product Name:</label>
            <input type='text' onfocus='this.value=(this.value==`Input Product Name`) ? `` : this.value' name='productName' value='Input Product Name'/>
            <label>Product SKU:</label>
            <input type='text' maxlength='6' name='productSKU' value='{$sku}-00' readonly/>
            <label>Choose Brand:</label>
            <select name='brandID'>";
            while ($row = mysqli_fetch_array($query)){
                echo "<option name='brandOpt[]' value='{$row['brandID']}'
                "; 
                
                if ($row['brandID'] == $id){
                    echo " selected";
                }
                
                echo ">{$row['brandName']}</option>";
            }
            
            echo "
            </select>
            <label>Cost:</label>
            <input type='number' name='productCost' value='0.0' step='0.10' min='0'>
            <label>Wholesale Cost:</label>
            <input type='number' name='productWSCost' value='0.0' step='0.10' min='0'>
            <label>Product Image (Recommended size 100x150):</label>
            <input type='file' id='IMGUPLD' accept='.gif,.jpg,.jpeg,.png' onchange='readURL(this)' name='productImage'/>
            <img id='SRCIMG' src='images/adm_IMG.svg' class='prodprevImg'/>
            <label>Product Description:</label>
            <textarea name='productDesc'>Enter a Description here.</textarea>
            <label>Product Flavors:</label>
            <input type='text' value='Input flavor and press ender to add to list' id='CUSTOMINPUT' onfocus='this.value=``' onkeyup='addCustomInput(event)'/>
            <ol id='CUSTOMOUTPUT' name='ProdFlavors' class='CUSTOMOUTPUT'>
            </ol>
            <label>Product PG/VG:</label>
            <input type='number' name='PG' value='-1' step='5' min='0'/> 
            <input type='number' name='VG' value='-1' step='5' min='0'/> 
            <label>Product Bottle Size (ML):</label>
            <input type='number' name='botSize' value='0' step='5' min='0'/> 
            <label>Nicotine Levels (Separate Levels by `, `):</label>
            <input type='text' name='nicotineLevels' onfocus='this.value=``' value='EX: 0, 8, 12 (Produces products for 0mg, 8mg, 12mg)'/> 
            <input type='submit' value='Submit' onclick='admaddProducts()'/>";
            // Close Connection
            $this->dbCloseConnection();
        }
        
        /* 
         * php delete function that deals with directories recursively
         */
        function delete_files($target) {
            if(is_dir($target)){
                $files = glob( $target . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned

                foreach( $files as $file )
                {
                    $this->delete_files( $file );      
                }

                rmdir( $target );
            } elseif(is_file($target)) {
                unlink( $target );  
            }
        }
        
        function deleteProducts($array){
            // Connect to Database
            $this->dbConnect();
            // For each array item
            foreach($array as $value) {
                // Query Brands
                $query = mysqli_query($this->link, "SELECT * FROM products WHERE productSKU='$value'");
                $row = mysqli_fetch_array($query);
                // Get brand Variables
                $prodImg = $row['productImageURL'];
                if (file_exists("../../../../" . $prodImg)){
                // Do Delete
                unlink("../../../../" . $prodImg);
                }
                mysqli_query($this->link, "DELETE FROM products WHERE productSKU='$value'");
            }
            // Close Connection
            $this->dbCloseConnection();
        }
        
        function deleteInvoice($invID){
            // Connect to Database
            $this->dbConnect();
            // Invoice Query
            mysqli_query($this->link, "DELETE FROM invoices WHERE invoiceNumber='$invID'");
            // Close Connection
            $this->dbCloseConnection();
        }
        
        function deleteBrands($array){
            // Connect to Database
            $this->dbConnect();
            // For each array item
  
            foreach($array as $value) {
                // Query Brands
                $query = mysqli_query($this->link, "SELECT * FROM brands WHERE brandID='$value'");
                $row = mysqli_fetch_array($query);
                // Remove all products
                $query2 = mysqli_query($this->link, "DELETE FROM products WHERE brandID='$value'");
                // Get brand Variables
                $brandgcc = $row['brandGCC'];
                $brandimgurl = $row['brandImageURL'];
                $brandprodurl = $row['brandProdDir'];
                
                // Do Delete
                unlink("../../../../" . $brandgcc);
                unlink("../../../../" . $brandimgurl);
                $this->delete_files("../../../../" . $brandprodurl);
                
                mysqli_query($this->link, "DELETE FROM brands WHERE brandID='$value'");
            }
            // Close Connection
            $this->dbCloseConnection();
        }
        
        // Get brand list for adm panel
        function adm_BrandList($search=""){
            // Connect to Database
            $this->dbConnect();
            $search = mysqli_real_escape_string($this->link, $search);
            // Perform Query
            if ($search == ""){
            $query = mysqli_query($this->link, "SELECT * FROM brands ORDER BY brandName DESC");
            } else {
            $query = mysqli_query($this->link, "SELECT * FROM brands WHERE brandName LIKE '%{$search}%' OR brandID LIKE '%{$search}%' ORDER BY brandName DESC");
            }
            // Do Loop
            while ($row = mysqli_fetch_array($query)){
                $query2 = mysqli_query($this->link, "SELECT uID FROM products WHERE brandID='{$row['brandID']}'");
                $cnt = 0;
                while ($row2 = mysqli_fetch_array($query2)){
                   $cnt += 1; 
                }
                
                echo "<li><b><input name='brandChk' value='{$row['brandID']}' type='checkbox'/></b><img src='{$row['brandImageURL']}' class='bimg'/><div  onclick='admloadProductList(`{$row['brandID']}`)'>{$row['brandName']}<i>ID: {$row['brandID']} | {$cnt} Products</i></div><button onclick='popWindow(`Edit {$row['brandName']}`, `popEditBrand`, [`{$row['brandID']}`])'><img src='images/adm_Edit.svg'>Edit</button></li>";
            }
             // Close Connection
            $this->dbCloseConnection();
        }
        
        // Change invoice status
        function changeInvoiceStatusData($data){
            // Connect to Database
            $this->dbConnect();
            for ($i = 0; $i < sizeof($data); $i++){
                // Get Data
                $invId = $data[$i][0];
                $invsv = $data[$i][1];
                echo $invsv;
                if ($invsv != 'DELETE'){
                // Set Data
                mysqli_query($this->link, "UPDATE invoices SET invoiceStatus='$invsv' WHERE invoiceNumber='$invId'");
                } else {
                    $this->deleteInvoice($invId);
                }
            }
            // Close Connection
            $this->dbCloseConnection();
        }
        
        // Brand List
        function adm_GetInvoiceList($search=""){
            // Connect to Database
            $this->dbConnect();
            $search = mysqli_real_escape_string($this->link, $search);
            // Perform Query
            if ($search == ""){
            $query = mysqli_query($this->link, "SELECT * FROM invoices ORDER BY FIELD(invoiceStatus, 'ERROR', 'PENDING', 'PAID') ASC");
            } else {
            $query = mysqli_query($this->link, "SELECT * FROM invoices WHERE 
            `invoiceNumber` LIKE '%{$search}%'
            OR `invoiceStatus` LIKE '%{$search}%'
            OR `dueDate` LIKE '%{$search}%'
            OR `invoiceUser` LIKE '%{$search}%'
            OR `invoiceUserVerID` LIKE '%{$search}%'
            OR `invoiceTotalQTY` LIKE '%{$search}%'
            OR `invoiceTotalCost` LIKE '%{$search}%' ORDER BY FIELD(invoiceStatus, 'ERROR', 'PENDING', 'PAID') ASC, dateIssued ASC");
            }
            echo "<table>
            <tr class='titleTop'>
            <td></td>
            <td>Status</td>
            <td>Invoice Number</td>
            <td>Date Issued</td>
            <td>User</td>
            <td>Total Cost</td>
            <td>Total QTY</td>
            <td></td>
            <td></td>
            <td></td>
            </tr>";
            
            // Do Loop
            while ($row = mysqli_fetch_array($query)){      
                
            echo "
            <tr>";
            if ($row['invoiceStatus'] == "PAID"){
              echo "<td class='inputTableChkBx'><input type='checkbox' name='invChk' value='{$row['invoiceNumber']}'/></td>";
            } else {
                echo "<td class='inputTableChkBx'><input type='checkbox' name='invChk' value='{$row['invoiceNumber']}'/></td>"; 
            }
            
            if ($row['invoiceStatus'] == "PAID"){
            echo "<td style='color: #2ecc71; font-weight: bold;'>{$row['invoiceStatus']}</td>";
            } elseif ($row['invoiceStatus'] == "PENDING") {
            echo "<td style='color: #e67e22; font-weight: bold;'>{$row['invoiceStatus']}</td>";
            } elseif ($row['invoiceStatus'] == "ERROR") {
            echo "<td style='color: #e74c3c; font-weight: bold;'>{$row['invoiceStatus']}</td>";
            } else {
            echo "<td>{$row['invoiceStatus']}</td>";
            }
            echo "<td>{$row['invoiceNumber']}</td>
            <td>{$row['dateIssued']}</td>
            <td>{$row['invoiceUser']}</td>
            <td>$"."{$row['invoiceTotalCost']}"."</td>
            <td>{$row['invoiceTotalQTY']}</td>";
            if ($row['invoiceStatus'] == "PAID"){
                echo "<td class='btnSlot'><button disabled><img src='images/adm_Edit.svg'/>Edit Invoice</button></td>";
            } else {
                echo "<td class='btnSlot'><button><img src='images/adm_Edit.svg' onclick='editInvoice(`{$row['invoiceNumber']}`)'/>Edit Invoice</button></td>";
            }
            echo "<td class='btnSlot'><button onclick='adm_loadInvoiceData(`{$row['invoiceNumber']}`)'><img src='images/adm_VIEW.svg'/>View Invoice</button></td>";
            if ($row['invoiceStatus'] == "PAID"){
                echo "<td class='btnSlot'><button onclick='popWindow(`Change Status`, `changeInvoiceStatus`, [`{$row['invoiceNumber']}`], [`20%`, `65%`])' disabled><img src='images/adm_Change.svg'/>Change Status</button></td>";
            } else {
                echo "<td class='btnSlot'><button onclick='popWindow(`Change Status`, `changeInvoiceStatus`, [`{$row['invoiceNumber']}`], [`20%`, `65%`])'><img src='images/adm_Change.svg'/>Change Status</button></td>";
            }
            echo "</tr>";
            }
            echo "</table>";
             // Close Connection
            $this->dbCloseConnection();
        } 
        
        // Brand List
        function adm_viewInvoice($invNum){
            echo "<img src='images/adm_ManageInvoices.svg' class='cpRRP_BCKDROP'/>";
            $this->displayInvoice($invNum, false, true);
        } 
        
         // Change User status
        function changeUserStatusData($data){
            // Connect to Database
            $this->dbConnect();
            for ($i = 0; $i < sizeof($data); $i++){
                // Get Data
                $userId = $data[$i][0];
                $userat = $data[$i][1];
                $userver = $data[$i][2];
                // Set Data
                mysqli_query($this->link, "UPDATE accounts SET accountType='$userat', accountVerified='$userver' WHERE accountVerID='$userId'");
            }
            // Close Connection
            $this->dbCloseConnection();
        }
        
        // Function Change User Status
        function adm_usrChngStatus($id_array){
            // Connect to Database
            $this->dbConnect();
            foreach ($id_array as $val){
            // Perform Query
            $query = mysqli_query($this->link, "SELECT username, accountVerID, accountType, accountVerified FROM accounts WHERE accountVerID='{$val}' LIMIT 1");
            $row = mysqli_fetch_array($query);
                
                // Draw Info
                echo "<center>
                <label>User: {$row['username']} - {$row['accountVerID']}</label>
                <input type='hidden' value='$val' name='accountVerID'/>
                <select style='cursor: pointer;' name='changeStatusSEL'>";
                
                echo "<option value='ADM' style='color: #e67e22; font-weight: bold;' "; if ($row['accountType'] == "ADM") { echo "selected"; } echo ">Admin</option>
                <option value='MOD' style='color: #9b59b6; font-weight: bold;' "; if ($row['accountType'] == "MOD") { echo "selected"; } echo ">Moderator</option>
                <option value='DEF' style='color: #34495e; font-weight: bold;' "; if ($row['accountType'] == "DEF") { echo "selected"; } echo ">Default</option>
                <option value='BAN' style='color: #e74c3c; font-weight: bold;' "; if ($row['accountType'] == "BAN") { echo "selected"; } echo ">Ban</option>";
               
                echo "</select>
                <select style='cursor: pointer;' name='changeVerSEL'>
                <option value='T' style='color: #2ecc71; font-weight: bold;' "; if ($row['accountVerified'] == "T") { echo "selected"; } echo ">Verified</option>
                <option value='F' style='color: #e74c3c; font-weight: bold;' "; if ($row['accountVerified'] == "F") { echo "selected"; } echo ">Non-Varified</option>
                </select>
                </center>
                ";
                }
            echo "<center><input type='submit' value='Submit' onclick='submitChangeUserStatus()'/></center>";
            // Close Connection
            $this->dbCloseConnection();
        }
        
         // Function Change User Status
        function adm_sendMassEmail($id_array){
            // Connect to Database
            $this->dbConnect();
            foreach ($id_array as $val){
            // Perform Query
            $query = mysqli_query($this->link, "SELECT email, accountVerID FROM accounts WHERE accountVerID='{$val}' LIMIT 1");
            $row = mysqli_fetch_array($query);
                
                // Draw Info
                echo "<input type='hidden' value='$val' name='accountID'/>";
                echo "<input type='hidden' value='{$row['email']}' name='accountEmail'/>";
                }
            echo "
            <input type='text' value='Subject' id='EMAILSUBJECT' onfocus='this.value = (this.value == `Subject`) ? `` : this.value' style='width: 100%;'/>
            <textarea onfocus='this.value = (this.value == `Message`) ? `` : this.value' style='width: 100%; height: 260px; resize: none; padding: 5px;' id='EMAILMESSAGE'>Message</textarea>
            <input type='submit' value='Send Email' onclick='SendMassEmail()' style='width: 100%';/>
            ";
            // Close Connection
            $this->dbCloseConnection();
        }
        
        // Send Mass email
        function sendMassEmail($data, $sub, $mes){
            // Connect to Database
            $this->dbConnect();
            foreach ($data as $val){
            // Perform Query
            $query = mysqli_query($this->link, "SELECT email, firstName, lastName FROM accounts WHERE accountVerID='{$val[0]}' AND email='{$val[1]}' LIMIT 1");
            // Get Request
            $row = mysqli_fetch_array($query);
            // Get server url 

            $message = $mes;
            $message = nl2br($message);
            echo $message;
            $subject = $sub;
            $subject = mysqli_real_escape_string($this->link, $subject);
            //Create a new PHPMailer instance
            $mail = new PHPMailer;
            //Set who the message is to be sent from
            $mail->setFrom('no-reply@opmhproject.com', 'No Reply');
            //Set an alternative reply-to address
            $mail->addReplyTo('no-reply@opmhproject.com', 'No Reply');
            //Set who the message is to be sent to
            $mail->addAddress($row['email'], "{$row['firstName']} {$row['lastName']}");
            //Set the subject line
            $mail->Subject = $subject;
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            $mail->msgHTML($message);
            //Replace the plain text body with one created manually
            $mail->AltBody = $mail->html2text($message);
            //Attach an image file
            //$mail->addAttachment('images/phpmailer_mini.png');
            //send the message, check for errors
            if (!$mail->send()) {
                echo "Mailer Error: " . $mail->ErrorInfo;
            } else {
               // echo "Message sent!";
            }
            }
             // Close Connection
            $this->dbCloseConnection();
        }
        
        // Function Change invoice Status
        function adm_invChngStatus($id_array){
            // Connect to Database
            $this->dbConnect();
            foreach ($id_array as $val){
            // Perform Query
            $query = mysqli_query($this->link, "SELECT * FROM invoices WHERE invoiceNumber='{$val}'");
            $row = mysqli_fetch_array($query);
                if ($row['invoiceStatus'] != "PAID"){
                // Draw Info
                echo "<center>
                <label>Invoice: {$row['invoiceNumber']}</label>
                <input type='hidden' value='$val' name='invoiceIndex'/>
                <select style='cursor: pointer;' name='changeStatusSEL'>";
                
                if ($row['invoiceStatus'] != "PAID"){
                echo "
                <option style='color: #e67e22; font-weight: bold;' value='PENDING'"; if ($row['invoiceStatus'] == "PENDING"){ echo " selected"; } echo">Pending</option>
                <option style='color: #e74c3c; font-weight: bold;' value='ERROR'"; if ($row['invoiceStatus'] == "ERROR"){ echo " selected"; } echo">Payment Issue</option>
                <option style='color: #2ecc71; font-weight: bold;' value='PAID'>Paid</option>
                <option style='background: #ecf0f1;' disabled></option>
                <option style=' font-weight: bold;' value='DELETE'>Delete</option>";
                } else {
                    echo "
                <option style='color: #2ecc71; font-weight: bold;' value='PAID selected>Paid</option>";
                }
                echo "</select>
                </center>
                ";
                }
            }
            echo "<center><input type='submit' value='Submit' onclick='submitChangeInvoiceStatus()'/></center>";
            // Close Connection
            $this->dbCloseConnection();
        }
        
        // Function Edit Brand
        function editBrand($id){
            // Connect to Database
            $this->dbConnect();
            // Perform Query
            $query = mysqli_query($this->link, "SELECT * FROM brands WHERE brandID='$id'");
            $row = mysqli_fetch_array($query);
            
            echo "<label>Brand Name:</label>
<input type='text' onclick='this.value=``' name='brandName' value='{$row['brandName']}'>
<label>Brand ID:</label>
<input type='text' id='BRANDID' name='brandID' value='{$row['brandID']}' readonly>
<label>Brand GCC PDF File:</label>
<input type='file' value='{$row['brandGCC']}' name='brandGCC' accept='.pdf'/>
<label>Brand Logo Image (Images must be 333Wx343H):</label>
<input type='file' id='IMGUPLD' name='brandImage' value='{$row['brandImageURL']}' accept='.gif,.jpg,.jpeg,.png' onchange='readURL(this)'/>
<div class='fpreviewWindow'>
    <img id='SRCIMG' src='{$row['brandImageURL']}' style='opacity: 1;'/>
</div>

<input type='submit' value='Submit' onclick='submiteditBrand()'/>";
            // Close Connection
            $this->dbCloseConnection();
        }
        
        // Get the Popular Products for ADM
        function getPopProdArray(){
            // Connect to Database
            $this->dbConnect();
            // Perform Query
            $query = mysqli_query($this->link, "SELECT invoiceProducts FROM invoices");
            
            $total_arry = [];
            $itm_names = array();
            $total_amount = 0;
            while ($row = mysqli_fetch_array($query)){
            // Product Array
            $strArray = explode("|", $row['invoiceProducts']);
            $invArray = array();
            $skuArray = array();
            $qtyArray = array();
            foreach ($strArray as $value) {
                $v = explode(",", $value);
                array_push($invArray, $v);
            }
            
                for ($n=0; $n < sizeof($invArray); $n++){
                    $qtyArray[$invArray[$n][0]] = $invArray[$n][1];
                    array_push($skuArray, $invArray[$n][0]);
                }
                
                for ($i=0;$i<sizeof($skuArray);$i++){
                    $str = strval($skuArray[$i]);
                    $sstr = substr(strval($skuArray[$i]), 0, -2);
                    if(!array_key_exists($str, $total_arry)){
                        $total_arry[$str] = $qtyArray[$skuArray[$i]];
                        $total_amount += $qtyArray[$skuArray[$i]];
                        $q = mysqli_query($this->link, "SELECT `productName` FROM products WHERE productSKU=$str");
                        $r = mysqli_fetch_array($q);
                        $r = rand(54, 255);
                        $g = rand(54, 255);
                        $b = rand(54, 255);
                    } else {
                        $total_arry[$str] += $qtyArray[$skuArray[$i]];
                        $total_amount += $qtyArray[$skuArray[$i]];
                    }
                }
                
            }
            
            
            // Do sorting   
            arsort($total_arry);
            // Get Name for Each Product
            foreach ($total_arry as $key => $value){
                $q = mysqli_query($this->link, "SELECT `productName` FROM products WHERE productSKU='$key'");
                $r = mysqli_fetch_array($q);
                array_push($itm_names, $r['productName']);
            }
            $total_arry = array_values($total_arry);
            $total_arry = array_slice($total_arry, sizeof($total_arry)-5);
            $itm_names = array_slice($itm_names, sizeof($itm_names)-5);
            // Close Connection
            $this->dbCloseConnection();
            // Set Results and return
            return $results = [$total_arry, $itm_names, $total_amount];
        }
        
        // Get Order Item List for Edit
        function getInvoiceOrderRightDetails($invID){
            // Connect to Database
            $this->dbConnect();
            // Perform Query
            $invquery = mysqli_query($this->link, "SELECT invoiceProducts, invoiceTotalCost, invoiceTotalQTY, customerComments FROM invoices WHERE invoiceNumber='$invID' LIMIT 1");
            $r1 = mysqli_fetch_array($invquery);
            $ordDet = $r1['invoiceProducts'];
            echo "<div class='orderRight_Title' id='ORDERCOST'>Cost: \${$r1['invoiceTotalCost']}</div>
    <div class='orderRight_Title' id='ORDERQTY'>Total Quanitity: {$r1['invoiceTotalQTY']}</div>
    <div class='orderRight_Title'>Order Details</div> 
    <script>
        document.getElementById('CUSTOMERCOMMENT').value = '{$r1['customerComments']}';
    </script>";
            // Explode Order Details
            $strArray = explode("|", $ordDet);
            for ($i = 0; $i < sizeof($strArray); $i++){
                // Get Product SKU and QTY
                $data = explode(",", $strArray[$i]);
                // Query Product
                $pquery = mysqli_query($this->link, "SELECT * FROM products WHERE productSKU='{$data[0]}' LIMIT 1");
                // Get Query Results
                $r2 = mysqli_fetch_array($pquery);
                // Echo details to page
                echo "
                <div id='ITEM_{$r2['productSKU']}' class='orderRight_Item'>
                <div class='orderRight_Item_Graphic'>
                <img src='{$r2['productImageURL']}'>
                </div>
                <h4>{$r2['productSKU']}</h4>
                <h5>{$r2['productName']}</h5>
                <table>
                <tr>
                <td>{$r2['productNicLVL']} MG </td>
                <td>
                <input id='DOPLEINP_{$r2['productSKU']}' class='DOPLEITEMS' type='number' value='{$data[1]}' onclick='this.select();' oninput='changeProdValue(`{$r2['productSKU']}`, this.value)' name='{$r2['productWholeSaleCost']}_{$r2['productSKU']}'>
                </td>
                </tr>
                </table>
                <input type='submit' onclick='deleteProduct(`{$r2['productSKU']}`)' value='x'></div>
                <script>
                 changeProdValue(`{$r2['productSKU']}`, `{$data[1]}`);
                </script>
                ";
            }    
            // Close Connection
            $this->dbCloseConnection();
        }
        
        // Get Product list
        function productList(){
          // Connect to Database
            $this->dbConnect();
            // Perform Query
            $brandquery = mysqli_query($this->link, "SELECT brandID FROM brands ORDER BY brandName");
            $brow = mysqli_fetch_array($brandquery);
            $query = mysqli_query($this->link, "SELECT * FROM products GROUP BY productName");
            // Get Results
            while ($row = mysqli_fetch_array($query)){
                $sku = substr($row['productSKU'], 0, -2);
                echo "<div name='{$row['brandID']}' id='{$sku}' class='productSlot'>
        <div class='imgslot'><img id='PRODUCTIMAGE_{$sku}' src='{$row['productImageURL']}'/></div>
        <span>SKU: ".$sku."<h6>PG/VG Ratio</h6>
            <h6>{$row['productPG']}% PG / {$row['productVG']}% VG</h6></span>
        
        <input type='hidden' value='{$row['productName']}' id='PNAME_{$sku}'/>
        <h4>{$row['productName']} <i>Retail: $".$row['productCost']."</i></h4>
        <h5>
        <div class='infButton' onclick='showhideprodInfPan(`{$sku}`)' title='More Info'></div>
        Base WS: $".$row['productWholeSaleCost']."</h5>
        <input type='hidden' value='{$row['productWholeSaleCost']}' id='PRICE_{$sku}'/>
        
         <div class='productSlot_InfoHoldr' id='INFOPANEL_{$sku}' >
         <div class='productSlot_InfoCont'>
            <h6>Description</h6>
            <p>{$row['productDescription']}</p>
        </div>
            
            <div class='productSlot_InfoCont'>
            <h6>Primary Flavors</h6>
            <p>{$row['productFlavors']}</p>
        </div>
         </div> 
        
        <div class='productSlot_Content'>
        
        <table class='orderTable'>
        
        <tr>
        <td class='orderTable_Title'>Nic Lvl</td>
        <td class='orderTable_Title'>Qty</td>
        </tr>";
        
            $nquery = mysqli_query($this->link, "SELECT * FROM `products` WHERE `productSKU` LIKE '$sku%'");
            // Get Results
            while ($row2 = mysqli_fetch_array($nquery)){
            echo "
            <tr>
            <td id='NICLVLD_{$row2['productSKU']}' class='orderTable_NicL'>{$row2['productNicLVL']} MG </td>
            <td><input type='number' id='{$brow['brandID']}' onClick='this.select();' oninput='updateProductValues(`{$row2['productSKU']}`)' name='{$row2['productSKU']}' value='0' tabindex='1' min='0' max='8250'/></td>
            </tr>";
            }
            echo "
        </table>
            
           <div class='productSlot_TotalPrice' id='TOTAL_{$sku}'>$0.00</div>
            
            </div>
        </div>";
                
            } 
             // Close Connection
            $this->dbCloseConnection();
        }
                
        
        // Send password reset email
        function sendVerificationEmail($user, $email){
            // Connect to Database
            $this->dbConnect();
            // Perform Query
            $query = mysqli_query($this->link, "SELECT email, username, firstName, lastName, accountVerID FROM accounts WHERE username='$user' AND email='$email' LIMIT 1");
       
            // Get Request
            $row = mysqli_fetch_array($query);
            // Get server url 
//
//            $message = "
//            <html>
//            <head>
//            </head>
//            <body>
//            <center>
//            <h2>Thank You!</h2>
//            <hr style='border-bottom: 2px dotted rgba(0, 0, 0, 0.35); width: 30%; margin-top: 10px; margin-bottom: 10px;'/>
//            <br/>
//            <p>{$row['firstName']} {$row['lastName']}, thank you for taking the time to create an account with us. Before you can begin to use our ordering system we require that you verify your email address.
//            <br/>
//            <br/>
//            <h4>Why?</h4>
//            We do this to verify that you're human, and to make sure we will be sending all the information to the right person. Your email is also used to send you password reset requests/Forgot password requests.
//            <br/>
//            <br/>
//            <h4>Usage:</h4>
//            At no time will we be sending your personal information to a 3rd party for any monitary gain.
//            <br/>
//            <h4>What to do next?</h4>
//            Next we ask that you please click the verification link shown below, you will be taken to the OPMH website where your email will be varified and you will be able to login and begin setting up your first order.
//            </p>
//            <br/>
//            <hr style='border-bottom: 2px dotted rgba(0, 0, 0, 0.35); width: 30%; margin-top: 10px; margin-bottom: 10px;'/>
//            <table>
//            <tr>
//            <th>Verification Link</th>
//            </tr>
//            <tr>
//            <td><a href='https://opmhproject.com/verifyAccount.php?user={$user}&id={$row['accountVerID']}'>https://opmhproject.com/verifyAccount.php?user={$user}&id={$row['accountVerID']}</a></td>
//            </tr>
//            </table>
//            <br/>
//            <hr style='border-bottom: 2px dotted rgba(0, 0, 0, 0.35); width: 30%; margin-top: 10px; margin-bottom: 10px;'/>
//            <br/>
//            </center>
//            </body>
//            </html>
            // v_safin8442@godaddy.com
//            ";
            
            $message = "
            <html>
            <head>
            <title>OPMH Account Verification - Do Not Reply -</title>
            </head>
            <body>
            <center>
            <hr style='border-bottom: 2px dotted rgba(0, 0, 0, 0.35); width: 30%; margin-top: 10px; margin-bottom: 10px;'/>
            <h2>Thank You {$row['firstName']} {$row['lastName']}!</h2>
            <hr style='border-bottom: 2px dotted rgba(0, 0, 0, 0.35); width: 30%; margin-top: 10px; margin-bottom: 10px;'/>
            <p><b>You're on your final step to becoming a verified member of OPMH Project, if you have any questions or concerns feel free to check out our <a href='https://opmhproject.com/contact.php' title='contact us' target='_blank'>Contact Us</a> page.</b></p>
            <hr style='border-bottom: 2px dotted rgba(0, 0, 0, 0.35); width: 20%; margin-top: 10px; margin-bottom: 10px;'/>
            <p>Click the link below to be taken to the account verificaiton page:<br/>
            <hr style='border-bottom: 2px dotted rgba(0, 0, 0, 0.35); width: 30%; margin-top: 10px; margin-bottom: 10px;'/>
            <br/>
            <h3><a href='https://opmhproject.com/verifyAccount.php?user={$user}&id={$row['accountVerID']}'>https://opmhproject.com/verifyAccount.php?user={$user}&id={$row['accountVerID']}</a></h3></p>
            </center>
            </body>
            </html>
            ";
            $message = mysqli_real_escape_string($this->link, $message);

            //Create a new PHPMailer instance
            $mail = new PHPMailer;
            //Set who the message is to be sent from
            $mail->setFrom('no-reply@opmhproject.com', 'No Reply');
            //Set an alternative reply-to address
            $mail->addReplyTo('no-reply@opmhproject.com', 'No Reply');
            //Set who the message is to be sent to
            $mail->addAddress($row['email'], "{$row['firstName']} {$row['lastName']}");
            //Set the subject line
            $mail->Subject = 'OPMH Password Reset - Do Not Reply -';
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            $mail->msgHTML($message);
            //Replace the plain text body with one created manually
            $mail->AltBody = $mail->html2text($message);
            //Attach an image file
            //$mail->addAttachment('images/phpmailer_mini.png');
            //send the message, check for errors
            if (!$mail->send()) {
                echo "Mailer Error: " . $mail->ErrorInfo;
            } else {
               // echo "Message sent!";
            }
             // Close Connection
            $this->dbCloseConnection();
        }
        
         // Send password reset email
        function sendPWReset($user, $email, $code){
            // Connect to Database
            $this->dbConnect();
            // Perform Query
            $query = mysqli_query($this->link, "SELECT email, accountVerID, username, firstName, lastName FROM accounts WHERE username='$user' AND email='$email' LIMIT 1");
            // Get Request
            $row = mysqli_fetch_array($query);
            // Get server url 

            $message = "
            <html>
            <head>
            <title>OPMH Password Reset - Do Not Reply -</title>
            </head>
            <body>
            <center>
            <p>This email was sent as an attempt to change an account password.<br/> <i>If this email was not sent by you please contact and of the OPMH staff <b>immediately!</b></i></p>
            <hr style='border-bottom: 2px dotted rgba(0, 0, 0, 0.35); width: 30%; margin-top: 10px; margin-bottom: 10px;'/>
            <h2>Thank You!</h2>
            <hr style='border-bottom: 2px dotted rgba(0, 0, 0, 0.35); width: 30%; margin-top: 10px; margin-bottom: 10px;'/>
            <p>Use the code below to reset your password:<br/>
            <hr style='border-bottom: 2px dotted rgba(0, 0, 0, 0.35); width: 30%; margin-top: 10px; margin-bottom: 10px;'/>
            <br/>
            <h3>{$code}</h3></p>
            </center>
            </body>
            </html>
            ";
            

            //Create a new PHPMailer instance
            $mail = new PHPMailer;
            //Set who the message is to be sent from
            $mail->setFrom('no-reply@opmhproject.com', 'No Reply');
            //Set an alternative reply-to address
            $mail->addReplyTo('no-reply@opmhproject.com', 'No Reply');
            //Set who the message is to be sent to
            $mail->addAddress($row['email'], "{$row['firstName']} {$row['lastName']}");
            //Set the subject line
            $mail->Subject = 'OPMH Password Reset - Do Not Reply -';
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            $mail->msgHTML($message);
            //Replace the plain text body with one created manually
            $mail->AltBody = $mail->html2text($message);
            //Attach an image file
            //$mail->addAttachment('images/phpmailer_mini.png');
            //send the message, check for errors
            if (!$mail->send()) {
                echo "Mailer Error: " . $mail->ErrorInfo;
            } else {
               // echo "Message sent!";
            }
             // Close Connection
            $this->dbCloseConnection();
        }
        
         
        
        
        // Send verification email
        function sendInvoiceEmail($user){
            // Connect to Database
            $this->dbConnect();
            // Perform Query
            $query = mysqli_query($this->link, "SELECT email, firstName, lastName, accountVerID, username FROM accounts WHERE username='$user' LIMIT 1");
            // Get Request
            $row = mysqli_fetch_array($query);
            // Get server url 
            $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
            // Send Email
            $to = "{$row['email']}";
            $subject = "OPMH Account Verification - Do Not Reply -";

            $message = "
            <html>
            <head>
            <title>OPMH Account Verification - Do Not Reply -</title>
            </head>
            <body>
            <p>Hello {$row['firstName']} {$row['lastName']}, thank you for taking the time to create an account with us over at OPMH, we hope that our products and services are to your liking, and that our systems are easy to use. Before you start ordering we only ask that you please take time to verify your account email. Doing so will help us in better assisting you in any case where we may need to contact you.</p>
            <table>
            <tr>
            <th>Verification Link</th>
            </tr>
            <tr>
            <td>{$actual_link}/verifyAccount.php?user={$user}&id={$row['accountVerID']}</td>
            </tr>
            </table>
            <p>If you have any problems feel free to check out our contact page, where you can get in-touch with people who can help.</p>
            </body>
            </html>
            ";

            // Always set content-type when sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $nstr = str_replace("http://","",$actual_link);
            // More headers
            $headers .= 'From: <noreply@'.$nstr.'>' . "\r\n";
            // Mail Stuff
            $result = mail($to,$subject,$message,$headers);
            if(!$result) {   
                  //  echo "Error";   
                } else {
                  //  echo "Success";
             }
             // Close Connection
            $this->dbCloseConnection();
        }
        
        
        
        
        
        
        // Brand List
        function getBrandPageList(){
            // Connect to Database
            $this->dbConnect();
            // Perform Query
            $query = mysqli_query($this->link, "SELECT brandName, brandImageURL, brandGCC, brandID FROM brands ORDER BY brandName");
            while ($row = mysqli_fetch_array($query)){
                $t = 0;
                $brandId = $row['brandID'];    
                $pquery = mysqli_query($this->link, "SELECT `productName` FROM `products` WHERE brandID='".$brandId."' GROUP BY `productName`");
                while ($row2 = mysqli_fetch_array($pquery)){
                    $t += 1;
                }
                //onclick='window.open(`{$row['brandGCC']}`, `_blank`, `location=yes,height=960,width=720,scrollbars=yes,status=yes`);'
                echo "<div class='brandBox' onclick='viewBrandDetails(`{$brandId}`)'>
                    <img src='{$row['brandImageURL']}'/>
                    <h4>{$row['brandName']} (".$t.")</h4>
                    </div>";
            }
            //
            $this->dbCloseConnection();
        }
        
        // Brand List
        function getBrandDetails($brandID){
            // Connect to Database
            $this->dbConnect();
            // Perform Query
            $query = mysqli_query($this->link, "SELECT * FROM brands WHERE brandID='$brandID' LIMIT 1");
            $row = mysqli_fetch_array($query);
            //
                $t = 0;  
                $pquery = mysqli_query($this->link, "SELECT `productName` FROM `products` WHERE brandID='".$brandID."' GROUP BY `productName`");
                while ($row2 = mysqli_fetch_array($pquery)){
                    $t += 1;
                }
            echo "<div class='bdv_lct'>
                    <div class='logoWatermark'>
                    <img src='{$row['brandImageURL']}'/>
                    </div>
                    <h4>{$t}</h4>
                    <div class='bdv_button' onclick='window.open(`{$row['brandGCC']}`, `_blank`, `location=yes,height=960,width=720,scrollbars=yes,status=yes`);'>Open GCC .PDF <img src='images/downloadPDF.svg'/></div>
                </div>
                <div class='bdv_rtct'>
                    <div class='bdv_titleBar'>
                        <h4>Brand Title</h4>
                        <button class='bdv_close' onclick='closeBrandDetailViewer()'>X</button>
                    </div>
                    <div class='bdv_split'>
                        <!--Displays Product List-->
                        <div class='bdv_sLeft'>
                        <ul>
                        ";
                        $prquery = mysqli_query($this->link, "SELECT productName, productSKU, productImageURL FROM `products` WHERE brandID='".$brandID."' GROUP BY `productName`");
                        while ($row3 = mysqli_fetch_array($prquery)){
                           echo "<li onclick='changeBrandDetProd(`{$row3['productSKU']}`)'><div class='bdv_imgPrHd'><img src='{$row3['productImageURL']}'/></div>{$row3['productName']}</li>"; 
                        }
                        echo "</ul>
                        </div>
                        <!--Displays Product Info-->
                        <div class='bdv_sRight' id='BDVDISPLAYWINDOW'>
                            <h6>Click a product for more details.</h6>
                        </div>
                    </div>
                </div>
                ";
            //
            $this->dbCloseConnection();
        }
        
        // Brand List
        function getBrandDetProd($prodSKU){
            // Connect to Database
            $this->dbConnect();
            // Perform Query
            $query = mysqli_query($this->link, "SELECT * FROM products WHERE productSKU='{$prodSKU}' LIMIT 1");
            $row = mysqli_fetch_array($query);
            
            echo "<div class='bdv_prodImg'>
                        <img src='{$row['productImageURL']}'/>    
                        </div>
                        <h1>{$row['productName']}</h1>
                        <h2>SKU: {$row['productSKU']}
                            <br/>
                            PG/VG RATIO
                            <br/>
                            {$row['productPG']}% PG / {$row['productVG']}% VG

                            </h2>
                        <h4>Nicotine Levels:
                        <select>";
              
                        $sku = substr($prodSKU, 0, -2);
                        $pquery = mysqli_query($this->link, "SELECT `productNicLVL` FROM `products` WHERE productSKU LIKE '{$sku}%'");
                        while ($row2 = mysqli_fetch_array($pquery)){
                            echo "<option>{$row2['productNicLVL']} MG</option>";
                        }
                            
                        echo "</select>
                        </h4>
                        
                        <h3><b>Flavors:</b>
                            {$row['productFlavors']}
                            </h3>
                        <div class='bdv_prodDesc'>{$row['productDescription']}</div>";
            
            
            //
            $this->dbCloseConnection();
        }
        
        
        // Update Account Info
        function updateAccount($user, $email, $address, $city, $state, $zip){
            // Connect to Database
            $this->dbConnect();
            // Perform Query
            mysqli_query($this->link, "UPDATE accounts SET email='$email', streetAddress='$address', cityName='$city', stateName='$state', zipCode='$zip' WHERE username='$user'");
             // Close Connection
            $this->dbCloseConnection();
        }
        
        /*Display Invoice On Pop-up or Solo Page*/
        function displayInvoice($id, $isSolo = false, $adm_ovrride = false){
             // Connect to Database
            $this->dbConnect();
            // Get User
            $user = $this->getSessionUsr();
            if ($user == "" && $adm_ovrride != true){
                 echo "<meta http-equiv='refresh' content='0;url=index.php' />";
            }
            
            if ($user != "" || $adm_ovrride == true){
            // Perform Query
            if ($adm_ovrride == false){
            $query = mysqli_query($this->link, "SELECT * FROM invoices WHERE invoiceUser='$user' AND invoiceNumber='$id' LIMIT 1");
            } else {
            $query = mysqli_query($this->link, "SELECT * FROM invoices WHERE invoiceNumber='$id' LIMIT 1");
            }
            // Get Invoice Results
            $row = mysqli_fetch_array($query);
            if ($adm_ovrride == true){
                $user = $row['invoiceUser'];
            }
            // Do User Info Query
            $uquery = mysqli_query($this->link, "SELECT firstName, lastName, companyName, streetAddress, cityName, stateName, zipCode FROM accounts WHERE username='$user' AND ID='{$row['invoiceUserID']}' AND accountVerID='{$row['invoiceUserVerID']}' LIMIT 1");
            // Get Results for uQuery
            $row2 = mysqli_fetch_array($uquery);
          
            if ($isSolo == false && $adm_ovrride == false){
            echo "<div class='invoiceTopper'>";
        if ($row['invoiceStatus'] != 'PAID'){
        echo "<button title='Delete Invoice Forever' onclick='deleteInvoice(`{$id}`)'><img src='images/inv_Delete.svg'/></button>
        <button title='Edit Invoice' onclick='window.open(`order.php?mode=edit&invid={$id}`, `_self`);'><img src='images/Invoice_BLK.svg'/></button>";
        };
        echo "<button title='Print Invoice' onclick='window.open(`InvoicePopPage.php?id={$id}`, `_blank`);'><img src='images/openNew_BLK.svg'/></button>
        <button  title='Close Popup' onClick='closeInvoiceWindow()'><img src='images/close_BLK.svg'/></button>
    </div>";
            }
      
    if ($isSolo == false){
    echo "
    <div id='invoiceContent'>";
    }else {
    echo "
    <div id='invoiceContent' style=' height: auto !important; overflow-y: auto !important; page-break-after:always; background: #fff;'>";
    }
                
    echo "<img class='invLogo' src='images/Logo_002.png'/>
    <h2>
        INVOICE
        <table>
            <tr>
            <th>Date</th>
            <td>
            {$row['dateIssued']}
             </td>
            </tr>
            <tr>
            <th>Invoice ID</th>
            <td>
            {$row['invoiceNumber']}
             </td>
            </tr>
            <tr>
            <th>Customer ID</th>
            <td>
            {$row['invoiceUserVerID']}
             </td>
            </tr>
        </table>
        </h2>
    <div class='invoiceDivider'>
        <div class='invdivLeft'>
        <ul>
        <li><h4>Customer Contact</h4></li>
        <li>{$row2['companyName']}</li>
        <li>{$row2['firstName']}, {$row2['lastName']}</li>
        <li>{$row2['streetAddress']}</li>
        <li>{$row2['cityName']}, {$row2['stateName']}</li>
        <li>{$row2['zipCode']}</li>
        </ul>
        </div>
        <div class='invdivRight'>
        
            <ul>
        <li><h4>Customer Comments</h4></li>
        <li>{$row['customerComments']}</li>
        </ul>
        </div>
    </div>
        <br class='clear'/>
        
        <table class='invdataTable'>
            <tr class='invdataTableTitles'>
                <td class='invdataTableQty'>SKU</td>
                <td>Item</td>
                <td class='invdataTableQty'>Nic. LVL</td>
                <td class='invdataTableQty'>QTY</td>
                <td class='invdataTableQty'>Unit Price</td>
                <td class='invdataTableQty'>Amount</td>
            </tr>";
            /* Get Each Product from Invoice */
            // Product Array
            $strArray = explode("|", $row['invoiceProducts']);
            $invArray = array();
            $skuArray = array();
            $qtyArray = array();
            foreach ($strArray as $value) {
                $v = explode(",", $value);
                array_push($invArray, $v);
            }
            
            for ($n=0; $n < sizeof($invArray); $n++){
                $qtyArray[$invArray[$n][0]] = $invArray[$n][1];
                array_push($skuArray, $invArray[$n][0]);
            }
            // Product Query
            $kv = implode(",", $skuArray);
            $pquery = mysqli_query($this->link, "SELECT * FROM products WHERE productSKU IN ({$kv})");
            // Do for Results
            $ot = false;
            $tcost = 0;
            $tqty = 0;
            while ($prow = mysqli_fetch_array($pquery)){
                $cost = intval($qtyArray[$prow['productSKU']]) * intval($prow['productWholeSaleCost']);
                $tcost += $cost;
                $tqty += intval($qtyArray[$prow['productSKU']]);
            if ($ot == false){
            echo "<tr>";
            } else {
            echo "<tr class='invdataTableOther'>";
            }
            echo "
                <td>{$prow['productSKU']}</td>
                <td>{$prow['productName']}</td>
                <td>{$prow['productNicLVL']} MG</td>
                <td>{$qtyArray[$prow['productSKU']]}</td>
                <td>$".$prow['productWholeSaleCost']."</td>
                <td>$".$cost."</td>
                </tr>
                ";
                $ot = !$ot;
            }
            /* Echo Total QTY and COST */
            echo "<tfoot><tr class='invdataTotal'>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class='invTD'>Quantity: </td>
                <td class='invTD'>{$tqty}</td>
            </tr>
            <tr class='invdataTotal'>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class='invTD'>Total: </td>
                <td class='invTD'>$".$row['invoiceTotalCost']."</td>
            </tr></tfoot>
        </table>
        <br class='break'/><center>Invoices must be paid in full by {$row['dueDate']}.</center><br/><br/>
    </div>";
            }
            
            // Close Connection
            $this->dbCloseConnection();
        }
        
        /*Return a list of states for option dropdown*/
        function getStates($selectedvalue=''){
            // Get User
            $user = $this->getSessionUsr();
            // Connect to Database
            $this->dbConnect();
            // Perform Query
            $userQuery = mysqli_query($this->link, "SELECT stateID, stateName FROM states");
            // Get Results
            while ($row = mysqli_fetch_array($userQuery)){
                if ($selectedvalue == $row['stateID']){
                    echo "<option value='{$row['stateID']}' selected>{$row['stateID']} - {$row['stateName']}</option>";
                } else {
                    echo "<option value='{$row['stateID']}'>{$row['stateID']} - {$row['stateName']}</option>";
                }
            }
             // Close Connection
            $this->dbCloseConnection();
        }
        
        
        // Check Registration Info
        function checkReg($data){
          $canSubmit = true;
         // Connect to Database
         $this->dbConnect();
            // Perform Query
            $uquery = mysqli_query($this->link, "SELECT username FROM accounts WHERE username='{$data[0]}'");
            $uqres = mysqli_fetch_array($uquery);
            // Close Connection
            $this->dbCloseConnection();
         echo "    
        <div class='regFormleft'>
        <!--Input Field-->";
            
        
        if ($data[0] == '' || $data[0] == 'Enter a username' || sizeof($uqres) >= 1 || preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $data[0]) || strlen($data[0]) <= 3 || strpos($data[0], ' ') !== false){
            echo "<label id='REG_USERNAME' style='color: #F95B4B;'>Username</label>";
            $canSubmit = false;
        } else {
            echo "<label id='REG_USERNAME'>Username</label>";
        }
        echo "
        <input type='text' onfocus='clearTextBox(this)' name='reg_userName' value='{$data[0]}' style='margin-bottom: 1px;'/>        
        <div class='inputSplit'>
        <div class='splitLeft'>
        <!--Input Field-->";
         if ($data[1] == 'What is your first name?' || strlen($data[1]) <= 2 || strpos($data[1], ' ') !== false || $data[1] == ''){   
             echo "<label id='REG_FIRSTNAME' style='color: #F95B4B;'>First Name</label>";
             $canSubmit = false;
         } else {
             echo "<label id='REG_FIRSTNAME'>First Name</label>";
         }
        echo "
        <input type='text' onfocus='clearTextBox(this)' name='reg_firstName' name='firstName' onClick='clearTextIfFirst(this)' value='{$data[1]}'/>
        </div>
        <!--Input Field-->
        <div class='splitRight'>";
        if ($data[2] == 'And your last name?' || strlen($data[2]) <= 2 || strpos($data[2], ' ') !== false || $data[2] == ''){   
            echo "<label id='REG_LASTNAME' style='color: #F95B4B;'>Last Name</label>";
            $canSubmit = false;
        } else {
            echo "<label id='REG_LASTNAME'>Last Name</label>";
        }
            
        echo "
        <input type='text' onfocus='clearTextBox(this)' name='reg_lastName' value='{$data[2]}'/>
        </div>
        </div>
        <div class='inputSplit'>
        <div class='splitLeft'>
        <!--Input Field-->";
        if ($data[3] == 'Enter a password' || strlen($data[3]) <= 2 || strpos($data[3], ' ') !== false || $data[3] == ''){       
        echo "<label id='REG_PASSWORD' style='color: #F95B4B;'>Password</label>
         <input type='text' onfocus='clearTextBox(this)' name='reg_password' value='{$data[3]}'/>";
            $canSubmit = false;
        } else {
        echo "<label id='REG_PASSWORD'>Password</label>
         <input type='password' onfocus='clearTextBox(this)' name='reg_password' value='{$data[3]}'/>";
        }
            
        echo "
       
        </div>
        <!--Input Field-->
        <div class='splitRight'>";
            
        if ($data[4] == 'ne more time please' || $data[4] != $data[3]){    
            echo "<label id='REG_CONFPASSWORD' style='color: #F95B4B;'>Confirm Password</label><input type='text' onfocus='clearTextBox(this)' name='reg_confPassword' value='{$data[4]}'/>";
            $canSubmit = false;
        } else {
            echo "<label id='REG_CONFPASSWORD'>Confirm Password</label>
            <input type='password' onfocus='clearTextBox(this)' name='reg_confPassword' value='{$data[4]}'/>";
        }
            
        echo "</div>
        </div>
            
        <div class='inputSplit'>
        <div class='splitLeft'>
        <!--Input Field-->";
        if ($data[5] == 'Enter your email' || strlen($data[5]) <= 5 || strpos($data[5], ' ') !== false || $data[5] == ''){   
        echo "<label id='REG_EMAIL' style='color: #F95B4B;'>Email</label>";
            $canSubmit = false;
        } else {
        echo "<label id='REG_EMAIL'>Email</label>";
        }
            
        echo "
        <input type='text' onfocus='clearTextBox(this)' name='reg_email' value='{$data[5]}'/>
        </div>
        <!--Input Field-->
        <div class='splitRight'>";
        if ($data[6] == 'Once more please' || $data[6] != $data[5]){   
        echo "<label id='REG_CONFEMAIL' style='color: #F95B4B;'>Confirm Email</label>";
            $canSubmit = false;
        } else {
        echo "<label id='REG_CONFEMAIL'>Confirm Email</label>";
        }
        echo "<input type='text' onfocus='clearTextBox(this)' name='reg_confEmail' value='{$data[6]}'/>
        </div>
        </div>
        <!--Input Field-->";
        if ($data[7] == 'What is your business called?' || strlen($data[7]) <= 2 ||  $data[7] == ''){   
        echo "<label id='REG_COMPANYNAME' style='color: #F95B4B;'>Company Name</label>";
            $canSubmit = false;
        } else {
        echo "<label id='REG_COMPANYNAME'>Company Name</label>";
        }
            
        echo "<input type='text' onfocus='clearTextBox(this)' name='reg_companyName' value='{$data[7]}'/>
        <!--Input Field-->";
        if ($data[8] == 'Enter Phone Number' || strlen($data[8]) < 10 || strpos($data[8], ' ') !== false || $data[8] == ''){   
        echo "<label id='REG_PHONENUMBER' style='color: #F95B4B;'>Phone Number</label>";
            $canSubmit = false;
        } else {
        echo "<label id='REG_PHONENUMBER'>Phone Number</label>";
        }
        echo "<input type='text' onfocus='clearTextBox(this)' onkeypress='return event.charCode >= 48 && event.charCode <= 57' name='reg_phoneNumber' value='{$data[8]}'/>
        <!--Input Field-->";
        echo "<label id='REG_FAXNUMBER'>Fax Number (If available)</label>";
        echo "<input type='text' onfocus='clearTextBox(this)' onkeypress='return event.charCode >= 48 && event.charCode <= 57' name='reg_faxNumber' value='{$data[9]}'/>
        </div>
            
        <div class='regFormright'>
        <!--Input Field-->";
        if ($data[10] == 'Enter your street address' || strlen($data[10]) <= 4 || $data[10] == ''){   
            echo "<label id='REG_ADDRESS' style='color: #F95B4B;'>Address</label>";
            $canSubmit = false;
        } else {
            echo "<label id='REG_ADDRESS'>Address</label>";
        }
        echo "<input type='text' onfocus='clearTextBox(this)' name='reg_streetAddress' value='{$data[10]}'/>
        <!--Input Field-->";
        if ($data[11] == 'Enter the city your business is located' || strlen($data[11]) <= 3 || strpos($data[11], ' ') !== false || $data[11] == ''){   
        echo "<label id='REG_CITY' style='color: #F95B4B;'>City</label>";
            $canSubmit = false;
        } else {
        echo "<label id='REG_CITY'>City</label>";
        }
        echo "<input type='text' onfocus='clearTextBox(this)' name='reg_city' value='{$data[11]}'/>
        <!--Input Split-->
        <div class='inputSplit'>
        <div class='splitLeft'>
        <!--Input Field-->";
        if ($data[12] == 'empty'){ 
            echo "<label id='REG_STATE' style='color: #F95B4B;'>State</label>";
            $canSubmit = false;
        } else {
            echo "<label id='REG_STATE'>State</label>";
        }
        echo "<select name='reg_state'>
        <option value='empty' selected disabled hidden>Select a State</option>";
         $this->getStates($data[12]);
        echo "</select>
        </div>
        <div class='splitRight'>
        <!--Input Field-->";
        if ($data[13] == 'Enter your Zip Code' || strlen($data[13]) < 5 || strpos($data[13], ' ') !== false || $data[13] == ''){    
            echo "<label id='REG_ZIPCODE' style='color: #F95B4B;'>Zip Code</label>";
            $canSubmit = false;
        } else {
            echo "<label id='REG_ZIPCODE'>Zip Code</label>";
        }
        echo "<input type='text' onfocus='clearTextBox(this)' maxlength='5' name='reg_zipCode' onkeypress='return event.charCode >= 48 && event.charCode <= 57' value='{$data[13]}'/>
        </div>
        </div>
        <!--Input Field-->";
        if ($data[14] == 'empty' || $data[15] == 'empty' || $data[16] == 'empty'){    $canSubmit = false;
        echo "<label id='REG_BIRTHDAY' style='color: #F95B4B;'>Birthdate (must be 18+)</label>";
        } else {
        echo "<label id='REG_BIRTHDAY'>Birthdate (must be 18+)</label>";
        }
        echo "<div class='inputTriSplit'>
        <!--Month-->
        <select name='reg_birthMonth'>
        <option value='empty' selected disabled hidden>Month</option>";
        $sel_mnth = $data[14];
            for ($m=1; $m<=12; $m++) {
    if($sel_mnth == $m)
        echo '  <option value="' . $m . '" selected="selected">' . date('M', mktime(0,0,0,$m)) . '</option>' . PHP_EOL;
    else   
        echo '  <option value="' . $m . '">' . date('M', mktime(0,0,0,$m)) . '</option>' . PHP_EOL;
        }
           
        echo "</select>
        <!--Day-->
        <select name='reg_birthDay'>
        <option value='empty' selected disabled hidden>Day</option>";
        
           for($r = 1; $r <= 31; $r++){
               if ($data[15] == $r){    
              echo "<option value='{$r}' selected>{$r}</option>";
               } else {
              echo "<option value='{$r}'>{$r}</option>";
               }
           }
       echo "
        </select>
        <!--Year-->
        <select name='reg_birthYear'>
        <option value='empty' selected disabled hidden>Year</option>";

           for($i = date('Y')-17 ; $i > date('Y')-100 ; $i--){
            if ($data[15] == $i){
              echo "<option value='{$i}' selected>$i</option>";
            } else {
              echo "<option value='{$i}'>$i</option>";
            }
           }
 
        echo "
        </select>  
        </div>
        <!--Terms-->
        <label>Terms Of Service</label>
       <center style='font-size: 12px;'> <input type='checkbox' name='reg_termsAgree' onclick='enableDisableReg()' checked/> By Checking this box you hereby agree to and have read the <a style='cursor: pointer;' onclick='window.open(`core/ajax/termsandConditions.php`, `_blank`, `location=yes,height=960,width=720,scrollbars=yes,status=yes`);'>Terms and Conditions Of Service</a>.
        <br/>
        Once registration is complete a verification email will be sent to you.</center>
        <br/>
        <input type='submit' onclick='signUpAccount()' name='submitButton' value='Submit'/>
        </div>";
            if ($canSubmit == true){
                echo "<input type='hidden' value='true' name='canSubmit'/>";
            } else {
                echo "<input type='hidden' value='false' name='canSubmit'/>";
            }
        }
        
        function createAccount($data){
            // Connect to Database
            $this->dbConnect();
            // Get Account Key
            $act_key = $this->generateKey(10, 10);
            $salt_hash = $this->outputSalt($data[3]);
            $timeStamp = date('Y-m-d H:i:s');
            $dob = $data[14] . "-" . sprintf("%02d", $data[13]) . "-" . sprintf("%02d", $data[12]);
            // Add Invoice
            mysqli_query($this->link, "INSERT INTO `accounts` (`username`, `hashKey`, `email`, `logginAttempts`, `streetAddress`, `cityName`, `stateName`, `zipCode`, `firstName`, `lastName`, `companyName`, `accountVerified`, `accountVerID`, `dateEST`, `birthdate`, `phoneNumber`, `faxNumber`, `accountType`) VALUES ('{$data[0]}', '$salt_hash', '{$data[4]}', '0', '{$data[8]}', '{$data[9]}', '{$data[10]}', '{$data[11]}', '{$data[1]}', '{$data[2]}', '{$data[5]}', 'F', '$act_key', '$timeStamp', '$dob', '{$data[6]}', '{$data[7]}', 'DEF')");
            // Close Connection
            $this->dbCloseConnection();
            $this->sendVerificationEmail($data[0], $data[4]);
            $this->loginUser($data[0], $data[3]);
            echo "<meta http-equiv='refresh' content='0.0;url=index.php' />";
            header( "refresh:0.0;url=index.php" );
        }
        
        /*Get Invoice List*/
        function accountRightInfo(){
            // Get User
            $user = $this->getSessionUsr();
            // Connect to Database
            $this->dbConnect();
            // Perform Query
            $userQuery = mysqli_query($this->link, "SELECT accountVerID, ID FROM accounts WHERE username='$user' LIMIT 1");
            // get Results for User Query
            $row = mysqli_fetch_array($userQuery);
            // Perform Main Query
            $query = mysqli_query($this->link, "SELECT invoiceNumber, dateIssued, invoiceStatus, invoiceTotalCost FROM invoices WHERE invoiceUser='$user' AND invoiceUserID='{$row['ID']}' AND invoiceUserVerID='{$row['accountVerID']}' ORDER BY FIELD(invoiceStatus, 'ERROR', 'PENDING', 'PAID') ASC, dateIssued ASC");
            while ($res = mysqli_fetch_array($query)){
            echo "<li onClick='showInvoice(`{$res['invoiceNumber']}`)'>
                    <img src='images/Invoice_BLK.svg'/>
                    <i>{$res['invoiceNumber']}</i>
                    <b>$".$res['invoiceTotalCost']."</b>";
                if ($res['invoiceStatus'] == 'PENDING'){
                   echo "<h6 style='color: #f39c12;'>{$res['dateIssued']} | {$res['invoiceStatus']}</h6>";
                } else if ($res['invoiceStatus'] == 'PAID'){
                    echo "<h6 style='color: #27ae60;'>{$res['dateIssued']} | {$res['invoiceStatus']}</h6>";
                } else {
                    echo "<h6 style='color: #e74c3c;'>{$res['dateIssued']} | {$res['invoiceStatus']}</h6>";
                echo "</li>";
                }
            }
            // Close Connection
            $this->dbCloseConnection();
        }
        
        /*Reset Password*/
        function updatePassword($user, $email, $password){
            // Connect to Database
            $this->dbConnect();
            $refreshedSalt = $this->outputSalt($password);
            mysqli_query($this->link, "UPDATE accounts SET hashKey='$refreshedSalt' WHERE username='$user' AND email='$email'");
            // Close Connection
            $this->dbCloseConnection();
        }
        /*Insert HTML for Account Page*/
        function accountLeftInfo(){
            // Get User
            $user = $this->getSessionUsr();
            // Connect to Database
            $this->dbConnect();
            // Perform Query
            $query = mysqli_query($this->link, "SELECT username, firstName, lastName, companyName, accountVerified, birthDate, phoneNumber, email, streetAddress, cityName, stateName, accountType, zipCode FROM accounts WHERE username='$user' LIMIT 1");
            // Get Results
            $row = mysqli_fetch_array($query); 
            // Close Connection
            $this->dbCloseConnection();
            // Post Results
            echo "<!--Header for Seperation-->
            <h3>Account"; 
            
            if ($row['accountType'] == "ADM"){
                echo "<a href='adm.php?p=dashboard' style='float:right'>Admin Control Panel</a>";
            }
            
            echo "</h3>
            <i>{$row['firstName']} {$row['lastName']}</i>
            <i>{$row['companyName']}</i>
            <i>{$row['birthDate']}</i>
            <h3>Password</h3>
                <div class='gSplitLeft'><input type='button' onclick='window.location.href = `changePassword.php?username={$user}&email={$row['email']}`;' value='Reset Password'/></div>
            <h3>Email</h3>
            <input type='hidden' value='{$row['username']}' name='HUserName' id='HUserName'/>";
            // Check if Account Verified
            if ($row['accountVerified'] == "T"){
               echo "<b><img src='images/verified_BLK.svg' class='validated'/>Your account has been validated.</b>";
            } else {
              echo "<b id='PHPPROCESSOR'><img src='images/verified_BLK.svg' class='notvalidated'/>Your email has not been validated. <a href='#' onclick='reSendVer()'>Resend Validation Email</a></b>";  
            }
            echo "<input type='text' value='{$row['email']}' onchange='highlightSaveBTN()' name='emlTXT' class='EMAILTXT' onkeydown='highlightSaveBTN()' disabled/>
            <input type='button' value='Change Email' name='CHGEMAIL' onClick='modifyElement(`EMAILTXT`, this)'/>
            <h3>Address</h3>
            <input type='text' name='address' value='{$row['streetAddress']}' onkeydown='highlightSaveBTN()' onchange='highlightSaveBTN()' class='ADDRESSTXT' disabled/>
            <div class='gridSplit'>
                <div class='gSplitLeft'><input type='text' class='ADDRESSTXT' onchange='highlightSaveBTN()' onkeydown='highlightSaveBTN()' value='{$row['cityName']}' disabled/></div>
                <div class='gSplitRight'><input type='text' class='ADDRESSTXT' onchange='highlightSaveBTN()' onkeydown='highlightSaveBTN()' value='{$row['stateName']}' disabled/></div>
            </div>
            <input type='text' onchange='highlightSaveBTN()' value='{$row['zipCode']}' class='ADDRESSTXT' disabled/>
            <input type='button' value='Change Address' name='ADDBTN' onClick='modifyElement(`ADDRESSTXT`, this)'/>
            <input type='button' id='SAVECHG' onClick='toggleOverlaySave()' value='Save Changes' disabled/>
            <!--Save Overylay password varify and passwordchange overlay-->
            <div id='saveOverlay'>
                <div class='saveBox'>
                    <button onClick='toggleOverlaySave()'>X</button>
                    <div class='sbContent' id='SAVECH'>
                        <h3>Save Changes</h3>
                        <b id='SAVEVERI'><center>Password Confirmation</center></b>
                        <input type='password' onkeypress='saveBoxPasswordType(event, this)' oninput='saveBoxPasswordType(event, this)' value='' name='scPASS'/>
                        <input type='button' value='Confirm Changes' onClick='updateActInf()' id='SAVECONFBTN' disabled/>
                        <input type='button' class='inputCancel' onClick='toggleOverlaySave()' value='Cancel'/>
                    </div>    
                </div>
            </div>";
        }
    
    }
    
    
    // Makes Page Variable to call functions
    $pfunc = new OPMH();

?>