<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Obtener la persona autenticada
$persona = $_SESSION['persona'];
$apenom = isset($_SESSION['apenom']) ? $_SESSION['apenom'] : 'Invitado';
$documento = isset($_SESSION['dni']) ? $_SESSION['dni'] : 'Desconocido';


// Verificar que se ha seleccionado un tipo de certificado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tipo_certificado'])) {
    $tipo_certificado = $_POST['tipo_certificado'];
    $id_ddjj_head = $_POST['id_ddjj_head'];
    
    // Dependiendo del tipo de certificado, carga los campos correspondientes
    switch ($tipo_certificado) {
        case '1': // Certificado de trabajo
            $formulario = "
                <form action='subir_certificado.php' method='POST' enctype='multipart/form-data'>
                    <label>Nombre de la empresa</label>
                    <input type='text' name='empresa' class='form-control' required>

                    <label>Domicilio de la empresa</label>
                    <input type='text' name='domicilio' class='form-control' required>

                    <label>Teléfono de la empresa</label>
                    <input type='text' name='telefono' class='form-control' required>

                    <label>Contacto de la empresa</label>
                    <input type='text' name='contacto_empresa' class='form-control' required>

                    <label>Horario de trabajo</label>
                    <input type='text' name='horario' class='form-control' required>

                    <label>Cantidad de horas semanales</label>
                    <input type='number' name='horas' class='form-control' required>

                    <label>Subir certificado de trabajo (PDF o imagen)</label>
                    <input type='file' name='certificado' class='form-control' accept='.pdf,image/*' required>

                    <label>Subir recibo de sueldo (PDF o imagen)</label>
                    <input type='file' name='recibo' class='form-control' accept='.pdf,image/*' required>
                    <input type='hidden' name='tipo_certificado' value='$tipo_certificado'>
                    <input type='hidden' name='id_ddjj_head' value='$id_ddjj_head'>
                    <button type='submit' class='btn btn-primary mt-3'>Subir</button>
                </form>";
            break;

        case '2': // Certificado de domicilio
            $formulario = "
                <form action='subir_certificado.php' method='POST' enctype='multipart/form-data'>
                    <label>Domicilio</label>
                    <input type='text' name='domicilio' class='form-control' required>

                    <label>Localidad</label>
                    <input type='text' name='localidad' class='form-control' required>

                    <label>Distancia a la facultad (km)</label>
                    <input type='number' name='distancia' class='form-control' required>

                    <label>Subir certificado de domicilio (PDF o imagen)</label>
                    <input type='file' name='certificado' class='form-control' accept='.pdf,image/*' required>
                    <input type='hidden' name='tipo_certificado' value='$tipo_certificado'>
                    <input type='hidden' name='id_ddjj_head' value='$id_ddjj_head'>
                    <button type='submit' class='btn btn-primary mt-3'>Subir</button>
                </form>";
            break;
        case '3': // Certificado de padre reciente
            $formulario = "
                <form action='subir_certificado.php' method='POST' enctype='multipart/form-data'>
                    <label>Subir Partida de nacimiento y fotocopioa DNI en (PDF o imagen)</label>
                    <input type='file' name='certificado' class='form-control' accept='.pdf,image/*' required>
                    <input type='hidden' name='tipo_certificado' value='$tipo_certificado'>
                    <input type='hidden' name='id_ddjj_head' value='$id_ddjj_head'>
                    <button type='submit' class='btn btn-primary mt-3'>Subir</button>
                </form>";
            break;    
        case '4': // Certificado de ayudante catedra
            $formulario = "
                <form action='subir_certificado.php' method='POST' enctype='multipart/form-data'>
                    <label>Nombre de la cátedra</label>
                    <input type='text' name='empresa' class='form-control' required>

                    <label>Teléfono de la cátedra</label>
                    <input type='text' name='telefono' class='form-control' required>

                    <label>Horario de trabajo</label>
                    <input type='text' name='horario' class='form-control' required>

                    <label>Cantidad de horas semanales</label>
                    <input type='number' name='horas' class='form-control' required>
                   
                    <input type='hidden' name='tipo_certificado' value='$tipo_certificado'>
                    <input type='hidden' name='id_ddjj_head' value='$id_ddjj_head'>
                    <button type='submit' class='btn btn-primary mt-3'>Subir</button>
                </form>";
            break;    
        
        case '5': // Certificado de capacidades diferentes
            $formulario = "
                <form action='subir_certificado.php' method='POST' enctype='multipart/form-data'>
                    <label>Subir certificado en (PDF o imagen)</label>
                    <input type='file' name='certificado' class='form-control' accept='.pdf,image/*' required>                   
                    <input type='hidden' name='tipo_certificado' value='$tipo_certificado'>
                    <input type='hidden' name='id_ddjj_head' value='$id_ddjj_head'>
                    <button type='submit' class='btn btn-primary mt-3'>Subir</button>
                </form>";
            break;

        case '6': // Certificado de Becas deportivas
            $formulario = "
                <form action='subir_certificado.php' method='POST' enctype='multipart/form-data'>
                    <label>Subir certificado en (PDF o imagen)</label>
                    <input type='file' name='certificado' class='form-control' accept='.pdf,image/*' required>                   
                    <input type='hidden' name='tipo_certificado' value='$tipo_certificado'>
                    <input type='hidden' name='id_ddjj_head' value='$id_ddjj_head'>
                    <button type='submit' class='btn btn-primary mt-3'>Subir</button>
                </form>";
            break;

        case '7': // Certificado de pasantes
            $formulario = "
                <form action='subir_certificado.php' method='POST' enctype='multipart/form-data'>
                    <label>Nombre de la empresa</label>
                    <input type='text' name='empresa' class='form-control' required>

                    <label>Teléfono de la empresa</label>
                    <input type='text' name='telefono' class='form-control' required>

                    <label>Horario de trabajo</label>
                    <input type='text' name='horario' class='form-control' required>

                    <label>Cantidad de horas semanales</label>
                    <input type='number' name='horas' class='form-control' required>
                   
                    <input type='hidden' name='tipo_certificado' value='$tipo_certificado'>
                    <input type='hidden' name='id_ddjj_head' value='$id_ddjj_head'>
                    <button type='submit' class='btn btn-primary mt-3'>Subir</button>
                </form>";
            break;    

        // Agregar más casos según los diferentes tipos de certificado

        default:
            $formulario = "<p>Tipo de certificado no reconocido.</p>";
            break;
    }
} else {
    header('Location: listar_formulario.php');
    exit;
}

// Mostrar el formulario correspondiente
//echo $formulario;
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
                            <p>persona: <?php echo htmlspecialchars($persona); ?></p>
                            
                        </span>
                    </div>
                    
                    <div class="card-body">
                        <?php echo $formulario ?>
                    </div>
                </div>
            </div>
        </section>
    </section>
</section>

</body>
</html>