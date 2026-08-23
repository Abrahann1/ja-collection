<div class="container">
    <div style="padding: 1.5rem 0 0.5rem; font-size: 0.8rem; color: var(--text-muted);">
        <a href="/" style="color: var(--text-muted); text-decoration: none;">Inicio</a> / 
        <a href="/shop" style="color: var(--text-muted); text-decoration: none;">Catálogo</a> / 
        <a href="/shop?brand=<?= htmlspecialchars($product['brand_slug']) ?>" style="color: var(--text-muted); text-decoration: none;"><?= htmlspecialchars($product['brand_name']) ?></a> / 
        <span style="color: var(--text-primary);"><?= htmlspecialchars($product['name']) ?></span>
    </div>

    <div class="product-detail-layout">
        <div class="product-gallery-box">
            <span class="badge" style="position: absolute; top: 1.25rem; left: 1.25rem; border-color: var(--accent-gold); color: var(--accent-gold);">
                Escala <?= htmlspecialchars($product['scale']) ?>
            </span>
            <div style="text-align: center;">
                <span style="font-family: var(--font-luxury); font-size: 1.2rem; color: var(--text-muted); letter-spacing: 2px; display: block; margin-bottom: 0.5rem;">[ <?= htmlspecialchars($product['sku']) ?> ]</span>
                <span style="font-size: 0.85rem; color: var(--text-secondary);">Fotografía en Alta Resolución</span>
            </div>
        </div>

        <div class="product-info-panel">
            <span class="product-brand-tag"><?= htmlspecialchars($product['brand_name']) ?> &bull; <?= htmlspecialchars($product['category_name']) ?></span>
            <h1 class="product-heading"><?= htmlspecialchars($product['name']) ?></h1>

            <div class="product-price-box">
                <span class="price-main"><?= $product['price_formatted'] ?></span>
                <?php if ($product['old_price_formatted']): ?>
                    <span class="price-old"><?= $product['old_price_formatted'] ?></span>
                <?php endif; ?>
                
                <span class="status-pill" style="margin-left: auto; <?= (int)$product['stock_available'] > 0 ? 'background: rgba(46,125,50,0.15); border: 1px solid rgba(76,175,80,0.35); color: #81C784;' : 'background: rgba(229,115,115,0.15); border: 1px solid rgba(229,115,115,0.35); color: #E57373;' ?>">
                    <?= (int)$product['stock_available'] > 0 ? ((int)$product['stock_available'] <= (int)$product['minimum_stock'] ? 'Últimas Unidades (' . $product['stock_available'] . ')' : 'En Stock (' . $product['stock_available'] . ')') : 'Agotado' ?>
                </span>
            </div>

            <p style="color: var(--text-secondary); line-height: 1.7; font-size: 0.95rem; margin-bottom: 1.5rem;">
                <?= nl2br(htmlspecialchars($product['description'] ?: 'Modelo de alta precisión para coleccionistas exigentes. Construcción con carrocería diecast y acabados de pintura automotriz de alta fidelidad.')) ?>
            </p>

            <div class="specs-grid">
                <div class="spec-item">
                    <span class="spec-label">Código SKU</span>
                    <span class="spec-value" style="font-family: monospace; color: var(--accent-gold);"><?= htmlspecialchars($product['sku']) ?></span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Escala</span>
                    <span class="spec-value"><?= htmlspecialchars($product['scale']) ?></span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Fabricante</span>
                    <span class="spec-value"><?= htmlspecialchars($product['brand_name']) ?></span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Categoría</span>
                    <span class="spec-value"><?= htmlspecialchars($product['category_name']) ?></span>
                </div>
            </div>

            <div class="buy-actions">
                <?php if ((int)$product['stock_available'] > 0): ?>
                    <input type="number" id="buy-qty" class="qty-input" value="1" min="1" max="<?= (int)$product['stock_available'] ?>">
                    <button class="btn-add-cart" id="btn-add" onclick="handleAddProduct()">
                        Agregar a la Bolsa
                    </button>
                <?php else: ?>
                    <button class="btn-add-cart" disabled>
                        Modelo Agotado
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function handleAddProduct() {
    const qty = parseInt(document.getElementById("buy-qty").value, 10) || 1;
    const prod = <?= json_encode($product) ?>;
    Cart.addItem(prod, qty);
    Toast.show(`Agregado a la bolsa: ${prod.name} (${qty} unid.)`);
}
</script>