            <div class="glavno">
                <div class="glavno-sadrzaj">
                    <div class="dokumentacija">
                        <h2>Opis projektnog zadatka</h2>
                        <p>Tema projektnog zadatka je izrada sustava za upravljanje marketinškom kampanjom proizvoda. 
                            Neregistrirani korisnik vidi popis svih kampanja koje su grupirane po količini proizvoda u svakoj kampanji i također vidi popis korisnika koji imaju izrađen profil. Kampanje i korisnici se mogu filtrirati i sortirati. 
                            Registrirani korisnik kreira profil i onda ima mogućnost kupovati proizvode. Također vidi popis otvorenih kampanji te klikom na određenu kampanju vidi opise i cijenu proizvoda u toj kampanji. Ima mogućnost vidjeti svoje kupljene proizvode i stanje svojih bodova. 
                            Moderator kreira i ažurira kampanju te pridružuje proizvode za koje je zadužen u kampanju. Također vidi popis proizvoda u aktivnim kampanjama i može odrediti uvjete za ostvarivanje bodovova i cijene proizvoda u bodovima. Moderator može vidjeti statistiku količine kupljenih proizvoda po kampanji. 
                            Administrator kreira i ažurira proizvod te dodjeljuje moderatora za svaki proizvod. Također vidi statistiku količine kupljenih proizvoda po moderatoru. Administrator može upravljati konfiguracijom aplikacije, blokiranjem korisnika, pomakom za virtualno vrijeme i ostalo. Ima mogućnost uvida u dnevnik radi i na temelju dnevnika može izraditi izvještaje.</p> 
                        <h2>Opis projektnog rješenja</h2>
                        <p>U projektnom rješenju se ne nalaze funkcionalnosti za registriranog korisnika i moderatora, kod administratora nema grafičkog prikaza statistike, dodjele moderatora te funkcionalnost iz projektnog zadatka.
                        Također nedostaje prilagođavanje mediju. Kroz cijeli projekt je korišten Smarty predložak i zaštita od XSS napada i SQL ubacivanja. Sve funkcionalnosti su implementirane pomoću jQuerya i AJAX-a. Svaka tablica u aplikaciji ima mogućnost sortiranja i pretraživanja te neke i filtriranje po datumu.
                        Izrada projekta je trajala 3 tjedna, a u projektu su korišteni programski jezici PHP i Javascript, HTML i CSS jezici. Od vanjskih izvora su korišteni reCaptcha API od Googl-a. Konfiguracija aplikacije se nalazi u datoteci konfiguracija.conf koja je na serveru i u kojoj su sadržane sve bitne postavke za aplikaciju.</p> 
                        <h2>ERA model</h2>
                        <div>
                            <a href="multimedija/era.png"><img src="multimedija/era.png" alt="ERA dijagram" height="200" width="200"></a>                           
                        </div>    
                        <p>Baza podataka sastoji se od nekoliko tablica koje su međusobno povezane radi pohrane i upravljanja informacijama za projekt. Pogledajmo ove tablice i njihove veze detaljnije.

Prva tablica je `dnevnik_rada`, koja služi kao zapisnik za bilježenje različitih aktivnosti. Sadrži stupce poput `id_zapisa` (identifikator za svaki zapis), `datum_vrijeme` (datum i vrijeme zapisa), `upit` (opciona pohrana upita povezanih s zapisi), `radnja` (opciona opis radnje izvršene u zapisu), `id_korisnik` (referenca na odgovarajući unos korisnika u tablici `korisnik`) i `id_tip_zapisa` (referenca na vrstu zapisa u tablici `tip_zapisa`).

Tablica `jezik_aplikacije` pohranjuje informacije o jezicima aplikacije. Sadrži stupce `id_jezika` (identifikator za svaki unos jezika) i `naziv` (naziv jezika).

Sljedeća je tablica `kampanja`, koja predstavlja kampanje. Sadrži stupce poput `id_kampanje` (identifikator za svaku kampanju), `naziv` (naziv kampanje), `opis` (opis kampanje), `datum_pocetka` (datum i vrijeme početka kampanje), `datum_zavrsetka` (datum i vrijeme završetka kampanje) i `moderator_id` (referenca na odgovarajućeg moderatora u tablici `korisnik`).

