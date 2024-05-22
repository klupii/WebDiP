<?php
    $direktorij = dirname(getcwd());

    include_once "$direktorij/klase/konfiguracija.class.php";
    include_once "$direktorij/klase/baza.class.php";
    include_once "$direktorij/skripte/funkcije.php";

    $korisnicko_ime = $_POST['korisnicko_ime'];

    $baza = new Baza();
    $baza->spojiDB();
    $rezultat = $baza->korisnik_dohvati($korisnicko_ime)[0];

    $email = $rezultat['email'];

    $lozinka = generirajLozinku();
    $sha256 = sha256($lozinka);
    $lozinka_sha256 = $sha256['sha256'];
    $sol = $sha256['sol'];

    $baza->korisnik_azuriraj_lozinka($korisnicko_ime, $lozinka, $sol, $lozinka_sha256);
    $baza->zatvoriDB();

    $baza->spojiDB();
    $baza->dnevnik_ostale_radnje("Zaboravljena lozinka");
    $baza->zatvoriDB();
    zaboravljenaLozinkaMail($email, $korisnicko_ime, $lozinka);

    echo json_encode(array("uspjeh" => $korisnicko_ime));