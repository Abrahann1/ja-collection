<div style="max-width: 950px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; border-bottom: 1px solid var(--border-glass); padding-bottom: 1.25rem;">
        <div>
            <span style="font-size: 0.72rem; letter-spacing: 2px; text-transform: uppercase; color: var(--accent-gold); font-weight: 700;">Detalle de Orden</span>
            <h1 style="font-family: var(--font-luxury); font-size: 1.85rem; letter-spacing: 2px; text-transform: uppercase; margin-top: 0.25rem;">
                Pedido <?= htmlspecialchars($order['order_number']) ?>
            </h1>
        </div>
        <a href="/admin/orders" style="color: var(--text-muted); font-size: 0.82rem; text-decoration: none; text-transform: uppercase; letter-spacing: 1px;">&larr; Volver a Pedidos</a>
    </div>

    <?php if (!empty($success)): ?>
        <div style="background: rgba(46, 125, 50, 0.15); border: 1px solid rgba(76, 175, 80, 0.4); color: #81C784; padding: 1rem 1.5rem; border-radius: var(--radius-btn); margin-bottom: 2rem; font-size: 0.88rem;">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <div style="display: grid; grid-template-columns: 1.4fr 1fr; gap: 2.5rem; align-items: start;">
        <!-- Lista de Modelos Comprados -->
        <div class="table-panel">
            <div class="table-panel-header">
                <h2 class="table-panel-title">Modelos Solicitados</h2>
            </div>
            <table class="luxury-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cant.</th>
                        <th>P. Unit</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order['items'] as $item): ?>
                        <tr>
                            <td>
                                <strong style="color: var(--text-primary); display:block;"><?= htmlspecialchars($item['product_name']) ?></strong>
                                <span style="font-family: monospace; font-size: 0.75rem; color: var(--accent-gold);"><?= htmlspecialchars($item['product_sku']) ?></span>
                            </td>
                            <td style="font-weight: 700;"><?= (int)$item['quantity'] ?></td>
                            <td>S/ <?= number_format((float)$item['price'], 2) ?></td>
                            <td style="font-weight: 700; color: var(--text-primary);">S/ <?= number_format((float)$item['subtotal'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <div style="padding: 1.5rem 2rem; background: var(--bg-secondary); border-top: 1px solid var(--border-glass); display: flex; flex-direction: column; gap: 0.5rem;">
                <div style="display:flex; justify-content:space-between; font-size: 0.88rem; color: var(--text-muted);">
                    <span>Subtotal:</span>
                    <strong>S/ <?= number_format((float)$order['subtotal'], 2) ?></strong>
                </div>
                <div style="display:flex; justify-content:space-between; font-size: 0.88rem; color: var(--text-muted);">
                    <span>Costo de Envío:</span>
                    <strong>S/ <?= number_format((float)$order['shipping_cost'], 2) ?></strong>
                </div>
                <div style="display:flex; justify-content:space-between; font-size: 1.25rem; font-weight: 700; color: var(--text-primary); border-top: 1px solid var(--border-glass); padding-top: 0.75rem; margin-top: 0.5rem;">
                    <span>Total Pagado:</span>
                    <span style="font-family: var(--font-luxury); color: var(--accent-gold);">S/ <?= number_format((float)$order['total'], 2) ?></span>
                </div>
            </div>
        </div>

        <!-- Panel de Despacho y Actualización de Estado -->
        <div style="display: flex; flex-direction: column; gap: 1.75rem;">
            <!-- Datos de Envío -->
            <div class="glass-card" style="padding: 1.75rem;">
                <h3 style="font-family: var(--font-luxury); font-size: 1rem; text-transform: uppercase; color: var(--accent-gold); margin-bottom: 1rem; border-bottom: 1px solid var(--border-glass); padding-bottom: 0.5rem;">
                    Datos del Destinatario
                </h3>
                <p style="font-size: 0.88rem; color: var(--text-primary); margin-bottom: 0.4rem;"><strong>Cliente:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
                <p style="font-size: 0.88rem; color: var(--text-primary); margin-bottom: 0.4rem;"><strong>Teléfono:</strong> <?= htmlspecialchars($order['customer_phone']) ?></p>
                <p style="font-size: 0.88rem; color: var(--text-primary); margin-bottom: 0.4rem;"><strong>Email:</strong> <?= htmlspecialchars($order['customer_email']) ?></p>
                <p style="font-size: 0.88rem; color: var(--text-primary); margin-bottom: 0.4rem;"><strong>Destino:</strong> <?= htmlspecialchars($order['shipping_address'] . ', ' . $order['shipping_district'] . ' - ' . $order['shipping_department']) ?></p>
                <?php if (!empty($order['notes'])): ?>
                    <p style="font-size: 0.82rem; color: var(--text-muted); margin-top: 0.5rem;"><strong>Referencia:</strong> <?= htmlspecialchars($order['notes']) ?></p>
                <?php endif; ?>
            </div>

            <!-- Cambio de Estado del Pedido -->
            <div class="glass-card" style="padding: 1.75rem;">
                <h3 style="font-family: var(--font-luxury); font-size: 1rem; text-transform: uppercase; color: var(--accent-gold); margin-bottom: 1rem; border-bottom: 1px solid var(--border-glass); padding-bottom: 0.5rem;">
                    Actualizar Estado
                </h3>
                <form method="POST" action="/admin/orders/<?= $order['id'] ?>/status">
                    <select name="status" style="width: 100%; background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.75rem 1rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.88rem; margin-bottom: 1.25rem;">
                        <option value="PENDING" <?= $order['status'] === 'PENDING' ? 'selected' : '' ?>>PENDIENTE DE PAGO</option>
                        <option value="PAID" <?= $order['status'] === 'PAID' ? 'selected' : '' ?>>PAGO VERIFICADO (PAGADO)</option>
                        <option value="PROCESSING" <?= $order['status'] === 'PROCESSING' ? 'selected' : '' ?>>EN PREPARACIÓN / EMPAQUE</option>
                        <option value="SHIPPED" <?= $order['status'] === 'SHIPPED' ? 'selected' : '' ?>>DESPACHADO / EN RUTA</option>
                        <option value="DELIVERED" <?= $order['status'] === 'DELIVERED' ? 'selected' : '' ?>>ENTREGADO AL CLIENTE</option>
                        <option value="CANCELLED" <?= $order['status'] === 'CANCELLED' ? 'selected' : '' ?>>CANCELADO</option>
                    </select>

                    <button type="submit" style="width: 100%; background: var(--accent-gold); color: #090B0E; border: none; padding: 0.85rem; font-size: 0.82rem; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; border-radius: var(--radius-pill); cursor: pointer;">
                        Actualizar Estado &rarr;
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>