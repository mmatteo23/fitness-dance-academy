function validateImage() {
    const fileInput = document.getElementById('profile_img')
    var file = document.getElementById("profile_img").files[0];

    var t = file.type.split('/').pop().toLowerCase();
    if (t != "jpeg" && t != "jpg" && t != "png" && t != "bmp" && t != "gif") {
        setError(fileInput, 'Please select a valid image file')
        document.getElementById("profile_img").value = '';
        return false;
    }

    setSuccess(fileInput)
    return true;
}