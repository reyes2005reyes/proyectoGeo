<?php

require_once __DIR__ . '/../../model/listaUsuarios/listaUsuariosModel.php';

class listaUsuariosController {

    public function lista() {
        $numeroDocumento = isset($_GET['numero_documento']) ? trim($_GET['numero_documento']) : '';

        $model = new ListaUsuariosModel();
        $usuarios = $model->obtenerUsuarios($numeroDocumento);

        // pasar variables a la vista
        require_once __DIR__ . '/../../view/listaUsuarios/listaUsuarios.php'; // Se corrigio la ruta del archivo de vista para que apunte a la carpeta correcta
    }
}
?>
