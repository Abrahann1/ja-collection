<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; border-bottom: 1px solid var(--border-glass); padding-bottom: 1.25rem;">
    <div>
        <span style="font-size: 0.72rem; letter-spacing: 2px; text-transform: uppercase; color: var(--accent-gold); font-weight: 700;">Ventas & Despachos</span>
        <h1 style="font-family: var(--font-luxury); font-size: 1.85rem; letter-spacing: 2px; text-transform: uppercase; margin-top: 0.25rem;">Gestión de Pedidos</h1>
    </div>
</div>

<?php if (!empty($success)): ?>
    <div style="background: rgba(46, 125, 50, 0.15); border: 1px solid rgba(76, 175, 80, 0.4); color: #81C784; padding: 1rem 1.5rem; border-radius: var(--radius-btn); margin-bottom: 2rem; font-size: 0.88rem;">
        <?= htmlspecialchars($success) ?>
    </div>
<?php endif; ?>

<!-- Filtros por Estado -->
<div style="display: flex; gap: 0.6rem; margin-bottom: 2rem; overflow-x: auto; padding-bottom: 0.5rem;">
    <a href="/admin/orders" class="filter-pill <?= empty($currentStatus) ? 'active' : '' ?>">Todos los Pedidos</a>
    <a href="/admin/orders?status=PENDING" class="filter-pill <?= $currentStatus === 'PENDING' ? 'active' : '' ?>">Pendientes</a>
    <a href="/admin/orders?status=PAID" class="filter-pill <?= $currentStatus === 'PAID' ? 'active' : '' ?>">Pagados</a>
    <a href="/admin/orders?status=PROCESSING" class="filter-pill <?= $currentStatus === 'PROCESSING' ? 'active' : '' ?>">En Preparación</a>
    <a href="/admin/orders?status=SHIPPED" class="filter-pill <?= $currentStatus === 'SHIPPED' ? 'active' : '' ?>">Enviados</a>
    <a href="/admin/orders?status=DELIVERED" class="filter-pill <?= $currentStatus === 'DELIVERED' ? 'active' : '' ?>">Entregados</a>
</div>

<div class="table-panel">
    <table class="luxury-table">
        <thead>
            <tr>
                <th>N° Pedido</th>
                <th>Cliente</th>
                <th>Teléfono</th>
                <th>Destino</th>
                <th>Monto Total</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th style="text-align: right;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($orders)): ?>
                <tr>
                    <td colspan="8" style="text-align: center; padding: 3rem; color: var(--text-muted);">
                        No hay pedidos registrados con este estado.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($orders as $ord): ?>
                    <tr>
                        <td style="font-family: monospace; font-weight: 700; color: var(--accent-gold);">
                            <?= htmlspecialchars($ord['order_number']) ?>
                        </td>
                        <td>
                            <strong style="color: var(--text-primary); display: block;"><?= htmlspecialchars($ord['customer_name']) ?></strong>
                            <span style="font-size: 0.75rem; color: var(--text-muted);"><?= htmlspecialchars($ord['customer_email']) ?></span>
                        </td>
                        <td style="font-size: 0.85rem;"><?= htmlspecialchars($ord['customer_phone']) ?></td>
                        <td style="font-size: 0.85rem;"><?= htmlspecialchars($ord['shipping_district'] . ', ' . $ord['shipping_department']) ?></td>
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
                            <a href="/admin/orders/<?= $ord['id'] ?>" style="background: var(--bg-card); border: 1px solid var(--border-glass); color: var(--accent-gold); padding: 0.4rem 0.85rem; border-radius: var(--radius-btn); font-size: 0.78rem; font-weight: 700; text-decoration: none;">
                                Ver Detalle &rarr;
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>