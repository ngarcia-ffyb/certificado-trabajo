<?php
header("Content-Type: text/html;charset=utf-8");
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: listar_formulario.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    //$password = $_POST['password']; // No aplicar md5 aquí, se usará la función crypt en la consulta.
    $password2 = md5($_POST['password']);

    // Conexión a la base de datos
    require('conexion.php');


    $sql = "SELECT 
                p.persona, 
                p.apellido, 
                p.nombres, 
                p.usuario, 
                p.clave, 
                d.nro_documento,
                c.email
            FROM 
                negocio.mdp_personas p
            JOIN negocio.mdp_personas_documentos d ON p.persona = d.persona
            JOIN negocio.mdp_personas_contactos c ON p.persona = c.persona
            WHERE 
                p.usuario = :username 
                AND p.clave = crypt(:password, p.clave) 
                AND c.contacto_tipo='MP'";


    // Preparar la consulta
    $stmt = $pdo->prepare($sql);

    // Ejecutar la consulta con valores bind
    $stmt->execute(['username' => $username, 'password' => $password2]);
    //$user = $stmt->fetch(PDO::FETCH_ASSOC);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    //$tipos_certificados = $stmt_tipos->fetchAll(PDO::FETCH_ASSOC);

    // Verificar si se encontró un usuario
    if ($user) {
        $_SESSION['loggedin'] = true;
        $_SESSION['persona'] = $user['persona'];
        $_SESSION['username'] = $username;

        $_SESSION['apenom'] = $user['apellido'] . ' ' . $user['nombres'];  // Guardar apenom
        $_SESSION['dni'] = $user['nro_documento'];  // Guardar documento
        $_SESSION['email'] = $user['email'];  // Guardar documento

        header('Location: listar_formulario.php');
        exit;
    } else {
        $error = "Nombre de usuario o contraseña incorrectos.";
    }
}

include_once('head.php');

?>


<br>

<section class="container-fluid">
    <section class="row justify-content-center">
        <section col-12 col-sm-4 col-md-2 id="contenedor0">
            <div class="col-md-4" style="margin-bottom: 10px;">
                <div class="card p-3 mb-5 bg-white rounded" style="width: 20rem;">
                    <div class="card-header">
                        <img class='' src='img/logoFFyb_2024.png' width='100%' height='50' />
                        <span class='titu'>Ingrese sus datos</span>
                    </div>
                    <div class="card-body">

                        <div class="input-group" id="mensaje"></div>

                        <form action="" name="f1" method="POST" class="form-container">
                            <label for="username">Usuario</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <input type="text" name="username" id="username" class="form-control">
                            </div>
                            <label for="password">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                <input type="password" name="password" id="password" class="form-control">
                            </div>
                            <br />
                            <div class="input-group">
                                <button type="submit" class="btn btn-primary btn-block" id="Iniciar">Ingresar</button>
                            </div>
                        </form>

                        <div class="input-group" id="mensaje2"></div>

                    </div>
                </div>
            </div>
            </div>
        </section>
    </section>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>