<?php
//listar_habilitaciones.php
header("Content-Type: text/html;charset=utf-8");
session_start();

if (isset($_SESSION['loggedinA']) && $_SESSION['loggedinA'] === true) {
    //header('Location: listar_formulario.php');
    //exit;
} else {
    header('Location: login_A.php');
    exit;
}

require('conexion2.php');

// Obtener los registros de habilitaciones existentes
//$sql = "SELECT * FROM public.ddjj_head ORDER BY año DESC, periodo DESC";

$sql = "SELECT e.año, e.periodo, e.desde, e.hasta, e.estado, e.fin_estado,(SELECT COUNT(*) FROM ddjj_data i WHERE i.id_ddjj_head = e.id_ddjj_head) AS cert_count 
    FROM ddjj_head e";


$stmt = $pdo->query($sql);
$registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

include_once('head.php');

if (isset($_GET['mensaje'])) {
    $mensaje = $_GET['mensaje'];
} else {
    $mensaje = "";
}

?>
<style>
    .form-row {
        display: flex;
        flex-wrap: wrap;
        /* Permite que se ajusten en filas si la pantalla es pequeña */
        justify-content: space-between;
        /* Ajustar el espacio entre los elementos */
        align-items: center;
    }

    .form-group {
        margin: 10px;
        flex: 1;
        /* Permite que los elementos ocupen el mismo ancho */
        min-width: 100px;
        /* Evita que los campos se hagan demasiado pequeños */
    }

    button {
        margin-left: 15px;
    }
</style>

<body>

    <section class="container-fluid">
        <section class="row justify-content-center">
            <section col-12 col-sm-4 col-md-2 id="contenedor0">
                <div class="col-md-4" style="margin-bottom: 10px;">
                    <div class="card p-3 mb-5 bg-white rounded" style="width: 65rem;">
                        <div class="card-header">
                            <img class='' src='img/logoFFyb_2024.png' height='50' />
                            <div class="card-body">
                                <div class="col-md-8">
                                    <h4>Administrar habilitaciones para subir Certificados</h4>
                                    <!-- Tabla para mostrar las habilitaciones de certificados -->
                                </div>
                            </div>
                        </div>
                        <div id='mensajes'></div>
                        <!-- Formulario para agregar una nueva habilitación -->

                        <!--<form action="guardar_ddjj_head.php" method="POST">-->
                        <form name='guardar_habilitacion' id='guardar_habilitacion'>
                            <div class="form-row d-flex justify-content-around align-items-center">
                                <div class="form-group">
                                    <label for="año">Año:</label>
                                    <input type="number" name="año" id="agnio" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="periodo">Periodo:</label>
                                    <input type="number" name="periodo" id="periodo" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="desde">Desde:</label>
                                    <input type="date" name="desde" class="form-control" id="desde" required title='Fecha inicio'>
                                </div>
                                <div class="form-group">
                                    <label for="hasta">Hasta:</label>
                                    <input type="date" name="hasta" class="form-control" id="hasta" required title='Fecha límite para subir certificados'>
                                </div>
                                <div class="form-group">
                                    <label for="estado">Estado (Habilitado):</label>
                                    <select name="estado" class="form-control" id="estado">
                                        <option value="true">Activo</option>
                                        <option value="false">Inactivo</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="finestado" title="Fecha límite para cambiar de estado los certificados">Fin Estado:</label>
                                    <input type="date" name="finestado" id="finestado" class="form-control" required title="Fecha límite para cambiar de estado los certificados">
                                </div>

                            </div>
                            <div>
                                <button type="submit" class="btn btn-success">Agregar Habilitación</button>
                            </div>
                        </form>



                        <br>
                        <!-- Fin del formulario -->

                        <!-- Tabla para mostrar las habilitaciones de certificados -->
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Año</th>
                                    <th>Periodo</th>
                                    <th>Desde</th>
                                    <th>Hasta</th>
                                    <th>Estado</th>
                                    <th>Fin Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($registros as $registro) { ?>
                                    <tr>
                                        <td><?= $registro['año'] ?></td>
                                        <td><?= $registro['periodo'] ?></td>
                                        <td><?= $registro['desde'] ?></td>
                                        <td><?= $registro['hasta'] ?></td>
                                        <td><?= $registro['estado'] ? 'Activo' : 'Inactivo' ?></td>
                                        <td><?= $registro['fin_estado'] ?></td>
                                        <td>
                                            <?php if ($registro['cert_count'] > 0) {
                                            ?>
                                                <a href="editar_ddjj_head.php?id=<?= $registro['id_ddjj_head'] ?>" class="btn btn-info btn-sm action-button" data-action="Editar" data-id-habilitacion="11">Editar</a>
                                                <a href="#" class="btn btn-secondary btn-sm" data-action="Eliminar">Eliminar</a>
                                            <?php
                                            } else {
                                            ?>
                                                <a href="editar_ddjj_head.php?id=<?= $registro['id_ddjj_head'] ?>" class="btn btn-info btn-sm action-button" data-action="Editar" data-id-habilitacion="11">Editar</a>
                                                <a href="eliminar_ddjj_head.php?id=<?= $registro['id_ddjj_head'] ?>" onclick="return confirm('¿Estás seguro de eliminar?')" class="btn btn-danger btn-sm action-button" data-action="Eliminar">Eliminar</a>
                                            <?php
                                            }

                                            ?>

                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </section>
    </section>
    <script>
        $("#guardar_habilitacion").submit(function(event) {
            //alert('0');
            if (event.isDefaultPrevented()) {
                // handle the invalid form...
                //alert('a');
            } else {
                // everything looks good!
                event.preventDefault();
                //submitForm();
                //submitForm2();
                //alert('b');
                guardarHabilitacion();
            }
        });

        /*
            document.getElementById('miFormulario').addEventListener('submit', function(event) {
                // Obtener los valores del formulario
                var desde = new Date(document.getElementById('desde').value);
                var hasta = new Date(document.getElementById('hasta').value);
                var finEstado = new Date(document.getElementById('finestado').value);

                // Validar que "hasta" sea mayor que "desde"
                if (hasta <= desde) {
                    alert('La fecha "Hasta" debe ser mayor que la fecha "Desde".');
                    event.preventDefault(); // Prevenir el envío del formulario
                    return;
                }

                // Validar que "fin_estado" sea mayor o igual a "hasta"
                if (finEstado < hasta) {
                    alert('La fecha "Fin Estado" debe ser mayor o igual a la fecha "Hasta".');
                    event.preventDefault(); // Prevenir el envío del formulario
                    return;
                }

                // Si todo está bien, el formulario se enviará automáticamente
            });
        */

        function guardarHabilitacion() {

            $.ajax('guardar_ddjj_head.php', {
                type: 'POST', // http method
                //type: 'GET',  // http method
                dataType: 'json',
                data: {
                    agnio: $('#agnio').val(),
                    periodo: $('#periodo').val(),
                    desde: $('#desde').val(),
                    hasta: $('#hasta').val(),
                    estado: $('#estado').val(),
                    finestado: $('#finestado').val()
                }, // data to submit

                beforeSend: function() {
                    //$('.btn-success').attr("disabled","disabled");
                    $('.container-fluid').addClass('loader');
                },

                success: function(response) {
                    if (response.mensajeOk === true) {
                        //$('#mensajes').html(response.respuesta);
                        location.reload();
                    } else {
                        $('#mensajes').html(response.respuesta);
                    }
                },

                complete: function() {
                    $('.container-fluid').removeClass('loader');
                    //$('#verDetalles').html(response.respuesta);
                },
            });
        }
    </script>>


</body>

</html>