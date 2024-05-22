            <div class="glavno-registracija">
                <p>{$test}</p>
                <h1>Kreirajte novi račun</h1>
                <div class="glavno-autentikacija">
                    <form id="form2" method="post" name="form2" action="">
                        <div class="grupirano">
                                <div class="inputii {if $greske_registracija.ime_prezime}krivi-unos{/if}">
                                    <label for="ime_prezime">Ime i prezime: </label>
                                    <input type="text" name="ime_prezime" id="ime_prezime" placeholder="ime i prezime" maxlength=80 
                                           {if $ime_prezime_registracija}value="{$ime_prezime_registracija}"{/if}>
                                </div>
                                
                                <div class="inputii {if $greske_registracija.korisnicko_ime}krivi-greske{/if}">
                                    <label for="korisnicko_ime">Korisničko ime: </label>
                                    <input type="text" id="korisnicko_ime" name="korisnicko_ime" size="30" maxlength="30" placeholder="korisničko ime" 
                                           {if $korisnicko_ime_registracija}value="{$korisnicko_ime_registracija}"{/if}><br>
                                </div>
                                
                                <div class="inputii {if $greske_registracija.email}krivi-greske{/if}">
                                    <label for="email">Email adresa: </label>
                                    <input type="email" id="email" name="email" size="30" maxlength="50" placeholder="ime.prezime@posluzitelj.xxx"
                                        {if $email_registracija}value="{$email_registracija}"{/if}><br>
                                </div>

                                <div class="inputii {if $greske_registracija.lozinka}krivi-greske{/if}">
                                    <label for="lozinka">Lozinka: </label>
                                    <input type="password" id="lozinka" name="lozinka" size="25" placeholder="lozinka"
                                        {if $lozinka_registracija}value="{$lozinka_registracija}"{/if}><br>
                                </div>
                                <div class="inputii {if $greske_registracija.lozinka2}krivi-greske{/if}">
                                    <label for="lozinka2">Ponovi lozinku: </label>
                                    <input type="password" id="lozinka2" name="lozinka2" size="25" placeholder="lozinka"
                                        {if $lozinka2_registracija}value="{$lozinka2_registracija}"{/if}><br>
                                </div>
                                <div class='g-recaptcha' data-sitekey="6LeMXy8gAAAAAOOfH0Ropue91jfoMHyuSdhkZi83"></div>
                                
                                <button type="submit" name="registracija" id="registracija">Registriraj se</button>
                                    
                                <div id="registracijske-greske" {if count($greske_registracija)===0}hidden=""{else}class="greske"{/if}>
                                    {foreach $greske_registracija key=unos item=vrijednost}
                                        <p>{$vrijednost}</p>
                                    {/foreach}
                                </div>
                        </div>
                    </form>

                </div>
            </div>