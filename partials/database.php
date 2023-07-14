<?php  
    $env = parse_ini_file('.env');
    $dbservername = $env['DB_SERVER_NAME'];
    $dbusername = $env['DB_USER_NAME'];
    $dbpassword = $env['DB_PASSWORD'];
    $dbname = $env['DB_NAME'];
    // TODO https://www.w3schools.com/php/func_mysqli_close.asp
    // Create connection
    $conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
    // TODO CLOSE CONNECTION
    function userExist($role, $email) {
        $rID = $role . "_ID";
        $rTable = "user" . strtolower($role);
        $rEmail = $role . "_Email";
        global $conn;
        $result = $conn->query("SELECT $rID FROM $rTable WHERE $rEmail = '$email'");
        if($result->num_rows == 0) return false;
        else return true;
    }
    function userRegister($role, $email, $password) {
        // TODO change password to use hash
        $rTable = "user" . strtolower($role);
        $rEmail = $role . "_Email";
        $rPassword = $role . "_Password";
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        global $conn;
        $result = $conn->query("INSERT INTO $rTable ($rEmail, $rPassword) VALUES ('$email', '$hashed_password')");
        if ($result === TRUE) return true;
        else return false;
    }
    function userLogin($role, $email, $password) {
        // TODO change password to use hash checking 
        $rTable = "user" . strtolower($role);
        $rEmail = $role . "_Email";
        $rPassword = $role . "_Password";
        global $conn;
        $result = $conn->query("SELECT $rPassword FROM $rTable WHERE $rEmail = '$email'");
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $hashed_password = $row[$rPassword];
            if (password_verify($password, $hashed_password)) return true;
        } 
        return false;
        // global $conn;
        // $result = $conn->query("SELECT $rEmail, $rPassword FROM $rTable WHERE $rEmail = '$email' AND $rPassword = '$password'");
        // if ($result->num_rows == 1) return true;
        // else return false;
    }
    function getIdByEmail($email, $role) {
        $rID = $role . "_ID";
        $rTable = "user" . strtolower($role);
        $rEmail = $role . "_Email";
        global $conn;
        $result = $conn->query("SELECT $rID FROM $rTable WHERE $rEmail = '$email'");
        if($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            return $row[$rID];
        }
        else return false;
    }
    // function getEventByOrganizerID($organizerID) {
    //     global $conn;
    //     $result = $conn->query("SELECT * FROM event WHERE Event_OrganizerID = '$organizerID'");
    //     if($result->num_rows > 0) {
    //         $row = $result->fetch_assoc();
    //         return $row;
    //     }
    //     else return false;
    // }
    function newEvent($organizerID, $eventName, $currentTime, $eventEndTime, $eventCode) {
        global $conn;
        $resultEvent = $conn->query("INSERT INTO events (Organizer_ID, Event_Name, Event_Start, Event_End, Event_Code) VALUES ('$organizerID', '$eventName', '$currentTime', '$eventEndTime', '$eventCode')");
        $eventID = $conn->insert_id;
        $resultParti = $conn->query("INSERT INTO eventparticipants (Event_ID) VALUES ('$eventID')");
        if ($resultEvent === TRUE && $resultParti === TRUE) return true;
        else return false;
    } 
    function hasEventEnded($eventID) {
        $event = getEventByEventID($eventID);
        $eventEndTime = $event["Event_End"];
        $currentDateTime = new DateTime('now', new DateTimeZone('Asia/Singapore')); 
        $currentDateTime = $currentDateTime->format('Y-m-d H:i:s');
        if ($currentDateTime > $eventEndTime) return true;
        else return false;
    }
    function participantJoinEvent($participantID, $eventID) {
        $currentDateTime = new DateTime('now', new DateTimeZone('Asia/Singapore')); 
        $joinTimeSQL = $currentDateTime->format('Y-m-d H:i:s');
        global $conn;
        $sql = "UPDATE `eventparticipants`
                SET `Event_participants` = IF(`Event_participants` IS NULL,
                        JSON_ARRAY(),
                        `Event_participants`),
                    `Event_participants` = JSON_ARRAY_APPEND(
                        `Event_participants`,
                        '$',
                        JSON_OBJECT('$joinTimeSQL', '$participantID'))
                WHERE `Event_ID` = '$eventID'";
        $result = $conn->query($sql);
        if ($result === TRUE) return true;
        else return false;
    }
    function getParticipantByEventID($eventID) {
        global $conn;
        $result = $conn->query("SELECT Event_participants FROM eventparticipants WHERE Event_ID = '$eventID'");
        if($result->num_rows > 0) {
            $row = $result->fetch_assoc()["Event_participants"];
            return $row;
        }
        else return false;
    }
    function getEventIdByEventCode($eventCode) {
        global $conn;
        $result = $conn->query("SELECT Event_ID FROM events WHERE Event_Code = '$eventCode'");
        if($result->num_rows > 0) { 
            $row = $result->fetch_assoc();
            return $row["Event_ID"];
        }
        else return false;
    } 
    function hasJoinedEvent($participantID, $eventID) {
        $row = (array)getParticipantByEventID($eventID);
        // echo print_r($row);
        if ($row == null) return false;
        $row = json_decode($row[0], true);
        foreach($row as $key => $value) {
            foreach($value as $key2 => $value2) {
                if($value2 == $participantID) return true;
            }
        }
        return false;
    }
    function getParticipantInformation($participantID) {
        global $conn;
        $result = $conn->query("SELECT * FROM userparticipant WHERE Participant_ID = '$participantID'");
        if($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
        }
        else return false;
    }
    // function updateParticipantInformation($participantID, $varKey, $varValue) {
    //     global $conn;
    //     $sql = "UPDATE `userparticipant`
    //             SET `Participant_information` = IF(`Participant_information` IS NULL,
    //                     JSON_ARRAY(), `Participant_information`),
    //                 `Participant_information` = JSON_ARRAY_APPEND(
    //                     `Participant_information`, '$', JSON_OBJECT('$varKey', '$varValue'))
    //             WHERE `Participant_ID` = $participantID;";
                
    //     $result = $conn->query($sql);
    //     if ($result === TRUE) return true;
    //     else return false;
    // }
    class Participant {
        private $participantID;
        public function __construct($participantID) {
            $this->participantID = $participantID;
        }
        public function getName() {
            $row = getParticipantInformation($this->participantID);
            return $row["Participant_Name"];
        }
        public function getPhone() {
            $row = getParticipantInformation($this->participantID);
            return $row["Participant_Phone"];
        }
        public function getEmail() {
            $row = getParticipantInformation($this->participantID);
            return $row["Participant_Email"];
        }

        public function updateName($name) {
            global $conn;
            $result = $conn->query("UPDATE userparticipant SET Participant_Name = '$name' WHERE Participant_ID = $this->participantID");
            return $result;
            // if ($result === TRUE) return true;
            // else return false;
            // return updateParticipantInformation($this->participantID, "Name", $name);
        }
        public function updatePhone($phone) {
            global $conn;
            $result = $conn->query("UPDATE userparticipant SET Participant_Phone = '$phone' WHERE Participant_ID = $this->participantID");
            return $result;
            // return updateParticipantInformation($this->participantID, "Phone", $phone);
        }
    }
    function getParticipantNameArrayByEventId($eventId) {
        $nameArray = array();
        foreach(json_decode(getParticipantByEventID($eventId)) as $key => $value) {
            foreach($value as $timestamp => $participantId) {
                $participant = new participant($participantId);
                array_push($nameArray, $participant->getName());
            }
        }
        return $nameArray;
    }
    function getParticipantPhoneArrayByEventId($eventId) {
        $phoneArray = array();
        foreach(json_decode(getParticipantByEventID($eventId)) as $key => $value) {
            foreach($value as $timestamp => $participantId) {
                $participant = new participant($participantId);
                array_push($phoneArray, $participant->getPhone());
            }
        }
        return $phoneArray;
    }
    function getParticipantEmailArrayByEventId($eventId) {
        $emailArray = array();
        foreach(json_decode(getParticipantByEventID($eventId)) as $key => $value) {
            foreach($value as $timestamp => $participantId) {
                $participant = new participant($participantId);
                array_push($emailArray, $participant->getEmail());
            }
        }
        return $emailArray;
    }
    function getParticipantIdArrayByEventId($eventId) {
        $idArray = array();
        foreach(json_decode(getParticipantByEventID($eventId)) as $key => $value) {
            foreach($value as $timestamp => $participantId) {
                array_push($idArray, $participantId);
            }
        }
        return $idArray;
    }
    function getParticipantTimestampArrayByEventId($eventId) {
        $timestampArray = array();
        foreach(json_decode(getParticipantByEventID($eventId)) as $key => $value) {
            foreach($value as $timestamp => $participantId) {
                array_push($timestampArray, $timestamp);
            }
        }
        return $timestampArray;
    }
    function getEventsByOrganizerId($organizerId) {
        global $conn;
        $result = $conn->query("SELECT * FROM events WHERE Organizer_ID = '$organizerId' ORDER BY Event_ID DESC");
        if($result->num_rows > 0) {
            // $row = $result->fetch_assoc();
            // return $row;
            return $result;
        }
        else return false;
    }
    function getEventByEventID($eventId) {
        global $conn;
        $result = $conn->query("SELECT * FROM events WHERE Event_ID = '$eventId'");
        if($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
        }
        else return false;
    }

    function getEventByEventCode($eventCode) {
        global $conn;
        $result = $conn->query("SELECT * FROM events WHERE Event_Code = '$eventCode'");
        if($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
        }
        else return false;
    }
    function getCurrentEventByOrganizerId($organizerId) {
        // TODO check if organizer has any events
        global $conn;
        $result = $conn->query("SELECT * FROM events WHERE Organizer_ID = '$organizerId' ORDER BY Event_ID DESC LIMIT 1");
        if($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
        } else {
            if($result == "") return false;
        }
    }
    function updateEventEndTime($eventId, $endTime) {
        global $conn;
        // print_r($eventId);
        // print_r($endTime);
        // print_r($endTime->format('Y-m-d H:i:s'));
        // echo "UPDATE events SET Event_End = '$endTime' WHERE Event_ID = $eventId";
        $result = $conn->query("UPDATE events SET Event_End = '$endTime' WHERE Event_ID = $eventId");

        return $result;
    }
?>