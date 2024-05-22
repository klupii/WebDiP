<?php
    $direktorij = dirname(getcwd());

    include_once "$direktorij/klase/baza.class.php";
    include_once "$direktorij/klase/konfiguracija.class.php";

    $konfiguracija = new Konfiguracija($direktorij);
    $limit = $konfiguracija->vratiBrojStranicaStranicenja();
    $pomak = $konfiguracija->vratiPomak();

    $atribut_za_sortiranje = (isset($_POST['atribut_za_sortiranje']) && $_POST['atribut_za_sortiranje'] != null) ? $_POST['atribut_za_sortiranje'] : null;
    $atribut_za_pretrazivanje = (isset($_POST['atribut_za_pretrazivanje']) && $_POST['atribut_za_pretrazivanje'] != null) ? $_POST['atribut_za_pretrazivanje'] : null;
    $smjer_sortiranja = (isset($_POST['smjer_sortiranja']) && $_POST['smjer_sortiranja'] != null) ? $_POST['smjer_sortiranja'] : null;
    $vrijednost_za_pretrazivanje = (isset($_POST['vrijednost_za_pretrazivanje']) && $_POST['vrijednost_za_pretrazivanje'] != null) ? $_POST['vrijednost_za_pretrazivanje'] : null;
    $od = (isset($_POST['datum_od']) && $_POST['datum_od'] != "") ? date("Y-m-d H:i:s",strtotime($_POST['datum_od'])+$pomak*60*60) : null;
    $do = (isset($_POST['datum_do']) && $_POST['datum_do'] != "") ? date("Y-m-d H:i:s",strtotime($_POST['datum_do'])+$pomak*60*60) : null;

    $trenutna_stranica = (isset($_POST['broj_stranice']) && $_POST['broj_stranice'] != null) ? $_POST['broj_stranice'] : 0;

    $baza = new Baza();
    $baza->spojiDB();
    $broj_redova = count($baza->dnevnik_dohvati_sve(999999999, 0, $atribut_za_sortiranje, $smjer_sortiranja, $atribut_za_pretrazivanje, $vrijednost_za_pretrazivanje,$od,$do));

    $max_broj_stranica = ceil($broj_redova / $limit) - 1;
    if ($trenutna_stranica > $max_broj_stranica) {
        $trenutna_stranica = $max_broj_stranica;
    } elseif ($trenutna_stranica < 0) {
        $trenutna_stranica = 0;
    }

    $offset = $trenutna_stranica * $limit;
    $tablica = $baza->dnevnik_dohvati_sve($limit, $offset, $atribut_za_sortiranje, $smjer_sortiranja, $atribut_za_pretrazivanje, $vrijednost_za_pretrazivanje,$od,$do);
    $baza->zatvoriDB();

    echo json_encode(array("tablica" => $tablica, "trenutna_stranica" => $trenutna_stranica, "broj_redova" => $broj_redova));

