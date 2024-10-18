<?php
header("Content-Type: text/html;charset=utf-8");
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    //header('Location: listar_formulario.php');
    //exit;
}

require('conexion2.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM public.ddjj_head WHERE id_ddjj_head = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $registro = $stmt->fetch(PDO::FETCH_ASSOC);
}

?>

<form action="actualizar_ddjj_head.php" method="POST">
    <input type="hidden" name="id_ddjj_head" value="<?= $registro['id_ddjj_head'] ?>">

    <label for="año">Año:</label>
    <input type="number" name="año" value="<?= $registro['año'] ?>" required>

    <label for="periodo">Periodo:</label>
    <input type="number" name="periodo" value="<?= $registro['periodo'] ?>" required>

    <label for="desde">Desde (Fecha inicio):</label>
    <input type="datetime-local" name="desde" value="<?= $registro['desde'] ?>" required>

    <label for="hasta">Hasta (Fecha límite para subir certificados):</label>
    <input type="datetime-local" name="hasta" value="<?= $registro['hasta'] ?>" required>

    <label for="estado">Estado:</label>
    <select name="estado">
        <option value="true" <?= $registro['estado'] ? 'selected' : '' ?>>Activo</option>
        <option value="false" <?= !$registro['estado'] ? 'selected' : '' ?>>Inactivo</option>
    </select>

    <button type="submit">Actualizar</button>
</form>