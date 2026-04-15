<?php

require_once "config/conexion.php";

class Vehiculo {

    private $db;
    public $marca;
    public $modelo;
    public $anio;
    public $precio;
    public $estado;
    public $imagen;

    public function __construct(){
        $this->db = Conexion::conectar();
    }

    // Obtener todos los vehículos
    public function getAll(){
        $sql  = "SELECT * FROM vehiculos ORDER BY id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener uno por ID
    public function getById($id){
        $sql  = "SELECT * FROM vehiculos WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Contar vehículos
    public function contar(){
        $sql  = "SELECT COUNT(*) as total FROM vehiculos";
        $stmt = $this->db->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Guardar nuevo vehículo
    public function guardar(){
        $sql  = "INSERT INTO vehiculos (marca, modelo, anio, precio, estado, imagen)
                 VALUES (:marca, :modelo, :anio, :precio, :estado, :imagen)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':marca'  => $this->marca,
            ':modelo' => $this->modelo,
            ':anio'   => $this->anio,
            ':precio' => $this->precio,
            ':estado' => $this->estado,
            ':imagen' => $this->imagen,
        ]);
    }

    // Actualizar vehículo
    public function actualizar($id){
        $sql  = "UPDATE vehiculos SET marca=:marca, modelo=:modelo, anio=:anio,
                 precio=:precio, estado=:estado, imagen=:imagen WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':marca'  => $this->marca,
            ':modelo' => $this->modelo,
            ':anio'   => $this->anio,
            ':precio' => $this->precio,
            ':estado' => $this->estado,
            ':imagen' => $this->imagen,
            ':id'     => $id,
        ]);
    }

    // Eliminar vehículo
    public function eliminar($id){
        $sql  = "DELETE FROM vehiculos WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
}