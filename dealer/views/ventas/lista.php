<?php
$activeNav = 'ventas';
$ventas    = is_array($ventas) ? $ventas : [];
?>
<?php require_once "views/layouts/header.php"; ?>
<?php require_once "views/layouts/sidebar.php"; ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Gestión de <span>Ventas</span></h1>
        <div class="page-breadcrumb">
            <i class="bi bi-house-fill"></i>
            <a href="index.php?controller=dashboard&action=index">Inicio</a>
            <i class="bi bi-chevron-right" style="font-size:.65rem;"></i>
            <span>Ventas</span>
        </div>
    </div>
    <a href="index.php?controller=venta&action=crear" class="ds-btn ds-btn-primary">
        <i class="bi bi-plus-lg"></i>
        Nueva venta
    </a>
</div>

<?php if (!empty($mensaje)): ?>
<div class="ds-alert ds-alert-success mb-4">
    <i class="bi bi-check-circle-fill"></i>
    <?= htmlspecialchars($mensaje) ?>
    <button class="ds-alert-close" onclick="this.parentElement.remove()"><i class="bi bi-x"></i></button>
</div>
<?php endif; ?>

<?php
    $total_ventas = count($ventas);
    $suma_total   = $total_ventas > 0 ? array_sum(array_column($ventas, 'total')) : 0;
    $promedio     = $total_ventas > 0 ? $suma_total / $total_ventas : 0;
?>

<!-- KPI rápido -->
<div class="kpi-grid" style="margin-bottom:1.5rem;">

    <div class="kpi-card kpi-teal" style="animation: fadeUp .4s ease both;">
        <div class="kpi-top">
            <p class="kpi-label">Total ventas</p>
            <div class="kpi-icon"><i class="bi bi-receipt-cutoff"></i></div>
        </div>
        <p class="kpi-value"><?= $total_ventas ?></p>
        <span class="kpi-badge neutral">
            <i class="bi bi-list-check"></i> Registradas
        </span>
    </div>

    <div class="kpi-card kpi-gold" style="animation: fadeUp .4s ease .08s both;">
        <div class="kpi-top">
            <p class="kpi-label">Ingresos totales</p>
            <div class="kpi-icon"><i class="bi bi-currency-dollar"></i></div>
        </div>
        <p class="kpi-value" style="font-size:1.8rem;">
            $<?= number_format($suma_total, 2, '.', ',') ?>
        </p>
        <span class="kpi-badge neutral">
            <i class="bi bi-graph-up"></i> Acumulado
        </span>
    </div>

    <div class="kpi-card kpi-blue" style="animation: fadeUp .4s ease .16s both;">
        <div class="kpi-top">
            <p class="kpi-label">Promedio por venta</p>
            <div class="kpi-icon"><i class="bi bi-calculator"></i></div>
        </div>
        <p class="kpi-value" style="font-size:1.8rem;">
            $<?= number_format($promedio, 2, '.', ',') ?>
        </p>
        <span class="kpi-badge neutral">
            <i class="bi bi-bar-chart"></i> Por operación
        </span>
    </div>

</div>

