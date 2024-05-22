<?php
    $naslov = "Dokumentacija";
    $direktorij = getcwd();
    $putanja = dirname($_SERVER["REQUEST_URI"]);

    include_once "$direktorij/zaglavlje.php";

    $smarty->display("dokumentacija.tpl");
    $smarty->display("podnozje.tpl");

