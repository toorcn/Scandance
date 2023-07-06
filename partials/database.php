<?php    
    $dbservername = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "Scandance";

    // Create connection
    $conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
    function userExist($role, $email) {
        $rID = $role . "_ID";
        $rTable = "user" . $role;
        $rEmail = $role . "_Email";
        global $conn;
        $result = $conn->query("SELECT $rID FROM $rTable WHERE $rEmail = '$email'");
        if($result->num_rows == 0) return false;
        else return true;
    }
    function userRegister($role, $email, $password) {
        $rTable = "user" . $role;
        $rEmail = $role . "_Email";
        $rPassword = $role . "_Password";
        global $conn;
        $result = $conn->query("INSERT INTO $rTable ($rEmail, $rPassword) VALUES ('$email', '$password')");
        if ($result === TRUE) return true;
        else return false;
    }
    function userLogin($role, $email, $password) {
        $rTable = "user" . $role;
        $rEmail = $role . "_Email";
        $rPassword = $role . "_Password";
        global $conn;
        $result = $conn->query("SELECT $rEmail, $rPassword FROM $rTable WHERE $rEmail = '$email' AND $rPassword = '$password'");
        if ($result->num_rows == 1) return true;
        else return false;
    }
    function getIdByEmail($email, $role) {
        $rID = $role . "_ID";
        $rTable = "user" . $role;
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
    function newEvent($organizerID, $eventName, $eventEndTime, $eventCode) {
        global $conn;
        $resultEvent = $conn->query("INSERT INTO events (Organizer_ID, Event_Name, Event_End, Event_Code) VALUES ('$organizerID', '$eventName', '$eventEndTime', '$eventCode')");
        $eventID = $conn->insert_id;
        $resultParti = $conn->query("INSERT INTO eventparticipants (Event_ID) VALUES ('$eventID')");
        if ($resultEvent === TRUE && $resultParti === TRUE) return true;
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
            return $row;
        }
        else return false;
    } 
    function hasJoinedEvent($participantID, $eventID) {
        $row = (array)getParticipantByEventID($eventID);
        $row = json_decode($row[0], true);
        foreach($row as $key => $value) {
            foreach($value as $key2 => $value2) {
                if($value2 == $participantID) return true;
            }
        }
        return false;
    }
?>