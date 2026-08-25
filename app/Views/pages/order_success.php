<div class="container" style="padding: 4.5rem 0 6rem; text-align: center; max-width: 760px;">
    <div class="glass-card" style="padding: 3.5rem 2.5rem;">
        <span style="font-size: 3rem; color: var(--accent-gold); display: block; margin-bottom: 0.75rem;">✦</span>
        <span style="font-size: 0.75rem; letter-spacing: 2.5px; text-transform: uppercase; color: var(--accent-gold); font-weight: 700;">¡Gracias por tu compra!</span>
        <h1 style="font-family: var(--font-luxury); font-size: 2.3rem; letter-spacing: 2px; text-transform: uppercase; margin: 0.5rem 0 1rem; color: var(--text-primary);">
            Pedido Registrado
        </h1>
        <p style="font-family: monospace; font-size: 1.25rem; font-weight: 700; color: var(--accent-gold); background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.5rem 1.5rem; display: inline-block; border-radius: var(--radius-pill); margin-bottom: 2rem;">
            <?= htmlspecialchars($order['order_number']) ?>
        </p>

        <p style="color: var(--text-secondary); line-height: 1.7; font-size: 0.95rem; margin-bottom: 2.5rem;">
            Hemos reservado tus modelos diecast en nuestro almacén. Hemos enviado el detalle de tu orden a <strong><?= htmlspecialchars($order['customer_email']) ?></strong>.
        </p>

        <!-- INSTRUCCIONES DE PAGO DINÁMICAS (DESDE LA BASE DE DATOS) -->
        <div style="background: var(--bg-secondary); border: 1px solid var(--border-glass); border-radius: var(--radius-card); padding: 2rem; text-align: left; margin-bottom: 2.5rem;">
            <h3 style="font-family: var(--font-luxury); font-size: 1.05rem; color: var(--accent-gold); text-transform: uppercase; margin-bottom: 1.25rem; letter-spacing: 1px; border-bottom: 1px solid var(--border-glass); padding-bottom: 0.6rem;">
                Instrucciones para Completar el Pago
            </h3>

            <?php if ($order['payment_method'] === 'YAPE_PLIN'): ?>
                <div style="display: flex; justify-content: space-between; align-items: center; background: var(--bg-card); padding: 1.25rem; border-radius: var(--radius-btn); border: 1px solid var(--border-accent); margin-bottom: 1rem; flex-wrap: wrap; gap: 1rem;">
                    <div>
                        <span style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1.5px; color: var(--text-muted); display: block; font-weight: 600;">Pago Móvil Yape / Plin:</span>
                        <strong style="font-size: 1.4rem; color: var(--accent-gold); font-family: monospace;" id="yape-number-text">
                            <?= htmlspecialchars($settings['yape_phone']) ?>
                        </strong>
                        <span style="font-size: 0.8rem; color: var(--text-secondary); display: block; margin-top: 0.2rem;">Titular: J.A COLLECTION</span>
                    </div>
                    <button type="button" onclick="copyYapeNumber()" style="background: var(--accent-gold); color: #090B0E; border: none; padding: 0.6rem 1.2rem; font-size: 0.78rem; font-weight: 700; border-radius: var(--radius-pill); cursor: pointer; text-transform: uppercase; letter-spacing: 1px;">
                        📋 Copiar Número
                    </button>
                </div>
            <?php else: ?>
                <div style="background: var(--bg-card); padding: 1.25rem; border-radius: var(--radius-btn); border: 1px solid var(--border-glass); margin-bottom: 1rem;">
                    <span style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1.5px; color: var(--text-muted); display: block; font-weight: 600;">Cuenta Corriente Bancaria:</span>
                    <strong style="font-size: 1.1rem; color: var(--text-primary); display: block; margin-top: 0.3rem;">
                        <?= htmlspecialchars($settings['bcp_account']) ?>
                    </strong>
                </div>
            <?php endif; ?>

            <div style="font-size: 0.88rem; color: var(--text-secondary); line-height: 1.6;">
                <p>&bull; <strong>Monto Exacto a Transferir:</strong> <strong style="color: var(--text-primary); font-size: 1rem;">S/ <?= number_format((float)$order['total'], 2) ?></strong></p>
                <p style="margin-top: 0.35rem;">&bull; <strong>Referencia:</strong> Incluye el número <strong><?= htmlspecialchars($order['order_number']) ?></strong> en el mensaje de tu transferencia.</p>
            </div>
        </div>

        <a href="/shop" style="background: var(--accent-gold); color: #090B0E; padding: 0.9rem 2.5rem; font-size: 0.85rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; border-radius: var(--radius-pill); text-decoration: none; display: inline-block; box-shadow: 0 4px 15px var(--accent-glow);">
            Volver a la Tienda
        </a>
    </div>
</div>

<script>
function copyYapeNumber() {
    const text = document.getElementById("yape-number-text").textContent.trim();
    navigator.clipboard.writeText(text).then(() => {
        if (typeof Toast !== "undefined") {
            Toast.show("¡Número de Yape copiado al portapapeles!");
        } else {
            alert("Número copiado: " + text);
        }
    });
}
</script>