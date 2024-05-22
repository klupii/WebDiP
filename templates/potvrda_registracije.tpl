    <div class="glavno-registracija">
        <div class="glavno-autentikacija">
            <div>
               {if $aktiviran}
                   <p>Uspješno ste aktivirali korisnički račun!</p>
                   <a href="./obrasci/prijava.php">Prijava</a>
               {else}

                   <p>Niste uspjeli aktivirati korisnički račun...Ponovno se registrirajte!</p>
                   <a href="./obrasci/registracija.php">Registracija</a>

               {/if} 

            </div>
        </div>
    </div>


