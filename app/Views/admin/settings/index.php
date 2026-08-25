<div style="max-width: 850px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; border-bottom: 1px solid var(--border-glass); padding-bottom: 1.25rem;">
        <div>
            <span style="font-size: 0.72rem; letter-spacing: 2px; text-transform: uppercase; color: var(--accent-gold); font-weight: 700;">Parámetros del Sistema</span>
            <h1 style="font-family: var(--font-luxury); font-size: 1.85rem; letter-spacing: 2px; text-transform: uppercase; margin-top: 0.25rem;">Configuración de la Boutique</h1>
        </div>
    </div>

    <?php if (!empty($success)): ?>
        <div style="background: rgba(46, 125, 50, 0.15); border: 1px solid rgba(76, 175, 80, 0.4); color: #81C784; padding: 1rem 1.5rem; border-radius: var(--radius-btn); margin-bottom: 2rem; font-size: 0.88rem;">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="/admin/settings" style="display: flex; flex-direction: column; gap: 2rem;">
        <!-- Tarifas de Envío en Perú -->
        <section class="glass-card" style="padding: 2rem;">
            <h3 style="font-family: var(--font-luxury); font-size: 1.1rem; color: var(--accent-gold); text-transform: uppercase; margin-bottom: 1.25rem; border-bottom: 1px solid var(--border-glass); padding-bottom: 0.6rem;">
                1. Tarifas de Envío (Perú)
            </h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div>
                    <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 0.4rem; font-weight: 600;">Flete Lima / Callao (S/) *</label>
                    <input type="number" step="0.50" name="shipping_lima" value="<?= htmlspecialchars($settings['shipping_lima']) ?>" required style="width: 100%; background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.75rem 1rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.95rem; font-weight: 700;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 0.4rem; font-weight: 600;">Flete Provincias - Cusco, etc. (S/) *</label>
                    <input type="number" step="0.50" name="shipping_provincia" value="<?= htmlspecialchars($settings['shipping_provincia']) ?>" required style="width: 100%; background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.75rem 1rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.95rem; font-weight: 700;">
                </div>
            </div>
        </section>

        <!-- Cuentas de Pago para Clientes -->
        <section class="glass-card" style="padding: 2rem;">
            <h3 style="font-family: var(--font-luxury); font-size: 1.1rem; color: var(--accent-gold); text-transform: uppercase; margin-bottom: 1.25rem; border-bottom: 1px solid var(--border-glass); padding-bottom: 0.6rem;">
                2. Datos de Pago Mostrados en Checkout
            </h3>
            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                <div>
                    <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 0.4rem; font-weight: 600;">Número Yape / Plin *</label>
                    <input type="text" name="yape_phone" value="<?= htmlspecialchars($settings['yape_phone']) ?>" required style="width: 100%; background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.75rem 1rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.9rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 0.4rem; font-weight: 600;">Cuenta Corriente Bancaria (BCP / Titular) *</label>
                    <input type="text" name="bcp_account" value="<?= htmlspecialchars($settings['bcp_account']) ?>" required style="width: 100%; background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.75rem 1rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.9rem;">
                </div>
            </div>
        </section>

        <!-- Contacto & Soporte -->
        <section class="glass-card" style="padding: 2rem;">
            <h3 style="font-family: var(--font-luxury); font-size: 1.1rem; color: var(--accent-gold); text-transform: uppercase; margin-bottom: 1.25rem; border-bottom: 1px solid var(--border-glass); padding-bottom: 0.6rem;">
                3. Canales de Soporte
            </h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div>
                    <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 0.4rem; font-weight: 600;">WhatsApp Oficial</label>
                    <input type="text" name="contact_whatsapp" value="<?= htmlspecialchars($settings['contact_whatsapp']) ?>" style="width: 100%; background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.75rem 1rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.9rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 0.4rem; font-weight: 600;">Email de Soporte</label>
                    <input type="email" name="contact_email" value="<?= htmlspecialchars($settings['contact_email']) ?>" style="width: 100%; background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.75rem 1rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.9rem;">
                </div>
            </div>
        </section>

        <button type="submit" style="background: var(--accent-gold); color: #090B0E; border: none; padding: 1rem 2.5rem; font-size: 0.85rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; border-radius: var(--radius-pill); cursor: pointer; align-self: flex-end; box-shadow: 0 4px 15px var(--accent-glow);">
            Guardar y Aplicar Ajustes &rarr;
        </button>
    </form>
</div>