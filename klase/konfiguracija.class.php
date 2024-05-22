<?php
error_reporting(E_ALL ^ E_NOTICE);

class Konfiguracija {
    private $naziv_datoteke = "";
    private $trajanje_kolacica = 2; //dana
    private $broj_stranica_stranicenja = 11;
    private $broj_neuspjesnih_prijava = 5;
    private $trajanje_sesije = 1; //sati
    private $pomak = 1;
    private $istek_verifikacije = 10;
    private $u_izradi = 0;
    private $datoteka = null;

    public function __construct($direktorij) {
        $this->naziv_datoteke = "$direktorij/configs/konfiguracija.conf";
        if (($this->datoteka = fopen($this->naziv_datoteke, 'r')) == false) {
            if (fopen($this->naziv_datoteke, 'w') == false) {
                echo "Konfiguracijska datoteka nije uspješno stvorena!";
                exit();
            } else {
                $this->spremiKonfiguraciju();
            }
        } else {
            fclose($this->datoteka);
            $this->procitajKonfiguraciju();
        }
    }

    public function postaviKonfiguraciju($trajanje_kolacica = null, $broj_stranica_stranicenja = null, $trajanje_sesije = null, $broj_neuspjesnih_prijava = null, $pomak = null, $istek_verifikacije = null) {
        if ($trajanje_kolacica !== null)
            $this->postaviTrajanjeKolacica($trajanje_kolacica);
        if ($broj_stranica_stranicenja !== null)
            $this->postaviBrojStranicaStranicenja($broj_stranica_stranicenja);
        if ($trajanje_sesije !== null)
            $this->postaviTrajanjeSesije($trajanje_sesije);
        if ($broj_neuspjesnih_prijava !== null)
            $this->postaviBrojNeuspjesnihPrijava($broj_neuspjesnih_prijava);
        if ($pomak !== null)
            $this->postaviPomak($pomak);
        if ($istek_verifikacije !== null)
            $this->postaviIstekVerifikacije($istek_verifikacije);
        if ($u_izradi !== null)
            $this->postaviUIzradi($u_izradi);
     }

        
    public function procitajKonfiguraciju() {
        if (($this->datoteka = fopen($this->naziv_datoteke, 'r')) == false) {
            echo "Greška u otvaranju datoteke";
            exit();
        }

        $vrijednosti_konfiguracije = array();
        while (($postavka = fgets($this->datoteka)) !== false) {
            $postavka = str_replace(["\r", "\n", " "], "", $postavka);
            $razdvojena_postavka = explode("=", $postavka);
            $naziv_postavke = $razdvojena_postavka[0];
            $vrijednost_postavke = $razdvojena_postavka[1];
            $vrijednosti_konfiguracije[$naziv_postavke] = $vrijednost_postavke;
        }
        fclose($this->datoteka);
        $this->postaviKonfiguraciju($vrijednosti_konfiguracije['trajanje_kolacica'], $vrijednosti_konfiguracije['broj_stranica_stranicenja'],
                $vrijednosti_konfiguracije['trajanje_sesije'], $vrijednosti_konfiguracije['broj_neuspjesnih_prijava'], $vrijednosti_konfiguracije['pomak'], $vrijednosti_konfiguracije['istek_verifikacije'],$vrijednosti_konfiguracije['u_izradi']);
        return $vrijednosti_konfiguracije;
    }

    public function spremiKonfiguraciju() {
        if (($this->datoteka = fopen($this->naziv_datoteke, 'w')) === false) {
            echo "Greška u otvaranju datoteke";
            exit();
        }
        $postavke_za_spremanje = "trajanje_kolacica=" . $this->vratiTrajanjeKolacica() .
                        "\nbroj_stranica_stranicenja=" . $this->vratiBrojStranicaStranicenja() .
                        "\ntrajanje_sesije=" . $this->vratiTrajanjeSesije() .
                        "\nbroj_neuspjesnih_prijava=" . $this->vratiBrojNeuspjesnihPrijava() .
                        "\npomak=" . $this->vratiPomak() .
                        "\nistek_verifikacije=" . $this->vratiIstekVerifikacije() .
                        "\nu_izradi=" . $this->vratiUIzradi() . "\n";
                fwrite($this->datoteka, $postavke_za_spremanje);
                fclose($this->datoteka);
            }

    public function postaviTrajanjeKolacica($trajanje_kolacica) {
        $this->trajanje_kolacica = $trajanje_kolacica;
    }

    public function postaviBrojStranicaStranicenja($broj_stranica_stranicenja) {
        $this->broj_stranica_stranicenja = $broj_stranica_stranicenja;
    }

    public function postaviTrajanjeSesije($trajanje_sesije) {
        $this->trajanje_sesije = $trajanje_sesije;
    }

    public function postaviBrojNeuspjesnihPrijava($broj_neuspjesnih_prijava) {
        $this->broj_neuspjesnih_prijava = $broj_neuspjesnih_prijava;
    }

    public function postaviPomak($pomak) {
        $this->pomak = $pomak;
    }

    public function postaviIstekVerifikacije($istek_verifikacije) {
        $this->istek_verifikacije = $istek_verifikacije;
    }
    public function postaviUIzradi($u_izradi) {
        $this->u_izradi = $u_izradi;
    }

    public function vratiTrajanjeKolacica() {
        return $this->trajanje_kolacica;
    }

    public function vratiBrojStranicaStranicenja() {
        return $this->broj_stranica_stranicenja;
    }

    public function vratiTrajanjeSesije() {
        return $this->trajanje_sesije;
    }

    public function vratiBrojNeuspjesnihPrijava() {
        return $this->broj_neuspjesnih_prijava;
    }

    public function vratiPomak() {
        return $this->pomak;
    }

    public function vratiIstekVerifikacije() {
        return $this->istek_verifikacije;
    }

    public function vratiUIzradi() {
        return $this->u_izradi;
    }

    public function virtualnoVrijeme() {
        $this->procitajKonfiguraciju();
        $pomak = $this->vratiPomak();
        return date('Y-m-d H:i:s', time() + ($pomak * 60 * 60));
    }

}
