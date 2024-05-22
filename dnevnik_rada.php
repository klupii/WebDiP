<?php
    $naslov = "Dnevnik rada";
    $direktorij = getcwd();
    $putanja = dirname($_SERVER["REQUEST_URI"]);

    include "$direktorij/zaglavlje.php";

    if (!isset($_SESSION['id_uloga']) || $_SESSION['id_uloga'] > 1) {
        $baza = new Baza();
        $baza->spojiDB();
        $baza->dnevnik_ostale_radnje("Pokusaj dolaska na stranicu '$naslov'");
        $baza->zatvoriDB();
        header("Location: ./index.php");
    }

    $smarty->display("dnevnik_rada.tpl");
    $smarty->display("podnozje.tpl");

