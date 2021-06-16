var dataP;
var dataK;
var sbutton;
var IncSum;
var ExpSum;

function connection() {
     alert("proba - komunikacja z plikiem js"); 
}

function wyborOkresu() {
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0');
    var mmPop = String(today.getMonth()).padStart(2, '0');
    var yyyy = today.getFullYear();
    
    var ele = document.getElementsByName("rodzajRaportu");
    for(i=0; i<ele.length; i++) {
        if(ele[i].checked) {
        sessionStorage.setItem("detail", ele[i].value);
        }
    } 

    if(document.getElementById("okres").value == "InnyOkres") {
        var pagebutton = document.getElementById("hiddenbutton");
        pagebutton.click();
    } else {
       if(document.getElementById("okres").value == "Aktualny") {
            var d = ostatniDzienMiesiaca(mm*1, yyyy);
            dataP = yyyy + "-" + mm + "-01";
            dataK = yyyy + "-" + mm + "-" + d;
            var miesiacP = previousMonthInWords(mm*1);
            document.getElementById("wybranyOkresSprawozdania").innerHTML = "Wybrany okres zestawienia: "+miesiacP+" "+yyyy+" r.";
            sessionStorage.setItem("okresTekst", "bieżącym miesiącu");
        }
        else if(document.getElementById("okres").value == "Poprzedni") {
            var ostatni = ostatniDzienMiesiaca(mmPop*1, yyyy);
            dataP = yyyy + "-" + (mmPop) + "-01";
            dataK = yyyy + "-" + (mmPop) + "-" + ostatni;
            var miesiacP = previousMonthInWords(mmPop*1);
            document.getElementById("wybranyOkresSprawozdania").innerHTML = "Wybrany okres zestawienia: "+miesiacP+" "+yyyy+" r.";
            sessionStorage.setItem("okresTekst", "poprzednim miesiącu");
        }
        else if(document.getElementById("okres").value == "Rok") {
            var d = ostatniDzienMiesiaca(mm*1, yyyy);
            document.getElementById("wybranyOkresSprawozdania").innerHTML = "Wybrany okres zestawienia: Rok "+yyyy;
            dataP = yyyy + "-01-01";
            dataK = yyyy + "-" + mm + "-" + d;
            sessionStorage.setItem("okresTekst", "bieżącym roku");
        }
            document.getElementById("DPocz").value = dataP;
            document.getElementById("DKonc").value = dataK;
            sessionStorage.setItem("wybor", document.getElementById("okres").value);
            sessionStorage.setItem("timeB", dataP);
            sessionStorage.setItem("timeE", dataK);  
            sbutton = document.getElementById("statementButton");
            sbutton.click();
    }
}

 function wynik() {
        if(sessionStorage.getItem("wybor") == null) {
            document.getElementById("okres").value = "Aktualny";
        } else {
            document.getElementById("okres").value = sessionStorage.getItem("wybor");
        }        
       
        if (sessionStorage.getItem("timeB") == null) {
            var mm = String(today.getMonth() + 1).padStart(2, '0');
            var yyyy = today.getFullYear();
            var d = ostatniDzienMiesiaca(mm*1, yyyy);
            dataP = yyyy + "-" + mm + "-01";
            dataK = yyyy + "-" + mm + "-" + d;
            sessionStorage.setItem("timeB", dataP);
            sessionStorage.setItem("timeE", dataK);  
        }
        var x = sessionStorage.getItem("timeB");
        var y = sessionStorage.getItem("timeE");      
        var k = IncSum;       
        var l = ExpSum;  
        var z = k - l;   
        z = new Intl.NumberFormat('pl', {style: 'decimal', minimumFractionDigits: 2, userGrouping: true}).format(z);
        
        var o = sessionStorage.getItem("okresTekst");

        document.getElementById("pasekOkresu").innerHTML = "ZESTAWIENIE ZA OKRES: "+x+" - "+y;
        document.getElementById("wynikFinansowy").innerHTML = "Całkowity bilans: "+z+" zł";             
        
        if((k-l) < 0) {
            document.getElementById("kolorWynikuFinansowego").setAttribute("class", "bg-danger py-2 px-4 mt-3 mb-3");
            document.getElementById("komWynikuFinansowego").innerHTML = "Uwaga! W "+o+" twoje wydatki przekroczyły przychody!";
        } else if ((k-l) == 0 && k==0 && l==0) {sessionStorage.setItem("stan", "wejscie");
            document.getElementById("kolorWynikuFinansowego").setAttribute("class", "bg-primary py-2 px-4 mt-3 mb-3");
            document.getElementById("komWynikuFinansowego").innerHTML = "W "+o+" nie było żadnych przychodów i wydatków!";
        }  else if ((k-l) == 0) {
            document.getElementById("kolorWynikuFinansowego").setAttribute("class", "bg-success py-2 px-4 mt-3 mb-3");
            document.getElementById("komWynikuFinansowego").innerHTML = "W "+o+" przychody były równe wydatkom!";
        } else if ((k-l) >0) {
            document.getElementById("kolorWynikuFinansowego").setAttribute("class", "bg-success py-2 px-4 mt-3 mb-3");
            document.getElementById("komWynikuFinansowego").innerHTML = "Gratulacje! Bardzo dobrze zarządzasz swoimi finansami!";
        }   
        
        if(sessionStorage.getItem("detail")=="szczegoly") 
            document.getElementById("radioSzczegol").checked = true;
        else if (sessionStorage.getItem("detail")=="podsumowania")
            document.getElementById("radioSuma").checked = true;
}

