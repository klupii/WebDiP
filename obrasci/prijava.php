<?php

    if (!isset($_SERVER['HTTPS'])) {
        $https_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        header("Location: " . $https_url);
    }

    $naslov = "Prijava";
    $direktorij = dirname(getcwd());
    $putanja = dirname(dirname($_SERVER["REQUEST_URI"]));

    include_once "$direktorij/zaglavlje.php";
    include_once "$direktorij/klase/sesija.class.php";
    include_once "$direktorij/skripte/funkcije.php";
    
    if (Sesija::kreiranKorisnik()) {
        header("Location: ../index.php");
    }

    $greske = array();
    $zapamti_ime = false;

    if (isset($_COOKIE['zapamti_ime']) && ($_COOKIE['zapamti_ime'] != 1 && $_COOKIE['zapamti_ime'] != -1)) {
        $zapamti_ime = $_COOKIE['zapamti_ime'];
    }

    if (isset($_POST['prijava'])) {

        $korisnicko_ime = filter_input(INPUT_POST, 'korisnicko_ime', FILTER_SANITIZE_STRING);
        $lozinka = filter_input(INPUT_POST, 'lozinka', FILTER_SANITIZE_STRING);

        $baza = new Baza();
        $baza->spojiDB();
        $rezultat = $baza->korisnik_dohvati($korisnicko_ime);
        $baza->zatvoriDB();

        if (count($rezultat) == 0) {
            $greske['korisnicko_ime'] = "Korisničko ime ne postoji!";
        } else {
            $korisnicko_ime_baza = $rezultat[0]['korisnicko_ime'];
            $lozinka_sha256_baza = $rezultat[0]['lozinka_sha256'];
            $sol_baza = $rezultat[0]['sol'];
            $lozinka_sha256 = sha256($lozinka, $sol_baza)['sha256'];
            $aktiviran = $rezultat[0]['aktiviran'];
            $blokiran = $rezultat[0]['blokiran'];
            if ($aktiviran == 0) {
                $greske['aktiviran'] = "Korisnik nije aktiviran!";
            } elseif ($blokiran == 1) {
                $greske['blokiran'] = "Korisnik je blokiran!";
            } elseif ($lozinka_sha256 !== $lozinka_sha256_baza) {
                $baza = new Baza();
                $baza->spojiDB();
                $rezultat = $baza->korisnik_dohvati($korisnicko_ime);

                $broj_unosa = $rezultat[0]['broj_unosa'];

                $baza->korisnik_azuriraj_pokusaji_prijave_increment($korisnicko_ime);
                $konfiguracija = new Konfiguracija($direktorij);
                $dozvoljene_prijave = $konfiguracija->vratiBrojNeuspjesnihPrijava();
                $broj_preostalih_pokusaja = $dozvoljene_prijave - $broj_unosa;

                if ($broj_preostalih_pokusaja === 0) {
                    $greske['lozinka'] = "Kriva lozinka! Vaš račun je blokiran zbog previše unosa pogrešne lozinke!";
                    $baza->korisnik_azuriraj_blokiran($korisnicko_ime, 1);
                    $baza->spojiDB();
                    $baza->dnevnik_ostale_radnje("Blokiranje korisnika");
                    $baza->zatvoriDB();
                } else {
                    $greske['lozinka'] = "Kriva lozinka! Broj preostalih pokušaja: " . $broj_preostalih_pokusaja;
                    $baza->spojiDB();
                    $baza->dnevnik_prijava_odjava("Neuspješna prijava");
                    $baza->zatvoriDB();
                }

                $baza->zatvoriDB();
            }
        }
        if (count($greske) == 0) {
                $konfiguracija = new Konfiguracija($direktorij);
                $trenutno_vrijeme = $konfiguracija->virtualnoVrijeme();
                $trajanje_kolacica = $konfiguracija->vratiTrajanjeKolacica();
                $istek_kolacica = strtotime($trenutno_vrijeme) + $trajanje_kolacica * 24 * 60 * 60;
                if (isset($_POST['zapamti_ime']) && isset($_COOKIE['zapamti_ime']) && $_COOKIE['zapamti_ime'] != -1) {
                    setcookie("zapamti_ime", $korisnicko_ime, $istek_kolacica, "/");
                } elseif (!isset($_POST['zapamti_ime'])) {
                    if (isset($_COOKIE['zapamti_ime'])) {
                        setcookie("zapamti_ime", -1, $istek_kolacica, "/");
                    }
                }
            
            $baza = new Baza();
            $baza->spojiDB();
            $baza->korisnik_azuriraj_pokusaji_prijave_reset($korisnicko_ime);
            $korisnik = $baza->korisnik_dohvati($korisnicko_ime)[0];
            $baza->zatvoriDB();

            Sesija::kreirajKorisnika($korisnik['id_korisnik'], $korisnik['id_uloga'], $korisnik['korisnicko_ime']);
            $baza = new Baza();
            $baza->spojiDB();
            $baza->dnevnik_prijava_odjava("Uspješna prijava!");
            $baza->zatvoriDB();
            
            if (isset($_COOKIE['zadnja_stranica']) && isset($_COOKIE['uvjeti_koristenja']) && $_COOKIE['zadnja_stranica'] != -1 && $_COOKIE['zadnja_stranica'] != 1) {
                $zadnja_stranica = $_COOKIE['zadnja_stranica'];
                header("Location: $putanja/$zadnja_stranica");
            } else {
                header("Location: ../index.php");
            }
            if(isset($_POST["odjava"])){
                Sesija::obrisiSesiju();
                header("Location: index.php");
            }
        }
        
    }
    $smarty->assign("zapamti_ime", $zapamti_ime);
    $smarty->assign("greske_prijava", $greske);
    $smarty->display("prijava.tpl");
    $smarty->display("podnozje.tpl");

