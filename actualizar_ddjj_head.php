<?php
header("Content-Type: text/html;charset=utf-8");
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    //header('Location: listar_formulario.php');
    //exit;
}

require('conexion2.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_ddjj_head = $_POST['id_ddjj_head'];
    $año = $_POST['año'];
    $periodo = $_POST['periodo'];
    $desde = $_POST['desde'];
    $hasta = $_POST['hasta'];
    $estado = $_POST['estado'] === 'true' ? true : false;

    $sql = "UPDATE public.ddjj_head 
            SET año = :año, periodo = :periodo, desde = :desde, hasta = :hasta, estado = :estado
            WHERE id_ddjj_head = :id_ddjj_head";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':año' => $año, 
        ':periodo' => $periodo, 
        ':desde' => $desde, 
        ':hasta' => $hasta, 
        ':estado' => $estado, 
        ':id_ddjj_head' => $id_ddjj_head
    ]);

    echo "Registro actualizado.";
}
?>
