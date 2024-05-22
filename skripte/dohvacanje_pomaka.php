<?php
    $url = "http://barka.foi.hr/WebDiP/pomak_vremena/pomak.php?format=json";

    if (!($fp = fopen($url, 'r'))) {
        echo "Problem: nije moguće otvoriti url: " . $url;
        exit;
    }

    $var = json_decode(file_get_contents("http://barka.foi.hr/WebDiP/pomak_vremena/pomak.php?format=json"),false);
    $brojSati = $var->WebDiP->vrijeme->pomak->brojSati;

    echo $brojSati;
