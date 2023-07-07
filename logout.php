<?php
   session_start();
   unset($_SESSION["valid"]);
   unset($_SESSION["timeout"]);
   unset($_SESSION["email"]);
   unset($_SESSION["role"]);
   
   echo 'You have cleaned session';
   header('Refresh: 1; URL = login.php');
?>