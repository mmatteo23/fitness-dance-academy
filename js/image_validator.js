function validateImage(imageId) {
    const fileInput = document.getElementById(imageId)

    var file = fileInput.files[0]

    if (file) {
        
        if(file.size > 2097152) {
            setError(fileInput, 'Il file caricato eccede il limite di 2 MB, riprova.')
            return false
        }

        var t = file.type.split('/').pop().toLowerCase();

        if (t != "jpeg" && t != "jpg" && t != "png" && t != "bmp" && t != "gif") {
            setError(fileInput, 'Carica un\'immagine valida (jpeg/jpg/png/bmp/gif)')
            return false
        }
    
    } 

    setSuccess(fileInput)
    return true
}