<?php
    
    include_once __DIR__ . '/../lib/helpers.php';
    include_once __DIR__ . '/../lib/helpersLogin.php';
    include_once __DIR__ . '/../view/partials/header.php';
    
    echo "<body>";

        echo "<div class='container'>";
            include_once __DIR__ . "/../view/partials/navbar.php";

            if(isset($_GET["modulo"])){
                resolve();
            }else{
                session_destroy();
            }

            include_once __DIR__ . "/../view/partials/footer.php";
        echo "</div>";
    
    echo "</body>";
    echo "</html>";


?>

