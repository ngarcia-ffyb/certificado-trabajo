<?php
header("Content-Type: text/html;charset=utf-8");
session_start();

require('v_session.php');
require('conexion2.php');

if (isset($_GET['id_certificado'])) {
    $id_certificado = $_GET['id_certificado'];

    // Consulta para obtener los detalles del certificado
    $sql_certificado = "SELECT *
                        FROM public.ddjj_data c,tipo_certificados tc
                        WHERE (c.id_tipo_certificado=tc.id_tipo_certificado) and c.id_ddjj_data = :id_certificado";
    $stmt = $pdo->prepare($sql_certificado);
    $stmt->bindParam(':id_certificado', $id_certificado, PDO::PARAM_INT);
    $stmt->execute();

    //$certificado = $stmt->fetch(PDO::FETCH_ASSOC);

    $certificado = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$certificado) {
        //echo "No se encontró el certificado.";
        exit;
    }

    if (count($certificado) > 0) {

        // Generar el HTML para mostrar en la misma página
        $MyData = '<h3>Detalles del Certificado</h3>
                    <table class="table">';
        foreach ($certificado as $certificados) {
            $MyData .= '<tr><th>Apellido y Nombres</th><td>' . $certificados['apenom'] . '</td>';
            $MyData .= '<th>Tipo de Certificado</th><td>' . $certificados['descripcion'] . '</td>';
            $MyData .= '<th>Fecha subida</th><td>' . $certificados['fecha'] . '</td>';
            switch ($certificados['estado']) {
                case 1:
                    $estado = 'Pendiente';
                    break;
                case 2:
                    $estado = 'Aceptado';
                    break;
                case 3:
                    $estado = 'Rechazado';
                    break;
            }
            $MyData .= '<th>Estado</th><td>' . $estado . '</td></tr>';

            $link = '';
            switch ($certificados['id_tipo_certificado']) {
                case 1:
                    //trabajo
                    $archivo0 = $certificados['archivo_certificado'];
                    $extension0 = pathinfo($archivo0, PATHINFO_EXTENSION);

                    // Si es una imagen (jpg, png, gif, etc.)
                    if (in_array($extension0, ['jpg', 'jpeg', 'png', 'gif'])) {
                        $link .= '<img src="' . $archivo0 . '" alt="Certificado" style="max-width: 100%; height: auto;" />';
                    }
                    // Si es un PDF
                    else if ($extension0 === 'pdf') {
                        //$link .= '<a href="' . $archivo0 . '" target="_blank">Ver Certificado (PDF)</a>';
                        $link .= '<iframe src="' . $archivo . '" width="100%" height="600px"></iframe>';
                    }
                    // Si es otro tipo de archivo, solo mostramos el enlace
                    else {
                        $link .= 'archivo no admitido';
                    }

                    $archivo1 = $certificados['archivo_recibo'];
                    $extension1 = pathinfo($archivo1, PATHINFO_EXTENSION);


                    // Si es una imagen (jpg, png, gif, etc.)
                    if (in_array($extension1, ['jpg', 'jpeg', 'png', 'gif'])) {
                        $link .= '<br><img src="' . $archivo1 . '" alt="Certificado" style="max-width: 100%; height: auto;" />';
                    }
                    // Si es un PDF
                    else if ($extension1 === 'pdf') {
                        //$link .= '<a href="' . $archivo0 . '" target="_blank">Ver Certificado (PDF)</a>';
                        $link .= '<br><iframe src="' . $archiv1 . '" width="100%" height="600px"></iframe>';
                    }
                    // Si es otro tipo de archivo, solo mostramos el enlace
                    else {
                        $link .= 'archivo no admitido';
                    }


                    $link .= '';
                    break;
                case 2:
                    //domicilio
                    $archivo0 = $certificados['archivo_certificado'];
                    $extension0 = pathinfo($archivo0, PATHINFO_EXTENSION);

                    // Si es una imagen (jpg, png, gif, etc.)
                    if (in_array($extension0, ['jpg', 'jpeg', 'png', 'gif'])) {
                        $link .= '<img src="' . $archivo0 . '" alt="Certificado" style="max-width: 100%; height: auto;" />';
                    }
                    // Si es un PDF
                    else if ($extension0 === 'pdf') {
                        //$link .= '<a href="' . $archivo0 . '" target="_blank">Ver Certificado (PDF)</a>';
                        $link .= '<iframe src="' . $archivo . '" width="100%" height="600px"></iframe>';
                    }
                    // Si es otro tipo de archivo, solo mostramos el enlace
                    else {
                        $link .= 'archivo no admitido';
                    }

                    $link .= '';
                    break;
                case 3:
                    //padres recientes
                    $archivo0 = $certificados['archivo_certificado'];
                    $extension0 = pathinfo($archivo0, PATHINFO_EXTENSION);
                    $link = '';
                    break;
                case 5:
                    //capacidades diferentes
                    $archivo0 = $certificados['archivo_certificado'];
                    $extension0 = pathinfo($archivo0, PATHINFO_EXTENSION);
                    $link = '';
                    break;
                case 6:
                    //becas deportivas
                    $archivo0 = $certificados['archivo_certificado'];
                    $extension0 = pathinfo($archivo0, PATHINFO_EXTENSION);
                    $link = '';
                    break;
            }


            $MyData .= '<tr><th>certificado</th><td colspan="6">' . $link . '</td></tr>';
        }
        $MyData .= '</table>';
    } else {
        $MyData = '<table><tr><td colspan="6">No se encontraron certificados con los filtros aplicados.</td></tr></table>';
    }
}
$salidaJson = array('respuesta' => true, 'mensaje' => 'hola', 'MyData' => $MyData);
echo json_encode($salidaJson);
