<?php

    $naslov = "Dodavanje novog proizvoda";
    $direktorij = dirname(getcwd());
    $putanja = dirname(dirname($_SERVER["REQUEST_URI"]));

    include_once "$direktorij/zaglavlje.php";
    
    if (!isset($_SESSION['id_uloga']) || $_SESSION['id_uloga'] > 1) {
        $baza = new Baza();
        $baza->spojiDB();
        $baza->dnevnik_ostale_radnje("Pokušaj prijave na stranicu '$naslov'");
        $baza->zatvoriDB();
        header("Location: ../index.php");
    }

    $smarty->display("dodaj_novi_proizvod.tpl");
    $smarty->display("podnozje.tpl");