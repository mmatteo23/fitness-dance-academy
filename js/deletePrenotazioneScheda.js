function deletePrenotazioneScheda(id){

    var data = new FormData();
    data.append("prenScheda", id);

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "php/deletePrenotazioneScheda.php");
    xhr.onload = function(){
        console.log(this.response);
    }
    xhr.send(data);

    var element = document.getElementById("scheda"+id);
    element.parentNode.removeChild(element);
    
    return false;
}