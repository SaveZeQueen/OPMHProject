<?php session_start(); ?>
<?php include_once("../../../cpfsu.php");
    $mode = $_POST['mode'];

    if ($mode == 'edit'){
        $pfunc->editProdWin($_POST['sku']);
    } else {
        if ($_POST['hasfile'] == "true"){
        $data = [$_POST['pn'], $_POST['bid'], $_POST['pcost'], $_POST['pwscost'], $_FILES['file'], $_POST['pdesc'], $_POST['pnic'], $_POST['pg'], $_POST['vg'], $_POST['pfla'], $_POST['botsize'], $_POST['sku']];
         } else {
        $data = [$_POST['pn'], $_POST['bid'], $_POST['pcost'], $_POST['pwscost'], $_POST['hasfile'], $_POST['pdesc'], $_POST['pnic'], $_POST['pg'], $_POST['vg'], $_POST['pfla'], $_POST['botsize'], $_POST['sku']];
        }
        $pfunc->editProd($data, $_POST['hasfile']);
    }
?>