<!-- Tabla -->
<div class="ds-panel" style="animation: fadeUp .5s ease .2s both;">
    <div class="ds-panel-header">
        <div class="ds-panel-title">
            <i class="bi bi-receipt-cutoff"></i>
            Historial de ventas
            <span class="ds-badge"><?= $total_ventas ?> registros</span>
        </div>
        <div class="ds-table-tools">
            <div class="ds-search-wrap">
                <i class="bi bi-search ds-search-icon"></i>
                <input type="text" id="tableSearch" class="ds-search"
                       placeholder="Buscar venta...">
            </div>
        </div>
    </div>

    <div class="ds-table-wrap">
        <table class="ds-table" id="ventasTable">
            <thead>
                <tr>
                    <th style="width:50px;">#</th>
                    <th>Cliente</th>
                    <th>Vehículo</th>
                    <th>Total</th>
                    <th>Fecha</th>
                    <th style="width:80px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($ventas)): ?>
                <?php foreach ($ventas as $i => $v): ?>
                <tr>
                    <td class="ds-td-muted"><?= $i + 1 ?></td>

                    <!-- Cliente -->
                    <td>
                        <div style="display:flex;align-items:center;gap:.75rem;">
                            <div class="cliente-avatar">
                                <?= strtoupper(substr($v['cliente_nombre'] ?? '?', 0, 1)) ?>
                            </div>
                            <div>
                                <p class="ds-td-title"><?= htmlspecialchars($v['cliente_nombre'] ?? '—') ?></p>
                                <p class="ds-td-sub"><?= htmlspecialchars($v['cliente_cedula'] ?? '') ?></p>
                            </div>
                        </div>
                    </td>

                    <!-- Vehículo -->
                    <td>
                        <p class="ds-td-title"><?= htmlspecialchars($v['vehiculo_marca'] ?? '—') ?></p>
                        <p class="ds-td-sub">
                            <?= htmlspecialchars($v['vehiculo_modelo'] ?? '') ?>
                            <?= !empty($v['vehiculo_anio']) ? '· '.$v['vehiculo_anio'] : '' ?>
                        </p>
                    </td>

                    <!-- Total -->
                    <td class="ds-td-price">
                        $<?= number_format($v['total'], 2, '.', ',') ?>
                    </td>

                    <!-- Fecha -->
                    <td class="ds-td-muted">
                        <i class="bi bi-calendar3" style="font-size:.75rem;margin-right:3px;"></i>
                        <?= date('d/m/Y', strtotime($v['fecha'])) ?>
                    </td>

                    <!-- Acciones -->
                    <td>
                        <div class="ds-actions">
                            <a href="index.php?controller=venta&action=eliminar&id=<?= $v['id'] ?>"
                               class="ds-action-btn ds-action-delete"
                               title="Eliminar"
                               onclick="return confirmDelete(event, '<?= htmlspecialchars($v['cliente_nombre'].' - '.$v['vehiculo_marca']) ?>')">
                                <i class="bi bi-trash3-fill"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">
                        <div class="ds-empty">
                            <i class="bi bi-receipt"></i>
                            <p>No hay ventas registradas</p>
                            <a href="index.php?controller=venta&action=crear"
                               class="ds-btn ds-btn-primary mt-2">
                                <i class="bi bi-plus-lg"></i> Registrar primera venta
                            </a>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal eliminar -->
<div class="ds-modal-overlay" id="deleteOverlay">
    <div class="ds-modal">
        <div class="ds-modal-icon danger"><i class="bi bi-trash3-fill"></i></div>
        <h3 class="ds-modal-title">¿Eliminar venta?</h3>
        <p class="ds-modal-body">
            Eliminarás la venta de <strong id="deleteTarget"></strong>.
            Esta acción no se puede deshacer.
        </p>
        <div class="ds-modal-actions">
            <button class="ds-btn ds-btn-ghost" onclick="closeDeleteModal()">Cancelar</button>
            <a href="#" id="deleteConfirmBtn" class="ds-btn ds-btn-danger">
                <i class="bi bi-trash3-fill"></i> Eliminar
            </a>
        </div>
    </div>
</div>

<style>
.cliente-avatar {
    width: 34px; height: 34px; border-radius: 50%;
    background: linear-gradient(135deg, rgba(232,160,32,.2), rgba(232,160,32,.05));
    border: 1px solid rgba(232,160,32,.3);
    display: flex; align-items: center; justify-content: center;
    font-family: 'Syne', sans-serif; font-weight: 700;
    font-size: .85rem; color: var(--gold); flex-shrink: 0;
}
</style>

<script>
document.getElementById('tableSearch').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#ventasTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});

function confirmDelete(e, name) {
    e.preventDefault();
    document.getElementById('deleteTarget').textContent = name;
    document.getElementById('deleteConfirmBtn').href = e.currentTarget.href;
    document.getElementById('deleteOverlay').classList.add('visible');
    return false;
}
function closeDeleteModal() {
    document.getElementById('deleteOverlay').classList.remove('visible');
}
document.getElementById('deleteOverlay').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeDeleteModal();
});
</script>

<?php require_once "views/layouts/footer.php"; ?>