<?php
require('partials/header.php');
// Redirect to dashboard if already logged in
if (isset($_SESSION['valid']) && $_SESSION['valid'] == true) header("Location: dashboard.php");

function displayErrorMessage($message) {
    echo "<div class='alert alert-info' role='alert'>$message</div>";
}
?>

<h1 class="text-center">Click on Sign up to get started!</h1>

<div class="position-fixed bottom-0 start-0 mb-5 ms-5">
    <div class='alert alert-info' role='alert'>Lost? Tap on the <strong>i</strong></div>
    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-arrow-down-left" viewBox="0 0 16 16">
    <path fill-rule="evenodd" d="M2 13.5a.5.5 0 0 0 .5.5h6a.5.5 0 0 0 0-1H3.707L13.854 2.854a.5.5 0 0 0-.708-.708L3 12.293V7.5a.5.5 0 0 0-1 0v6z"/>
    </svg>
</div>


<?php require('partials/footer.php'); ?>