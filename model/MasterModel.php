<?php

    include_once('../lib/conf/Connection.php');

    class MasterModel extends Connection{


    protected function query($sql, $params = []) {
        $result = pg_query_params($this->getConnect(), $sql, $params);

        if (!$result) {
            throw new Exception(pg_last_error($this->getConnect()));
        }

        return $result;
    }
    // Métodos CRUD genéricos

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


        public function autoincrement($table, $field){
            $sql = "SELECT MAX($field) FROM $table";
            $result = pg_query($this -> getConnect(),$sql);
            $max_id = pg_fetch_array($result);
            return $max_id[0] + 1;
        }
    }
    