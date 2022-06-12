const titolo = document.getElementById('titolo');
const descrizione = document.getElementById('descrizione');
const data_inizio = document.getElementById('data_inizio');
const data_fine = document.getElementById('data_fine');
const alt_copertina = document.getElementById('alt_copertina');

const ReTitolo = /^[a-zA-Z\s-]+$/;
const ReDescrizione = /^[a-zA-Z\s\.\,\!\"\&\*\#-]+$/;
const ReEmail = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/;

const setError = (element, message) => {
    const inputWrapper = element.parentElement;
    const errorDisplay = inputWrapper.querySelector('.error');

    errorDisplay.innerText = message;
    inputWrapper.classList.add('error');
    inputWrapper.classList.remove('success');
}

const setSuccess = element => {
    const inputWrapper = element.parentElement;
    const errorDisplay = inputWrapper.querySelector('.error');

    errorDisplay.innerText = '';
    inputWrapper.classList.add('success');
    inputWrapper.classList.remove('error');
};

function validaTitolo(){
    var titoloV = titolo.value.trim();
    if(titoloV==""){
        setError(titolo, "Il campo 'titolo' va inserito");
    }
    else if(!ReTitolo.test(titoloV)){
        setError(titolo, "Il campo 'titolo' contiene caratteri non validi");
    }
    else{
        setSuccess(titolo);
    }
}

function validaDescrizione(){
    var descrizioneV = descrizione.value.trim();
    if(descrizioneV==""){
        setError(descrizione, "Il campo 'descrizione' va inserito");
    }
    else if(!ReDescrizione.test(descrizioneV)){
        setError(descrizione, "Il campo 'descrizione' contiene caratteri non validi");
    }
    else{
        setSuccess(descrizione);
    }
}

function validaDate(){
    let dataInizio = new Date(data_inizio.value)
    let dataFine = new Date(data_fine.value)
    
    if(dataInizio==""){
        setError(data_inizio, "Il campo 'data di inizio' va inserito");
    }
    if(dataFine==""){
        setError(data_fine, "Il campo 'data di fine' va inserito");
    }
    if(!(dataInizio<dataFine)){
        setError(data_inizio, "La data di inizio deve precedere la data di fine");
        setError(data_fine, "La data di inizio deve precedere la data di fine");
    }
    else{
        setSuccess(data_inizio);
        setSuccess(data_fine);
    }
}

function validaAltImmagine() {
    const fileInput = document.getElementById("copertina")
    var file = fileInput.files[0];
    if (file) {
        var alt_copertinaV = alt_copertina.value.trim();
        if(alt_copertinaV==""){
            setError(alt_copertina, "Il campo 'descrizione copertina' va inserito");
        }
        else if(!ReDescrizione.test(alt_copertinaV)){
            setError(alt_copertina, "Il campo 'descrizione copertina' contiene caratteri non validi");
        }
        else{
            setSuccess(alt_copertina);
        }
    }
}
