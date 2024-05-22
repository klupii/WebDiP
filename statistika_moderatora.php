<?php
    $naslov = "Statistika moderatora";
    $direktorij = getcwd();
    $putanja = dirname($_SERVER["REQUEST_URI"]);
    
    include_once "$direktorij/zaglavlje.php";
    include_once "$direktorij/klase/sesija.class.php";

    if (!isset($_SESSION['id_uloga']) || $_SESSION['id_uloga'] > 1) {
        $baza = new Baza();
        $baza->spojiDB();
        $baza->dnevnik_ostale_radnje("Pokusaj dolaska na stranicu '$naslov'");
        $baza->zatvoriDB();
        header("Location: ./index.php");
    }
    
    $smarty->display("statistika_moderatora.tpl");
    $smarty->display("podnozje.tpl");