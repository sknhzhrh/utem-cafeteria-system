<?php

session_start();
session_destroy();

header("Location: operator-login.php");
exit();

?>
