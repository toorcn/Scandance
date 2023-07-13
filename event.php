<?php // [MAJOR-CHANGES-NEEDED 13/7/23]
require('partials/database.php');
require('partials/headerForLogin.php');

if(isset($_GET['eventID'])) {
    $event_id = $_GET['eventID'];
    $event = getEventByEventID($event_id);
    $eventParticipants = getParticipantByEventID($event_id);
    ?>
    <div class="container">
        <!-- TODO CHANGE EVERYTHING HERE -->
        <div class="row row-cols-auto">
            <div class="col-sm-6 col-12 mb-4">
                <h1>Event Details</h1>
                <p>ID: <?php echo $event_id ?></p>
                <p>Name: <?php echo $event["Event_Name"] ?></p>
                <!-- <p>Event Code: <?php echo $event["Event_Code"] ?></p> -->
                <p>Start Time: <?php echo $event["Event_Start"] ?></p>
                <p>End Time: <?php echo $event["Event_End"] ?></p>
                <!-- <p>Event Participants: <?php echo $eventParticipants ?></p> -->
            </div>
            <div class="col-sm-6 col-12">
                <h1>Event Participants</h1>

                <?php
                if ($eventParticipants == NULL) {
                    echo "<p>No participants joined this event.</p>";
                } else {
                    ?>
                    <form action="downloadCSV.php" method="get">
                        <input type="hidden" name="eventID" value="<?php echo $event_id ?>">
                        <input type="hidden" name="eventName" value="<?php echo $event["Event_Name"] ?>">
                        <input 
                            class="
                                btn 
                                btn-outline-dark" 
                            type="submit" 
                            name="export" 
                            value="Export to CSV"/>
                    </form>     
                    <hr>
                    <div class="pt-3 px-2">
                    <?php
                    $eventParticipants = json_decode($eventParticipants, true);
                    $counter = 0;
                    foreach($eventParticipants as $participant) {
                        $counter++;
                        $participantID = array_values($participant)[0];
                        $participant = new participant($participantID);
                        $participantName = $participant->getName();
                        $participantPhone = $participant->getPhone();
                        $participantEmail = $participant->getEmail();
                        ?>
                            <!-- <div>Participant ID: <?php echo $participantID ?></div> -->
                            <div><?php echo $counter ?>. <?php echo $participantName ?></div>
                            <!-- <div>Participant Phone: <?php echo $participantPhone ?></div> -->
                            <!-- <div>Participant Email: <?php echo $participantEmail ?></div> -->
                        <?php
                    }  
                    ?>                      
                    </div>   
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
    <?php
} 
?>