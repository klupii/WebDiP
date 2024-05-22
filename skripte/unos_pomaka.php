<?php
    include_once '../klase/konfiguracija.class.php';
    include_once '../klase/sesija.class.php';
    $pomak = filter_input(INPUT_POST, "pomak", FILTER_SANITIZE_STRING);
    $direktorij = dirname(getcwd());

    $konfiguracija = new Konfiguracija($direktorij);
    $konfiguracija->procitajKonfiguraciju();
    $konfiguracija->postaviPomak($pomak);
    $konfiguracija->spremiKonfiguraciju();

    Sesija::kreirajSesiju();

    if(isset($_SESSION['vrijeme_registracije'])){
        $_SESSION['vrijeme_registracije'] = $konfiguracija->virtualnoVrijeme();
    }

    echo json_encode(array("pomak"=>$pomak));
