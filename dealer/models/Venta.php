<?php

require_once "config/conexion.php";

class Venta {

    private $db;
    public $cliente_id;
    public $vehiculo_id;
    public $total;
    public $fecha;

    public function __construct(){
        $this->db = Conexion::conectar();
    }

    // Obtener todas las ventas con datos de cliente y vehículo
    public function getAll(){
        $sql = "SELECT v.*, 
                       c.nombre   AS cliente_nombre,
                       c.cedula   AS cliente_cedula,
                       ve.marca   AS vehiculo_marca,
                       ve.modelo  AS vehiculo_modelo,
                       ve.anio    AS vehiculo_anio
                FROM ventas v
                LEFT JOIN clientes  c  ON v.cliente_id  = c.id
                LEFT JOIN vehiculos ve ON v.vehiculo_id = ve.id
                ORDER BY v.id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener una venta por ID
    public function getById($id){
        $sql = "SELECT v.*,
                       c.nombre   AS cliente_nombre,
                       c.cedula   AS cliente_cedula,
                       ve.marca   AS vehiculo_marca,
                       ve.modelo  AS vehiculo_modelo,
                       ve.anio    AS vehiculo_anio
                FROM ventas v
                LEFT JOIN clientes  c  ON v.cliente_id  = c.id
                LEFT JOIN vehiculos ve ON v.vehiculo_id = ve.id
                WHERE v.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Contar ventas
    public function contar(){
        $sql  = "SELECT COUNT(*) as total FROM ventas";
        $stmt = $this->db->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Guardar nueva venta
    public function guardar(){
        $sql  = "INSERT INTO ventas (cliente_id, vehiculo_id, total, fecha)
                 VALUES (:cliente_id, :vehiculo_id, :total, :fecha)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':cliente_id'  => $this->cliente_id,
            ':vehiculo_id' => $this->vehiculo_id,
            ':total'       => $this->total,
            ':fecha'       => $this->fecha,
        ]);
    }

    // Eliminar venta
    public function eliminar($id){
        $sql  = "DELETE FROM ventas WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
    }

    // Obtener todos los clientes (para el select)
    public function getClientes(){
        $sql  = "SELECT id, nombre FROM clientes ORDER BY nombre ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener vehículos disponibles (para el select)
    public function getVehiculos(){
        $sql  = "SELECT id, marca, modelo, anio, precio 
                 FROM vehiculos 
                 WHERE estado = 'disponible' 
                 ORDER BY marca ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}