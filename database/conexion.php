
<?php

    class conexion{

        private $server;
        private $user;
        private $password;
        private $database;
        private $port;
        public $conexion;

        public function __construct() {
            try {
                $datosLista = $this->datosConexion();
                foreach ($datosLista as $key => $value) {
                    $this->server = $value["server"];
                    $this->user = $value["user"];
                    $this->password = $value["password"];
                    $this->database = $value["database"];
                    $this->port = $value["port"];
                }
                $this->conexion = new mysqli($this->server, $this->user, $this->password, $this->database, $this->port);
            } catch (Exception $e) {
                return "Excepcion: $e";
            }
        }

        private function datosConexion(){
            $direccion = dirname(__FILE__);
            $jsondata = file_get_contents($direccion . "/" . "config");
            return json_decode($jsondata, true);
        }

    }

?>