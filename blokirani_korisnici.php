<?php
    $naslov = "Blokirani korisnici";

    $direktorij = getcwd();
    $putanja = dirname($_SERVER["REQUEST_URI"]);

    include_once "$direktorij/zaglavlje.php";
    include_once "$direktorij/skripte/funkcije.php";
    include_once "$direktorij/klase/baza.class.php";
    include_once "$direktorij/klase/konfiguracija.class.php";

    if (!isset($_SESSION['id_uloga']) || $_SESSION['id_uloga'] > 1) {
        $baza = new Baza();
        $baza->spojiDB();
        $baza->dnevnik_ostale_radnje("Pokušaj prijave na stranicu '$naslov'");
        $baza->zatvoriDB();
        header("Location: ./index.php");
    }

    $smarty->display("blokirani_korisnici.tpl");
    $smarty->display("podnozje.tpl");

