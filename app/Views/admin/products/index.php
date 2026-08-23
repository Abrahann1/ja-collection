<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h2 style="font-family: var(--font-luxury); font-size: 1.5rem; letter-spacing: 2px; text-transform: uppercase;">Catálogo de Coleccionismo</h2>
        <p style="color: var(--text-muted); font-size: 0.82rem; margin-top: 0.2rem;">Control de modelos, variantes de escala y disponibilidad en tiempo real.</p>
    </div>
    <a href="/admin/products/create" class="btn-action primary" style="padding: 0.75rem 1.5rem; font-size: 0.8rem; font-weight: 700; text-decoration: none; border-radius: var(--radius-btn); letter-spacing: 1.5px; text-transform: uppercase; background: var(--accent-gold); color: #090B0E; display: inline-flex; align-items: center; gap: 0.5rem;">
        + Nuevo Modelo
    </a>
</div>

<?php if (!empty($success)): ?>
    <div style="background: rgba(46, 125, 50, 0.15); border: 1px solid rgba(76, 175, 80, 0.4); color: #81C784; padding: 1rem 1.5rem; border-radius: var(--radius-btn); margin-bottom: 1.5rem; font-size: 0.85rem;">
        <?= htmlspecialchars($success) ?>
    </div>
<?php endif; ?>

<!-- Barra de Filtros y Búsqueda -->
<form method="GET" action="/admin/products" style="display: grid; grid-template-columns: 2fr 1fr 1fr auto; gap: 1rem; margin-bottom: 1.5rem; background: var(--bg-card); padding: 1.25rem; border-radius: var(--radius-card); border: 1px solid var(--border-glass);">
    <input type="text" name="q" value="<?= htmlspecialchars($filters['search'] ?? '') ?>" placeholder="Buscar por Nombre, SKU o Modelo..." style="background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.65rem 1rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.85rem;">
    
    <select name="brand" style="background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.65rem 1rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.85rem;">
        <option value="">Todas las Marcas</option>
        <?php foreach ($brands as $b): ?>
            <option value="<?= htmlspecialchars($b['slug']) ?>" <?= ($filters['brand'] ?? '') === $b['slug'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($b['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <select name="category" style="background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.65rem 1rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.85rem;">
        <option value="">Todas las Categorías</option>
        <?php foreach ($categories as $c): ?>
            <option value="<?= htmlspecialchars($c['slug']) ?>" <?= ($filters['category'] ?? '') === $c['slug'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit" style="background: var(--bg-card-hover); border: 1px solid var(--border-glass); color: var(--text-primary); padding: 0.65rem 1.25rem; border-radius: var(--radius-btn); cursor: pointer; font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">Filtrar</button>
</form>

<!-- Tabla de Productos -->
<div class="table-panel">
    <table class="luxury-table">
        <thead>
            <tr>
                <th>SKU</th>
                <th>Modelo</th>
                <th>Marca / Cat</th>
                <th>Escala</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Estado</th>
                <th style="text-align: right;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($products)): ?>
                <tr>
                    <td colspan="8" style="text-align: center; padding: 3rem; color: var(--text-muted);">
                        No se encontraron modelos con los filtros seleccionados.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($products as $p): ?>
                    <tr>
                        <td style="font-family: monospace; font-size: 0.85rem; color: var(--accent-gold); font-weight: 700;">
                            <?= htmlspecialchars($p['sku']) ?>
                        </td>
                        <td>
                            <strong style="color: var(--text-primary); display: block; font-size: 0.92rem;"><?= htmlspecialchars($p['name']) ?></strong>
                            <?php if ($p['is_featured']): ?><span style="font-size: 0.65rem; background: rgba(197,168,128,0.2); color: var(--accent-gold); padding: 2px 6px; border-radius: 4px; margin-right: 4px;">DESTACADO</span><?php endif; ?>
                            <?php if ($p['is_new']): ?><span style="font-size: 0.65rem; background: rgba(33,150,243,0.2); color: #64B5F6; padding: 2px 6px; border-radius: 4px;">NUEVO</span><?php endif; ?>
                        </td>
                        <td>
                            <span style="display: block; color: var(--text-primary); font-size: 0.85rem;"><?= htmlspecialchars($p['brand_name']) ?></span>
                            <span style="color: var(--text-muted); font-size: 0.75rem;"><?= htmlspecialchars($p['category_name']) ?></span>
                        </td>
                        <td style="font-weight: 600;"><?= htmlspecialchars($p['scale']) ?></td>
                        <td style="font-weight: 700; color: var(--text-primary);"><?= $p['price_formatted'] ?></td>
                        <td>
                            <span class="status-pill <?= (int)$p['stock_available'] > (int)$p['minimum_stock'] ? 'status-paid' : ((int)$p['stock_available'] > 0 ? 'status-pending' : '') ?>" style="<?= (int)$p['stock_available'] <= 0 ? 'background: rgba(229,115,115,0.15); border: 1px solid rgba(229,115,115,0.4); color: #E57373;' : '' ?>">
                                <?= (int)$p['stock_available'] ?> Disp.
                            </span>
                        </td>
                        <td>
                            <span style="font-size: 0.75rem; color: <?= $p['status'] === 'ACTIVE' ? '#81C784' : '#E57373' ?>;">
                                &bull; <?= $p['status'] ?>
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <div style="display: inline-flex; gap: 0.5rem;">
                                <a href="/admin/products/<?= $p['id'] ?>/edit" title="Editar" style="background: var(--bg-card-hover); border: 1px solid var(--border-glass); color: var(--text-primary); padding: 0.4rem 0.75rem; border-radius: var(--radius-btn); text-decoration: none; font-size: 0.75rem; font-weight: 600;">Editar</a>
                                <a href="/admin/products/<?= $p['id'] ?>/duplicate" title="Duplicar Variante" onclick="return confirm('¿Deseas duplicar este modelo como nueva variante?');" style="background: var(--bg-card-hover); border: 1px solid var(--border-glass); color: var(--accent-gold); padding: 0.4rem 0.75rem; border-radius: var(--radius-btn); text-decoration: none; font-size: 0.75rem; font-weight: 600;">Clonar</a>
                                <a href="/admin/products/<?= $p['id'] ?>/delete" title="Archivar" onclick="return confirm('¿Seguro que deseas archivar este modelo?');" style="background: rgba(229,115,115,0.1); border: 1px solid rgba(229,115,115,0.3); color: #E57373; padding: 0.4rem 0.75rem; border-radius: var(--radius-btn); text-decoration: none; font-size: 0.75rem; font-weight: 600;">Borrar</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>