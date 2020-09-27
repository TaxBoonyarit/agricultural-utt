<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['status'] !== 'user') {
    session_destroy();
    header("Location: login.php");
    exit();
}
