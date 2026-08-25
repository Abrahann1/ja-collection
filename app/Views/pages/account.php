<div class="container" style="padding: 3rem 0 5rem;">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; border-bottom: 1px solid var(--border-glass); padding-bottom: 1.5rem; margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <span style="font-size: 0.72rem; letter-spacing: 2px; text-transform: uppercase; color: var(--accent-gold); font-weight: 700;">Panel de Coleccionista</span>
            <h1 style="font-family: var(--font-luxury); font-size: 2.2rem; letter-spacing: 2px; text-transform: uppercase; margin-top: 0.25rem;">
                Hola, <?= htmlspecialchars($user['name']) ?>
            </h1>
        </div>
        <a href="/logout" style="background: rgba(229,115,115,0.1); border: 1px solid rgba(229,115,115,0.3); color: #E57373; padding: 0.5rem 1.25rem; font-size: 0.78rem; font-weight: 700; border-radius: var(--radius-pill); text-transform: uppercase; letter-spacing: 1px;">
            Cerrar Sesión
        </a>
    </div>

    <?php if (!empty($success)): ?>
        <div style="background: rgba(46, 125, 50, 0.15); border: 1px solid rgba(76, 175, 80, 0.4); color: #81C784; padding: 1rem 1.5rem; border-radius: var(--radius-card); margin-bottom: 2rem; font-size: 0.88rem;">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div style="background: rgba(139,30,36,0.25); border: 1px solid #8B1E24; color: #FFB4B6; padding: 1rem 1.5rem; border-radius: var(--radius-card); margin-bottom: 2rem; font-size: 0.88rem;">
            ⚠️ <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div style="display: grid; grid-template-columns: 1fr 340px; gap: 3rem; align-items: start;">
        <!-- Columna Izquierda: Mis Pedidos -->
        <div class="table-panel">
            <div class="table-panel-header">
                <h2 class="table-panel-title">Mis Pedidos Realizados</h2>
                <span style="font-size: 0.8rem; color: var(--text-muted);"><?= count($orders) ?> compras</span>
            </div>

            <?php if (empty($orders)): ?>
                <div style="text-align: center; padding: 4rem 2rem;">
                    <span style="font-size: 2.5rem; color: var(--accent-gold); display: block; margin-bottom: 0.75rem;">📦</span>
                    <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 1.25rem;">Aún no tienes pedidos registrados.</p>
                    <a href="/shop" style="background: var(--accent-gold); color: #090B0E; padding: 0.65rem 1.5rem; font-size: 0.78rem; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; border-radius: var(--radius-pill);">
                        Ir al Catálogo &rarr;
                    </a>
                </div>
            <?php else: ?>
                <table class="luxury-table">
                    <thead>
                        <tr>
                            <th>N° Pedido</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th style="text-align: right;">Detalles</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $ord): ?>
                            <tr>
                                <td style="font-family: monospace; font-weight: 700; color: var(--accent-gold);">
                                    <?= htmlspecialchars($ord['order_number']) ?>
                                </td>
                                <td style="font-size: 0.82rem; color: var(--text-muted);"><?= htmlspecialchars(date('d/m/Y', strtotime($ord['created_at']))) ?></td>
                                <td style="font-family: var(--font-luxury); font-weight: 700; color: var(--text-primary);">
                                    S/ <?= number_format((float)$ord['total'], 2) ?>
                                </td>
                                <td>
                                    <span class="status-pill <?= in_array($ord['status'], ['PAID', 'DELIVERED'], true) ? 'status-paid' : 'status-pending' ?>">
                                        <?= $ord['status'] ?>
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <a href="/orders/success?order=<?= urlencode($ord['order_number']) ?>" style="background: var(--bg-card); border: 1px solid var(--border-glass); color: var(--accent-gold); padding: 0.35rem 0.75rem; border-radius: var(--radius-pill); font-size: 0.72rem; font-weight: 700;">
                                        Ver Recibo
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <!-- Columna Derecha: Datos y Seguridad -->
        <div style="display: flex; flex-direction: column; gap: 2rem;">
            <!-- Datos de Contacto -->
            <div class="glass-card" style="padding: 1.75rem;">
                <h3 style="font-family: var(--font-luxury); font-size: 1rem; text-transform: uppercase; color: var(--accent-gold); margin-bottom: 1.25rem; border-bottom: 1px solid var(--border-glass); padding-bottom: 0.5rem;">
                    Mis Datos
                </h3>
                <form method="POST" action="/account/profile" style="display: flex; flex-direction: column; gap: 1rem;">
                    <div>
                        <label style="display: block; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 0.3rem;">Nombre</label>
                        <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required style="width: 100%; background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.6rem 0.85rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.88rem;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 0.3rem;">Apellido</label>
                        <input type="text" name="lastname" value="<?= htmlspecialchars($user['lastname']) ?>" required style="width: 100%; background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.6rem 0.85rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.88rem;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 0.3rem;">Teléfono</label>
                        <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" placeholder="+51 987 654 321" style="width: 100%; background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.6rem 0.85rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.88rem;">
                    </div>
                    <button type="submit" style="background: var(--accent-gold); color: #090B0E; border: none; padding: 0.75rem; font-size: 0.78rem; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; border-radius: var(--radius-pill); cursor: pointer; margin-top: 0.5rem;">
                        Guardar Cambios
                    </button>
                </form>
            </div>

            <!-- Seguridad / Contraseña -->
            <div class="glass-card" style="padding: 1.75rem;">
                <h3 style="font-family: var(--font-luxury); font-size: 1rem; text-transform: uppercase; color: var(--accent-gold); margin-bottom: 1.25rem; border-bottom: 1px solid var(--border-glass); padding-bottom: 0.5rem;">
                    Cambiar Contraseña
                </h3>
                <form method="POST" action="/account/password" style="display: flex; flex-direction: column; gap: 0.85rem;">
                    <div>
                        <label style="display: block; font-size: 0.72rem; text-transform: uppercase; color: var(--text-muted); margin-bottom: 0.25rem;">Contraseña Actual</label>
                        <input type="password" name="current_password" required placeholder="••••••••" style="width: 100%; background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.6rem 0.85rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.85rem;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.72rem; text-transform: uppercase; color: var(--text-muted); margin-bottom: 0.25rem;">Nueva Contraseña (min. 8)</label>
                        <input type="password" name="new_password" required minlength="8" placeholder="••••••••" style="width: 100%; background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.6rem 0.85rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.85rem;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.72rem; text-transform: uppercase; color: var(--text-muted); margin-bottom: 0.25rem;">Confirmar Contraseña</label>
                        <input type="password" name="confirm_password" required minlength="8" placeholder="••••••••" style="width: 100%; background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.6rem 0.85rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.85rem;">
                    </div>
                    <button type="submit" style="background: var(--bg-secondary); border: 1px solid var(--border-accent); color: var(--accent-gold); padding: 0.75rem; font-size: 0.78rem; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; border-radius: var(--radius-pill); cursor: pointer; margin-top: 0.5rem;">
                        Actualizar Clave
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>