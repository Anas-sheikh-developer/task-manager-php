<?php
session_start();

// Saare session variables delete karo
$_SESSION = array();

// Session destroy karo
session_destroy();

// Wapas login page pe bhej do
header("Location: index.php");
exit();
?>