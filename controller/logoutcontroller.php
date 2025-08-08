<?php
session_start();
session_unset();
session_destroy();
header("Location: ../controller/logincontroller.php"); // Or just login.php if in root
exit();
