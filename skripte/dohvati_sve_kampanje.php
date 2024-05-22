<?php
   $direktorij = dirname(getcwd());

    include_once "$direktorij/klase/baza.class.php";
    include_once "$direktorij/klase/konfiguracija.class.php";

    $konfiguracija = new Konfiguracija($direktorij);
    $limit = $konfiguracija->vratiBrojStranicaStranicenja();
    $pomak = $konfiguracija->vratiPomak();

    $atribut_za_sortiranje1 = (isset($_POST['atribut_za_sortiranje1']) && $_POST['atribut_za_sortiranje1'] != null) ? $_POST['atribut_za_sortiranje1'] : null;
    $atribut_za_pretrazivanje1 = (isset($_POST['atribut_za_pretrazivanje1']) && $_POST['atribut_za_pretrazivanje1'] != null) ? $_POST['atribut_za_pretrazivanje1'] : null;
    $smjer_sortiranja1 = (isset($_POST['smjer_sortiranja1']) && $_POST['smjer_sortiranja1'] != null) ? $_POST['smjer_sortiranja1'] : null;
    $vrijednost_za_pretrazivanje1 = (isset($_POST['vrijednost_za_pretrazivanje1']) && $_POST['vrijednost_za_pretrazivanje1'] != null) ? $_POST['vrijednost_za_pretrazivanje1'] : null;
    $od = (isset($_POST['datum_od']) && $_POST['datum_od'] != "") ? $_POST['datum_od'] : null;
    $do = (isset($_POST['datum_do']) && $_POST['datum_do'] != "") ? $_POST['datum_do'] : null;

    $trenutna_stranica = (isset($_POST['broj_stranice1']) && $_POST['broj_stranice1'] != null) ? $_POST['broj_stranice1'] : 0;
 
 
    $baza = new Baza();
    $baza->spojiDB();
    $broj_redova = count($baza->dohvati_sve_kampanje(999999999, 0, $atribut_za_sortiranje1, $smjer_sortiranja1, $atribut_za_pretrazivanje1, $vrijednost_za_pretrazivanje1, $od, $do));

    $max_broj_stranica = ceil($broj_redova / $limit) - 1;
    if ($trenutna_stranica > $max_broj_stranica) {
        $trenutna_stranica = $max_broj_stranica;
    } elseif ($trenutna_stranica < 0) {
        $trenutna_stranica = 0;
    }

    $offset = $trenutna_stranica * $limit;
    $tablica = $baza->dohvati_sve_kampanje($limit, $offset, $atribut_za_sortiranje1, $smjer_sortiranja1, $atribut_za_pretrazivanje1, $vrijednost_za_pretrazivanje1, $od, $do);
    $baza->zatvoriDB();

    echo json_encode(array("tablica" => $tablica, "trenutna_stranica" => $trenutna_stranica, "broj_redova" => $broj_redova));
