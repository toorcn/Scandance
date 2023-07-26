<?php // [ESSENTIALLY-COMPLETE 13/7/23]
require('partials/database.php');
require('partials/header.php');

if(isset($_SESSION['valid']) && $_SESSION['valid'] == true) {
    $email = $_SESSION['email'];
    $role = $_SESSION['role'];
    $userID = getIdByEmail($email, $role);
    $participant = new participant($userID);

    if($role == "Organizer") {
        // Organizer Dashboard View
        require('partials/organizer_dashboard.php'); 
        ?>
        <!-- Countdown JS -->
        <script type="text/javascript" src="./javascript/countdown.js"></script>        
        <?php
    }
    if($role == "Participant") {
        if ($env['FORCE_USER_FILL_PROFILE'] == true) {
            if (
                // Either one of these is true
                $participant->getName() == NULL
             && $participant->getPhone() == NULL
                ) {
                // Participant Profile View
                header("Location: profile.php?firstTime=true");
            }          
        }
        // Participant Dashboard View
        require('partials/participant_dashboard.php');
    }
} else {
    // Redirect to login page
    header("Location: login.php");
}
require('partials/footer.php');
?>