<?php // [COMPLETE 13/7/23]
   session_start();
   if (isset($_SESSION['endTime'])) {
      require('partials/database.php');
      $currentDateTime = new DateTime('now', new DateTimeZone('Asia/Singapore'));
      $currentDateTimeSQL = $currentDateTime->format('Y-m-d H:i:s');   
      $eventId = getEventIdByEventCode($_SESSION['event_code']); 
      updateEventEndTime($eventId, $currentDateTimeSQL);
   };
   unset($_SESSION['event_name']);
   unset($_SESSION['endTime']);   
   unset($_SESSION['endTimeSQL']);
   unset($_SESSION['event_organizerID']);
   unset($_SESSION['event_code']);
   
   header('Location: dashboard.php');
?>