<?php
require('partials/database.php');
require('partials/header.php');

if(isset($_GET['eventID'])) {
    $event_id = $_GET['eventID'];
    
    $event = getEventByEventID($event_id);
    $eventParticipants = getParticipantByEventID($event_id);
    
    ?>
    <div class="container">
        <div class="row">
            <div class="col">
                <h1>Event Details</h1>
                <p>Event ID: <?php echo $event_id ?></p>
                <p>Event Name: <?php echo $event["Event_Name"] ?></p>
                <!-- <p>Event Code: <?php echo $event["Event_Code"] ?></p> -->
                <p>Event End Time: <?php echo $event["Event_End"] ?></p>
                <!-- <p>Event Participants: <?php echo $eventParticipants ?></p> -->
            </div>
            <div class="col">
                <h1>Event Participants</h1>

                <?php
                if ($eventParticipants == NULL) {
                    echo "<p>No participants joined this event.</p>";
                } else {
                    ?>
                    <form action="downloadCSV.php" method="get">
                        <input type="hidden" name="eventID" value="<?php echo $event_id ?>">
                        <input type="hidden" name="eventName" value="<?php echo $event["Event_Name"] ?>">
                        <input type="submit" name="export" value="Export to CSV" class="btn btn-primary" />
                    </form>                    
                    <?php
                    $eventParticipants = json_decode($eventParticipants, true);

                    foreach($eventParticipants as $participant) {
                        $participantID = array_values($participant)[0];
                        $participant = new participant($participantID);
                        $participantName = $participant->getName();
                        $participantPhone = $participant->getPhone();
                        $participantEmail = $participant->getEmail();
                        ?>
                        <p>
                            <div>Participant ID: <?php echo $participantID ?></div>
                            <div>Participant Name: <?php echo $participantName ?></div>
                            <div>Participant Phone: <?php echo $participantPhone ?></div>
                            <div>Participant Email: <?php echo $participantEmail ?></div>
                        </p>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <?php
} 
?>