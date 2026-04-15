<?php

require_once "models/Venta.php";
require_once "models/Vehiculo.php";
require_once "models/Cliente.php";

class ReporteController {

    // Vista principal de reportes
    public function index(){
        $ventaModel   = new Venta();
        $vehiculoModel = new Vehiculo();
        $clienteModel  = new Cliente();

        $ventas    = $ventaModel->getAll();
        $vehiculos = $vehiculoModel->getAll();
        $clientes  = $clienteModel->getAll();

        require_once "views/reportes/index.php";
    }

    // Generar reporte PDF de ventas
    public function pdfVentas(){
        $model  = new Venta();
        $ventas = $model->getAll();
        require_once "views/reportes/pdf_ventas.php";
    }

    // Exportar ventas a CSV
    public function csvVentas(){
        $model  = new Venta();
        $ventas = $model->getAll();

        // Cabeceras para descarga de CSV
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="reporte_ventas_' . date('Y-m-d') . '.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');

        // BOM para que Excel muestre tildes correctamente
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        // Encabezados del CSV
        fputcsv($output, [
            'ID',
            'Cliente',
            'Cédula',
            'Vehículo',
            'Año',
            'Total (RD$)',
            'Fecha'
        ], ';');

        // Filas de datos
        foreach ($ventas as $v) {
            fputcsv($output, [
                $v['id'],
                $v['cliente_nombre']  ?? '—',
                $v['cliente_cedula']  ?? '—',
                ($v['vehiculo_marca'] ?? '') . ' ' . ($v['vehiculo_modelo'] ?? ''),
                $v['vehiculo_anio']   ?? '—',
                number_format($v['total'], 2, '.', ','),
                date('d/m/Y', strtotime($v['fecha']))
            ], ';');
        }

        fclose($output);
        exit;
    }

    // Exportar vehículos a CSV
    public function csvVehiculos(){
        $model     = new Vehiculo();
        $vehiculos = $model->getAll();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="reporte_vehiculos_' . date('Y-m-d') . '.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        fputcsv($output, [
            'ID', 'Marca', 'Modelo', 'Año', 'Precio (RD$)', 'Estado', 'Fecha registro'
        ], ';');

        foreach ($vehiculos as $v) {
            fputcsv($output, [
                $v['id'],
                $v['marca'],
                $v['modelo'],
                $v['anio'],
                number_format($v['precio'], 2, '.', ','),
                ucfirst($v['estado']),
                isset($v['created_at']) ? date('d/m/Y', strtotime($v['created_at'])) : '—'
            ], ';');
        }

        fclose($output);
        exit;
    }
}