Tablica `korisnik` pohranjuje informacije o korisnicima. Sadrži stupce poput `id_korisnik` (identifikator za svakog korisnika), `korisnicko_ime` (korisničko ime), `lozinka` (lozinka korisnika), `lozinka_sha256` (SHA256 kriptografski hash lozinke korisnika), `email` (e-mail adresa korisnika), `datum_vrijeme_registracije` (datum i vrijeme registracije korisnika), `uvjeti_koristenja` (datum i vrijeme kada je korisnik prihvatio uvjete korištenja, ako su primjenjivi), `broj_unosa` (broj unosa korisnika, ako je primjenjivo), `id_jezika` (referenca na preferirani jezik u tablici `jezik_aplikacije`), `id_uloga` (referenca na ulogu korisnika u tablici `uloga`), `stanje_bodova` (stanje bodova korisnika s pretpostavljenom vrijednosti 0), `aktivacijski_kod` (aktivacijski kod za korisnika, ako je primjenjivo), `slika` (naziv datoteke slike profila korisnika, ako je

 dostupna), `sol` (vrijednost soli, ako je primjenjivo), `ime_prezime` (puno ime korisnika), `blokiran` (oznaka koja ukazuje je li korisnik blokiran) i `aktiviran` (oznaka koja ukazuje je li korisnik aktiviran).

Dalje, imamo tablicu `kupovina`, koja predstavlja kupovine. Sadrži stupce poput `id_kupovine` (identifikator za svaku kupovinu), `id_korisnik` (referenca na odgovarajućeg korisnika u tablici `korisnik`), `opis` (opis kupovine), `vrijednost` (vrijednost kupovine), `potroseni_bodovi` (bodovi korišteni za kupovinu) i `datum_vrijeme` (datum i vrijeme kupovine).

Tablica `kupovina_ima_proizvod` uspostavlja vezu između kupovina i proizvoda. Sadrži stupce `id_kupovine` (referenca na kupovinu u tablici `kupovina`) i `id_proizvoda` (referenca na proizvod u tablici `proizvod`).

Tablica `pojam` pohranjuje pojmove i njihove odgovarajuće identifikatore. Sadrži stupce `id_pojam` (identifikator za svaki pojam) i `naziv_pojma` (longtext koji predstavlja pojam).

Tablica `prevedeni_pojam` održava prijevode pojmova na različite jezike. Sastoji se od stupaca `id_jezika` (referenca na jezik u tablici `jezik_aplikacije`), `id_pojam` (referenca na pojam u tablici `pojam`) i `prevedena_vrijednost` (longtext koji predstavlja prevedenu vrijednost pojma).

Tablica `proizvod` predstavlja proizvode i sadrži stupce poput `id_proizvoda` (identifikator za svaki proizvod), `naziv` (naziv proizvoda), `opis` (opis proizvoda), `slika` (naziv datoteke slike proizvoda, ako je dostupna), `kolicina` (količina proizvoda), `cijena` (cijena proizvoda), `idstatus_proizvoda` (referenca na status proizvoda u tablici `status_proizvoda`), `admin_id` (referenca na administratora odgovornog za proizvod) i `moderator_id` (referenca na moderatora odgovornog za proizvod).

Tablica `prozvod_u_kampanji` uspostavlja vezu između proizvoda i kampanja. Sadrži stupce `id_proizvoda` (referenca na proizvod u tablici `proizvod`), `id_kampanje` (referenca na kampanju u tablici

 `kampanja`), `bodovi_vrijednost` (vrijednost bodova za proizvod u kampanji, ako je primjenjivo) i `bodovi_cijena` (bodovna cijena proizvoda u kampanji, ako je primjenjivo).

