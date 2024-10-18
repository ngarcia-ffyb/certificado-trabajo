<?php
header("Content-Type: text/html;charset=utf-8");
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('conexion2.php');

$mensaje='';
$paso=0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $agnio = $_POST['agnio'];
    $periodo = $_POST['periodo'];
    $desde = $_POST['desde'];
    $hasta = $_POST['hasta'];
    $estado = ($_POST['estado'] === 'true') ? 'true' : 'false';
    $finestado = $_POST['finestado'];

    // 1. Validar que "hasta" sea mayor que "desde"
    if (strtotime($hasta) <= strtotime($desde)) {
        //die("Error: La fecha 'hasta' debe ser mayor que la fecha 'desde'.");
        $mensaje.="<br>Error: La fecha 'hasta' debe ser mayor que la fecha 'desde'.";
        $paso = $paso+1;
    }

    // 2. Validar que "fin_estado" sea mayor o igual a "hasta"
    if (strtotime($finestado) < strtotime($hasta)) {
        //die("Error: La fecha 'fin_estado' debe ser mayor o igual a la fecha 'hasta'.");
        $mensaje.="<br>Error: La fecha 'fin_estado' debe ser mayor o igual a la fecha 'hasta'.";
        $paso=$paso+1;
    }

    // 3. Verificar que no se repita año y período
    $sql_check = "SELECT COUNT(*) FROM public.ddjj_head WHERE año = :agnio AND periodo = :periodo";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([':agnio' => $agnio, ':periodo' => $periodo]);
    $count = $stmt_check->fetchColumn();

    if ($count > 0) {
        //die("Error: Ya existe una habilitación para el mismo año y período.");
        $mensaje.='<br>Error: Ya existe una habilitación para el mismo año y período.';
        $paso=$paso+1;
    }

    // 4. Verificar que no haya otra habilitación activa
    if ($estado === 'true') {
        $sql_active_check = "SELECT COUNT(*) FROM public.ddjj_head WHERE estado = true";
        $stmt_active_check = $pdo->query($sql_active_check);
        $active_count = $stmt_active_check->fetchColumn();

        if ($active_count > 0) {
            //die("Error: No puede haber más de una habilitación activa al mismo tiempo.");
            $mensaje.="<br>Error: No puede haber más de una habilitación activa al mismo tiempo.";
            $paso=$paso+1;
        }
        
    }

    
    if ($paso==0) {
        // Insertar la nueva habilitación en la base de datos
        $sql = "INSERT INTO public.ddjj_head (año, periodo, desde, hasta, estado, fin_estado) 
                VALUES (:agnio, :periodo, :desde, :hasta, :estado, :finestado)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':agnio' => $agnio, 
            ':periodo' => $periodo, 
            ':desde' => $desde, 
            ':hasta' => $hasta, 
            ':estado' => $estado,
            ':finestado' => $finestado
        ]);
        $mensajeOk=true;
    }else{
        $mensajeOk=false;
    }
    

    // Redireccionar de vuelta a la página de lista
   //header('Location: listar_habilitaciones.php?mensaje='.$mensaje);
   // exit;
    //echo $paso;
    //echo $mensaje;
}

$salidaJson=array('mensajeOk' => $mensajeOk,'respuesta' => $mensaje);
echo json_encode($salidaJson);

?>
