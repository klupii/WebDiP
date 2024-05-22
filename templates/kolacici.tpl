<div class="glavno">
    <div class="glavno-sadrzaj">
            <h2 style="text-align: center;">Kolačići</h2>
            <form class="tablica" method="POST" action="">
                <table>
                    <thead>
                        <tr>
                            <th>Naziv</th>
                            <th>Opis</th>
                            <th>Potvrdi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Uvjeti korištenja</td>
                            <td>Ovaj kolačić se koristi za odobravanje ostalih kolačića u aplikaciji. Ako nije omogućen, dodatne funckionalnosti aplikacije neće funkcionirati.</td>
                            <td><input type="checkbox" name="uvjeti_koristenja" value="uvjeti_koristenja" value="uvjeti_koristenja" {if $uvjeti_koristenja}checked=""{/if}></td>
                        </tr>
                        <tr>
                            <td>Zapamti ime</td>
                            <td>Ovaj kolačić koristi se za pamćenje korisničkog imena prilikom prijave. Ako se na formi pritisne "Zapamti ime", ime se pamti, a u suprotnome se briše zapamćeno ime</td>
                            <td><input type="checkbox" name="zapamti_ime" value="zapamti_ime" value="zapamti_ime" {if $zapamti_ime}checked=""{/if}></td>
                        </tr>
                        <tr>
                            <td>Povratak na stranicu</td>
                            <td>Ovaj kolačić pamti zadnju stranicu koju je korisnika posjetio prije odjave ili odlaska sa sustava. Kada se korisnik prijavi, sustav ga vraća na zadnju posjećenu stranicu.</td>
                            <td><input type="checkbox" name="zadnja_stranica" value="zadnja_stranica" value="zadnja_stranica" {if $zadnja_stranica}checked=""{/if}></td>
                        </tr>
                    <tbody>
                </table>
                <div class="button-wrapper" style="text-align: center;"><button type="submit" name="spremi" id="spremi" value="spremi">Spremi</button></div>
            </form>
    </div>
</div>
