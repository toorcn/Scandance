<?php
require('partials/headerForLogin.php');
require('partials/database.php');

if ($_SESSION["role"] == "Participant") {
    $email = $_SESSION['email'];
    $role = $_SESSION['role'];
    $userID = getIdByEmail($email, $role);
    $participant = new participant($userID);
?>
    <!-- back -->
    <div class="container">
        
        <a href="dashboard.php" class="btn btn-outline-dark mb-5">Back</a>
    <!-- <div class="card" style="width: 18rem;"> -->
        <div class="card-body" id="video-card">
            <h5 class="card-title">User information</h5>
            <?php
            if (isset($_POST['userName']) && isset($_POST['userPhone'])) {
                $userName = $_POST['userName'];
                $userPhone = $_POST['userPhone'];

                if (
                    $participant->updateName($userName) &&
                    $participant->updatePhone($userPhone)
                ) {
                    echo "<p>Info updated</p>";
                } else {
                    echo "<p>Error updating info</p>";
                }
            }
            ?>
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" class="text-center">
                <div class="form-group mb-3">
                    <input type="text" name="userName" class="form-control" id="userName" value="<?php
                        if ($participant->getName()) echo $participant->getName();
                        ?>">
                    <label for="userName">Name: </label>                                            
                </div>
                
                <div class="form-group mb-3">
                    <input type="tel" name="userPhone" class="form-control" id="userPhone" value="<?php
                        if ($participant->getPhone()) echo $participant->getPhone()
                        ?>">
                    <label for="userPhone">Phone: </label>

                </div>

                <input type="submit" class="btn btn-outline-dark" value="Update" >
            </form>
        </div>
        <a href="logout.php" class="btn btn-outline-dark mt-5" style="width:100%;">Logout</a>

    </div>

<?php
}

?>