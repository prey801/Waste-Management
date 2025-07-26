<?php
session_start();
require_once __DIR__ . '/../config/db.php';

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';
}

function isWasteTeam() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'waste_team';
}

function redirectIfNotLoggedIn() {
    if (!isLoggedIn()) {
        header('Location: /login.php');
        exit();
    }
}

function redirectIfNotAdmin() {
    redirectIfNotLoggedIn();
    if (!isAdmin()) {
        header('Location: /index.php');
        exit();
    }
}

function redirectIfNotWasteTeam() {
    redirectIfNotLoggedIn();
    if (!isWasteTeam()) {
        header('Location: /index.php');
        exit();
    }
}
?>