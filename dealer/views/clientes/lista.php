<?php $activeNav = 'clientes'; ?>
<?php require_once "views/layouts/header.php"; ?>
<?php require_once "views/layouts/sidebar.php"; ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Gestión de <span>Clientes</span></h1>
        <div class="page-breadcrumb">
            <i class="bi bi-house-fill"></i>
            <a href="index.php?controller=dashboard&action=index">Inicio</a>
            <i class="bi bi-chevron-right" style="font-size:.65rem;"></i>
            <span>Clientes</span>
        </div>
    </div>
    <a href="index.php?controller=cliente&action=crear" class="ds-btn ds-btn-primary">
        <i class="bi bi-person-plus-fill"></i>
        Agregar cliente
    </a>
</div>

<?php if (!empty($mensaje)): ?>
<div class="ds-alert ds-alert-<?= htmlspecialchars($tipo_mensaje ?? 'success') ?> mb-4">
    <i class="bi bi-check-circle-fill"></i>
    <?= htmlspecialchars($mensaje) ?>
    <button class="ds-alert-close" onclick="this.parentElement.remove()"><i class="bi bi-x"></i></button>
</div>
<?php endif; ?>

<div class="ds-panel" style="animation: fadeUp .5s ease both;">
    <div class="ds-panel-header">
        <div class="ds-panel-title">
            <i class="bi bi-people-fill"></i>
            Listado de clientes
            <span class="ds-badge"><?= is_array($clientes) ? count($clientes) : 0 ?> registros</span>
        </div>
        <div class="ds-table-tools">
            <div class="ds-search-wrap">
                <i class="bi bi-search ds-search-icon"></i>
                <input type="text" id="tableSearch" class="ds-search"
                       placeholder="Buscar cliente...">
            </div>
        </div>
    </div>

    <div class="ds-table-wrap">
        <table class="ds-table" id="clientesTable">
            <thead>
                <tr>
                    <th style="width:50px;">#</th>
                    <th>Cliente</th>
                    <th>Cédula</th>
                    <th>Teléfono</th>
                    <th>Fecha registro</th>
                    <th style="width:120px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($clientes)): ?>
                <?php foreach ($clientes as $i => $c): ?>
                <tr>
                    <td class="ds-td-muted"><?= $i + 1 ?></td>

                    <!-- Avatar + Nombre -->
                    <td>
                        <div style="display:flex;align-items:center;gap:.75rem;">
                            <div class="cliente-avatar">
                                <?= strtoupper(substr($c['nombre'], 0, 1)) ?>
                            </div>
                            <p class="ds-td-title"><?= htmlspecialchars($c['nombre']) ?></p>
                        </div>
                    </td>

                    <td>
                        <span class="ds-chip">
                            <i class="bi bi-person-vcard" style="font-size:.75rem;margin-right:3px;"></i>
                            <?= htmlspecialchars($c['cedula']) ?>
                        </span>
                    </td>

                    <td>
                        <a href="tel:<?= htmlspecialchars($c['telefono']) ?>"
                           style="color:var(--gold);text-decoration:none;font-size:.88rem;">
                            <i class="bi bi-telephone-fill" style="font-size:.75rem;margin-right:3px;"></i>
                            <?= htmlspecialchars($c['telefono']) ?>
                        </a>
                    </td>

                    <td class="ds-td-muted">
                        <i class="bi bi-calendar3" style="font-size:.75rem;margin-right:3px;"></i>
                        <?= date('d/m/Y', strtotime($c['created_at'])) ?>
                    </td>

                    <td>
                        <div class="ds-actions">
                            <a href="index.php?controller=cliente&action=editar&id=<?= $c['id'] ?>"
                               class="ds-action-btn ds-action-edit" title="Editar">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <a href="index.php?controller=cliente&action=eliminar&id=<?= $c['id'] ?>"
                               class="ds-action-btn ds-action-delete"
                               title="Eliminar"
                               onclick="return confirmDelete(event, '<?= htmlspecialchars($c['nombre']) ?>')">
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
                            <i class="bi bi-people"></i>
                            <p>No hay clientes registrados</p>
                            <a href="index.php?controller=cliente&action=crear"
                               class="ds-btn ds-btn-primary mt-2">
                                <i class="bi bi-person-plus-fill"></i> Agregar el primero
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
        <h3 class="ds-modal-title">¿Eliminar cliente?</h3>
        <p class="ds-modal-body">
            Estás a punto de eliminar a <strong id="deleteTarget"></strong>.
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
    width: 36px; height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, rgba(232,160,32,.2), rgba(232,160,32,.05));
    border: 1px solid rgba(232,160,32,.3);
    display: flex; align-items: center; justify-content: center;
    font-family: 'Syne', sans-serif;
    font-weight: 700;
    font-size: .9rem;
    color: var(--gold);
    flex-shrink: 0;
}
</style>

<script>
document.getElementById('tableSearch').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#clientesTable tbody tr').forEach(row => {
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