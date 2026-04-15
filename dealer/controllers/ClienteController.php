<?php

require_once "models/Cliente.php";

class ClienteController {

    // Listar todos
    public function index(){
        $model    = new Cliente();
        $clientes = $model->getAll();
        require_once "views/clientes/lista.php";
    }

    // Mostrar formulario crear
    public function crear(){
        require_once "views/clientes/crear.php";
    }

    // Guardar nuevo cliente
    public function guardar(){
        $model           = new Cliente();
        $model->nombre   = $_POST['nombre'];
        $model->cedula   = $_POST['cedula'];
        $model->telefono = $_POST['telefono'];
        $model->guardar();
        header("Location: index.php?controller=cliente&action=index");
        exit;
    }

    // Mostrar formulario editar
    public function editar(){
        $id      = $_GET['id'] ?? null;
        $model   = new Cliente();
        $cliente = $model->getById($id);
        require_once "views/clientes/editar.php";
    }

    // Actualizar cliente
    public function actualizar(){
        $id              = $_POST['id'];
        $model           = new Cliente();
        $model->nombre   = $_POST['nombre'];
        $model->cedula   = $_POST['cedula'];
        $model->telefono = $_POST['telefono'];
        $model->actualizar($id);
        header("Location: index.php?controller=cliente&action=index");
        exit;
    }

    // Eliminar cliente
    public function eliminar(){
        $id    = $_GET['id'] ?? null;
        $model = new Cliente();
        $model->eliminar($id);
        header("Location: index.php?controller=cliente&action=index");
        exit;
    }
}