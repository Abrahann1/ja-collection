<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; border-bottom: 1px solid var(--border-glass); padding-bottom: 1.25rem;">
    <div>
        <span style="font-size: 0.72rem; letter-spacing: 2px; text-transform: uppercase; color: var(--accent-gold); font-weight: 700;">Base de Datos de Coleccionistas</span>
        <h1 style="font-family: var(--font-luxury); font-size: 1.85rem; letter-spacing: 2px; text-transform: uppercase; margin-top: 0.25rem;">Gestión de Clientes</h1>
    </div>
</div>

<?php if (!empty($success)): ?>
    <div style="background: rgba(46, 125, 50, 0.15); border: 1px solid rgba(76, 175, 80, 0.4); color: #81C784; padding: 1rem 1.5rem; border-radius: var(--radius-btn); margin-bottom: 2rem; font-size: 0.88rem;">
        <?= htmlspecialchars($success) ?>
    </div>
<?php endif; ?>

<div class="table-panel">
    <div class="table-panel-header">
        <h2 class="table-panel-title">Clientes Registrados (<?= count($customers) ?>)</h2>
    </div>
    <table class="luxury-table">
        <thead>
            <tr>
                <th>Coleccionista</th>
                <th>Correo Electrónico</th>
                <th>Teléfono</th>
                <th>Total Pedidos</th>
                <th>Total Invertido</th>
                <th>Estado</th>
                <th style="text-align: right;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($customers)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 3rem; color: var(--text-muted);">
                        Aún no hay clientes registrados en la plataforma.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($customers as $c): ?>
                    <tr>
                        <td><strong style="color: var(--text-primary); font-size: 0.92rem;"><?= htmlspecialchars($c['name'] . ' ' . $c['lastname']) ?></strong></td>
                        <td style="color: var(--text-secondary);"><?= htmlspecialchars($c['email']) ?></td>
                        <td><?= htmlspecialchars($c['phone'] ?: 'No registrado') ?></td>
                        <td style="font-weight: 700;"><?= (int)$c['total_orders'] ?> pedidos</td>
                        <td style="font-family: var(--font-luxury); font-weight: 700; color: var(--accent-gold); font-size: 1rem;">
                            S/ <?= number_format((float)$c['total_spent'], 2) ?>
                        </td>
                        <td>
                            <span class="status-pill <?= $c['status'] === 'ACTIVE' ? 'status-paid' : 'status-pending' ?>">
                                <?= $c['status'] === 'ACTIVE' ? 'ACTIVO' : 'SUSPENDIDO' ?>
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <form method="POST" action="/admin/customers/<?= $c['id'] ?>/toggle" style="display:inline;">
                                <button type="submit" onclick="return confirm('¿Cambiar estado de este cliente?');" style="background: transparent; border: 1px solid var(--border-glass); color: <?= $c['status'] === 'ACTIVE' ? '#E57373' : '#81C784' ?>; padding: 0.35rem 0.75rem; border-radius: var(--radius-pill); font-size: 0.72rem; font-weight: 700; cursor: pointer;">
                                    <?= $c['status'] === 'ACTIVE' ? 'Suspender' : 'Reactivar' ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>