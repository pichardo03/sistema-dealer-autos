<?php

require_once "models/Vehiculo.php";
require_once "models/Cliente.php";
require_once "models/Venta.php";

class DashboardController {

    public function index(){

        $vehiculo = new Vehiculo();
        $cliente = new Cliente();
        $venta = new Venta();

        $vehiculos = $vehiculo->contar();
        $clientes = $cliente->contar();
        $ventas = $venta->contar();

        require_once "views/dashboard/index.php";
    }

}