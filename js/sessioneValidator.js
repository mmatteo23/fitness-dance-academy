var date = document.getElementById("data");
var oraI = document.getElementById("ora_inizio");
var oraF = document.getElementById("ora_fine");
var submit = document.getElementById("prenota");

var dateValid = true;
var oraIValid = true;
var oraFValid = true;

const setError = (element, message) => {
    const inputWrapper = element.parentElement;
    const errorDisplay = inputWrapper.querySelector('.error');

    errorDisplay.innerText = message;

    var submit = document.getElementById("prenota");
    submit.disabled = !(dateValid && oraIValid && oraFValid);
}

const setSuccess = element => {
    const inputWrapper = element.parentElement;
    const errorDisplay = inputWrapper.querySelector('.error');

    errorDisplay.innerText = '';

    var submit = document.getElementById("prenota");
    submit.disabled = !(dateValid && oraIValid && oraFValid);
};

function validateDOW(date, time){
    var dow = (new Date(Date.parse(date))).getDay();
    console.log(dow);
    if(dow === 0){ 
        return false;
    } 
    if(dow === 6 && (time < "11:00" || time > "16:30")){
        return false;
    }
    if(time < "10:00" || time > "22:30"){
        return false;
    }
    return true;
}

function validateDate(){
    var date = document.getElementById("data");
    var dateV = date.value;
    var oggi = new Date();
    if(new Date(dateV)<oggi.setHours(0,0,0,0)){
        dateValid = false;
        setError(date, "Non puoi andare indietro nel tempo");
    }
    else{
        dateValid = true;
        setSuccess(date);
    }
    validateOraI();
}

function validateOraI(){
    var oraI = document.getElementById("ora_inizio");
    var oraIV = oraI.value;
    var date = document.getElementById("data");
    var dateV = date.value;
    var today = new Date();
    var mese = today.getMonth()+1;
    var oggi = today.getFullYear()+'-'+(mese>10?mese:"0"+mese)+'-'+today.getDate();
    var adesso = today.getHours() + ":" + today.getMinutes();
    if(dateV == oggi && oraIV < adesso){
        oraIValid = false;
        setError(oraI, "Il momento è passato");
    }
    else if(validateDOW(dateV, oraIV)===false){
        oraIValid = false;
        setError(oraI, "la palestra è chiusa a quell'ora");
    }
    else{
        oraIValid = true;
        setSuccess(oraI);
    }
    validateOraF();
}

function validateOraF(){
    var date = document.getElementById("data");
    var dateV = date.value;
    var oraF = document.getElementById("ora_fine");
    var oraFV = oraF.value;
    var oraI = document.getElementById("ora_inizio");
    var oraIV = oraI.value;
    if(oraIV>=oraFV){
        oraFValid = false;
        setError(oraF, "La sessione non può finire prima che inizi");
    }
    else if(!validateDOW(dateV, oraFV)){
        oraFValid = false;
        setError(oraF, "la palestra è chiusa a quell'ora");
    }
    else{
        oraFValid = true;
        setSuccess(oraF);
    }
}