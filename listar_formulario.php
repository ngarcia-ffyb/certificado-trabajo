<?php
header("Content-Type: text/html;charset=utf-8");
session_start();

// Verifica si el usuario está autenticado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Obtener la persona autenticada
$persona = $_SESSION['persona'];
$apenom = isset($_SESSION['apenom']) ? $_SESSION['apenom'] : 'Invitado';
$documento = isset($_SESSION['dni']) ? $_SESSION['dni'] : 'Desconocido';

// Conexión a la base de datos
require('conexion2.php');

// Paso 1: Obtener los períodos habilitados
$sql_periodos = "
    SELECT h.id_ddjj_head, h.año, h.periodo, h.desde, h.hasta, h.estado 
    FROM public.ddjj_head h 
    WHERE h.estado = TRUE 
    AND NOW() BETWEEN h.desde AND h.hasta;
";
$stmt_periodos = $pdo->prepare($sql_periodos);
$stmt_periodos->execute();
$periodos_abiertos = $stmt_periodos->fetchAll(PDO::FETCH_ASSOC);

// Verifica si existen periodos abiertos
if (!$periodos_abiertos) {
    echo "No hay períodos abiertos actualmente para subir certificados.";
    exit;
}else{

}

// Paso 2: Obtener los tipos de certificados
$sql_tipos_certificados = "
    SELECT id_tipo_certificado, descripcion 
    FROM public.tipo_certificados;
";
$stmt_tipos = $pdo->prepare($sql_tipos_certificados);
$stmt_tipos->execute();
$tipos_certificados = $stmt_tipos->fetchAll(PDO::FETCH_ASSOC);

// Paso 3: Verificar si el usuario ya ha subido un certificado
$sql_certificados_subidos = "
    SELECT d.id_tipo_certificado, d.estado 
    FROM public.ddjj_data d 
    WHERE d.persona = :persona 
    AND d.id_ddjj_head = :id_ddjj_head;
";

$certificado_subido = null;

foreach ($periodos_abiertos as $periodo) {
    $id_ddjj_head = $periodo['id_ddjj_head'];
     $_SESSION['id_ddjj_head'] = $id_ddjj_head;

    $stmt_certificado = $pdo->prepare($sql_certificados_subidos);
    $stmt_certificado->execute([
        'persona' => $persona,
        'id_ddjj_head' => $id_ddjj_head
    ]);

    $certificado_subido = $stmt_certificado->fetch(PDO::FETCH_ASSOC);
    if ($certificado_subido) {
        $estado = $certificado_subido['estado'];
        switch ($estado) {
            case 1:
                $texto_estado='Pendiente de verificacion';
                break;
            case 2:
                $texto_estado='Aceptado';
                break;
            case 3:
                $texto_estado='Rechazado';
                break;
        }    
        
        break; // Si ya subió un certificado, no es necesario continuar
    }
}

// Paso 4: Mostrar el formulario al usuario
include_once('head.php');
?>

<body>

<br>

<section class="container-fluid">
    <section class="row justify-content-center">
        <section col-12 col-sm-4 col-md-2 id="contenedor0">
            <div class="col-md-4" style="margin-bottom: 10px;">
                <div class="card p-3 mb-5 bg-white rounded" style="width: 20rem;">
                    <div class="card-header">
                        <img class='' src='img/logoFFyb_2024.png' width='100%' height='50' />
                        <span class='titu'>
                            <h4>Bienvenido, <?php echo htmlspecialchars($apenom); ?></h4>
                            <p>Documento: <?php echo htmlspecialchars($documento); ?></p>
                        </span>
                    </div>
                    <div class="card-body">
                        
                        <?php if ($certificado_subido): ?>
                            <form action="index.php" name="fz" class="form-container">
                            <p>Ya has subido el siguiente certificado:</p>
                            <ul>
                                <li><strong>Certificado:</strong> 
                                    <?php 
                                    $id_tipo_certificado = $certificado_subido['id_tipo_certificado'];
                                    $descripcion_certificado = array_column($tipos_certificados, 'descripcion', 'id_tipo_certificado')[$id_tipo_certificado];
                                    echo htmlspecialchars($descripcion_certificado);
                                    ?>
                                </li>
                                <li><strong>Estado:</strong><?php echo $texto_estado ?></li>
                            </ul>
                            <button type="submit" class="btn btn-primary mt-3">Cerrar</button>
                        </form>
                        <?php else: ?>
                            <!-- Seleccionar el tipo de certificado y enviar -->
                            <form action="seleccionar_certificado.php" method="POST">
                                <div class="form-group">
                                    <label for="tipo_certificado">Seleccione un tipo de certificado:</label>
                                    <select name="tipo_certificado" id="tipo_certificado" class="form-control">
                                        <?php foreach ($tipos_certificados as $tipo): ?>
                                            <option value="<?php echo htmlspecialchars($tipo['id_tipo_certificado']); ?>">
                                                <?php echo htmlspecialchars($tipo['descripcion']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type='hidden' name='id_ddjj_head' value='$id_ddjj_head'>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">Siguiente</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    </section>
</section>

</body>
</html>
