var params = [];
var url = location.search.substring(1).split("&");
for (var index = 0; index < url.length; index++) {
    params.push(url[index].split("="));
}

// params is an associative array with
// [ key => value ]

params.forEach(setInputValues);

function setInputValues(item, index, arr) {
    var input = document.getElementsByTagName("input");

    for(var i = 0; i < params.length; i++){
        for(var j = 0; j < input.length; j++){
            if(input[j].name == params[i][0])
                input[j].value = params[i][1];
        }
    }
    
    console.log(input[1].name);
}
