<?php
    abstract class Model {
        private static $_co;
        
        private static function setCo() {
            self::$_co = new PDO("mysql:host=localhost:3306;dbname=camagrudb", 'adm', 'clemclem');
            self::$_co->setAttribute(PDO::ERRMODE_EXCEPTION, PDO::ERRMODE_WARNING);
            self::$_co->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        }

        protected function getCo() {
            if (self::$_co == null)
                self::setCo();
            return (self::$_co);
        }

        protected function getAll($table, $obj) {
            $tab = [];
            $req = self::$_co->prepare("SELECT * FROM $table");
            $req->execute();
            while ($data = $req->fetch(PDO::FETCH_ASSOC)) {
                $tab[] = new $obj($data);
            }
            return $tab;
            $req->closeCursor();
        }
    }
?>