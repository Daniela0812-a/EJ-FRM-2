<?php

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    http_response_code(405);
    echo '<h1>Método no permitido</h1>';
    exit;
}

function clean_str(string $s): string
{
    return trim($s);
}

function h(?string $s): string
{
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
}

$full_name = clean_str($_POST['full-name'] ?? '');
$e_mail = filter_var(($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$pass = $_POST['pass'] ?? '';
$age = filter_var(($_POST['age'] ?? ''), FILTER_VALIDATE_INT);
$reg_date = $_POST['reg-date'] ?? '';
$rol = $_POST['rol'] ?? '';
$sch = $_POST['sch'] ?? '';
$tyc = isset($_POST['tc']) && $_POST['tc'] == '1';
$comments = clean_str($_POST['comments'] ?? '');

$errors = [];

if (mb_strlen($full_name) < 3 || mb_strlen($full_name) > 90) {
    $errors[] = "Nombre no valido";
}
if (!$e_mail) {
    $errors[] = "Correo electrónico no valido";
}
if (mb_strlen($pass) < 6) {
    $errors[] = "Contraseña no valido";
}
if ($age == false || $age < 18 || $age > 120) {
    $errors[] = "Contraseña no valido";
}
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $reg_date)) {
    $errors[] = "Fecha no valido";
}
if (!in_array($rol, ['s', 't', 'g'])) {
    $errors[] = "Rol no válido";
}
if (!in_array($sch, ['m', 'a', 'n'])) {
    $errors[] = "Horario no válido";
}
if (!$tyc) {
    $errors[] = "Debes aceptar términos";
}

// Si hay errores, muéstralos y link para volver
if ($errors) {
    http_response_code(422);
    echo "<!doctype html><html lang='es'><meta charset='utf-8'><title>Errores</title>";
    echo "<body style='font-family:system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; max-width:720px; margin:2rem auto;'>";
    echo "<h1>Errores en el formulario</h1><ul>";
    foreach ($errors as $e)
        echo "<li>" . h($e) . "</li>";
    echo "</ul><p><a href='/EJ-FRM-2'>← Volver al formulario</a></p></body></html>";
    exit;
}

/*
Aquí se deben realizar las operaciones en la base de datos
*/
require './utils/db.php';
$fn = $_POST['full-name'];


try {
    $q = "INSERT INTO public.person(full_name)VALUES (:fn);";
    $stmt = $pdo->prepare($q);
    $stmt->execute(["fn" => $fn]);
    /*echo json_encode(
        [
            "success" => true,
            "message" => "Created O.K."
        ]
    );*/

} catch (PDOException $e) {
    /*echo json_encode(
        [
            "success" => false,
            "message" => $e->getMessage()
        ]
    );*/
}

?>

<!doctype html>
<html lang="es">
<meta charset="utf-8">
<title>Registro exitoso</title>

<body
    style="font-family:system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; max-width:720px; margin:2rem auto;">
    <h1>¡Registro exitoso!</h1>
    <p>Gracias, <strong><?= h($full_name) ?></strong>. Estos son los datos recibidos:</p>
    <ul>
        <li><b>Email:</b> <?= h($e_mail) ?></li>
        <li><b>Edad:</b> <?= h((string) $age) ?></li>
        <li><b>Fecha:</b> <?= h($reg_date) ?></li>
        <li><b>Rol:</b> <?= h($rol) ?></li>
        <li><b>Horario:</b> <?= h($sch) ?></li>
        <li><b>Aceptó términos:</b> <?= $tyc ? 'Sí' : 'No' ?></li>
        <li><b>Comentarios:</b> <?= nl2br(h($comments)) ?></li>
    </ul>
    <p><a href="/EJ-FRM-2">← Volver al formulario</a></p>
</body>

</html>

