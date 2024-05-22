$(document).ready(function () {
    let naslov = $(document).find("title").text();
    switch (naslov) {
        case "Početna stranica":
            $("#uvjeti-odbij").click(function (event) {
                    $("#uvjeti-koristenja").hide();
                });
                $("#uvjeti-prihvati").click(spremiUvjeteKoristenja);
                
            pocetnaStranicaKampanje();
            pocetnaStranica();
            break;
        case "Upravljanje konfiguracijom":
            upravljanjeKonfiguracijom();
            break;            
        case "Registracija":
            registracija();
            break;
        case "Prijava":
            prijava();
            break;
        case "Dnevnik rada":
            dnevnik();
            break;
        case "Blokirani korisnici":
            blokiraniKorisnici();
            break;
        case "Proizvodi":
            proizvodi();
        case "Dodavanje novog proizvoda":
            dodavanjeNovogProizvoda();
            break;
        case "Statistika moderatora":
            dohvatiModeratore();
            break;
    }
});



function prijava() {
    $("#zaboravljena_lozinka").click(function (event) {
        let korisnicko_ime = $("#korisnicko_ime").val();
        if (prazanUnos(korisnicko_ime)) {
            alert("Morate unijeti korisničko ime kako bi mogli resetirati lozinku!");

        } else if (!postojiKorisnickoIme(korisnicko_ime)) {
            alert("Korisničko ime ne postoji!");
        } else {
            $.post("../skripte/zaboravljena_lozinka.php", {korisnicko_ime: korisnicko_ime}, function (data) {
                alert("Nova lozinka poslana Vam je na mail adresu!");
            });
        }
    });
}

function spremiUvjeteKoristenja(event) {
    $.post("skripte/spremi_uvjete_koristenja.php", {korisnicko_ime: "korisnicko_ime"}, function (data) {
    });
    $("#uvjeti-koristenja").hide();
}

function registracija() {
    $("#registracija").click(provjeriUnoseRegistracija);
}

function provjeriUnoseRegistracija(event) {
    let ime_prezime = $("#ime_prezime").val();
    let korisnicko_ime = $("#korisnicko_ime").val();
    let email = $("#email").val();
    let lozinka = $("#lozinka").val();
    let lozinka2 = $("#lozinka2").val();
    let greske = {};
    let polje_unosa = {ime_prezime: ime_prezime, korisnicko_ime: korisnicko_ime, email: email, lozinka: lozinka, lozinka2: lozinka2};
    $.each(polje_unosa, function (unos, vrijednost) {
        if (prazanUnos(vrijednost)) {
            greske[unos] = parseCitljivost(unos) + " - Nije unesena vrijednost";
        } else {
            let uspjeh;
            let regularni_izraz;
            switch (unos) {
                case "ime_prezime":
                    uspjeh = dovoljanBrojZnakova(0, 50, vrijednost);
                    regularni_izraz = new RegExp('^(([A-Z,ČĆŽĐŠ][a-z,čćžđš]{1,})(([ ]|[-])([A-Z,ČĆŽĐŠ][a-z,čćžđš]{1,}))?)[ ](([A-Z,ČĆŽĐŠ][a-z,čćžđš]{1,})(([ ]|[-])([A-Z,ČĆŽĐŠ][a-z,čćžđš]{1,}))?)$', 'g');
                    if (uspjeh !== true) {
                        greske[unos] = parseCitljivost(unos) + " - " + uspjeh;
                    } else if (!regularni_izraz.test(vrijednost)) {
                        greske[unos] = parseCitljivost(unos) + " - Format imena i prezimena mora biti Ime[[-|' ']Ime] Prezime[[-|' ']Prezime]";
                    }
                    break;
                case "korisnicko_ime":
                    uspjeh = dovoljanBrojZnakova(5, 50, vrijednost);
                    regularni_izraz = new RegExp('^([^-]|[a-z,A-Z,0-9,ČĆŽĐŠčćžđš,_])[a-z,A-Z,0-9,ČĆŽĐŠčćžđš,_,-]{4,50}$', 'g');
                    if (uspjeh !== true) {
                        greske[unos] = parseCitljivost(unos) + " - " + uspjeh;
                    } else if (!regularni_izraz.test(vrijednost)) {
                        greske[unos] = parseCitljivost(unos) + " - Korisničko ime se smije sastojati samo od malih i velikih slova, brojeva te znakova '-' i '_' s time da ne smije početi znakom '-'";
                    } else if (postojiKorisnickoIme(korisnicko_ime)) {
                        greske[unos] = parseCitljivost(unos) + " - Korisničko ime " + korisnicko_ime + " već postoji!";
                    }
                    break;
                case "email":
                    uspjeh = dovoljanBrojZnakova(10, 50, vrijednost);
                    regularni_izraz = new RegExp('^([A-Z,a-z,ČĆŽĐŠčćžđš][a-z,A-Z,0-9,ČĆŽĐŠčćžđš,_,-]{2,}@[a-z]{2,}[.][a-z]{1,})$', 'g');
                    if (uspjeh !== true) {
                        greske[unos] = parseCitljivost(unos) + " - " + uspjeh;
                    } else if (!regularni_izraz.test(vrijednost)) {
                        greske[unos] = parseCitljivost(unos) + " - E-mail mora biti u formatu 'korime@imedomene.domena'";
                    }

                    break;
                case "lozinka":
                    uspjeh = dovoljanBrojZnakova(8, 50, vrijednost);
                    regularni_izraz = new RegExp('^(?=((.*[0-9]){2,}))(?=((.*[A-ZČĆŽĐŠ]){2,}))(?=((.*[a-zčćžđš]){2,})).{8,50}$', 'g');
                    if (uspjeh !== true) {
                        greske[unos] = parseCitljivost(unos) + " - " + uspjeh;
                    } else if (!regularni_izraz.test(vrijednost)) {
                        greske[unos] = parseCitljivost(unos) + " - Lozinka mora imati barem 2 mala slova, 2 velika slova i 2 broja";
                    }

                    break;
                case "lozinka2":
                    if (vrijednost !== lozinka) {
                        greske[unos] = parseCitljivost(unos) + " - Ponovljena lozinka se ne podudara s lozinkom";
                    }

                    break;
            }
        }

    });
    
    ocistiPoljeGresaka("#registracijske-greske");
    if (Object.keys(greske).length > 0) {
        prikaziGreske(greske, "#registracijske-greske");
        event.preventDefault();
    } else {
        postaviTocnoPolje("#registracijske-greske");
    }
}


