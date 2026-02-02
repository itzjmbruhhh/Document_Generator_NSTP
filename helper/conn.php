<?php

//Connection to database

$conn = new mysqli('localhost', 'root', '', 'DocumentGenerator');

if (!$conn) {
    die(mysqli_error($conn));
}

?>