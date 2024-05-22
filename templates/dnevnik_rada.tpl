<div class="glavno">
    <div class="glavno-sadrzaj">
        <h1>Dnevnik rada</h1>       
                <div class="pretrazivanje-tablice">
                    <label for="pretrazi">Pretraži:</label>
                    <input type="text" name="pretrazi" id="pretrazi">
                </div>
                <select id="atribut_za_pretrazivanje">
                    <option id="naziv" name="naziv" value="naziv">Tip zapisa</option>
                    <option id="upit" name="upit" value="upit">Upit</option>
                    <option id="radnja" name="radnja" value="radnja">Radnja</option>
                    <option id="datum_vrijeme" name="datum_vrijeme" value="datum_vrijeme">Datum i vrijeme</option>
                    <option id="korisnicko_ime" name="korisnicko_ime" value="korisnicko_ime">Korisničko ime</option>
                </select>
        <table class="tablica">
            <thead>
                <tr>
                    <th id="upit" class="tablica-hover">Upit</th>
                    <th id="radnja" class="tablica-hover">Radnja</th>
                    <th id="datum_vrijeme" class="tablica-hover">Datum i vrijeme</th>
                    <th id="naziv" class="tablica-hover">Tip zapisa</th>
                    <th id="korisnicko_ime" class="tablica-hover">Korisničko ime</th>
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
<div class="datum">
    <input id="od" name="od" type="date" value="">
    <input id="do" name="do" type="date" value="">
    <div><button class="obican" id="filter">Filtriraj</button>
    </div>
</div>

<input id="atribut_za_sortiranje" name="atribut_za_sortiranje" value="" hidden>
<input id="smjer_sortiranja" name="smjer_sortiranja" value="ASC" hidden>
<input id="broj_stranice" name="broj_stranice" value="0" hidden>

</div>
