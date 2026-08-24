<section class="metrics-grid">
    <article class="glass-card">
        <span class="metric-title">Ventas Totales</span>
        <div class="metric-value gold" id="metric-total-sales"><?= $metrics['total_sales_formatted'] ?></div>
        <p class="metric-trend">&bull; Ingresos acumulados en tiempo real</p>
    </article>

    <article class="glass-card">
        <span class="metric-title">Pedidos Activos</span>
        <div class="metric-value" id="metric-active-orders"><?= $metrics['active_orders'] ?></div>
        <p class="metric-trend">&bull; Pendientes de pago o despacho</p>
    </article>

    <article class="glass-card">
        <span class="metric-title">Modelos en Catálogo</span>
        <div class="metric-value" id="metric-catalog-count"><?= $metrics['catalog_count'] ?></div>
        <p class="metric-trend">&bull; Autos activos en la tienda pública</p>
    </article>

    <article class="glass-card">
        <span class="metric-title">Alertas de Stock</span>
        <div class="metric-value" id="metric-low-stock" style="color: <?= $metrics['low_stock_count'] > 0 ? '#FFB74D' : 'var(--text-primary)' ?>;">
            <?= $metrics['low_stock_count'] ?>
        </div>
        <p class="metric-trend">&bull; Modelos con stock bajo o agotados</p>
    </article>
</section>

<section class="table-panel">
    <div class="table-panel-header">
        <div>
            <h2 class="table-panel-title">Últimos Pedidos Registrados</h2>
            <span style="font-size: 0.75rem; color: var(--text-muted);">Sincronización en vivo con base de datos</span>
        </div>
        <a href="/admin/orders" style="color: var(--accent-gold); font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">
            Ver Todos los Pedidos &rarr;
        </a>
    </div>

    <table class="luxury-table">
        <thead>
            <tr>
                <th>N° Pedido</th>
                <th>Cliente</th>
                <th>Monto Total</th>
                <th>Estado de Pago</th>
                <th>Fecha de Registro</th>
                <th style="text-align: right;">Acción</th>
            </tr>
        </thead>
        <tbody id="dashboard-recent-orders-tbody">
            <?php if (empty($metrics['recent_orders'])): ?>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 2.5rem; color: var(--text-muted);">
                        Aún no se han registrado pedidos en la plataforma.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($metrics['recent_orders'] as $ord): ?>
                    <tr>
                        <td style="font-family: monospace; font-weight: 700; color: var(--accent-gold);">
                            <?= htmlspecialchars($ord['order_number']) ?>
                        </td>
                        <td><strong style="color: var(--text-primary);"><?= htmlspecialchars($ord['customer_name']) ?></strong></td>
                        <td style="font-family: var(--font-luxury); font-weight: 700; color: var(--text-primary);">
                            S/ <?= number_format((float)$ord['total'], 2) ?>
                        </td>
                        <td>
                            <span class="status-pill <?= in_array($ord['status'], ['PAID', 'DELIVERED'], true) ? 'status-paid' : 'status-pending' ?>">
                                <?= $ord['status'] ?>
                            </span>
                        </td>
                        <td style="font-size: 0.8rem; color: var(--text-muted);"><?= htmlspecialchars($ord['created_at']) ?></td>
                        <td style="text-align: right;">
                            <a href="/admin/orders/<?= $ord['id'] ?>" style="background: var(--bg-card); border: 1px solid var(--border-glass); color: var(--accent-gold); padding: 0.35rem 0.75rem; border-radius: var(--radius-btn); font-size: 0.75rem; font-weight: 700;">
                                Ver &rarr;
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<!-- SCRIPT DE ACTUALIZACIÓN EN VIVO (LIVE SYNC) -->
<script>
function syncDashboardLive() {
    fetch('/api/admin/dashboard/stats')
        .then(res => res.json())
        .then(res => {
            if (!res.success || !res.data) return;
            const d = res.data;

            // Actualizar Tarjetas de Métricas
            document.getElementById("metric-total-sales").textContent = d.total_sales_formatted;
            document.getElementById("metric-active-orders").textContent = d.active_orders;
            document.getElementById("metric-catalog-count").textContent = d.catalog_count;
            document.getElementById("metric-low-stock").textContent = d.low_stock_count;

            // Actualizar Filas de la Tabla
            const tbody = document.getElementById("dashboard-recent-orders-tbody");
            if (d.recent_orders.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" style="text-align: center; padding: 2.5rem; color: var(--text-muted);">Aún no se han registrado pedidos en la plataforma.</td></tr>`;
                return;
            }

            tbody.innerHTML = d.recent_orders.map(ord => `
                <tr>
                    <td style="font-family: monospace; font-weight: 700; color: var(--accent-gold);">${ord.order_number}</td>
                    <td><strong style="color: var(--text-primary);">${ord.customer_name}</strong></td>
                    <td style="font-family: var(--font-luxury); font-weight: 700; color: var(--text-primary);">S/ ${parseFloat(ord.total).toFixed(2)}</td>
                    <td>
                        <span class="status-pill ${['PAID', 'DELIVERED'].includes(ord.status) ? 'status-paid' : 'status-pending'}">
                            ${ord.status}
                        </span>
                    </td>
                    <td style="font-size: 0.8rem; color: var(--text-muted);">${ord.created_at}</td>
                    <td style="text-align: right;">
                        <a href="/admin/orders/${ord.id}" style="background: var(--bg-card); border: 1px solid var(--border-glass); color: var(--accent-gold); padding: 0.35rem 0.75rem; border-radius: var(--radius-btn); font-size: 0.75rem; font-weight: 700;">
                            Ver &rarr;
                        </a>
                    </td>
                </tr>
            `).join("");
        })
        .catch(err => console.log("Live sync paused:", err));
}

// Sincronizar automáticamente cada 8 segundos
setInterval(syncDashboardLive, 8000);
</script>