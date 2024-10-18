<?php
header("Content-Type: text/html;charset=utf-8");
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    //header('Location: listar_formulario.php');
    //exit;
}

require('conexion2.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM public.ddjj_head WHERE id_ddjj_head = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    echo "Registro eliminado.";
    // Redireccionar de vuelta a la página de lista
    header('Location: listar_habilitaciones.php');
    exit;
}
