<?php

// Esta función verifica si el usuario está logueado
function estaLogueado()
{
    return (isset($_SESSION['auth']) && $_SESSION['auth'] == "ok");
}
// Esta función verifica si el usuario tiene un módulo específico
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
// Esta función verifica si el usuario tiene un permiso específico
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
// Esta función redirige a una URL específica
function accesoDenegado()
{
    echo "<h3>No tiene permisos para acceder a esta opción.</h3>";
    exit;
}
// Esta función redirige a una URL específica
function requiereLogin()
{
    if (!estaLogueado()) {

        $_SESSION['error'] = 'Debe iniciar sesión para acceder a esta opción.';
        redirect('login.php');
        exit;

    }
}
?>