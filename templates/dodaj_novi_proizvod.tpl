            <div class="glavno">
                <div class="glavno-sadrzaj">
                    <h2>Dodajte novi proizvod</h2>
                    <form method="post" action="">
                        <div class="inputii">
                            <label>Naziv</label>
                            <input type="text" id="naziv" name="naziv" {if $naziv}value="{$naziv}"{/if}>
                        </div>
                        <div class="inputii">
                            <label>Opis</label>
                            <textarea id="opis" name="opis" >{if $opis}{$opis}{/if}</textarea>
                        </div>
                        <div class="inputii">
                            <label>Slika proizvoda</label>
                            <input type="file" id="slika_proizvoda" name="slika_proizvoda" {*{if $naziv}value="{$naziv}"{/if}*}>
                        </div>
                        <div class="inputii">
                            <label>Količina</label>
                            <input type="textarea" id="kolicina" name="kolicina" {if $kolicina}value="{$kolicina}"{/if}>
                        </div>
                        <div class="inputii">
                            <label>Cijena</label>
                            <input type="text" id="cijena" name="cijena" {if $cijena}value="{$cijena}"{/if}>
                        </div>
                        <div class="inputii">
                            <label>Status proizvoda</label>
                            <select id="status" name="status">
                                <option {if $idstatus_proizvoda}value="{$idstatus_proizvoda}"{/if}> {if $status}{$status}{else}Status proizvoda{/if}</option> 
                            </select>
                        </div>
                        <div class="inputii">
                            <label>Moderator</label>
                            <select id="moderator" name="moderator">
                                <option {if $moderator_id}value="{$moderator_id}"{/if}> {if $moderator}{$moderator}{else}Odaberite moderatora{/if}</option> 
                            </select>
                        </div>
                        <div class="inputii">
                            <label>Admin</label>
                            <input type="text" id="admin" name="admin" {if $admin}value="{$admin}"{/if} disabled>
                        </div>
                        <div class="button-wrapper" >
                            <button type="submit" name="dodaj" id="dodaj">+ Dodaj</button>
                        </div>
                    </form>
                </div>                   
            </div>
                