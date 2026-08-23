<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Acceso Administrativo | J.A COLLECTION') ?></title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
        body { background-color: #0A0A0A; color: #F5F5F0; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 1.5rem; }
        .login-card { background-color: #121212; border: 1px solid #292929; width: 100%; max-width: 420px; padding: 3rem 2.5rem; text-align: center; box-shadow: 0 20px 40px rgba(0,0,0,0.8); }
        .badge { display: inline-block; padding: 0.25rem 0.75rem; border: 1px solid #8B1E24; color: #F5F5F0; font-size: 0.7rem; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 1.25rem; }
        .brand-title { font-size: 1.4rem; font-weight: 700; letter-spacing: 3px; text-transform: uppercase; margin-bottom: 0.5rem; }
        .brand-title span { color: #8B1E24; }
        .brand-subtitle { font-size: 0.8rem; color: #8A8A8A; letter-spacing: 1px; margin-bottom: 2rem; }
        .form-group { text-align: left; margin-bottom: 1.25rem; }
        .form-label { display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: #A0A0A0; margin-bottom: 0.4rem; font-weight: 600; }
        .form-input { width: 100%; background-color: #1A1A1A; border: 1px solid #333333; color: #FFFFFF; padding: 0.85rem 1rem; font-size: 0.95rem; border-radius: 0; outline: none; transition: border-color 0.2s; }
        .form-input:focus { border-color: #8B1E24; box-shadow: 0 0 0 1px #8B1E24; }
        .btn-submit { width: 100%; background-color: #8B1E24; color: #FFFFFF; border: none; padding: 0.9rem; font-size: 0.85rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; cursor: pointer; margin-top: 0.75rem; transition: background-color 0.2s; }
        .btn-submit:hover { background-color: #A5242B; }
        .alert-error { background-color: rgba(139, 30, 36, 0.25); border: 1px solid #8B1E24; color: #FFB4B6; padding: 0.75rem 1rem; font-size: 0.85rem; margin-bottom: 1.5rem; text-align: left; }
    </style>
</head>
<body>
    <article class="login-card">
        <span class="badge">Área Restringida</span>
        <h1 class="brand-title">J.A <span>ADMIN</span></h1>
        <p class="brand-subtitle">Plataforma de Control & Coleccionismo</p>

        <?php if (!empty($error)): ?>
            <div class="alert-error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/admin/login">
            <input type="hidden" name="is_admin" value="1">
            <div class="form-group">
                <label class="form-label" for="email">Usuario Administrador</label>
                <input class="form-input" type="email" id="email" name="email" value="<?= htmlspecialchars($email ?? 'admin@jacollection.com') ?>" required autofocus>
            </div>
            <div class="form-group">
                <label class="form-label" for="password">Contraseña</label>
                <input class="form-input" type="password" id="password" name="password" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn-submit">Ingresar al Panel</button>
        </form>
    </article>
</body>
</html>