function dovoljanBrojZnakova(min, max, unos) {
    if (unos.length < min)
        return "Uneseno je manje od " + min + " znakova";
    if (unos.length > max)
        return "Uneseno je više od " + max + " znakova";
    return true;
}

function prazanUnos(unos) {
    return !$.trim(unos).length > 0;
}

function postojiKorisnickoIme(korisnicko_ime) {
    var postoji;
    let podaci_za_slanje = {korisnicko_ime: korisnicko_ime};
    $.ajax({
        type: "POST",
        url: "../skripte/provjeri_korisnicko_ime.php",
        data: podaci_za_slanje,
        dataType: 'json',
        async: false,
        success: function (data) {
            postoji = data.postoji;
        }
    });

    return korisnicko_ime === postoji;
}

function prikaziGreske(greske, polje_gresaka) {
    $(polje_gresaka).removeClass("greske-tocno");
    $(polje_gresaka).addClass("greske");
    $(polje_gresaka).html("");
    $(polje_gresaka).show();
    $.each(greske, function (key, value) {
        $(polje_gresaka).append("<p>" + value + "</p>");
        $("#" + key).parent("div").addClass("krivi-unos");
    });
}

function ocistiPoljeGresaka(polje_gresaka) {
    $(polje_gresaka).html("");
    $("*").removeClass("krivi-unos");

    $(polje_gresaka).hide();
}

function postaviTocnoPolje(polje_gresaka) {
    $(polje_gresaka).show();
    $(polje_gresaka).html("Uspjeh!");
    $(polje_gresaka).removeClass("greske");
    $(polje_gresaka).addClass("greske-tocno");
    $(polje_gresaka).delay(700).fadeOut('slow');
}


function upravljanjeKonfiguracijom() {
    $("#spremi-konfiguraciju").click({skripta: "skripte/spremi_konfiguraciju.php"}, spremiKonfiguraciju);
    $("#postavi-pomak").click({servis: "http://barka.foi.hr/WebDiP/pomak_vremena/vrijeme.html"}, pozoviServis);
    $("#dohvati-pomak").click(vratiPomak);
    $("#spremi-pomak").click(spremiPomak);
    $("#reset").click(function (event) {
        $.post("skripte/resetiraj_uvjete_koristenja.php", function (data) {
            alert("Resetirali ste uvjete korištenja svim korisnicima!");
        });
    });
}

