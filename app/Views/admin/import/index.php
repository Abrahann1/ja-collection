<div style="max-width: 1050px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; border-bottom: 1px solid var(--border-glass); padding-bottom: 1.25rem;">
        <div>
            <span style="font-size: 0.72rem; letter-spacing: 2px; text-transform: uppercase; color: var(--accent-gold); font-weight: 700;">Carga Masiva</span>
            <h1 style="font-family: var(--font-luxury); font-size: 1.85rem; letter-spacing: 2px; text-transform: uppercase; margin-top: 0.25rem;">Importación desde Excel</h1>
        </div>
        <a href="/admin/import/template" style="background: var(--bg-card); border: 1px solid var(--border-accent); color: var(--accent-gold); padding: 0.65rem 1.25rem; font-size: 0.78rem; font-weight: 700; text-decoration: none; border-radius: var(--radius-pill); letter-spacing: 1px; text-transform: uppercase; display: inline-flex; align-items: center; gap: 0.4rem;">
            ⬇ Descargar Plantilla Oficial
        </a>
    </div>

    <?php if (!empty($success)): ?>
        <div style="background: rgba(46, 125, 50, 0.15); border: 1px solid rgba(76, 175, 80, 0.4); color: #81C784; padding: 1rem 1.5rem; border-radius: var(--radius-btn); margin-bottom: 2rem; font-size: 0.88rem;">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div style="background: rgba(139,30,36,0.25); border: 1px solid #8B1E24; color: #FFB4B6; padding: 1rem 1.5rem; border-radius: var(--radius-btn); margin-bottom: 2rem; font-size: 0.88rem;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <?php if (empty($preview)): ?>
        <!-- DROPZONE PARA SUBIR ARCHIVO -->
        <div class="glass-card" style="padding: 3.5rem 2rem; text-align: center; border: 2px dashed var(--border-accent);">
            <span style="font-size: 3rem; color: var(--accent-gold); display: block; margin-bottom: 1rem;">📑</span>
            <h2 style="font-family: var(--font-luxury); font-size: 1.35rem; text-transform: uppercase; margin-bottom: 0.5rem;">Cargar Archivo Excel (.xlsx / .csv)</h2>
            <p style="color: var(--text-secondary); max-width: 500px; margin: 0 auto 2rem; font-size: 0.9rem;">
                Sube tu hoja de cálculo con el catálogo de autos. El sistema validará los SKUs, precios y stock automáticamente antes de ingresarlos.
            </p>

            <form method="POST" action="/admin/import" enctype="multipart/form-data" style="display: inline-flex; flex-direction: column; align-items: center; gap: 1.5rem;">
                <input type="file" name="excel_file" accept=".xlsx, .csv, .xls" required style="background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.75rem 1.5rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.85rem; cursor: pointer;">
                
                <button type="submit" style="background: var(--accent-gold); color: #090B0E; border: none; padding: 0.9rem 2.5rem; font-size: 0.85rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; border-radius: var(--radius-pill); cursor: pointer; box-shadow: 0 4px 15px var(--accent-glow);">
                    Analizar y Previsualizar Archivo &rarr;
                </button>
            </form>
        </div>
    <?php else: ?>
        <!-- VISTA PREVIA DE AUDITORÍA -->
        <section class="metrics-grid" style="margin-bottom: 2rem;">
            <article class="glass-card">
                <span class="metric-title">Filas Procesadas</span>
                <div class="metric-value"><?= (int)$preview['total_processed'] ?></div>
            </article>
            <article class="glass-card">
                <span class="metric-title">Modelos Válidos para Importar</span>
                <div class="metric-value" style="color: #81C784;"><?= count($preview['valid']) ?></div>
            </article>
            <article class="glass-card">
                <span class="metric-title">Errores Detectados</span>
                <div class="metric-value" style="color: <?= count($preview['invalid']) > 0 ? '#E57373' : 'var(--text-muted)' ?>;">
                    <?= count($preview['invalid']) ?>
                </div>
            </article>
        </section>

        <?php if (!empty($preview['invalid'])): ?>
            <div style="background: rgba(139,30,36,0.25); border: 1px solid #8B1E24; color: #FFB4B6; padding: 1.5rem; border-radius: var(--radius-card); margin-bottom: 2rem;">
                <h3 style="font-size: 0.95rem; font-weight: 700; text-transform: uppercase; margin-bottom: 0.75rem;">Errores que impiden la importación de ciertas filas:</h3>
                <ul style="padding-left: 1.5rem; font-size: 0.85rem; display: flex; flex-direction: column; gap: 0.35rem;">
                    <?php foreach ($preview['invalid'] as $inv): ?>
                        <?php foreach ($inv['errors'] as $err): ?>
                            <li><?= htmlspecialchars($err) ?></li>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Tabla de Modelos Válidos -->
        <div class="table-panel" style="margin-bottom: 2rem;">
            <div class="table-panel-header">
                <h2 class="table-panel-title">Modelos Listos para Ingresar a Base de Datos</h2>
            </div>
            <table class="luxury-table">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Modelo</th>
                        <th>Marca</th>
                        <th>Categoría</th>
                        <th>Escala</th>
                        <th>Precio</th>
                        <th>Stock Inicial</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($preview['valid'] as $item): ?>
                        <tr>
                            <td style="font-family: monospace; color: var(--accent-gold); font-weight: 700;"><?= htmlspecialchars($item['sku']) ?></td>
                            <td><strong style="color: var(--text-primary);"><?= htmlspecialchars($item['name']) ?></strong></td>
                            <td><?= htmlspecialchars($item['brand']) ?></td>
                            <td><?= htmlspecialchars($item['category']) ?></td>
                            <td><?= htmlspecialchars($item['scale']) ?></td>
                            <td style="font-weight: 700; color: var(--text-primary);">S/ <?= number_format((float)$item['price'], 2) ?></td>
                            <td style="font-weight: 600;"><?= (int)$item['stock'] ?> unid.</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Botones de Acción de Confirmación -->
        <div style="display: flex; justify-content: flex-end; gap: 1.25rem;">
            <a href="/admin/import/cancel" style="padding: 0.85rem 1.75rem; border: 1px solid var(--border-glass); color: var(--text-secondary); text-decoration: none; border-radius: var(--radius-pill); font-size: 0.82rem; font-weight: 700; text-transform: uppercase;">
                Cancelar
            </a>
            <form method="POST" action="/admin/import/confirm">
                <button type="submit" style="padding: 0.85rem 2.5rem; background: var(--accent-gold); color: #090B0E; border: none; border-radius: var(--radius-pill); font-size: 0.82rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; cursor: pointer; box-shadow: 0 4px 15px var(--accent-glow);">
                    Confirmar e Importar <?= count($preview['valid']) ?> Modelos &rarr;
                </button>
            </form>
        </div>
    <?php endif; ?>
</div>