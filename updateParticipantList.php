<?php

    header('Content-Type: application/json');

    $aResult = array();

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
        $aResult['result'] = getParticipantByEventID(floatval($_POST['arguments'][0]));

    }

    echo json_encode($aResult);

?>