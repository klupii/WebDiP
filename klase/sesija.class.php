<?php

/*
 * The MIT License
 *
 * Copyright 2014 Matija Novak <matija.novak@foi.hr>.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 * 
 * Klasa za upravljanje sa sesijama
 *
 * @author Matija Novak <matija.novak@foi.hr>
 */

class Sesija {

    const KORISNICKO_IME = "korisnicko_ime";
    const ID_KORISNIK = "id_korisnik";
    const ID_ULOGA = "id_uloga";
    const ULOGA = "uloga"; // konstanta uloga
    const KOSARICA = "kosarica";
    const SESSION_NAME = "prijava_sesija";

    static function kreirajSesiju() {
         if (session_id() == "") {
            session_name(self::SESSION_NAME);
            @session_start();
        }
    }
    
    static function postojiSesija() {
        return !(session_id() == "");
    }

    static function kreiranKorisnik() {
        return (isset($_SESSION['korisnicko_ime']) && isset($_SESSION['uloga']) && isset($_SESSION['id_korisnik']) & isset($_SESSION['id_uloga']));
    }


    static function kreirajKorisnika($id_korisnik, $id_uloga, $korisnicko_ime) {
            self::kreirajSesiju();
            $_SESSION[self::ID_KORISNIK] = $id_korisnik;
            $_SESSION[self::ID_ULOGA] = $id_uloga;
            $_SESSION[self::KORISNICKO_IME] = $korisnicko_ime;
            $uloga = "";
            switch ($id_uloga) {
                case 1:
                    $uloga = "Administrator";
                    break;
                case 2:
                    $uloga = "Moderator";
                    break;
                case 3:
                    $uloga = "Registrirani korisnik";
                    break;
            }
            $_SESSION[self::ULOGA] = $uloga;
        }


    static function kreirajKosaricu($kosarica) {
        self::kreirajSesiju();
        $_SESSION[self::KOSARICA] = $kosarica;
    }

    static function dajKorisnika() {
        self::kreirajSesiju();
        if (isset($_SESSION[self::KORISNIK])) {
            $korisnik[self::KORISNIK] = $_SESSION[self::KORISNIK];
            $korisnik[self::ULOGA] = $_SESSION[self::ULOGA];
            $korisnik[self::ID_KORISNIK] = $id_korisnik;
        } else {
            return null;
        }
        return $korisnik; // vraca asocijativni niz korisnik
    }

    static function dajKosaricu() {
        self::kreirajSesiju();
        if (isset($_SESSION[self::KOSARICA])) {
            $kosarica = $_SESSION[self::KOSARICA];
        } else {
            return null;
        }
        return $kosarica;
    }

    /**
     * Odjavljuje korisnika tj. briše sesiju
     */
    static function obrisiSesiju() {
        if (session_id() != "") {
            session_unset();
            session_destroy();
        }
    }

}