function spremiKonfiguraciju(event) {
    event.preventDefault();
    let skripta = event.data.skripta;
    let trajanje_kolacica = $("#trajanje_kolacica").val();
    let broj_stranica_stranicenja = $("#broj_stranica_stranicenja").val();
    let trajanje_sesije = $("#trajanje_sesije").val();
    let broj_neuspjesnih_prijava = $("#broj_neuspjesnih_prijava").val();
    let istek_verifikacije = $("#istek_verifikacije").val();
    let parametri = {
        trajanje_kolacica: trajanje_kolacica,
        broj_stranica_stranicenja: broj_stranica_stranicenja,
        trajanje_sesije: trajanje_sesije,
        broj_neuspjesnih_prijava: broj_neuspjesnih_prijava,
        istek_verifikacije: istek_verifikacije
    };

    $("#konfiguracijska-forma").children("div").removeClass("krivi-unos");
    let greske = provjeriKonfiguracijskeParametre(parametri);
    if (Object.keys(greske).length > 0) {
        prikaziGreske(greske, "#konfiguracija-greske");
    } else {
        greske = pozoviSkriptu(skripta, parametri);
        prikaziGreske(greske);
    }
    if (!greske) {
        ocistiPoljeGresaka("#konfiguracija-greske");
        postaviTocnoPolje("#konfiguracija-greske");
    }

}

function provjeriKonfiguracijskeParametre(parametri) {
    let greske = {};
    $.each(parametri, function (key, value) {
        if (!value || value == "") {
            greske[key] = parseCitljivost(key) + " - Vrijednost nije unesena";
        } else if (!broj_je(value)) {
            greske[key] = parseCitljivost(key) + " - Nije broj!";
        } else if (value > 99999 || value < -99999) {
            greske[key] = parseCitljivost(key) + " - Broj je prevelik!";
        }
    });
    return greske;
}

function posrednickaFunkcijaAjax(event) {
    event.data.funkcija(event.data.metoda, event.data.skripta, event.data.parametri, event.data.funkcija_obrada);
}

function pozoviServis(event) {
    window.open(event.data.servis);
}

function pozoviSkriptu(skripta, parametri) {
    $.post(skripta, parametri, function (data) {
    }, "json");
}

function pozoviObradiSkriptu(metoda, skripta, parametri, funkcija) {
    if (metoda == "get")
        $.get(skripta, parametri, funkcija, "json");
    else {
        $.post(skripta, parametri, funkcija, "json");
    }
}

function vratiPomak(event) {
    $.post("skripte/dohvacanje_pomaka.php", function (data) {
        $("#pomak").val(data);
    });
}

function spremiPomak(event) {
    let pomak = $("#pomak").val();
    let parametri = {pomak: pomak};
    $.post("skripte/unos_pomaka.php", parametri, function (data) {
        postaviTocnoPolje("#pomak-greske");
    }, "json");
}


function parseCitljivost(string) {
    return string.charAt(0).toUpperCase() + string.substring(1).replaceAll("_", " ");
}

function broj_je(vrijednost) {
    return !isNaN(vrijednost) && parseInt(Number(vrijednost)) == vrijednost && !isNaN(parseInt(vrijednost, 10));
}

function prikaziGreske(greske, polje_gresaka) {
    $(polje_gresaka).removeClass("greske-tocno");
    $(polje_gresaka).addClass("greske");
    $(polje_gresaka).html("");
    $(polje_gresaka).show();
    $.each(greske, function (key, value) {
        $(polje_gresaka).append("<p>" + value + "</p>");
        $("#" + key).parent("div").addClass("krivi-unos");
    });
}

function ocistiPoljeGresaka(polje_gresaka) {
    $(polje_gresaka).html("");
    $("*").removeClass("krivi-unos");

    $(polje_gresaka).hide();
}

function postaviTocnoPolje(polje_gresaka) {
    $(polje_gresaka).show();
    $(polje_gresaka).html("Uspjeh!");
    $(polje_gresaka).removeClass("greske");
    $(polje_gresaka).addClass("greske-tocno");
    $(polje_gresaka).delay(700).fadeOut('slow');
}

