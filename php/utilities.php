<?php

    function append_filters(array $filters, array $table_fields){
        $partial_query = "";
        
        foreach($filters as $key => $value){
            if(in_array($key, $table_fields) && $value != ""){
                // if there is at least a valid parameter I insert the keyword where
                if($partial_query === "") $partial_query .= " WHERE";
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

?>