<?php // [MAJOR-CHANGES-NEEDED 13/7/23]
require('partials/database.php');
require('partials/headerForLogin.php');
?>
<div class="container">
    <a class="btn btn-outline-dark mb-4" href="dashboard.php">Back</a>
    <?php
    // TODO presentation of text/response
    function joinEvent($userID, $eventID, $eventCode) {
        $event = getEventByEventCode($eventCode);
        if ($event == null) {
            echo "<p>Invalid event code. Please try again!</p>";
            exit();
        }
        $event_name = $event["Event_Name"];
        if(hasJoinedEvent($userID, $eventID)) {
            echo "<p>You have already joined this event.</p>";
            exit();
        } else {
            participantJoinEvent($userID, $eventID);
            echo "<p>Thank you for joining <strong>$event_name</strong>!</p>";
        }
    }
    if($_SERVER['REQUEST_METHOD'] === 'GET') {
        $email = $_SESSION['email'];
        $role = $_SESSION['role'];
        $user_id = getIdByEmail($email, $role);  
        if(isset($_GET['input_event_code'])) {
            $event_code = $_GET['input_event_code'];
            $event_id = getEventIdByEventCode($event_code);

            joinEvent($user_id, $event_id, $event_code);
        }
        if(isset($_GET['qridentifier'])) {
            $qridentifier = $_GET['qridentifier'];
            // TODO redo condition
            if(strlen($qridentifier) != 6) {
                echo "<p>Invalid scan. Please try again!</p>";
                exit();
            }
            // parse event_code
            $event_code = substr($qridentifier, -6);
            $event = getEventByEventCode($event_code);
            $event_id = $event["Event_ID"];
            $organizer_id = $event["Organizer_ID"];
        
            joinEvent($user_id, $event_id, $event_code);
        }
    }
    if(isset($_POST['eventCode'])) {
        $event_code = $_POST['eventCode'];
        header("Location: scansuccess.php?qridentifier=$event_code");
    }
    ?>
</div>
<?php
require('partials/footer.php'); 
?>