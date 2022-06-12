const form = document.getElementById('loginForm');
const nome = document.getElementById('nome');
const cognome = document.getElementById('cognome');
const dataNascita = document.getElementById('dataNascita');
const telefono = document.getElementById('telefono');
const sesso = document.getElementById('sesso');
const altezza = document.getElementById('altezza');
const peso = document.getElementById('peso');
const password = document.getElementById('password');
const password2 = document.getElementById('Rpassword');

const ReNome = /^[a-zA-Z\s-]+$/;
const ReEmail = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/;

const setError = (element, message) => {
    const inputWrapper = element.parentElement;
    const errorDisplay = inputWrapper.querySelector('.error');

    errorDisplay.innerText = message;
    //inputWrapper.classList.add('error');
    //inputWrapper.classList.remove('success');
}

const setSuccess = element => {
    const inputWrapper = element.parentElement;
    const errorDisplay = inputWrapper.querySelector('.error');

    errorDisplay.innerText = '';
    inputWrapper.classList.add('success');
    inputWrapper.classList.remove('error');
};

function validaNome(){
    var nomeV = nome.value.trim();
    if(nomeV==""){
        setError(nome, "Il campo 'nome' va inserito");
    }
    else if(!ReNome.test(nomeV)){
        setError(nome, "Il campo 'nome' contiene caratteri non validi");
    }
    else{
        setSuccess(nome);
    }
}

function validaCognome(){
    var cognomeV = cognome.value.trim();
    if(cognomeV==""){
        setError(cognome, "Il campo 'cognome' va inserito");
    }
    else if(!ReNome.test(cognomeV)){
        setError(cognome, "Il campo 'cognome' contiene caratteri non validi");
    }
    else{
        setSuccess(cognome);
    }
}

function validaEmail(){
    var emailV = email.value.trim();
    if(emailV==""){
        setError(email, "Il campo 'email' va inserito");
    }
    else if(!ReEmail.test(emailV)){
        setError(email, "Il campo 'email' non è valido");
    }
    else{
        setSuccess(email);
    }
}

// function validaImg() {
//     console.log("NON VALI NIENTE")
// }