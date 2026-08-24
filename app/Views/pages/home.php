<!-- HERO -->
<section style="border-bottom: 1px solid var(--border-glass); padding: 6rem 0 5rem; background: radial-gradient(circle at center, rgba(197, 168, 128, 0.07) 0%, var(--bg-primary) 75%); text-align: center;">
    <div class="container">
        <span style="display: inline-block; padding: 0.35rem 1rem; border: 1px solid var(--accent-gold); color: var(--accent-gold); font-size: 0.72rem; letter-spacing: 2.5px; text-transform: uppercase; border-radius: var(--radius-pill); margin-bottom: 1.5rem;">
            Coleccionismo Automotriz de Precisión
        </span>
        <h1 style="font-family: var(--font-luxury); font-size: 3.2rem; letter-spacing: 3px; text-transform: uppercase; margin-bottom: 1.25rem; color: var(--text-primary); line-height: 1.15;">
            Precisión en Cada Escala
        </h1>
        <p style="color: var(--text-secondary); max-width: 640px; margin: 0 auto 2.5rem; font-size: 1.08rem; line-height: 1.75;">
            Piezas seleccionadas con base y carrocería de metal, neumáticos de goma Real Riders y cajas acrílicas de exhibición.
        </p>
        <div style="display: flex; gap: 1.25rem; justify-content: center; flex-wrap: wrap;">
            <a href="/shop" style="background: var(--accent-gold); color: #090B0E; padding: 0.85rem 2.25rem; font-size: 0.82rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; border-radius: var(--radius-pill); box-shadow: 0 4px 20px var(--accent-glow);">
                Explorar Catálogo
            </a>
            <a href="/shop?category=jdm-specials" style="background: var(--bg-card); border: 1px solid var(--border-glass); color: var(--text-primary); padding: 0.85rem 2.25rem; font-size: 0.82rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; border-radius: var(--radius-pill);">
                Colección JDM
            </a>
        </div>
    </div>
</section>

<!-- CATEGORÍAS -->
<section class="container" style="padding: 4.5rem 0 3rem;">
    <div style="text-align: center; margin-bottom: 2.75rem;">
        <span style="font-size: 0.72rem; letter-spacing: 2px; text-transform: uppercase; color: var(--accent-gold); font-weight: 700;">Curaduría Oficial</span>
        <h2 style="font-family: var(--font-luxury); font-size: 1.85rem; letter-spacing: 2px; text-transform: uppercase; margin-top: 0.3rem;">Categorías Principales</h2>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem;">
        <?php foreach ($categories as $cat): ?>
            <a href="/shop?category=<?= htmlspecialchars($cat['slug']) ?>" class="product-card" style="padding: 1.75rem 1.25rem; text-align: center; text-decoration: none;">
                <span style="font-family: var(--font-luxury); font-size: 1.05rem; font-weight: 700; color: var(--text-primary); display: block; margin-bottom: 0.35rem;">
                    <?= htmlspecialchars($cat['name']) ?>
                </span>
                <span style="font-size: 0.72rem; color: var(--accent-gold); letter-spacing: 1.5px; text-transform: uppercase; font-weight: 600;">Ver Colección &rarr;</span>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<!-- MODELOS EN CUADRÍCULA DE 4 COLUMNAS -->
<section style="background: var(--bg-secondary); border-top: 1px solid var(--border-glass); border-bottom: 1px solid var(--border-glass); padding: 4.5rem 0;">
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2.5rem;">
            <div>
                <span style="font-size: 0.72rem; letter-spacing: 2px; text-transform: uppercase; color: var(--accent-gold); font-weight: 700;">Selección de Alta Gama</span>
                <h2 style="font-family: var(--font-luxury); font-size: 1.85rem; letter-spacing: 2px; text-transform: uppercase; margin-top: 0.3rem;">Modelos Disponibles</h2>
            </div>
            <a href="/shop" style="color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 600;">Ver Catálogo Completo &rarr;</a>
        </div>

        <div class="products-grid">
            <?php foreach ($featuredProducts as $item): ?>
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
                            <button class="btn-add" onclick="handleQuickAdd(<?= htmlspecialchars(json_encode($item)) ?>)">
                                + Agregar
                            </button>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- BENEFICIOS -->
<section class="container" style="padding: 4.5rem 0;">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem;">
        <div class="product-card" style="padding: 2rem;">
            <span style="font-size: 1.5rem; color: var(--accent-gold); display: block; margin-bottom: 0.75rem;">🛡️</span>
            <h3 style="font-family: var(--font-luxury); font-size: 1.05rem; color: var(--text-primary); margin-bottom: 0.4rem; text-transform: uppercase;">Empaque Blindado</h3>
            <p style="color: var(--text-secondary); font-size: 0.85rem; line-height: 1.6;">Cajas de alta resistencia con protección burbuja para asegurar que blisters y tarjetas lleguen sin dobleces.</p>
        </div>
        <div class="product-card" style="padding: 2rem;">
            <span style="font-size: 1.5rem; color: var(--accent-gold); display: block; margin-bottom: 0.75rem;">💎</span>
            <h3 style="font-family: var(--font-luxury); font-size: 1.05rem; color: var(--text-primary); margin-bottom: 0.4rem; text-transform: uppercase;">Piezas Originales</h3>
            <p style="color: var(--text-secondary); font-size: 0.85rem; line-height: 1.6;">Modelos 100% genuinos directamente de distribuidores autorizados de Mattel, Mini GT y Takara Tomy.</p>
        </div>
        <div class="product-card" style="padding: 2rem;">
            <span style="font-size: 1.5rem; color: var(--accent-gold); display: block; margin-bottom: 0.75rem;">🚀</span>
            <h3 style="font-family: var(--font-luxury); font-size: 1.05rem; color: var(--text-primary); margin-bottom: 0.4rem; text-transform: uppercase;">Envíos con Tracking</h3>
            <p style="color: var(--text-secondary); font-size: 0.85rem; line-height: 1.6;">Despacho rápido a Lima y provincias con código de seguimiento en tiempo real para tu tranquilidad.</p>
        </div>
    </div>
</section>

<script>
function handleQuickAdd(prod) {
    Cart.addItem(prod, 1);
    Toast.show(`"${prod.name}" agregado al carrito.`);
}
</script>