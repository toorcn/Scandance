<?php // [COMPLETE 13/7/23]
header('Content-Type: application/json');

$aResult = array();
if( !isset($_POST['functionname']) ) { $aResult['error'] = 'No function name!'; }

if( !isset($_POST['arguments']) ) { $aResult['error'] = 'No function arguments!'; }

if( !isset($aResult['error']) ) {
    require('partials/database.php');

    $eventId = floatval($_POST['arguments'][0]);

    $result = new stdClass();
    $result->idArray = getParticipantIdArrayByEventId($eventId);
    $result->timestampArray = getParticipantTimestampArrayByEventId($eventId);
    $result->nameArray = getParticipantNameArrayByEventId($eventId);
    $result->phoneArray = getparticipantPhoneArrayByEventId($eventId);
    $result->emailArray = getParticipantEmailArrayByEventId($eventId);

    $aResult['result'] = $result;
}

echo json_encode($aResult);
?>