<?php

    $direktorij = isset($direktorij) ? $direktorij : 
    dirname(getcwd());

    include_once "$direktorij/skripte/funkcije.php";
    include_once "$direktorij/klase/konfiguracija.class.php";
    include_once "$direktorij/klase/sesija.class.php";

class Baza {
    const server = "localhost"; //uvijek ostaje localhost
    const korisnik = "WebDiP2022x029"; //kad radimo remote onda je WebDiP2022x029
    const lozinka = "admin_JmMI"; //lozinka na barci je admin_JmMI
    const baza = "WebDiP2022x029";

    private $veza = null;
    private $greska = '';

    function spojiDB() {
        $this->veza = new mysqli(self::server, self::korisnik, self::lozinka, self::baza);
        if ($this->veza->connect_errno) {
            echo "Neuspješno spajanje na bazu: " . $this->veza->connect_errno . ", " .
            $this->veza->connect_error;
            $this->greska = $this->veza->connect_error;
        }
        $this->veza->set_charset("utf8");
        if ($this->veza->connect_errno) {
            echo "Neuspješno postavljanje znakova za bazu: " . $this->veza->connect_errno . ", " .
            $this->veza->connect_error;
            $this->greska = $this->veza->connect_error;
        }
        return $this->veza;
    }

    function zatvoriDB() {
        @$this->veza->close();
    }

    function selectDB($upit) {
        $rezultat = $this->veza->query($upit);
        if ($this->veza->connect_errno) {
            echo "Greška kod upita: {$upit} - " . $this->veza->connect_errno . ", " .
            $this->veza->connect_error;
            $this->greska = $this->veza->connect_error;
        }
        if (!$rezultat) {
            $rezultat = null;
        }
        return $rezultat;
    }

    function updateDB($upit, $skripta = '') {
        $rezultat = $this->veza->query($upit);
        if ($this->veza->connect_errno) {
            echo "Greška kod upita: {$upit} - " . $this->veza->connect_errno . ", " .
            $this->veza->connect_error;
            $this->greska = $this->veza->connect_error;
        } else {
            if ($skripta != '') {
                header("Location: $skripta");
            }
        }

        return $rezultat;
    }

    function pogreskaDB() {
        if ($this->greska != '') {
            return true;
        } else {
            return false;
        }
    }

    function dnevnik_prijava_odjava($radnja) {
        Sesija::kreirajSesiju();
        if (Sesija::kreiranKorisnik()) {
            global $direktorij;
            $konfiguracija = new Konfiguracija($direktorij);

            $veza = $this->veza;
            $datum_vrijeme = date("Y-m-d H:i:s", strtotime($konfiguracija->virtualnoVrijeme()));
            $id_tip_zapisa = 1;
            $id_korisnik = isset($_SESSION['id_korisnik']) ? $_SESSION['id_korisnik'] : -1;

            $statement = $veza->prepare("INSERT INTO dnevnik_rada (id_tip_zapisa,id_korisnik,datum_vrijeme,radnja) VALUES (?,?,?,?)");
            $statement->bind_param("ssii",$id_tip_zapisa,$id_korisnik,$datum_vrijeme,$radnja);
            $statement->execute();
        }
    }

    function dnevnik_rad_s_bazom($veza, $upit) {
        Sesija::kreirajSesiju();
        if (Sesija::kreiranKorisnik()) {
            global $direktorij;
            $konfiguracija = new Konfiguracija($direktorij);

            $datum_vrijeme = date("Y-m-d H:i:s", strtotime($konfiguracija->virtualnoVrijeme()));
            $id_tip_zapisa = 2;
            $id_korisnik = isset($_SESSION['id_korisnik']) ? $_SESSION['id_korisnik'] : -1;

            $statement = $veza->prepare("INSERT INTO dnevnik_rada (upit,datum_vrijeme,id_tip_zapisa,id_korisnik) VALUES (?,?,?,?)");
            $statement->bind_param("ssii", $upit, $datum_vrijeme, $id_tip_zapisa, $id_korisnik);
            $statement->execute();
        }
    }

    function dnevnik_ostale_radnje($radnja) {
        Sesija::kreirajSesiju();
        if (Sesija::kreiranKorisnik()) {
            global $direktorij;
            $konfiguracija = new Konfiguracija($direktorij);

            $veza = $this->veza;
            $datum_vrijeme = date("Y-m-d H:i:s", strtotime($konfiguracija->virtualnoVrijeme()));
            $id_tip_zapisa = 3;
            $id_korisnik = isset($_SESSION['id_korisnik']) ? $_SESSION['id_korisnik'] : -1;

            $statement = $veza->prepare("INSERT INTO dnevnik_rada (radnja,datum_vrijeme,id_tip_zapisa,id_korisnik) VALUES (?,?,?,?)");
            $statement->bind_param("ssii", $radnja, $datum_vrijeme, $id_tip_zapisa, $id_korisnik);
            $statement->execute();
        }
    }
    
