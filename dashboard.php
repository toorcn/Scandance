<?php // [ESSENTIALLY-COMPLETE 13/7/23]
require('partials/database.php');
require('partials/headerForLogin.php');

if(isset($_SESSION['valid']) && $_SESSION['valid'] == true) {
    $email = $_SESSION['email'];
    $role = $_SESSION['role'];
    $userID = getIdByEmail($email, $role);
    $participant = new participant($userID);

    if($role == "Organizer") {
        // Organizer Dashboard View
        ?>
        <!-- Experimental interface -->
        <!-- <link rel="stylesheet" href="./stylesheets/organizerStyle.css">
        <section style="height: 100vh;">
            <div class="position-relative" style="width: 100%; height: 100%;">
                <div class="position-absolute" style="left: 50%; top: 40%; transform: translate(-50%, -50%);">
                    <div id="dashboardNavigation">
                        <a href="#new" class="card text-center">
                            <div class="card-header">New Event</div>
                            <div class="card-body">
                                <h5 class="card-title">Special title treatment</h5>
                                <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                            </div>
                        </a>
                        <a href="#history" class="card text-center">
                            <div class="card-header">View History</div>
                            <div class="card-body">
                                <h5 class="card-title">Special title treatment</h5>
                                <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                            </div>
                        </a>
                    </div>  
                </div> 
            </div>     
        </section>
        <script>
        $('#dashboardNavigation').on("click", "a", function(e){
            e.preventDefault();
            var id = $(this).attr("href"),
                topSpace = 30;
            $('html, body').animate({
                scrollTop: $(id).offset().top - topSpace
            }, 800);
        });
        </script> -->
        
        <?php require('partials/organizer_dashboard.php'); ?>
        <!-- Countdown JS -->
        <script type="text/javascript" src="./javascript/countdown.js"></script>        
        <?php
    }
    if($role == "Participant") {
        // Participant Dashboard View
        require('partials/participant_dashboard.php');
    }
} else {
    // Redirect to login page
    header("Location: login.php");
}
require('partials/footer.php');
?>