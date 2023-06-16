<?php
session_start();
if (isset($_POST['sessionDestroy'])) {
    foreach ($_SESSION as $key => $value) {
        unset($_SESSION[$key]);
    }
    session_destroy();
}
