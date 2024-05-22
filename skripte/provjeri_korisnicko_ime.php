<?php
    include_once "../klase/baza.class.php";

    $korisnicko_ime = $_POST['korisnicko_ime'];

    $baza=new Baza();
    $baza->spojiDB();
    $tablica = $baza->dohvatiTablicu("korisnik");
    $baza->zatvoriDB();
    $postoji = array();

    foreach($tablica as $red){
        if($red['korisnicko_ime']==$korisnicko_ime){
            $postoji['postoji']=$red['korisnicko_ime'];
        }
    }

    echo json_encode($postoji);