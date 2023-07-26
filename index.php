<?php // [CONTENT-NEEDED 13/7/23]
require('partials/header.php');
// Redirect to dashboard if already logged in
if (isset($_SESSION['valid']) && $_SESSION['valid'] == true) header("Location: dashboard.php");
?>

<h1 class="text-center">Click on Sign up to get started!</h1>

<?php require('partials/footer.php'); ?>