<?php
$activeNav = 'reportes';
?>
<?php require_once "views/layouts/header.php"; ?>
<?php require_once "views/layouts/sidebar.php"; ?>

<!-- ══════════════════════════════════════
     REPORTES — jsPDF + CSV
     Siguiendo la guía del maestro
     ══════════════════════════════════════ -->

<div class="page-header">
    <div>
        <h1 class="page-title">Centro de <span>Reportes</span></h1>
        <div class="page-breadcrumb">
            <i class="bi bi-house-fill"></i>
            <a href="index.php?controller=dashboard&action=index">Inicio</a>
            <i class="bi bi-chevron-right" style="font-size:.65rem;"></i>
            <span>Reportes</span>
        </div>
    </div>
</div>

<?php
    // Obtener datos desde PHP para pasarlos a JS
    require_once "models/Venta.php";
    require_once "models/Vehiculo.php";
    require_once "models/Cliente.php";

    $ventaModel    = new Venta();
    $vehiculoModel = new Vehiculo();
    $clienteModel  = new Cliente();

    $ventas    = $ventaModel->getAll();
    $vehiculos = $vehiculoModel->getAll();
    $clientes  = $clienteModel->getAll();

    $ventas    = is_array($ventas)    ? $ventas    : [];
    $vehiculos = is_array($vehiculos) ? $vehiculos : [];
    $clientes  = is_array($clientes)  ? $clientes  : [];

    $totalVentas   = count($ventas);
    $sumaTotal     = $totalVentas > 0 ? array_sum(array_column($ventas, 'total')) : 0;
?>

<!-- KPIs -->
<div class="kpi-grid" style="margin-bottom:2rem;">
    <div class="kpi-card kpi-teal" style="animation: fadeUp .4s ease both;">
        <div class="kpi-top">
            <p class="kpi-label">Total ventas</p>
            <div class="kpi-icon"><i class="bi bi-receipt-cutoff"></i></div>
        </div>
        <p class="kpi-value"><?= $totalVentas ?></p>
        <span class="kpi-badge neutral"><i class="bi bi-list-check"></i> Registradas</span>
    </div>
    <div class="kpi-card kpi-gold" style="animation: fadeUp .4s ease .08s both;">
        <div class="kpi-top">
            <p class="kpi-label">Ingresos totales</p>
            <div class="kpi-icon"><i class="bi bi-currency-dollar"></i></div>
        </div>
        <p class="kpi-value" style="font-size:1.8rem;">
            $<?= number_format($sumaTotal, 2, '.', ',') ?>
        </p>
        <span class="kpi-badge neutral"><i class="bi bi-graph-up"></i> Acumulado</span>
    </div>
    <div class="kpi-card kpi-blue" style="animation: fadeUp .4s ease .16s both;">
        <div class="kpi-top">
            <p class="kpi-label">Total vehículos</p>
            <div class="kpi-icon"><i class="bi bi-car-front-fill"></i></div>
        </div>
        <p class="kpi-value"><?= count($vehiculos) ?></p>
        <span class="kpi-badge neutral"><i class="bi bi-box-seam"></i> En inventario</span>
    </div>
</div>

