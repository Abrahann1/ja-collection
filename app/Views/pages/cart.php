<div class="container" style="padding-top: 2.5rem;">
    <div style="border-bottom: 1px solid var(--border-glass); padding-bottom: 1.25rem; margin-bottom: 2.5rem;">
        <span style="font-size: 0.72rem; letter-spacing: 2px; text-transform: uppercase; color: var(--accent-gold); font-weight: 700;">Revisión de Colección</span>
        <h1 style="font-family: var(--font-luxury); font-size: 2.2rem; letter-spacing: 2px; text-transform: uppercase; margin-top: 0.25rem;">Bolsa de Compras</h1>
    </div>

    <!-- ESTADO VACÍO -->
    <div id="cart-empty-state" style="display: none; text-align: center; padding: 5rem 2rem; background: var(--bg-card); border: 1px solid var(--border-glass); border-radius: var(--radius-card); margin-bottom: 5rem;">
        <span style="font-size: 3rem; color: var(--accent-gold); display: block; margin-bottom: 1rem;">🛒</span>
        <h2 style="font-family: var(--font-luxury); font-size: 1.5rem; text-transform: uppercase; margin-bottom: 0.5rem;">Tu bolsa está vacía</h2>
        <p style="color: var(--text-secondary); max-width: 450px; margin: 0 auto 2rem; font-size: 0.95rem;">
            Aún no has agregado modelos a tu colección. Explora nuestro catálogo de ediciones especiales y piezas exclusivas.
        </p>
        <a href="/shop" style="background: var(--accent-gold); color: #090B0E; padding: 0.85rem 2rem; font-size: 0.82rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; border-radius: var(--radius-pill); display: inline-block;">
            Explorar Catálogo
        </a>
    </div>

    <!-- GRID DE 2 COLUMNAS (TABLA + RESUMEN) -->
    <div id="cart-content-grid" class="cart-layout">
        <!-- Columna Izquierda: Tabla de Modelos -->
        <div class="cart-table-panel">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Modelo Diecast</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th style="text-align: right;"></th>
                    </tr>
                </thead>
                <tbody id="cart-items-tbody">
                    <!-- Se inyecta dinámicamente -->
                </tbody>
            </table>
        </div>

        <!-- Columna Derecha: Resumen de Compra -->
        <aside class="cart-summary-card">
            <h2 style="font-family: var(--font-luxury); font-size: 1.15rem; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: var(--text-primary); padding-bottom: 0.75rem; border-bottom: 1px solid var(--border-glass); margin-bottom: 1.25rem;">
                Resumen de Compra
            </h2>
            
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem; font-size: 0.9rem; color: var(--text-secondary);">
                <span>Subtotal Modelos:</span>
                <strong id="cart-subtotal-val" style="color: var(--text-primary); font-size: 1rem;">S/ 0.00</strong>
            </div>

            <div style="background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.85rem 1rem; border-radius: var(--radius-btn); margin: 1.25rem 0; font-size: 0.8rem; color: var(--text-muted); line-height: 1.5;">
                📍 <strong>Envío a todo el Perú:</strong> El flete exacto se calculará automáticamente en el siguiente paso según tu ciudad (*Cusco, Lima, etc.*).
            </div>

            <div style="display: flex; justify-content: space-between; align-items: baseline; border-top: 1px solid var(--border-glass); padding-top: 1.25rem; font-size: 1.05rem; font-weight: 700;">
                <span>Total Estimado:</span>
                <span class="amount" id="cart-total-val" style="font-family: var(--font-luxury); font-size: 1.85rem; color: var(--accent-gold);">S/ 0.00</span>
            </div>

            <button type="button" class="btn-checkout-luxury" onclick="proceedToCheckout()">
                CONTINUAR AL CHECKOUT &rarr;
            </button>

            <div style="margin-top: 1.5rem; text-align: center; font-size: 0.78rem; color: var(--text-muted); line-height: 1.5;">
                🛡️ Empaque blindado en caja reforzada incluido en todos los despachos.
            </div>
        </aside>
    </div>
</div>