function provjeriDatum(unos_datum) {
    if (unos_datum.length !== 20)
        return false;

    let cijeli_zapis = unos_datum.split(' ');
    if (cijeli_zapis.length !== 2)
        return false;

    let datum = cijeli_zapis[0].split('.');
    if (datum.length !== 4)
        return false;

    let vrijeme = cijeli_zapis[1].split(':');
    if (vrijeme.length !== 3)
        return false;

    let dan = datum[0];
    let mjesec = datum[1];
    let godina = datum[2];

    let sati = vrijeme[0];
    let minute = vrijeme[1];
    let sekunde = vrijeme[2];

    let spoj = dan + mjesec + godina + sati + minute + sekunde;
    for (let slovo of spoj) {
        if (parseInt(slovo) != slovo)
            return false;
    }
    if (dan.length !== 2 || mjesec.length !== 2 || godina.length !== 4 || sati.length !== 2 || minute.length !== 2 || sekunde.length !== 2) {
        return false;
    } else if (parseInt(dan[0]) > 3 || parseInt(dan[0]) < 0)
        return false;
    else if (parseInt(dan[1]) > 9 || parseInt(dan[1]) < 0)
        return;
    else if (parseInt(mjesec[0]) > 1 || parseInt(mjesec[0]) < 0)
        return false;
    else if (parseInt(mjesec[1]) > 9 || parseInt(mjesec[1]) < 0)
        return false;
    else if (parseInt(godina[0]) > 9 || parseInt(godina[0]) < 0)
        return false;
    else if (parseInt(godina[1]) > 9 || parseInt(godina[1]) < 0)
        return false;
    else if (parseInt(godina[2]) > 9 || parseInt(godina[2]) < 0)
        return false;
    else if (parseInt(godina[3]) > 9 || parseInt(godina[3]) < 0)
        return false;
    else if (parseInt(sati[0]) < 0 || parseInt(sati[0]) > 2)
        return false;
    else if (parseInt(sati[1]) < 0 || parseInt(sati[1]) > 9)
        return false;
    else if (parseInt(minute[0]) < 0 || parseInt(minute[0]) > 5)
        return false;
    else if (parseInt(minute[1]) < 0 || parseInt(minute[1]) > 9)
        return false;
    else if (parseInt(sekunde[0]) < 0 || parseInt(sekunde[0]) > 5)
        return false;
    else if (parseInt(sekunde[1]) < 0 || parseInt(sekunde[1]) > 9)
        return false;

    return true;
    
}

function pocetnaStranica(){
    postaviTablicuKorisnika();
    $("#pretrazi").keyup(postaviTablicuKorisnika);
    $(".tablica-hover").click({funkcija: postaviTablicuKorisnika}, sortiranjeTablice);
    $("#prva").click({funkcija: postaviTablicuKorisnika}, prva);
    $("#sljedeca").click({funkcija: postaviTablicuKorisnika}, sljedeca);
    $("#prijasnja").click({funkcija: postaviTablicuKorisnika}, prijasnja);
    $("#posljednja").click({funkcija: postaviTablicuKorisnika}, posljednja);
}

function pocetnaStranicaKampanje(){
    postaviTablicuKampanja();
    $("#pretrazivanje").keyup(postaviTablicuKampanja);
    $(".tablica1-hover").click({funkcija: postaviTablicuKampanja}, sortiranjeTabliceKampanje);
    $("#prva1").click({funkcija: postaviTablicuKampanja}, prva1);
    $("#sljedeca1").click({funkcija: postaviTablicuKampanja}, sljedeca1);
    $("#prijasnja1").click({funkcija: postaviTablicuKampanja}, prijasnja1);
    $("#posljednja1").click({funkcija: postaviTablicuKampanja}, posljednja1);
    $("#filtriraj").click(filtriraj);
}

function sortiranjeTabliceKampanje(event) {
    let atribut_za_sortiranje1 = event.target.id;
    let smjer_sortiranja1 = ($("#smjer_sortiranja1").val() == "DESC" && $("#atribut_za_sortiranje1").val() == atribut_za_sortiranje1) ? $("#smjer_sortiranja1").val("ASC") : $("#smjer_sortiranja1").val("DESC");
    $("#atribut_za_sortiranje1").val(atribut_za_sortiranje1);
    event.data.funkcija();
}

function filtriraj(event) {
    postaviTablicuKampanja();
}

function prva1(event) {
    $("#broj_stranice1").val(0);
    event.data.funkcija();
}

function sljedeca1(event) {
    let trenutna = $("#broj_stranice1").val();
    $("#broj_stranice1").val(parseInt(trenutna) + 1);
    event.data.funkcija();
}

function prijasnja1(event) {
    let trenutna = $("#broj_stranice1").val();
    $("#broj_stranice1").val(parseInt(trenutna) - 1);
    event.data.funkcija();
}

function posljednja1(event) {
    $("#broj_stranice1").val(9999999);
    event.data.funkcija();
}

