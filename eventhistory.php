<?php // [MAJOR-CHANGES-NEEDED 13/7/23]
require('partials/database.php');
require('partials/headerForLogin.php');

if ($_SESSION["role"] == "Organizer") {
    $email = $_SESSION['email'];
    $role = $_SESSION['role'];
    $userID = getIdByEmail($email, $role);
    $participant = new participant($userID);
    ?>
    
    <div class="container">
        <a class="btn btn-outline-dark mb-5" href="dashboard.php">Back</a>
        <!-- up button -->
        <button class="btn btn-outline-dark" onclick="topFunction()" id="scrollBackUp" title="Go to top" style="display: none;">^</button>
        <script>
            //Get the button
            var scrollBackUp = document.getElementById("scrollBackUp");
            // When the user scrolls down 20px from the top of the document, show the button
            window.onscroll = function () {
                scrollFunction()
            };
            function scrollFunction() {
                if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                    scrollBackUp.style.display = "block";
                }
                else {
                    scrollBackUp.style.display = "none";
                }
            }
            // When the user clicks on the button, scroll to the top of the document
            function topFunction() {
                $('html, body').animate({
                    scrollTop: 0
                }, 400);
            }
        </script>
        <div class="text-center mt-2">
            <div class="card borderRemoveOnMobile" style="width: 100%;">
                <h5 class="card-title p-3">Event History</h5>
                <!-- <p class="card-text" id="video-text">Camera 1</p> -->
                <!-- TODO styling and display -->
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
                                <?php echo $event["Event_ID"] ?>. <?php echo $event["Event_Name"] ?> <!-- [<?php echo $event["Event_End"] ?>] -->
                                    [<?php echo $participant ?> Joined]
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