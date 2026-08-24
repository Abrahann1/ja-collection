const LiveShop = {
    state: {
        category: "",
        brand: "",
        scale: "",
        sort: "newest",
        search: "",
        page: 1
    },

    init() {
        const urlParams = new URLSearchParams(window.location.search);
        this.state.category = urlParams.get("category") || "";
        this.state.brand = urlParams.get("brand") || "";
        this.state.scale = urlParams.get("scale") || "";
        this.state.sort = urlParams.get("sort") || "newest";
        this.state.search = urlParams.get("q") || "";

        this.bindEvents();
    },

    bindEvents() {
        // Interceptar clics en Píldoras de Categoría
        document.querySelectorAll(".filter-pill").forEach(pill => {
            pill.addEventListener("click", (e) => {
                e.preventDefault();
                const url = new URL(pill.href);
                const cat = url.searchParams.get("category") || "";
                this.setFilter("category", cat);
            });
        });

        // Búsqueda en tiempo real con debounce
        const searchInput = document.querySelector(".luxury-search-input");
        if (searchInput) {
            let debounceTimer;
            searchInput.addEventListener("input", (e) => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    this.state.search = e.target.value.trim();
                    this.state.page = 1;
                    this.fetchAndRender();
                }, 250);
            });
        }

        // Interceptar cambios en Selectores de Marca, Escala y Orden
        document.querySelectorAll(".luxury-select").forEach(select => {
            select.addEventListener("change", (e) => {
                const name = select.name;
                const val = select.value;
                if (name === "brand") this.state.brand = val;
                if (name === "scale") this.state.scale = val;
                if (name === "sort") this.state.sort = val;
                this.state.page = 1;
                this.fetchAndRender();
            });
        });

        // Botón Limpiar Filtros
        const clearBtn = document.querySelector(".btn-clear-filters");
        if (clearBtn) {
            clearBtn.addEventListener("click", (e) => {
                e.preventDefault();
                this.state = { category: "", brand: "", scale: "", sort: "newest", search: "", page: 1 };
                if (searchInput) searchInput.value = "";
                document.querySelectorAll(".luxury-select").forEach(s => s.value = s.name === "sort" ? "newest" : "");
                this.fetchAndRender();
            });
        }
    },

    setFilter(key, value) {
        this.state[key] = value;
        this.state.page = 1;
        this.updatePillsUI();
        this.fetchAndRender();
    },

    updatePillsUI() {
        document.querySelectorAll(".filter-pill").forEach(pill => {
            const url = new URL(pill.href);
            const cat = url.searchParams.get("category") || "";
            if (cat === this.state.category) {
                pill.classList.add("active");
            } else {
                pill.classList.remove("active");
            }
        });
    },

    async fetchAndRender() {
        const grid = document.getElementById("shop-products-grid");
        const countText = document.getElementById("shop-count-text");
        if (!grid) return;

        grid.style.opacity = "0.4";
        grid.style.transition = "opacity 0.2s ease";

        const params = new URLSearchParams();
        if (this.state.category) params.set("category", this.state.category);
        if (this.state.brand) params.set("brand", this.state.brand);
        if (this.state.scale) params.set("scale", this.state.scale);
        if (this.state.sort) params.set("sort", this.state.sort);
        if (this.state.search) params.set("q", this.state.search);
        params.set("page", this.state.page);

        // Actualizar URL del navegador sin recargar
        const newUrl = window.location.pathname + (params.toString() ? "?" + params.toString() : "");
        window.history.pushState(null, "", newUrl);

        try {
            const res = await fetch("/api/products?" + params.toString());
            const json = await res.json();

            grid.style.opacity = "1";

            if (!json.success || !json.data || json.data.length === 0) {
                grid.innerHTML = `
                    <div style="grid-column: 1 / -1; text-align: center; padding: 5rem 2rem; background: var(--bg-card); border: 1px solid var(--border-glass); border-radius: var(--radius-card);">
                        <p style="color: var(--text-muted); font-size: 1rem; margin-bottom: 1.25rem;">No se encontraron modelos con los filtros seleccionados.</p>
                        <button onclick="LiveShop.setFilter('category', ''); LiveShop.setFilter('brand', '');" style="background: transparent; border: none; color: var(--accent-gold); font-size: 0.85rem; text-transform: uppercase; font-weight: 700; cursor: pointer;">
                            &larr; Ver todos los modelos
                        </button>
                    </div>
                `;
                if (countText) countText.textContent = "Mostrando 0 modelos";
                return;
            }

            if (countText) {
                countText.textContent = `Mostrando ${json.data.length} de ${json.pagination.total_items} modelos disponibles`;
            }

            grid.innerHTML = json.data.map(item => `
                <article class="product-card">
                    <div class="product-media">
                        <span class="scale-tag">Escala ${item.scale}</span>
                        <div class="diecast-silhouette">
                            <span style="color: var(--accent-gold); font-size: 1rem; display: block; margin-bottom: 0.2rem;">✦</span>
                            <span>${item.sku}</span>
                        </div>
                    </div>
                    <div class="product-body">
                        <span class="product-brand">${item.brand_name} &bull; ${item.category_name}</span>
                        <h3 class="product-title">
                            <a href="/product?sku=${encodeURIComponent(item.sku)}">
                                ${item.name}
                            </a>
                        </h3>
                        <div class="product-footer">
                            <span class="product-price">${item.price_formatted}</span>
                            <button class="btn-add" onclick="handleShopAdd(${JSON.stringify(item).replace(/"/g, '&quot;')})">
                                + Agregar
                            </button>
                        </div>
                    </div>
                </article>
            `).join("");

        } catch (err) {
            grid.style.opacity = "1";
            console.error("Error al filtrar productos en vivo:", err);
        }
    }
};

document.addEventListener("DOMContentLoaded", () => {
    if (document.getElementById("shop-products-grid")) {
        LiveShop.init();
    }
});