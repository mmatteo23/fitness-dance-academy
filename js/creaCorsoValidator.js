const formCrea = document.getElementById('formCreaCorso');
const formEdit = document.getElementById('formModificaCorso');
const titolo = document.getElementById('titolo');
const descrizione = document.getElementById('descrizione');
const data_inizio = document.getElementById('data_inizio');
const data_fine = document.getElementById('data_fine');
const alt_copertina = document.getElementById('alt_copertina');

const ReTitolo = /^[a-zA-ZÀ-ÿ\s-]+$/;
const ReDescrizione = /^[a-zA-ZÀ-ÿ\s\.\,\!\"\&\*\#\:-]+$/;

formCrea ?
    form = formCrea
:
    form = formEdit

form.addEventListener('submit', e => {
    e.preventDefault();
    
    var validForm = validateInputs();
    
    if(validForm)
        form.submit()
});

function validateInputs() {    

    var validInput = true
 
    validInput = (
        validInput & 
        validaTitolo() & validaDescrizione() &
        validaDate() & validaAltImmagine()
    )

    return validInput
}

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
        return false
    }
    else if(!ReTitolo.test(titoloV)){
        setError(titolo, "Il campo 'titolo' contiene caratteri non validi");
        return false
    }
    else{
        setSuccess(titolo);
        return true
    }
}

function validaDescrizione(){
    var descrizioneV = descrizione.value.trim();
    if(descrizioneV==""){
        setError(descrizione, "Il campo 'descrizione' va inserito");
        return false
    }
    else if(!ReDescrizione.test(descrizioneV)){
        setError(descrizione, "Il campo 'descrizione' contiene caratteri non validi");
        return false
    }
    else{
        setSuccess(descrizione);
        return true
    }
}

function validaDate(){
    let dataInizio = new Date(data_inizio.value)
    let dataFine = new Date(data_fine.value)
    
    if(dataInizio==""){
        setError(data_inizio, "Il campo 'data di inizio' va inserito");
        return false
    }
    if(dataFine==""){
        setError(data_fine, "Il campo 'data di fine' va inserito");
        return false
    }
    if(!(dataInizio<dataFine)){
        setError(data_inizio, "La data di inizio deve precedere la data di fine");
        setError(data_fine, "La data di inizio deve precedere la data di fine");
        return false
    }
    else{
        setSuccess(data_inizio);
        setSuccess(data_fine);
        return true
    }
}

function validaAltImmagine() {
    var alt_copertinaV = alt_copertina.value.trim();
    if(alt_copertinaV==""){
        setError(alt_copertina, "Il campo 'descrizione copertina' va inserito");
        return false
    }
    else if(!ReDescrizione.test(alt_copertinaV)){
        setError(alt_copertina, "Il campo 'descrizione copertina' contiene caratteri non validi");
        return false
    }
    else{
        setSuccess(alt_copertina);
        return true
    }
}
