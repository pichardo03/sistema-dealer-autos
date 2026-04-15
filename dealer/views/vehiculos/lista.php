<?php
$activeNav = 'vehiculos';
?>
<?php require_once "views/layouts/header.php"; ?>
<?php require_once "views/layouts/sidebar.php"; ?>

<!-- ══════════════════════════════════════
     VEHÍCULOS — Lista con Fetch API / AJAX
     Sin recarga de página
     ══════════════════════════════════════ -->

<div class="page-header">
    <div>
        <h1 class="page-title">Gestión de <span>Vehículos</span></h1>
        <div class="page-breadcrumb">
            <i class="bi bi-house-fill"></i>
            <a href="index.php?controller=dashboard&action=index">Inicio</a>
            <i class="bi bi-chevron-right" style="font-size:.65rem;"></i>
            <span>Vehículos</span>
        </div>
    </div>
    <button class="ds-btn ds-btn-primary" onclick="abrirModalCrear()">
        <i class="bi bi-plus-lg"></i>
        Agregar vehículo
    </button>
</div>

<!-- Toast de notificaciones -->
<div id="toastContainer" style="
    position:fixed; top:1.2rem; right:1.2rem;
    z-index:9999; display:flex; flex-direction:column; gap:.5rem;">
</div>

<!-- Panel principal -->
<div class="ds-panel" style="animation: fadeUp .5s ease both;">
    <div class="ds-panel-header">
        <div class="ds-panel-title">
            <i class="bi bi-car-front-fill"></i>
            Inventario de vehículos
            <span class="ds-badge" id="totalBadge">— registros</span>
        </div>
        <div class="ds-table-tools">
            <div class="ds-search-wrap">
                <i class="bi bi-search ds-search-icon"></i>
                <input type="text" id="searchInput" class="ds-search"
                       placeholder="Buscar marca, modelo..."
                       oninput="buscarVehiculos(this.value)">
            </div>
            <button class="ds-btn ds-btn-ghost" onclick="cargarVehiculos()" title="Recargar">
                <i class="bi bi-arrow-clockwise" id="reloadIcon"></i>
            </button>
        </div>
    </div>

    <!-- Tabla -->
    <div class="ds-table-wrap">
        <table class="ds-table">
            <thead>
                <tr>
                    <th style="width:50px;">#</th>
                    <th>Imagen</th>
                    <th>Marca / Modelo</th>
                    <th>Año</th>
                    <th>Precio</th>
                    <th>Estado</th>
                    <th style="width:120px;">Acciones</th>
                </tr>
            </thead>
            <tbody id="tablaBody">
                <!-- Skeleton loader inicial -->
                <tr class="skeleton-row">
                    <td colspan="7">
                        <div class="ds-empty">
                            <div class="ajax-spinner"></div>
                            <p style="margin-top:.8rem;">Cargando vehículos...</p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- ══════════════════════════════════════
     MODAL — Ver detalle del vehículo
     ══════════════════════════════════════ -->
<div class="ds-modal-overlay" id="detalleOverlay">
    <div class="ds-modal" style="max-width:500px;">
        <div id="detalleContenido">
            <div style="text-align:center;padding:2rem;">
                <div class="ajax-spinner" style="margin:0 auto;"></div>
            </div>
        </div>
        <div class="ds-modal-actions" style="margin-top:1.2rem;">
            <button class="ds-btn ds-btn-ghost" onclick="cerrarModal('detalleOverlay')">
                <i class="bi bi-x-lg"></i> Cerrar
            </button>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════
     MODAL — Confirmar eliminar
     ══════════════════════════════════════ -->
<div class="ds-modal-overlay" id="deleteOverlay">
    <div class="ds-modal">
        <div class="ds-modal-icon danger"><i class="bi bi-trash3-fill"></i></div>
        <h3 class="ds-modal-title">¿Eliminar vehículo?</h3>
        <p class="ds-modal-body">
            Eliminarás <strong id="deleteNombre"></strong>. Esta acción no se puede deshacer.
        </p>
        <div class="ds-modal-actions">
            <button class="ds-btn ds-btn-ghost" onclick="cerrarModal('deleteOverlay')">Cancelar</button>
            <button class="ds-btn ds-btn-danger" id="deleteConfirmBtn">
                <i class="bi bi-trash3-fill"></i> Eliminar
            </button>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════
     MODAL — Crear vehículo (sin recarga)
     ══════════════════════════════════════ -->
