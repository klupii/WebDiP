<?php

error_reporting(E_ALL);

include "$direktorij/klase/konfiguracija.class.php";
include "$direktorij/klase/sesija.class.php";
include "$direktorij/klase/baza.class.php";
require "$direktorij/vanjske_biblioteke/smarty-4.3.0/libs/Smarty.class.php";

ob_start();

$smarty = new Smarty();
$smarty->setTemplateDir("$direktorij/templates")
        ->setCompileDir("$direktorij/templates_c")
        ->setPluginsDir(SMARTY_PLUGINS_DIR)
        ->setCacheDir("$direktorij/cache")
        ->setConfigDir("$direktorij/configs");

$korisnicko_ime = -1;
$id_korisnik = -1;
$id_uloga = -1;
$uloga = -1;
@Sesija::kreirajSesiju();
if (!Sesija::kreiranKorisnik()) {
    $id_korisnik = "-1";
    $korisnicko_ime = "Neregistrirani korisnik";
    $uloga = "Neregistrirani korisnik";
} else {
    $korisnicko_ime = $_SESSION['korisnicko_ime'];
    $id_korisnik = $_SESSION['id_korisnik'];
    $id_uloga = $_SESSION['id_uloga'];
    $uloga = $_SESSION['uloga'];
}
if (!isset($_SESSION['vrijeme_registracije']) && Sesija::kreiranKorisnik()) {
    $konfiguracija = new Konfiguracija($direktorij);
    $_SESSION['vrijeme_registracije'] = $konfiguracija->virtualnoVrijeme();
}

if (isset($_SESSION['vrijeme_registracije'])) {
    $konfiguracija = new Konfiguracija($direktorij);
    $trajanje_sesije = $konfiguracija->vratiTrajanjeSesije();
    $trenutno_vrijeme = $konfiguracija->virtualnoVrijeme();

    if (strtotime($trenutno_vrijeme) > (strtotime($_SESSION['vrijeme_registracije']) + $trajanje_sesije * 60 *60)) {
        $baza = new Baza();
        $baza->spojiDB();
        $baza->dnevnik_ostale_radnje("Istek sesije");
        $baza->zatvoriDB();

        Sesija::obrisiSesiju();

        header("Location: index.php");
    }
}

if (isset($_GET['odjava'])) {
    $baza = new Baza();
    $baza->spojiDB();
    $baza->dnevnik_prijava_odjava("ODJAVA");
    $baza->zatvoriDB();
    Sesija::obrisiSesiju();
    header("Location: index.php");
    exit();
}


if (isset($_COOKIE['uvjeti_koristenja'])) {
    if (isset($_COOKIE['zadnja_stranica']) && $_COOKIE['zadnja_stranica'] != -1 && $naslov != "Prijava" && !isset($_GET['odjava']) && Sesija::kreiranKorisnik()) {
        $konfiguracija = new Konfiguracija($direktorij);
        $trenutno_vrijeme = $konfiguracija->virtualnoVrijeme();
        $trajanje_kolacica = $konfiguracija->vratiTrajanjeKolacica();
        $istek_kolacica = strtotime($trenutno_vrijeme) + $trajanje_kolacica * 24 * 60 * 60;
        $zadnja_stranica = substr(str_replace("$putanja", "", $_SERVER['PHP_SELF']), 1);
        setcookie("zadnja_stranica", $zadnja_stranica, $istek_kolacica, "/");
    }
}


$uvjeti_koristenja = false;
if ((!isset($_COOKIE['uvjeti_koristenja']) || $_COOKIE['uvjeti_koristenja'] == -1) && Sesija::kreiranKorisnik()) {
    $uvjeti_koristenja = true;
} elseif (isset($_COOKIE['uvjeti_koristenja']) && Sesija::kreiranKorisnik()) {
    $baza = new Baza();
    $baza->spojiDB();
    $rezultat = $baza->korisnik_dohvati($_SESSION['korisnicko_ime'])[0];
    $baza->zatvoriDB();
    $konfiguracija = new Konfiguracija($direktorij);
    $trenutno_vrijeme = $konfiguracija->virtualnoVrijeme();
    if (strtotime($rezultat['uvjeti_koristenja']) < strtotime($trenutno_vrijeme)) {
        if (isset($_COOKIE['uvjeti_koristenja'])) {
            unset($_COOKIE['uvjeti_koristenja']);
            setcookie("uvjeti_koristenja", null, -1, "/");
        }
        if (isset($_COOKIE['zapamti_ime'])) {
            unset($_COOKIE['zapamti_ime']);
            setcookie("zapamti_ime", null, -1, "/");
        }
        if (isset($_COOKIE['zadnja_stranica'])) {
            unset($_COOKIE['zadnja_stranica']);
            setcookie("zadnja_stranica", null, -1, "/");
        }

        $uvjeti_koristenja = true;
    }
}

$baza = new Baza();
$baza->spojiDB();
$baza->dnevnik_ostale_radnje("Ulazak na stranicu $naslov");
$baza->zatvoriDB();

$smarty->assign("uvjeti_koristenja", $uvjeti_koristenja);
$smarty->assign("korisnicko_ime", htmlspecialchars($korisnicko_ime));
$smarty->assign("uloga", htmlspecialchars($uloga));
$smarty->assign("id_uloga", htmlspecialchars($id_uloga));
$smarty->assign("id_korisnik", htmlspecialchars($id_korisnik));

$smarty->assign("naslov", $naslov);
$smarty->assign("putanja", $putanja);
$konfiguracija = new Konfiguracija($direktorij);
$konfiguracija->procitajKonfiguraciju();

if ($naslov != "Upravljanje konfiguracijom") {
    if (Sesija::kreiranKorisnik()) {
        if (@$_SESSION['id_uloga'] == 1) {
            $konfiguracija->postaviUIzradi(0);
            $konfiguracija->spremiKonfiguraciju();
        } elseif ($konfiguracija->vratiUIzradi() != 0 && $naslov != "U izradi...") {
            header("Location:$putanja/u_izradi.php");
        }
    } elseif ($konfiguracija->vratiUIzradi() != 0 && $naslov != "U izradi...") {
        header("Location:$putanja/u_izradi.php");
    }
}

$smarty->display("zaglavlje.tpl");

function provjeraPostavljenosti($varijabla) {
    return isset($varijabla) ? $varijabla : "";
}

