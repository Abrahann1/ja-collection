<div class="container" style="padding: 5rem 0; text-align: center; max-width: 750px;">
    <div class="glass-card" style="padding: 3.5rem 2.5rem;">
        <span style="font-size: 3.5rem; color: var(--accent-gold); display: block; margin-bottom: 1rem;">✦</span>
        <span style="font-size: 0.75rem; letter-spacing: 2.5px; text-transform: uppercase; color: var(--accent-gold); font-weight: 700;">¡Gracias por tu compra!</span>
        <h1 style="font-family: var(--font-luxury); font-size: 2.4rem; letter-spacing: 2px; text-transform: uppercase; margin: 0.5rem 0 1rem; color: var(--text-primary);">
            Pedido Registrado
        </h1>
        <p style="font-family: monospace; font-size: 1.25rem; font-weight: 700; color: var(--accent-gold); background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.5rem 1.5rem; display: inline-block; border-radius: var(--radius-pill); margin-bottom: 2rem;">
            <?= htmlspecialchars($order['order_number']) ?>
        </p>

        <p style="color: var(--text-secondary); line-height: 1.7; font-size: 0.95rem; margin-bottom: 2.5rem;">
            Hemos reservado tus modelos diecast en nuestro almacén. Hemos enviado la confirmación y el detalle de tu orden a <strong><?= htmlspecialchars($order['customer_email']) ?></strong>.
        </p>

        <!-- Instrucciones de Pago Yape / Transferencia -->
        <div style="background: var(--bg-secondary); border: 1px solid var(--border-glass); border-radius: var(--radius-card); padding: 1.75rem; text-align: left; margin-bottom: 2.5rem;">
            <h3 style="font-family: var(--font-luxury); font-size: 1rem; color: var(--accent-gold); text-transform: uppercase; margin-bottom: 0.75rem;">
                Instrucciones para Completar el Pago
            </h3>
            <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem;">
                &bull; <strong>Yape / Plin:</strong> Realiza el pago de <strong>S/ <?= number_format((float)$order['total'], 2) ?></strong> al número <strong>900 000 000</strong> (J.A COLLECTION).
            </p>
            <p style="font-size: 0.85rem; color: var(--text-secondary);">
                &bull; <strong>Referencia:</strong> Incluye el número <strong><?= htmlspecialchars($order['order_number']) ?></strong> en el mensaje de tu transferencia.
            </p>
        </div>

        <a href="/shop" style="background: var(--accent-gold); color: #090B0E; padding: 0.9rem 2.5rem; font-size: 0.85rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; border-radius: var(--radius-pill); text-decoration: none; display: inline-block; box-shadow: 0 4px 15px var(--accent-glow);">
            Volver a la Tienda
        </a>
    </div>
</div>