<!-- Tarjetas de reportes -->
<div class="reportes-grid">

    <!-- Reporte PDF Ventas -->
    <div class="reporte-card" style="animation: fadeUp .5s ease .1s both;">
        <div class="reporte-icon" style="background:rgba(226,75,74,.12);border-color:rgba(226,75,74,.25);">
            <i class="bi bi-file-earmark-pdf-fill" style="color:#f09595;font-size:1.4rem;"></i>
        </div>
        <div class="reporte-info">
            <h3 class="reporte-title">Reporte de Ventas — PDF</h3>
            <p class="reporte-desc">
                Genera un documento PDF profesional con el listado completo de ventas,
                cliente, vehículo, total e ingresos acumulados.
            </p>
            <div class="reporte-meta">
                <span><i class="bi bi-table"></i> <?= $totalVentas ?> registros</span>
                <span><i class="bi bi-calendar3"></i> <?= date('d/m/Y') ?></span>
            </div>
        </div>
        <div class="reporte-actions">
            <button onclick="generarPDF()" class="ds-btn ds-btn-danger-outline">
                <i class="bi bi-file-earmark-pdf-fill"></i>
                Generar PDF
            </button>
        </div>
    </div>

    <!-- Reporte CSV Ventas -->
    <div class="reporte-card" style="animation: fadeUp .5s ease .2s both;">
        <div class="reporte-icon" style="background:rgba(16,185,129,.1);border-color:rgba(16,185,129,.2);">
            <i class="bi bi-file-earmark-excel-fill" style="color:#6ee7b7;font-size:1.4rem;"></i>
        </div>
        <div class="reporte-info">
            <h3 class="reporte-title">Exportar Ventas — CSV</h3>
            <p class="reporte-desc">
                Exporta todos los datos de ventas a un archivo CSV compatible con
                Microsoft Excel para análisis contable.
            </p>
            <div class="reporte-meta">
                <span><i class="bi bi-file-earmark-spreadsheet"></i> Formato Excel</span>
                <span><i class="bi bi-calendar3"></i> <?= date('d/m/Y') ?></span>
            </div>
        </div>
        <div class="reporte-actions">
            <button onclick="exportarCSV(ventasData)" class="ds-btn ds-btn-success-outline">
                <i class="bi bi-file-earmark-excel-fill"></i>
                Exportar CSV
            </button>
        </div>
    </div>

    <!-- Reporte CSV Vehículos -->
    <div class="reporte-card" style="animation: fadeUp .5s ease .3s both;">
        <div class="reporte-icon" style="background:rgba(232,160,32,.1);border-color:rgba(232,160,32,.2);">
            <i class="bi bi-car-front-fill" style="color:var(--gold);font-size:1.4rem;"></i>
        </div>
        <div class="reporte-info">
            <h3 class="reporte-title">Inventario Vehículos — CSV</h3>
            <p class="reporte-desc">
                Exporta el inventario completo de vehículos con marca, modelo,
                año, precio y estado en formato Excel.
            </p>
            <div class="reporte-meta">
                <span><i class="bi bi-car-front"></i> <?= count($vehiculos) ?> vehículos</span>
                <span><i class="bi bi-calendar3"></i> <?= date('d/m/Y') ?></span>
            </div>
        </div>
        <div class="reporte-actions">
            <button onclick="exportarCSVVehiculos(vehiculosData)" class="ds-btn ds-btn-gold-outline">
                <i class="bi bi-file-earmark-excel-fill"></i>
                Exportar CSV
            </button>
        </div>
    </div>

</div>

<style>
.reportes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 1.25rem;
}
.reporte-card {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
    transition: transform .2s, border-color .2s, box-shadow .2s;
}
.reporte-card:hover {
    transform: translateY(-3px);
    border-color: rgba(255,255,255,.12);
    box-shadow: 0 8px 32px rgba(0,0,0,.3);
}
.reporte-icon {
    width: 52px; height: 52px;
    border-radius: 12px; border: 1px solid;
    display: flex; align-items: center; justify-content: center;
}
.reporte-title {
    font-family: 'Syne', sans-serif;
    font-weight: 700; font-size: 1rem;
    color: var(--text); margin-bottom: .3rem;
}
.reporte-desc {
    font-size: .83rem; color: var(--text-dim);
    line-height: 1.5; margin-bottom: .5rem;
}
.reporte-meta {
    display: flex; gap: 1rem;
    font-size: .75rem; color: var(--text-dim);
}
.reporte-meta span { display: flex; align-items: center; gap: .3rem; }
.reporte-meta i { color: var(--gold); font-size: .8rem; }
.reporte-actions {
    display: flex; gap: .7rem; flex-wrap: wrap;
    padding-top: .8rem;
    border-top: 1px solid var(--border);
}
.ds-btn-danger-outline {
    background: rgba(226,75,74,.1);
    border-color: rgba(226,75,74,.3);
    color: #f09595;
}
.ds-btn-danger-outline:hover {
    background: rgba(226,75,74,.2);
    border-color: rgba(226,75,74,.5);
    color: #f8b8b8;
    transform: translateY(-1px);
}
.ds-btn-success-outline {
    background: rgba(16,185,129,.1);
    border-color: rgba(16,185,129,.25);
    color: #6ee7b7;
}
.ds-btn-success-outline:hover {
    background: rgba(16,185,129,.2);
    border-color: rgba(16,185,129,.4);
    color: #a7f3d0;
    transform: translateY(-1px);
}
.ds-btn-gold-outline {
    background: rgba(232,160,32,.1);
    border-color: rgba(232,160,32,.25);
    color: var(--gold);
}
.ds-btn-gold-outline:hover {
    background: rgba(232,160,32,.2);
    border-color: rgba(232,160,32,.4);
    color: #f0b030;
    transform: translateY(-1px);
}
</style>

