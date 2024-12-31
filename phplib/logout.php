<?php

session_start();
unset($_SESSION['login']);
unset($_SESSION['password']);
unset($_SESSION['userName']);
unset($_SESSION['userTotal']);
session_destroy();

echo "<script>alert('Wylogowano'); document.location='../login.php'</script>";

?>