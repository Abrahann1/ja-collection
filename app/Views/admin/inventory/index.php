<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; border-bottom: 1px solid var(--border-glass); padding-bottom: 1rem;">
    <div>
        <span style="font-size: 0.72rem; letter-spacing: 2px; text-transform: uppercase; color: var(--accent-gold); font-weight: 700;">Auditoría de Stock</span>
        <h1 style="font-family: var(--font-luxury); font-size: 1.85rem; letter-spacing: 2px; text-transform: uppercase; margin-top: 0.25rem;">Control de Inventario & Kardex</h1>
    </div>
</div>

<!-- Tabla de Inventario -->
<div class="table-panel" style="margin-bottom: 3.5rem;">
    <div class="table-panel-header">
        <h2 class="table-panel-title">Estado de Stock Físico</h2>
    </div>
    <table class="luxury-table">
        <thead>
            <tr>
                <th>SKU</th>
                <th>Modelo</th>
                <th>Marca / Cat</th>
                <th>Stock Físico</th>
                <th>Reservado</th>
                <th>Disponible</th>
                <th>Estado</th>
                <th style="text-align: right;">Ajuste Rápido</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($inventory as $inv): ?>
                <tr id="inv-row-<?= $inv['product_id'] ?>">
                    <td style="font-family: monospace; font-size: 0.85rem; color: var(--accent-gold); font-weight: 700;"><?= htmlspecialchars($inv['sku']) ?></td>
                    <td><strong style="color: var(--text-primary);"><?= htmlspecialchars($inv['name']) ?></strong></td>
                    <td><?= htmlspecialchars($inv['brand_name']) ?> &bull; <?= htmlspecialchars($inv['category_name']) ?></td>
                    <td style="font-weight: 700;" class="stock-current-cell"><?= (int)$inv['stock_current'] ?> unid.</td>
                    <td style="color: var(--text-muted);"><?= (int)$inv['stock_reserved'] ?></td>
                    <td style="font-weight: 700; color: var(--accent-gold); font-size: 1rem;" class="stock-avail-cell"><?= (int)$inv['stock_available'] ?></td>
                    <td>
                        <span class="status-pill <?= (int)$inv['stock_available'] > (int)$inv['minimum_stock'] ? 'status-paid' : ((int)$inv['stock_available'] > 0 ? 'status-pending' : '') ?>" style="<?= (int)$inv['stock_available'] <= 0 ? 'background: rgba(229,115,115,0.15); border: 1px solid rgba(229,115,115,0.4); color: #E57373;' : '' ?>">
                            <?= $inv['stock_status'] ?>
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <form method="POST" action="/admin/inventory/adjust" class="ajax-stock-form" style="display: inline-flex; gap: 0.4rem; align-items: center;">
                            <input type="hidden" name="product_id" value="<?= $inv['product_id'] ?>">
                            <input type="hidden" name="type" value="PURCHASE">
                            <input type="hidden" name="reason" value="Ingreso de lote rápido">
                            <input type="number" name="quantity_change" value="5" min="1" max="100" style="width: 55px; background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.35rem; color: var(--text-primary); border-radius: 4px; text-align: center; font-size: 0.85rem;">
                            <button type="submit" style="background: var(--accent-gold); color: #090B0E; border: none; padding: 0.35rem 0.75rem; border-radius: 4px; font-weight: 700; font-size: 0.75rem; cursor: pointer;">
                                + Lote
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Kardex de Movimientos -->
<div class="table-panel">
    <div class="table-panel-header">
        <h2 class="table-panel-title">Kardex / Historial de Movimientos de Auditoría</h2>
    </div>
    <table class="luxury-table">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Modelo</th>
                <th>Tipo</th>
                <th>Variación</th>
                <th>Stock Anterior &rarr; Posterior</th>
                <th>Motivo / Responsable</th>
            </tr>
        </thead>
        <tbody id="kardex-tbody">
            <?php foreach ($movements as $m): ?>
                <tr>
                    <td style="font-size: 0.8rem; color: var(--text-muted);"><?= htmlspecialchars($m['created_at']) ?></td>
                    <td>
                        <span style="font-family: monospace; color: var(--accent-gold); font-size: 0.78rem;"><?= htmlspecialchars($m['sku'] ?? '') ?></span>
                        <strong style="display: block; color: var(--text-primary); font-size: 0.88rem;"><?= htmlspecialchars($m['product_name'] ?? '') ?></strong>
                    </td>
                    <td>
                        <span style="font-size: 0.72rem; font-weight: 700; letter-spacing: 1px; padding: 0.2rem 0.6rem; border-radius: 4px; background: rgba(255,255,255,0.06);">
                            <?= $m['type'] ?>
                        </span>
                    </td>
                    <td style="font-weight: 700; font-size: 0.95rem; color: <?= (int)$m['quantity'] >= 0 ? '#81C784' : '#E57373' ?>;">
                        <?= (int)$m['quantity'] >= 0 ? '+' . $m['quantity'] : $m['quantity'] ?> unid.
                    </td>
                    <td style="font-family: monospace; font-size: 0.85rem;">
                        <?= $m['stock_before'] ?> &rarr; <strong style="color: var(--text-primary);"><?= $m['stock_after'] ?></strong>
                    </td>
                    <td style="font-size: 0.85rem; color: var(--text-secondary);">
                        <?= htmlspecialchars($m['reason']) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
document.querySelectorAll(".ajax-stock-form").forEach(form => {
    form.addEventListener("submit", async (e) => {
        e.preventDefault();
        const formData = new FormData(form);
        const pid = formData.get("product_id");
        const qty = parseInt(formData.get("quantity_change"), 10);

        try {
            const res = await fetch("/admin/inventory/adjust", {
                method: "POST",
                body: formData
            });

            if (res.ok) {
                const row = document.getElementById("inv-row-" + pid);
                if (row) {
                    const currCell = row.querySelector(".stock-current-cell");
                    const availCell = row.querySelector(".stock-avail-cell");
                    const currentVal = parseInt(currCell.textContent, 10);
                    const newVal = currentVal + qty;
                    currCell.textContent = newVal + " unid.";
                    availCell.textContent = newVal;
                }
                if (typeof Toast !== "undefined") {
                    Toast.show(`Stock incrementado en +${qty} unidades.`);
                }
            }
        } catch(err) {
            form.submit();
        }
    });
});
</script>