<!-- ══════════════════════════════════════
     DATOS PHP → JavaScript
     ══════════════════════════════════════ -->
<script>
// Pasar datos de PHP a JavaScript (igual que la guía del maestro)
const ventasData    = <?= json_encode($ventas) ?>;
const vehiculosData = <?= json_encode($vehiculos) ?>;
const clientesData  = <?= json_encode($clientes) ?>;

console.log('📊 Datos cargados para reportes:', {
    ventas:    ventasData,
    vehiculos: vehiculosData,
    clientes:  clientesData
});
</script>

<!-- ══════════════════════════════════════
     jsPDF + AutoTable (como indica el maestro)
     ══════════════════════════════════════ -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>

<script>
// ══════════════════════════════════════════════
// CONTROLADOR DE REPORTES — JavaScript
// Siguiendo la guía del maestro (jsPDF)
// ══════════════════════════════════════════════

// ── 1. GENERAR PDF ────────────────────────────
function generarPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    const fechaHoy  = new Date().toLocaleDateString('es-DO');
    const horaHoy   = new Date().toLocaleTimeString('es-DO', { hour: '2-digit', minute: '2-digit' });
    const sumaTotal = ventasData.reduce((acc, v) => acc + parseFloat(v.total || 0), 0);

    // ── Encabezado del documento ──
    doc.setFillColor(26, 31, 46);
    doc.rect(0, 0, 210, 35, 'F');

    doc.setTextColor(232, 160, 32);
    doc.setFontSize(20);
    doc.setFont('helvetica', 'bold');
    doc.text('DEALER SYSTEM', 14, 15);

    doc.setTextColor(200, 200, 200);
    doc.setFontSize(9);
    doc.setFont('helvetica', 'normal');
    doc.text('Gestión Vehicular Avanzada', 14, 22);

    doc.setTextColor(255, 255, 255);
    doc.setFontSize(14);
    doc.setFont('helvetica', 'bold');
    doc.text('REPORTE DE VENTAS', 14, 30);

    // Fecha en encabezado
    doc.setTextColor(200, 200, 200);
    doc.setFontSize(8);
    doc.setFont('helvetica', 'normal');
    doc.text(`Generado: ${fechaHoy} ${horaHoy}`, 150, 22);

    // ── Línea dorada ──
    doc.setDrawColor(232, 160, 32);
    doc.setLineWidth(1);
    doc.line(14, 38, 196, 38);

    // ── KPIs resumen ──
    doc.setFillColor(245, 245, 250);
    doc.roundedRect(14, 42, 55, 18, 3, 3, 'F');
    doc.roundedRect(77, 42, 55, 18, 3, 3, 'F');
    doc.roundedRect(140, 42, 55, 18, 3, 3, 'F');

    doc.setTextColor(232, 160, 32);
    doc.setFontSize(14);
    doc.setFont('helvetica', 'bold');
    doc.text(`${ventasData.length}`, 41, 53, { align: 'center' });
    doc.text(`$${sumaTotal.toLocaleString('es-DO', { minimumFractionDigits: 2 })}`, 104, 53, { align: 'center' });
    doc.text(`${new Date().getFullYear()}`, 167, 53, { align: 'center' });

    doc.setTextColor(120, 120, 140);
    doc.setFontSize(7);
    doc.setFont('helvetica', 'normal');
    doc.text('TOTAL VENTAS', 41, 57, { align: 'center' });
    doc.text('INGRESOS TOTALES', 104, 57, { align: 'center' });
    doc.text('AÑO', 167, 57, { align: 'center' });

    // ── Tabla de ventas (autoTable como indica el maestro) ──
    doc.autoTable({
        startY: 65,
        head: [['#', 'Cliente', 'Cédula', 'Vehículo', 'Año', 'Total', 'Fecha']],
        body: ventasData.map((v, i) => [
            i + 1,
            v.cliente_nombre  || '—',
            v.cliente_cedula  || '—',
            `${v.vehiculo_marca || ''} ${v.vehiculo_modelo || ''}`.trim() || '—',
            v.vehiculo_anio   || '—',
            `$${parseFloat(v.total || 0).toLocaleString('es-DO', { minimumFractionDigits: 2 })}`,
            new Date(v.fecha).toLocaleDateString('es-DO')
        ]),
        theme: 'grid',
        headStyles: {
            fillColor: [26, 31, 46],
            textColor: [232, 160, 32],
            fontStyle: 'bold',
            fontSize: 8,
        },
        bodyStyles: {
            fontSize: 8,
            textColor: [50, 50, 70],
        },
        alternateRowStyles: {
            fillColor: [248, 248, 252],
        },
        columnStyles: {
            0: { cellWidth: 8,  halign: 'center' },
            5: { textColor: [180, 100, 10], fontStyle: 'bold' },
        },
        margin: { left: 14, right: 14 },
    });

    // ── Fila de total ──
    const finalY = doc.lastAutoTable.finalY + 5;
    doc.setFillColor(26, 31, 46);
    doc.roundedRect(14, finalY, 182, 10, 2, 2, 'F');
    doc.setTextColor(255, 255, 255);
    doc.setFontSize(9);
    doc.setFont('helvetica', 'bold');
    doc.text('TOTAL GENERAL:', 20, finalY + 6.5);
    doc.setTextColor(232, 160, 32);
    doc.text(
        `$${sumaTotal.toLocaleString('es-DO', { minimumFractionDigits: 2 })}`,
        130, finalY + 6.5
    );
    doc.setTextColor(200, 200, 200);
    doc.text(`${ventasData.length} ventas registradas`, 160, finalY + 6.5);

    // ── Pie de página ──
    const pageCount = doc.internal.getNumberOfPages();
    for (let i = 1; i <= pageCount; i++) {
        doc.setPage(i);
        doc.setDrawColor(232, 160, 32);
        doc.setLineWidth(.5);
        doc.line(14, 285, 196, 285);
        doc.setTextColor(150, 150, 160);
        doc.setFontSize(7);
        doc.setFont('helvetica', 'normal');
        doc.text('© 2026 Dealer System — Todos los derechos reservados', 14, 290);
        doc.text(`Página ${i} de ${pageCount}`, 196, 290, { align: 'right' });
    }

    // ── Descargar el PDF ──
    doc.save(`reporte_ventas_${Date.now()}.pdf`);

    console.log('✅ PDF generado correctamente con jsPDF');
}

