function validateImage(imageId) {
    const fileInput = document.getElementById(imageId)
    const alt_img = document.getElementsByClassName("alt_img")[0]

    var file = fileInput.files[0];
    let error = false;

    if (file) {
        
        if(file.size > 2097152) {
            setError(fileInput, 'Il file caricato eccede il limite di 2 MB, riprova.')
            error = true;
        }

        var t = file.type.split('/').pop().toLowerCase();

        if (t != "jpeg" && t != "jpg" && t != "png" && t != "bmp" && t != "gif") {
            setError(fileInput, 'Carica un\'immagine valida (jpeg/jpg/png/bmp/gif)')
            error = true;
        }
    
    } 

    if (error) {
        if (alt_img) {
            alt_img.style.display = "none";
        }
        return false
    } else {
        setSuccess(fileInput)
        if (alt_img) {
            alt_img.style.display = "flex";
        }

        return true;
    }
}