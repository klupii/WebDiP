            <div class="glavno">
                <div class="glavno-sadrzaj">
                    <h1>Statistika količine kupljenih proizvoda po moderatoru</h1>
                    <div class="pretrazivanje-tablice">
                        <label for="pretrazi">Pretraži:</label>
                        <input type="text" name="pretrazi" id="pretrazi">
                    </div>
                    <select id="atribut_za_pretrazivanje">
                        <option id="korisnicko_ime" name="korisnicko_ime" value="korisnicko_ime">Korisničko ime</option>
                        <option id="ime_prezime" name="ime_prezime" value="ime_prezime">Ime i prezime</option>
                        <option id="broj_proizvoda" name="broj_proizvoda" value="broj_proizvoda">Broj proizvoda</option>                       
                    </select>
                    <table class="tablica">
                        <thead>
                            <tr>
                                <th id="korisnicko_ime" class="tablica-hover">Korisničko ime</th>
                                <th id="ime_prezime" class="tablica-hover">Ime i prezime</th>
                                <th id="broj_proizvoda" class="tablica-hover">Broj proizvoda</th>
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
           
   