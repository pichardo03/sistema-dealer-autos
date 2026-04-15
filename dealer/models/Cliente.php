<?php

require_once "config/conexion.php";

class Cliente {

    private $db;
    public $nombre;
    public $cedula;
    public $telefono;

    public function __construct(){
        $this->db = Conexion::conectar();
    }

    // Obtener todos los clientes
    public function getAll(){
        $sql  = "SELECT * FROM clientes ORDER BY id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener uno por ID
    public function getById($id){
        $sql  = "SELECT * FROM clientes WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Contar clientes
    public function contar(){
        $sql  = "SELECT COUNT(*) as total FROM clientes";
        $stmt = $this->db->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Guardar nuevo cliente
    public function guardar(){
        $sql  = "INSERT INTO clientes (nombre, cedula, telefono)
                 VALUES (:nombre, :cedula, :telefono)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':nombre'   => $this->nombre,
            ':cedula'   => $this->cedula,
            ':telefono' => $this->telefono,
        ]);
    }

    // Actualizar cliente
    public function actualizar($id){
        $sql  = "UPDATE clientes SET nombre=:nombre, cedula=:cedula,
                 telefono=:telefono WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':nombre'   => $this->nombre,
            ':cedula'   => $this->cedula,
            ':telefono' => $this->telefono,
            ':id'       => $id,
        ]);
    }

    // Eliminar cliente
    public function eliminar($id){
        $sql  = "DELETE FROM clientes WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
}