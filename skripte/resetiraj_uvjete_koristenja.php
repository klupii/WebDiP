<?php
    $direktorij = dirname(getcwd());

    include_once "$direktorij/klase/baza.class.php";
    include_once "$direktorij/klase/konfiguracija.class.php";

    $konfiguracija = new Konfiguracija($direktorij);
    $trenutno_vrijeme = $konfiguracija->virtualnoVrijeme();

    $baza = new Baza();

    $baza->spojiDB();
    $tablica = $baza->dohvatiTablicu("korisnik");

    $baza->korisnik_azuriraj_uvjeti_koristenja_svi(date("Y-m-d H:i:s", (strtotime($trenutno_vrijeme) - 1)));

    $baza->zatvoriDB();

    echo json_encode(array("uspjeh" => 1));