<div class="ds-modal-overlay" id="crearOverlay">
    <div class="ds-modal" style="max-width:560px; text-align:left;">
        <div class="ds-panel-title mb-3" style="font-size:1.1rem;">
            <i class="bi bi-plus-circle-fill" style="color:var(--gold);"></i>
            Nuevo Vehículo
        </div>

        <form id="crearForm" novalidate>
            <div class="ds-form-row">
                <div class="ds-field">
                    <label class="ds-label"><i class="bi bi-bookmark-fill"></i> Marca <span class="ds-required">*</span></label>
                    <input type="text" name="marca" class="ds-input" placeholder="Toyota, BMW..." required>
                </div>
                <div class="ds-field">
                    <label class="ds-label"><i class="bi bi-tag-fill"></i> Modelo <span class="ds-required">*</span></label>
                    <input type="text" name="modelo" class="ds-input" placeholder="Corolla, Serie 3..." required>
                </div>
            </div>
            <div class="ds-form-row">
                <div class="ds-field">
                    <label class="ds-label"><i class="bi bi-calendar3"></i> Año <span class="ds-required">*</span></label>
                    <input type="number" name="anio" class="ds-input"
                           placeholder="<?= date('Y') ?>" min="1900" max="<?= date('Y')+1 ?>" required>
                </div>
                <div class="ds-field">
                    <label class="ds-label"><i class="bi bi-currency-dollar"></i> Precio <span class="ds-required">*</span></label>
                    <div class="ds-input-prefix-wrap">
                        <span class="ds-input-prefix">$</span>
                        <input type="number" name="precio" class="ds-input ds-input-prefixed"
                               placeholder="0.00" min="0" step="0.01" required>
                    </div>
                </div>
            </div>
            <div class="ds-field" style="margin-bottom:1rem;">
                <label class="ds-label"><i class="bi bi-toggle-on"></i> Estado</label>
                <div class="ds-select-wrap">
                    <select name="estado" class="ds-select">
                        <option value="disponible">Disponible</option>
                        <option value="vendido">Vendido</option>
                        <option value="reservado">Reservado</option>
                    </select>
                    <i class="bi bi-chevron-down ds-select-icon"></i>
                </div>
            </div>
            <div class="ds-field" style="margin-bottom:1.2rem;">
                <label class="ds-label"><i class="bi bi-image-fill"></i> Imagen</label>
                <input type="file" name="imagen" accept="image/*" class="ds-input" style="padding:.5rem;">
            </div>

            <div class="ds-form-actions">
                <button type="button" class="ds-btn ds-btn-ghost" onclick="cerrarModal('crearOverlay')">
                    <i class="bi bi-x-lg"></i> Cancelar
                </button>
                <button type="submit" class="ds-btn ds-btn-primary" id="crearBtn">
                    <i class="bi bi-plus-lg"></i>
                    <span class="btn-txt">Crear vehículo</span>
                    <span class="ds-btn-spinner" style="display:none;"></span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ══════════════════════════════════════
     CSS extra para AJAX
     ══════════════════════════════════════ -->
