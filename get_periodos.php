<?php
require('conexion2.php');

if (isset($_GET['ano'])) {
    $ano = $_GET['ano'];

    // Consulta para obtener los periodos del año seleccionado
    $sql = "SELECT id_ddjj_head, periodo 
            FROM public.ddjj_head 
            WHERE año = :ano AND estado = TRUE 
            ORDER BY periodo";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['ano' => $ano]);

    $periodos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Devolver los periodos en formato JSON
    echo json_encode($periodos);
}
