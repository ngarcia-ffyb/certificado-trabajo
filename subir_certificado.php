<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    //header('Location: login.php');
    //exit;
}

// Obtener la persona autenticada
$persona = $_SESSION['persona'];
$apenom = isset($_SESSION['apenom']) ? $_SESSION['apenom'] : 'Invitado';
$documento = isset($_SESSION['dni']) ? $_SESSION['dni'] : 'Desconocido';
$email = isset($_SESSION['email']) ? $_SESSION['email'] : ' ';
$id_ddjj_head = $_SESSION['id_ddjj_head'];

echo $persona;
echo $apenom;
echo $documento;

// Conexión a la base de datos
require('conexion2.php');

// Verificar si se recibió una solicitud POST con los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Verificar qué tipo de certificado se está procesando
    $tipo_certificado = $_POST['tipo_certificado'];

    // Comienza una transacción para asegurar que todos los datos se guarden de manera atómica
    $pdo->beginTransaction();

    try {
        // Procesar los datos según el tipo de certificado
        switch ($tipo_certificado) {
            case '1': // Certificado de trabajo
                $empresa = $_POST['empresa'];
                $domicilio_empresa = $_POST['domicilio'];
                $telefono_empresa = $_POST['telefono'];
                $contacto_empresa = $_POST['contacto_empresa'];
                $horario = $_POST['horario'];
                $horas_semanales = $_POST['horas'];

                // Guardar el certificado de trabajo
                $certificado_trabajo = $_FILES['certificado'];
                $recibo_sueldo = $_FILES['recibo'];

                // Subir los archivos
                $ruta_certificado_trabajo = subirArchivo($certificado_trabajo, 'trabajo');
                $ruta_recibo_sueldo = subirArchivo($recibo_sueldo, 'recibo');

                // Insertar los datos en la base de datos
                $sql = "
                    INSERT INTO public.ddjj_data 
                    (id_ddjj_head ,persona, id_tipo_certificado, empresa, domicilio, telefono, horario, horas, archivo_certificado, archivo_recibo, estado ,fecha ,contacto_empresa ,apenom, email, documento) 
                    VALUES 
                    (:id_ddjj_head ,:persona, :tipo_certificado, :empresa, :domicilio, :telefono, :horario, :horas, :archivo_certificado, :archivo_recibo, 1, now() ,:contacto_empresa, :apenom, :email, :documento)
                ";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':persona' => $persona,
                    ':tipo_certificado' => $tipo_certificado,
                    ':empresa' => $empresa,
                    ':domicilio' => $domicilio_empresa,
                    ':telefono' => $telefono_empresa,
                    ':horario' => $horario,
                    ':horas' => $horas_semanales,
                    ':archivo_certificado' => $ruta_certificado_trabajo,
                    ':archivo_recibo' => $ruta_recibo_sueldo,
                    'id_ddjj_head'=> $id_ddjj_head,
                    'contacto_empresa' => $contacto_empresa,
                    'apenom' => $apenom,
                    'email' => $email,
                    'documento' => $documento
                ]);
                break;

            case '2': // Certificado de domicilio
                $domicilio = $_POST['domicilio'];
                $localidad = $_POST['localidad'];
                $distancia = $_POST['distancia'];

                // Guardar el certificado de domicilio
                $certificado_domicilio = $_FILES['certificado'];

                // Subir el archivo
                $ruta_certificado_domicilio = subirArchivo($certificado_domicilio, 'domicilio');

                // Insertar los datos en la base de datos
                $sql = "
                    INSERT INTO public.ddjj_data 
                    (id_ddjj_head ,persona, id_tipo_certificado, domicilio, localidad, distancia, archivo_certificado, estado ,fecha, apenom, email, documento) 
                    VALUES 
                    (:id_ddjj_head ,:persona, :tipo_certificado, :domicilio, :localidad, :distancia, :archivo_certificado, 1, now(), :apenom, :email, :documento)
                ";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':persona' => $persona,
                    ':tipo_certificado' => $tipo_certificado,
                    ':domicilio' => $domicilio,
                    ':localidad' => $localidad,
                    ':distancia' => $distancia,
                    ':archivo_certificado' => $ruta_certificado_domicilio,
                    'id_ddjj_head'=> $id_ddjj_head,
                    'apenom' => $apenom,
                    'email' => $email,
                    'documento' => $documento
                ]);
                break;

            case '3': // padres recientes
                // Guardar el certificado de domicilio
                $certificado_padre = $_FILES['certificado'];

                // Subir el archivo
                $ruta_certificado_padre = subirArchivo($certificado_padre, 'padre');

                // Insertar los datos en la base de datos
                $sql = "
                    INSERT INTO public.ddjj_data 
                    (id_ddjj_head ,persona, id_tipo_certificado, archivo_certificado, estado, fecha, apenom, email, documento) 
                    VALUES 
                    (:id_ddjj_head ,:persona, :tipo_certificado, :archivo_certificado, 1, now(), :apenom, :email, :documento)
                ";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':persona' => $persona,
                    ':tipo_certificado' => $tipo_certificado,
                    ':archivo_certificado' => $ruta_certificado_padre,
                    'id_ddjj_head'=> $id_ddjj_head,
                    'apenom' => $apenom,
                    'email' => $email,
                    'documento' => $documento
                ]);
                break;

            case '4': // ayudante cátedra
                $empresa = $_POST['empresa'];
                $telefono_empresa = $_POST['telefono'];
                $horario = $_POST['horario'];
                $horas_semanales = $_POST['horas'];

                // Insertar los datos en la base de datos
                $sql = "
                    INSERT INTO public.ddjj_data 
                    (id_ddjj_head ,persona, id_tipo_certificado, empresa, telefono, horario, horas, estado, fecha, apenom, email, documento) 
                    VALUES 
                    (:id_ddjj_head ,:persona, :tipo_certificado, :empresa, :telefono, :horario, :horas, 1, now(), :apenom, :email, :documento)
                ";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':persona' => $persona,
                    ':tipo_certificado' => $tipo_certificado,
                    ':empresa' => $empresa,
                    ':telefono' => $telefono_empresa,
                    ':horario' => $horario,
                    ':horas' => $horas_semanales,
                    'id_ddjj_head'=> $id_ddjj_head,
                    'apenom' => $apenom,
                    'email' => $email,
                    'documento' => $documento
                    ]);
                break; 

            case '5': // Certificado de capacidades diferentes

                // Guardar el certificado de CD
                $certificado_CD = $_FILES['certificado'];

                // Subir el archivo
                $ruta_certificado_CD = subirArchivo($certificado_CD, 'Capacidades');

                // Insertar los datos en la base de datos
                $sql = "
                    INSERT INTO public.ddjj_data 
                    (id_ddjj_head ,persona, id_tipo_certificado, archivo_certificado, estado ,fecha, apenom, email, documento) 
                    VALUES 
                    (:id_ddjj_head ,:persona, :tipo_certificado, :archivo_certificado, 1, now(), :apenom, :email, :documento)
                ";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':persona' => $persona,
                    ':tipo_certificado' => $tipo_certificado,
                    ':archivo_certificado' => $ruta_certificado_CD,
                    'id_ddjj_head'=> $id_ddjj_head,
                    'apenom' => $apenom,
                    'email' => $email,
                    'documento' => $documento
                ]);
                break;           

            case '6': // Certificado de becas deportivas

                // Guardar el certificado de BD
                $certificado_BD = $_FILES['certificado'];

                // Subir el archivo
                $ruta_certificado_BD = subirArchivo($certificado_BD, 'becas_deportivas');

                // Insertar los datos en la base de datos
                $sql = "
                    INSERT INTO public.ddjj_data 
                    (id_ddjj_head ,persona, id_tipo_certificado, archivo_certificado, estado ,fecha, apenom, email, documento) 
                    VALUES 
                    (:id_ddjj_head ,:persona, :tipo_certificado, :archivo_certificado, 1, now(), :apenom, :email, :documento)
                ";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':persona' => $persona,
                    ':tipo_certificado' => $tipo_certificado,
                    ':archivo_certificado' => $ruta_certificado_BD,
                    'id_ddjj_head'=> $id_ddjj_head,
                    'apenom' => $apenom,
                    'email' => $email,
                    'documento' => $documento
                ]);
                break; 

            case '7': // pasantes
                $empresa = $_POST['empresa'];
                $telefono_empresa = $_POST['telefono'];
                $horario = $_POST['horario'];
                $horas_semanales = $_POST['horas'];

                // Insertar los datos en la base de datos
                $sql = "
                    INSERT INTO public.ddjj_data 
                    (id_ddjj_head ,persona, id_tipo_certificado, empresa, telefono, horario, horas, estado, fecha, apenom, email, documento) 
                    VALUES 
                    (:id_ddjj_head ,:persona, :tipo_certificado, :empresa, :telefono, :horario, :horas, 1, now(), :apenom, :email, :documento)
                ";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':persona' => $persona,
                    ':tipo_certificado' => $tipo_certificado,
                    ':empresa' => $empresa,
                    ':telefono' => $telefono_empresa,
                    ':horario' => $horario,
                    ':horas' => $horas_semanales,
                    'id_ddjj_head'=> $id_ddjj_head,
                    'apenom' => $apenom,
                    'email' => $email,
                    'documento' => $documento
                    ]);
                break;       

            // Agregar más casos según los diferentes tipos de certificados
        }

        // Confirmar la transacción
        $pdo->commit();

        // Redirigir al usuario o mostrar un mensaje de éxito
        header('Location: listar_formulario.php?success=true&persona='.$persona.'&tipo_certificado='.$tipo_certificado.'&archivo='.$ruta_certificado_domicilio);
        exit;

    } catch (Exception $e) {
        // En caso de error, revertir la transacción
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Solicitud inválida.";
}

// Función para subir archivos
function subirArchivo($archivo, $tipo) {
    // Ruta donde se guardarán los archivos
    $directorio = 'uploads/' . $tipo . '/';
    
    // Verificar si el directorio existe, si no, crear uno
    if (!is_dir($directorio)) {
        mkdir($directorio, 0777, true);
    }
    
    // Generar un nombre único para el archivo
    $nombreArchivo = $directorio . uniqid() . '_' . basename($archivo['name']);
    
    // Verificar y mover el archivo
    if (move_uploaded_file($archivo['tmp_name'], $nombreArchivo)) {
        return $nombreArchivo;
    } else {
        throw new Exception('Error al subir el archivo.');
    }
}
?>
