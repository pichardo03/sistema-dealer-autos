<?php $activeNav = 'ventas'; ?>
<?php require_once "views/layouts/header.php"; ?>
<?php require_once "views/layouts/sidebar.php"; ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Nueva <span>Venta</span></h1>
        <div class="page-breadcrumb">
            <i class="bi bi-house-fill"></i>
            <a href="index.php?controller=dashboard&action=index">Inicio</a>
            <i class="bi bi-chevron-right" style="font-size:.65rem;"></i>
            <a href="index.php?controller=venta&action=index">Ventas</a>
            <i class="bi bi-chevron-right" style="font-size:.65rem;"></i>
            <span>Crear</span>
        </div>
    </div>
    <a href="index.php?controller=venta&action=index" class="ds-btn ds-btn-ghost">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<?php if (!empty($error)): ?>
<div class="ds-alert ds-alert-danger mb-4">
    <i class="bi bi-exclamation-triangle-fill"></i>
    <?= htmlspecialchars($error) ?>
    <button class="ds-alert-close" onclick="this.parentElement.remove()"><i class="bi bi-x"></i></button>
</div>
<?php endif; ?>

<div class="ds-form-grid" style="grid-template-columns: 1fr 1fr;">

    <!-- Columna izquierda — formulario -->
    <div class="ds-form-col">
        <div class="ds-panel" style="animation: fadeUp .45s ease both;">
            <div class="ds-panel-header">
                <div class="ds-panel-title">
                    <i class="bi bi-receipt-cutoff"></i> Datos de la venta
                </div>
            </div>
            <div class="ds-panel-body">

                <form method="POST"
                      action="index.php?controller=venta&action=guardar"
                      id="ventaForm"
                      novalidate>

                    <!-- Cliente -->
                    <div class="ds-field">
                        <label class="ds-label" for="cliente_id">
                            <i class="bi bi-person-fill"></i>
                            Cliente <span class="ds-required">*</span>
                        </label>
                        <div class="ds-select-wrap">
                            <select id="cliente_id" name="cliente_id"
                                    class="ds-select" required
                                    onchange="actualizarPreview()">
                                <option value="">— Seleccionar cliente —</option>
                                <?php foreach ($clientes as $c): ?>
                                <option value="<?= $c['id'] ?>">
                                    <?= htmlspecialchars($c['nombre']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <i class="bi bi-chevron-down ds-select-icon"></i>
                        </div>
                        <?php if (empty($clientes)): ?>
                        <span class="ds-field-hint" style="color:#f09595;">
                            <i class="bi bi-exclamation-triangle"></i>
                            No hay clientes registrados.
                            <a href="index.php?controller=cliente&action=crear"
                               style="color:var(--gold);">Agregar uno</a>
                        </span>
                        <?php endif; ?>
                    </div>

                    <!-- Vehículo -->
                    <div class="ds-field">
                        <label class="ds-label" for="vehiculo_id">
                            <i class="bi bi-car-front-fill"></i>
                            Vehículo <span class="ds-required">*</span>
                        </label>
                        <div class="ds-select-wrap">
                            <select id="vehiculo_id" name="vehiculo_id"
                                    class="ds-select" required
                                    onchange="autocompletarPrecio()">
                                <option value="">— Seleccionar vehículo —</option>
                                <?php foreach ($vehiculos as $v): ?>
                                <option value="<?= $v['id'] ?>"
                                        data-precio="<?= $v['precio'] ?>">
                                    <?= htmlspecialchars($v['marca'].' '.$v['modelo'].' '.$v['anio']) ?>
                                    — $<?= number_format($v['precio'], 2, '.', ',') ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <i class="bi bi-chevron-down ds-select-icon"></i>
                        </div>
                        <?php if (empty($vehiculos)): ?>
                        <span class="ds-field-hint" style="color:#f09595;">
                            <i class="bi bi-exclamation-triangle"></i>
                            No hay vehículos disponibles.
                            <a href="index.php?controller=vehiculo&action=crear"
                               style="color:var(--gold);">Agregar uno</a>
                        </span>
                        <?php endif; ?>
                    </div>

                    <!-- Total -->
                    <div class="ds-field">
                        <label class="ds-label" for="total">
                            <i class="bi bi-currency-dollar"></i>
                            Total <span class="ds-required">*</span>
                        </label>
                        <div class="ds-input-prefix-wrap">
                            <span class="ds-input-prefix">$</span>
                            <input type="number" id="total" name="total"
                                   class="ds-input ds-input-prefixed"
                                   placeholder="0.00" min="0" step="0.01"
                                   required>
                        </div>
                        <span class="ds-field-hint">Se autocompleta al seleccionar el vehículo</span>
                    </div>

                    <!-- Fecha -->
                    <div class="ds-field">
                        <label class="ds-label" for="fecha">
                            <i class="bi bi-calendar3"></i>
                            Fecha <span class="ds-required">*</span>
                        </label>
                        <input type="date" id="fecha" name="fecha"
                               class="ds-input"
                               value="<?= date('Y-m-d') ?>"
                               required>
                    </div>

                    <div class="ds-form-actions" style="margin-top:1.2rem;">
                        <button type="button" class="ds-btn ds-btn-ghost"
                                onclick="window.location='index.php?controller=venta&action=index'">
                            <i class="bi bi-x-lg"></i> Cancelar
                        </button>
                        <button type="submit" class="ds-btn ds-btn-primary" id="submitBtn">
                            <i class="bi bi-receipt-cutoff"></i>
                            <span class="btn-txt">Registrar venta</span>
                            <span class="ds-btn-spinner" style="display:none;"></span>
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <!-- Columna derecha — resumen -->
    <div class="ds-form-col">
        <div class="ds-panel" style="animation: fadeUp .45s ease .1s both;">
            <div class="ds-panel-header">
                <div class="ds-panel-title">
                    <i class="bi bi-eye-fill"></i> Resumen de venta
                </div>
            </div>
            <div class="ds-panel-body">

                <div class="venta-resumen">

                    <div class="resumen-item">
                        <p class="resumen-label"><i class="bi bi-person-fill"></i> Cliente</p>
                        <p class="resumen-val" id="resumenCliente">—</p>
                    </div>

                    <div class="resumen-divider"></div>

                    <div class="resumen-item">
                        <p class="resumen-label"><i class="bi bi-car-front-fill"></i> Vehículo</p>
                        <p class="resumen-val" id="resumenVehiculo">—</p>
                    </div>

                    <div class="resumen-divider"></div>

                    <div class="resumen-item">
                        <p class="resumen-label"><i class="bi bi-calendar3"></i> Fecha</p>
                        <p class="resumen-val" id="resumenFecha"><?= date('d/m/Y') ?></p>
                    </div>

                    <div class="resumen-divider"></div>

                    <div class="resumen-total">
                        <p class="resumen-total-label">Total de la venta</p>
                        <p class="resumen-total-val" id="resumenTotal">$0.00</p>
                    </div>

                </div>

            </div>
        </div>
    </div>

</div>

<style>
.venta-resumen {
    background: rgba(232,160,32,.03);
    border: 1px solid rgba(232,160,32,.12);
    border-radius: var(--radius-md);
    overflow: hidden;
}
.resumen-item {
    padding: .9rem 1.1rem;
    display: flex; align-items: center;
    justify-content: space-between; gap: 1rem;
}
.resumen-label {
    font-size: .75rem; color: var(--text-dim);
    display: flex; align-items: center; gap: .35rem;
    white-space: nowrap;
}
.resumen-label i { color: var(--gold); }
.resumen-val {
    font-size: .88rem; font-weight: 500;
    color: var(--text); text-align: right;
    transition: all .2s;
}
.resumen-divider {
    height: 1px;
    background: var(--border);
    margin: 0 1.1rem;
}
.resumen-total {
    padding: 1.1rem;
    background: rgba(232,160,32,.07);
    display: flex; align-items: center;
    justify-content: space-between;
    border-top: 1px solid rgba(232,160,32,.2);
}
.resumen-total-label {
    font-size: .78rem; font-weight: 600;
    letter-spacing: .06em; text-transform: uppercase;
    color: var(--gold);
}
.resumen-total-val {
    font-family: 'Syne', sans-serif;
    font-weight: 800; font-size: 1.5rem;
    color: var(--gold);
    transition: all .25s;
}
</style>

<script>
// Datos de vehículos para el JS
const vehiculosData = <?= json_encode($vehiculos) ?>;
const clientesData  = <?= json_encode($clientes)  ?>;

function autocompletarPrecio() {
    const sel     = document.getElementById('vehiculo_id');
    const opt     = sel.options[sel.selectedIndex];
    const precio  = opt.dataset.precio ?? '';
    const texto   = opt.text ?? '—';

    document.getElementById('total').value = precio;
    document.getElementById('resumenVehiculo').textContent =
        sel.value ? texto.split('—')[0].trim() : '—';
    document.getElementById('resumenTotal').textContent =
        precio ? '$' + parseFloat(precio).toLocaleString('es-DO', {minimumFractionDigits:2}) : '$0.00';
}

function actualizarPreview() {
    const sel   = document.getElementById('cliente_id');
    const texto = sel.options[sel.selectedIndex]?.text ?? '—';
    document.getElementById('resumenCliente').textContent = sel.value ? texto : '—';
}

document.getElementById('total').addEventListener('input', function() {
    const val = parseFloat(this.value) || 0;
    document.getElementById('resumenTotal').textContent =
        '$' + val.toLocaleString('es-DO', {minimumFractionDigits:2});
});

document.getElementById('fecha').addEventListener('change', function() {
    const d = new Date(this.value + 'T00:00:00');
    document.getElementById('resumenFecha').textContent =
        d.toLocaleDateString('es-DO', {day:'2-digit', month:'2-digit', year:'numeric'});
});

document.getElementById('ventaForm').addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    btn.querySelector('.btn-txt').style.display = 'none';
    btn.querySelector('.ds-btn-spinner').style.display = 'inline-block';
    btn.disabled = true;
});
</script>

<?php require_once "views/layouts/footer.php"; ?>