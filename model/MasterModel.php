<?php

include_once '../lib/conf/connection.php';

class MasterModel extends Connection{

    public function select($sql){
        $result = pg_query($this->getConnect(), $sql);

        if(!$result){
            die(pg_last_error($this->getConnect()));
        }

        return $result;
    }

    public function insert($sql){
        $result = pg_query($this->getConnect(), $sql);

        if(!$result){
            die(pg_last_error($this->getConnect()));
        }

        return $result;
    }

    public function update($sql){
        $result = pg_query($this->getConnect(), $sql);

        if(!$result){
            die(pg_last_error($this->getConnect()));
        }

        return $result;
    }

    public function delete($sql){
        $result = pg_query($this->getConnect(), $sql);

        if(!$result){
            die(pg_last_error($this->getConnect()));
        }

        return $result;
    }

    public function findOne($table, $fields, $condition){

        $sql = "SELECT $fields FROM $table WHERE $condition";

        $result = pg_query($this->getConnect(), $sql);

        if(pg_num_rows($result) > 0){
            return $result;
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




    protected function query($sql, $params = array()) {
            $result = pg_query_params($this->getConnect(), $sql, $params);

            if (!$result) {
                throw new Exception(pg_last_error($this->getConnect()));
            }

            return $result;
        }


    public function getLastError() {
            return pg_last_error($this->getConnect());
    }

    public function queryOne($sql, $params = array()) {
            $res = $this->query($sql, $params);
            $row = pg_fetch_assoc($res);
            if ($row === false) {
                return null;
            }
            return $row;
    }
}