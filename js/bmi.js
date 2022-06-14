function getBMI(){
    var height = document.getElementById("text-altezza").value;
    var errorString = "";
    if(height.includes(","))
        height = height.replace(",", ".");
    height = parseInt(height);
    if(isNaN(height)){
        errorString = "Errore inserimento altezza";
    }
    var weight = document.getElementById("text-peso").value;
    if(weight.includes(","))
        weight = weight.replace(",", ".");
    weight = parseFloat(weight);
    if(isNaN(weight)){
        if(errorString=="")
            errorString = "Errore inserimento peso";
        else
            errorString += " e peso";
    }
    if(errorString == ""){
        height = height / 100;
        height = height * height;
        var bmi = weight / height;
        bmi = Math.round(bmi * 100) / 100;
        var stato = "";
        if(bmi <= 18.5)
            stato = "sottopeso";
        else if(bmi < 25)
            stato = "peso forma";
        else if(bmi < 30)
            stato = "sovrappeso";
        else if(bmi < 35)
            stato = "obeso";
        else
            stato = "estremamente obeso";
        document.getElementById("bmi-result").innerHTML = "Risultato: " + bmi + " (" + stato + ")"; 
    }
    else{
        document.getElementById("bmi-result").innerHTML = errorString;
    }

}