// ── 2. EXPORTAR CSV VENTAS ────────────────────
// Siguiendo exactamente la guía del maestro con Blob
function exportarCSV(data) {
    // 1. Definir encabezados
    let csvContent = "ID,Cliente,Cédula,Vehículo,Año,Total,Fecha\n";

    // 2. Iterar sobre los datos
    data.forEach(item => {
        const vehiculo = `${item.vehiculo_marca || ''} ${item.vehiculo_modelo || ''}`.trim();
        const fecha    = new Date(item.fecha).toLocaleDateString('es-DO');
        const total    = parseFloat(item.total || 0).toFixed(2);

        let row = `${item.id},"${item.cliente_nombre || ''}","${item.cliente_cedula || ''}","${vehiculo}",${item.vehiculo_anio || ''},${total},${fecha}`;
        csvContent += row + "\r\n";
    });

    // 3. Crear Blob y disparar descarga (como indica el maestro)
    const blob = new Blob(["\uFEFF" + csvContent], { type: 'text/csv;charset=utf-8;' });
    const url  = URL.createObjectURL(blob);
    const link = document.createElement("a");

    link.setAttribute("href", url);
    link.setAttribute("download", `reporte_ventas_${Date.now()}.csv`);
    link.click();

    console.log('✅ CSV de ventas exportado correctamente');
}

// ── 3. EXPORTAR CSV VEHÍCULOS ─────────────────
function exportarCSVVehiculos(data) {
    // 1. Definir encabezados
    let csvContent = "ID,Marca,Modelo,Año,Precio,Estado\n";

    // 2. Iterar sobre los datos
    data.forEach(item => {
        let row = `${item.id},"${item.marca}","${item.modelo}",${item.anio},${parseFloat(item.precio || 0).toFixed(2)},"${item.estado}"`;
        csvContent += row + "\r\n";
    });

    // 3. Crear Blob y disparar descarga
    const blob = new Blob(["\uFEFF" + csvContent], { type: 'text/csv;charset=utf-8;' });
    const url  = URL.createObjectURL(blob);
    const link = document.createElement("a");

    link.setAttribute("href", url);
    link.setAttribute("download", `inventario_vehiculos_${Date.now()}.csv`);
    link.click();

    console.log('✅ CSV de vehículos exportado correctamente');
}
</script>

<?php require_once "views/layouts/footer.php"; ?>