function postaviTablicuKampanja(event){
    let atribut_za_sortiranje1 = ($("#atribut_za_sortiranje1").val() != "") ? $("#atribut_za_sortiranje1").val() : null;
    let smjer_sortiranja1 = ($("#smjer_sortiranja1").val() != "") ? $("#smjer_sortiranja1").val() : null;
    let broj_stranice1 = ($("#broj_stranice1").val() != "") ? $("#broj_stranice1").val() : null;
    let vrijednost_za_pretrazivanje1 = ($("#pretrazivanje").val() != "") ? $("#pretrazivanje").val() : null;
    let datum_od = ($("#od").val() != "") ? $("#od").val() : null;
    let datum_do = ($("#do").val() != "") ? $("#do").val() : null;
    let atribut_za_pretrazivanje1 = ($("#atribut_za_pretrazivanje1").val() != "") ? $("#atribut_za_pretrazivanje1").val() : null;
    let parametri = {datum_od: datum_od, datum_do: datum_do, atribut_za_sortiranje1: atribut_za_sortiranje1, smjer_sortiranja1: smjer_sortiranja1, broj_stranice1: broj_stranice1, vrijednost_za_pretrazivanje1: vrijednost_za_pretrazivanje1, atribut_za_pretrazivanje1: atribut_za_pretrazivanje1};
    $.post("skripte/dohvati_sve_kampanje.php", parametri, function (data) {
        $(".tablica1").children("tbody").html("");
        if (data.tablica.length > 0) {
            $.each(data.tablica, function (atribut, vrijednost) {
                let tbody = "<tr id='" + vrijednost.id_kampanje + "'>";
                tbody += "<td id='naziv'>" + vrijednost.naziv + "</td>" +
                        "<td id='opis'>" + vrijednost.opis + "</td>" +
                        "<td id='datum_pocetka'>" + vrijednost.datum_pocetka + "</td>" +
                        "<td id='datum_zavrsetka'>" + vrijednost.datum_zavrsetka + "</td>" +
                        "<td id=broj_proizvoda>" + vrijednost.broj_proizvoda + "</td>";
                tbody += "</tr>";
                $(".tablica1").children("tbody").append(tbody);
                
            });
            $("#broj_stranice1").val(data.trenutna_stranica);
        } else {
            $(".tablica1").children("tbody").append("Nema podataka");
        }

    }, "json");
}

function postaviTablicuKorisnika(event){
    let atribut_za_sortiranje = ($("#atribut_za_sortiranje").val() != "") ? $("#atribut_za_sortiranje").val() : null;
    let smjer_sortiranja = ($("#smjer_sortiranja").val() != "") ? $("#smjer_sortiranja").val() : null;
    let broj_stranice = ($("#broj_stranice").val() != "") ? $("#broj_stranice").val() : null;
    let vrijednost_za_pretrazivanje = ($("#pretrazi").val() != "") ? $("#pretrazi").val() : null;
    let atribut_za_pretrazivanje = ($("#atribut_za_pretrazivanje").val() != "") ? $("#atribut_za_pretrazivanje").val() : null;
    let parametri = {
        atribut_za_sortiranje: atribut_za_sortiranje,
        smjer_sortiranja: smjer_sortiranja,
        broj_stranice: broj_stranice,
        vrijednost_za_pretrazivanje: vrijednost_za_pretrazivanje,
        atribut_za_pretrazivanje: atribut_za_pretrazivanje
    };
    $.post("skripte/dohvati_korisnike_index.php", parametri, function (data) {
        $(".tablica").children("tbody").html("");
        if (data.tablica.length > 0) {
            $.each(data.tablica, function (atribut, vrijednost) {
                let tbody = "<tr id='" + vrijednost.id_korisnik + "'>";
                tbody += "<td id='korisnicko_ime'>" + vrijednost.korisnicko_ime + "</td>" +
                    "<td id='ime_prezime'>" + vrijednost.ime_prezime + "</td>";
                tbody += "</tr>";
                $(".tablica").children("tbody").append(tbody);
            });
            $("#broj_stranice").val(data.trenutna_stranica);
        } else {
         $(".tablica").children("tbody").append(tbody);        }
    }, "json");
}

function dohvatiModeratore(){
    postaviTablicuModeratora();
    $("#pretrazi").keyup(postaviTablicuModeratora);
    $(".tablica-hover").click({funkcija: postaviTablicuModeratora}, sortiranjeTablice);
    $("#prva").click({funkcija: postaviTablicuModeratora}, prva);
    $("#sljedeca").click({funkcija: postaviTablicuModeratora}, sljedeca);
    $("#prijasnja").click({funkcija: postaviTablicuModeratora}, prijasnja);
    $("#posljednja").click({funkcija: postaviTablicuModeratora}, posljednja);    
}

