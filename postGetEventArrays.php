<?php
    // TEST TURN OFF
    header('Content-Type: application/json');

    $aResult = array();

    // TEST
    // require('partials/database.php');

    // $eventId = floatval(238);

    // $result = new stdClass();
    // $result->timestampToUser = getParticipantByEventID($eventId);
    // $idArray = array();
    // $timestampArray = array();
    // foreach(json_decode($result->timestampToUser) as $key => $value) {
    //     foreach($value as $timestamp => $participantId) {
    //         array_push($idArray, $participantId);
    //         array_push($timestampArray, $timestamp);
    //     }
    // }
    // print_r($idArray);
    // print_r($timestampArray);
    // $nameArray = array();
    // $phoneArray = array();

    // foreach(json_decode($result->timestampToUser) as $key => $value) {
    //     foreach($value as $timestamp => $participantId) {

    //         $participant = new participant($participantId);
    //         array_push($nameArray, $participant->getName());
    //         array_push($phoneArray, $participant->getPhone());
    //         echo 'name:' . $participant->getName();
    //         echo '<br>';
    //         echo 'timestamp:' . $timestamp;
    //         echo '<br>';
    //         echo 'participantId:' . $participantId;
    //         echo '<br><br>';
    //     }
    // }

    // print_r(json_decode($result->timestampToUser));
    // print_r($nameArray);


    // $aResult['result'] = $result;
    // TEST

    if( !isset($_POST['functionname']) ) { $aResult['error'] = 'No function name!'; }

    if( !isset($_POST['arguments']) ) { $aResult['error'] = 'No function arguments!'; }

    if( !isset($aResult['error']) ) {
        require('partials/database.php');
        // switch($_POST['functionname']) {
        //     case 'getParticipantByEventID':
        //         $aResult['result'] = getParticipantByEventID(floatval($_POST['arguments'][0]));
        //     default:
        //        $aResult['error'] = 'Not found function "'.$_POST['functionname'].'"!';
        //        break;
        // }

        $eventId = floatval($_POST['arguments'][0]);

        $result = new stdClass();
        // $result->timestampToUser = getParticipantByEventID($eventId);
    
        // $idArray = array();
        // $timestampArray = array();
        // foreach(json_decode($result->timestampToUser) as $key => $value) {
        //     foreach($value as $timestamp => $participantId) {
        //         array_push($idArray, $participantId);
        //         array_push($timestampArray, $timestamp);
        //     }
        // }
        $result->idArray = getParticipantIdArrayByEventId($eventId);
        $result->timestampArray = getParticipantTimestampArrayByEventId($eventId);
        $result->nameArray = getParticipantNameArrayByEventId($eventId);
        $result->phoneArray = getparticipantPhoneArrayByEventId($eventId);


        $aResult['result'] = $result;

    }

    echo json_encode($aResult);

?>