<style>
.ajax-spinner {
    width: 32px; height: 32px;
    border: 3px solid rgba(232,160,32,.15);
    border-top-color: var(--gold);
    border-radius: 50%;
    animation: spin .7s linear infinite;
}
.ds-action-view {
    /* botón ver detalle */
}
.ds-action-view:hover {
    background: rgba(232,160,32,.15);
    border-color: rgba(232,160,32,.3);
    color: var(--gold);
    transform: scale(1.1);
}
.toast {
    display: flex; align-items: center; gap: .5rem;
    padding: .75rem 1.1rem;
    border-radius: 10px;
    font-size: .85rem; font-weight: 500;
    min-width: 260px;
    box-shadow: 0 8px 24px rgba(0,0,0,.4);
    animation: toastIn .3s ease;
    border: 1px solid transparent;
}
@keyframes toastIn {
    from { opacity:0; transform: translateX(20px); }
    to   { opacity:1; transform: translateX(0); }
}
.toast-success { background:#0d2b1e; border-color:rgba(16,185,129,.3); color:#6ee7b7; }
.toast-danger  { background:#2b0d0d; border-color:rgba(226,75,74,.3);  color:#f09595; }
.toast-info    { background:#0d1b2b; border-color:rgba(232,160,32,.3); color:var(--gold); }

@keyframes spin { to { transform: rotate(360deg); } }

#reloadIcon.spinning { animation: spin .6s linear infinite; }

.detalle-img {
    width:100%; height:200px; object-fit:cover;
    border-radius:12px; margin-bottom:1rem;
}
.detalle-grid {
    display: grid; grid-template-columns: 1fr 1fr; gap:.6rem;
}
.detalle-item { background:var(--bg3); border:1px solid var(--border); border-radius:8px; padding:.6rem .8rem; }
.detalle-label { font-size:.68rem; letter-spacing:.1em; text-transform:uppercase; color:var(--text-dim); margin-bottom:.2rem; }
.detalle-val { font-size:.9rem; font-weight:500; color:var(--text); }
</style>

<!-- ══════════════════════════════════════
     JAVASCRIPT — Fetch API
     ══════════════════════════════════════ -->
<script>
// ─────────────────────────────────────────
// CONFIGURACIÓN
// ─────────────────────────────────────────
const API_URL = 'api/vehiculos.php';

let searchTimer = null;
let vehiculoIdAEliminar = null;

// ─────────────────────────────────────────
// 1. CARGAR TODOS LOS VEHÍCULOS
// ─────────────────────────────────────────
async function cargarVehiculos() {
    const icon = document.getElementById('reloadIcon');
    icon.classList.add('spinning');
    mostrarSkeleton();

    try {
        // ── FETCH API ──────────────────────────
        const response = await fetch(`${API_URL}?accion=listar`);

        // Verificar que la respuesta sea OK
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }

        // Parsear JSON de la respuesta
        const json = await response.json();

        // Log en consola (para el screenshot del PDF)
        console.log('✅ Respuesta JSON del servidor:', json);
        console.log('📦 Total vehículos:', json.total);
        console.log('🚗 Datos:', json.data);

        if (json.success) {
            renderTabla(json.data);
            document.getElementById('totalBadge').textContent = `${json.total} registros`;
        } else {
            mostrarError('Error al cargar los vehículos');
        }

    } catch (error) {
        console.error('❌ Error en fetch:', error);
        mostrarError('No se pudo conectar con el servidor');
    } finally {
        icon.classList.remove('spinning');
    }
}

// ─────────────────────────────────────────
// 2. BUSCAR VEHÍCULOS (con debounce)
// ─────────────────────────────────────────
function buscarVehiculos(termino) {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(async () => {

        if (termino.trim() === '') {
            cargarVehiculos();
            return;
        }

        mostrarSkeleton();

        try {
            // ── FETCH API con parámetro de búsqueda ──
            const response = await fetch(
                `${API_URL}?accion=buscar&q=${encodeURIComponent(termino)}`
            );
            const json = await response.json();

            console.log(`🔍 Búsqueda "${termino}":`, json);

            renderTabla(json.data ?? []);
            document.getElementById('totalBadge').textContent =
                `${json.total ?? 0} resultados`;

        } catch (error) {
            console.error('❌ Error en búsqueda:', error);
        }

    }, 350); // debounce 350ms
}

// ─────────────────────────────────────────
// 3. VER DETALLE DE UN VEHÍCULO
// ─────────────────────────────────────────
async function verDetalle(id) {
    abrirModal('detalleOverlay');
    document.getElementById('detalleContenido').innerHTML = `
        <div style="text-align:center;padding:2rem;">
            <div class="ajax-spinner" style="margin:0 auto;"></div>
            <p style="margin-top:.8rem;color:var(--text-dim);">Cargando...</p>
        </div>`;

    try {
        // ── FETCH API para obtener un registro ──
        const response = await fetch(`${API_URL}?accion=obtener&id=${id}`);
        const json     = await response.json();

        console.log(`🔎 Detalle vehículo ID ${id}:`, json);

        if (json.success) {
            const v = json.data;
            const estadoClase = { disponible:'success', vendido:'danger', reservado:'warning' };
            const clase = estadoClase[v.estado] ?? 'neutral';

            document.getElementById('detalleContenido').innerHTML = `
                ${v.imagen
                    ? `<img src="uploads/${v.imagen}" class="detalle-img" alt="${v.marca}">`
                    : `<div style="width:100%;height:120px;background:var(--bg3);border-radius:12px;
                        display:flex;align-items:center;justify-content:center;margin-bottom:1rem;font-size:3rem;color:var(--text-dim);">
                        <i class="bi bi-car-front"></i></div>`
                }
                <div class="detalle-grid">
                    <div class="detalle-item">
                        <p class="detalle-label">Marca</p>
                        <p class="detalle-val">${v.marca}</p>
                    </div>
                    <div class="detalle-item">
                        <p class="detalle-label">Modelo</p>
                        <p class="detalle-val">${v.modelo}</p>
                    </div>
                    <div class="detalle-item">
                        <p class="detalle-label">Año</p>
                        <p class="detalle-val">${v.anio}</p>
                    </div>
                    <div class="detalle-item">
                        <p class="detalle-label">Precio</p>
                        <p class="detalle-val" style="color:var(--gold);">
                            $${parseFloat(v.precio).toLocaleString('es-DO', {minimumFractionDigits:2})}
                        </p>
                    </div>
                    <div class="detalle-item" style="grid-column:1/-1;">
                        <p class="detalle-label">Estado</p>
                        <span class="ds-status ds-status-${clase}" style="margin-top:.3rem;">
                            ${v.estado.charAt(0).toUpperCase() + v.estado.slice(1)}
                        </span>
                    </div>
                </div>`;
        }
    } catch (error) {
        console.error('❌ Error al obtener detalle:', error);
    }
}

// ─────────────────────────────────────────
// 4. CREAR VEHÍCULO SIN RECARGA
// ─────────────────────────────────────────
document.getElementById('crearForm').addEventListener('submit', async function(e) {
    e.preventDefault(); // ← evita recarga de página

    const btn     = document.getElementById('crearBtn');
    const formData = new FormData(this);

    // UI: estado cargando
    btn.querySelector('.btn-txt').style.display  = 'none';
    btn.querySelector('.ds-btn-spinner').style.display = 'inline-block';
    btn.disabled = true;

    try {
        // ── FETCH API POST con FormData ────────
        const response = await fetch(`${API_URL}?accion=guardar`, {
            method: 'POST',
            body:   formData   // incluye archivo imagen
        });
        const json = await response.json();

        console.log('💾 Crear vehículo - respuesta:', json);

        if (json.success) {
            cerrarModal('crearOverlay');
            this.reset();
            mostrarToast(json.mensaje, 'success');
            cargarVehiculos(); // recarga tabla sin recargar página
        } else {
            mostrarToast('Error al crear el vehículo', 'danger');
        }

    } catch (error) {
        console.error('❌ Error al guardar:', error);
        mostrarToast('Error de conexión', 'danger');
    } finally {
        btn.querySelector('.btn-txt').style.display  = 'inline';
        btn.querySelector('.ds-btn-spinner').style.display = 'none';
        btn.disabled = false;
    }
});

// ─────────────────────────────────────────
// 5. ELIMINAR VEHÍCULO SIN RECARGA
// ─────────────────────────────────────────
function confirmarEliminar(id, nombre) {
    vehiculoIdAEliminar = id;
    document.getElementById('deleteNombre').textContent = nombre;
    abrirModal('deleteOverlay');
}

document.getElementById('deleteConfirmBtn').addEventListener('click', async () => {
    if (!vehiculoIdAEliminar) return;

    const btn = document.getElementById('deleteConfirmBtn');
    btn.innerHTML = '<span class="ds-btn-spinner" style="display:inline-block;border-color:rgba(255,255,255,.3);border-top-color:#f09595;"></span>';
    btn.disabled  = true;

    const formData = new FormData();
    formData.append('id', vehiculoIdAEliminar);

    try {
        // ── FETCH API DELETE ───────────────────
        const response = await fetch(`${API_URL}?accion=eliminar`, {
            method: 'POST',
            body:   formData
        });
        const json = await response.json();

        console.log('🗑️ Eliminar vehículo - respuesta:', json);

        cerrarModal('deleteOverlay');

        if (json.success) {
            mostrarToast(json.mensaje, 'success');
            cargarVehiculos();
        } else {
            mostrarToast('Error al eliminar', 'danger');
        }

    } catch (error) {
        console.error('❌ Error al eliminar:', error);
        mostrarToast('Error de conexión', 'danger');
    } finally {
        btn.innerHTML = '<i class="bi bi-trash3-fill"></i> Eliminar';
        btn.disabled  = false;
        vehiculoIdAEliminar = null;
    }
});

// ─────────────────────────────────────────
// RENDER — Dibuja la tabla con los datos JSON
// ─────────────────────────────────────────
function renderTabla(vehiculos) {
    const tbody = document.getElementById('tablaBody');

    if (!vehiculos || vehiculos.length === 0) {
        tbody.innerHTML = `
            <tr><td colspan="7">
                <div class="ds-empty">
                    <i class="bi bi-car-front"></i>
                    <p>No se encontraron vehículos</p>
                </div>
            </td></tr>`;
        return;
    }

    const estadoMap = {
        disponible: { clase:'success', icono:'check-circle-fill', label:'Disponible' },
        vendido:    { clase:'danger',  icono:'x-circle-fill',     label:'Vendido'    },
        reservado:  { clase:'warning', icono:'clock-fill',        label:'Reservado'  },
    };

    tbody.innerHTML = vehiculos.map((v, i) => {
        const e = estadoMap[v.estado?.toLowerCase()] ??
                  { clase:'neutral', icono:'circle', label: v.estado };
        const imgHtml = v.imagen
            ? `<div class="ds-thumb">
                   <img src="uploads/${v.imagen}" alt="${v.marca}" loading="lazy">
               </div>`
            : `<div class="ds-thumb ds-thumb-empty">
                   <i class="bi bi-car-front"></i>
               </div>`;
        const precio = parseFloat(v.precio).toLocaleString('es-DO',
                       { minimumFractionDigits:2 });

        return `
        <tr style="animation: fadeUp .3s ease ${i * 0.04}s both;">
            <td class="ds-td-muted">${i + 1}</td>
            <td>${imgHtml}</td>
            <td>
                <p class="ds-td-title">${v.marca}</p>
                <p class="ds-td-sub">${v.modelo}</p>
            </td>
            <td><span class="ds-chip">${v.anio ?? '—'}</span></td>
            <td class="ds-td-price">$${precio}</td>
            <td>
                <span class="ds-status ds-status-${e.clase}">
                    <i class="bi bi-${e.icono}"></i> ${e.label}
                </span>
            </td>
            <td>
                <div class="ds-actions">
                    <button class="ds-action-btn ds-action-view" title="Ver detalle"
                            onclick="verDetalle(${v.id})">
                        <i class="bi bi-eye-fill"></i>
                    </button>
                    <a href="index.php?controller=vehiculo&action=editar&id=${v.id}"
                       class="ds-action-btn ds-action-edit" title="Editar">
                        <i class="bi bi-pencil-fill"></i>
                    </a>
                    <button class="ds-action-btn ds-action-delete" title="Eliminar"
                            onclick="confirmarEliminar(${v.id}, '${v.marca} ${v.modelo}')">
                        <i class="bi bi-trash3-fill"></i>
                    </button>
                </div>
            </td>
        </tr>`;
    }).join('');
}

// ─────────────────────────────────────────
// HELPERS
// ─────────────────────────────────────────
function mostrarSkeleton() {
    document.getElementById('tablaBody').innerHTML = `
        <tr><td colspan="7">
            <div class="ds-empty">
                <div class="ajax-spinner"></div>
                <p style="margin-top:.8rem;color:var(--text-dim);">Cargando...</p>
            </div>
        </td></tr>`;
}

function mostrarError(msg) {
    document.getElementById('tablaBody').innerHTML = `
        <tr><td colspan="7">
            <div class="ds-empty">
                <i class="bi bi-wifi-off" style="color:#f09595;"></i>
                <p style="color:#f09595;">${msg}</p>
                <button class="ds-btn ds-btn-ghost mt-2" onclick="cargarVehiculos()">
                    <i class="bi bi-arrow-clockwise"></i> Reintentar
                </button>
            </div>
        </td></tr>`;
}

function abrirModal(id)  { document.getElementById(id).classList.add('visible'); }
function cerrarModal(id) { document.getElementById(id).classList.remove('visible'); }
function abrirModalCrear() { abrirModal('crearOverlay'); }

function mostrarToast(mensaje, tipo = 'success') {
    const icons = { success:'check-circle-fill', danger:'exclamation-triangle-fill', info:'info-circle-fill' };
    const toast = document.createElement('div');
    toast.className = `toast toast-${tipo}`;
    toast.innerHTML = `<i class="bi bi-${icons[tipo] ?? 'info-circle-fill'}"></i> ${mensaje}`;
    document.getElementById('toastContainer').appendChild(toast);
    setTimeout(() => toast.remove(), 3500);
}

// Cerrar modales con Escape o clic fondo
document.querySelectorAll('.ds-modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', e => {
        if (e.target === overlay) overlay.classList.remove('visible');
    });
});
document.addEventListener('keydown', e => {
    if (e.key === 'Escape')
        document.querySelectorAll('.ds-modal-overlay.visible')
                .forEach(m => m.classList.remove('visible'));
});

// ─────────────────────────────────────────
// INICIO — cargar al abrir la página
// ─────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    cargarVehiculos();
    console.log('🚀 Dealer System — Fetch API inicializado');
    console.log('📡 Endpoint:', `${window.location.origin}/${API_URL}`);
});
</script>

<?php require_once "views/layouts/footer.php"; ?>