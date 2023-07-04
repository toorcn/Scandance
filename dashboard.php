<?php require('partials/database.php') ?>
<?php require('partials/header.php') ?>
<!-- QR Code Scanner JS -->
<script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
<!--  -->
<?php
if($_SERVER['REQUEST_METHOD'] === 'POST') { 
    $email = $_POST['email'];
    $role = $_POST['role'];

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
                ?>
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
                <?php
            } else {
                // newEvent Error
            }
        }
    }
    if($role == "Participant") {
        if(!isset($_POST['eventCode'])) {
            ?>
            <!-- <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
                <label for="eventID">Event ID: </label>
                <input type="text" name="eventID" id="eventID" value="1">
                <label for="eventCode">Event Code: </label>
                <input type="text" name="eventCode" id="eventCode" value="ABC123">
                <input type="submit" value="Join"> -->
                <!-- Hidden -->
                <!-- <input type="text" name="email" id="email" value="<?php echo $email ?>" hidden>
                <input type="text" name="role" id="role" value="<?php echo $role ?>" hidden>
            </form>    -->
            <!-- <h3>Scan QR Code</h3> -->
            
            <div class="card" style="width: 18rem;">
                <video id="preview" class="card-img-top"></video>
                <div class="card-body" id="video-card">
                    <h5 class="card-title">Scan QR</h5>
                    <p class="card-text" id="video-text">Camera 1</p>

                </div>
            </div>
            <script>
                let scanner = new Instascan.Scanner({ video: document.getElementById('preview'), mirror: false });
                scanner.addListener('scan', function (content) {
                    // QR Code scanned
                    // post to scansucess.php
                    // console.log(content);
                    let email = "<?php echo $email ?>";
                    let role = "<?php echo $role ?>";
                    let eventCode = content;
                    // $.post("scansuccess.php", {email: email, role: role, eventCode: eventCode}, function(data) {
                    //     // console.log(data);
                    //     // $("#video-card").html(data);
                    // });
                    window.location.href = "scansuccess.php?email=" + email + "&role=" + role + "&eventCode=" + eventCode;
                    console.log(content);
                });
                Instascan.Camera.getCameras().then(function (cameras) {
                    if (cameras.length > 0) {
                        for (let i = 0; i < cameras.length; i++) {
                            document.getElementById("video-card").innerHTML += 
                            "<a href='#' class='camera-switches btn btn-primary p-1 m-1' data-cam='"+i+"'>Camera " + (i+1) + "</a>";
                        }
                        // when clicked start scanner of that camera
                        $(".camera-switches").click(function() {
                            let cam = $(this).attr("data-cam");
                            scanner.start(cameras[cam]);
                            $("#video-text").html("<p class='card-text'>Camera " + (parseInt(cam)+1) + "</p>");
                        });
                        // scanner.start(cameras[1]);
                    } else {
                        console.error('No cameras found.');
                    }
                }).catch(function (e) {
                    console.error(e);
                });
            </script>
            <?php     
        } else {
            // Participant form submitted
            $event_code = $_POST['eventCode'];
            $eventID = $_POST['eventID'];
            $email = $_POST['email'];
            $role = $_POST['role'];
            $participantID = getIdByEmail($email, $role);
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