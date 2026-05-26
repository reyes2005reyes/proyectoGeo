<?php include_once '../lib/helpers.php'; ?>
<?php include_once '../view/partials/header.php'; ?>
<?php include_once '../view/partials/navbar.php'; ?>

    <div class="main-panel">
        <?php include_once '../view/partials/panelIzquierdo.php'; ?>

        <div class="container">
            <div class="page-inner">

                <?php
                if(isset($_GET["modulo"])){
                    $modulosProtegidos = ['usuarios', 'solicitudes', 'perfil', 'reportes'];
                    $modulo = strtolower($_GET['modulo']);
                    if(in_array($modulo, $modulosProtegidos)){
                        include_once '../lib/helpersLogin.php';
                        exit();
                    }
                    resolve();
                } else {
                    //include_once '../view/partials/mapa.php';
                }
                ?>

            </div>
        </div>
        <?php include_once '../view/partials/footer.php'; ?>
    </div>