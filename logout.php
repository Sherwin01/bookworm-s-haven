<?php
session_start();  // Start the session


session_unset();


session_destroy();


header("Location: index.php"); 
exit();
?>
