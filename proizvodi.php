<?php
    $naslov = "Proizvodi";

    $direktorij = getcwd();
    $putanja = dirname($_SERVER["REQUEST_URI"]);

    include_once "$direktorij/zaglavlje.php";

    if (!isset($_SESSION['id_uloga']) || $_SESSION['id_uloga'] > 1) {
        $baza = new Baza();
        $baza->spojiDB();
        $baza->dnevnik_ostale_radnje("Pokusaj dolaska na stranicu '$naslov'");
        $baza->zatvoriDB();
        header("Location: ./index.php");
    }


    $smarty->display("proizvodi.tpl");
    $smarty->display("podnozje.tpl");

