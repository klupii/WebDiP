<?php
    $naslov = "O autoru";
    $direktorij = getcwd();
    $putanja = dirname($_SERVER["REQUEST_URI"]);

    include_once "$direktorij/zaglavlje.php";

    $smarty->display("o_autoru.tpl");
    $smarty->display("podnozje.tpl");
