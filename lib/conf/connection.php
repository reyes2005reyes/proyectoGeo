<?php
    class Connection {
        private $server;
        private $user;
        private $password;
        private $database;
        private $port;
        private $link;

        function __construct(){
            $this->setConnection();
            $this->connect();
        }

        private function setConnection(){
            require __DIR__ . '/conf.php';
            $this->server = $server;
            $this->user = $user;
            $this->password = $password;
            $this->database = $database;
            $this->port = $port;
        }
        
        private function connect(){
            $portsToTry = [$this->port, 5433, 5434]; // Puertos alternativos en caso de que el principal no funcione
            $connectionString = "host={$this->server} port={$this->port} dbname={$this->database} user={$this->user} password={$this->password}";
            
            // Intentar conexión principal
            $this->link = @pg_connect($connectionString);
            
            if(!$this->link) {
                // Si falla el puerto principal, intentar puertos alternativos
                $portFound = false;
                foreach($portsToTry as $altPort) {
                    if($altPort === $this->port) continue; // Saltar el puerto ya intentado
                    
                    $altConnectionString = "host={$this->server} port={$altPort} dbname={$this->database} user={$this->user} password={$this->password}";
                    $this->link = @pg_connect($altConnectionString);
                    
                    if($this->link) {
                        $this->port = $altPort;
                        error_log("Conexión exitosa en puerto alternativo: {$altPort}");
                        $portFound = true;
                        break;
                    }
                }
                
                if(!$portFound) {
                    $error = "❌ ERROR DE CONEXIÓN A POSTGRESQL\n";
                    $error .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
                    $error .= "Host: {$this->server}\n";
                    $error .= "Usuario: {$this->user}\n";
                    $error .= "Base de datos: {$this->database}\n";
                    $error .= "Puertos intentados: " . implode(", ", $portsToTry) . "\n";
                    $error .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
                    $error .= "Posibles causas:\n";
                    $error .= "1. PostgreSQL no está ejecutándose\n";
                    $error .= "2. Puerto incorrecto en conf.php\n";
                    $error .= "3. Credenciales inválidas (usuario/contraseña)\n";
                    $error .= "4. Base de datos '{$this->database}' no existe\n";
                    $error .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
                    $error .= "Verificar: psql -h {$this->server} -U {$this->user} -d {$this->database}\n";
                    
                    error_log($error);
                    die("<pre>" . htmlspecialchars($error) . "</pre>");
                }
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