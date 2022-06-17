const formCrea = document.getElementById('formCreaProfilo');
const formEdit = document.getElementById('formEditProfilo');
const nome = document.getElementById('nome');
const cognome = document.getElementById('cognome');
const email = document.getElementById('email');
const dataNascita = document.getElementById('data_nascita');
const telefono = document.getElementById('telefono');
const sesso = document.getElementById('sesso');
const altezza = document.getElementById('altezza');
const peso = document.getElementById('peso');
const password1 = document.getElementById('password');
const password2 = document.getElementById('Rpassword');

const ReNome = /^[a-zA-Z\s-]+$/;
const ReEmail = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/;
const ReTelefono = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im;

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
        validaNome() & validaCognome() &
        validaDataNascita() &
        validaTelefono() & validaAltezza() &
        validaPeso() & validaPassword() & validateImage("profile-img")
    )
    
    if(!email.disabled)
        validInput = validInput & validaEmail();

    return validInput
}

const setError = (element, message) => {
    const inputWrapper = element.parentElement;
    const errorDisplay = inputWrapper.querySelector('.error');

    errorDisplay.innerText = message;
    errorDisplay.setAttribute("role", "alert");
    inputWrapper.classList.add('error');
    inputWrapper.classList.remove('success');
}

const setSuccess = element => {
    const inputWrapper = element.parentElement;
    const errorDisplay = inputWrapper.querySelector('.error');

    errorDisplay.removeAttribute("role");
    errorDisplay.innerText = '';
    inputWrapper.classList.add('success');
    inputWrapper.classList.remove('error');
};

function validaNome(){
    var nomeV = nome.value.trim();
    if(nomeV==""){
        setError(nome, "Il campo 'nome' va inserito");
        return false
    }
    else if(!ReNome.test(nomeV)){
        setError(nome, "Il campo 'nome' contiene caratteri non validi");
        return false
    }
    else{
        setSuccess(nome);
        return true
    }
}

function validaCognome(){
    var cognomeV = cognome.value.trim();
    if(cognomeV==""){
        setError(cognome, "Il campo 'cognome' va inserito");
        return false
    }
    else if(!ReNome.test(cognomeV)){
        setError(cognome, "Il campo 'cognome' contiene caratteri non validi");
        return false
    }
    else{
        setSuccess(cognome);
        return true
    }
}

function validaEmail(){
    var emailV = email.value.trim();
    if(emailV==""){
        setError(email, "Il campo 'email' va inserito");
        return false
    }
    else if(!ReEmail.test(emailV)){
        setError(email, "Il campo 'email' non è valido");
        return false
    }
    else{
        setSuccess(email);
        return true
    }
}

function validaDataNascita(){
    var data_inserita = dataNascita.value.trim();
    data_inserita = new Date(data_inserita)

    var date = new Date();

    var day         = date.getDate() + 1,
        month       = date.getMonth() + 1,
        year        = date.getFullYear(),
        maggiorenne = year-18

    maggiorenne = maggiorenne + "-" + month + "-" + day
    maggiorenne = new Date(maggiorenne)

    let minorenne = data_inserita>=maggiorenne

    if(data_inserita = ""){
        setError(dataNascita, "Il campo 'data di nascita' va inserito");
        return false
    }
    else if(minorenne){
        setError(dataNascita, "Per poterti iscrivere devi essere maggiorenne.");
        return false
    }
    else{
        setSuccess(dataNascita);
        return true
    }
}

function validaTelefono(){
    var telefonoV = telefono.value.trim();
    if(!ReTelefono.test(telefonoV) && telefonoV!=""){
        setError(telefono, "Il campo 'telefono' non è valido");
        return false
    }
    else{
        setSuccess(telefono);
        return true
    }
}

function validaAltezza(){
    var altezzaV = altezza.value.trim();
    if((altezzaV<55 || altezzaV>250) && altezzaV != ""){
        setError(altezza, "Il campo 'altezza' non è valido");
        return false
    }
    else{
        setSuccess(altezza);
        return true
    }
}

function validaPeso(){
    var pesoV = peso.value.trim();
    if((pesoV<30 || pesoV>300) && pesoV != ""){
        setError(peso, "Il campo 'peso' non è valido");
        return false
    }
    else{
        setSuccess(peso);
        return true
    }
}

function validaPassword(){
    var password1V = password1.value.trim();
    var password2V = password2.value.trim();

    if (password1V=="") {
        setError(password1, "Il campo 'password' va inserito");
        return false
    }
    else if((password1V != password2V) && password1V){
        setSuccess(password1);
        setError(password2, "Attenzione, Le due password devono combaciare!");
        return false
    }
    else{
        setSuccess(password1);
        setSuccess(password2);
        return true
    }
}

