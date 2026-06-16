<?php

include_once '../lib/conf/connection.php';

class MasterModel extends Connection{

    protected function query($sql, $params = array()) {

        if (count($params) > 0) {
            return pg_query_params($this->getConnect(), $sql, $params);
        }

        return pg_query($this->getConnect(), $sql);
    }

    public function select($sql){
        $result = pg_query($this->getConnect(), $sql);

        if(!$result){
            die(pg_last_error($this->getConnect()));
        }

            return $result;
        }
        //SELECT Sirve para LISTAR por si acaso.
        public function select($sql){
            $result = pg_query($this -> getConnect(),$sql);
            return $result;
        }
        public function insert($sql){
            $result = pg_query($this -> getConnect(),$sql);
            return $result;
        }
        public function update($sql){
            $result = pg_query($this -> getConnect(),$sql);
            return $result;
        }
        public function delete($sql){
            $result = pg_query($this -> getConnect(),$sql);
            return $result;
        }
        public function findOne($table, $fields, $condition){
            $sql = "SELECT $fields FROM $table WHERE $condition";
            $result = pg_query($this -> getConnect(),$sql);
            if(pg_num_rows($result) > 0){
                return $result;
            }else{
                echo "No se encontro ningun registro";
            }
        }

        return false;
    }

    public function autoincrement($table, $field){

        $sql = "SELECT MAX($field) AS maximo FROM $table";

        $result = pg_query($this->getConnect(), $sql);

        $row = pg_fetch_assoc($result);

        if(!$row['maximo']){
            return 1;
        }

        return $row['maximo'] + 1;
    }
}