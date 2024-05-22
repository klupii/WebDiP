            <div class="glavno-prijava">
                <h1>Prijavite se</h1>
                <div class="glavno-autentikacija">
                    <form id="form1" method="post" name="form1" action="">
                        <div class="grupirano">
                            <p><label for="korisnicko_ime">Korisničko ime: </label>
                                <input type="text" id="korisnicko_ime" name="korisnicko_ime" size="30" maxlength="30" placeholder="korisnicko ime"
                                    {if $zapamti_ime} value="{$zapamti_ime}"{/if}>
                                <label for="lozinka">Lozinka: </label>
                                <input type="password" id="lozinka" name="lozinka" size="30" maxlength="30" placeholder="lozinka"><br>
                                <div class="zapamti">
                                    <label for="zapamti_ime">Zapamti me</label>
                                    <input type="checkbox" name="zapamti_ime" id="zapamti_ime"> 
                                </div>
                                <button id="prijava" type="submit" name="prijava">Prijavi se</button>
                                <div id="za-lozinku">
                                    <p id="zaboravljena_lozinka">Zaboravljena lozinka</p>
                                </div>
                        </div>
                        <div id="prijava-greske" {if count($greske_prijava)===0}hidden=""{else}class="greske"{/if}>
                             {foreach $greske_prijava key=unos item=vrijednost}
                                 <p>{$vrijednost}</p>
                             {/foreach}
                         </div>                       
                    </form>
                </div>
            </div>


