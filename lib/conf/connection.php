<?php
    // Se crarn los mismos atributos de la configuracion solo que se le añadio el atributo "link".
    class Connection {
        private $server;
        private $user;
        private $password;
        private $database;
        private $port;
        private $link;


        // El constructor se utiliza dos guiones bajos separados de la funcion
        function __construct(){
            $this-> setConnection(); // Asigna los parametros de conexion.
            $this-> connect(); // Conecta con la base de datos.
        }

        //require requiere  el archivo configuracion.php para poder ejecutar
        //Si el archivo no esta no existe te laza el error.
        //Para sacar las comillas simples al lado del cero.
        private function setConnection(){
            require 'conf.php';
            $this->server = $server;
            $this->user = $user;
            $this->password = $password;
            $this->database = $database;
            $this->port = $port;
        }
        
        private function connect(){
            // Se debe poner en el orden en el cual esta el archivo 'configuracion.php' de lo contrario saldra error.
            $this->link = @pg_connect("host={$this->server} port={$this->port} dbname={$this->database} user={$this->user} password={$this->password}");
            
            // El if se hace para ver si la conexion existe.
            if(!$this->link){
                throw new Exception("Fallo en la conexion con la base de datos."); 
                // Mostrara cual es el error o se puede tambien escribir un mensaje diciendo que en la conexion hubo un error.
            } else {
                // Si la conexion es exitosa se puede mostrar un mensaje diciendo que la conexion fue exitosa.
                // echo "Conexion exitosa.";
            }
        }

        public function getConnect(){
            return $this->link;
        }

        public function close(){
            pg_close($this->link);
        }
    } 
?>