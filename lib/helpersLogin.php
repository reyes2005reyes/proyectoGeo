<?php

function estaLogueado()
{
    return (isset($_SESSION['auth']) && $_SESSION['auth'] == "ok");
}

function tieneModulo($modulo)
{
    if (!isset($_SESSION['permisos'])) {
        return false;
    }

    foreach ($_SESSION['permisos'] as $permiso) {

        if ($permiso['modulo'] == $modulo) {
            return true;
        }

    }

    return false;
}

function tienePermiso($modulo, $accion)
{
    if (!isset($_SESSION['permisos'])) {
        return false;
    }

    foreach ($_SESSION['permisos'] as $permiso) {

        if (
            $permiso['modulo'] == $modulo &&
            $permiso['accion'] == $accion
        ) {
            return true;
        }

    }

    return false;
}

function accesoDenegado()
{
    echo "<h3>No tiene permisos para acceder a esta opción.</h3>";
    exit;
}

function requiereLogin()
{
    if (!estaLogueado()) {

        $_SESSION['error'] = 'Debe iniciar sesión para acceder a esta opción.';
        redirect('login.php');
        exit;

    }
}
?>