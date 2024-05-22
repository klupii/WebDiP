<?php
    include_once '../klase/konfiguracija.class.php';
    $greske = array();
    $direktorij = dirname(getcwd());
    $konfiguracijske_postavke = array();
    foreach ($_POST as $naziv_postavke => $vrijednost_postavke) {
        $vrijednost = filter_input(INPUT_POST, $naziv_postavke, FILTER_SANITIZE_STRING);
        $konfiguracijske_postavke[$naziv_postavke] = (isset($vrijednost_postavke) || empty($vrijednost)) ? $vrijednost_postavke : "xxx";
        provjeriPostavke($naziv_postavke, $vrijednost_postavke);
    }
    if (count($greske) == 0) {
        $konfiguracija = new Konfiguracija($direktorij);
        $konfiguracija->postaviTrajanjeKolacica($konfiguracijske_postavke['trajanje_kolacica']);
        $konfiguracija->postaviBrojStranicaStranicenja($konfiguracijske_postavke['broj_stranica_stranicenja']);
        $konfiguracija->postaviTrajanjeSesije($konfiguracijske_postavke['trajanje_sesije']);
        $konfiguracija->postaviBrojNeuspjesnihPrijava($konfiguracijske_postavke['broj_neuspjesnih_prijava']);
        $konfiguracija->postaviIstekVerifikacije($konfiguracijske_postavke['istek_verifikacije']);
        $konfiguracija->spremiKonfiguraciju();
        include_once "../klase/sesija.class.php";
        Sesija::kreirajSesiju();

        if (isset($_SESSION['vrijeme_registracije'])) {
            $_SESSION['vrijeme_registracije'] = $konfiguracija->virtualnoVrijeme();
        }
    }
    echo json_encode(array("prvo" => $konfiguracijske_postavke, "drugo" => $greske));

    function provjeriPostavke($naziv_postavke, $vrijednost_postavke) {
        global $greske;
        if (!isset($vrijednost_postavke)) {
            $greske[$naziv_postavke] = parseCitljivost($naziv_postavke) . " - nije upisano!";
        }

        if (!broj_je($vrijednost_postavke)) {
            $greske[$naziv_postavke] = parseCitljivost($naziv_postavke) . " - unesena vrijednost nije broj!";
        }
        if ($vrijednost_postavke >= 100000 || $vrijednost_postavke <= -100000) {
            $greske[$naziv_postavke] = parseCitljivost($naziv_postavke) . " - unesena vrijednost ima više od 5 znamenaka";
        }
    }

    function parseCitljivost($string) {
        return ucfirst(str_replace("_", " ", $string));
    }

    function broj_je($string) {
        if ($string[0] == "-") {
            return ctype_digit(substr($string, 1));
        }
        return ctype_digit($string);
    }