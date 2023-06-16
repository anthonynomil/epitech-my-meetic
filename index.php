<?php
session_start();
if (isset($_SESSION['user'])) {
    header("Location: ./pages/home.php");
} else {
    header("Location: ./pages/login.php");
}
