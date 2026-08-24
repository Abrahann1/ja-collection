<div class="container">
    <div style="padding: 1.75rem 0 0.5rem; font-size: 0.8rem; color: var(--text-muted);">
        <a href="/" style="color: var(--text-muted);">Inicio</a> / 
        <a href="/shop" style="color: var(--text-muted);">Catálogo</a> / 
        <a href="/shop?brand=<?= htmlspecialchars($product['brand_slug']) ?>" style="color: var(--text-muted);"><?= htmlspecialchars($product['brand_name']) ?></a> / 
        <span style="color: var(--text-primary);"><?= htmlspecialchars($product['name']) ?></span>
    </div>

    <!-- Ficha Principal -->
    <div class="product-detail-view">
        <div class="detail-showcase">
            <span class="scale-tag" style="position: absolute; top: 1.25rem; left: 1.25rem;">
                Escala <?= htmlspecialchars($product['scale']) ?>
            </span>
            
            <div style="text-align: center;">
                <span style="font-family: var(--font-luxury); font-size: 1.35rem; letter-spacing: 2px; color: var(--accent-gold); display: block; margin-bottom: 0.4rem;">[ <?= htmlspecialchars($product['sku']) ?> ]</span>
                <span style="font-size: 0.82rem; color: var(--text-muted); letter-spacing: 1px; text-transform: uppercase;">Exhibición de Modelo Diecast</span>
            </div>
        </div>

        <div class="detail-info">
            <span class="detail-brand-badge"><?= htmlspecialchars($product['brand_name']) ?> &bull; <?= htmlspecialchars($product['category_name']) ?></span>
            <h1 class="detail-title"><?= htmlspecialchars($product['name']) ?></h1>

            <div class="detail-price-row">
                <span class="detail-price"><?= $product['price_formatted'] ?></span>
                <?php if ($product['old_price_formatted']): ?>
                    <span style="font-size: 1.1rem; color: var(--text-muted); text-decoration: line-through;"><?= $product['old_price_formatted'] ?></span>
                <?php endif; ?>

                <span class="detail-stock-status" style="<?= (int)$product['stock_available'] > 0 ? 'background: rgba(46,125,50,0.15); border: 1px solid rgba(76,175,80,0.35); color: #81C784;' : 'background: rgba(229,115,115,0.15); border: 1px solid rgba(229,115,115,0.35); color: #E57373;' ?>">
                    <?= (int)$product['stock_available'] > 0 ? ((int)$product['stock_available'] <= (int)$product['minimum_stock'] ? 'Últimas ' . $product['stock_available'] . ' unid.' : 'En Stock (' . $product['stock_available'] . ')') : 'Agotado' ?>
                </span>
            </div>

            <p style="color: var(--text-secondary); line-height: 1.7; font-size: 0.92rem; margin-bottom: 1.5rem;">
                <?= nl2br(htmlspecialchars($product['description'] ?: 'Modelo de alta precisión para coleccionistas. Fabricado con cuerpo diecast de metal, neumáticos de goma de alto detalle y acabado de pintura automotriz.')) ?>
            </p>

            <div class="specs-container">
                <div class="spec-card">
                    <span class="lbl">Código SKU</span>
                    <span class="val" style="font-family: monospace; color: var(--accent-gold);"><?= htmlspecialchars($product['sku']) ?></span>
                </div>
                <div class="spec-card">
                    <span class="lbl">Escala</span>
                    <span class="val"><?= htmlspecialchars($product['scale']) ?></span>
                </div>
                <div class="spec-card">
                    <span class="lbl">Fabricante</span>
                    <span class="val"><?= htmlspecialchars($product['brand_name']) ?></span>
                </div>
                <div class="spec-card">
                    <span class="lbl">Categoría</span>
                    <span class="val"><?= htmlspecialchars($product['category_name']) ?></span>
                </div>
            </div>

            <div class="action-row">
                <?php if ((int)$product['stock_available'] > 0): ?>
                    <div class="qty-control">
                        <button type="button" class="qty-btn" onclick="changeQty(-1)">&minus;</button>
                        <input type="text" id="qty-input" class="qty-display" value="1" readonly>
                        <button type="button" class="qty-btn" onclick="changeQty(1)">&plus;</button>
                    </div>

                    <button class="btn-main-buy" onclick="handleDetailAdd()">
                        AGREGAR AL CARRITO
                    </button>
                <?php else: ?>
                    <button class="btn-main-buy" disabled style="background: #333; color: #777; cursor: not-allowed;">
                        MODELO AGOTADO
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- SECCIÓN DE RECOMENDACIONES / COMPLETAR COLECCIÓN -->
    <?php if (!empty($relatedProducts)): ?>
        <section style="border-top: 1px solid var(--border-glass); padding-top: 3.5rem; margin-top: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2.5rem;">
                <div>
                    <span style="font-size: 0.72rem; letter-spacing: 2px; text-transform: uppercase; color: var(--accent-gold); font-weight: 700;">Curaduría Relacionada</span>
                    <h2 style="font-family: var(--font-luxury); font-size: 1.75rem; letter-spacing: 2px; text-transform: uppercase; margin-top: 0.25rem;">Completa tu Colección</h2>
                </div>
                <a href="/shop" style="color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 600;">Ver Catálogo Completo &rarr;</a>
            </div>

            <div class="products-grid">
                <?php foreach ($relatedProducts as $rel): ?>
                    <article class="product-card">
                        <div class="product-media">
                            <span class="scale-tag">1:64</span>
                            <div class="diecast-silhouette">
                                <span style="color: var(--accent-gold); font-size: 1rem; display: block; margin-bottom: 0.2rem;">✦</span>
                                <span><?= htmlspecialchars($rel['sku']) ?></span>
                            </div>
                        </div>
                        
                        <div class="product-body">
                            <span class="product-brand"><?= htmlspecialchars($rel['brand_name']) ?> &bull; <?= htmlspecialchars($rel['category_name']) ?></span>
                            <h3 class="product-title">
                                <a href="/product?sku=<?= urlencode($rel['sku']) ?>">
                                    <?= htmlspecialchars($rel['name']) ?>
                                </a>
                            </h3>
                            
                            <div class="product-footer">
                                <span class="product-price"><?= $rel['price_formatted'] ?></span>
                                <button class="btn-add" onclick="handleRelAdd(<?= htmlspecialchars(json_encode($rel)) ?>)">
                                    + Agregar
                                </button>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
</div>

<script>
let currentQty = 1;
const maxStock = <?= (int)$product['stock_available'] ?>;

function changeQty(delta) {
    currentQty = Math.max(1, Math.min(maxStock, currentQty + delta));
    document.getElementById("qty-input").value = currentQty;
}

function handleDetailAdd() {
    const prod = <?= json_encode($product) ?>;
    Cart.addItem(prod, currentQty);
    Toast.show(`"${prod.name}" (${currentQty} unid.) agregado al carrito.`);
}

function handleRelAdd(prod) {
    Cart.addItem(prod, 1);
    Toast.show(`"${prod.name}" agregado al carrito.`);
}
</script>