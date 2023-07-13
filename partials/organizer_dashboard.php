<?php
// if (!(isset($_POST['event_name']) 
// && isset($_POST['event_duration']) 
// && isset($_SESSION['event_code']))) {
//     echo "true";
// } else echo "false";
// if (isset($_SESSION['event_code']))  {
//     echo "SESSION SET";
// } else echo "SESSION NOT SET";
// TODO IF HAS ONGOING SESSION THEN LOAD AND CONTINUE

if (!(isset($_POST['event_name']) && isset($_POST['event_duration']))) {
    if (!isset($_SESSION['event_code'])) {
        ?>
        <!-- Organizer dashboard -->
        <div class="row container">
            <section id="new">
                <div class="col mt-2 text-center">
                    <h5 class="">Create Event</h5>
                    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                        <!-- Event Name -->
                        <div class="form-group row mb-2">
                            <label for="event_name" class="col-sm-4 col-form-label">Event Name</label>
                            <!-- <label for="event_name" class="col-sm-2 col-form-label">Event Name: </label> -->
                            <div class="col-5">
                                <input type="text" name="event_name" id="event_name" class="form-control" placeholder="Event Name" required>
                            </div>
                        </div>
                        <!-- Event Duration -->

                        <div class="form-group row mb-2">
                            <label for="event_duration" class="col-sm-4 col-form-label">Event Duration</label>
                            <div class="col-4">
                                <input type="text" name="event_duration" id="event_duration" class="form-control" placeholder="Event Duration" value="15" required>
                            </div>
                            <div class="input-group-append col-3">
                                <span class="input-group-text" id="inputGroupPrepend">minutes</span>
                            </div>
                        </div>
                        <div class="form-group row text-center mb-2">
                            <label for="event_duration" class="col-sm-4 col-form-label">Duration Presents</label>
                            <button type="button" class="btn btn-outline-light text-black col-2" onclick="updateEventDuration(1)">1</button>
                            <button type="button" class="btn btn-outline-light text-black col-2" onclick="updateEventDuration(15)">15</button>
                            <button type="button" class="btn btn-outline-light text-black col-2" onclick="updateEventDuration(30)">30</button>
                        </div>
                        <script>
                            function updateEventDuration(duration) {
                                document.getElementById("event_duration").value = duration;
                            }
                        </script>
                        <!-- <div class="form-group row mb-2"> -->
                        <!-- click for present values to update event_duration -->
                        <!-- <div class="col-4"></div> -->


                        <!-- </div> -->
                        <!-- <div class="form-group row">
                                    <div class="input-group col-sm-2">
                                        <label for="event_duration" class="col-form-label">Event Duration</label>
                                        <input type="number" class="form-control" min="0" max="1440" value="1">
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="inputGroupPrepend">minutes</span>
                                        </div>
                                    </div> 
                                </div> -->

                        <!-- <label for="event_duration">Event Duration: </label>
                                <input type="radio" name="event_duration" id="event_duration" value="1" required>
                                <label for="1">1m</label>
                                <input type="radio" name="event_duration" id="event_duration" value="30" required>
                                <label for="30">30m</label>
                                <input type="radio" name="event_duration" id="event_duration" value="60" required>
                                <label for="60">60m</label>
                                <br> -->
                        <input type="submit" value="Create" class="btn btn-outline-dark">
                        <!-- Hidden -->
                        <!-- <input type="text" name="email" id="email" value="<?php echo $email ?>" hidden>
                                <input type="text" name="role" id="role" value="<?php echo $role ?>" hidden>                 -->
                    </form>
                </div>
            </section>
            <section id="history">
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
            </section>
        </div>

    <?php

    }
} else {
    if (!isset($_SESSION['event_code'])) {
        $event_name = $_POST['event_name'];
        $event_duration = $_POST['event_duration'];
        $event_organizerID = getIdByEmail($email, $role);
        // TODO get alpha generator that can do more than F
        $event_code = (string)bin2hex(random_bytes(4));
        $event_code = strtoupper(substr($event_code, 0, 6));

        $currentDateTime = new DateTime('now', new DateTimeZone('Asia/Singapore'));
        $endTime = $currentDateTime->add(new DateInterval('PT' . $event_duration . 'M'));
        $endTimeSQL = $endTime->format('Y-m-d H:i:s');
        // echo "editted SQL time: " . $endTimeSQL . "<br>";
        // echo "<br>ID" . getIdByEmail($email, $role);
        $_SESSION['event_name'] = $event_name;
        $_SESSION['endTime'] = $endTime;
        $_SESSION['endTimeSQL'] = $endTimeSQL;
        $_SESSION['event_organizerID'] = $event_organizerID;
        $_SESSION['event_code'] = $event_code;
        newEvent($event_organizerID, $event_name, $endTimeSQL, $event_code);
    }
}
if (
    isset($_SESSION['event_name']) && isset($_SESSION['endTime'])
    && isset($_SESSION['endTimeSQL']) && isset($_SESSION['event_organizerID'])
    && isset($_SESSION['event_code'])
) {
    $event_name = $_SESSION['event_name'];
    $endTime = $_SESSION['endTime'];
    $endTimeSQL = $_SESSION['endTimeSQL'];
    $event_organizerID = $_SESSION['event_organizerID'];
    $event_code = $_SESSION['event_code'];

    // if(newEvent($event_organizerID, $event_name, $endTimeSQL, $event_code)) {

    $endTimeCountdown = $endTime->format('Y-m-d') . "T" . $endTime->format('H:i:s');
    // echo "editted Countdown time: '" . $endTimeCountdown . "'<br>";

    // $qrIdentifier = $event_organizerID . ":" . $event_name . ":" . $event_code;
    $localIP = '192.168.1.102/s4webdevgroup';
    $qrIdentifier = 'https://' . $localIP . '/?qridentifier=' . $event_code;
    $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=$qrIdentifier";

    $event_id = getEventIdByEventCode($event_code)["Event_ID"];
    ?>
    <!-- Organizer Event view -->
    <div class="row">
        <div class="col text-center">
            <div class="h2 text-muted"><?php echo $event_name ?></div>
            <img src='<?php echo $qrCodeUrl ?>' alt='QR Code'>
            <div>Event Code</div>
            <div class="h1"><?php echo $event_code ?></div>
            [<span id="cntdwn"></span>]
            <script language="JavaScript">
                TargetDate = "<?php echo $endTimeCountdown ?>";
                CountActive = true;
                CountStepper = -1;
                LeadingZero = true;
                DisplayFormat = "%%M%% minutes %%S%% seconds";
                FinishMessage = "Event Expired";
            </script>
        </div>
        <script>
            var activateOnceFlag = false;

            function checkCountdownEnd() {
                const countdown = document.getElementById("cntdwn");
                console.log(countdown.innerHTML);
                if (countdown.innerHTML == "Event Expired" && activateOnceFlag == false) {
                    activateOnceFlag = true;
                    window.location.href = "clearQRSession.php";
                    // console.log(countdown.innerHTML);
                    // alert('TIMES UP!');
                } else {
                    activateOnceFlag = false;
                }
                setTimeout(checkCountdownEnd, 5000);
            }
            checkCountdownEnd();
        </script>
        <div class="col">
            <div style="width:100%; height:100%;">
                <div>
                    <span class="h4">Live Preview</span>
                    <span class="text-muted">(Total: <span id="attendanceCount">0</span>)</span>
                </div>
                <div id="liveAttendance"></div>
            </div>
            <script language="JavaScript">
                function updateLiveAttendance() {
                    // const thisPath = String(window.location.href).substr(7, 23);
                    // console.log(thisPath + '/API/updateParticipantList.php')
                    // console.log('ULA');
                    $.ajax({
                        type: "POST",
                        url: 'postGetEventArrays.php',
                        dataType: 'json',
                        data: {
                            functionname: 'getParticipantByEventID',
                            arguments: [<?php echo $event_id ?>]
                        },

                        success: (obj, textstatus) => {
                            if (!('error' in obj)) {
                                if (obj.result != null) {
                                    let objResult = obj.result;
                                    let html = `
                                                <span class="row">
                                                    <span class="col h5 text-muted">Name</span>
                                                    <span class="col h5 text-muted">Phone Number</span>
                                                    <span class="col h5 text-muted">Time Attended</span>
                                                </span>
                                            `;
                                    const participantCount = objResult.idArray.length;

                                    for (let i = 0; i < participantCount; i++) {
                                        const participantId = objResult.idArray[i];
                                        const timestamp = objResult.timestampArray[i];
                                        const name = objResult.nameArray[i];
                                        const phone = objResult.phoneArray[i];
                                        html += `
                                                    <span class="row border-bottom text-center">
                                                        <span class="col">${name}</span>
                                                        <span class="col">${phone}</span>
                                                        <span class="col">${timestamp}</span>
                                                    </span>
                                                `;
                                        // html += "<p>(" + participantId + ") " + name + "[" + phone + "]- Scan time: " +  timestamp + "</p>";
                                    }
                                    document.getElementById("liveAttendance").innerHTML = html;
                                    document.getElementById("attendanceCount").innerHTML = participantCount;
                                }
                            } else {
                                console.log(obj.error);
                            }
                        },
                        error: (jqXHR, textStatus, errorThrown) => {
                            document.getElementById("liveAttendance").innerHTML = "<p><i>No participants yet</i></p>";
                        }
                    });
                    setTimeout(updateLiveAttendance, 1000);
                }
                updateLiveAttendance();
            </script>
        </div>
    </div>

<?php

    // } else {
    // newEvent Error
    // }
}
