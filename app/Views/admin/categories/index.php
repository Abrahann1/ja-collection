<div style="display: grid; grid-template-columns: 350px 1fr; gap: 2.5rem;">
    <!-- Formulario Crear Categoría -->
    <div class="glass-card" style="padding: 2rem;">
        <h2 style="font-family: var(--font-luxury); font-size: 1.25rem; text-transform: uppercase; color: var(--accent-gold); margin-bottom: 1.5rem;">
            + Nueva Categoría
        </h2>
        <form method="POST" action="/admin/categories">
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 0.4rem; font-weight: 600;">Nombre de la Categoría *</label>
                <input type="text" name="name" required placeholder="Ej: Dioramas, Protectores, RLC..." style="width: 100%; background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.75rem 1rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.9rem;">
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 0.4rem; font-weight: 600;">Descripción</label>
                <textarea name="description" rows="3" placeholder="Accesorios y piezas de exhibición..." style="width: 100%; background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.75rem 1rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.85rem;"></textarea>
            </div>
            <button type="submit" style="width: 100%; background: var(--accent-gold); color: #090B0E; border: none; padding: 0.85rem; font-size: 0.82rem; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; border-radius: var(--radius-btn); cursor: pointer;">
                Guardar Categoría
            </button>
        </form>
    </div>

    <!-- Listado de Categorías -->
    <div class="table-panel">
        <div class="table-panel-header">
            <h2 class="table-panel-title">Categorías Registradas</h2>
        </div>
        <table class="luxury-table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Slug</th>
                    <th>Modelos Activos</th>
                    <th>Estado</th>
                    <th style="text-align: right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $c): ?>
                    <tr>
                        <td><strong style="color: var(--text-primary);"><?= htmlspecialchars($c['name']) ?></strong></td>
                        <td style="font-family: monospace; font-size: 0.8rem; color: var(--accent-gold);"><?= htmlspecialchars($c['slug']) ?></td>
                        <td><?= (int)$c['products_count'] ?> modelos</td>
                        <td><span style="color: #81C784; font-size: 0.75rem;">&bull; <?= $c['status'] ?></span></td>
                        <td style="text-align: right;">
                            <a href="/admin/categories/<?= $c['id'] ?>/delete" onclick="return confirm('¿Archivar categoría?');" style="color: #E57373; font-size: 0.78rem; font-weight: 700;">Archivar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>