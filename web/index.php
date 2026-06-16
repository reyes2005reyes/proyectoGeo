<?php include_once '../lib/helpers.php'; ?>
<?php include_once '../view/partials/header.php'; ?>
<?php include_once '../view/partials/navbar.php'; ?>

    <?php if(isset($_SESSION['bienvenida'])): ?>
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?php echo $_SESSION['bienvenida']; unset($_SESSION['bienvenida']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php
    if(isset($_GET["modulo"])){
        $modulosProtegidos = array(
            'usuarios',
        );
        $modulo = strtolower($_GET['modulo']);
        if(in_array($modulo, $modulosProtegidos)){
            include_once '../lib/helpersLogin.php';
        }
        resolve();
    } else {
        
    }
    ?>

<?php include_once '../view/partials/footer.php'; ?>
