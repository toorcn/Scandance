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
                            <!-- TODO animations when entering -->
                            <div class="form-group mb-3 form-floating">
                                <input type="text" name="event_name" id="event_name" class="form-control" placeholder="Event Name" required>
                                <label for="event_name" class="form-label">Event Name</label>
                            </div>
                            <div class="form-group mb-2 form-floating">
                                    <input type="text" name="event_duration" id="event_duration" class="form-control" placeholder="Event Duration" required>
                                <label for="event_duration" class="form-label">Event Duration (minutes)</label>
                            </div>
                            <!-- TODO duration present update to better interactions -->
                            <div class="form-group row mb-2 px-2">
                                <label for="event_duration" class="col-sm-4 col-form-label">Duration Presents</label>
                                <button type="button" class="btn btn-outline-light text-black col" onclick="updateEventDuration(10)">10</button>
                                <button type="button" class="btn btn-outline-light text-black col" onclick="updateEventDuration(30)">30</button>
                                <button type="button" class="btn btn-outline-light text-black col" onclick="updateEventDuration(60)">60</button>
                            </div>
                            <script>
                                function updateEventDuration(duration) {
                                    document.getElementById("event_duration").value = duration;
                                }
                            </script>
                            <hr>
                            <!-- End of todo -->
                            <button type="submit" class="btn btn-outline-dark mb-2 mt-2" style="width:100%;">Create Events</button>
                        </form>
                    </div>
                </div>    
                <div><a href="./eventhistory.php" class="btn btn-outline-dark mt-5" style="width: 60%">View History</a></div>
            </div>
        </div>
                <!-- previous -->
                <!-- <div class="col mt-2 text-center">
                    <h5 class="">Create Event</h5>
                    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post"> -->
                        <!-- Event Name -->
                        <!-- <div class="form-group row mb-2">
                            <label for="event_name" class="col-sm-4 col-form-label">Event Name</label> -->
                            <!-- <label for="event_name" class="col-sm-2 col-form-label">Event Name: </label> -->
                            <!-- <div class="col-5">
                                <input type="text" name="event_name" id="event_name" class="form-control" placeholder="Event Name" required>
                            </div>
                        </div> -->
                        <!-- Event Duration -->

                        <!-- <div class="form-group row mb-2">
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
                        </script> -->
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
                        <!-- <input type="submit" value="Create" class="btn btn-outline-dark"> -->
                        <!-- Hidden -->
                        <!-- <input type="text" name="email" id="email" value="<?php echo $email ?>" hidden>
                                <input type="text" name="role" id="role" value="<?php echo $role ?>" hidden>                 -->
                    <!-- </form>
                </div> -->
            <!-- </section> -->
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
        $currentDateTimeSQL = $currentDateTime->format('Y-m-d H:i:s');
        $endTime = $currentDateTime->add(new DateInterval('PT' . $event_duration . 'M'));
        $endTimeSQL = $endTime->format('Y-m-d H:i:s');
        // echo "editted SQL time: " . $endTimeSQL . "<br>";
        // echo "<br>ID" . getIdByEmail($email, $role);
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

    // if(newEvent($event_organizerID, $event_name, $endTimeSQL, $event_code)) {
    
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
    <div class="row row-cols-auto align-items-center" style="width: 100vw; height: 87vh; align-items: center;">
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
                    DisplayFormat = "%%M%% minutes %%S%% seconds";
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
                    // console.log(countdown.innerHTML);
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
                                    if (objResult.idArray.length > 0) {
                                        let html = `
                                                <span class="row">
                                                    <span class="col h5 text-muted">Name</span>
                                                    <span class="col h5 text-muted">Email</span>
                                                    <span class="col h5 text-muted">Phone Number</span>
                                                    <span class="col h5 text-muted">Time Attended</span>
                                                </span>
                                                `;
                                        const participantCount = objResult.idArray.length;

                                        for (let i = 0; i < participantCount; i++) {
                                            const participantId = objResult.idArray[i];
                                            const timestamp = objResult.timestampArray[i];
                                            const name = objResult.nameArray[i];
                                            const email = objResult.emailArray[i];
                                            const phone = objResult.phoneArray[i];
                                            html += `
                                                        <span class="row border-bottom text-center">
                                                            <span class="col">${name}</span>
                                                            <span class="col">${email}</span>
                                                            <span class="col">${phone}</span>
                                                            <span class="col">${timestamp}</span>
                                                        </span>
                                                    `;
                                            // html += "<p>(" + participantId + ") " + name + "[" + phone + "]- Scan time: " +  timestamp + "</p>";
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

    // } else {
    // newEvent Error
    // }
}
