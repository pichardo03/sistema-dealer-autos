<!-- ════════════════════════════════════
     SIDEBAR
     ════════════════════════════════════ -->
<aside id="sidebar">

    <!-- Logo -->
    <div class="sidebar-logo">
        <div class="sidebar-logo-icon">
            <i class="bi bi-car-front-fill"></i>
        </div>
        <div class="sidebar-logo-text">
            Dealer System
            <small>Panel de gestión</small>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">

        <p class="nav-section-label">Principal</p>
        <ul class="list-unstyled">
            <li class="nav-item">
                <a href="index.php?controller=dashboard&action=index"
                   class="nav-link <?= (isset($activeNav) && $activeNav === 'dashboard') ? 'active' : '' ?>">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>
        </ul>

        <p class="nav-section-label">Inventario</p>
        <ul class="list-unstyled">
            <li class="nav-item">
                <a href="index.php?controller=vehiculo&action=index"
                   class="nav-link <?= (isset($activeNav) && $activeNav === 'vehiculos') ? 'active' : '' ?>">
                    <i class="bi bi-car-front"></i>
                    <span>Vehículos</span>
                </a>
            </li>
        </ul>

        <p class="nav-section-label">Gestión</p>
        <ul class="list-unstyled">
            <li class="nav-item">
                <a href="index.php?controller=cliente&action=index"
                   class="nav-link <?= (isset($activeNav) && $activeNav === 'clientes') ? 'active' : '' ?>">
                    <i class="bi bi-people-fill"></i>
                    <span>Clientes</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="index.php?controller=venta&action=index"
                   class="nav-link <?= (isset($activeNav) && $activeNav === 'ventas') ? 'active' : '' ?>">
                    <i class="bi bi-receipt"></i>
                    <span>Ventas</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="index.php?controller=reporte&action=index"
                   class="nav-link <?= (isset($activeNav) && $activeNav === 'reportes') ? 'active' : '' ?>">
                    <i class="bi bi-file-earmark-bar-graph-fill"></i>
                    <span>Reportes</span>
                </a>
            </li>
        </ul>

    </nav>

    <!-- User footer -->
    <div class="sidebar-foot">
        <div class="sidebar-user">
            <div class="avatar">
                <?= strtoupper(substr($_SESSION['usuario'] ?? 'A', 0, 1)) ?>
            </div>
            <div class="user-info">
                <p class="user-name"><?= htmlspecialchars($_SESSION['usuario'] ?? 'Administrador') ?></p>
                <p class="user-role">Administrador</p>
            </div>
        </div>
    </div>

</aside>

<!-- ════════════════════════════════════
     MAIN WRAPPER — abre aquí
     ════════════════════════════════════ -->
<div id="main-wrapper">

    <!-- TOPBAR -->
    <header id="topbar">

        <!-- Mobile toggle -->
        <button class="topbar-toggle" id="sidebarToggle" aria-label="Menú">
            <i class="bi bi-list"></i>
        </button>

        <p class="topbar-title">
            <i class="bi bi-car-front-fill me-2" style="color:var(--gold);font-size:.9rem;"></i>
            Dealer System
        </p>

        <div class="topbar-right">
            <span class="topbar-time">
                <i class="bi bi-clock"></i>
                <span id="clockDisplay"></span>
            </span>
            <div class="topbar-divider"></div>
            <a href="index.php?controller=auth&action=logout" class="btn-logout">
                <i class="bi bi-box-arrow-right"></i>
                <span>Cerrar sesión</span>
            </a>
        </div>

    </header>

    <!-- PAGE CONTENT opens -->
    <main id="page-content">