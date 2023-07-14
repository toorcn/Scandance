<?php // [ESSENTIALLY COMPLETE 13/7/23]
require('partials/database.php');
require('partials/headerForLogin.php');
ob_start();
// Redirect to dashboard if already logged in
if (isset($_SESSION['valid']) && $_SESSION['valid'] == true) header("Location: dashboard.php");
?>
<div 
    class="position-relative" 
    style="width: 100%; 
            height: 88vh;"
    >
    <div 
        class="position-absolute" 
        style="left: 50%; 
                top: 35%; 
                transform: translate(-50%, -50%);"
        >
        <div 
            class="card 
                    text-center 
                    sm" 
            id="signInCard"
            >
            <div class="card-body">
                <h2 class="card-title">Create your Scandance Account</h2>
                <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" id="registerForm">
                    <!-- Account type as tabs -->
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
                            if ($(this).text() == "User") $('#Participant').prop('checked', true);
                            if ($(this).text() == "Host") $('#Organizer').prop('checked', true);
                        });
                    </script>
                    <!-- TODO (for production) remove 'value' -->
                    <div class="form-group mb-3 form-floating">
                        <input 
                            class="form-control"
                            id="email" 
                            type="email"
                            name="email"
                            required
                            value="<?php echo $env['ACCOUNT_FILLER_EMAIL']; ?>"
                            style="border-top-left-radius: 0; 
                                border-top-right-radius: 0;" 
                            >
                        <label for="email">Email</label>
                    </div>
                    <div class="form-group mb-3 form-floating">
                        <input 
                            class="form-control" 
                            id="password" 
                            type="password"
                            name="password"
                            required
                            value="<?php echo $env['ACCOUNT_FILLER_PASSWORD']; ?>" 
                            >
                        <label for="password">Password</label>    
                        <div id="passwordError" class="form-text" hidden>Password must contain at least 8 characters</div>
                    </div>
                    <div class="form-group mb-5 form-floating">
                        <input 
                            class="form-control" 
                            id="passwordConfirm" 
                            type="password"
                            name="passwordConfirm"
                            required
                            value="<?php echo $env['ACCOUNT_FILLER_PASSWORD']; ?>"
                            >
                        <label for="passwordConfirm">Repeat password</label>  
                        <div id="passwordConfirmError" class="form-text" hidden>Passwords do not match</div>
                    </div>
                    <div 
                        class="font-monospace mb-1"
                        id="submitErrorResponseBox"
                        >
                        <?php
                        if($_SERVER['REQUEST_METHOD'] === 'POST') { 
                            $email = $_POST['email'];
                            $password = (string)$_POST['password'];
                            $role = $_POST['role'];

                            if(!userExist($role, $email)) {
                                if (userRegister($role, $email, $password)) {
                                    // registeration success
                                    echo "<p>Thank you for registering!</p>
                                          <p>You will be redirected to the login page in 1 second.</p>";
                                    
                                    header('Refresh: 1; URL = login.php');
                                } else {
                                    // registeration error
                                    echo "<p>An unexpected error occured.</p>
                                          <p>Please try again later.</p>";
                                }
                            } else {
                                // email already registered
                                echo "<p>Email already registered!</p>";
                            }
                        } 
                        ?>    
                    </div>
                    <button 
                        class="btn 
                            btn-outline-dark 
                            mb-2" 
                        id="registerBtn" 
                        style="width:100%;"
                        >
                        Create account
                    </button>
                </form>
                <!-- TODO change placement of active validation -->
                <script>
                    // check if password is at least 8 characters
                    $('#password').on('input', function() {
                        const passwordInput = $('#password').val();
                        if(passwordInput.length < 8) {
                            $('#passwordError').removeAttr('hidden');
                        } else {
                            $('#passwordError').attr('hidden', true);
                        }
                    });
                    // check if password and passwordConfirm match when passwordConfirm is changed
                    $('#passwordConfirm, #password').on('input', function() {
                        const passwordInput = $('#password').val();
                        const passwordConfirmInput = $('#passwordConfirm').val();
                        if(passwordConfirmInput == passwordInput || passwordConfirmInput == "") {
                            $('#passwordConfirmError').attr('hidden', true);
                        } else {
                            $('#passwordConfirmError').removeAttr('hidden');
                        }
                    });
                    // check if password and passwordConfirm match on submit
                    $('#registerBtn').on('click', function (e) {
                        e.preventDefault();
                        // var password = $('#password').val();
                        // var passwordConfirm = $('#passwordConfirm').val();
                        // if (password != passwordConfirm) {
                        //     $('#submitErrorResponseBox').html("<p>Passwords do not match!</p>");
                        // } else {
                        if($('#passwordError').attr('hidden') && $('#passwordConfirmError').attr('hidden')) {
                            $('#registerForm').submit();
                        } else {
                            $('#submitErrorResponseBox').html("<p>Please check your password!</p>");
                        }
                        // }
                    });
                </script>
                <p 
                    class="card-subtitle 
                    mb-2 
                    text-body-secondary"
                    >
                    Already have an account?
                    <a href="login.php">Sign in</a>
                </p>
            </div>
        </div>    
    </div>
</div>
<?php
require('partials/footer.php');
?>