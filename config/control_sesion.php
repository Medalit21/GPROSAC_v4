<?php
session_start();

// Si no hay sesión activa, redirige al login
if (!isset($_SESSION['usu']) || empty($_SESSION['usu'])) {
    header("Location: ../../../"); // Ajustar la ruta según estructura
    exit;
}
