<div class="container" style="padding-top: 2.5rem;">
    <div style="border-bottom: 1px solid var(--border-glass); padding-bottom: 1.25rem; margin-bottom: 2rem;">
        <span style="font-size: 0.72rem; letter-spacing: 2px; text-transform: uppercase; color: var(--accent-gold); font-weight: 700;">Catálogo de Coleccionismo</span>
        <h1 style="font-family: var(--font-luxury); font-size: 2.2rem; letter-spacing: 2px; text-transform: uppercase; margin-top: 0.25rem;">Exhibición de Modelos</h1>
    </div>

    <!-- BARRA DE FILTROS HORIZONTAL EN VIVO -->
    <div class="filter-toolbar-luxury">
        <!-- Píldoras Rápidas Reactivas -->
        <div class="filter-pills-row">
            <a href="/shop" class="filter-pill <?= empty($filters['category']) && empty($filters['brand']) ? 'active' : '' ?>">Todos los Modelos</a>
            <a href="/shop?category=jdm-specials" class="filter-pill <?= ($filters['category'] ?? '') === 'jdm-specials' ? 'active' : '' ?>">JDM Specials</a>
            <a href="/shop?category=supercars" class="filter-pill <?= ($filters['category'] ?? '') === 'supercars' ? 'active' : '' ?>">Supercars</a>
            <a href="/shop?category=premium" class="filter-pill <?= ($filters['category'] ?? '') === 'premium' ? 'active' : '' ?>">Línea Premium</a>
            <a href="/shop?category=muscle" class="filter-pill <?= ($filters['category'] ?? '') === 'muscle' ? 'active' : '' ?>">American Muscle</a>
            <a href="/shop?category=mainline" class="filter-pill <?= ($filters['category'] ?? '') === 'mainline' ? 'active' : '' ?>">Mainline</a>
            <a href="/shop?category=treasure-hunt" class="filter-pill <?= ($filters['category'] ?? '') === 'treasure-hunt' ? 'active' : '' ?>">Treasure Hunt</a>
        </div>

        <!-- Controles en Cápsula (Sin recarga de página) -->
        <div class="filter-controls-row">
            <div class="search-input-wrap">
                <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
                <input type="text" value="<?= htmlspecialchars($filters['search'] ?? '') ?>" placeholder="Buscar Skyline, RX-7, GT3 RS..." class="luxury-search-input">
            </div>

            <select name="brand" class="luxury-select">
                <option value="">Todas las Marcas</option>
                <?php foreach ($brands as $b): ?>
                    <option value="<?= htmlspecialchars($b['slug']) ?>" <?= ($filters['brand'] ?? '') === $b['slug'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($b['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="scale" class="luxury-select">
                <option value="">Todas las Escalas</option>
                <option value="1:64" <?= ($filters['scale'] ?? '') === '1:64' ? 'selected' : '' ?>>Escala 1:64</option>
                <option value="1:43" <?= ($filters['scale'] ?? '') === '1:43' ? 'selected' : '' ?>>Escala 1:43</option>
                <option value="1:24" <?= ($filters['scale'] ?? '') === '1:24' ? 'selected' : '' ?>>Escala 1:24</option>
            </select>

            <select name="sort" class="luxury-select">
                <option value="newest" <?= ($filters['sort'] ?? '') === 'newest' ? 'selected' : '' ?>>Más Recientes</option>
                <option value="price_asc" <?= ($filters['sort'] ?? '') === 'price_asc' ? 'selected' : '' ?>>Precio: Menor a Mayor</option>
                <option value="price_desc" <?= ($filters['sort'] ?? '') === 'price_desc' ? 'selected' : '' ?>>Precio: Mayor a Menor</option>
            </select>

            <button type="button" class="btn-clear-filters" style="border:none; cursor:pointer;" title="Restablecer">&times; Limpiar</button>
        </div>
    </div>

    <div style="margin-bottom: 1.5rem; font-size: 0.85rem; color: var(--text-secondary);" id="shop-count-text">
        Mostrando <?= count($products) ?> de <?= (int)($pagination['total_items'] ?? 0) ?> modelos disponibles
    </div>

    <!-- GRID DE PRODUCTOS REACTIVO -->
    <div class="products-grid" id="shop-products-grid" style="margin-bottom: 5rem;">
        <?php foreach ($products as $item): ?>
            <article class="product-card">
                <div class="product-media">
                    <span class="scale-tag">Escala <?= htmlspecialchars($item['scale']) ?></span>
                    <div class="diecast-silhouette">
                        <span style="color: var(--accent-gold); font-size: 1rem; display: block; margin-bottom: 0.2rem;">✦</span>
                        <span><?= htmlspecialchars($item['sku']) ?></span>
                    </div>
                </div>
                
                <div class="product-body">
                    <span class="product-brand"><?= htmlspecialchars($item['brand_name']) ?> &bull; <?= htmlspecialchars($item['category_name']) ?></span>
                    <h3 class="product-title">
                        <a href="/product?sku=<?= urlencode($item['sku']) ?>">
                            <?= htmlspecialchars($item['name']) ?>
                        </a>
                    </h3>
                    
                    <div class="product-footer">
                        <span class="product-price"><?= $item['price_formatted'] ?></span>
                        <button class="btn-add" onclick="handleShopAdd(<?= htmlspecialchars(json_encode($item)) ?>)">
                            + Agregar
                        </button>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</div>

<script src="/assets/js/shop.js"></script>
<script>
function handleShopAdd(prod) {
    Cart.addItem(prod, 1);
    Toast.show(`"${prod.name}" agregado al carrito.`);
}
</script>