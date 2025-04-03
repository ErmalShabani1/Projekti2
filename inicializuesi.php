<?php
require_once 'autoload.php';

// Inicializon aplikacionin duke ngarkuar klasat, lidhur me databazën dhe nisur sesionin.
$db = Database::getInstance();
$conn = $db->getConnection();

// Starto sesionin nese nuk ka filluar
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}