function postaviTablicuModeratora(event){
   let atribut_za_sortiranje = ($("#atribut_za_sortiranje").val() != "") ? $("#atribut_za_sortiranje").val() : null;
    let smjer_sortiranja = ($("#smjer_sortiranja").val() != "") ? $("#smjer_sortiranja").val() : null;
    let broj_stranice = ($("#broj_stranice").val() != "") ? $("#broj_stranice").val() : null;
    let vrijednost_za_pretrazivanje = ($("#pretrazi").val() != "") ? $("#pretrazi").val() : null;
    let atribut_za_pretrazivanje = ($("#atribut_za_pretrazivanje").val() != "") ? $("#atribut_za_pretrazivanje").val() : null;
    let parametri = {
        atribut_za_sortiranje: atribut_za_sortiranje,
        smjer_sortiranja: smjer_sortiranja,
        broj_stranice: broj_stranice,
        vrijednost_za_pretrazivanje: vrijednost_za_pretrazivanje,
        atribut_za_pretrazivanje: atribut_za_pretrazivanje
    };
    $.post("skripte/dohvati_moderatore.php", parametri, function (data) {
        $(".tablica").children("tbody").html("");
        if (data.tablica.length > 0) {
            $.each(data.tablica, function (atribut, vrijednost) {
                let tbody = "<tr id='" + vrijednost.id_korisnik + "'>";
                tbody += "<td id='korisnicko_ime'>" + vrijednost.korisnicko_ime + "</td>" +
                    "<td id='ime_prezime'>" + vrijednost.ime_prezime + "</td>" +
                    "<td id='broj_proizvoda'>" + vrijednost.broj_proizvoda + "</td>";           
                tbody += "</tr>";
                $(".tablica").children("tbody").append(tbody);
            });
            $("#broj_stranice1").val(data.trenutna_stranica);
        } else {
            $(".tablica").children("tbody").append("Nema podataka");
        }
    }, "json");
}
    

function dnevnik() {
    postaviTablicuDnevnik();
    $("#pretrazi").keyup(postaviTablicuDnevnik);
    $(".tablica-hover").click({funkcija: postaviTablicuDnevnik}, sortiranjeTablice);
    $("#prva").click({funkcija: postaviTablicuDnevnik}, prva);
    $("#sljedeca").click({funkcija: postaviTablicuDnevnik}, sljedeca);
    $("#prijasnja").click({funkcija: postaviTablicuDnevnik}, prijasnja);
    $("#posljednja").click({funkcija: postaviTablicuDnevnik}, posljednja);
    $("#filter").click(filter);
}

function prva(event) {
    $("#broj_stranice").val(0);
    event.data.funkcija();
}

function sljedeca(event) {
    let trenutna = $("#broj_stranice").val();
    $("#broj_stranice").val(parseInt(trenutna) + 1);
    event.data.funkcija();
}

function prijasnja(event) {
    let trenutna = $("#broj_stranice").val();
    $("#broj_stranice").val(parseInt(trenutna) - 1);
    event.data.funkcija();
}

function posljednja(event) {
    $("#broj_stranice").val(9999999);
    event.data.funkcija();
}

function sortiranjeTablice(event) {
    let atribut_za_sortiranje = event.target.id;
    let smjer_sortiranja = ($("#smjer_sortiranja").val() == "DESC" && $("#atribut_za_sortiranje").val() == atribut_za_sortiranje) ? $("#smjer_sortiranja").val("ASC") : $("#smjer_sortiranja").val("DESC");
    $("#atribut_za_sortiranje").val(atribut_za_sortiranje);
    event.data.funkcija();
}


function filter(event) {
    postaviTablicuDnevnik();
}

function postaviTablicuDnevnik() {
    let atribut_za_sortiranje = ($("#atribut_za_sortiranje").val() != "") ? $("#atribut_za_sortiranje").val() : null;
    let smjer_sortiranja = ($("#smjer_sortiranja").val() != "") ? $("#smjer_sortiranja").val() : null;
    let broj_stranice = ($("#broj_stranice").val() != "") ? $("#broj_stranice").val() : null;
    let vrijednost_za_pretrazivanje = ($("#pretrazi").val() != "") ? $("#pretrazi").val() : null;
    let datum_od = ($("#od").val() != "") ? $("#od").val() : null;
    let datum_do = ($("#do").val() != "") ? $("#do").val() : null;
    let atribut_za_pretrazivanje = ($("#atribut_za_pretrazivanje").val() != "") ? $("#atribut_za_pretrazivanje").val() : null;
    let parametri = {datum_od: datum_od, datum_do: datum_do, atribut_za_sortiranje: atribut_za_sortiranje, smjer_sortiranja: smjer_sortiranja, broj_stranice: broj_stranice, vrijednost_za_pretrazivanje: vrijednost_za_pretrazivanje, atribut_za_pretrazivanje: atribut_za_pretrazivanje};

    $.post("skripte/dohvati_dnevnik.php", parametri, function (data) {
        $(".tablica").children("tbody").html("");
        if (data.tablica.length > 0) {
            $.each(data.tablica, function (atribut, vrijednost) {
                let tbody = "<tr>";
                tbody += "<td id='upit'>" + (vrijednost.upit == null ? "/" : vrijednost.upit) + "</td>" +
                        "<td id='radnja'>" + (vrijednost.radnja == null ? "/" : vrijednost.radnja) + "</td>" +
                        "<td id='datum_vrijeme'>" + vrijednost.datum_vrijeme + "</td>" +
                        "<td id='naziv'>" + vrijednost.naziv + "</td>" +
                        "<td id='korisnicko_ime'>" + vrijednost.korisnicko_ime + "</td>";
                tbody += "</tr>";
                $(".tablica").children("tbody").append(tbody);
            });
            $("#broj_stranice").val(data.trenutna_stranica);
        } else {
            $("tbody").append("Nema podataka");
        }

    }, "json");

}

