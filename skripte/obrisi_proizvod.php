<?php
    $direktorij = dirname(getcwd());

    include_once "$direktorij/klase/baza.class.php";

    $id_proizvoda = filter_input(INPUT_POST, 'id_proizvoda', FILTER_SANITIZE_STRING);
    $baza = new Baza();
    $baza->spojiDB();
    $baza->proizvod_obrisi($id_proizvoda);

    $baza->zatvoriDB();
    echo json_encode(array("uspio" => true));


