<?php
    
    include_once '../lib/helpers.php';
    include_once '../lib/helpersLogin.php';
    include_once '../view/partials/header.php';
    
    echo "<body>";

        echo "<div class='container'>";
            include_once "../view/partials/navbar.php";

            if(isset($_GET["modulo"])){
                resolve();
            }else{
                session_destroy();
            }

            include_once "../view/partials/footer.php";
        echo "</div>";
    
    echo "</body>";
    echo "</html>";


?>

