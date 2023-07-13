<?php // [COMPLETE 13/7/23]
   session_start();
   session_destroy();

   header("Location: login.php");
?>