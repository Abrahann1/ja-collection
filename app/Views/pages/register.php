<div class="container" style="padding: 3.5rem 0 5rem; max-width: 480px;">
    <div class="glass-card" style="padding: 2.75rem 2.25rem;">
        <span style="font-size: 0.72rem; letter-spacing: 2px; text-transform: uppercase; color: var(--accent-gold); font-weight: 700; display: block; margin-bottom: 0.3rem;">
            Membresía Boutique
        </span>
        <h1 style="font-family: var(--font-luxury); font-size: 1.85rem; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 1.75rem;">
            Crear Cuenta
        </h1>

        <?php if (!empty($error)): ?>
            <div style="background: rgba(139,30,36,0.25); border: 1px solid #8B1E24; color: #FFB4B6; padding: 0.85rem 1rem; border-radius: var(--radius-btn); margin-bottom: 1.5rem; font-size: 0.85rem;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/register" style="display: flex; flex-direction: column; gap: 1rem;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.72rem; text-transform: uppercase; margin-bottom: 0.3rem; color: var(--text-muted); font-weight: 600;">Nombre *</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($data['name'] ?? '') ?>" required placeholder="Carlos" style="width: 100%; background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.65rem 0.85rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.88rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.72rem; text-transform: uppercase; margin-bottom: 0.3rem; color: var(--text-muted); font-weight: 600;">Apellido *</label>
                    <input type="text" name="lastname" value="<?= htmlspecialchars($data['lastname'] ?? '') ?>" required placeholder="Mendoza" style="width: 100%; background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.65rem 0.85rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.88rem;">
                </div>
            </div>

            <div>
                <label style="display: block; font-size: 0.72rem; text-transform: uppercase; margin-bottom: 0.3rem; color: var(--text-muted); font-weight: 600;">Correo Electrónico *</label>
                <input type="email" name="email" value="<?= htmlspecialchars($data['email'] ?? '') ?>" required placeholder="carlos@gmail.com" style="width: 100%; background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.65rem 0.85rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.88rem;">
            </div>

            <div>
                <label style="display: block; font-size: 0.72rem; text-transform: uppercase; margin-bottom: 0.3rem; color: var(--text-muted); font-weight: 600;">Teléfono / WhatsApp</label>
                <input type="tel" name="phone" value="<?= htmlspecialchars($data['phone'] ?? '') ?>" placeholder="+51 987 654 321" style="width: 100%; background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.65rem 0.85rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.88rem;">
            </div>

            <div>
                <label style="display: block; font-size: 0.72rem; text-transform: uppercase; margin-bottom: 0.3rem; color: var(--text-muted); font-weight: 600;">Contraseña (Mínimo 8 caracteres) *</label>
                <input type="password" name="password" required minlength="8" placeholder="••••••••" style="width: 100%; background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.65rem 0.85rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.88rem;">
            </div>

            <button type="submit" style="width: 100%; background: var(--accent-gold); color: #090B0E; border: none; padding: 0.9rem; font-size: 0.85rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; border-radius: var(--radius-pill); cursor: pointer; margin-top: 0.5rem; box-shadow: 0 4px 15px var(--accent-glow);">
                Registrarse &rarr;
            </button>
        </form>

        <div style="margin-top: 2rem; text-align: center; border-top: 1px solid var(--border-glass); padding-top: 1.25rem; font-size: 0.85rem; color: var(--text-secondary);">
            ¿Ya tienes cuenta? <a href="/login" style="color: var(--accent-gold); font-weight: 700;">Inicia sesión</a>
        </div>
    </div>
</div>