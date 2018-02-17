<?php
require_once 'functions.php';

session_start();

if (isset($_SESSION['user'])) {
    unset($_SESSION['user']);
}

redirectTo();
?>
