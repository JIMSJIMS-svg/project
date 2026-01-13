<?php
session_start();

require '../db/db.php';

function isLoggedIn() {
    header('Location: ../dashboard.php');
    return isset($_SESSION['user_id']) && isset($_SESSION['username']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

function login($user_id, $username) {
    $_SESSION['user_id'] = $user_id;
    $_SESSION['username'] = $username;
    $_SESSION['login_time'] = time();
}