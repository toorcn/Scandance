<?php
   session_start();
   unset($_SESSION['event_name']);
   unset($_SESSION['endTime']);
   unset($_SESSION['endTimeSQL']);
   unset($_SESSION['event_organizerID']);
   unset($_SESSION['event_code']);
   
   echo 'You have cleaned session';
   header('Refresh: 0; URL = dashboard.php');
?>