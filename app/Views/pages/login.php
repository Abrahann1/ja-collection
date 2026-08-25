<div class="container" style="padding: 4rem 0 6rem; max-width: 440px;">
    <div class="glass-card" style="padding: 2.75rem 2.25rem;">
        <span style="font-size: 0.72rem; letter-spacing: 2px; text-transform: uppercase; color: var(--accent-gold); font-weight: 700; display: block; margin-bottom: 0.3rem;">
            Área de Coleccionistas
        </span>
        <h1 style="font-family: var(--font-luxury); font-size: 1.85rem; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 1.75rem;">
            Iniciar Sesión
        </h1>

        <?php if (!empty($error)): ?>
            <div style="background: rgba(139,30,36,0.25); border: 1px solid #8B1E24; color: #FFB4B6; padding: 0.85rem 1rem; border-radius: var(--radius-btn); margin-bottom: 1.5rem; font-size: 0.85rem;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/login" style="display: flex; flex-direction: column; gap: 1.25rem;">
            <div>
                <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.35rem; color: var(--text-muted); font-weight: 600;">Correo Electrónico</label>
                <input type="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required placeholder="tu.correo@gmail.com" style="width: 100%; background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.75rem 1rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.9rem;">
            </div>

            <div>
                <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.35rem; color: var(--text-muted); font-weight: 600;">Contraseña</label>
                <input type="password" name="password" required placeholder="••••••••" style="width: 100%; background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.75rem 1rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.9rem;">
            </div>

            <button type="submit" style="width: 100%; background: var(--accent-gold); color: #090B0E; border: none; padding: 0.9rem; font-size: 0.85rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; border-radius: var(--radius-pill); cursor: pointer; margin-top: 0.5rem; box-shadow: 0 4px 15px var(--accent-glow);">
                Acceder a Mi Cuenta &rarr;
            </button>
        </form>

        <div style="margin-top: 2rem; text-align: center; border-top: 1px solid var(--border-glass); padding-top: 1.25rem; font-size: 0.85rem; color: var(--text-secondary);">
            ¿Aún no tienes cuenta? <a href="/register" style="color: var(--accent-gold); font-weight: 700;">Regístrate aquí</a>
        </div>
    </div>
</div>