<?php
    $direktorij = dirname(getcwd());

    include_once "$direktorij/klase/baza.class.php";

    $id_korisnik = isset($_POST['id_korisnik']) ? filter_input(INPUT_POST, 'id_korisnik', FILTER_SANITIZE_STRING) : null;

    $baza = new Baza();
    $baza->spojiDB();
    $baza->korisnik_azuriraj_blokiran_id($id_korisnik, 0);
    $baza->korisnik_azuriraj_pokusaji_prijave_reset_id($id_korisnik);
    $baza->zatvoriDB();

    echo json_encode(array("blokiran" => true));



