<?php

    // isIndex == TRUE se chiamata da model->index()
    function append_filters(array $filters, array $table_fields, bool $isIndex = true){
        $partial_query = "";
        
        foreach($filters as $key => $value){
            if(in_array($key, $table_fields) && $value != ""){
                // if there is at least a valid parameter I insert the keyword where
                if($partial_query === "" && $isIndex) $partial_query .= " WHERE";
                else $partial_query .= " AND";
                if($value){
                    if (gettype($value) == "string") {
                        $partial_query .= " " . $key . " LIKE '%" . $value . "%'";
                    } else {
                        $partial_query .= " " . $key . " = " . $value;
                    }
                }
            }
        }

        return $partial_query;
    }

    // $idInputFrom è l'id nel campo img del form
    function checkAndUploadImage(string $target_dir, string $idInputForm, string $img_name, string $defaultImage) {
        $image = "";
        $errors = "";
        
        if(isset($_FILES[$idInputForm]) && $_FILES[$idInputForm]['name']){

            $target_file = $target_dir . basename($_FILES[$idInputForm]["name"]);
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            $image = $img_name . "." . $imageFileType;

            $maxsize    = 2097152;  // about 2 MB
            $acceptable = array(
                'image/jpeg',
                'image/jpg',
                'image/gif',
                'image/png'
            );

            if(($_FILES[$idInputForm]['size'] >= $maxsize) || ($_FILES[$idInputForm]["size"] == 0)) {
                $errors .= '<li class="error">File too large. File must be less than 2 MB.</li>';
            }

            if((!in_array($_FILES[$idInputForm]['type'], $acceptable)) && (!empty($_FILES["uploaded_file"]["type"]))) {
                $errors .= '<li class="error">Invalid file type. Only JPG, GIF and PNG types are accepted.</li>';
            }

            if($errors == "") {
                move_uploaded_file($_FILES[$idInputForm]["tmp_name"], $target_dir . $image);
            }

        } else {
            if ($defaultImage != "")
                $image = $defaultImage;
            else 
                $errors = "Image is required";
        }
        
        return [$image, $errors];
    }

    function preventMaliciousCode (array &$inputs) {
        foreach ($inputs as &$in){
            $in = preg_replace('/<[^>]*>/', '', $in);  
        }
    }

?>