var date = new Date();
var date_end = addMinutes(date, 30);

var day         = date.getDate(),
    tomorrow    = date.getDate() + 1,
    month       = date.getMonth() + 1,
    year        = date.getFullYear(),
    maggiorenne = year-18,
    hour1       = date.getHours(),
    min1        = date.getMinutes(),
    hour2       = date_end.getHours(),
    min2        = date_end.getMinutes();

month = (month < 10 ? "0" : "") + month;
day = (day < 10 ? "0" : "") + day;
tomorrow = (tomorrow < 10 ? "0" : "") + tomorrow;
hour1 = (hour1 < 10 ? "0" : "") + hour1;
min1 = (min1 < 10 ? "0" : "") + min1;
hour2 = (hour2 < 10 ? "0" : "") + hour2;
min2 = (min2 < 10 ? "0" : "") + min2;

var today           = year + "-" + month + "-" + day,
    tomorrow        = year + "-" + month + "-" + tomorrow,
    maggiorenne     = maggiorenne + "-" + month + "-" + day,
    displayTime     = hour1 + ":" + min1,
    displayTime2    = hour2 + ":" + min2;
  
if(document.getElementById('data')) {
    document.getElementById('data').value=today;
}
if(document.getElementById('data_nascita')) {
    document.getElementById('data_nascita').value=maggiorenne;
}
if(document.getElementById('data_inizio')) {
    document.getElementById('data_inizio').value=today;
}
if(document.getElementById('data_fine')) {
    document.getElementById('data_fine').value=tomorrow;
}
if(document.getElementById('ora_inizio')) {
    document.getElementById('ora_inizio').value=displayTime;
}
if(document.getElementById('ora_fine')) {
    document.getElementById('ora_fine').value=displayTime2;
}

function addMinutes(date, minutes) {
    return new Date(date.getTime() + minutes*60000);
}