<?php
    include '../includes/db.php';
    session_start();
    session_unset();
    session_destroy();
    echo "<script>alert('You have logged out'); window.location.href = '/Fine-T/Interface/home.php';</script>";
?>