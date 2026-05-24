<!-- Este index no tiene nada que ver con lo de NICOLAS. LO cree yo
Victor, con el proposito de hacer mas facil la navegacion entre archivos
php y asi. NO eliminar por favor. -->


<?php



$path = $_GET['path'] ?? '.';
$fullPath = realpath($path);

// Seguridad básica
if ($fullPath === false || strpos($fullPath, __DIR__) !== 0) {
    die("Acceso denegado");
}

$files = scandir($fullPath);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <meta charset="UTF-8">
    <title>Explorador</title>

    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>

<h2>Explorandouu: <?php echo htmlspecialchars($path); ?></h2>
<ul>

<?php
// Subir nivel
if ($path !== '.') {
    $parent = dirname($path);
    echo "<li><a href='?path=$parent'>⬆️ Volver</a></li>";
}

foreach ($files as $file) {
    if ($file === '.' || $file === '..') continue;

    $newPath = ($path === '.') ? $file : "$path/$file";

    if (is_dir($newPath)) {
        echo "<li>📁 <a href='?path=$newPath'>$file</a></li>";
    } else {
        echo "<li>📄 <a href='$newPath' >$file</a></li>";
    }
}
?>

</ul>

</body>
</html>