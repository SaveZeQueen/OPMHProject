<?php session_start(); ?>
<?php include_once("../../../cpfsu.php"); 
    $swit = $_POST['mode'];

    if ($swit == 'adding') {
        
        $data = [$_POST['brandName'], $_POST['brandID'], $_FILES['gccFiles'], $_FILES['brandImg']];
        
        $pfunc->addnewBrand($data);
        echo "<center>
        <label>..Loading..</label>
        <img src='images/adm_loading.svg'/>
        </center>";
    } else {

?>


<label>Brand Name:</label>
<input type='text' onclick="this.value=''" name='brandName' value='Input Brand Name'>
<label>Brand ID (auto generated):</label>
<input type='text' id='BRANDID' name='brandID' value='<?php echo $pfunc->generateKey(15, 10); ?>' readonly>
<label>Brand GCC PDF File:</label>
<input type='file' name='brandGCC' accept=".pdf"/>
<label>Brand Logo Image (Images must be 333Wx343H):</label>
<input type='file' id='IMGUPLD' name='brandImage' accept=".gif,.jpg,.jpeg,.png" onchange='readURL(this)'/>
<div class='fpreviewWindow'>
    <img id='SRCIMG' src='images/adm_IMG.svg'/>
</div>
<input type='submit' value='Submit' onclick='addNewBrand()'/>

<?php } ?>
