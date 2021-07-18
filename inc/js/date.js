<!-- Hide from old browser
function monthName(m) {
        if (m==0) return "Januari"
        else if (m==1) return "Februari"
        else if (m==2) return "Maret"
        else if (m==3) return "April"
        else if (m==4) return "Mei"
        else if (m==5) return "Juni"
        else if (m==6) return "Juli"
        else if (m==7) return "Agustus"
        else if (m==8) return "September"
        else if (m==9) return "Oktober"
        else if (m==10) return "November"
        else if (m==11) return "Desember"
}

function dayName(x) {
        if (x==0) return "Minggu"
        else if (x==1) return "Senin"
        else if (x==2) return "Selasa"
        else if (x==3) return "Rabu"
        else if (x==4) return "Kamis"
        else if (x==5) return "Jumat"
        else if (x==6) return "Sabtu"
}

var d = new Date()
var today = ""

today = dayName(d.getDay()) + ", " + d.getDate() + " " + monthName(d.getMonth()) + " " + d.getFullYear();
// end hiding -->

/*
Usage:
document.write (dayName(d.getDay()), ", ", monthName(d.getMonth()),
        " ", d.getDate(), ", ", 1900 + d.getYear())
*/
