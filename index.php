<?php
    $naslov = "Početna stranica";
    $direktorij = getcwd();
    $putanja = dirname($_SERVER["REQUEST_URI"]);
    
    include_once "$direktorij/zaglavlje.php";
    include_once "$direktorij/klase/sesija.class.php";
    

    $smarty->display("index.tpl");
    $smarty->display("podnozje.tpl");

