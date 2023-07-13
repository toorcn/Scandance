<?php require('partials/database.php') ?>
<?php require('partials/header.php') ?>

<?php
if(isset($_GET['qridentifier'])) { 
    $email = $_SESSION['email'];
    $role = $_SESSION['role'];
    $userId = getIdByEmail($email, $role);
    $qridentifier = $_GET['qridentifier'];
    // parse event_code
    $event_code = substr($qridentifier, -6);
    $event = getEventByEventCode($event_code);
    $event_id = $event["Event_ID"];
    $organizer_id = $event["Organizer_ID"];
    $event_name = $event["Event_Name"];

    if(hasJoinedEvent($userId, $event_id)) {
        echo "<p>You have already joined this event.</p>";
        exit();
    } else {
        participantJoinEvent($userId, $event_id);
        echo "<p>Thank you for joining <strong>$event_name</strong>!</p>";
    }
} 
if(isset($_POST['eventCode'])) {
    $event_code = $_POST['eventCode'];

    header("Location: scansuccess.php?qridentifier=$event_code");
}
?>

<?php require('partials/footer.php') ?>