<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scandance</title>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./stylesheets/style.css">
    <link rel="icon" type="image/png" href="../icon.png" />
</head>

<body>
    <header class="p-2 rounded-4 rounded-top-0">
        <div class="container">
            <div class="align-items-center" style="display: flex;">
                <a href="/s4webdevgroup" class="fs-4 text-decoration-none link-dark" id="scandanceNavTitle">
                    <p>Scandance<sup>&copy</sup></p>
                </a>
                <!-- End right -->
                <div style="margin-left: auto;">
                <!-- TODO changing nav items -->
                    <?php
                    // if$_SERVER['PHP_SELF']
                    ?>
                                        <?php 
                    if(!(isset($_SESSION['valid']) && $_SESSION['valid'] == true)) {
                        ?>
                        <a href="register.php" class="btn btn-outline-dark me-2">Sign up</a>
                        <?php
                    } else {
                        if ($_SESSION['role'] == "Organizer") {
                            ?>
                            <a href="logout.php" class="btn btn-outline-dark me-2">Logout</a>
                            <?php
                        }
                        if ($_SESSION['role'] == "Participant") {
                            ?>
                            <a href="profile.php" class="btn btn-outline-dark me-2">Profile</a>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
            <hr>
        </div>
    </header>