<?php
session_start();

// chưa login
if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit;
}

// không phải admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 0) {
    header("Location: ../users/home.php");
    exit;
}