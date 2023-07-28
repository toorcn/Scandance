    <div class="align-center-bottom">
        <footer id="footer">
            <p>Copyright &copy; <?php echo date("Y"); ?> Scandance (inc.)</p>
        </footer>
    </div>
    <!-- info button on the bottom left -->
    <div class="position-fixed bottom-0 start-0 mb-1">
        <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#infoModal">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
            <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
        </svg>
        </button>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="infoModalLabel">Scandance Information</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <!-- subheading -->
            <?php if (basename($_SERVER['SCRIPT_NAME']) == "index.php") { ?>
                <p class="fs-4">Welcome to Scandance.</p>
                <p>To get started, click on "Sign up" on the top right.</p>

            <?php } ?>
            <?php if (basename($_SERVER['SCRIPT_NAME']) == "login.php" || basename($_SERVER['SCRIPT_NAME']) == "register.php") { ?>
                <!-- Back -->
                <p class="fs-4">To use Scandance, you will need to have an account.</p>
                <p>There are two types of accounts:</p>
                <ol>
                    <li>
                        <p>User: Users can join events.</p>
                    </li>
                    <li>
                        <p>Host: Hosts can create events.</p>
                    </li>
                </ol>
                <p>To create an account, click on the "Create account" button.</p>
            <?php } ?>
            <?php if (basename($_SERVER['SCRIPT_NAME']) == "dashboard.php") { 
                if ($_SESSION["role"] == "Participant") {
                ?>
                <!-- User View -->
                <p class="fs-4">Joining event</p>
                <p>There are two ways to join an event:</p>
                <ol>
                    <li>Scanning: Press on "Begin scan" and scan the QR code.</li>
                    <li>Manual: Enter in the event code and press on "Join"</li>
                </ol>
                <p>To edit your profile, press on the "Profile" on the top right.</p>
                <?php 
                } 
                if ($_SESSION["role"] == "Organizer") {
                ?>
                <!-- Host View -->
                <p class="fs-4">Creating event</p>
                <p>Enter in the event name and duration and press on "Create event".</p>
                <p>To stop the event, click on the "Stop Now" button.</p>

                <p>To view your event history, click on the "View History" button.</p>
                <p>To log out, click on the "Log out" on the top right.</p>
                <?php 
                }
            } 
            ?>
            <!-- User -->
            <?php if (basename($_SERVER['SCRIPT_NAME']) == "profile.php") { ?>
                <!-- Name, Phone, Update -->
                <p>To update your information, enter your information and click on the "Update" button.</p>
                <p>To log out, click on the "Log out" on the top right.</p>
            <?php } ?>
            <?php if (basename($_SERVER['SCRIPT_NAME']) == "scansuccess.php") { ?>
                <!-- Back -->
                <p>Press the "Back" button to return to the dashboard.</p>
            <?php } ?>     
            <!-- Host -->
            <?php if (basename($_SERVER['SCRIPT_NAME']) == "eventhistory.php") { ?>
                <!-- Back -->
                <p>Press the "Back" button to return to the dashboard.</p>

                <!-- History item -->
                <p>Click on the event to view the event details.</p>

            <?php } ?>
            <?php if (basename($_SERVER['SCRIPT_NAME']) == "event.php") { ?>
                <!-- Back -->
                <p>Press the "Back" button to return to the event history.</p>
                 
                <!-- Export -->
                <p>To export the event participants to a CSV file, click on the "Export to CSV" button.</p>
            <?php } ?>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
    </div>
</body>
</html>