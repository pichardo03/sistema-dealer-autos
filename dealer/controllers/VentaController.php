<?php

if(!class_exists('Venta')){
    require_once "models/Venta.php";
}

class VentaController {

    // Listar todas las ventas
    public function index(){
        $model  = new Venta();
        $ventas = $model->getAll();
        require_once "views/ventas/lista.php";
    }

    // Mostrar formulario crear
    public function crear(){
        $model     = new Venta();
        $clientes  = $model->getClientes();
        $vehiculos = $model->getVehiculos();
        require_once "views/ventas/crear.php";
    }

    // Guardar nueva venta
    public function guardar(){
        $model              = new Venta();
        $model->cliente_id  = $_POST['cliente_id'];
        $model->vehiculo_id = $_POST['vehiculo_id'];
        $model->total       = $_POST['total'];
        $model->fecha       = $_POST['fecha'];
        $model->guardar();
        header("Location: index.php?controller=venta&action=index");
        exit;
    }

    // Eliminar venta
    public function eliminar(){
        $id    = $_GET['id'] ?? null;
        $model = new Venta();
        $model->eliminar($id);
        header("Location: index.php?controller=venta&action=index");
        exit;
    }
}