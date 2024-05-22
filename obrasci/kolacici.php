<?php
    $naslov = "Kolačići";
    $direktorij = dirname(getcwd());
    $putanja = dirname(dirname($_SERVER["REQUEST_URI"]));

    include_once "$direktorij/zaglavlje.php";

    if (!isset($_SESSION['id_uloga']) || $_SESSION['id_uloga'] > 3) {
        $baza = new Baza();
        $baza->spojiDB();
        $baza->dnevnik_ostale_radnje("Pokušaj prijave na stranicu '$naslov'");
        $baza->zatvoriDB();
        header("Location: ../index.php");
    }


    if (isset($_POST['spremi'])) {
        $konfiguracija = new Konfiguracija($direktorij);
        $trenutno_vrijeme = $konfiguracija->virtualnoVrijeme();
        $trajanje_kolacica = $konfiguracija->vratiTrajanjeKolacica();
        $istek_kolacica = strtotime($trenutno_vrijeme) + $trajanje_kolacica * 24 * 60 * 60;
        if (isset($_POST['uvjeti_koristenja'])) {
            setcookie("uvjeti_koristenja", 1, $istek_kolacica, "/");
            if (isset($_POST['zapamti_ime'])) {
                setcookie("zapamti_ime", 1, $istek_kolacica, "/");
            } else {
                setcookie("zapamti_ime", -1, $istek_kolacica, "/");
            }if (isset($_POST['zadnja_stranica'])) {
                setcookie("zadnja_stranica", 1, $istek_kolacica, "/");
            } else {
                setcookie("zadnja_stranica", -1, $istek_kolacica, "/");
            }
        } else {
            unset($_COOKIE['uvjeti_koristenja']);
            unset($_COOKIE['zapamti_ime']);
            unset($_COOKIE['zadnja_stranica']);
            setcookie("uvjeti_koristenja", -1, $istek_kolacica, "/");
            setcookie("zapamti_ime", -1, $istek_kolacica, "/");
            setcookie("zadnja_stranica", -1, $istek_kolacica, "/");
        }
        header("Location: ./kolacici.php");
    }

    $uvjeti_koristenja = (isset($_COOKIE['uvjeti_koristenja']) && $_COOKIE['uvjeti_koristenja'] != -1) ? true : false;
    $zapamti_ime = (isset($_COOKIE['zapamti_ime']) && $_COOKIE['zapamti_ime'] != -1) ? true : false;
    $zadnja_stranica = (isset($_COOKIE['zadnja_stranica']) && $_COOKIE['zadnja_stranica'] != -1) ? true : false;

    $smarty->assign("uvjeti_koristenja", $uvjeti_koristenja);
    $smarty->assign("zapamti_ime", $zapamti_ime);
    $smarty->assign("zadnja_stranica", $zadnja_stranica);

    $smarty->display("kolacici.tpl");
    $smarty->display("podnozje.tpl");


