<?php
    $direktorij = dirname(getcwd());

    include_once "$direktorij/klase/konfiguracija.class.php";
    include_once "$direktorij/klase/baza.class.php";
    include_once "$direktorij/klase/sesija.class.php";

    Sesija::kreirajSesiju();
    $korisnicko_ime = $_SESSION['korisnicko_ime'];

    $konfiguracija = new Konfiguracija($direktorij);
    $trenutno_vrijeme= $konfiguracija->virtualnoVrijeme();
    $trajanje_kolacica = $konfiguracija->vratiTrajanjeKolacica();
    $istek_kolacica = strtotime($trenutno_vrijeme)+$trajanje_kolacica*24*60*60;

    $baza=new Baza();
    $baza->spojiDB();
    $baza->korisnik_azuriraj_uvjeti_koristenja($korisnicko_ime, date("Y-m-d H:i:s",$istek_kolacica));
    $baza->zatvoriDB();

    setcookie("uvjeti_koristenja","1",$istek_kolacica,"/");
    setcookie("zapamti_ime","1",$istek_kolacica,"/");
    setcookie("zadnja_stranica","1",$istek_kolacica,"/");

