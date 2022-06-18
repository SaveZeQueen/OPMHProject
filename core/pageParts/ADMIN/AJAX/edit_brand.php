<?php session_start(); ?>
<?php include_once("../../../cpfsu.php"); 


if ($_POST['mode'] == 'new'){

$pfunc->editBrand($_POST['id']);
} else {
    $data = [$_POST['brandName'], $_POST['brandID']];
        if (strlen(strstr($_POST['hasFiles'],"G"))>0) {
            array_push($data, $_FILES['gccFiles']);
        } else {
            array_push($data, "");
        }
    
        if (strlen(strstr($_POST['hasFiles'],"I"))>0) {
            array_push($data, $_FILES['brandImg']);
        } else {
            array_push($data, "");
        }
    $pfunc->updateeditBrand($data);
    header("Refresh:0");
        echo "<center>
        <label>..Loading..</label>
        <img src='images/adm_loading.svg'/>
        </center>";
}

    
    ?>


