<?php
$activeNav  = 'vehiculos';
$esEdicion  = isset($vehiculo) && !empty($vehiculo['id']);
$titulo     = $esEdicion ? 'Editar vehículo' : 'Nuevo vehículo';
$accion     = $esEdicion
    ? "index.php?controller=vehiculo&action=actualizar"
    : "index.php?controller=vehiculo&action=guardar";
?>
<?php require_once "views/layouts/header.php"; ?>
<?php require_once "views/layouts/sidebar.php"; ?>

<!-- ══════════════════════════════════════
     VEHÍCULOS — Formulario crear / editar
     ══════════════════════════════════════ -->

<div class="page-header">
    <div>
        <h1 class="page-title"><?= $esEdicion ? 'Editar <span>Vehículo</span>' : 'Nuevo <span>Vehículo</span>' ?></h1>
        <div class="page-breadcrumb">
            <i class="bi bi-house-fill"></i>
            <a href="index.php?controller=dashboard&action=index">Inicio</a>
            <i class="bi bi-chevron-right" style="font-size:.65rem;"></i>
            <a href="index.php?controller=vehiculo&action=index">Vehículos</a>
            <i class="bi bi-chevron-right" style="font-size:.65rem;"></i>
            <span><?= $esEdicion ? 'Editar' : 'Crear' ?></span>
        </div>
    </div>
    <a href="index.php?controller=vehiculo&action=index" class="ds-btn ds-btn-ghost">
        <i class="bi bi-arrow-left"></i>
        Volver al listado
    </a>
</div>

<!-- Flash message -->
<?php if (!empty($error)): ?>
<div class="ds-alert ds-alert-danger mb-4">
    <i class="bi bi-exclamation-triangle-fill"></i>
    <?= htmlspecialchars($error) ?>
    <button class="ds-alert-close" onclick="this.parentElement.remove()"><i class="bi bi-x"></i></button>
</div>
<?php endif; ?>

<!-- Form -->
<form method="POST"
      action="<?= $accion ?>"
      enctype="multipart/form-data"
      id="vehiculoForm"
      novalidate>

    <?php if ($esEdicion): ?>
        <input type="hidden" name="id" value="<?= (int)$vehiculo['id'] ?>">
    <?php endif; ?>

    <div class="ds-form-grid">

        <!-- ── Left column ── -->
        <div class="ds-form-col">

            <div class="ds-panel" style="animation: fadeUp .45s ease both;">
                <div class="ds-panel-header">
                    <div class="ds-panel-title"><i class="bi bi-info-circle-fill"></i> Información del vehículo</div>
                </div>
                <div class="ds-panel-body">

                    <div class="ds-form-row">
                        <div class="ds-field">
                            <label class="ds-label" for="marca">
                                <i class="bi bi-bookmark-fill"></i> Marca <span class="ds-required">*</span>
                            </label>
                            <input type="text"
                                   id="marca"
                                   name="marca"
                                   class="ds-input"
                                   placeholder="Ej: Toyota, BMW, Ford..."
                                   value="<?= htmlspecialchars($vehiculo['marca'] ?? '') ?>"
                                   required>
                            <span class="ds-field-hint">Marca del fabricante</span>
                        </div>

                        <div class="ds-field">
                            <label class="ds-label" for="modelo">
                                <i class="bi bi-tag-fill"></i> Modelo <span class="ds-required">*</span>
                            </label>
                            <input type="text"
                                   id="modelo"
                                   name="modelo"
                                   class="ds-input"
                                   placeholder="Ej: Corolla, Serie 3, Mustang..."
                                   value="<?= htmlspecialchars($vehiculo['modelo'] ?? '') ?>"
                                   required>
                            <span class="ds-field-hint">Nombre del modelo</span>
                        </div>
                    </div>

                    <div class="ds-form-row">
                        <div class="ds-field">
                            <label class="ds-label" for="anio">
                                <i class="bi bi-calendar3"></i> Año <span class="ds-required">*</span>
                            </label>
                            <input type="number"
                                   id="anio"
                                   name="año"
                                   class="ds-input"
                                   placeholder="<?= date('Y') ?>"
                                   min="1900"
                                   max="<?= date('Y') + 1 ?>"
                                   value="<?= htmlspecialchars($vehiculo['año'] ?? $vehiculo['anio'] ?? '') ?>"
                                   required>
                        </div>

                        <div class="ds-field">
                            <label class="ds-label" for="precio">
                                <i class="bi bi-currency-dollar"></i> Precio <span class="ds-required">*</span>
                            </label>
                            <div class="ds-input-prefix-wrap">
                                <span class="ds-input-prefix">$</span>
                                <input type="number"
                                       id="precio"
                                       name="precio"
                                       class="ds-input ds-input-prefixed"
                                       placeholder="0.00"
                                       min="0"
                                       step="0.01"
                                       value="<?= htmlspecialchars($vehiculo['precio'] ?? '') ?>"
                                       required>
                            </div>
                        </div>
                    </div>

                    <div class="ds-field">
                        <label class="ds-label" for="estado">
                            <i class="bi bi-toggle-on"></i> Estado <span class="ds-required">*</span>
                        </label>
                        <div class="ds-select-wrap">
                            <select id="estado" name="estado" class="ds-select" required>
                                <?php
                                    $estados   = ['disponible' => 'Disponible', 'vendido' => 'Vendido', 'reservado' => 'Reservado'];
                                    $selActual = strtolower($vehiculo['estado'] ?? 'disponible');
                                    foreach ($estados as $val => $lbl):
                                ?>
                                <option value="<?= $val ?>" <?= $selActual === $val ? 'selected' : '' ?>>
                                    <?= $lbl ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <i class="bi bi-chevron-down ds-select-icon"></i>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <!-- ── Right column ── -->
        <div class="ds-form-col">

            <div class="ds-panel" style="animation: fadeUp .45s ease .1s both;">
                <div class="ds-panel-header">
                    <div class="ds-panel-title"><i class="bi bi-image-fill"></i> Imagen del vehículo</div>
                </div>
                <div class="ds-panel-body">

                    <!-- Image drop zone -->
                    <div class="ds-dropzone" id="dropzone" onclick="document.getElementById('imagen').click()">
                        <div class="ds-dropzone-content" id="dropzoneContent">
                            <div class="ds-dropzone-icon"><i class="bi bi-cloud-arrow-up-fill"></i></div>
                            <p class="ds-dropzone-title">Arrastra una imagen aquí</p>
                            <p class="ds-dropzone-sub">o haz clic para seleccionar</p>
                            <p class="ds-dropzone-hint">PNG, JPG, WEBP — máx. 5 MB</p>
                        </div>
                        <img src="" alt="Preview" class="ds-dropzone-preview" id="imgPreview">
                    </div>

                    <input type="file"
                           id="imagen"
                           name="imagen"
                           accept="image/*"
                           style="display:none;">

                    <?php if ($esEdicion && !empty($vehiculo['imagen'])): ?>
                    <div class="ds-current-img">
                        <p class="ds-label mb-2"><i class="bi bi-image"></i> Imagen actual</p>
                        <div class="ds-thumb ds-thumb-lg">
                            <img src="public/uploads/<?= htmlspecialchars($vehiculo['imagen']) ?>"
                                 alt="Imagen actual" id="currentImg">
                        </div>
                        <p class="ds-field-hint mt-1">Sube una nueva imagen para reemplazarla</p>
                    </div>
                    <?php endif; ?>

                </div>
            </div>

            <!-- Action buttons -->
            <div class="ds-form-actions" style="animation: fadeUp .45s ease .18s both;">
                <button type="button"
                        class="ds-btn ds-btn-ghost"
                        onclick="window.location='index.php?controller=vehiculo&action=index'">
                    <i class="bi bi-x-lg"></i> Cancelar
                </button>
                <button type="submit" class="ds-btn ds-btn-primary" id="submitBtn">
                    <i class="bi bi-<?= $esEdicion ? 'floppy-fill' : 'plus-lg' ?>"></i>
                    <span><?= $esEdicion ? 'Guardar cambios' : 'Crear vehículo' ?></span>
                    <span class="ds-btn-spinner" style="display:none;"></span>
                </button>
            </div>

        </div>
    </div>
