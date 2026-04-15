<?php

class Conexion {

    public static function conectar(){

        $host = "localhost";
        $db = "dealer_db";
        $user = "root";
        $pass = "";

        try{

            $conexion = new PDO(
                "mysql:host=$host;dbname=$db;charset=utf8",
                $user,
                $pass
            );

            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $conexion;

        }catch(PDOException $e){

            echo "Error de conexión: " . $e->getMessage();

        }

    }

}