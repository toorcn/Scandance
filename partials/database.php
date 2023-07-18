<?php  
    $env = parse_ini_file('.env');
    $dbservername = $env['DB_SERVER_NAME'];
    $dbusername = $env['DB_USER_NAME'];
    $dbpassword = $env['DB_PASSWORD'];
    $dbname = $env['DB_NAME'];

    function query($sql) {
        // echo $sql . "<br>";
        global $dbservername, $dbusername, $dbpassword, $dbname;
        // Create connection
        $conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);
        if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
        $result = $conn->query($sql);
        $conn->close();
        return $result;
    }
    function getParticipantByEventID($eventID) {
        $eventID = getEventByEventID($eventID)["Event_ID"];
        $result = query("SELECT Event_participants FROM eventparticipants WHERE Event_ID = '$eventID'");
        if($result->num_rows > 0) {
            $row = $result->fetch_assoc()["Event_participants"];
            return $row;
        }
        else return false;
    }
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
            $result = query("UPDATE userparticipant SET Participant_Name = '$name' WHERE Participant_ID = $this->participantID");
            return $result;
        }
        public function updatePhone($phone) {
            $result = query("UPDATE userparticipant SET Participant_Phone = '$phone' WHERE Participant_ID = $this->participantID");
            return $result;
        }
    }
    function getIdByEmail($email, $role) {
        $rID = $role . "_ID";
        $rTable = "user" . strtolower($role);
        $rEmail = $role . "_Email";
        $result = query("SELECT $rID FROM $rTable WHERE $rEmail = '$email'");
        if($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            return $row[$rID];
        }
        else return false;
    }
    function getEventIdByEventCode($eventCode) {
        $result = query("SELECT Event_ID FROM events WHERE Event_Code = '$eventCode'");
        if($result->num_rows > 0) { 
            $row = $result->fetch_assoc();
            return $row["Event_ID"];
        }
        else return false;
    } 
    function getParticipantInformation($participantID) {
        $result = query("SELECT * FROM userparticipant WHERE Participant_ID = '$participantID'");
        if($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
        } else return false;
    }
    function getEventByEventID($eventId) {
        $result = query("SELECT * FROM events WHERE Event_ID = '$eventId'");
        if($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
        } else return false;
    }
    function userExist($role, $email) {
        $rID = $role . "_ID";
        $rTable = "user" . strtolower($role);
        $rEmail = $role . "_Email";
        $result = query("SELECT $rID FROM $rTable WHERE $rEmail = '$email'");
        if($result->num_rows == 0) return false;
        else return true;
    }
    function userRegister($role, $email, $password) {
        $rTable = "user" . strtolower($role);
        $rEmail = $role . "_Email";
        $rPassword = $role . "_Password";
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $result = query("INSERT INTO $rTable ($rEmail, $rPassword) VALUES ('$email', '$hashed_password')");
        if ($result === TRUE) return true;
        else return false;
    }
    function userLogin($role, $email, $password) {
        $rTable = "user" . strtolower($role);
        $rEmail = $role . "_Email";
        $rPassword = $role . "_Password";
        $result = query("SELECT $rPassword FROM $rTable WHERE $rEmail = '$email'");
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $hashed_password = $row[$rPassword];
            if (password_verify($password, $hashed_password)) return true;
        } 
        return false;
    }
    function createEvent($organizerID, $eventName, $currentTime, $eventEndTime, $eventCode) {
        $resultEvent = query("INSERT INTO events (Organizer_ID, Event_Name, Event_Start, Event_End, Event_Code) 
                         VALUES ('$organizerID', '$eventName', '$currentTime', '$eventEndTime', '$eventCode')");
        $eventID = getEventIdByEventCode($eventCode);
        $resultParti = query("INSERT INTO eventparticipants (Event_ID) VALUES ('$eventID')");
        if ($resultEvent === TRUE && $resultParti === TRUE) return true;
        else return false;
    } 
    function participantJoinEvent($participantID, $eventID) {
        $currentDateTime = new DateTime('now', new DateTimeZone('Asia/Singapore')); 
        $joinTimeSQL = $currentDateTime->format('Y-m-d H:i:s');
        $result = query("UPDATE `eventparticipants`
                SET `Event_participants` = IF(`Event_participants` IS NULL,
                        JSON_ARRAY(),
                        `Event_participants`),
                    `Event_participants` = JSON_ARRAY_APPEND(
                        `Event_participants`,
                        '$',
                        JSON_OBJECT('$joinTimeSQL', '$participantID'))
                WHERE `Event_ID` = '$eventID'");
        if ($result === TRUE) return true;
        else return false;
    }
    function hasJoinedEvent($participantID, $eventID) {
        $row = (array)getParticipantByEventID($eventID);
        if ($row == null) return false;
        $row = json_decode($row[0], true);
        foreach($row as $key => $value) {
            foreach($value as $key2 => $value2) {
                if($value2 == $participantID) return true;
            }
        }
        return false;
    }
    function hasEventEnded($eventID) {
        $event = getEventByEventID($eventID);
        $eventEndTime = $event["Event_End"];
        $currentDateTime = new DateTime('now', new DateTimeZone('Asia/Singapore')); 
        $currentDateTime = $currentDateTime->format('Y-m-d H:i:s');
        if ($currentDateTime > $eventEndTime) return true;
        else return false;
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
        $result = query("SELECT * FROM events WHERE Organizer_ID = '$organizerId' ORDER BY Event_ID DESC");
        if($result->num_rows > 0) {
            return $result;
        } else return false;
    }
    function getEventByEventCode($eventCode) {
        $result = query("SELECT * FROM events WHERE Event_Code = '$eventCode'");
        if($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
        } else return false;
    }
    function updateEventEndTime($eventId, $endTime) {
        $result = query("UPDATE events SET Event_End = '$endTime' WHERE Event_ID = $eventId");
        return $result;
    }
?>