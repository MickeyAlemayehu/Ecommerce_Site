<?php
session_start();

function is_admin_logged_in() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
}

function require_admin_login() {
    if (!is_admin_logged_in()) {
        header('Location: login.php');
        exit;
    }
}