    function dohvatiTablicu($naziv_tablice) {
        $veza = $this->veza;
        $this->dnevnik_rad_s_bazom($veza, "SELECT * FROM $naziv_tablice");
        $statement = $veza->prepare("SELECT * FROM $naziv_tablice");
        $statement->execute();
        return $statement->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    function dnevnik_dohvati_sve($limit, $offset, $atribut_za_sortiranje = null, $smjer_sortiranja = null, $atribut_za_pretrazivanje = null, $vrijednost_za_pretrazivanje = null, $od = null, $do = null) {
        $upit = "SELECT upit,radnja,datum_vrijeme,naziv,korisnicko_ime FROM dnevnik_rada d JOIN tip_zapisa tz ON tz.id_tip_zapisa=d.id_tip_zapisa JOIN korisnik k ON k.id_korisnik=d.id_korisnik WHERE 1";
        $vrijednost_za_pretrazivanje = "%$vrijednost_za_pretrazivanje%";
        $veza = $this->veza;
        if ($atribut_za_pretrazivanje != null && $atribut_za_sortiranje != null && ($od != null && $do != null)) {
            switch ($atribut_za_pretrazivanje) {
                case "upit":
                    $upit .= " AND upit LIKE ?";
                    break;
                case "radnja":
                    $upit .= " AND radnja LIKE ?";
                    break;
                case "datum_vrijeme":
                    $upit .= " AND datum_vrijeme LIKE ?";
                    break;
                case "naziv":
                    $upit .= " AND naziv LIKE ?";
                    break;
                case "korisnicko_ime":
                    $upit .= " AND korisnicko_ime LIKE ?";
                    break;
            }
            $upit .= " AND datum_vrijeme>? AND datum_vrijeme<?";
            switch ($atribut_za_sortiranje) {
                case "upit":
                    $upit .= " ORDER BY upit";
                    break;
                case "radnja":
                    $upit .= " ORDER BY radnja";
                    break;
                case "datum_vrijeme":
                    $upit .= " ORDER BY datum_vrijeme";
                    break;
                case "naziv":
                    $upit .= " ORDER BY naziv";
                    break;
                case "korisnicko_ime":
                    $upit .= " ORDER BY korisnicko_ime";
                    break;
            }
            if ($smjer_sortiranja == "ASC") {
                $upit .= " ASC";
            } else {
                $upit .= " DESC";
            }
            $upit .= " LIMIT ? OFFSET ?";

            $statement = $veza->prepare($upit);
            $statement->bind_param("sssii", $vrijednost_za_pretrazivanje, $od, $do, $limit, $offset);
        } else if ($atribut_za_sortiranje != null && ($od != null && $do != null)) {
            $upit .= " AND datum_vrijeme>? AND datum_vrijeme<?";
            switch ($atribut_za_sortiranje) {
                case "upit":
                    $upit .= " ORDER BY upit";
                    break;
                case "radnja":
                    $upit .= " ORDER BY radnja";
                    break;
                case "datum_vrijeme":
                    $upit .= " ORDER BY datum_vrijeme";
                    break;
                case "naziv":
                    $upit .= " ORDER BY naziv";
                    break;
                case "korisnicko_ime":
                    $upit .= " ORDER BY korisnicko_ime";
                    break;
            }
            if ($smjer_sortiranja == "ASC") {
                $upit .= " ASC";
            } else {
                $upit .= " DESC";
            }
            $upit .= " LIMIT ? OFFSET ?";
            $statement = $veza->prepare($upit);
            $statement->bind_param("ssii", $od, $do, $limit, $offset);
        } else if ($atribut_za_pretrazivanje != null && ($od != null && $do != null)) {
            switch ($atribut_za_pretrazivanje) {
                case "upit":
                    $upit .= " AND upit LIKE ?";
                    break;
                case "radnja":
                    $upit .= " AND radnja LIKE ?";
                    break;
                case "datum_vrijeme":
                    $upit .= " AND datum_vrijeme LIKE ?";
                    break;
                case "naziv":
                    $upit .= " AND naziv LIKE ?";
                    break;
                case "korisnicko_ime":
                    $upit .= " AND korisnicko_ime LIKE ?";
                    break;
            }
            $upit .= " AND datum_vrijeme>? AND datum_vrijeme<?";
            $upit .= " LIMIT ? OFFSET ?";

            $statement = $veza->prepare($upit);
            $statement->bind_param("sssii", $vrijednost_za_pretrazivanje, $od, $do, $limit, $offset);
        } else if ($atribut_za_pretrazivanje != null && $atribut_za_sortiranje != null) {
            switch ($atribut_za_pretrazivanje) {
                case "upit":
                    $upit .= " AND upit LIKE ?";
                    break;
                case "radnja":
                    $upit .= " AND radnja LIKE ?";
                    break;
                case "datum_vrijeme":
                    $upit .= " AND datum_vrijeme LIKE ?";
                    break;
                case "naziv":
                    $upit .= " AND naziv LIKE ?";
                    break;
                case "korisnicko_ime":
                    $upit .= " AND korisnicko_ime LIKE ?";
                    break;
            }
            switch ($atribut_za_sortiranje) {
                case "upit":
                    $upit .= " ORDER BY upit";
                    break;
                case "radnja":
                    $upit .= " ORDER BY radnja";
                    break;
                case "datum_vrijeme":
                    $upit .= " ORDER BY datum_vrijeme";
                    break;
                case "naziv":
                    $upit .= " ORDER BY naziv";
                    break;
                case "korisnicko_ime":
                    $upit .= " ORDER BY korisnicko_ime";
                    break;
            }
            if ($smjer_sortiranja == "ASC") {
                $upit .= " ASC";
            } else {
                $upit .= " DESC";
            }
            $upit .= " LIMIT ? OFFSET ?";

            $statement = $veza->prepare($upit);
            $statement->bind_param("sii", $vrijednost_za_pretrazivanje, $limit, $offset);
        } else if ($atribut_za_sortiranje != null) {

            switch ($atribut_za_sortiranje) {
                case "upit":
                    $upit .= " ORDER BY upit";
                    break;
                case "radnja":
                    $upit .= " ORDER BY radnja";
                    break;
                case "datum_vrijeme":
                    $upit .= " ORDER BY datum_vrijeme";
                    break;
                case "naziv":
                    $upit .= " ORDER BY naziv";
                    break;
                case "korisnicko_ime":
                    $upit .= " ORDER BY korisnicko_ime";
                    break;
            }
            if ($smjer_sortiranja == "ASC") {
                $upit .= " ASC";
            } else {
                $upit .= " DESC";
            }
            $upit .= " LIMIT ? OFFSET ?";
            $statement = $veza->prepare($upit);
            $statement->bind_param("ii", $limit, $offset);
        } else if ($atribut_za_pretrazivanje != null) {
            switch ($atribut_za_pretrazivanje) {
                case "upit":
                    $upit .= " AND upit LIKE ?";
                    break;
                case "radnja":
                    $upit .= " AND radnja LIKE ?";
                    break;
                case "datum_vrijeme":
                    $upit .= " AND datum_vrijeme LIKE ?";
                    break;
                case "naziv":
                    $upit .= " AND naziv LIKE ?";
                    break;
                case "korisnicko_ime":
                    $upit .= " AND korisnicko_ime LIKE ?";
                    break;
            }
            $upit .= " LIMIT ? OFFSET ?";

            $statement = $veza->prepare($upit);
            $statement->bind_param("sii", $vrijednost_za_pretrazivanje, $limit, $offset);
        } else if ($od != null && $do != null) {
            $upit .= " AND datum_vrijeme>? AND datum_vrijeme<?";
            $upit .= " LIMIT ? OFFSET ?";

            $statement = $veza->prepare($upit);
            $statement->bind_param("ssii", $od, $do, $limit, $offset);
        } else {
            $upit .= " LIMIT ? OFFSET ?";
            $statement = $veza->prepare($upit);
            $statement->bind_param("ii", $limit, $offset);
        }
        $statement->execute();
        return $statement->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    function korisnik_dohvati_prijava($korisnicko_ime) {
        $veza = $this->veza;
        $this->dnevnik_rad_s_bazom($veza, "SELECT korisnicko_ime,lozinka_sha256,sol FROM korisnik WHERE korisnicko_ime='$korisnicko_ime'");
        $statement = $veza->prepare("SELECT korisnicko_ime,lozinka_sha256,sol FROM korisnik WHERE korisnicko_ime=?");
        $statement->bind_param("s", $korisnicko_ime);
        $statement->execute();
        return $statement->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    function korisnik_dohvati($korisnicko_ime) {
        $veza = $this->veza;
        $this->dnevnik_rad_s_bazom($veza, "SELECT * FROM korisnik WHERE korisnicko_ime='$korisnicko_ime'");
        $statement = $veza->prepare("SELECT * FROM korisnik WHERE korisnicko_ime=?");
        $statement->bind_param("s", $korisnicko_ime);
        $statement->execute();
        return $statement->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    function korisnik_azuriraj_lozinka($korisnicko_ime, $lozinka, $sol, $lozinka_sha256) {
        $veza = $this->veza;
        $this->dnevnik_rad_s_bazom($veza, "UPDATE korisnik SET lozinka='$lozinka', lozinka_sha256='$lozinka_sha256',sol='$sol' WHERE korisnicko_ime='$korisnicko_ime'");
        $statement = $veza->prepare("UPDATE korisnik SET lozinka=?, lozinka_sha256=?,sol=? WHERE korisnicko_ime=?");
        $statement->bind_param("ssss", $lozinka, $lozinka_sha256, $sol, $korisnicko_ime);
        $statement->execute();
    }
    
    
    function korisnik_azuriraj_pokusaji_prijave_increment($korisnicko_ime) {
        $veza = $this->veza;
        $this->dnevnik_rad_s_bazom($veza, "UPDATE korisnik SET broj_unosa=broj_unosa+1 WHERE korisnicko_ime='$korisnicko_ime'");
        $statement = $veza->prepare("UPDATE korisnik SET broj_unosa=broj_unosa+1 WHERE korisnicko_ime=?");
        $statement->bind_param("s", $korisnicko_ime);
        $statement->execute();
    }

    function korisnik_azuriraj_pokusaji_prijave_reset($korisnicko_ime) {
        $veza = $this->veza;
        $this->dnevnik_rad_s_bazom($veza, "UPDATE korisnik SET broj_unosa=0 WHERE korisnicko_ime='$korisnicko_ime'");
        $statement = $veza->prepare("UPDATE korisnik SET broj_unosa=0 WHERE korisnicko_ime=?");
        $statement->bind_param("s", $korisnicko_ime);
        $statement->execute();
    }
    
    function korisnik_azuriraj_pokusaji_prijave_reset_id($id_korisnik) {
        $veza = $this->veza;
        $this->dnevnik_rad_s_bazom($veza, "UPDATE korisnik SET broj_unosa = 0 WHERE id_korisnik = $id_korisnik");
        $statement = $veza->prepare("UPDATE korisnik SET broj_unosa = 0 WHERE id_korisnik = ?");
        $statement->bind_param("i", $id_korisnik);
        $statement->execute
        ();
    }
    
    function korisnik_azuriraj_blokiran($korisnicko_ime, $int) {
        $veza = $this->veza;
        $this->dnevnik_rad_s_bazom($veza, "UPDATE korisnik SET blokiran=$int WHERE korisnicko_ime='$korisnicko_ime'");
        $statement = $veza->prepare("UPDATE korisnik SET blokiran=? WHERE korisnicko_ime=?");
        $statement->bind_param("is", $int, $korisnicko_ime);
        $statement->execute();
    }
    
    
    function korisnik_dohvati_index($limit, $offset, $atribut_za_sortiranje = null, $smjer_sortiranja = null, $atribut_za_pretrazivanje = null, $vrijednost_za_pretrazivanje = null) {
        $upit = "SELECT id_korisnik, korisnicko_ime, ime_prezime FROM korisnik WHERE 1";
        $vrijednost_za_pretrazivanje = "%$vrijednost_za_pretrazivanje%";
        $veza = $this->veza;
        if ($atribut_za_pretrazivanje != null && $atribut_za_sortiranje != null) {
            switch ($atribut_za_pretrazivanje) {
                case "id_korisnik":
                    $upit .= " AND id_korisnik LIKE ?";
                case "korisnicko_ime":
                    $upit .= " AND korisnicko_ime LIKE ?";
                    break;
                case "ime_prezime":
                    $upit .= " AND ime_prezime LIKE ?";
                    break;               
            }
            switch ($atribut_za_sortiranje) {
                case "id_korisnik":
                    $upit .= " ORDER BY id_korisnik";
                    break;
                case "korisnicko_ime":
                    $upit .= " ORDER BY korisnicko_ime";
                    break;
                case "ime_prezime":
                    $upit .= " ORDER BY ime_prezime";
                    break;
            }
            if ($smjer_sortiranja == "ASC") {
                $upit .= " ASC";
            } else {
                $upit .= " DESC";
            }
            $upit .= " LIMIT ? OFFSET ?";
            $statement = $veza->prepare($upit);
            $statement->bind_param("sii", $vrijednost_za_pretrazivanje, $limit, $offset);
        } elseif ($atribut_za_sortiranje != null) {
            switch ($atribut_za_sortiranje) {
                case "id_korisnik":
                    $upit .= " ORDER BY id_korisnik";
                    break;
                case "korisnicko_ime":
                    $upit .= " ORDER BY korisnicko_ime";
                    break;
                case "ime_prezime":
                    $upit .= " ORDER BY ime_prezime";
                    break;
            }
            if ($smjer_sortiranja == "ASC") {
                $upit .= " ASC";
            } else {
                $upit .= " DESC";
            }
            $upit .= " LIMIT ? OFFSET ?";
            $statement = $veza->prepare($upit);
            $statement->bind_param("ii", $limit, $offset);
        } else if ($atribut_za_pretrazivanje != null) {
            switch ($atribut_za_pretrazivanje) {
                    case "id_korisnik":
                    $upit .= " AND id_korisnik LIKE ?";
                    break;
                case "korisnicko_ime":
                    $upit .= " AND korisnicko_ime LIKE ?";
                    break;
                case "ime_prezime":
                    $upit .= " AND ime_prezime LIKE ?";
                    break;
            }
            $upit .= " LIMIT ? OFFSET ?";
            $statement = $veza->prepare($upit);
            $statement->bind_param("sii", $vrijednost_za_pretrazivanje, $limit, $offset);
        } else {
            $upit .= " LIMIT ? OFFSET ?";
            $statement = $veza->prepare($upit);
            $statement->bind_param("ii", $limit, $offset);
        }
        $statement->execute();
        return $statement->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    function dohvati_moderatore($limit, $offset, $atribut_za_sortiranje = null, $smjer_sortiranja = null, $atribut_za_pretrazivanje = null, $vrijednost_za_pretrazivanje = null) {
       $upit = "SELECT k.korisnicko_ime, k.ime_prezime, COUNT(kip.id_proizvoda) AS broj_proizvoda FROM korisnik k JOIN kupovina ku ON k.id_korisnik = ku.id_korisnik
                JOIN kupovina_ima_proizvod kip ON ku.id_kupovine = kip.id_kupovine WHERE k.id_uloga = 2";

       $vrijednost_za_pretrazivanje = "%$vrijednost_za_pretrazivanje%";

       if ($atribut_za_pretrazivanje != null && $atribut_za_sortiranje != null) {
           switch ($atribut_za_pretrazivanje) {
               case "id_korisnik":
                   $upit .= " AND k.id_korisnik LIKE ?";
                   break;
               case "korisnicko_ime":
                   $upit .= " AND k.korisnicko_ime LIKE ?";
                   break;
               case "ime_prezime":
                   $upit .= " AND k.ime_prezime LIKE ?";
                   break;
               case "broj_proizvoda":
                   $upit .= " AND COUNT(kip.id_proizvoda) LIKE ?";
                   break;
           }

           $upit .= " GROUP BY k.id_korisnik, k.korisnicko_ime, k.ime_prezime";

           switch ($atribut_za_sortiranje) {
               case "id_korisnik":
                   $upit .= " ORDER BY k.id_korisnik";
                   break;
               case "korisnicko_ime":
                   $upit .= " ORDER BY k.korisnicko_ime";
                   break;
               case "ime_prezime":
                   $upit .= " ORDER BY k.ime_prezime";
                   break;
               case "broj_proizvoda":
                   $upit .= " ORDER BY COUNT(kip.id_proizvoda)";
                   break;
           }

           if ($smjer_sortiranja == "ASC") {
               $upit .= " ASC";
           } else {
               $upit .= " DESC";
           }

           $upit .= " LIMIT ? OFFSET ?";

           $statement = $this->veza->prepare($upit);
           $statement->bind_param("sii", $vrijednost_za_pretrazivanje, $limit, $offset);

       } elseif ($atribut_za_sortiranje != null) {
           $upit .= " GROUP BY k.id_korisnik, k.korisnicko_ime, k.ime_prezime";

           switch ($atribut_za_sortiranje) {
               case "id_korisnik":
                   $upit .= " ORDER BY k.id_korisnik";
                   break;
               case "korisnicko_ime":
                   $upit .= " ORDER BY k.korisnicko_ime";
                   break;
               case "ime_prezime":
                   $upit .= " ORDER BY k.ime_prezime";
                   break;
               case "broj_proizvoda":
                   $upit .= " ORDER BY COUNT(kip.id_proizvoda)";
                   break;
           }

           if ($smjer_sortiranja == "ASC") {
               $upit .= " ASC";
           } else {
               $upit .= " DESC";
           }

           $upit .= " LIMIT ? OFFSET ?";

           $statement = $this->veza->prepare($upit);
           $statement->bind_param("ii", $limit, $offset);

       } elseif ($atribut_za_pretrazivanje != null) {
           switch ($atribut_za_pretrazivanje) {
               case "id_korisnik":
                   $upit .= " AND k.id_korisnik LIKE ?";
                   break;
               case "korisnicko_ime":
                   $upit .= " AND k.korisnicko_ime LIKE ?";
                   break;
               case "ime_prezime":
                   $upit .= " AND k.ime_prezime LIKE ?";
                   break;
               case "broj_proizvoda":
                   $upit .= " AND COUNT(kip.id_proizvoda) LIKE ?";
                   break;
           }

           $upit .= " GROUP BY k.id_korisnik, k.korisnicko_ime, k.ime_prezime";
           $upit .= " LIMIT ? OFFSET ?";

           $statement = $this->veza->prepare($upit);
           $statement->bind_param("sii", $vrijednost_za_pretrazivanje, $limit, $offset);

       } else {
           $upit .= " GROUP BY k.id_korisnik, k.korisnicko_ime, k.ime_prezime";
           $upit .= " LIMIT ? OFFSET ?";

           $statement = $this->veza->prepare($upit);
           $statement->bind_param("ii", $limit, $offset);
       }

       $statement->execute();
       $rezultat = $statement->get_result()->fetch_all(MYSQLI_ASSOC);
       $statement->close();
       return $rezultat;
   }

    
    function dohvati_sve_kampanje($limit, $offset, $atribut_za_sortiranje1 = null, $smjer_sortiranja1 = null, $atribut_za_pretrazivanje1 = null, $vrijednost_za_pretrazivanje1 = null, $od = null, $do = null) {
        $upit = "SELECT k.*, COUNT(pk.id_proizvoda) AS broj_proizvoda FROM kampanja k LEFT JOIN prozvod_u_kampanji pk ON k.id_kampanje = pk.id_kampanje ";
        $vrijednost_za_pretrazivanje1 = "%$vrijednost_za_pretrazivanje1%";
        $veza = $this->veza;
       
        if ($atribut_za_pretrazivanje1 != null && $atribut_za_sortiranje1 != null && ($od != null && $do != null)) {
            $upit.=" WHERE ";
            switch ($atribut_za_pretrazivanje1) {
                case "id_kampanje":
                    $upit .= " id_kampanje LIKE ?";
                    break;
                case "naziv":
                    $upit .= " naziv LIKE ?";
                    break;
                case "opis":
                    $upit .= " opis LIKE ?";
                    break;
                case "datum_pocetka":
                    $upit .= " datum_pocetka LIKE ?";
                    break;
                case "datum_zavrsetka":
                    $upit .= " datum_zavrsetka LIKE ?";
                    break;
                case "broj_proizvoda":
                    $upit .= " broj_proizvoda LIKE ?";
                    break;
            }
            $upit .= " AND datum_pocetka>=? AND datum_zavrsetka<=?";
            $upit.= " GROUP BY k.id_kampanje, k.naziv, k.opis, k.datum_pocetka, k.datum_zavrsetka ";
            switch ($atribut_za_sortiranje1) {
                case "id_kampanje":
                    $upit .= " ORDER BY id_kampanje";
                    break;
                case "naziv":
                    $upit .= " ORDER BY naziv";
                    break;
                case "opis":
                    $upit .= " ORDER BY opis";
                    break;
                case "datum_pocetka":
                    $upit .= " ORDER BY datum_pocetka";
                    break;
                case "datum_zavrsetka":
                    $upit .= " ORDER BY datum_zavrsetka";
                    break;
                case "broj_proizvoda":
                    $upit .= " ORDER BY broj_proizvoda";
                    break;
            }
            if ($smjer_sortiranja1 == "ASC") {
                $upit .= " ASC";
            } else {
                $upit .= " DESC";
            }
            $upit .= " LIMIT ? OFFSET ?";

            $statement = $veza->prepare($upit);
            $statement->bind_param("sssii", $vrijednost_za_pretrazivanje1, $od, $do, $limit, $offset);
        } else if ($atribut_za_sortiranje1 != null && ($od != null && $do != null)) {
            $upit .= " WHERE datum_pocetka>=? AND datum_zavrsetka<=?";
            $upit.= " GROUP BY k.id_kampanje, k.naziv, k.opis, k.datum_pocetka, k.datum_zavrsetka";
            
            switch ($atribut_za_sortiranje1) {
                case "id_kampanje":
                    $upit .= " ORDER BY id_kampanje";
                    break;
                case "naziv":
                    $upit .= " ORDER BY naziv";
                    break;
                case "opis":
                    $upit .= " ORDER BY opis";
                    break;
                case "datum_pocetka":
                    $upit .= " ORDER BY datum_pocetka";
                    break;
                case "datum_zavrsetka":
                    $upit .= " ORDER BY datum_zavrsetka";
                    break;
                case "broj_proizvoda":
                    $upit .= " ORDER BY broj_proizvoda";
                    break;               
            }
            if ($smjer_sortiranja1 == "ASC") {
                $upit .= " ASC";
            } else {
                $upit .= " DESC";
            }
            $upit .= " LIMIT ? OFFSET ?";
            $statement = $veza->prepare($upit);
            $statement->bind_param("ssii", $od, $do, $limit, $offset);
        } else if ($atribut_za_pretrazivanje1 != null && ($od != null && $do != null)) {
            $upit.=" WHERE";
            switch ($atribut_za_pretrazivanje1) {
                case "id_kampanje":
                    $upit .= " id_kampanje LIKE ?";
                    break;
                case "naziv":
                    $upit .= " naziv LIKE ?";
                    break;
                case "opis":
                    $upit .= " opis LIKE ?";
                    break;
                case "datum_pocetka":
                    $upit .= " datum_pocetka LIKE ?";
                    break;
                case "datum_zavrsetka":
                    $upit .= " datum_zavrsetka LIKE ?";
                    break;
                case "broj_proizvoda":
                    $upit .= " broj_proizvoda LIKE ?";
                    break;                
            }
            $upit .= " AND datum_pocetka>=? AND datum_zavrsetka<=?";
            $upit.= " GROUP BY k.id_kampanje, k.naziv, k.opis, k.datum_pocetka, k.datum_zavrsetka";
            $upit .= " LIMIT ? OFFSET ?";

            $statement = $veza->prepare($upit);
            $statement->bind_param("sssii", $vrijednost_za_pretrazivanje1, $od, $do, $limit, $offset);
        } else if ($atribut_za_pretrazivanje1 != null && $atribut_za_sortiranje1 != null) {
            $upit.=" WHERE";
            switch ($atribut_za_pretrazivanje1) {
                case "id_kampanje":
                    $upit .= " id_kampanje LIKE ?";
                    break;
                case "naziv":
                    $upit .= " naziv LIKE ?";
                    break;
                case "opis":
                    $upit .= " opis LIKE ?";
                    break;
                case "datum_pocetka":
                    $upit .= " datum_pocetka LIKE ?";
                    break;
                case "datum_zavrsetka":
                    $upit .= " datum_zavrsetka LIKE ?";
                    break;
                case "broj_proizvoda":
                    $upit .= " broj_proizvoda LIKE ?";
                    break;               
            }
            
            $upit.= " GROUP BY k.id_kampanje, k.naziv, k.opis, k.datum_pocetka, k.datum_zavrsetka";
            switch ($atribut_za_sortiranje1) {
                case "id_kampanje":
                    $upit .= " ORDER BY id_kampanje";
                    break;
                case "naziv":
                    $upit .= " ORDER BY naziv";
                    break;
                case "opis":
                    $upit .= " ORDER BY opis";
                    break;
                case "datum_pocetka":
                    $upit .= " ORDER BY datum_pocetka";
                    break;
                case "datum_zavrsetka":
                    $upit .= " ORDER BY datum_zavrsetka";
                    break;
                case "broj_proizvoda":
                    $upit .= " ORDER BY broj_proizvoda";
                    break;               
            }
            if ($smjer_sortiranja1 == "ASC") {
                $upit .= " ASC";
            } else {
                $upit .= " DESC";
            }
            $upit .= " LIMIT ? OFFSET ?";

            $statement = $veza->prepare($upit);
            $statement->bind_param("sii", $vrijednost_za_pretrazivanje1, $limit, $offset);
        } else if ($atribut_za_sortiranje1 != null) {

            switch ($atribut_za_sortiranje1) {
                case "id_kampanje":
                    $upit .= " ORDER BY id_kampanje";
                    break;
                case "naziv":
                    $upit .= " ORDER BY naziv";
                    break;
                case "opis":
                    $upit .= " ORDER BY opis";
                    break;
                case "datum_pocetka":
                    $upit .= " ORDER BY datum_pocetka";
                    break;
                case "datum_zavrsetka":
                    $upit .= " ORDER BY datum_zavrsetka";
                    break;
                case "broj_proizvoda":
                    $upit .= " ORDER BY broj_proizvoda";
                    break;               
            }
            if ($smjer_sortiranja1 == "ASC") {
                $upit .= " ASC";
            } else {
                $upit .= " DESC";
            }
            $upit .= " LIMIT ? OFFSET ?";
            $statement = $veza->prepare($upit);
            $statement->bind_param("ii", $limit, $offset);
        } else if ($atribut_za_pretrazivanje1 != null) {
            $upit.= " WHERE";
            switch ($atribut_za_pretrazivanje1) {
                case "id_kampanje":
                    $upit .= " id_kampanje LIKE ?";
                    break;
                case "naziv":
                    $upit .= " naziv LIKE ?";
                    break;
                case "opis":
                    $upit .= " opis LIKE ?";
                    break;
                case "datum_pocetka":
                    $upit .= " datum_pocetka LIKE ?";
                    break;
                case "datum_zavrsetka":
                    $upit .= " datum_zavrsetka LIKE ?";
                    break;
                case "broj_proizvoda":
                    $upit .= " broj_proizvoda LIKE ?";
                    break;                
            }
            
            $upit.= " GROUP BY k.id_kampanje, k.naziv, k.opis, k.datum_pocetka, k.datum_zavrsetka";
            $upit .= " LIMIT ? OFFSET ?";

            $statement = $veza->prepare($upit);
            $statement->bind_param("sii", $vrijednost_za_pretrazivanje1, $limit, $offset);
        } else if ($od != null && $do != null) {
            $upit .= " WHERE datum_pocetka>=? AND datum_zavrsetka<=?";
            $upit.= " GROUP BY k.id_kampanje, k.naziv, k.opis, k.datum_pocetka, k.datum_zavrsetka";
            $upit .= " LIMIT ? OFFSET ?";

            $statement = $veza->prepare($upit);
            $statement->bind_param("ssii", $od, $do, $limit, $offset);
        } else {
            $upit .= " LIMIT ? OFFSET ?";
            $statement = $veza->prepare($upit);
            $statement->bind_param("ii", $limit, $offset);
        }
        $statement->execute();
        return $statement->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    function korisnik_dohvati_za_blokiranje($limit, $offset, $atribut_za_sortiranje = null, $smjer_sortiranja = null, $atribut_za_pretrazivanje = null, $vrijednost_za_pretrazivanje = null) {
        $upit = "SELECT id_korisnik, korisnicko_ime, ime_prezime, email, broj_unosa, blokiran, naziv FROM korisnik k JOIN uloga u ON u.id_uloga = k.id_uloga WHERE 1";
        $vrijednost_za_pretrazivanje = "%$vrijednost_za_pretrazivanje%";
        $veza = $this->veza;
        if ($atribut_za_pretrazivanje != null && $atribut_za_sortiranje != null) {
            switch ($atribut_za_pretrazivanje) {
                case "korisnicko_ime":
                    $upit .= " AND korisnicko_ime LIKE ?";
                    break;
                case "ime_prezime":
                    $upit .= " AND ime_prezime LIKE ?";
                    break;
                case "email":
                    $upit .= " AND email LIKE ?";
                    break;
                case "broj_unosa":
                    $upit .= " AND broj_unosa LIKE ?";
                    break;
                case "blokiran":
                    $upit .= " AND blokiran LIKE ?";
                    break;
                case "naziv":
                    $upit .= " AND naziv LIKE ?";
                    break;
            }
            switch ($atribut_za_sortiranje) {
                case "korisnicko_ime":
                    $upit .= " ORDER BY korisnicko_ime";
                    break;
                case "ime_prezime":
                    $upit .= " ORDER BY ime_prezime";
                    break;
                case "email":
                    $upit .= " ORDER BY email";
                    break;
                case "broj_unosa":
                    $upit .= " ORDER BY broj_unosa";
                    break;
                case "blokiran":
                    $upit .= " ORDER BY blokiran";
                    break;
                case "naziv":
                    $upit .= " ORDER BY naziv";
                    break;
            }
            if ($smjer_sortiranja == "ASC") {
                $upit .= " ASC";
            } else {
                $upit .= " DESC";
            }
            $upit .= " LIMIT ? OFFSET ?";
            $this->dnevnik_rad_s_bazom($veza, "SELECT id_korisnik, korisnicko_ime, ime_prezime, email, broj_unosa, blokiran, naziv FROM korisnik k JOIN uloga u ON u.id_uloga = k.id_uloga WHERE 1 AND $atribut_za_pretrazivanje LIKE '$vrijednost_za_pretrazivanje' ORDER BY $atribut_za_sortiranje LIMIT $limit OFFSET $offset");
            $statement = $veza->prepare($upit);
            $statement->bind_param("sii", $vrijednost_za_pretrazivanje, $limit, $offset);
        } elseif ($atribut_za_sortiranje != null) {
            switch ($atribut_za_sortiranje) {
                case "korisnicko_ime":
                    $upit .= " ORDER BY korisnicko_ime";
                    break;
                case "ime_prezime":
                    $upit .= " ORDER BY ime_prezime";
                    break;
                case "email":
                    $upit .= " ORDER BY email";
                    break;
                case "broj_unosa":
                    $upit .= " ORDER BY broj_unosa";
                    break;
                case "blokiran":
                    $upit .= " ORDER BY blokiran";
                    break;
                case "naziv":
                    $upit .= " ORDER BY naziv";
                    break;
            }
            if ($smjer_sortiranja == "ASC") {
                $upit .= " ASC";
            } else {
                $upit .= " DESC";
            }
            $upit .= " LIMIT ? OFFSET ?";
            $this->dnevnik_rad_s_bazom($veza, "SELECT id_korisnik, korisnicko_ime, ime_prezime, email, broj_unosa, blokiran, naziv FROM korisnik k JOIN uloga u ON u.id_uloga = k.id_uloga WHERE 1 ORDER BY $atribut_za_sortiranje LIMIT $limit OFFSET $offset");
            $statement = $veza->prepare($upit);
            $statement->bind_param("ii", $limit, $offset);
        } else if ($atribut_za_pretrazivanje != null) {
            switch ($atribut_za_pretrazivanje) {
                case "korisnicko_ime":
                    $upit .= " AND korisnicko_ime LIKE ?";
                    break;
                case "ime_prezime":
                    $upit .= " AND ime_prezime LIKE ?";
                    break;
                case "email":
                    $upit .= " AND email LIKE ?";
                    break;
                case "broj_unosa":
                    $upit .= " AND broj_unosa LIKE ?";
                    break;
                case "blokiran":
                    $upit .= " AND blokiran LIKE ?";
                    break;
                case "naziv":
                    $upit .= " AND naziv LIKE ?";
                    break;
            }
            $upit .= " LIMIT ? OFFSET ?";
            $this->dnevnik_rad_s_bazom($veza, "SELECT id_korisnik, korisnicko_ime, ime_prezime, email, broj_unosa, blokiran, naziv FROM korisnik k JOIN uloga u ON u.id_uloga = k.id_uloga WHERE 1 AND $atribut_za_pretrazivanje LIKE '$vrijednost_za_pretrazivanje' LIMIT $limit OFFSET $offset");
            $statement = $veza->prepare($upit);
            $statement->bind_param("sii", $vrijednost_za_pretrazivanje, $limit, $offset);
        } else {
            $upit .= " LIMIT ? OFFSET ?";
            $this->dnevnik_rad_s_bazom($veza, "SELECT id_korisnik, korisnicko_ime, ime_prezime, email, broj_unosa, blokiran, naziv FROM korisnik k JOIN uloga u ON u.id_uloga = k.id_uloga WHERE 1 LIMIT $limit OFFSET $offset");
            $statement = $veza->prepare($upit);
            $statement->bind_param("ii", $limit, $offset);
        }
        $statement->execute();
        return $statement->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    function korisnik_azuriraj_blokiran_id($id_korisnik, $int) {
            $veza = $this->veza;
            $this->dnevnik_rad_s_bazom($veza, "UPDATE korisnik SET blokiran = $int WHERE id_korisnik = $id_korisnik");
            $statement = $veza->prepare("UPDATE korisnik SET blokiran = ? WHERE id_korisnik = ?");
            $statement->bind_param("ii", $int, $id_korisnik);
            $statement->execute();
        }

    function korisnik_azuriraj_uvjeti_koristenja($korisnicko_ime, $datum) {
            $veza = $this->veza;
            $this->dnevnik_rad_s_bazom($veza, "UPDATE korisnik SET uvjeti_koristenja='$datum' WHERE korisnicko_ime='$korisnicko_ime'");
            $statement = $veza->prepare("UPDATE korisnik SET uvjeti_koristenja=? WHERE korisnicko_ime=?");
            $statement->bind_param("ss", $datum, $korisnicko_ime);
            $statement->execute();
        }
        
    function korisnik_azuriraj_uvjeti_koristenja_svi($datum) {
            $veza = $this->veza;
            $this->dnevnik_rad_s_bazom($veza, "UPDATE korisnik SET uvjeti_koristenja='$datum'");
            $statement = $veza->prepare("UPDATE korisnik SET uvjeti_koristenja=?");
            $statement->bind_param("s", $datum);
            $statement->execute();
        }


    function korisnik_spremi($atributi) {
        $korisnicko_ime = $atributi['korisnicko_ime'];
        $ime_prezime = $atributi['ime_prezime'];
        $email = $atributi['email'];
        $lozinka = $atributi['lozinka'];
        $sha256 = sha256($lozinka);

        $lozinka_sha256 = $sha256['sha256'];
        $sol = $sha256['sol'];
        $datum_vrijeme_registracije = $atributi['datum_vrijeme_registracije'];
        $uvjeti_koristenja = $atributi['uvjeti_koristenja'];
        $aktivacijski_kod = $atributi['aktivacijski_kod'];
        $id_uloga = 3;
        $id_jezika= 1;

        $veza = $this->veza;

        $this->dnevnik_rad_s_bazom($veza, "INSERT INTO korisnik (korisnicko_ime,ime_prezime,email,lozinka,lozinka_sha256,sol,datum_vrijeme_registracije,uvjeti_koristenja,aktivacijski_kod,id_uloga) VALUES ('$korisnicko_ime', '$ime_prezime', '$email', '$lozinka', '$lozinka_sha256', '$sol', '$datum_vrijeme_registracije', '$uvjeti_koristenja', '$aktivacijski_kod', $id_uloga,$id_jezika)");

        $statement = $veza->prepare("INSERT INTO korisnik (korisnicko_ime,ime_prezime,email,lozinka,lozinka_sha256,sol,datum_vrijeme_registracije,uvjeti_koristenja,aktivacijski_kod,id_uloga, id_jezika) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
        $statement->bind_param("sssssssssii", $korisnicko_ime, $ime_prezime, $email, $lozinka, $lozinka_sha256, $sol, $datum_vrijeme_registracije, $uvjeti_koristenja, $aktivacijski_kod, $id_uloga,$id_jezika);

        $statement->execute();
        
        
    }

    function korisnik_azuriraj_aktiviran($korisnicko_ime) {
        $veza = $this->veza;
        $this->dnevnik_rad_s_bazom($veza, "UPDATE korisnik SET aktiviran=1 WHERE korisnicko_ime='$korisnicko_ime'");
        $statement = $veza->prepare("UPDATE korisnik SET aktiviran=1 WHERE korisnicko_ime=?");
        $statement->bind_param("s", $korisnicko_ime);
        $statement->execute();
    }


    function korisnik_dohvati_aktivacijski_kod($korisnicko_ime, $aktivacijski_kod) {
        $veza = $this->veza;
        $this->dnevnik_rad_s_bazom($veza, "SELECT datum_vrijeme_registracije,aktiviran FROM korisnik WHERE korisnicko_ime='$korisnicko_ime' AND aktivacijski_kod='$aktivacijski_kod'");
        $statement = $veza->prepare("SELECT datum_vrijeme_registracije,aktiviran FROM korisnik WHERE korisnicko_ime=? AND aktivacijski_kod=?");
        $statement->bind_param("ss", $korisnicko_ime, $aktivacijski_kod);
        $statement->execute();
        return $statement->get_result()->fetch_all(MYSQLI_ASSOC);
    }


    function korisnik_obrisi($korisnicko_ime) {
        $veza = $this->veza;
        $this->dnevnik_rad_s_bazom($veza, "DELETE FROM korisnik WHERE korisnicko_ime='$korisnicko_ime'");
        $statement = $veza->prepare("DELETE FROM korisnik WHERE korisnicko_ime=?");
        $statement->bind_param("s", $korisnicko_ime);
        $statement->execute();
    }

    function korisnik_dohvati_zasticeno() {
        $veza = $this->veza;
        $this->dnevnik_rad_s_bazom($veza, "SELECT * FROM korisnik");
        $statement = $veza->prepare("SELECT * FROM korisnik");
        $statement->execute();
        return $statement->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    function proizvod_obrisi($id_proizvoda) {
        $veza = $this->veza;
        $this->dnevnik_rad_s_bazom($veza, "DELETE FROM proizvod WHERE id_proizvoda=$id_proizvoda");
        $statement = $veza->prepare("DELETE FROM proizvod WHERE id_proizvoda=?");
        $statement->bind_param("i", $id_proizvoda);
        $statement->execute();
    }
    
    
    function dohvati_proizvode($limit, $offset, $atribut_za_sortiranje = null, $smjer_sortiranja = null, $atribut_za_pretrazivanje = null, $vrijednost_za_pretrazivanje = null) {
        $upit = "SELECT p.id_proizvoda, p.naziv, p.opis, p.kolicina, p.cijena, s.naziv AS status, k.ime_prezime AS moderator FROM proizvod p JOIN status_proizvoda s ON p.idstatus_proizvoda = s.idstatus_proizvoda JOIN korisnik k ON p.moderator_id = k.id_korisnik";
        $vrijednost_za_pretrazivanje = "%$vrijednost_za_pretrazivanje%";
        $veza = $this->veza;

        if ($atribut_za_pretrazivanje != null && $atribut_za_sortiranje != null) {
            switch ($atribut_za_pretrazivanje) {
                case "id_proizvoda":
                    $upit .= " AND id_proizvoda LIKE ?";
                    break;
                case "naziv":
                    $upit .= " AND p.naziv LIKE ?";
                    break;
                case "status":
                    $upit .= " AND s.naziv LIKE ?";
                    break;    
                case "moderator":
                    $upit .= " AND k.ime_prezime LIKE ?";
                    break;               
            }

            switch ($atribut_za_sortiranje) {
                case "id_proizvoda":
                    $upit .= " ORDER BY p.id_proizvoda";
                    break;
                case "naziv":
                    $upit .= " ORDER BY p.naziv";
                    break;
                case "status":
                    $upit .= " ORDER BY s.naziv";
                    break;
                case "moderator":
                    $upit .= " ORDER BY moderator";
                    break;
            }

            if ($smjer_sortiranja == "ASC") {
                $upit .= " ASC";
            } else {
                $upit .= " DESC";
            }

            $upit .= " LIMIT ? OFFSET ?";
            $statement = $veza->prepare($upit);
            $statement->bind_param("ssi", $vrijednost_za_pretrazivanje, $limit, $offset);
        } elseif ($atribut_za_sortiranje != null) {           
            switch ($atribut_za_sortiranje) {
                case "id_proizvoda":
                    $upit .= " ORDER BY p.id_proizvoda";
                    break;
                case "naziv":
                    $upit .= " ORDER BY p.naziv";
                    break;
                case "status":
                    $upit .= " ORDER BY s.naziv";
                    break;
                case "moderator":
                    $upit .= " ORDER BY moderator";
                    break;
            }

            if ($smjer_sortiranja == "ASC") {
                $upit .= " ASC";
            } else {
                $upit .= " DESC";
            }

            $upit .= " LIMIT ? OFFSET ?";
            $statement = $veza->prepare($upit);
            $statement->bind_param("ii", $limit, $offset);
        } else if ($atribut_za_pretrazivanje != null) {
            switch ($atribut_za_pretrazivanje) {
                case "id_proizvoda":
                    $upit .= " AND id_proizvoda LIKE ?";
                    break;
                case "naziv":
                    $upit .= " AND p.naziv LIKE ?";
                    break;
                case "status":
                    $upit .= " AND s.naziv LIKE ?";
                    break;
                case "moderator":
                    $upit .= " AND k.ime_prezime LIKE ?";
                    break;               
            }

            $upit .= " LIMIT ? OFFSET ?";
            $statement = $veza->prepare($upit);
            $statement->bind_param("ssi", $vrijednost_za_pretrazivanje, $limit, $offset);
        } else {
            $upit .= " LIMIT ? OFFSET ?";
            $statement = $veza->prepare($upit);
            $statement->bind_param("ii", $limit, $offset);
        }

        $statement->execute();
        return $statement->get_result()->fetch_all(MYSQLI_ASSOC);
    }


}
