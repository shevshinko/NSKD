<?php

include '../session.php';
if ($user_role < 5) {
    session_destroy();
    header("Location: ../login.php");
}
?>