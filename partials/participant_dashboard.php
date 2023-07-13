<?php
if (!isset($_POST['eventCode'])) {
?>
    <!-- Participant Dashboard View -->
    <div class="container">
            <!-- TODO CHECK  -->
<div class="position-relative" style="width: 100%; height: 89vh;">
    <div class="position-absolute" style="left: 50%; top: 45%; transform: translate(-50%, -50%);">
            <div class="card" style="width: 400px;">
                <video id="preview" class="card-img-top"></video>
                <div class="card-body" id="video-card">
                    <h5 class="card-title">Scan QR</h5>
                    <!-- <p class="card-text" id="video-text">Camera 1</p> -->
                    <h6 class="text-center">OR</h6>
                    <form action="./scansuccess.php" method="post" class="row g-1">
                        <div class="form-group mb-3 form-floating col-8">
                            <input type="text" class="form-control" id="inputEventCode" placeholder="Enter event code" name="eventCode" required>
                            
                            <label for="eventCode">Event Code</label>
                        </div>
                        
                        <input type="submit" class="btn btn-outline-dark col-4" value="Join">
                    </form>
                </div>
            </div>

            <script src="./javascript/qrscanner.js"></script>
</div>
</div>
            <div class="card" style="width: 18rem;">
                <div class="card-body" id="video-card">
                    <h5 class="card-title">User information</h5>
                    <?php
                    if (isset($_POST['userName']) && isset($_POST['userPhone'])) {
                        $userName = $_POST['userName'];
                        $userPhone = $_POST['userPhone'];

                        if (
                            $participant->updateName($userName) &&
                            $participant->updatePhone($userPhone)
                        ) {
                            echo "<p>Info updated</p>";
                        } else {
                            echo "<p>Error updating info</p>";
                        }
                    }
                    ?>
                    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                        <label for="userName">Name: </label>
                        <input type="text" name="userName" id="userName" value="<?php
                                                                                if ($participant->getName()) echo $participant->getName();
                                                                                ?>">
                        <br>
                        <label for="userPhone">Phone: </label>
                        <input type="tel" name="userPhone" id="userPhone" value="<?php
                                                                                    if ($participant->getPhone()) echo $participant->getPhone()
                                                                                    ?>">
                        <input type="submit" class="btn btn-outline-dark" value="Join">
                    </form>
                </div>
            </div>
    </div>
<?php
} else {
    // Participant form submitted
    $event_code = $_POST['eventCode'];
    $eventID = $_POST['eventID'];
    if (participantJoinEvent($userID, $eventID)) {
        echo "<span>$event_code</span>";
        echo "<p>Thank you for joining the event!</p>";
        $message = getParticipantByEventID($eventID);
        echo "json: " . print_r($message) . "<br>";
    } else {
        echo "<p>Error joining event.</p>";
    }
}
