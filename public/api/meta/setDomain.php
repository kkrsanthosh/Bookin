<?php
session_start();

// If domain is passed via GET, set it in the session
if (isset($_GET['domain'])) {
    $_SESSION['domain'] = $_GET['domain'];
    echo 'Domain stored in session: ' . $_SESSION['domain'];
} else {
    echo 'No domain provided.';
}
?>

