<div class="glavno">
    <div class="glavno-sadrzaj">
        <h2>Blokirani korisnici</h2>
                <div class="pretrazivanje-tablice">
                    <label for="pretrazi">Pretraži:</label>
                    <input type="text" name="pretrazi" id="pretrazi">
                </div>
                <select id="atribut_za_pretrazivanje">
                    <option id="korisnicko_ime" name="korisnicko_ime" value="korisnicko_ime">Korisničko ime</option>
                    <option id="ime_prezime" name="ime_prezime" value="ime_prezime">Ime i prezime</option>
                    <option id="email" name="email" value="email">E-mail</option>
                    <option id="broj_unosa" name="broj_unosa" value="broj_unosa">Broj pokušaja prijava</option>
                    <option id="naziv" name="naziv" value="naziv">Uloga</option>
                </select>
        <table class="tablica">
            <thead>
                <tr>
                    <th id="korisnicko_ime" class="tablica-hover">Korisničko ime</th>

                    <th id="ime_prezime" class="tablica-hover">Ime i prezime</th>

                    <th id="email" class="tablica-hover">E-mail</th>

                    <th id="broj_unosa" class="tablica-hover">Broj pokušaja prijava</th>

                    <th id="blokiran" class="tablica-hover">Blokiran</th>

                    <th id="naziv" class="tablica-hover">Uloga</th>

                    <th id="h--blokiraj" >Blokiraj/odblokiraj</th>
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