</form>

<script>
/* ── Image dropzone ── */
const input      = document.getElementById('imagen');
const dropzone   = document.getElementById('dropzone');
const preview    = document.getElementById('imgPreview');
const content    = document.getElementById('dropzoneContent');
const currentImg = document.getElementById('currentImg');

function showPreview(file) {
    if (!file || !file.type.startsWith('image/')) return;
    const reader = new FileReader();
    reader.onload = e => {
        preview.src = e.target.result;
        preview.style.display = 'block';
        content.style.display = 'none';
        if (currentImg) currentImg.src = e.target.result;
    };
    reader.readAsDataURL(file);
}

input.addEventListener('change', () => showPreview(input.files[0]));

dropzone.addEventListener('dragover', e => {
    e.preventDefault();
    dropzone.classList.add('drag-over');
});
dropzone.addEventListener('dragleave', () => dropzone.classList.remove('drag-over'));
dropzone.addEventListener('drop', e => {
    e.preventDefault();
    dropzone.classList.remove('drag-over');
    const file = e.dataTransfer.files[0];
    if (file) {
        const dt = new DataTransfer();
        dt.items.add(file);
        input.files = dt.files;
        showPreview(file);
    }
});

/* ── Loading state on submit ── */
document.getElementById('vehiculoForm').addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    btn.querySelector('span:first-of-type').style.display = 'none';
    btn.querySelector('.ds-btn-spinner').style.display = 'inline-block';
    btn.disabled = true;
});

/* ── Client-side validation highlight ── */
document.getElementById('vehiculoForm').addEventListener('submit', function(e) {
    const required = this.querySelectorAll('[required]');
    let valid = true;
    required.forEach(el => {
        if (!el.value.trim()) {
            el.classList.add('ds-input-error');
            valid = false;
        } else {
            el.classList.remove('ds-input-error');
        }
    });
    if (!valid) e.preventDefault();
}, true);

document.querySelectorAll('.ds-input').forEach(el => {
    el.addEventListener('input', () => el.classList.remove('ds-input-error'));
});
</script>

<?php require_once "views/layouts/footer.php"; ?>