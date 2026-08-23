<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'J.A COLLECTION | Premium Automotive Diecast') ?></title>
    <link rel="stylesheet" href="/assets/css/variables.css">
    <link rel="stylesheet" href="/assets/css/base.css">
    <link rel="stylesheet" href="/assets/css/layout.css">
    <link rel="stylesheet" href="/assets/css/components.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
    <?php if (isset($extraCss)): ?>
        <link rel="stylesheet" href="/assets/css/<?= htmlspecialchars($extraCss) ?>.css">
    <?php endif; ?>
    <script src="/assets/js/theme.js"></script>
    <script src="/assets/js/toast.js"></script>
    <style>
        body { background-color: var(--bg-primary); color: var(--text-primary); transition: background-color 0.35s, color 0.35s; }
        .header-main { background: var(--bg-secondary); border-bottom: 1px solid var(--border-glass); }
        .logo-brand { font-family: var(--font-luxury); letter-spacing: 3px; color: var(--text-primary); text-decoration: none; font-weight: 700; }
        .logo-brand span { color: var(--accent-gold); }
    </style>
</head>
<body>
    <header class="header-main">
        <div class="container header-container">
            <a href="/" class="logo-brand">J.A <span>COLLECTION</span></a>
            <nav class="nav-desktop" aria-label="Navegación principal">
                <a href="/" class="nav-link">Inicio</a>
                <a href="/shop" class="nav-link">Catálogo</a>
                <a href="/shop?cat=jdm-specials" class="nav-link">JDM</a>
                <a href="/shop?cat=supercars" class="nav-link">Supercars</a>
            </nav>
            <div class="nav-actions" style="display: flex; align-items: center; gap: 1rem;">
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
                <a href="/cart" class="btn btn-outline" style="border-radius: var(--radius-pill); font-size: 0.8rem; padding: 0.5rem 1rem;">
                    Bolsa (<span id="cart-count-badge">0</span>)
                </a>
                <a href="/admin/dashboard" class="btn btn-outline" style="font-size: 0.75rem; padding: 0.5rem 0.9rem; border-radius: var(--radius-pill);">Admin</a>
            </div>
        </div>
    </header>

    <main id="main-content">
        <?= $content ?>
    </main>

    <footer class="footer-main" style="background: var(--bg-secondary); border-top: 1px solid var(--border-glass); padding: 3.5rem 0; margin-top: 4rem;">
        <div class="container" style="display: flex; justify-content: space-between; align-items: center; color: var(--text-muted); font-size: 0.85rem;">
            <p>&copy; <?= date('Y') ?> J.A COLLECTION. Todos los derechos reservados.</p>
            <p style="font-family: var(--font-luxury); letter-spacing: 2px;">Fundado por Josuee Abrahan</p>
        </div>
    </footer>

    <script src="/assets/js/api.js"></script>
    <script src="/assets/js/cart.js"></script>
</body>
</html>