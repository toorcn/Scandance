<?php require('partials/database.php') ?>
<?php require('partials/header.php') ?>

<?php
if(isset($_GET['eventCode'])) { 
    $email = $_SESSION['email'];
    $role = $_SESSION['role'];
    $event_code = $_GET['eventCode'];
    // parse event_code
    $code_explode = explode(":", $event_code);
    $code_orgId = $code_explode[0];
    $code_eventName = $code_explode[1];
    $code_code = $code_explode[2];

    $event_id = getEventIdByEventCode($code_code)["Event_ID"];
    if(hasJoinedEvent(getIdByEmail($email, $role), $event_id)) {
        echo "<p>You have already joined this event.</p>";
        exit();
    } else {
        participantJoinEvent(getIdByEmail($email, $role), $event_id);
        echo "<p>Thank you for joining the <strong>$code_eventName</strong>!</p>";
    }
} 
?>

<?php require('partials/footer.php') ?>