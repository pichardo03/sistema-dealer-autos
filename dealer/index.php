<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once __DIR__ . "/controllers/AuthController.php";
require_once __DIR__ . "/controllers/DashboardController.php";
require_once __DIR__ . "/controllers/VehiculoController.php";
require_once __DIR__ . "/controllers/ClienteController.php";
require_once __DIR__ . "/controllers/VentaController.php";
require_once __DIR__ . "/controllers/ReporteController.php";

$controller = $_GET['controller'] ?? 'auth';
$action     = $_GET['action']     ?? 'login';

switch($controller){

    case 'auth':
        $auth = new AuthController();
        if(method_exists($auth, $action)){
            $auth->$action();
        } else {
            $auth->login();
        }
    break;

    case 'dashboard':
        $dashboard = new DashboardController();
        $dashboard->index();
    break;

    case 'vehiculo':
        $vehiculo = new VehiculoController();
        if($action == 'crear'){
            $vehiculo->crear();
        } elseif($action == 'guardar'){
            $vehiculo->guardar();
        } elseif($action == 'editar'){
            $vehiculo->editar();
        } elseif($action == 'actualizar'){
            $vehiculo->actualizar();
        } elseif($action == 'eliminar'){
            $vehiculo->eliminar();
        } else {
            $vehiculo->index();
        }
    break;

    case 'cliente':
        $cliente = new ClienteController();
        if($action == 'crear'){
            $cliente->crear();
        } elseif($action == 'guardar'){
            $cliente->guardar();
        } elseif($action == 'editar'){
            $cliente->editar();
        } elseif($action == 'actualizar'){
            $cliente->actualizar();
        } elseif($action == 'eliminar'){
            $cliente->eliminar();
        } else {
            $cliente->index();
        }
    break;

    case 'venta':
        $venta = new VentaController();
        if($action == 'crear'){
            $venta->crear();
        } elseif($action == 'guardar'){
            $venta->guardar();
        } elseif($action == 'eliminar'){
            $venta->eliminar();
        } else {
            $venta->index();
        }
    break;

    case 'reporte':
        $reporte = new ReporteController();
        if($action == 'pdfVentas'){
            $reporte->pdfVentas();
        } elseif($action == 'csvVentas'){
            $reporte->csvVentas();
        } elseif($action == 'csvVehiculos'){
            $reporte->csvVehiculos();
        } else {
            $reporte->index();
        }
    break;

    default:
        echo "Controlador no válido";
    break;

}