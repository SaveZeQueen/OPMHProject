<?php session_start(); ?>
<?php include_once("../../../cpfsu.php");
    $mode = $_POST['mode'];

    if ($mode == 'new'){
        $pfunc->addProdWin($_POST['id']);
    } else {
        $data = [$_POST['pn'], $_POST['psku'], $_POST['bid'], $_POST['pcost'], $_POST['pwscost'], $_FILES['file'], $_POST['pdesc'], $_POST['pnic'], $_POST['pg'], $_POST['vg'], $_POST['pfla'], $_POST['botsize']];
        $pfunc->addNewProd($data);
    }
?>





