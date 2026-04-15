<?php

require_once "models/Vehiculo.php";

class VehiculoController {

    // Listar todos
    public function index(){
        $vehiculo  = new Vehiculo();
        $vehiculos = $vehiculo->getAll();
        require_once "views/vehiculos/lista.php";
    }

    // Mostrar formulario crear
    public function crear(){
        require_once "views/vehiculos/crear.php";
    }

    // Guardar nuevo vehículo
    public function guardar(){
        $vehiculo         = new Vehiculo();
        $vehiculo->marca  = $_POST['marca'];
        $vehiculo->modelo = $_POST['modelo'];
        $vehiculo->anio   = $_POST['anio'];
        $vehiculo->precio = $_POST['precio'];
        $vehiculo->estado = $_POST['estado'];

        if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0){
            $nombreImagen  = time() . "_" . basename($_FILES['imagen']['name']);
            move_uploaded_file($_FILES['imagen']['tmp_name'], "uploads/" . $nombreImagen);
            $vehiculo->imagen = $nombreImagen;
        } else {
            $vehiculo->imagen = null;
        }

        $vehiculo->guardar();
        header("Location: index.php?controller=vehiculo&action=index");
        exit;
    }

    // Mostrar formulario editar
    public function editar(){
        $id       = $_GET['id'] ?? null;
        $model    = new Vehiculo();
        $vehiculo = $model->getById($id);
        require_once "views/vehiculos/editar.php";
    }

    // Actualizar vehículo
    public function actualizar(){
        $id            = $_POST['id'];
        $model         = new Vehiculo();
        $model->marca  = $_POST['marca'];
        $model->modelo = $_POST['modelo'];
        $model->anio   = $_POST['anio'];
        $model->precio = $_POST['precio'];
        $model->estado = $_POST['estado'];

        if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0){
            $nombreImagen  = time() . "_" . basename($_FILES['imagen']['name']);
            move_uploaded_file($_FILES['imagen']['tmp_name'], "uploads/" . $nombreImagen);
            $model->imagen = $nombreImagen;
        } else {
            $actual        = $model->getById($id);
            $model->imagen = $actual['imagen'] ?? null;
        }

        $model->actualizar($id);
        header("Location: index.php?controller=vehiculo&action=index");
        exit;
    }

    // Eliminar vehículo
    public function eliminar(){
        $id    = $_GET['id'] ?? null;
        $model = new Vehiculo();
        $model->eliminar($id);
        header("Location: index.php?controller=vehiculo&action=index");
        exit;
    }
}