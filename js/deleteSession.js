function deleteSession(id){

    var data = new FormData();
    data.append("sessione", id);

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../php/deleteSession.php");
    xhr.onload = function(){
        console.log(this.response);
    }
    xhr.send(data);

    var element = document.getElementById("sess"+id);
    element.parentNode.removeChild(element);
    
    return false;
}

function showModal(id){
    var row = document.getElementById("sess"+id).children[3];
    console.log(row.children);
    row.children[0].style.display = 'block';
    row.children[1].style.display = 'block';
    row.children[2].style.display = 'none';
}

function hideModal(id){
    var row = document.getElementById("sess"+id).children[3];
    row.children[0].style.display = 'none';
    row.children[1].style.display = 'none';
    row.children[2].style.display = 'block';
}