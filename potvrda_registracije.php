<?php
    $naslov = "Potvrda registracije";

    $direktorij = getcwd();
    $putanja = dirname($_SERVER["REQUEST_URI"]);

    include_once "$direktorij/zaglavlje.php";
    include_once "$direktorij/skripte/funkcije.php";
    include_once "$direktorij/klase/baza.class.php";
    include_once "$direktorij/klase/konfiguracija.class.php";

    $aktiviran = false;
    $email = null;
    if (isset($_GET['aktivacijski_kod']) && isset($_GET['korisnicko_ime'])) {
        $korisnicko_ime = filter_input(INPUT_GET, 'korisnicko_ime', FILTER_SANITIZE_STRING);
        $aktivacijski_kod = filter_input(INPUT_GET, 'aktivacijski_kod', FILTER_SANITIZE_STRING);
        $baza = new Baza();
        $baza->spojiDB();
        $rezultat = $baza->korisnik_dohvati_aktivacijski_kod($korisnicko_ime, $aktivacijski_kod);
        $baza->zatvoriDB();
        if (count($rezultat) != 0) {
            $aktiviran_iz_baze = $rezultat[0]['aktiviran'];
            if ($aktiviran_iz_baze === 0) {

                $datum_vrijeme_registracije = $rezultat[0]['datum_vrijeme_registracije'];

                $konfiguracija = new Konfiguracija($direktorij);
                $trenutno_vrijeme = $konfiguracija->virtualnoVrijeme();
                $istek_verifikacije = $konfiguracija->vratiIstekVerifikacije();

                $datum_isteka = date('Y-m-d H:i:s', strtotime($datum_vrijeme_registracije . ' + ' . $istek_verifikacije . ' hours'));

                if (strtotime($datum_isteka) < strtotime($trenutno_vrijeme)) {
                    $aktiviran = false;
                    $baza = new Baza();
                    $baza->spojiDB();
                    $baza->korisnik_obrisi($korisnicko_ime);
                    $baza->zatvoriDB();
                } else {
                    $aktiviran = true;
                    $baza = new Baza();
                    $baza->spojiDB();
                    $baza->korisnik_azuriraj_aktiviran($korisnicko_ime);
                    $baza->zatvoriDB();
                }
            }
        }
    } else {
        header("Location: index.php");
    }

    $smarty->assign("aktiviran", $aktiviran);
    $smarty->assign("email", $email);
    $smarty->display("potvrda_registracije.tpl");
    $smarty->display("podnozje.tpl");