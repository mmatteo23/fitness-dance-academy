let changes = false;

form = document.getElementsByTagName('form');
form = form[0];

// nel caso della creazione scheda le modifiche sono fatte con il js
// non direttamente su un form => devo controllare queste input
inputs = document.getElementById('creaSchedaInput');

if(inputs){
    inputs.addEventListener("input", function () {
        changes = true;
    });
}


form.addEventListener("input", function () {
    changes = true;
});

window.addEventListener('beforeunload', function (e) {
    //console.log(e, e.type)
    // Check if any of the input was changed
    if (changes && document.activeElement.id != 'creaBtn') {
        // Cancel the event and
        // show alert that the unsaved
        // changes would be lost
        e.preventDefault();
        e.returnValue = 'Stop';
    }
});