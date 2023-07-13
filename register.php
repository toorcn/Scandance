<?php 
require('partials/database.php');
require('partials/headerForLogin.php');
?>

<div class="position-relative" style="width: 100%; height: 89vh;">
    <div class="position-absolute" style="left: 50%; top: 30%; transform: translate(-50%, -50%);">
        <div class="card text-center sm" id="signInCard">
            <div class="card-body">
                <h2 class="card-title">Create your Scandance Account</h2>
                <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" id="registerForm">
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
                    <div class="form-group mb-3 form-floating">
                        <input type="password" class="form-control" id="password" placeholder="Enter password" name="password"
                            value="123456" required>
                        <label for="password">Password</label>    
                        <div id="passwordError" class="form-text" hidden>Password must contain at least 8 characters</div>
                    </div>
                    <div class="form-group mb-5 form-floating">
                        <input type="password" class="form-control" id="passwordConfirm" placeholder="Enter password" name="passwordConfirm"
                            value="123456" required>
                        <label for="passwordConfirm">Repeat password</label>  
                        <div id="passwordConfirmError" class="form-text" hidden>Passwords do not match</div>
                    </div>
                    <button class="btn btn-outline-dark mb-2" id="registerBtn" style="width:100%;">Create account</button>
                </form>
                <script>


                    $('#password').on('input', function() {
                        const passwordInput = $('#password').val();
                        if(passwordInput.length < 8) {
                            $('#passwordError').removeAttr('hidden');
                        } else {
                            $('#passwordError').attr('hidden', true);
                        }
                    });

                    $('#passwordConfirm, #password').on('input', function() {
                        const passwordInput = $('#password').val();
                        const passwordConfirmInput = $('#passwordConfirm').val();
                        if(passwordConfirmInput == passwordInput || passwordConfirmInput == "") {
                            $('#passwordConfirmError').attr('hidden', true);
                            
                        } else {
                            $('#passwordConfirmError').removeAttr('hidden');
                        }
                    });
                    
                    $('#registerBtn').on('click', function (e) {
                        e.preventDefault();
                        var password = $('#password').val();
                        var passwordConfirm = $('#passwordConfirm').val();
                        if (password != passwordConfirm) {
                            alert("Passwords do not match!");
                        } else {
                            $('#registerForm').submit();
                        }
                    });
                </script>
                <p class="card-subtitle mb-2 text-body-secondary">Already have an account? <a href="login.php">Sign in</a></p>
                
            </div>
        </div>    
    </div>
</div>

<!-- TODO user text into form -->
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
            header('Refresh: 1; URL = login.php');
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