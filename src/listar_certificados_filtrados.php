<?php
header("Content-Type: text/html;charset=utf-8");
session_start();
/*
// Verifica si el usuario está autenticado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login_A.php');
    exit;
}
*/

require('v_session.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('conexion2.php');

// Obtener los parámetros de filtrado
/*
$ano = isset($_GET['ano']) ? $_GET['ano'] : '';
$periodo = isset($_GET['periodo']) ? $_GET['periodo'] : '';
$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : '';
$tipo_certificado = isset($_GET['tipo_certificado']) ? $_GET['tipo_certificado'] : '';
*/
$ano = isset($_GET['ano']) && $_GET['ano'] !== '' ? $_GET['ano'] : null;
$periodo = isset($_GET['periodo']) && $_GET['periodo'] !== '' ? $_GET['periodo'] : null;
$fecha = isset($_GET['fecha']) && $_GET['fecha'] !== '' ? $_GET['fecha'] : null;
$tipo_certificado = isset($_GET['tipo_certificado']) && $_GET['tipo_certificado'] !== '' ? $_GET['tipo_certificado'] : null;
$estado = isset($_GET['estado']) && $_GET['estado'] !== '' ? $_GET['estado'] : null;

// Construir la consulta base que siempre filtre por año y periodo
$sql = "SELECT d.id_ddjj_data, d.persona, to_char(d.fecha, 'DD/MM/YYYY') as fecha, d.id_tipo_certificado, d.estado, d.archivo_certificado, 
               d.apenom, t.descripcion, d.documento 
        FROM public.ddjj_data d
        JOIN public.ddjj_head h ON d.id_ddjj_head = h.id_ddjj_head
        JOIN public.tipo_certificados t ON d.id_tipo_certificado = t.id_tipo_certificado
        WHERE h.año = :ano AND h.id_ddjj_head = :periodo";  // Filtro por año y periodo

// Solo agregar condiciones adicionales si se ha seleccionado fecha o tipo de certificado
if (!empty($fecha)) {
    //$sql .= " AND d.fecha = :fecha";
    $sql .= " AND to_char(d.fecha, 'DD/MM/YYYY') = :fecha";
}
if (!empty($tipo_certificado)) {
    $sql .= " AND d.id_tipo_certificado = :tipo_certificado";
}

if (!empty($estado)) {
    $sql .= " AND d.estado = :estado";
}

$stmt = $pdo->prepare($sql);

// Pasar los parámetros a la consulta
$params = [
    'ano' => $ano,
    'periodo' => $periodo
];

if (!empty($fecha)) {
    $params['fecha'] = $fecha;
    /*
    $dateParts = explode('/', $fecha);
    if (count($dateParts) === 3) {
        $formattedDate = $dateParts[2] . '-' . $dateParts[1] . '-' . $dateParts[0];
    } else {
        $formattedDate = null;
    }

    $params['fecha'] = $formattedDate;
    echo $formattedDate;
 */
}
if (!empty($tipo_certificado)) {
    $params['tipo_certificado'] = $tipo_certificado;
}

if (!empty($estado)) {
    $params['estado'] = $estado;
}

$stmt->execute($params);
$certificados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mostrar los resultados en la tabla
if (count($certificados) > 0) {
    foreach ($certificados as $certificado) {
        echo '<tr>';
        //echo '<td>' . $certificado['persona'] . '</td>';
        echo '<td>' . $certificado['documento'] . '</td>';
        echo '<td>' . $certificado['apenom'] . '</td>';
        echo '<td>' . $certificado['descripcion'] . '</td>';
        echo '<td>' . $certificado['fecha'] . '</td>';

        // Estado (pendiente, aceptado, rechazado)
        $estado = $certificado['estado'] == 2 ? 'Aceptado' : ($certificado['estado'] == 3 ? 'Rechazado' : 'Pendiente');
        echo '<td>' . $estado . '</td>';

        // Acciones (Ver, Aceptar, Rechazar, Eliminar)
        echo '<td>';
        echo '<a href="ver_certificado_ajax.php?id_certificado=' . $certificado['id_ddjj_data'] . '" class="btn btn-info btn-sm action-button" data-action="Ver" data-id-certificado="11">Ver</a> ';

        if ($estado == 'Pendiente') {
            echo '<a href="cambiar_estado.php?id_certificado=' . $certificado['id_ddjj_data'] . '&estado=2" class="btn btn-success btn-sm action-button" data-action="Aceptar">Aceptar</a> ';
            echo '<a href="cambiar_estado.php?id_certificado=' . $certificado['id_ddjj_data'] . '&estado=3" class="btn btn-danger btn-sm action-button" data-action="Rechazar">Rechazar</a> ';
        }

        echo '<a href="eliminar_certificado.php?id_certificado=' . $certificado['id_ddjj_data'] . '" class="btn btn-warning btn-sm action-button" data-action="Eliminar">Eliminar</a>';
        echo '</td>';

        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="6">No se encontraron certificados con los filtros aplicados.</td></tr>';
}
