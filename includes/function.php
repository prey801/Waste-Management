<?php
function isLoggedIn() {
    return isset($_SESSION['user']);
}

function isAdmin() {
    return isLoggedIn() && $_SESSION['user']['user_type'] === 'admin';
}

function isWasteTeam() {
    return isLoggedIn() && $_SESSION['user']['user_type'] === 'waste_team';
}
