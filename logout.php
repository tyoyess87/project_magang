<?php

if (isset($_POST['btn_logout'])) {
    session_start();
    session_destroy();
    header( "Location: index.php");
}

?>