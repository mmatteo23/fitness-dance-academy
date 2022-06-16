var serie = document.getElementById("serie");
var ripetizioni = document.getElementById("ripetizioni");
var pausaEl = document.getElementById("pausa");
var hidden = document.getElementById("listaEs");

function addEsercizio(){
    var esercizi = document.getElementById("esercizio");
    var esNome = esercizi.options[esercizi.selectedIndex].text;
    var esID = esercizi.selectedIndex + 1;
    var tab = document.getElementById("tabExbody");
    if(validate()){
        var nSerie = serie.value;
        var nRip = ripetizioni.value;
        var pausa = pausaEl.value;
        var row = tab.insertRow();
        row.innerHTML = 
                    "<th data-title='Esercizio' scope='row'>"+esNome+"</th><td data-title='Serie'>"+nSerie+"</td><td data-title='Ripetizioni'>"+nRip+"</td><td data-title='Pausa'>"+pausa+"<abbr title='secondi'>s</abbr></td>";
        hidden.value = hidden.value + esID + ":" + nSerie + ":" + nRip + ":" + pausa + ";";
    }
}

const setError = (element, message) => {
    const inputWrapper = element.parentElement;
    const errorDisplay = inputWrapper.querySelector('.error');
    errorDisplay.setAttribute("role", "alert");
    errorDisplay.innerText = message;
}

const setSuccess = element => {
    const inputWrapper = element.parentElement;
    const errorDisplay = inputWrapper.querySelector('.error');
    errorDisplay.removeAttribute("role");
    errorDisplay.innerText = '';
};

function validate(){
    var nSerie = serie.value;
    var corretto = true;
    if(nSerie <= 0 || nSerie > 20){
        setError(serie, "Inserire un numero tra 1 e 20");
        corretto = false;
    }
    else{
        setSuccess(serie);
    }
    var nRip = ripetizioni.value;
    if(nRip <= 0 || nRip > 50){
        setError(ripetizioni, "Inserire un numero tra 1 e 50");
        corretto = false;
    }
    else{
        setSuccess(ripetizioni);
    }
    var pausa = pausaEl.value;
    if(pausa <= 0){
        setError(pausaEl, "Inserire un numero positivo");
        corretto = false;
    }
    else{
        setSuccess(pausaEl);
    }
    return corretto;
}