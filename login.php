<?php 
require('partials/database.php');
require('partials/headerForLogin.php');
ob_start();
?>
<!-- <div class="">
    <p>Don't have an account? <a href="register.php">Register</a> instead.</p>
</div> -->
        <!-- <label for="email">Email: </label>
        <input type="text" name="email" id="email" placeholder="Email" required>
        <br>
        <label for="password">Password: </label>
        <input type="text" name="password" id="password" placeholder="Password" required>
        <br> -->
<!-- <div class="position-relative">
    <div class="position-absolute">
        *------------------------
    </div>
</div> --> 
<div class="position-relative" style="width: 100%; height: 89vh;">
    <div class="position-absolute" style="left: 50%; top: 30%; transform: translate(-50%, -50%);">
        <div class="card text-center sm" id="signInCard">
            <div class="card-body">
                <h2 class="card-title">Sign in</h2>
                <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
                    <input type="radio" name="role" id="Participant" value="Participant" checked required hidden>
                    <input type="radio" name="role" id="Organizer" value="Organizer" required hidden>
                    <ul class="nav nav-tabs">
                        <li class="nav-item" style="cursor: pointer;">
                            <a class="nav-link active">User</a>
                        </li>
                        <li class="nav-item" style="cursor: pointer;">
                            <a class="nav-link">Host</a>
                        </li>
                    </ul>
                    <script>
                        $('.nav-tabs a').on('click', function (e) {
                            e.preventDefault();
                            $(this).tab('show');
                            if ($(this).text() == "User") {
                                $('#Participant').prop('checked', true);
                            }
                            if ($(this).text() == "Host") {
                                $('#Organizer').prop('checked', true);
                            }
                        });
                    </script>
                    <!-- TODO animations when entering -->
                    <div class="form-group mb-3 form-floating">
                        <input type="email" class="form-control" id="email" placeholder="Enter email" name="email"
                            value="loaf@duck.com" style="border-top-left-radius: 0; border-top-right-radius: 0;" required>
                        <label for="email">Email</label>
                    </div>
                    <div class="form-group mb-5 form-floating">
                        <input type="password" class="form-control" id="password" placeholder="Enter password" name="password"
                            value="123456" required>
                        <label for="password">Password:</label>    
                    </div>
                    <button type="submit" class="btn btn-outline-dark mb-2" style="width:100%;">Sign in</button>
                </form>
                <p class="card-subtitle mb-2 text-body-secondary">New to Scandance? <a href="register.php">Create account</a></p>
                
            </div>
        </div>    
    </div>
</div>

<!-- TODO user text into form -->
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

    echo "<p>email: $email password: $password role: $role</p>";

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