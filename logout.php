<?php
   session_start();
   unset($_SESSION["valid"]);
   unset($_SESSION["timeout"]);
   unset($_SESSION["email"]);
   unset($_SESSION["role"]);
   
   echo 'Logout successful!';
   header('Refresh: 0; URL = login.php');
?>