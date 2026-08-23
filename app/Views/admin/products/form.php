<div style="max-width: 900px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; border-bottom: 1px solid var(--border-glass); padding-bottom: 1rem;">
        <div>
            <span style="font-size: 0.72rem; letter-spacing: 2px; text-transform: uppercase; color: var(--accent-gold); font-weight: 700;">
                <?= $isEdit ? 'EDICIÓN DE MODELO' : 'NUEVO INGRESO AL CATÁLOGO' ?>
            </span>
            <h1 style="font-family: var(--font-luxury); font-size: 1.75rem; letter-spacing: 2px; text-transform: uppercase; margin-top: 0.3rem;">
                <?= $isEdit ? 'Editar: ' . htmlspecialchars($product['name'] ?? '') : 'Registrar Coleccionable' ?>
            </h1>
        </div>
        <a href="/admin/products" style="color: var(--text-muted); font-size: 0.85rem; text-decoration: none; text-transform: uppercase; letter-spacing: 1px;">&larr; Volver al Listado</a>
    </div>

    <?php if (!empty($errors)): ?>
        <div style="background: rgba(139,30,36,0.25); border: 1px solid #8B1E24; color: #FFB4B6; padding: 1.25rem; border-radius: var(--radius-card); margin-bottom: 2rem; font-size: 0.88rem;">
            <strong style="display: block; margin-bottom: 0.5rem; text-transform: uppercase;">Por favor corrige los siguientes puntos:</strong>
            <ul style="padding-left: 1.5rem;">
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= $isEdit ? '/admin/products/' . $product['id'] . '/edit' : '/admin/products/create' ?>" style="display: flex; flex-direction: column; gap: 2rem;">
        <!-- Bloque 1: Datos Principales -->
        <section class="glass-card" style="display: flex; flex-direction: column; gap: 1.25rem;">
            <h3 style="font-family: var(--font-luxury); font-size: 1.1rem; letter-spacing: 1.5px; text-transform: uppercase; color: var(--accent-gold); border-bottom: 1px solid var(--border-glass); padding-bottom: 0.75rem;">
                1. Información del Modelo
            </h3>
            
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.25rem;">
                <div>
                    <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 0.4rem; font-weight: 600;">Nombre del Producto *</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($product['name'] ?? '') ?>" required placeholder="Ej: Nissan Skyline GT-R (BNR34) Nismo" style="width: 100%; background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.75rem 1rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.9rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 0.4rem; font-weight: 600;">Código SKU Único *</label>
                    <input type="text" name="sku" value="<?= htmlspecialchars($product['sku'] ?? '') ?>" required placeholder="Ej: HW-R34-004" style="width: 100%; background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.75rem 1rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.9rem; font-family: monospace;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.25rem;">
                <div>
                    <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 0.4rem; font-weight: 600;">Marca Fabricante *</label>
                    <select name="brand_id" required style="width: 100%; background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.75rem 1rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.85rem;">
                        <option value="">Seleccione Marca...</option>
                        <?php foreach ($brands as $b): ?>
                            <option value="<?= $b['id'] ?>" <?= (int)($product['brand_id'] ?? 0) === (int)$b['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($b['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 0.4rem; font-weight: 600;">Categoría / Línea *</label>
                    <select name="category_id" required style="width: 100%; background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.75rem 1rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.85rem;">
                        <option value="">Seleccione Categoría...</option>
                        <?php foreach ($categories as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= (int)($product['category_id'] ?? 0) === (int)$c['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 0.4rem; font-weight: 600;">Escala Automotriz</label>
                    <select name="scale" style="width: 100%; background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.75rem 1rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.85rem;">
                        <option value="1:64" <?= ($product['scale'] ?? '1:64') === '1:64' ? 'selected' : '' ?>>1:64 (Estándar)</option>
                        <option value="1:43" <?= ($product['scale'] ?? '') === '1:43' ? 'selected' : '' ?>>1:43</option>
                        <option value="1:24" <?= ($product['scale'] ?? '') === '1:24' ? 'selected' : '' ?>>1:24</option>
                        <option value="1:18" <?= ($product['scale'] ?? '') === '1:18' ? 'selected' : '' ?>>1:18</option>
                    </select>
                </div>
            </div>

            <div>
                <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 0.4rem; font-weight: 600;">Descripción Detallada</label>
                <textarea name="description" rows="3" placeholder="Detalles de acabado, neumáticos de goma, chasis de metal, blister o caja acrílica..." style="width: 100%; background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.75rem 1rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.88rem;"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
            </div>
        </section>

        <!-- Bloque 2: Precios e Inventario -->
        <section class="glass-card" style="display: flex; flex-direction: column; gap: 1.25rem;">
            <h3 style="font-family: var(--font-luxury); font-size: 1.1rem; letter-spacing: 1.5px; text-transform: uppercase; color: var(--accent-gold); border-bottom: 1px solid var(--border-glass); padding-bottom: 0.75rem;">
                2. Precios & Control de Stock
            </h3>

            <div style="display: grid; grid-template-columns: 1fr 1fr <?= $isEdit ? '' : '1fr 1fr' ?>; gap: 1.25rem;">
                <div>
                    <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 0.4rem; font-weight: 600;">Precio de Venta (S/) *</label>
                    <input type="number" step="0.10" name="price" value="<?= htmlspecialchars((string)($product['price'] ?? '')) ?>" required placeholder="39.90" style="width: 100%; background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.75rem 1rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.95rem; font-weight: 700;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 0.4rem; font-weight: 600;">Precio Anterior / Tachado (S/)</label>
                    <input type="number" step="0.10" name="old_price" value="<?= htmlspecialchars((string)($product['old_price'] ?? '')) ?>" placeholder="49.90" style="width: 100%; background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.75rem 1rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.95rem;">
                </div>
                <?php if (!$isEdit): ?>
                    <div>
                        <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 0.4rem; font-weight: 600;">Stock Inicial (Unidades)</label>
                        <input type="number" name="stock" value="<?= htmlspecialchars((string)($product['stock'] ?? '5')) ?>" min="0" style="width: 100%; background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.75rem 1rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.95rem;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 0.4rem; font-weight: 600;">Alerta de Stock Mínimo</label>
                        <input type="number" name="minimum_stock" value="<?= htmlspecialchars((string)($product['minimum_stock'] ?? '2')) ?>" min="1" style="width: 100%; background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.75rem 1rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.95rem;">
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Bloque 3: Visibilidad y Badges -->
        <section class="glass-card" style="display: flex; flex-direction: column; gap: 1.25rem;">
            <h3 style="font-family: var(--font-luxury); font-size: 1.1rem; letter-spacing: 1.5px; text-transform: uppercase; color: var(--accent-gold); border-bottom: 1px solid var(--border-glass); padding-bottom: 0.75rem;">
                3. Visibilidad & Estado
            </h3>

            <div style="display: flex; gap: 2.5rem; align-items: center;">
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.9rem;">
                    <input type="checkbox" name="is_featured" value="1" <?= !empty($product['is_featured']) ? 'checked' : '' ?> style="width: 18px; height: 18px; accent-color: var(--accent-gold);">
                    <span>Producto Destacado en Portada</span>
                </label>

                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.9rem;">
                    <input type="checkbox" name="is_new" value="1" <?= !empty($product['is_new']) ? 'checked' : '' ?> style="width: 18px; height: 18px; accent-color: var(--accent-gold);">
                    <span>Marcar como Novedad</span>
                </label>

                <div style="margin-left: auto; display: flex; align-items: center; gap: 0.75rem;">
                    <label style="font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted); font-weight: 600;">Estado:</label>
                    <select name="status" style="background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.5rem 1rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.85rem;">
                        <option value="ACTIVE" <?= ($product['status'] ?? 'ACTIVE') === 'ACTIVE' ? 'selected' : '' ?>>Activo</option>
                        <option value="INACTIVE" <?= ($product['status'] ?? '') === 'INACTIVE' ? 'selected' : '' ?>>Inactivo</option>
                    </select>
                </div>
            </div>
        </section>

        <!-- Botones de Acción -->
        <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1rem;">
            <a href="/admin/products" style="padding: 0.85rem 1.75rem; border: 1px solid var(--border-glass); color: var(--text-secondary); text-decoration: none; border-radius: var(--radius-btn); font-size: 0.85rem; font-weight: 600; text-transform: uppercase;">Cancelar</a>
            <button type="submit" style="padding: 0.85rem 2.25rem; background: var(--accent-gold); color: #090B0E; border: none; border-radius: var(--radius-btn); font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; cursor: pointer;">
                <?= $isEdit ? 'Guardar Cambios' : 'Crear Producto' ?>
            </button>
        </div>
    </form>
</div>