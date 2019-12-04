<?php
session_start();
$_SESSION['loggedin'] = "";
session_destroy();
header("Location: ../index.php");
?>