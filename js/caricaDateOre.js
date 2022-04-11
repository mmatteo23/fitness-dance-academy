var mesi = ["gennaio", "febbraio", "marzo", "aprile", "maggio", "giugno", "luglio", "agosto", "settembre", "ottobre", "novembre", "dicembre"];

function caricaDateOre(){
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0');
    var yyyy = today.getFullYear();
    var htmlCode = "";
    var n = numeroDiGiorni(mm, yyyy);
    for(var i = 1; i <= n; i++){
        if(i==parseInt(dd))
            htmlCode += "<option value='" + i + "' selected>" + String(i).padStart(2, '0') + "</option>";
        else
            htmlCode += "<option value='" + i + "'>" + String(i).padStart(2, '0') + "</option>";
    }
    document.getElementById("giornoSessione").innerHTML = htmlCode;

    htmlCode = "";
    for(var i = 0; i < 12; i++){
        if(i+1==parseInt(mm))
            htmlCode += "<option value='" + (i + 1) + "' selected>" + mesi[i] + "</option>";
        else
            htmlCode += "<option value='" + (i + 1) + "'>" + mesi[i] + "</option>";
    }
    document.getElementById("meseSessione").innerHTML = htmlCode;

    var h = today.getHours();
    if(h>=10 && h<=22){
        document.getElementById("oraInizio").selectedIndex = h-10;
        document.getElementById("oraFine").selectedIndex = h-10;
        document.getElementById("minutoFine").selectedIndex = 2;
    }
}

function meseCambiato(){
    var e = document.getElementById("meseSessione");
    var mm = e.options[e.selectedIndex].value;
    console.log(mm);
    mm = String(mm).padStart(2, '0');
    var n = numeroDiGiorni(mm, new Date().getFullYear());
    var htmlCode = "";
    for(var i = 1; i <= n; i++){
        htmlCode += "<option value='" + i + "'>" + String(i).padStart(2, '0') + "</option>";
    }
    document.getElementById("giornoSessione").innerHTML = htmlCode;
}

function numeroDiGiorni(mm, yyyy){
    var n = 31;
    if(mm == "11" || mm == "04" || mm == "06" || mm == "09")
        n = 30;
    else if(mm == "02"){
        n = 28;
        if(yyyy%4==0)
            n = 29;
    }
    return n;
}