<?php
require('partials/headerForLogin.php');
require('partials/database.php');

if ($_SESSION["role"] == "Organizer") {
    $email = $_SESSION['email'];
    $role = $_SESSION['role'];
    $userID = getIdByEmail($email, $role);
    $participant = new participant($userID);
    ?>
    <!-- back -->
    <div class="container">
        <a href="dashboard.php" class="btn btn-outline-dark mb-5">Back</a>

        <div class="col text-center mt-2">
            <div class="card" style="width: 18rem;">
                <h5 class="card-title">Event History</h5>
                <!-- <p class="card-text" id="video-text">Camera 1</p> -->
                <?php
                $eventHistory = getEventsByOrganizerId($userID);
                if ($eventHistory != false) {
                    foreach ($eventHistory as $event) {
                        $participant = json_decode(getParticipantByEventID($event["Event_ID"]));
                        if ($participant == NULL) {
                            $participant = 0;
                        } else {
                            // print_r($participant);
                            $participant = count($participant);
                        }
                ?>
                        <p class="eventHistoryItems">
                            <a href="event.php?eventID=<?php echo $event["Event_ID"] ?>">
                                (<?php echo $event["Event_ID"] ?>) ><?php echo $event["Event_Name"] ?>< <!-- [<?php echo $event["Event_End"] ?>] -->
                                    +<?php echo $participant ?> participants
                            </a>
                        </p>
                <?php
                    }
                } else {
                    echo "<p><i>No events history found</i></p>";
                }
                ?>
            </div>
        </div>
    </div>
    <?php
}  
?>