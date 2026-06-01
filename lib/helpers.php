<?php

session_start();
function redirect($url)
{
    echo "<script>";
    echo "window.location = '$url'";
    echo "</script>";
}

function dd($var)
{
    echo "<pre>";
    die(print_r($var));
}

function getUrl($modulo, $controlador, $funcion, $parametros=false, $pagina = false){

    if($pagina == false){
        $pagina = "index";
    }

       // Si es ajax, usar ruta absoluta
    if($pagina == 'ajax'){
        $url = "/proyectoGeo/web/ajax.php?modulo=$modulo&controlador=$controlador&funcion=$funcion";
    } else {
        $url = "$pagina.php?modulo=$modulo&controlador=$controlador&funcion=$funcion";
    }

    if($parametros != false){
        foreach ($parametros as $key => $value) {
            $url .= "&$key=$value";
        }
    } 
    
    return $url;
}

function resolve()
{
    $modulo = ucwords($_GET['modulo']); // modulo --> Es la carpeta dentro de controller
    $controlador = ucwords($_GET['controlador']); // controlador --> Es el archivo dentro de la carpeta modulo
    $funcion = $_GET['funcion']; // funcion --> Es el metodo dentro de la clase controlador

    
    // TODA RUTA EMPIEZA DESDE index.php --> carpeta web

    if (is_dir("../controller/$modulo")) { //is_dir verifica si el directorio existe en la ruta especificada

        if (is_file("../controller/$modulo/".$controlador ."Controller.php")) { //is_file verifica si el archivo existe en la ruta especificada

            include_once "../controller/$modulo/$controlador"."Controller.php";

            $nombreClase = $controlador."Controller";

            $objeto = new $nombreClase();

            if (method_exists($objeto, $funcion)) {
                $objeto->$funcion();
            } else {
                echo "La funcion especificada no existe";
            }
        } else {
            echo "El controlador espeficicado no existe";
        }
    } else {
        echo "El modulo especificado no existe";
    }
}