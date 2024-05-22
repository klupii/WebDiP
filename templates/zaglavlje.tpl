<!DOCTYPE html>
<html>
    <head>
        <title>{$naslov}</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="Klara Lupoglavac">
        <meta name="keywords" content="proizvodi">
        <meta name="description" content="{$smarty.now|date_format:"%d.%m.%Y. %H:%M:%S"}">
        <link href="{$putanja}/css/klupoglav.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="{$putanja}/javascript/klupoglav.js"></script>
        <script src='https://www.google.com/recaptcha/api.js'></script>
    </head>
    
    <body>
        <header>
            <div class="zaglavlje">
                <div class="logo">
                    <a href="{$putanja}/index.php">
                        <img src="{$putanja}/multimedija/logo.png" alt="logo" width="70"></a>
                </div>
            </div>
            
            <div class="zaglavlje">
                <h1>{$naslov}</h1>
            </div>
            
            <div class="prijevod">
                 <label for="jezik">Odaberite jezik:</label>
                    <select name="jezik" id="jezik">
                        <option value="hrv" selected>Hrvatski</option>
                        <option value="eng">Engleski</option>                              
                    </select>           
            </div>
            
        </header>
        
        <nav>
            <div id="podaci-korisnika">
                        <p><strong>Korisnik:</strong> {$korisnicko_ime}</p>
                        <p><strong>Uloga:</strong> {$uloga}</p>
            </div>
            <a href="{$putanja}/index.php">Početna stranica</a>
            <a href="{$putanja}/o_autoru.php">O autoru</a>
            <a href="{$putanja}/dokumentacija.php">Dokumentacija</a>                      

            {if $id_uloga == 1}
                <a href="{$putanja}/upravljanje_konfiguracijom.php">Upravljanje konfiguracijom</a>        
                <a href="{$putanja}/dnevnik_rada.php">Dnevnik rada</a>
                <a href="{$putanja}/blokirani_korisnici.php">Blokirani korisnici</a>
                <a href="{$putanja}/obrasci/dodaj_novi_proizvod.php">Dodaj novi proizvod</a>
                <a href="{$putanja}/proizvodi.php">Proizvodi</a>       
                <a href="{$putanja}/statistika_moderatora.php">Statistika moderatora</a>
            {/if}      

            {if $id_uloga != 1 && $id_uloga != 2 && $id_uloga != 3}
                <a href="{$putanja}/obrasci/prijava.php">Prijava</a>
                <a href="{$putanja}/obrasci/registracija.php">Registracija</a>
            {/if}
            
            {if $id_uloga == 1 || $id_uloga == 2 || $id_uloga == 3}
                <a href="{$putanja}/obrasci/kolacici.php">Kolačići</a>
                <a id="odjava" href="{$putanja}/index.php?odjava=1">Odjava</a>
                
            {/if}        
        </nav> 
            
        {if $uvjeti_koristenja && $naslov=="Početna stranica"}
            <div id="uvjeti-koristenja">
                <h1>UVJETI KORIŠTENJA</h1>
                <p>Prihvatite uvjete korištenja kako bi mogli koristiti sve funkcionalnosti stranice!</p>
                <button type="button" id="uvjeti-prihvati">Prihvatite uvjete korištenja</button>
                <button type="button" id="uvjeti-odbij">Odbijte uvjete korištenja</button>
            </div>
        {/if}
            
        <section>
