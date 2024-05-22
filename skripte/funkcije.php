<?php

    function dovoljanBrojZnakova($min, $max, $unos) {
        if (strlen($unos) < $min)
            return "Uneseno je manje od " . $min . " znakova";
        if (strlen($unos) > $max)
            return "Uneseno je viĹˇe od " . $max . " znakova";
        return true;
    }

    function prazanUnos($unos) {
        return !strlen(trim($unos)) > 0;
    }

    function parseCitljivost($string) {
        return ucfirst(str_replace("_", " ", $string));
    }

    function sha256($lozinka, $sol = null) {
        if ($sol == null) {
            $znakovi = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $znakovi_duljina = strlen($znakovi);
            $sol = '';
            for ($i = 0; $i < 64; $i++) {
                $sol .= $znakovi[rand(0, $znakovi_duljina - 1)];
            }
        }

        $sha = hash("sha256", $lozinka . $sol);
        return array("lozinka" => $lozinka, "sha256" => $sha, "sol" => $sol);
    }

    function aktivacijskiKod() {
        $znakovi = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $znakovi_duljina = strlen($znakovi);
        $rand_string = '';
        for ($i = 0; $i < 8; $i++) {
            $rand_string .= $znakovi[rand(0, $znakovi_duljina - 1)];
        }
        return $rand_string;
    }

    function posaljiMail($mail_adresa, $naslov, $poruka) {
        $headers = "From: " . strip_tags("klupoglav@student.foi.hr") . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        mail($mail_adresa, $naslov, $poruka, $headers);
    }

    function verifikacijskiMail($mail_adresa, $korisnicko_ime, $aktivacijski_kod, $datum_isteka) {
        $naslov = 'Prodaja proizvoda - Registracija';
        $poveznica = "http://barka.foi.hr/WebDiP/2022_projekti/WebDiP2022x029/potvrda_registracije.php?aktivacijski_kod=" . $aktivacijski_kod . "&korisnicko_ime=" . $korisnicko_ime;

        $poruka = ' 
        <html> 
        <head> 
        </head> 
        <body style="color:#57779a;text-align:center;"> 
            <h1 style="background:#647394;color:#fff;text-align:center;font-size:30px;padding:20px;">Zahvaljujemo se na registraciji</h1>
            <p style="padding-left:10px;margin-top:30px;">Pritisnite na aktivacijski kod kako bi potvrdili registraciju!</p><p style="padding-left:10px;">Aktivacijski kod traje do: ' . $datum_isteka . '</p>
    <p style="padding-left:15px;">Korisničko ime: ' . $korisnicko_ime . '</p>
                    <a href="' . $poveznica . '" style="padding-left:15px;color:black;font-decoration:underline;font-style:italic;margin-bottom:20px;">Aktivacijski kod: ' . $aktivacijski_kod . '</a> 
        </body> 
        </html>';

        posaljiMail($mail_adresa, $naslov, $poruka);
    }

    function zaboravljenaLozinkaMail($mail_adresa, $korisnicko_ime, $lozinka) {
        $naslov = 'Prodaja proizvoda - Zaboravljena lozinka';
        $poveznica = "https://barka.foi.hr/WebDiP/2022_projekti/WebDiP2022x029/obrasci/prijava.php";
        $poruka = ' 
        <html> 
        <head> 
        </head> 
        <body style="color:#57779a;text-align:center;"> 
            <h1 style="background:#647394;color:#fff;text-align:center;font-size:30px;padding:20px;">Zaboravljena lozinka</h1>
            <p style="padding-left:10px;margin-top:30px;">Ispod se nalazi Vaša nova lozinka, pritisnite na nju kako bi se vratili na stranicu prijave</p>
    <p style="padding-left:15px;">Korisničko ime: ' . $korisnicko_ime . '</p>
                    <a href="' . $poveznica . '" style="padding-left:15px;color:black;font-decoration:underline;font-style:italic;margin-bottom:20px;">Nova lozinka: ' . $lozinka . '</a> 
        </body> 
        </html>';

        posaljiMail($mail_adresa, $naslov, $poruka);
    }

    function generirajLozinku() {
        $znakovi = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $znakovi_duljina = strlen($znakovi);
        $rand_string = '';
        for ($i = 0; $i < 10; $i++) {
            $rand_string .= $znakovi[rand(0, $znakovi_duljina - 1)];
        }
        return $rand_string;
    }
