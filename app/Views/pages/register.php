<section class="container" style="padding: 4rem 0; max-width: 500px;">
    <div style="background-color: var(--color-bg-card); border: 1px solid var(--color-border); padding: 2.5rem;">
        <span class="badge badge-scale" style="margin-bottom: 1rem;">Membresía</span>
        <h1 style="font-size: 1.5rem; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 1.5rem;">Crear Cuenta</h1>

        <?php if (!empty($error)): ?>
            <div style="background: rgba(139,30,36,0.2); border: 1px solid var(--color-accent); color: var(--color-text-primary); padding: 0.75rem; margin-bottom: 1.5rem; font-size: 0.85rem;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/register" style="display: flex; flex-direction: column; gap: 1rem;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.75rem; text-transform: uppercase; margin-bottom: 0.3rem; color: var(--color-text-muted);">Nombre</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($data['name'] ?? '') ?>" required style="width: 100%; background: var(--color-bg-secondary); border: 1px solid var(--color-border); padding: 0.65rem; color: var(--color-text-primary);">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; text-transform: uppercase; margin-bottom: 0.3rem; color: var(--color-text-muted);">Apellido</label>
                    <input type="text" name="lastname" value="<?= htmlspecialchars($data['lastname'] ?? '') ?>" required style="width: 100%; background: var(--color-bg-secondary); border: 1px solid var(--color-border); padding: 0.65rem; color: var(--color-text-primary);">
                </div>
            </div>
            <div>
                <label style="display: block; font-size: 0.75rem; text-transform: uppercase; margin-bottom: 0.3rem; color: var(--color-text-muted);">Correo Electrónico</label>
                <input type="email" name="email" value="<?= htmlspecialchars($data['email'] ?? '') ?>" required style="width: 100%; background: var(--color-bg-secondary); border: 1px solid var(--color-border); padding: 0.65rem; color: var(--color-text-primary);">
            </div>
            <div>
                <label style="display: block; font-size: 0.75rem; text-transform: uppercase; margin-bottom: 0.3rem; color: var(--color-text-muted);">Teléfono</label>
                <input type="tel" name="phone" value="<?= htmlspecialchars($data['phone'] ?? '') ?>" style="width: 100%; background: var(--color-bg-secondary); border: 1px solid var(--color-border); padding: 0.65rem; color: var(--color-text-primary);">
            </div>
            <div>
                <label style="display: block; font-size: 0.75rem; text-transform: uppercase; margin-bottom: 0.3rem; color: var(--color-text-muted);">Contraseña (Mínimo 8 caracteres)</label>
                <input type="password" name="password" required minlength="8" style="width: 100%; background: var(--color-bg-secondary); border: 1px solid var(--color-border); padding: 0.65rem; color: var(--color-text-primary);">
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 0.5rem;">Registrarse</button>
        </form>

        <div style="margin-top: 1.5rem; text-align: center; border-top: 1px solid var(--color-border); padding-top: 1rem; font-size: 0.85rem; color: var(--color-text-muted);">
            ¿Ya tienes cuenta? <a href="/login" style="color: var(--color-text-primary); text-decoration: underline;">Inicia sesión</a>
        </div>
    </div>
</section>