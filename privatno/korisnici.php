<?php
    $direktorij = dirname(getcwd());
    include_once "$direktorij/klase/baza.class.php";
    
    $baza = new Baza();
    $baza->spojiDB();
    $rezultat = $baza->korisnik_dohvati_zasticeno();
    $baza->zatvoriDB();

    echo "<table border=1><tr><th>Korisničko ime</th><th>Ime i prezime</th><th>E-mail</th><th>Lozinka</th></tr>";

    foreach($rezultat as $red){
        echo "<tr><td>".$red['korisnicko_ime']."</td><td>".$red['ime_prezime']."</td><td>".$red['email']."</td><td>".$red['lozinka']."</td></tr>";
    }

    echo "</table>";
