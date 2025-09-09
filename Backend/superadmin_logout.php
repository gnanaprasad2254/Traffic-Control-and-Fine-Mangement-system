<?php
session_name("superadmin");
session_start();
session_unset();
session_destroy();
header("Location: ../Interface/home.php");
exit();
?>
