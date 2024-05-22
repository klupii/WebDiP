<div class="glavno">
    <div class="glavno-sadrzaj">
        <h2>Proizvodi</h2>
                <div class="pretrazivanje-tablice">
                    <label for="pretrazi">Pretraži</label>
                    <input type="text" name="pretrazi" id="pretrazi">
                </div>
                <select id="atribut_za_pretrazivanje">
                    <option id="id_proizvoda" name="id_proizvoda" value="id_proizvoda">ID proizvoda</option>                            
                    <option id="naziv" name="naziv" value="naziv">Naziv proizvoda</option>
                    <option id="status" name="status" value="status">Status</option>                            
                    <option id="moderator" name="moderator" value="moderator">Moderator</option>
                </select>
                <table class="tablica">
                    <thead>
                        <tr>
                            <th id="id_proizvoda" class="tablicaodaj">ID proizvoda</th>                           
                            <th id="naziv" class="tablicaodaj">Naziv proizvoda</th>
                            <th id="opis" class="tablicaodaj">Opis proizvoda</th>
                            <th id="kolicina" class="tablicaodaj">Količina proizvoda</th>
                            <th id="cijena" class="tablicaodaj">Cijena proizvoda</th>
                            <th id="status" class="tablicaodaj">Status</th>
                            <th id="moderator" class="tablicaodaj">Moderator</th>

                            <th id="h--azuriraj" >Ažuriraj</th>
                            <th id="h--obrisi" >Obriši</th>
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
                <div><button class="vazan" name="dodaj">+ Dodaj proizvod</button></div>
            </div>
        </div>
        <input id="atribut_za_sortiranje" name="atribut_za_sortiranje" value="" hidden>
        <input id="smjer_sortiranja" name="smjer_sortiranja" value="ASC" hidden>
        <input id="broj_stranice" name="broj_stranice" value="0" hidden>
