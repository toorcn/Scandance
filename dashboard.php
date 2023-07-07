<?php require('partials/database.php') ?>
<?php require('partials/header.php') ?>
<!-- QR Code Scanner JS -->
<script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
<!--  -->
<?php
if(isset($_SESSION['valid']) && $_SESSION['valid'] == true) {
    $email = $_SESSION['email'];
    $role = $_SESSION['role'];
    $participantID = getIdByEmail($email, $role);
    $participant = new participant($participantID);

    if($role == "Organizer") {
        if(!(isset($_POST['event_name']) && isset($_POST['event_duration']))) {
            ?>
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
                <!-- Event Name -->
                <label for="event_name">Event Name: </label>
                <input type="text" name="event_name" id="event_name" place required>
                <br>
                <!-- Event Duration -->
                <label for="event_duration">Event Duration: </label>
                <input type="radio" name="event_duration" id="event_duration" value="1" required>
                <label for="1">1m</label>
                <input type="radio" name="event_duration" id="event_duration" value="30" required>
                <label for="30">30m</label>
                <input type="radio" name="event_duration" id="event_duration" value="60" required>
                <label for="60">60m</label>
                <br>
                <input type="submit" value="Submit">
                <!-- Hidden -->
                <input type="text" name="email" id="email" value="<?php echo $email ?>" hidden>
                <input type="text" name="role" id="role" value="<?php echo $role ?>" hidden>                
            </form>   
            <?php            
        } else {
            // Event form submitted
            $event_name = $_POST['event_name'];
            $event_duration = $_POST['event_duration'];
            $event_organizerID = getIdByEmail($email, $role);
            $event_code = (string)bin2hex(random_bytes(4));
            $event_code = strtoupper(substr($event_code, 0, 6));

            $currentDateTime = new DateTime('now', new DateTimeZone('Asia/Singapore')); 
            $endTime = $currentDateTime->add(new DateInterval('PT' . $event_duration . 'M'));
            $endTimeSQL = $endTime->format('Y-m-d H:i:s');
            // echo "editted SQL time: " . $endTimeSQL . "<br>";
            // echo "<br>ID" . getIdByEmail($email, $role);

            if(newEvent($event_organizerID, $event_name, $endTimeSQL, $event_code)) {
                $endTimeCountdown = $endTime->format('Y-m-d') . "T" . $endTime->format('H:i:s');
                // echo "editted Countdown time: '" . $endTimeCountdown . "'<br>";

                $qrIdentifier = $event_organizerID . ":" . $event_name . ":" . $event_code;
                $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=$qrIdentifier";                
                
                $event_id = getEventIdByEventCode($event_code)["Event_ID"];
                ?>
                <div class="row">
                    <div class="col">
                        <h2><?php echo $event_name ?></h2>
                        <img src='<?php echo $qrCodeUrl ?>' alt='QR Code'>
                        <p>Event Code: <?php echo $event_code ?></p>
                        (<span id="cntdwn"></span>)
                        <script language="JavaScript">
                            TargetDate = "<?php echo $endTimeCountdown ?>";
                            CountActive = true;
                            CountStepper = -1;
                            LeadingZero = true;
                            DisplayFormat = "%%M%% Minutes, %%S%% Seconds.";
                        </script>
                    </div>
                    <div class="col">
                        <div style="width:100%; height:100%;">
                            <h4>Live Attendance (<span id="attendanceCount">0</span>)</h4>
                            <div id="liveAttendance" style="border: 1px solid black; width:100%; height:100%;"></div>
                        </div>
                        <script language="JavaScript">
                            function updateLiveAttendance() {
                                // const thisPath = String(window.location.href).substr(7, 23);
                                // console.log(thisPath + '/API/updateParticipantList.php')
                                console.log('ULA');
                                $.ajax({
                                    type: "POST",
                                    url: 'postGetEventArrays.php',
                                    dataType: 'json',
                                    data: {functionname: 'getParticipantByEventID', arguments: [<?php echo $event_id ?>]},
                                    
                                    success: (obj, textstatus) => {
                                        // console.log({obj});
                                        // const t = obj.result;
                                        // console.log({t});

                                        // 
                                        if( !('error' in obj) ) {
                                            if (obj.result != null) {
                                                let objResult = obj.result;
                                                html = "";

                                                for(let i = 0; i < objResult.idArray.length; i++) {
                                                    const participantId = objResult.idArray[i];
                                                    const timestamp = objResult.timestampArray[i];
                                                    const name = objResult.nameArray[i];
                                                    const phone = objResult.phoneArray[i];
                                                    html += "<p>(" + participantId + ") " + name + "[" + phone + "]- Scan time: " +  timestamp + "</p>";
                                                } 
                                                document.getElementById("liveAttendance").innerHTML = html;
                                                document.getElementById("attendanceCount").innerHTML = objResult.idArray.length;
                                            }
                                        } else {
                                            console.log(obj.error);
                                        }
                                    }
                                });
                                setTimeout(updateLiveAttendance, 1000);
                            }
                            updateLiveAttendance();
                        </script>
                    </div>
                </div>

                <?php
                
            } else {
                // newEvent Error
            }
        }
    }
    if($role == "Participant") {
        if(!isset($_POST['eventCode'])) {
            ?>
            <!-- <h3>Scan QR Code</h3> -->
            
            <div class="card" style="width: 18rem;">
                <video id="preview" class="card-img-top"></video>
                <div class="card-body" id="video-card">
                    <h5 class="card-title">Scan QR</h5>
                    <p class="card-text" id="video-text">Camera 1</p>

                </div>
            </div>
            
            <script src="./javascript/qrscanner.js"></script>

            <div class="card" style="width: 18rem;">
                <div class="card-body" id="video-card">
                    <h5 class="card-title">User information</h5>
                    <?php
                    if (isset($_POST['userName']) && isset($_POST['userPhone'])) {
                        $userName = $_POST['userName'];
                        $userPhone = $_POST['userPhone'];

                        if($participant->updateName($userName) &&
                            $participant->updatePhone($userPhone)) {
                            echo "<p>Info updated</p>";
                        } else {
                            echo "<p>Error updating info</p>";
                        }
                    }
                    ?>
                    <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
                        <label for="userName">Name: </label>
                        <input type="text" name="userName" id="userName" 
                            value="<?php 
                                if ($participant->getName()) echo $participant->getName();
                                ?>">
                        <br>
                        <label for="userPhone">Phone: </label>
                        <input type="tel" name="userPhone" id="userPhone" 
                            value="<?php 
                                if ($participant->getPhone()) echo $participant->getPhone() 
                                ?>">

                        <input type="submit" value="Join">
                    </form>

                </div>
            </div>

            <?php     
        } else {
            // Participant form submitted
            $event_code = $_POST['eventCode'];
            $eventID = $_POST['eventID'];
            if(participantJoinEvent($participantID, $eventID)) {
                echo "<span>$event_code</span>";
                echo "<p>Thank you for joining the event!</p>";
                $message = getParticipantByEventID($eventID);
                echo "json: " . print_r($message) . "<br>";
            } else {
                echo "<p>Error joining event.</p>";
            }
        }
    }
}
?>
<!-- Countdown JS -->
<script type="text/javascript" src="./javascript/countdown.js"></script>
<!-- QR Code Scanner JS -->
<script type="text/javascript" src="./javascript/qrscanner.js"></script>

<?php require('partials/footer.php') ?>