<div style="max-width: 950px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; border-bottom: 1px solid var(--border-glass); padding-bottom: 1.25rem;">
        <div>
            <span style="font-size: 0.72rem; letter-spacing: 2px; text-transform: uppercase; color: var(--accent-gold); font-weight: 700;">Auditoría Contable</span>
            <h1 style="font-family: var(--font-luxury); font-size: 1.85rem; letter-spacing: 2px; text-transform: uppercase; margin-top: 0.25rem;">Exportación de Datos</h1>
        </div>
    </div>

    <section class="metrics-grid" style="margin-bottom: 2.5rem;">
        <article class="glass-card">
            <span class="metric-title">Valorización de Inventario</span>
            <div class="metric-value gold">S/ <?= number_format($inventoryValue, 2) ?></div>
            <p class="metric-trend">&bull; Capital activo en mercadería</p>
        </article>
        <article class="glass-card">
            <span class="metric-title">Unidades Físicas en Stock</span>
            <div class="metric-value"><?= number_format($totalUnits) ?> autos</div>
            <p class="metric-trend">&bull; Total de autos en almacén</p>
        </article>
    </section>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.75rem;">
        <!-- Reporte 1: Catálogo -->
        <div class="glass-card" style="padding: 2.25rem; display: flex; flex-direction: column;">
            <span style="font-size: 2rem; margin-bottom: 0.75rem;">🏎️</span>
            <h3 style="font-family: var(--font-luxury); font-size: 1.15rem; text-transform: uppercase; color: var(--text-primary); margin-bottom: 0.5rem;">
                Catálogo de Productos
            </h3>
            <p style="color: var(--text-secondary); font-size: 0.85rem; line-height: 1.6; margin-bottom: 1.75rem; flex: 1;">
                Exporta la lista de todos los modelos con SKU, marca, categoría, escala, precios y estado.
            </p>
            <a href="/admin/export/products" style="background: var(--bg-secondary); border: 1px solid var(--border-accent); color: var(--accent-gold); padding: 0.75rem 1.25rem; font-size: 0.78rem; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; border-radius: var(--radius-pill); text-align: center; text-decoration: none;">
                Descargar Excel (.csv) &rarr;
            </a>
        </div>

        <!-- Reporte 2: Inventario & Kardex -->
        <div class="glass-card" style="padding: 2.25rem; display: flex; flex-direction: column;">
            <span style="font-size: 2rem; margin-bottom: 0.75rem;">📊</span>
            <h3 style="font-family: var(--font-luxury); font-size: 1.15rem; text-transform: uppercase; color: var(--text-primary); margin-bottom: 0.5rem;">
                Inventario & Kardex
            </h3>
            <p style="color: var(--text-secondary); font-size: 0.85rem; line-height: 1.6; margin-bottom: 1.75rem; flex: 1;">
                Reporte de existencias físicas, stock disponible y valorización monetaria total por producto.
            </p>
            <a href="/admin/export/inventory" style="background: var(--bg-secondary); border: 1px solid var(--border-accent); color: var(--accent-gold); padding: 0.75rem 1.25rem; font-size: 0.78rem; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; border-radius: var(--radius-pill); text-align: center; text-decoration: none;">
                Descargar Excel (.csv) &rarr;
            </a>
        </div>

        <!-- Reporte 3: Ventas & Pedidos -->
        <div class="glass-card" style="padding: 2.25rem; display: flex; flex-direction: column;">
            <span style="font-size: 2rem; margin-bottom: 0.75rem;">💰</span>
            <h3 style="font-family: var(--font-luxury); font-size: 1.15rem; text-transform: uppercase; color: var(--text-primary); margin-bottom: 0.5rem;">
                Ventas & Pedidos
            </h3>
            <p style="color: var(--text-secondary); font-size: 0.85rem; line-height: 1.6; margin-bottom: 1.75rem; flex: 1;">
                Historial de transacciones de clientes, destinos de envío, métodos de pago y totales recaudados.
            </p>
            <a href="/admin/export/orders" style="background: var(--accent-gold); color: #090B0E; padding: 0.75rem 1.25rem; font-size: 0.78rem; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; border-radius: var(--radius-pill); text-align: center; text-decoration: none; box-shadow: 0 4px 15px var(--accent-glow);">
                Descargar Ventas (.csv) &rarr;
            </a>
        </div>
    </div>
</div>