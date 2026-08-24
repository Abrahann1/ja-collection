const Cart = {
    storageKey: "ja_cart_items",

    getItems() {
        try {
            return JSON.parse(localStorage.getItem(this.storageKey) || "[]");
        } catch {
            return [];
        }
    },

    saveItems(items) {
        localStorage.setItem(this.storageKey, JSON.stringify(items));
        this.updateBadge();
        this.renderCartView();
    },

    addItem(product, quantity = 1) {
        let items = this.getItems();
        const index = items.findIndex(item => item.id === product.id || item.sku === product.sku);

        if (index > -1) {
            items[index].quantity += quantity;
        } else {
            items.push({
                id: product.id,
                sku: product.sku,
                name: product.name,
                price: parseFloat(product.price),
                scale: product.scale || "1:64",
                brand_name: product.brand_name || "",
                quantity: quantity
            });
        }

        this.saveItems(items);
    },

    updateQuantity(index, delta) {
        let items = this.getItems();
        if (!items[index]) return;

        items[index].quantity += delta;
        if (items[index].quantity <= 0) {
            items.splice(index, 1);
        }

        this.saveItems(items);
    },

    removeItem(index) {
        let items = this.getItems();
        items.splice(index, 1);
        this.saveItems(items);
        if (typeof Toast !== "undefined") {
            Toast.show("Modelo eliminado de la bolsa.", "info");
        }
    },

    getSubtotal() {
        return this.getItems().reduce((acc, item) => acc + (item.price * item.quantity), 0);
    },

    getCount() {
        return this.getItems().reduce((acc, item) => acc + item.quantity, 0);
    },

    updateBadge() {
        const badge = document.getElementById("cart-count-badge");
        if (badge) {
            badge.textContent = this.getCount();
        }
    },

    renderCartView() {
        const tbody = document.getElementById("cart-items-tbody");
        const emptyState = document.getElementById("cart-empty-state");
        const cartContent = document.getElementById("cart-content-grid");
        const subtotalEl = document.getElementById("cart-subtotal-val");
        const totalEl = document.getElementById("cart-total-val");

        if (!tbody) return;

        const items = this.getItems();

        if (items.length === 0) {
            if (cartContent) cartContent.style.display = "none";
            if (emptyState) emptyState.style.display = "block";
            return;
        }

        if (cartContent) cartContent.style.display = "grid";
        if (emptyState) emptyState.style.display = "none";

        tbody.innerHTML = items.map((item, idx) => `
            <tr>
                <td>
                    <div style="font-family: monospace; font-size: 0.78rem; color: var(--accent-gold); font-weight: 700;">${item.sku}</div>
                    <strong style="color: var(--text-primary); font-size: 0.95rem; display: block; margin: 0.2rem 0;">${item.name}</strong>
                    <span style="font-size: 0.75rem; color: var(--text-muted);">Escala ${item.scale} &bull; ${item.brand_name}</span>
                </td>
                <td style="font-weight: 600; color: var(--text-primary); white-space: nowrap;">
                    S/ ${item.price.toFixed(2)}
                </td>
                <td>
                    <div class="qty-control" style="transform: scale(0.9); margin: 0;">
                        <button type="button" class="qty-btn" onclick="Cart.updateQuantity(${idx}, -1)">&minus;</button>
                        <span class="qty-display" style="display:inline-block; line-height:32px;">${item.quantity}</span>
                        <button type="button" class="qty-btn" onclick="Cart.updateQuantity(${idx}, 1)">&plus;</button>
                    </div>
                </td>
                <td style="font-family: var(--font-luxury); font-weight: 700; font-size: 1.1rem; color: var(--text-primary); white-space: nowrap;">
                    S/ ${(item.price * item.quantity).toFixed(2)}
                </td>
                <td style="text-align: right;">
                    <button class="btn-remove-item" onclick="Cart.removeItem(${idx})" title="Eliminar">&times;</button>
                </td>
            </tr>
        `).join("");

        const subtotal = this.getSubtotal();
        if (subtotalEl) subtotalEl.textContent = `S/ ${subtotal.toFixed(2)}`;
        if (totalEl) totalEl.textContent = `S/ ${subtotal.toFixed(2)}`;
    }
};

window.proceedToCheckout = function() {
    const items = Cart.getItems();
    if (items.length === 0) {
        if (typeof Toast !== "undefined") Toast.show("Tu bolsa está vacía.", "info");
        return;
    }
    window.location.href = "/checkout";
};

document.addEventListener("DOMContentLoaded", () => {
    Cart.updateBadge();
    Cart.renderCartView();
});