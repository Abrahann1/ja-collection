<section class="container" style="padding: 5rem 0; max-width: 450px;">
    <div style="background-color: var(--color-bg-card); border: 1px solid var(--color-border); padding: 2.5rem;">
        <span class="badge badge-scale" style="margin-bottom: 1rem;">Mi Cuenta</span>
        <h1 style="font-size: 1.5rem; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 1.5rem;">Iniciar Sesión</h1>

        <?php if (!empty($error)): ?>
            <div style="background: rgba(139,30,36,0.2); border: 1px solid var(--color-accent); color: var(--color-text-primary); padding: 0.75rem; margin-bottom: 1.5rem; font-size: 0.85rem;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/login" style="display: flex; flex-direction: column; gap: 1.25rem;">
            <div>
                <label style="display: block; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.4rem; color: var(--color-text-muted);">Correo Electrónico</label>
                <input type="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required style="width: 100%; background: var(--color-bg-secondary); border: 1px solid var(--color-border); padding: 0.75rem; color: var(--color-text-primary);">
            </div>
            <div>
                <label style="display: block; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.4rem; color: var(--color-text-muted);">Contraseña</label>
                <input type="password" name="password" required style="width: 100%; background: var(--color-bg-secondary); border: 1px solid var(--color-border); padding: 0.75rem; color: var(--color-text-primary);">
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 0.5rem;">Acceder</button>
        </form>

        <div style="margin-top: 1.5rem; text-align: center; border-top: 1px solid var(--color-border); padding-top: 1rem; font-size: 0.85rem; color: var(--color-text-muted);">
            ¿No tienes cuenta? <a href="/register" style="color: var(--color-text-primary); text-decoration: underline;">Regístrate</a>
        </div>
    </div>
</section>