Konačno, tablica `status_proizvoda` pohranjuje različite statusa proizvoda. Sadrži stupce `idstatus_proizvoda` (identifikator za svaki status) i `naziv` (naziv statusa).
Ove tablice su međusobno povezane referencama, omogućavajući bazi podataka da uspostavi veze između različitih entiteta i učinkovito pohranjuje i dohvaća informacije za aplikaciju.</p> 
                        <h2>Skripte, mape mjesta, navigacijski dijagram</h2>
                        <div>
                            <a href="multimedija/navigacijski.png"><img src="multimedija/navigacijski.png" alt="Navigacijski dijagram" height="200" width="200"></a>
                        </div>
                        <p>Prema navigacijskom dijagramu neregistrirani korisnik ima mogućnost pregleda i pristupanja registraciji, prijavi, stranici o autoru, dokumentaciji, popisu kampanja i popisu korisnika koji su na početnoj stranici te prijevod stranice.
                        Registrirani korisnik može pristupiti svom profilu koji mora kreirati na početku te ga može pregledati. Također može vidjeti popis kupljenih proizvoda te kupiti proizvod. Može vidjeti svoje stanje bodova i otvorene kampanje koje može pojedinačno pregledati.
                        Moderator ima pristup stranici kampanje gdje može kreirati novu kampanju i pristupiti postojećoj. Može pristupiti stranicama proizvodi u aktivnim kampanjama, statistika kupljenih proizvoda po kampanjama te stranici bodovi gdje može urediti uvjete za dobivanje bodova i cijene proizvoda.
                        Administrator ima svoju vlastitu stranicu gdje ima pregled proizvoda te može kreirati novi proizvod i ažurirati postojeći. Administrator ima uvid u statistiku kupljenih proizvoda po moderatorima, dnevnik rada, popis blokiranih proizvoda, konfiguraciju  aplikacije te podatke sustava koje može ažurirati.</p>  
                        <h3>Skripte</h3>
                        <ul>
                            <li>blokiraj_koriscnika.php</li>
                            <li>dohvacanje_pomaka.php</li>
                            <li>dohvati_dnevnik.php</li>
                            <li>dohvati_koriscnike_index.php</li>
                            <li>dohvati_moderatore.php</li>
                            <li>dohvati_proizvode.php</li>
                            <li>dohvati_sve_kampanje.php</li>
                            <li>dohvati_sve_korisnike_za_blokiranje.php</li>
                            <li>funkcije.php</li>
                            <li>obrisi_proizvod.php</li>
                            <li>odblokiraj_korisnika.php</li>
                            <li>provjeri_korisnicko_ime.php</li>   
                            <li>resetiraj_uvjete_koristenja.php</li>
                            <li>spremi_konfiguraciju.php</li>
                            <li>spremi_uvjete_koristenja.php</li>
                            <li>unos_pomaka.php</li>
                            <li>zaboravljena_lozinka.php</li>
                            <li>konfiguracija.class.php</li>
                        </ul>
                        <h2>Korištene tehnologije i alati</h2>
                        <ul class="alati">
                            <li>HTML (Hypertext Markup Language): jezik koji se koristi za kreiranje strukture i sadržaja web stranica.</li>
                            <li>CSS (Cascading Style Sheets): jezik koji se koristi za definiranje prezentacije i izgleda HTML dokumenata.</li>
                            <li>JavaScript: programski jezik koji web stranicama dodaje interaktivnost i dinamičko ponašanje.</li>
                            <li>jQuery: JavaScript biblioteka koja pojednostavljuje zadatke poput pregledavanja HTML dokumenata, rukovanja događajima i stvaranja animacija.</li>
                            <li>PHP (Hypertext Preprocessor): skriptni jezik na strani poslužitelja koji se koristi za web razvoj za generiranje dinamičkog web sadržaja.</li>
                            <li>MySQL: sustav upravljanja relacijskim bazama podataka za pohranu i upravljanje strukturiranim podacima.</li>
                            <li>MySQL Workbench: vizualni alat za dizajniranje i modeliranje MySQL baza podataka, koji vam omogućuje stvaranje i upravljanje shemama baze podataka.</li>
                            <li>phpMyAdmin: web-bazirani administrativni alat koji pruža grafičko sučelje za upravljanje MySQL bazama podataka, izvršavanje upita i obavljanje drugih zadataka baze podataka.</li>
                            <li>Apache NetBeans IDE: razvojno okruženje koje podržava više programskih jezika kao što su Java, PHP, HTML/CSS/JavaScript, nudeći značajke za kodiranje, otklanjanje pogrešaka i upravljanje projektima.</li>
                            <li>Smarty: PHP predložak koji razdvaja prezentaciju i poslovnu logiku u web aplikacijama, olakšavajući održavanje i ažuriranje dizajna web stranica.</li>
                            <li>reCAPTCHA: sigurnosna mjera koja pomaže u sprječavanju automatizirane zloupotrebe i neželjene pošte na web-lokacijama predstavljanjem izazova za provjeru je li korisnik čovjek ili bot.</li>
                        </ul>
                        <h2>Vanjski moduli/biblioteke</h2>
                        <ul>
                            <li>sessija.class.php - Klasa preko koje se dohvaća sesija korisnika koja je dorađena za potrebe projekta. Preuzeto s moodla te korištena na nastavi.</li>
                            <li>baza.class.php - Klasa za spajanje s bazom i zapisivanja podataka u dnevnik te je dorađena za potrebe projekta. Preuzeta s moodla te korištena na nastavi.</li>
                        </ul>
                    </div>
                </div>
            </div>

