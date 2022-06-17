const formLogin = document.getElementById('formLogin');
const email = document.getElementById('email');
const password = document.getElementById('password');

formLogin.addEventListener('submit', e => {
    e.preventDefault();

    var validForm = validateInputs();
    
    if(validForm)
        formLogin.submit()
});

const setError = (element, message) => {
    const inputWrapper = element.parentElement;
    const errorDisplay = inputWrapper.querySelector('.error');

    errorDisplay.setAttribute("role", "alert");
    errorDisplay.innerText = message;
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

function validateEmail(){
    const emailValue = email.value.trim();    // trim remove white space
    
    if(emailValue == '') {
        setError(email, 'Email is required')
        return false
    } else {
        setSuccess(email)
    }

    return true
}

function validatePassword() {
    const passwordValue = password.value.trim();

    if(passwordValue == '') {
        setError(password, 'Password is required')
        return false
    } else {
        setSuccess(password);
    }

    return true
}

function validateInputs () {    

    var validInput = true
 
    validInput = (validInput & validateEmail())
    validInput = (validInput & validatePassword())

    return validInput
}