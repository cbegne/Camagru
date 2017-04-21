<?php

 session_start() or die("Failed to resume session\n");

 if (session_destroy()) {
    header("Location: ../public/index.php");
 }

?>
