            <div class="glavno">
                <div class="glavno-sadrzaj">
                    <h1>Kampanje</h1>
                    <div class="pretrazivanje-tablice">
                        <label for="pretrazivanje">Pretraži:</label>
                        <input type="text" name="pretrazivanje" id="pretrazivanje">
                    </div>
                    <select id="atribut_za_pretrazivanje1">
                        <option id="naziv" name="naziv" value="naziv">Naziv kampanje</option>
                        <option id="opis" name="opis" value="opis">Opis kampanje</option>
                        <option id="datum_pocetka" name="datum_pocetka" value="datum_pocetka">Datum početka</option>
                        <option id="datum_zavrsetka" name="datum_zavrsetka" value="datum_zavrsetka">Datum završetka</option>
                        <option id="broj_proizvoda" name="broj_proizvoda" value="broj_proizvoda">Broj proizvoda</option>                                           
                    </select>
                    <table class="tablica1">
                        <thead>
                            <tr>
                                <th id="naziv" class="tablica1-hover">Naziv kampanje</th>
                                <th id="opis" class="tablica1-hover">Opis kampanje</th>
                                <th id="datum_pocetka" class="tablica1-hover">Datum početka kampanje</th>
                                <th id="datum_zavrsetka" class="tablica1-hover">Datum završetka kampanje</th>
                                <th id="broj_proizvoda" class="tablica1-hover">Broj proizvoda u kampanji</th>                              
                            </tr>    
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    <div class="tablica-dno">
                        <div><button class="obican" id="prva1">Prva</button></div>
                        <div><button class="obican" id="prijasnja1">Prijašnja</button></div>
                        <div><button class="obican" id="sljedeca1">Sljedeća</button></div>
                        <div><button class="obican" id="posljednja1">Posljednja</button></div>
                    </div>
                    
                    <div class="datum">
                        <input id="od" name="od" type="date" value="">
                        <input id="do" name="do" type="date" value="">
                        <div><button class="obican" id="filtriraj">Filtriraj</button>
                        </div>
                    </div>
                    <input id="atribut_za_sortiranje1" name="atribut_za_sortiranje1" value="" hidden>
                    <input id="smjer_sortiranja1" name="smjer_sortiranja1" value="ASC" hidden>
                    <input id="broj_stranice1" name="broj_stranice1" value="0" hidden>
                    
                    <h2>Korisnici koji imaju kreiran profil</h2>
                    <div class="pretrazivanje-tablice">
                        <label for="pretrazi">Pretraži:</label>
                        <input type="text" name="pretrazi" id="pretrazi">
                    </div>
                    <select id="atribut_za_pretrazivanje">
                        <option id="korisnicko_ime" name="korisnicko_ime" value="korisnicko_ime">Korisničko ime</option>
                        <option id="ime_prezime" name="ime_prezime" value="ime_prezime">Ime i prezime</option>
                    </select>
                    <table class="tablica">
                        <thead>
                            <tr>
                                <th id="korisnicko_ime" class="tablica-hover">Korisničko ime</th>
                                <th id="ime_prezime" class="tablica-hover">Ime i prezime</th>
                            </tr>    
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    <div class="tablica-dno">
                        <div><button class="obican" id="prva">Prva</button></div>
                        <div><button class="obican" id="prijasnja">Prijašnja</button></div>
                        <div><button class="obican" id="sljedeca">Sljedeća</button></div>
                        <div><button class="obican" id="posljednja">Posljednja</button></div>
                    </div>
                </div>
            </div>

            <input id="atribut_za_sortiranje" name="atribut_za_sortiranje" value="" hidden>
            <input id="smjer_sortiranja" name="smjer_sortiranja" value="ASC" hidden>
            <input id="broj_stranice" name="broj_stranice" value="0" hidden>
