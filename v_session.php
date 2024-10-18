<?php
// Verifica si el usuario está autenticado
if (!isset($_SESSION['loggedinA']) || $_SESSION['loggedinA'] !== true) {
    header('Location: login_A.php');
    exit;
}
