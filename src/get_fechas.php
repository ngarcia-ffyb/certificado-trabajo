<?php
require('conexion2.php');

if (isset($_GET['id_ddjj_head'])) {
    $id_ddjj_head = $_GET['id_ddjj_head'];

    // Consulta para obtener las fechas del periodo seleccionado
    $sql = "SELECT DISTINCT to_char(fecha, 'DD/MM/YYYY') AS fecha 
            FROM public.ddjj_data 
            WHERE id_ddjj_head = :id_ddjj_head 
            ORDER BY fecha";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_ddjj_head' => $id_ddjj_head]);

    $fechas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Devolver las fechas en formato JSON
    echo json_encode($fechas);
}
