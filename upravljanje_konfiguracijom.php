<?php

    $naslov = "Upravljanje konfiguracijom";

    $direktorij = getcwd();
    $putanja = dirname($_SERVER["REQUEST_URI"]);

    include_once "$direktorij/zaglavlje.php";

    if (!isset($_SESSION['id_uloga']) || $_SESSION['id_uloga'] > 1) {
        $baza = new Baza();
        $baza->spojiDB();
        $baza->dnevnik_ostale_radnje("Pokušaj prijave na stranicu '$naslov'");
        $baza->zatvoriDB();
        header("Location: ./index.php");
    }


    $redirekcija = false;
    if(!Sesija::kreiranKorisnik() || $_SESSION['id_uloga']!=1){
        $redirekcija = true;
        header("Location: ./index.php");
    }

    $konfiguracija = new Konfiguracija($direktorij);
    $konfiguracija->procitajKonfiguraciju();
    if ($konfiguracija->vratiUIzradi() == 0 && !$redirekcija) {
        $konfiguracija->postaviUIzradi(1);
        $konfiguracija->spremiKonfiguraciju();
    }
    
    $greske = "";
    $greske_klasa = "";
    if (isset($_POST["spremi-css"])) {
        if ($_FILES['css_datoteka']['size'] > 0) {
            if (explode(".", $_FILES['css_datoteka']['name'])[1] == "css") {
                $ime = "./css/klupoglav.css";
                $temp = $_FILES['css_datoteka']['tmp_name'];

                $sig_ime = "./css/klupoglav2.css";

                file_put_contents($sig_ime, file_get_contents($ime));

                file_put_contents($ime, file_get_contents($temp));
            } else {
                $greske = "Niste unijeli CSS datoteku!";
                $greske_klasa = "opcenito--polje-za-greske";
            }
        } else {
            $greske = "Niste unijeli ni jednu datoteku!";
            $greske_klasa = "opcenito--polje-za-greske";
        }
    } elseif (isset($_POST['spremi-css-tekst'])) {
        $css_tekst = $_POST['uredivanje-css'];
        $ime = "./css/klupoglav.css";
        $dat = fopen($ime, "w");
        file_put_contents($ime, $css_tekst);
        fclose($dat);
    }

    $konfiguracija = new Konfiguracija($direktorij);
    $konfiguracija->procitajKonfiguraciju();
    if ($konfiguracija->vratiUIzradi() == 0 && !$redirekcija) {
        $konfiguracija->postaviUIzradi(1);
        $konfiguracija->spremiKonfiguraciju();
    }


    $konfiguracija = new Konfiguracija($direktorij);
    $vrijednosti_konfiguracije = $konfiguracija->procitajKonfiguraciju();

    $trajanje_kolacica = provjeraPostavljenosti($vrijednosti_konfiguracije['trajanje_kolacica']);
    $broj_stranica_stranicenja = provjeraPostavljenosti($vrijednosti_konfiguracije['broj_stranica_stranicenja']);
    $trajanje_sesije = provjeraPostavljenosti($vrijednosti_konfiguracije['trajanje_sesije']);
    $broj_neuspjesnih_prijava = provjeraPostavljenosti($vrijednosti_konfiguracije['broj_neuspjesnih_prijava']);
    $pomak = provjeraPostavljenosti($vrijednosti_konfiguracije['pomak']);
    $istek_verifikacije = provjeraPostavljenosti($vrijednosti_konfiguracije['istek_verifikacije']);


    $smarty->assign("trajanje_kolacica", $trajanje_kolacica);
    $smarty->assign("broj_stranica_stranicenja", $broj_stranica_stranicenja);
    $smarty->assign("trajanje_sesije", $trajanje_sesije);
    $smarty->assign("broj_neuspjesnih_prijava", $broj_neuspjesnih_prijava);
    $smarty->assign("pomak", $pomak);
    $smarty->assign("istek_verifikacije", $istek_verifikacije);

    $smarty->display("upravljanje_konfiguracijom.tpl");
    $smarty->display("podnozje.tpl");

