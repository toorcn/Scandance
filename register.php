<?php require('partials/database.php') ?>
<?php require('partials/header.php') ?>

<h1>Register</h1>

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

    // check if email is already registered
    if(!userExist($role, $email)) {
        //  register
        if (userRegister($role, $email, $password)) {
            echo "<p>Thank you for registering as an $role!</p>";
            // header("Location: login.php");
        } else {
            echo "Error...";
        }
    } else {
        //   already registered
        echo "<p>You are already registered as an $role.</p>";
        echo "<p>Please <a href='login.php'>login</a> instead.</p>";
    }
    $conn->close();
} 
?>

<?php require('partials/footer.php') ?>