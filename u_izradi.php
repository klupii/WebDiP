<?php
    $naslov = "U izradi...";

    $direktorij = getcwd();
    $putanja = dirname($_SERVER["REQUEST_URI"]);

    include_once "$direktorij/zaglavlje.php";

    $smarty->display("u_izradi.tpl");
    $smarty->display("podnozje.tpl");

