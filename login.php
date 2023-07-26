<?php // [ESSENTIALLY COMPLETE 13/7/23]
require('partials/database.php');
require('partials/header.php');
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
                top: 30%; 
                transform: translate(-50%, -50%);"
        >
        <div 
            class="card text-center sm" 
            id="signInCard"
            >
            <div class="card-body">
                <h2 class="card-title">Sign in</h2>
                <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
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
                    <div class="form-group mb-3 form-floating">
                        <!-- TODO (for production) remove 'value' -->
                        <input 
                            class="form-control"
                            id="email" 
                            type="email"
                            name="email"
                            placeholder="Email"
                            required
                            value="<?php echo $env['ACCOUNT_FILLER_EMAIL']; ?>"
                            style="border-top-left-radius: 0; 
                                border-top-right-radius: 0;" 
                            >
                        <label for="email">Email</label>
                    </div>
                    <div class="form-group mb-5 form-floating">
                        <input 
                            class="form-control" 
                            id="password" 
                            type="password"
                            name="password"
                            placeholder="Password"
                            required
                            value="<?php echo $env['ACCOUNT_FILLER_PASSWORD']; ?>" 
                            >
                        <label for="password">Password</label>    
                    </div>
                    <div class="font-monospace mb-1">
                        <?php
                        if($_SERVER['REQUEST_METHOD'] === 'POST') { 
                            $email = $_POST['email'];
                            $password = (string)$_POST['password'];
                            $role = $_POST['role'];

                            if(userExist($role, $email)) {
                                if(userLogin($role, $email, $password)) {
                                    $_SESSION['valid'] = true;
                                    $_SESSION['timeout'] = time();
                        
                                    $_SESSION['email'] = $email;
                                    $_SESSION['role'] = $role;
                                    header("Location: dashboard.php");
                                } else {
                                    // login failed 
                                    echo "<p>Incorrect password.</p>";
                                    // echo "<p>An unexpected error ocurred. Try again later.</p>";
                                }
                            } else {
                                // User not registered
                                echo "<p>Account does not exist.</p>
                                      <p>Create an account or check your account type.</p>";
                            }
                        } 
                        ?>
                    </div>
                    <button 
                        class="btn btn-outline-dark mb-2"
                        type="submit"  
                        style="width:100%;">
                        Sign in
                    </button>
                </form>
                <p class="card-subtitle mb-2 text-body-secondary">New to Scandance? <a href="register.php">Create account</a></p>
            </div>
        </div>    
    </div>
</div>
<?php 
require('partials/footer.php'); 
?>