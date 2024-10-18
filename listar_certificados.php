<?php
header("Content-Type: text/html;charset=utf-8");
session_start();

/*
// Verifica si el usuario está autenticado
if (!isset($_SESSION['loggedinA']) || $_SESSION['loggedinA'] !== true) {
    header('Location: login_A.php');
    exit;
}
*/
require('v_session.php');

// Obtener la persona autenticada
$persona = $_SESSION['persona'];
$apenom = isset($_SESSION['apenom']) ? $_SESSION['apenom'] : 'Invitado';

require('conexion2.php');

// Obtener años disponibles
$sql_anos = "SELECT DISTINCT año FROM public.ddjj_head WHERE estado = TRUE ORDER BY año DESC";
$stmt_anos = $pdo->prepare($sql_anos);
$stmt_anos->execute();
$anos = $stmt_anos->fetchAll(PDO::FETCH_ASSOC);

// Obtener tipos de certificados
$sql_tipos_certificados = "SELECT id_tipo_certificado, descripcion FROM public.tipo_certificados";
$stmt_tipos = $pdo->prepare($sql_tipos_certificados);
$stmt_tipos->execute();
$tipos_certificados = $stmt_tipos->fetchAll(PDO::FETCH_ASSOC);

include_once('head.php');
?>

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
                                <h4>Administrar Certificados</h4>
                                <form class="form-inline" onsubmit='return(false);'>
                                    <div class="form-group">
                                        <!-- Filtro por Año -->
                                        <label>Año</label>
                                        <select id="select_ano" class="form-control" width='10px'>
                                            <option value="">Seleccione un año</option>
                                            <?php foreach ($anos as $ano) { ?>
                                                <option value="<?php echo $ano['año']; ?>"><?php echo $ano['año']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <!-- Filtro por Periodo (se carga dinámicamente) -->
                                        <label>Periodo</label>
                                        <select id="select_periodo" class="form-control">
                                            <option value="">Seleccione un periodo</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <!-- Filtro por Fechas disponibles (se carga dinámicamente) -->
                                        <label>Fechas</label>
                                        <select id="select_fecha" class="form-control">
                                            <option value="">Seleccione una fecha</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <!-- Filtro por Tipo de Certificado -->
                                        <label>Tipo de Certificado</label>
                                        <select id="select_tipo_certificado" class="form-control">
                                            <option value="">Seleccione un tipo de certificado</option>
                                            <?php foreach ($tipos_certificados as $tipo) { ?>
                                                <option value="<?php echo $tipo['id_tipo_certificado']; ?>"><?php echo $tipo['descripcion']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="estado">Estado</label>
                                        <select name="estado" id="estado" class="form-control">
                                            <option value="">Todos</option>
                                            <option value="2">Aceptado</option>
                                            <option value="3">Rechazado</option>
                                            <option value="1">Pendiente</option>
                                        </select>
                                    </div>

                                    <button id="filtrar" class="btn btn-primary mt-3">Filtrar</button>
                                </form>
                                <!-- Tabla para mostrar los certificados -->
                            </div>
                        </div>
                    </div>
                    <table class="table mt-3">
                        <thead>
                            <tr>
                                <th>Documento</th>
                                <th>Apellido y Nombres</th>
                                <th>Tipo Certificado</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="resultados_certificados">
                            <!-- Aquí se cargan los resultados de la búsqueda -->
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </section>
</section>

<!-- Modal para mostrar la vista del certificado -->
<div class="modal fade" id="certificadoModal" tabindex="-1" role='dialog' aria-labelledby="certificadoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="certificadoModalLabel"> </h5>
                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>&times;</button>
            </div>
            <div class="modal-body" id="modal-body-content">
                <!-- Aquí se cargará el contenido del certificado -->
            </div>
            <div class="modal-footer">
                <button type='button' class='btn btn-secondary' data-dismiss='modal'>Cerrar.</button>
            </div>
        </div>
    </div>
</div>

<script>
// Función para cargar los periodos según el año seleccionado
document.getElementById('select_ano').addEventListener('change', function() {
    var ano = this.value;
    var select_periodo = document.getElementById('select_periodo');
    select_periodo.innerHTML = '<option value="">Cargando periodos...</option>';

    fetch('get_periodos.php?ano=' + ano)
        .then(response => response.json())
        .then(data => {
            select_periodo.innerHTML = '<option value="">Seleccione un periodo</option>';
            data.forEach(function(periodo) {
                var option = document.createElement('option');
                option.value = periodo.id_ddjj_head;
                option.textContent = periodo.periodo;
                select_periodo.appendChild(option);
            });
        });
});

// Cargar las fechas según el año y periodo seleccionado
document.getElementById('select_periodo').addEventListener('change', function() {
    var id_ddjj_head = this.value;
    var select_fecha = document.getElementById('select_fecha');
    select_fecha.innerHTML = '<option value="">Cargando fechas...</option>';

    fetch('get_fechas.php?id_ddjj_head=' + id_ddjj_head)
        .then(response => response.json())
        .then(data => {
            select_fecha.innerHTML = '<option value="">Seleccione una fecha</option>';
            data.forEach(function(fecha) {
                var option = document.createElement('option');
                option.value = fecha.fecha;
                option.textContent = fecha.fecha;
                select_fecha.appendChild(option);
            });
        });
});

// Función para filtrar y mostrar los certificados pendientes
document.getElementById('filtrar').addEventListener('click', function() {
    var ano = document.getElementById('select_ano').value;
    var periodo = document.getElementById('select_periodo').value;
    var fecha = document.getElementById('select_fecha').value;
    var tipo_certificado = document.getElementById('select_tipo_certificado').value;
    var estado = document.getElementById('estado').value;

    fetch(`listar_certificados_filtrados.php?ano=${ano}&periodo=${periodo}&fecha=${fecha}&tipo_certificado=${tipo_certificado}&estado=${estado}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('resultados_certificados').innerHTML = html;
        });
});

// Función para cargar el modal con el contenido del certificado
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('action-button')) {
        event.preventDefault();

        var button = event.target;
        var action = button.getAttribute('data-action');
        var href = button.getAttribute('href');

        
        // Dependiendo de la acción, se obtiene la confirmación del usuario
        var confirmMessage = '';
        if (action === 'Ver') {
            confirmMessage = '¿Desea ver el certificado?';
        } else if (action === 'Aceptar') {
            confirmMessage = '¿Está seguro de que desea aceptar este certificado?';
        } else if (action === 'Rechazar') {
            confirmMessage = '¿Está seguro de que desea rechazar este certificado?';
        } else if (action === 'Eliminar') {
            confirmMessage = '¿Está seguro de que desea eliminar este certificado?';
        }

        if (confirm(confirmMessage)) {
            // Si la acción es "Ver", se carga el certificado en el modal
            if (action === 'Ver') {
                var url = new URL(href, window.location.origin);
                var params = new URLSearchParams(url.search);
                var id_certificado = params.get("id_certificado"); // se utiliza el método GET para captar el valor del parámetro nombre
                
                $.ajax({
                    type:"GET",
                    dataType:'json',
                    url:'ver_certificado_ajax.php',
                    data:{id_certificado:id_certificado},
                    success:function(response){
                        if(response.respuesta==true){
                            $("#modal-body-content").html(response.MyData);
                            $('#certificadoModal').modal('show');
                        }else{
                            $("#modal-body-content").html(response.mensaje);
                            $('#certificadoModal').modal('show');
                        }
                    },error:function(){
                        alert('Error general en el sistema');
                    }
                });
                
            } else {
                // Si es otra acción (Aceptar/Rechazar/Eliminar), se redirige al href original
                window.location.href = href;
            }
        }
    }
});

</script>
<script type="text/javascript" src="js/tether.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>

</body>
</html>
