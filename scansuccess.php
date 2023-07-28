<?php
require('partials/database.php');
require('partials/header.php');
?>
<div class="container">
    <a class="btn btn-outline-dark mb-4" href="dashboard.php">Back</a>
    <?php
    // Function to display success message
    function displaySuccessMessage($message) {
        echo "<div class='alert alert-success' role='alert'>$message</div>";
    }

    // Function to display error message
    function displayErrorMessage($message) {
        echo "<div class='alert alert-danger' role='alert'>$message</div>";
    }

    // Join event function
    function joinEvent($userID, $eventID, $eventCode) {
        $event = getEventByEventCode($eventCode);
        if ($event == null) {
            displayErrorMessage("Event can't be found. Please try again!");
            exit();
        }
        $event_name = $event["Event_Name"];
        if(hasJoinedEvent($userID, $eventID)) {
            displayErrorMessage("You have already joined this event.");
            exit();
        } else {
            if (hasEventEnded($eventID)) {
                displayErrorMessage("Event has ended.");
            } else {
                participantJoinEvent($userID, $eventID);
                displaySuccessMessage("Thank you for joining <strong>$event_name</strong>!");
            }
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
            
            if(strlen($qridentifier) != 6) {
                displayErrorMessage("Event can't be found. Please try again!");
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