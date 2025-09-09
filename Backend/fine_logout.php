<?php
session_name('officer_session');
include '../includes/db.php';
session_unset();
session_destroy();
header('Location: ../Interface/fine_login.php');
exit;
