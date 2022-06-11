<?php

session_start();

if(!isset($_SESSION['email']) || $_SESSION['email'] == ''){    // the user is authorized
    header("location: /login.php");
}

?>