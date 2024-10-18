<?php
header("Content-Type: text/html;charset=utf-8");
session_start();

// Verifica si el usuario está autenticado
require('v_session.php');

require('conexion2.php');

// Verifica si se ha pasado el ID del certificado
if (isset($_GET['id'])) {
    $id_certificado = $_GET['id'];

    // Consulta para obtener los detalles del certificado
    $sql_certificado = "SELECT *
                        FROM public.ddjj_data c
                        WHERE c.id_ddjj_data = :id_certificado";
    $stmt = $pdo->prepare($sql_certificado);
    $stmt->bindParam(':id_certificado', $id_certificado, PDO::PARAM_INT);
    $stmt->execute();

    $certificado = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$certificado) {
        echo "No se encontró el certificado.";
        exit;
    }
} else {
    echo "No se ha proporcionado un ID de certificado.";
    exit;
}

include_once('head.php');
?>

<body>
<section class="container">
    <h3>Detalles del Certificado</h3>

    <table class="table">
        <tr>
            <th>Apellido y Nombres</th>
            <td><?php echo htmlspecialchars($certificado['apellido'] . ', ' . $certificado['nombres']); ?></td>
        </tr>
        <tr>
            <th>Tipo de Certificado</th>
            <td><?php echo htmlspecialchars($certificado['tipo_certificado']); ?></td>
        </tr>
        <tr>
            <th>Fecha de Emisión</th>
            <td><?php echo htmlspecialchars($certificado['fecha']); ?></td>
        </tr>
        <tr>
            <th>Estado</th>
            <td><?php
                if ($certificado['estado'] == 1) {
                    echo "Pendiente";
                } elseif ($certificado['estado'] == 2) {
                    echo "Aceptado";
                } else {
                    echo "Rechazado";
                }
            ?></td>
        </tr>
        <tr>
            <th>Certificado</th>
            <td>
                <?php if ($certificado['archivo_certificado']) { ?>
                    <a href="<?php echo htmlspecialchars($certificado['archivo_certificado']); ?>" target="_blank">Ver Certificado</a>
                <?php } else { ?>
                    No disponible
                <?php } ?>
            </td>
        </tr>
    </table>

    <a href="listar_certificados.php" class="btn btn-primary">Volver</a>
</section>
</body>
</html>
