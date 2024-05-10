<?php
session_start();

$_SESSION = array(); // Clears all session data
session_destroy();
header("location: /Index.php");

exit;
?>