function zamkniecieModal() {
    dataP = document.getElementById("dateB").value;
    dataK = document.getElementById("dateE").value;
    if(dataK<dataP){
        var dataT = dataP;
        dataP = dataK;
        dataK = dataT;
    }
    document.getElementById("wybranyOkresSprawozdania").innerHTML = "Wybrany okres zestawienia: "+dataP+" - "+dataK;
    document.getElementById("DPocz").value = dataP;
    document.getElementById("DKonc").value = dataK;
    sessionStorage.setItem("timeB", dataP);
    sessionStorage.setItem("timeE", dataK);
    sessionStorage.setItem("wybor", document.getElementById("okres").value);
    document.getElementById("wybranyOkresSprawozdania").innerHTML = "Wybrany okres zestawienia: "+sessionStorage.getItem("timeB")+" - "+sessionStorage.getItem("timeE"); 
    sessionStorage.setItem("okresTekst", "wybranym okresie");

    sbutton = document.getElementById("statementButton");
    sbutton.click();    
}

function previousMonthInWords(m){
    var nazwaMiesiaca;
    switch(m) {
        case 0: nazwaMiesiaca = "GRUDZIEŃ"; break;
        case 1: nazwaMiesiaca = "STYCZEŃ"; break;
        case 2: nazwaMiesiaca = "LUTY"; break;
        case 3: nazwaMiesiaca = "MARZEC"; break;
        case 4: nazwaMiesiaca = "KWIECIEŃ"; break;
        case 5: nazwaMiesiaca = "MAJ"; break;
        case 6: nazwaMiesiaca = "CZERWIEC"; break;
        case 7: nazwaMiesiaca = "LIPIEC"; break;
        case 8: nazwaMiesiaca = "SIERPIEŃ"; break;
        case 9: nazwaMiesiaca = "WRZESIEŃ"; break;
        case 10: nazwaMiesiaca = "PAŹDZIERNIK"; break;
        case 11: nazwaMiesiaca = "LISTOPAD"; break;
    }
    return nazwaMiesiaca;
}

function ostatniDzienMiesiaca(m, y) {
    var LiczbaDni;
    switch(m) {
        case 0: LiczbaDni = 31; break;
        case 1: LiczbaDni = 31; break;
        case 2: 
            var d = checkLeapYear(y);
            LiczbaDni = d;
            break;
        case 3: LiczbaDni = 31; break;
        case 4: LiczbaDni = 30; break;
        case 5: LiczbaDni = 31; break;
        case 6: LiczbaDni = 30; break;
        case 7: LiczbaDni = 31; break;
        case 8: LiczbaDni = 31; break;
        case 9: LiczbaDni = 30; break;
        case 10: LiczbaDni = 31; break;
        case 11: LiczbaDni = 30; break;
    };
    return LiczbaDni;
}

function checkLeapYear(year) {
    var dni;
    if ((0 == year % 4) && (0 != year % 100) || (0 == year % 400)) {
        dni = 29;
    } else {
        dni = 28;
    }
    return dni;
}

function usuwanieKomentarza() {    
    document.getElementById("kom_amount").innerHTML = "";
    document.getElementById("kom_date").innerHTML = "";
    document.getElementById("kom_success").innerHTML = "";
}

// function start() {
//     if (sessionStorage.getItem("powtorz") == null) { 
//         dataP = sessionStorage.getItem("timeB");
//         dataK = sessionStorage.getItem("timeE");
//         document.getElementById("DPocz").value = dataP;
//         document.getElementById("DKonc").value = dataK; 

//         if(sessionStorage["wybor"] == undefined) {
//             document.getElementById("okres").value = "Aktualny"
//             sessionStorage.setItem("wybor", document.getElementById("okres").value);
//             wyborOkresu();  
//         } else {
//             document.getElementById("okres").value = sessionStorage.getItem("wybor");
//             dataP = sessionStorage.getItem("timeB");
//             dataK = sessionStorage.getItem("timeE");
//             document.getElementById("DPocz").value = dataP;
//             document.getElementById("DKonc").value = dataK;    
//             wynik();
//         }
//         sessionStorage.setItem("powtorz", "NIE");
//     } 
// }