function blokiraniKorisnici() {
    postaviTablicuBlokiraniKorisnici();
    $('body').on('click', '#blokiraj', blokiraj);
    $('body').on('click', '#odblokiraj', odblokiraj);
    $("#pretrazi").keyup(postaviTablicuBlokiraniKorisnici);
    $(".tablica-hover").click({funkcija: postaviTablicuBlokiraniKorisnici}, sortiranjeTablice);
    $("#prva").click({funkcija: postaviTablicuBlokiraniKorisnici}, prva);
    $("#sljedeca").click({funkcija: postaviTablicuBlokiraniKorisnici}, sljedeca);
    $("#prijasnja").click({funkcija: postaviTablicuBlokiraniKorisnici}, prijasnja);
    $("#posljednja").click({funkcija: postaviTablicuBlokiraniKorisnici}, posljednja);
}


function blokiraj(event) {
    let id_korisnik = $(this).parent("tr").attr("id");
    let parametri = {id_korisnik: id_korisnik};
    $.post("skripte/blokiraj_korisnika.php", parametri, function () {
        alert("Korisnik " + id_korisnik + " je blokiran");
        postaviTablicuBlokiraniKorisnici();
    }, "json");
}

function odblokiraj(event) {
    let id_korisnik = $(this).parent("tr").attr("id");
    let parametri = {id_korisnik: id_korisnik};
    $.post("skripte/odblokiraj_korisnika.php", parametri, function () {
        alert("Korisnik " + id_korisnik + " je odblokiran");
        postaviTablicuBlokiraniKorisnici();
    }, "json");
}

function postaviTablicuBlokiraniKorisnici(event) {
    let atribut_za_sortiranje = ($("#atribut_za_sortiranje").val() != "") ? $("#atribut_za_sortiranje").val() : null;
    let smjer_sortiranja = ($("#smjer_sortiranja").val() != "") ? $("#smjer_sortiranja").val() : null;
    let broj_stranice = ($("#broj_stranice").val() != "") ? $("#broj_stranice").val() : null;
    let vrijednost_za_pretrazivanje = ($("#pretrazi").val() != "") ? $("#pretrazi").val() : null;
    let atribut_za_pretrazivanje = ($("#atribut_za_pretrazivanje").val() != "") ? $("#atribut_za_pretrazivanje").val() : null;
    let parametri = {atribut_za_sortiranje: atribut_za_sortiranje, smjer_sortiranja: smjer_sortiranja, broj_stranice: broj_stranice, vrijednost_za_pretrazivanje: vrijednost_za_pretrazivanje, atribut_za_pretrazivanje: atribut_za_pretrazivanje};
    $.post("skripte/dohvati_sve_korisnike_za_blokiranje.php", parametri, function (data) {
        $(".tablica").children("tbody").html("");
        if (data.tablica.length > 0) {
            $.each(data.tablica, function (atribut, vrijednost) {
                let tbody = "<tr id='" + vrijednost.id_korisnik + "'>";
                tbody += "<td id='korisnicko_ime'>" + vrijednost.korisnicko_ime + "</td>" +
                        "<td id='ime_prezime'>" + vrijednost.ime_prezime + "</td>" +
                        "<td id='email'>" + vrijednost.email + "</td>" +
                        "<td id='broj_unosa'>" + vrijednost.broj_unosa + "</td>" +
                        ((vrijednost.blokiran == 0) ? "<td id='blokiran' style='color:#57779a'>Aktivan</td>" : "<td id='blokiran' style='color:#D84727'>Blokiran</td>") +
                        "<td id='naziv'>" + vrijednost.naziv + "</td>" +
                        ((vrijednost.blokiran == 0) ? "<td id='blokiraj' style='color: #DE3163'>BLOKIRAJ</td>" : "<td id='odblokiraj'>ODBLOKIRAJ</td>");

                tbody += "</tr>";
                $(".tablica").children("tbody").append(tbody);
            });
            $("#broj_stranice").val(data.trenutna_stranica);
        } else {
            $("tbody").append("Nema podataka");
        }

    }, "json");
}  
    
