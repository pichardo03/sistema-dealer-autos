<?php $activeNav = 'clientes'; ?>
<?php require_once "views/layouts/header.php"; ?>
<?php require_once "views/layouts/sidebar.php"; ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Nuevo <span>Cliente</span></h1>
        <div class="page-breadcrumb">
            <i class="bi bi-house-fill"></i>
            <a href="index.php?controller=dashboard&action=index">Inicio</a>
            <i class="bi bi-chevron-right" style="font-size:.65rem;"></i>
            <a href="index.php?controller=cliente&action=index">Clientes</a>
            <i class="bi bi-chevron-right" style="font-size:.65rem;"></i>
            <span>Crear</span>
        </div>
    </div>
    <a href="index.php?controller=cliente&action=index" class="ds-btn ds-btn-ghost">
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
                    <i class="bi bi-person-fill"></i> Datos del cliente
                </div>
            </div>
            <div class="ds-panel-body">

                <form method="POST"
                      action="index.php?controller=cliente&action=guardar"
                      id="clienteForm"
                      novalidate>

                    <div class="ds-field">
                        <label class="ds-label" for="nombre">
                            <i class="bi bi-person-fill"></i>
                            Nombre completo <span class="ds-required">*</span>
                        </label>
                        <input type="text" id="nombre" name="nombre"
                               class="ds-input"
                               placeholder="Ej: Juan Pérez"
                               required>
                    </div>

                    <div class="ds-field">
                        <label class="ds-label" for="cedula">
                            <i class="bi bi-person-vcard-fill"></i>
                            Cédula <span class="ds-required">*</span>
                        </label>
                        <input type="text" id="cedula" name="cedula"
                               class="ds-input"
                               placeholder="Ej: 001-0000000-0"
                               required>
                        <span class="ds-field-hint">Formato: 001-0000000-0</span>
                    </div>

                    <div class="ds-field">
                        <label class="ds-label" for="telefono">
                            <i class="bi bi-telephone-fill"></i>
                            Teléfono <span class="ds-required">*</span>
                        </label>
                        <input type="text" id="telefono" name="telefono"
                               class="ds-input"
                               placeholder="Ej: 809-000-0000"
                               required>
                        <span class="ds-field-hint">Formato: 809-000-0000</span>
                    </div>

                    <div class="ds-form-actions" style="margin-top:1.2rem;">
                        <button type="button" class="ds-btn ds-btn-ghost"
                                onclick="window.location='index.php?controller=cliente&action=index'">
                            <i class="bi bi-x-lg"></i> Cancelar
                        </button>
                        <button type="submit" class="ds-btn ds-btn-primary" id="submitBtn">
                            <i class="bi bi-person-plus-fill"></i>
                            <span class="btn-txt">Crear cliente</span>
                            <span class="ds-btn-spinner" style="display:none;"></span>
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <!-- Columna derecha — preview -->
    <div class="ds-form-col">
        <div class="ds-panel" style="animation: fadeUp .45s ease .1s both;">
            <div class="ds-panel-header">
                <div class="ds-panel-title">
                    <i class="bi bi-eye-fill"></i> Vista previa
                </div>
            </div>
            <div class="ds-panel-body">
                <div class="cliente-preview-card">
                    <div class="preview-avatar" id="previewAvatar">?</div>
                    <div class="preview-info">
                        <p class="preview-nombre" id="previewNombre">Nombre del cliente</p>
                        <p class="preview-dato" id="previewCedula">
                            <i class="bi bi-person-vcard"></i> —
                        </p>
                        <p class="preview-dato" id="previewTelefono">
                            <i class="bi bi-telephone"></i> —
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
.cliente-preview-card {
    display: flex; align-items: center; gap: 1rem;
    padding: 1.2rem;
    background: rgba(232,160,32,.04);
    border: 1px solid rgba(232,160,32,.15);
    border-radius: var(--radius-md);
}
.preview-avatar {
    width: 58px; height: 58px; border-radius: 50%;
    background: linear-gradient(135deg, rgba(232,160,32,.25), rgba(232,160,32,.05));
    border: 2px solid rgba(232,160,32,.3);
    display: flex; align-items: center; justify-content: center;
    font-family: 'Syne', sans-serif;
    font-weight: 800; font-size: 1.5rem;
    color: var(--gold); flex-shrink: 0; transition: all .3s;
}
.preview-nombre {
    font-family: 'Syne', sans-serif;
    font-weight: 700; font-size: 1rem;
    color: var(--text); margin-bottom: .35rem;
}
.preview-dato {
    font-size: .82rem; color: var(--text-dim);
    display: flex; align-items: center; gap: .35rem; margin-bottom: .2rem;
}
.preview-dato i { color: var(--gold); font-size: .8rem; }
</style>

<script>
document.getElementById('nombre').addEventListener('input', function() {
    const val = this.value.trim();
    document.getElementById('previewNombre').textContent = val || 'Nombre del cliente';
    document.getElementById('previewAvatar').textContent = val ? val.charAt(0).toUpperCase() : '?';
});
document.getElementById('cedula').addEventListener('input', function() {
    document.getElementById('previewCedula').innerHTML =
        `<i class="bi bi-person-vcard"></i> ${this.value || '—'}`;
});
document.getElementById('telefono').addEventListener('input', function() {
    document.getElementById('previewTelefono').innerHTML =
        `<i class="bi bi-telephone"></i> ${this.value || '—'}`;
});

document.getElementById('clienteForm').addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    btn.querySelector('.btn-txt').style.display = 'none';
    btn.querySelector('.ds-btn-spinner').style.display = 'inline-block';
    btn.disabled = true;
});
</script>

<?php require_once "views/layouts/footer.php"; ?>