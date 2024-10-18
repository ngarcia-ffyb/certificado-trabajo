<?php
$host = 'localhost';
$dbname = 'ddjj';
$user = 'postgres';
$password = "caseros@1853";

$dsn = 'pgsql:host=localhost;dbname=ddjj';


try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo 'si correcto';
} catch (PDOException $e) {
    echo 'Conexión fallida: ' . $e->getMessage();
}
?>