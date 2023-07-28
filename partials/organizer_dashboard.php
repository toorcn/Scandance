<?php
// check if user has ongoing event
$ongoingEvent = getEventsByOrganizerId($userID);
if (!(
    isset($_SESSION['event_name']) && isset($_SESSION['endTime'])
    && isset($_SESSION['event_organizerID']) && isset($_SESSION['event_code'])
) && $ongoingEvent == true) {
    $ongoingEvent = $ongoingEvent->fetch_assoc();
    $ongoingEventEndTime = $ongoingEvent["Event_End"];
    $parsedOngoingEventEndTime = new DateTime($ongoingEventEndTime, new DateTimeZone('Asia/Singapore'));
    // compare with current time
    $currentDateTime = new DateTime('now', new DateTimeZone('Asia/Singapore'));
    $currentDateTime = $currentDateTime->format('Y-m-d H:i:s');
    if ($ongoingEventEndTime > $currentDateTime) {
        $_SESSION['event_name'] = $ongoingEvent["Event_Name"];  
        $_SESSION['endTime'] = $parsedOngoingEventEndTime;
        $_SESSION['event_organizerID'] = $ongoingEvent["Organizer_ID"];
        $_SESSION['event_code'] = $ongoingEvent["Event_Code"];
    }
}

if (!(isset($_POST['event_name']) && isset($_POST['event_duration']))) {
    if (!isset($_SESSION['event_code'])) {
        ?>
        <!-- Organizer dashboard -->
        <div class="container">
        <div class="position-relative" style="width: 100%; height: 85vh;">
            <div class="position-absolute text-center" style="left: 50%; top: 40%; transform: translate(-50%, -50%);">
                <div class="card sm" id="signInCard">
                    <div class="card-body">
                        <h2 class="card-title">Scandance Events</h2>
                        <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
                            <div class="form-group mb-3 form-floating">
                                <input type="text" name="event_name" id="event_name" class="form-control" placeholder="Event Name" required>
                                <label for="event_name" class="form-label">Event Name</label>
                            </div>
                            <div class="form-group mb-2 form-floating">
                                    <input type="number" name="event_duration" id="event_duration" class="form-control" placeholder="Event Duration" required>
                                <label for="event_duration" class="form-label">Event Duration (minutes)</label>
                            </div>
                            <div class="form-group row mb-2 px-2">
                                <label for="event_duration" class="col-sm-4 col-form-label">Duration Presents</label>
                                <button type="button" class="btn btn-outline-light text-black col" onclick="updateEventDuration(10)">10</button>
                                <button type="button" class="btn btn-outline-light text-black col" onclick="updateEventDuration(60)">60</button>
                                <button type="button" class="btn btn-outline-light text-black col" onclick="updateEventDuration(120)">120</button>
                            </div>
                            <script language="JavaScript">
                                function updateEventDuration(duration) {
                                    document.getElementById("event_duration").value = duration;
                                }
                            </script>
                            <hr>
                            <button type="submit" class="btn btn-outline-dark mb-2 mt-2" style="width:100%;">Create Event</button>
                        </form>
                    </div>
                </div>    
                <div><a href="./eventhistory.php" class="btn btn-outline-dark mt-5" style="width: 60%">View History</a></div>
            </div>
        </div>
        </div>
    <?php

    }
} else {
    function random_strings($length_of_string) {
        // Removed zero and O to avoid confusion
        $str_result = '123456789ABCDEFGHIJKLMNPQRSTUVWXYZ';
        return substr(str_shuffle($str_result), 0, $length_of_string);
    }  
    if (!isset($_SESSION['event_code'])) {
        $event_name = $_POST['event_name'];
        $event_duration = $_POST['event_duration'];
        $event_organizerID = getIdByEmail($email, $role);
        // TODO get alpha generator that can do more than F
        // Create event code

        // Cryptographically generated binary string
        // $event_code = (string)bin2hex(random_bytes(4));
        // $event_code = strtoupper(substr($event_code, 0, 6));

        // Alphanumeric string
        $event_code = random_strings(6);
        // Test if event code is unique
        while (getEventIdByEventCode($event_code) != null) {
            $event_code = random_strings(6);
        }

        $currentDateTime = new DateTime('now', new DateTimeZone('Asia/Singapore'));
        $currentDateTimeSQL = $currentDateTime->format('Y-m-d H:i:s');
        $endTime = $currentDateTime->add(new DateInterval('PT' . $event_duration . 'M'));
        $endTimeSQL = $endTime->format('Y-m-d H:i:s');
        $_SESSION['event_name'] = $event_name;
        $_SESSION['endTime'] = $endTime;
        $_SESSION['endTimeSQL'] = $endTimeSQL;
        $_SESSION['event_organizerID'] = $event_organizerID;
        $_SESSION['event_code'] = $event_code;
        createEvent($event_organizerID, $event_name, $currentDateTimeSQL, $endTimeSQL, $event_code);
    }
}
if (
    isset($_SESSION['event_name']) && isset($_SESSION['endTime'])
    && isset($_SESSION['event_organizerID']) && isset($_SESSION['event_code'])
) {
    $event_name = $_SESSION['event_name'];
    $endTime = $_SESSION['endTime'];
    $event_organizerID = $_SESSION['event_organizerID'];
    $event_code = $_SESSION['event_code'];
    
    if (!is_string($endTime)) {
        $endTimeCountdown = $endTime->format('Y-m-d') . "T" . $endTime->format('H:i:s');
    } else {
        $endTime = new DateTime($endTime, new DateTimeZone('Asia/Singapore'));
        $endTimeCountdown = $endTime->format('Y-m-d') . "T" . $endTime->format('H:i:s');
    }
    // echo "editted Countdown time: '" . $endTimeCountdown . "'<br>";

    // $qrIdentifier = $event_organizerID . ":" . $event_name . ":" . $event_code;
    $localIP = $env['QR_DOMAIN'];
    $qrIdentifier = 'https://' . $localIP . '/?qridentifier=' . $event_code;
    $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=$qrIdentifier";

    $event_id = getEventIdByEventCode($event_code);
    ?>
    <!-- Organizer Event view -->
    <div class="row row-cols-auto align-items-center m-0" style="width: 100vw; height: 87vh; align-items: center;">
        <div class="col-sm-6 col-12 text-center align-items-center" style="height: 87%;">
            <div style="margin-top: 15%">
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
                    DisplayFormat = "%%H%% hours %%M%% minutes %%S%% seconds";
                    FinishMessage = "Event Expired";
                </script>
                <div><a href="clearQRSession.php" class="btn btn-outline-dark mt-1">Stop now</a></div>

            </div>

        </div>
        <script>
            var activateOnceFlag = false;

            function checkCountdownEnd() {
                const countdown = document.getElementById("cntdwn");
                console.log(countdown.innerHTML);
                if (countdown.innerHTML == "Event Expired" && activateOnceFlag == false) {
                    activateOnceFlag = true;
                    window.location.href = "clearQRSession.php";
                    // alert('TIMES UP!');
                } else {
                    activateOnceFlag = false;
                }
                setTimeout(checkCountdownEnd, 5000);
            }
            checkCountdownEnd();
        </script>
        <div class="col-sm-6 col-12">
            <div class="px-1 pb-5" style="width:100%; height:100%;">
                <div>
                    <span class="h4">Live Preview</span>
                    <span class="text-muted">(Total: <span id="attendanceCount">0</span>)</span>
                </div>
                <div id="liveAttendance"></div>
            </div>
            <script language="JavaScript">
                function updateLiveAttendance() {
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
                                    if (objResult.idArray.length > 0) {
                                        let html = `
                                                <span class="row">
                                                    <span class="col-6 col-md-3 h5 text-muted">Name</span>
                                                    <span class="col-3 d-none d-md-block h5 text-muted">Phone Number</span>
                                                    <span class="col-3 d-none d-md-block h5 text-muted">Email</span>
                                                    <span class="col-6 col-md-3 h5 text-muted">Time Attended</span>
                                                </span>
                                                `;
                                        const participantCount = objResult.idArray.length;

                                        for (let i = 0; i < participantCount; i++) {
                                            const participantId = objResult.idArray[i];
                                            const timestamp = objResult.timestampArray[i]
                                                ? (objResult.timestampArray[i]).substr(11)
                                                : "Unknown";
                                            const name = objResult.nameArray[i] == null 
                                                || objResult.nameArray[i] == "" 
                                                ? "Unknown" 
                                                : objResult.nameArray[i];
                                            const email = objResult.emailArray[i] == null 
                                                || objResult.emailArray[i] == "" 
                                                ? "Unknown" 
                                                : objResult.emailArray[i];
                                            const phone = objResult.phoneArray[i] == null 
                                                || objResult.phoneArray[i] == "" 
                                                ? "Unknown" 
                                                : objResult.phoneArray[i];
                                            html += `
                                                <div class="card mb-2">
                                                    <div class="card-body">
                                                        <span class="row text-center">
                                                            <span class="col-6 col-md-3">${name}</span>
                                                            <span class="col-3 d-none d-md-block">${email}</span>
                                                            <span class="col-3 d-none d-md-block">${phone}</span>
                                                            <span class="col-6 col-md-3">${timestamp}</span>
                                                        </span>
                                                    </div>
                                                </div>
                                                    `;
                                        }
                                        document.getElementById("liveAttendance").innerHTML = html;
                                        document.getElementById("attendanceCount").innerHTML = participantCount;
                                    } else {
                                        document.getElementById("liveAttendance").innerHTML = "<p><i>No participants yet</i></p>";
                                    }
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
}