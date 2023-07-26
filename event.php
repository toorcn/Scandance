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
        <a class="btn btn-outline-dark mb-5" href="eventhistory.php">Back</a>
        <div class="row row-cols-auto">
            <div class="col-sm-6 col-12 mb-4">
                <div class="mb-4">
                    <div class="card-title d-flex justify-content-between align-items-center">
                        <h1>Event Details</h1>
                        <p>(event-<?php echo $event_id ?>)</p>
                    </div>
                    <hr>
                    <p class="card-text"><strong>Name:</strong> <?php echo $event["Event_Name"] ?></p>
                    <p class="card-text"><strong>End Time:</strong> <?php echo $event["Event_End"] ?></p>             
                    <p class="card-text"><strong>Start Time:</strong> <?php echo $event["Event_Start"] ?></p>
                </div>
            </div>
            <div class="col-sm-6 col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1>Event Participants</h1>
                    <?php
                    if ($eventParticipants != NULL) {
                        ?>
                        <form action="downloadCSV.php" method="get">
                            <input type="hidden" name="eventID" value="<?php echo $event_id ?>">
                            <input type="hidden" name="eventName" value="<?php echo $event["Event_Name"] ?>">
                            <input class="btn btn-outline-dark" type="submit" name="export" value="Export to CSV"/>
                        </form>  
                        <?php
                    }
                    ?>
                </div>
                <hr>
                <?php
                if ($eventParticipants == NULL) {
                    echo "<p>No participants joined this event.</p>";
                } else {
                    ?>
                    <div class="pt-3 px-2 row">
                        <span class="col-6 col-md-4 h5 text-muted">Name</span>
                        <span class="col-4 d-none d-md-block h5 text-muted">Phone Number</span>
                        <span class="col-6 col-md-4 h5 text-muted">Email</span>
                    </div>
                    <div class="pt-3 px-2 row"> 
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
                        <div class="card mb-2">
                            <div class="card-body">
                                <span class="row text-center">
                                    <span class="col-6 col-md-4"><?php echo $participantName ?></span>
                                    <span class="col-4 d-none d-md-block"><?php echo $participantPhone ?></span>
                                    <span class="col-6 col-md-4"><?php echo $participantEmail ?></span>
                                </span>
                            </div>
                        </div>
                        
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