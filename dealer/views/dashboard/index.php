<?php
// Marca la sección activa en el sidebar
$activeNav = 'dashboard';
?>

<?php require_once "views/layouts/header.php"; ?>
<?php require_once "views/layouts/sidebar.php"; ?>

<!-- ════════════════════════════════════
     DASHBOARD
     ════════════════════════════════════ -->

<!-- Page header -->
<div class="page-header">
    <div>
        <h1 class="page-title">Panel de <span>Control</span></h1>
        <div class="page-breadcrumb">
            <i class="bi bi-house-fill"></i>
            <span>Inicio</span>
            <i class="bi bi-chevron-right" style="font-size:.65rem;"></i>
            <span>Dashboard</span>
        </div>
    </div>
    <div class="page-date">
        <i class="bi bi-calendar3"></i>
        <?= date('d \d\e F, Y') ?>
    </div>
</div>

<!-- Quick action buttons -->
<div class="quick-actions">
    <a href="index.php?controller=vehiculo&action=crear" class="quick-btn">
        <i class="bi bi-plus-circle-fill"></i>
        <span>Nuevo vehículo</span>
    </a>
    <a href="index.php?controller=cliente&action=crear" class="quick-btn">
        <i class="bi bi-person-plus-fill"></i>
        <span>Nuevo cliente</span>
    </a>
    <a href="index.php?controller=venta&action=crear" class="quick-btn">
        <i class="bi bi-bag-plus-fill"></i>
        <span>Nueva venta</span>
    </a>
    <a href="index.php?controller=vehiculo&action=index" class="quick-btn">
        <i class="bi bi-list-ul"></i>
        <span>Ver inventario</span>
    </a>
</div>

<!-- ── KPI Cards ── -->
<div class="kpi-grid">

    <!-- Vehículos -->
    <div class="kpi-card kpi-gold">
        <div class="kpi-top">
            <p class="kpi-label">Total vehículos</p>
            <div class="kpi-icon">
                <i class="bi bi-car-front-fill"></i>
            </div>
        </div>
        <p class="kpi-value" data-target="<?= (int)($vehiculos['total'] ?? 0) ?>">0</p>
        <span class="kpi-badge neutral">
            <i class="bi bi-box-seam"></i> En inventario
        </span>
    </div>

    <!-- Clientes -->
    <div class="kpi-card kpi-blue">
        <div class="kpi-top">
            <p class="kpi-label">Total clientes</p>
            <div class="kpi-icon">
                <i class="bi bi-people-fill"></i>
            </div>
        </div>
        <p class="kpi-value" data-target="<?= (int)($clientes['total'] ?? 0) ?>">0</p>
        <span class="kpi-badge neutral">
            <i class="bi bi-person-check"></i> Registrados
        </span>
    </div>

    <!-- Ventas -->
    <div class="kpi-card kpi-teal">
        <div class="kpi-top">
            <p class="kpi-label">Total ventas</p>
            <div class="kpi-icon">
                <i class="bi bi-receipt-cutoff"></i>
            </div>
        </div>
        <p class="kpi-value" data-target="<?= (int)($ventas['total'] ?? 0) ?>">0</p>
        <span class="kpi-badge">
            <i class="bi bi-arrow-up-short"></i> Operaciones
        </span>
    </div>

</div>

<!-- ── Info panels ── -->
<div class="info-row">

    <!-- Panel: Resumen rápido -->
    <div class="panel">
        <div class="panel-header">
            <p class="panel-title"><i class="bi bi-bar-chart-fill"></i> Resumen</p>
            <a href="index.php?controller=reporte&action=index" class="panel-action">
                Ver reportes <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="panel-body">
            <div class="stat-list">

                <div class="stat-row">
                    <div class="stat-dot" style="background:#e8a020;"></div>
                    <span class="stat-label">Vehículos en stock</span>
                    <span class="stat-val"><?= (int)($vehiculos['total'] ?? 0) ?></span>
                </div>

                <div class="stat-row">
                    <div class="stat-dot" style="background:#3b82f6;"></div>
                    <span class="stat-label">Clientes registrados</span>
                    <span class="stat-val"><?= (int)($clientes['total'] ?? 0) ?></span>
                </div>

                <div class="stat-row">
                    <div class="stat-dot" style="background:#10b981;"></div>
                    <span class="stat-label">Ventas realizadas</span>
                    <span class="stat-val"><?= (int)($ventas['total'] ?? 0) ?></span>
                </div>

                <div class="stat-row">
                    <div class="stat-dot" style="background:#8a93a8;"></div>
                    <span class="stat-label">Ratio venta / cliente</span>
                    <span class="stat-val">
                        <?php
                            $cli = (int)($clientes['total'] ?? 0);
                            $ven = (int)($ventas['total'] ?? 0);
                            echo $cli > 0 ? number_format($ven / $cli, 1) : '—';
                        ?>
                    </span>
                </div>

            </div>
        </div>
    </div>

    <!-- Panel: Actividad reciente -->
    <div class="panel">
        <div class="panel-header">
            <p class="panel-title"><i class="bi bi-activity"></i> Actividad reciente</p>
            <a href="index.php?controller=venta&action=index" class="panel-action">
                Ver ventas <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="panel-body">
            <div class="activity-list">

                <div class="activity-item" style="--accent-clr:#e8a020;">
                    <div class="activity-dot-wrap">
                        <div class="activity-dot"></div>
                    </div>
                    <div class="activity-body">
                        <p class="activity-title">Sistema iniciado correctamente</p>
                        <p class="activity-time"><i class="bi bi-clock me-1"></i>Hoy, <?= date('H:i') ?></p>
                    </div>
                </div>

                <div class="activity-item" style="--accent-clr:#3b82f6;">
                    <div class="activity-dot-wrap">
                        <div class="activity-dot"></div>
                    </div>
                    <div class="activity-body">
                        <p class="activity-title"><?= (int)($vehiculos['total'] ?? 0) ?> vehículos en inventario activo</p>
                        <p class="activity-time"><i class="bi bi-clock me-1"></i>Actualizado ahora</p>
                    </div>
                </div>

                <div class="activity-item" style="--accent-clr:#10b981;">
                    <div class="activity-dot-wrap">
                        <div class="activity-dot"></div>
                    </div>
                    <div class="activity-body">
                        <p class="activity-title"><?= (int)($clientes['total'] ?? 0) ?> clientes en base de datos</p>
                        <p class="activity-time"><i class="bi bi-clock me-1"></i>Actualizado ahora</p>
                    </div>
                </div>

                <div class="activity-item" style="--accent-clr:#8a93a8;">
                    <div class="activity-dot-wrap">
                        <div class="activity-dot"></div>
                    </div>
                    <div class="activity-body">
                        <p class="activity-title"><?= (int)($ventas['total'] ?? 0) ?> transacciones registradas</p>
                        <p class="activity-time"><i class="bi bi-clock me-1"></i>Actualizado ahora</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

<?php require_once "views/layouts/footer.php"; ?>
