<?php require('partials/database.php') ?>
<?php require('partials/header.php') ?>

<h1>Login</h1>

<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" class="align-center">
    <label for="email">Email: </label>
    <input type="text" name="email" id="email" value="loaf@duck.com" required>
    <br>
    <label for="password">Password: </label>
    <input type="text" name="password" id="password" value="123456" required>
    <br>
    <label for="role">Role: </label>
    <input type="radio" name="role" id="Organizer" value="Organizer" required>
    <label for="Organizer">Organizer</label>
    <input type="radio" name="role" id="Participant" value="Participant" required>
    <label for="Participant">Participant</label>

    <input type="submit" value="Submit" class="submitBlockCentered">
</form>    
<?php 
if($_SERVER['REQUEST_METHOD'] === 'POST') { 
    $email = $_POST['email'];
    $password = (string)$_POST['password'];
    $role = $_POST['role'];

    if(userExist($role, $email)) {
        if(userLogin($role, $email, $password)) {
            echo "<p>Thank you for logging in as an $role!</p>";
            // post to dashboard.php
            ?>
            <form action="dashboard.php" method="post" id="toDash" hidden>
                <input type="text" name="email" id="email" value="<?php echo $email ?>">
                <input type="text" name="role" id="role" value="<?php echo $role ?>">
            </form>   
            <?php
            echo "<script>document.getElementById('toDash').submit();</script>";
        } else {
            echo "<p>Wrong password.</p>";
        }
    } else {
        // User not registered
        echo "<p>You are not registered as an $role.</p>";
        echo "<p>Please <a href='register.php'>register</a> instead.</p>";
    }
    $conn->close();
} 
?>

<?php require('partials/footer.php') ?>