function proizvodi(){
    postaviTablicuProizvoda();
    $("#dodaj").click(otvoriProizvod);
    $("#pretrazi").keyup(postaviTablicuProizvoda);
    $("th").click({funkcija: postaviTablicuProizvoda}, sortiranjeProizvoda);
    $("#prva").click({funkcija: postaviTablicuProizvoda}, prva);
    $("#sljedeca").click({funkcija: postaviTablicuProizvoda}, sljedeca);
    $("#prijasnja").click({funkcija: postaviTablicuProizvoda}, prijasnja);
    $("#posljednja").click({funkcija: postaviTablicuProizvoda}, posljednja);
    $("body").on("click", "#obrisi", obrisiProizvod);
}

function obrisiProizvod(event) {
    let id_proizvoda = $(this).attr("id_retka");
    $.post("skripte/obrisi_proizvod.php", {id_proizvoda: id_proizvoda}, function (data) {
        postaviTablicuProizvoda();
        alert("Obrisan je proizvod " + id_proizvoda);

    }, "json");
}

function sortiranjeProizvoda(event) {
    let atribut_za_sortiranje = event.target.id;
    let smjer_sortiranja = ($("#smjer_sortiranja").val() == "DESC" && $("#atribut_za_sortiranje").val() == atribut_za_sortiranje) ? $("#smjer_sortiranja").val("ASC") : $("#smjer_sortiranja").val("DESC");
    $("#atribut_za_sortiranje").val(atribut_za_sortiranje);
    postaviTablicuProizvoda(event);
}

function postaviTablicuProizvoda(event) {
    let atribut_za_sortiranje = ($("#atribut_za_sortiranje").val() != "") ? $("#atribut_za_sortiranje").val() : null;
    let smjer_sortiranja = ($("#smjer_sortiranja").val() != "") ? $("#smjer_sortiranja").val() : null;
    let broj_stranice = ($("#broj_stranice").val() != "") ? $("#broj_stranice").val() : null;
    let vrijednost_za_pretrazivanje = ($("#pretrazi").val() != "") ? $("#pretrazi").val() : null;
    let atribut_za_pretrazivanje = ($("#atribut_za_pretrazivanje").val() != "") ? $("#atribut_za_pretrazivanje").val() : null;
    let parametri = {atribut_za_sortiranje: atribut_za_sortiranje, smjer_sortiranja: smjer_sortiranja, broj_stranice: broj_stranice, vrijednost_za_pretrazivanje: vrijednost_za_pretrazivanje, atribut_za_pretrazivanje: atribut_za_pretrazivanje};
    $.post("skripte/dohvati_proizvode.php", parametri, function (data) {
        $(".tablica").children("tbody").html("");
        if (data.tablica.length > 0) {
            $.each(data.tablica, function (atribut, vrijednost) {
                let tbody = "<tr>";
                tbody += "<td id='id_proizvoda'>" + vrijednost.id_proizvoda + "</td>" +
                        "<td id='naziv'>" + vrijednost.naziv + "</td>" +
                        "<td id='opis'>" + vrijednost.opis + "</td>" +
                        "<td id='kolicina'>" + vrijednost.kolicina + "</td>" +
                        "<td id='cijena'>" + vrijednost.cijena + "</td>" +
                        "<td id='status1'>" + vrijednost.status + "</td>" +
                        "<td id='moderator1'>" + vrijednost.moderator + "</td>" +
                        "<td class='tablica-hover'><a href='./obrasci/dodaj_novi_proizvod.php?id_proizvoda=" + vrijednost.id_proizvoda + "' style='color: #57779a;'>Ažuriraj</a></td>" +
                        "<td id='obrisi' id_retka='" + vrijednost.id_proizvoda + "' class='tablica-hover' style='color: #DE3163;'>Obriši</td>";
                tbody += "</tr>";
                $(".tablica").children("tbody").append(tbody);
            });
            $("#broj_stranice").val(data.trenutna_stranica);
        } else {
            $("tbody").append("Nema podataka");
        }
    }, "json");
}

function otvoriProizvod(event) {
    event.preventDefault();
    window.open("./obrasci/dodaj_novi_proizvod.php", "_self");
}