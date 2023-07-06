<?php require('partials/database.php') ?>
<?php require('partials/header.php') ?>

<?php
if(isset($_GET['email']) && isset($_GET['role']) && isset($_GET['eventCode'])) { 
    $email = $_GET['email'];
    $role = $_GET['role'];
    $event_code = $_GET['eventCode'];
    // parse event_code
    $code_explode = explode(":", $event_code);
    $code_orgId = $code_explode[0];
    $code_eventName = $code_explode[1];
    $code_code = $code_explode[2];

    // send data to database
    // echo "<p>Event Code: $event_code</p>";
    // echo "<p>Organizer ID: $code_orgId</p>";
    // echo "<p>Event Name: $code_eventName</p>";
    // echo "<p>Code: $code_code</p>";

    $event_id = getEventIdByEventCode($code_code)["Event_ID"];
    if(hasJoinedEvent(getIdByEmail($email, $role), $event_id)) {
        echo "<p>You have already joined this event.</p>";
        exit();
    } else {
        participantJoinEvent(getIdByEmail($email, $role), $event_id);
        echo "<p>Thank you for joining the <strong>$code_eventName</strong>!</p>";
    }

    // to test if the data is sent to database
    // echo "<br>participants joined by this Event: " . getParticipantByEventID($event_id);

} 
?>

<?php require('partials/footer.php') ?>