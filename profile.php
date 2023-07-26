<?php // [MINOR-CHANGES-WANTED 13/7/23]
require('partials/database.php');
require('partials/header.php');

if ($_SESSION["role"] == "Participant") {
    $email = $_SESSION['email'];
    $role = $_SESSION['role'];
    $userID = getIdByEmail($email, $role);
    $participant = new participant($userID);
    ?>
    <div class="container">
        <?php
        if (isset($_GET['firstTime']) && $_GET['firstTime']) {
            ?>
            <p class='font-monospace'>Please fill in your information before proceeding.</p>
            <?php
        } else {
            ?>
            <a 
                class="btn 
                    btn-outline-dark 
                    mb-5"
                href="dashboard.php">
                Back
            </a>  
            <?php          
        }
        ?>
        <div class="card-body" id="video-card">
            <h3 class="card-title">User information</h3>
            <form class="text-center mt-1" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                <div 
                    class="form-group 
                        mb-3 
                        form-floating"
                    >
                    <input 
                        class="form-control" 
                        id="userName" 
                        type="text" 
                        name="userName" 
                        pattern="[a-zA-Z0-9\s]+"
                        oninvalid="this.setCustomValidity('Please enter only alphanumeric characters.')"
                        oninput="this.setCustomValidity('')"
                        value="<?php 
                            if (isset($_POST['userName'])) {
                                echo $_POST['userName'];
                            } else {
                                if ($participant->getName()) echo $participant->getName(); 
                            }
                            ?>"
                        >
                    <label for="userName">Name</label>                                            
                </div>
                <div 
                    class="form-group 
                        mb-3 
                        form-floating"
                    >
                    <input 
                        class="form-control" 
                        id="userPhone" 
                        type="number" 
                        name="userPhone"
                        value="<?php
                            if (isset($_POST['userPhone'])) {
                                echo $_POST['userPhone'];
                            } else {
                                if ($participant->getPhone()) echo $participant->getPhone();    
                            }
                        ?>"
                        >
                    <label for="userPhone">Phone</label>
                </div>
                <div class="font-monospace mb-1">
                    <?php
                    if (isset($_POST['userName']) && isset($_POST['userPhone'])) {
                        $userName = $_POST['userName'];
                        $userPhone = $_POST['userPhone'];
                        if (
                            $participant->updateName($userName) &&
                            $participant->updatePhone($userPhone)
                        ) {
                            echo "<p>Information Updated!</p>";
                            if (isset($_GET['firstTime']) && $_GET['firstTime']) {
                                header("Location: dashboard.php");
                            }
                        } else {
                            echo "<p>Error updating info. Try again later.</p>";
                        }
                    }
                    ?>
                </div>
                <input class="btn btn-outline-dark" type="submit"  value="Update">
            </form>
        </div>
        <a class="btn btn-outline-dark mt-5" href="logout.php" style="width:100%;">Logout</a>
    </div>
    <?php
}
?>