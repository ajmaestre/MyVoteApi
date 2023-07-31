
<?php

    require_once "conexion.php";

    
    class Consulta extends conexion{

        public function __construct() {
            parent::__construct();
        }

        private function toUTF8($array){
            array_walk_recursive($array, function(&$item, $key){
                if(!mb_detect_encoding($item, 'utf-8', true)){
                    $item = utf8_encode($item);
                }
            });
            return $array;
        }

        public function executeQuery($query){
            try {
                $result = $this->conexion->query($query);
                $resultArray = array();
                foreach ($result as $key) {
                    $resultArray[] = $key;
                }
                return $this->toUTF8($resultArray);
            } catch (Exception $e) {
                return false;
            }
        }

        public function executeNotQuery($query){
            try {
                $result = $this->conexion->query($query);
                return $this->conexion->affected_rows;
            } catch (Exception $e) {
                return false;
            }
        }

        protected function encriptPassword($password){
            try{
                return md5($password);
            }catch(Exception $e){
                return false;
            }
        }

    }

?>