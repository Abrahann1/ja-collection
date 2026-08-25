<?php
use App\Core\Session;
$currentUser = Session::get('user');
?>
<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'J.A COLLECTION | Premium Automotive Diecast') ?></title>
    <link rel="stylesheet" href="/assets/css/variables.css">
    <link rel="stylesheet" href="/assets/css/luxury-store.css">
    <script src="/assets/js/theme.js"></script>
    <script src="/assets/js/toast.js"></script>
</head>
<body>
    <header class="header-main">
        <div class="container header-container">
            <a href="/" class="logo-brand">J.A <span>COLLECTION</span></a>
            
            <nav class="nav-desktop" aria-label="Navegación principal">
                <a href="/" class="nav-link">Inicio</a>
                <a href="/shop" class="nav-link">Catálogo</a>
                <a href="/shop?category=jdm-specials" class="nav-link">JDM Specials</a>
                <a href="/shop?category=supercars" class="nav-link">Supercars</a>
                <a href="/shop?category=premium" class="nav-link">Premium</a>
            </nav>

            <div class="nav-actions">
                <!-- Switch Deslizable Sol / Luna -->
                <button class="theme-switch is-dark" onclick="toggleTheme()" aria-label="Cambiar tema" title="Cambiar tema">
                    <span class="switch-thumb">
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
                        <svg class="icon-moon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"></path>
                        </svg>
                    </span>
                </button>

                <!-- Menú de Usuario / Botón Iniciar Sesión -->
                <?php if ($currentUser): ?>
                    <div class="user-dropdown-wrap">
                        <button type="button" class="user-pill-btn" onclick="toggleUserDropdown(event)">
                            <span class="user-avatar-circle"><?= strtoupper(substr($currentUser['name'], 0, 1)) ?></span>
                            <span><?= htmlspecialchars($currentUser['name']) ?></span>
                            <span style="font-size: 0.65rem; color: var(--accent-gold);">▼</span>
                        </button>
                        <div class="user-dropdown-menu" id="user-dropdown-panel">
                            <div style="padding: 0.85rem 1.2rem; border-bottom: 1px solid var(--border-glass);">
                                <strong style="color: var(--text-primary); display:block; font-size: 0.85rem;"><?= htmlspecialchars($currentUser['name'] . ' ' . $currentUser['lastname']) ?></strong>
                                <span style="font-size: 0.72rem; color: var(--text-muted);"><?= htmlspecialchars($currentUser['email']) ?></span>
                            </div>
                            <a href="/account" class="dropdown-item-link">📦 Mis Pedidos</a>
                            <a href="/account" class="dropdown-item-link">📍 Mis Datos de Envío</a>
                            <?php if (in_array($currentUser['role'] ?? '', ['ADMIN', 'STAFF'], true)): ?>
                                <a href="/admin/dashboard" class="dropdown-item-link" style="color: var(--accent-gold); font-weight: 700;">⚡ Panel Administrativo</a>
                            <?php endif; ?>
                            <a href="/logout" class="dropdown-item-link" style="color: #E57373; border-top: 1px solid var(--border-glass);">🚪 Cerrar Sesión</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="/login" class="btn-login-nav">
                        👤 Iniciar Sesión
                    </a>
                <?php endif; ?>

                <!-- Carrito de Compras -->
                <a href="/cart" class="btn-cart-nav">
                    🛒 Carrito (<span id="cart-count-badge">0</span>)
                </a>
            </div>
        </div>
    </header>

    <main id="main-content">
        <?= $content ?>
    </main>

    <footer style="background: var(--bg-secondary); border-top: 1px solid var(--border-glass); padding: 3.5rem 0 2.5rem; margin-top: 5rem;">
        <div class="container">
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-glass); padding-bottom: 2rem; margin-bottom: 2rem; flex-wrap: wrap; gap: 1.5rem;">
                <div>
                    <span class="logo-brand">J.A <span>COLLECTION</span></span>
                    <p style="color: var(--text-muted); font-size: 0.85rem; margin-top: 0.3rem;">
                        Curaduría y distribución exclusiva de modelos automotrices diecast a escala 1:64.
                    </p>
                </div>
                <div style="display: flex; gap: 2rem; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.5px;">
                    <a href="/shop" style="color: var(--text-secondary);">Catálogo</a>
                    <a href="/login" style="color: var(--text-secondary);">Mi Cuenta</a>
                    <a href="/admin/login" style="color: var(--text-muted); opacity: 0.5;" title="Acceso al Sistema">🔒 Acceso Privado</a>
                </div>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; color: var(--text-muted); font-size: 0.8rem;">
                <p>&copy; <?= date('Y') ?> J.A COLLECTION. Todos los derechos reservados.</p>
                <p style="font-family: var(--font-luxury); letter-spacing: 2px;">Fundado por Josuee Abrahan</p>
            </div>
        </div>
    </footer>

    <script src="/assets/js/api.js"></script>
    <script src="/assets/js/cart.js"></script>
    <script>
    function toggleUserDropdown(e) {
        e.stopPropagation();
        const panel = document.getElementById("user-dropdown-panel");
        if (panel) panel.classList.toggle("show");
    }

    document.addEventListener("click", () => {
        const panel = document.getElementById("user-dropdown-panel");
        if (panel) panel.classList.remove("show");
    });
    </script>
</body>
</html>