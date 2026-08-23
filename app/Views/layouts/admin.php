<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Panel Administrativo | J.A COLLECTION') ?></title>
    <link rel="stylesheet" href="/assets/css/variables.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
    <script src="/assets/js/theme.js"></script>
</head>
<body class="admin-body">
    <aside class="sidebar">
        <div class="sidebar-brand">J.A <span>COLLECTION</span></div>
        <nav style="flex: 1; display: flex; flex-direction: column;">
            <ul class="sidebar-menu">
                <li><a href="/admin/dashboard" class="sidebar-link active">Dashboard</a></li>
                <li><a href="/admin/products" class="sidebar-link">Productos</a></li>
                <li><a href="/admin/inventory" class="sidebar-link">Inventario</a></li>
                <li><a href="/admin/orders" class="sidebar-link">Pedidos</a></li>
                <li><a href="/admin/import" class="sidebar-link">Importar Excel</a></li>
            </ul>
            <ul class="sidebar-menu" style="margin-top: auto;">
                <li><a href="/" target="_blank" class="sidebar-link">Ver Tienda &nearr;</a></li>
                <li><a href="/admin/logout" class="sidebar-link logout">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </aside>

    <main class="admin-main-content">
        <header class="admin-header">
            <div>
                <span style="display: inline-block; font-size: 0.68rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: var(--accent-gold); margin-bottom: 0.35rem;">
                    &bull; Control de Boutique Automotriz
                </span>
                <h1>Panel de Control</h1>
            </div>
            <div style="display: flex; align-items: center; gap: 1.25rem;">
                <!-- Switch Vectorial Morphing -->
                <button class="theme-switch is-dark" onclick="toggleTheme()" aria-label="Cambiar tema" title="Cambiar tema">
                    <span class="switch-thumb">
                        <!-- SVG Sol (Modo Claro) -->
                        <svg class="icon-sun" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="4"></circle>
                            <path d="M12 2v2"></path>
                            <path d="M12 20v2"></path>
                            <path d="m4.93 4.93 1.41 1.41"></path>
                            <path d="m17.66 17.66 1.41 1.41"></path>
                            <path d="M2 12h2"></path>
                            <path d="M20 12h2"></path>
                            <path d="m6.34 17.66-1.41 1.41"></path>
                            <path d="m19.07 4.93-1.41 1.41"></path>
                        </svg>
                        <!-- SVG Luna (Modo Oscuro) -->
                        <svg class="icon-moon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"></path>
                        </svg>
                    </span>
                </button>

                <div class="admin-user-pill">
                    <div class="avatar">J</div>
                    <div>
                        <strong style="color: var(--text-primary); display: block; font-size: 0.85rem;">Josuee Abrahan</strong>
                        <span style="font-size: 0.72rem; color: var(--text-muted);">Administrador</span>
                    </div>
                </div>
            </div>
        </header>

        <?= $content ?>
    </main>
</body>
</html>