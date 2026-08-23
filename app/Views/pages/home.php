<!-- HERO -->
<section style="border-bottom: 1px solid var(--border-glass); padding: 6rem 0; background: radial-gradient(circle at center, rgba(197,168,128,0.06) 0%, var(--bg-primary) 70%); text-align: center;">
    <div class="container">
        <span class="badge" style="border-color: var(--accent-gold); color: var(--accent-gold); margin-bottom: 1.25rem;">
            Ediciones Limitadas &bull; Escala 1:64
        </span>
        <h1 style="font-family: var(--font-luxury); font-size: 3.2rem; letter-spacing: 3px; text-transform: uppercase; margin-bottom: 1rem; color: var(--text-primary); line-height: 1.15;">
            Precisión en Cada Escala
        </h1>
        <p style="color: var(--text-secondary); max-width: 620px; margin: 0 auto 2.5rem; font-size: 1.1rem; line-height: 1.7;">
            Boutique especializada en vehículos a escala diecast de alto nivel. Modelos seleccionados, piezas con neumáticos de goma, chasis de metal y ediciones especiales.
        </p>
        <div style="display: flex; gap: 1rem; justify-content: center;">
            <a href="/shop" class="btn btn-primary" style="background: var(--accent-gold); color: #090B0E; font-weight: 700; border-radius: var(--radius-btn);">Explorar Catálogo</a>
            <a href="/shop?category=jdm-specials" class="btn btn-outline" style="border-radius: var(--radius-btn);">Colección JDM</a>
        </div>
    </div>
</section>

<!-- CATEGORÍAS EN GRID -->
<section class="container" style="padding: 4rem 0;">
    <div style="text-align: center; margin-bottom: 3rem;">
        <span style="font-size: 0.72rem; letter-spacing: 2px; text-transform: uppercase; color: var(--accent-gold); font-weight: 700;">Curaduría Automotriz</span>
        <h2 style="font-family: var(--font-luxury); font-size: 1.85rem; letter-spacing: 2px; text-transform: uppercase; margin-top: 0.3rem;">Líneas & Categorías</h2>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
        <?php foreach ($categories as $cat): ?>
            <a href="/shop?category=<?= htmlspecialchars($cat['slug']) ?>" class="glass-card" style="text-align: center; text-decoration: none; padding: 2rem 1.5rem; display: block;">
                <span style="font-family: var(--font-luxury); font-size: 1.1rem; font-weight: 700; color: var(--text-primary); display: block; margin-bottom: 0.5rem;">
                    <?= htmlspecialchars($cat['name']) ?>
                </span>
                <span style="font-size: 0.75rem; color: var(--accent-gold); letter-spacing: 1px; text-transform: uppercase;">Explorar &rarr;</span>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<!-- MODELOS DESTACADOS DINÁMICOS -->
<section style="background: var(--bg-secondary); border-top: 1px solid var(--border-glass); border-bottom: 1px solid var(--border-glass); padding: 4.5rem 0;">
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2.5rem;">
            <div>
                <span style="font-size: 0.72rem; letter-spacing: 2px; text-transform: uppercase; color: var(--accent-gold); font-weight: 700;">Colección Principal</span>
                <h2 style="font-family: var(--font-luxury); font-size: 1.85rem; letter-spacing: 2px; text-transform: uppercase; margin-top: 0.3rem;">Piezas Destacadas</h2>
            </div>
            <a href="/shop" style="color: var(--text-muted); font-size: 0.85rem; text-decoration: none; text-transform: uppercase; letter-spacing: 1px;">Ver Todo &rarr;</a>
        </div>

        <div class="products-grid">
            <?php foreach ($featuredProducts as $item): ?>
                <article class="product-card glass-card" style="padding: 0; overflow: hidden; border-radius: var(--radius-card);">
                    <div class="product-card-media" style="position: relative; background: var(--bg-primary); padding: 2rem; text-align: center; border-bottom: 1px solid var(--border-glass);">
                        <span class="badge" style="position: absolute; top: 12px; left: 12px; font-size: 0.65rem; border-color: var(--accent-gold); color: var(--accent-gold);">
                            <?= htmlspecialchars($item['scale']) ?>
                        </span>
                        <div style="padding: 1.5rem 0; font-family: var(--font-luxury); font-size: 0.85rem; color: var(--text-muted); letter-spacing: 1px;">
                            [ FOTO MODELO DIECAST ]
                        </div>
                    </div>
                    <div class="product-card-body" style="padding: 1.5rem;">
                        <span class="product-brand" style="color: var(--accent-gold); font-size: 0.72rem; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; display: block; margin-bottom: 0.35rem;">
                            <?= htmlspecialchars($item['brand_name']) ?>
                        </span>
                        <h3 class="product-title" style="font-size: 1rem; margin-bottom: 1.25rem;">
                            <a href="/product?sku=<?= urlencode($item['sku']) ?>" style="color: var(--text-primary); text-decoration: none;">
                                <?= htmlspecialchars($item['name']) ?>
                            </a>
                        </h3>
                        <div class="product-price-row">
                            <span class="product-price" style="font-family: var(--font-luxury); font-size: 1.25rem; font-weight: 700; color: var(--text-primary);">
                                <?= $item['price_formatted'] ?>
                            </span>
                            <button class="btn btn-outline" style="padding: 0.4rem 0.9rem; font-size: 0.75rem; border-radius: var(--radius-pill);" onclick="addToBag(<?= htmlspecialchars(json_encode($item)) ?>)">
                                + Bolsa
                            </button>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- BRAND STORY & BENEFICIOS -->
<section class="container" style="padding: 5rem 0;">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem;">
        <div class="glass-card" style="padding: 2rem;">
            <h3 style="font-family: var(--font-luxury); font-size: 1.15rem; color: var(--accent-gold); margin-bottom: 0.75rem; text-transform: uppercase;">Empaque Blindado</h3>
            <p style="color: var(--text-secondary); font-size: 0.88rem; line-height: 1.6;">Todos los pedidos se despachan con cajas reforzadas y protección burbuja para asegurar que blisters y tarjetas lleguen en estado impecable.</p>
        </div>
        <div class="glass-card" style="padding: 2rem;">
            <h3 style="font-family: var(--font-luxury); font-size: 1.15rem; color: var(--accent-gold); margin-bottom: 0.75rem; text-transform: uppercase;">Garantía de Autenticidad</h3>
            <p style="color: var(--text-secondary); font-size: 0.88rem; line-height: 1.6;">Modelos 100% genuinos directamente de distribuidores autorizados de Mattel, TSM Model Mini GT, Matchbox y Takara Tomy.</p>
        </div>
        <div class="glass-card" style="padding: 2rem;">
            <h3 style="font-family: var(--font-luxury); font-size: 1.15rem; color: var(--accent-gold); margin-bottom: 0.75rem; text-transform: uppercase;">Envíos a Todo el Perú</h3>
            <p style="color: var(--text-secondary); font-size: 0.88rem; line-height: 1.6;">Cobertura a Lima y provincias con código de seguimiento en tiempo real para cada coleccionista.</p>
        </div>
    </div>
</section>

<script>
function addToBag(product) {
    Cart.addItem(product, 1);
    Toast.show(`"${product.name}" agregado a tu bolsa de compras.`);
}
</script>