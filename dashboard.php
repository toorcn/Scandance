<?php require('partials/database.php') ?>
<?php require('partials/headerForLogin.php') ?>
<!-- QR Code Scanner JS -->
<script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
<!--  -->
<?php
if(isset($_SESSION['valid']) && $_SESSION['valid'] == true) {
    $email = $_SESSION['email'];
    $role = $_SESSION['role'];
    $userID = getIdByEmail($email, $role);
    $participant = new participant($userID);

    if($role == "Organizer") {
        // Organizer Dashboard View
        ?>
        <link rel="stylesheet" href="./stylesheets/organizerStyle.css">
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
        </script>
        
        <?php
        require('partials/organizer_dashboard.php');
        ?>
        <!-- Countdown JS -->
        <script type="text/javascript" src="./javascript/countdown.js"></script>        
        <?php
    }
    if($role == "Participant") {
        require('partials/participant_dashboard.php');
        ?>
<!-- QR Code Scanner JS -->
<script type="text/javascript" src="./javascript/qrscanner.js"></script>        
        <?php
    }
} else {
    // Redirect to login page
    header('Refresh: 0; URL = login.php');
}
?>

<?php require('partials/footer.php') ?>