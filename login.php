<?php 
require('partials/database.php');
require('partials/header.php');
ob_start();
?>

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
if (isset($_SESSION['valid']) && $_SESSION['valid'] == true) {
    // echo "valid: " . $_SESSION['valid'] . "<br>";
    // echo "timeout: " . $_SESSION['timeout'] . "<br>";
    // echo "email: " . $_SESSION['email'] . "<br>";
    // echo "<p>You are already logged in as an " . $_SESSION['role'] . ".</p>";
    // echo "<p>Please <a href='logout.php'>logout</a> first.</p>";

    header("Location: dashboard.php");
}
if($_SERVER['REQUEST_METHOD'] === 'POST') { 
    $email = $_POST['email'];
    $password = (string)$_POST['password'];
    $role = $_POST['role'];

    if(userExist($role, $email)) {
        if(userLogin($role, $email, $password)) {
            echo "<p>Thank you for logging in as an $role!</p>";
            $_SESSION['valid'] = true;
            $_SESSION['timeout'] = time();

            $_SESSION['email'] = $email;
            $_SESSION['role'] = $role;
            header("Location: dashboard.php");
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