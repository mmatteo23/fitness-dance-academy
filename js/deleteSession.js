function deleteSession(id){

    var data = new FormData();
    data.append("sessione", id);

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "php/deleteSession.php");
    xhr.onload = function(){
        console.log(this.response);
    }
    xhr.send(data);

    var element = document.getElementById("sess"+id);
    console.log("sto provando ad eliminare sess"+id);
    element.parentNode.removeChild(element);
    
    return false;
}