<div class="container" style="padding-top: 2.5rem; padding-bottom: 5rem;">
    <div style="border-bottom: 1px solid var(--border-glass); padding-bottom: 1.25rem; margin-bottom: 2.5rem;">
        <span style="font-size: 0.72rem; letter-spacing: 2px; text-transform: uppercase; color: var(--accent-gold); font-weight: 700;">Despacho & Pago Seguro</span>
        <h1 style="font-family: var(--font-luxury); font-size: 2.2rem; letter-spacing: 2px; text-transform: uppercase; margin-top: 0.25rem;">Finalizar Compra</h1>
    </div>

    <?php if (!empty($error)): ?>
        <div style="background: rgba(139,30,36,0.25); border: 1px solid #8B1E24; color: #FFB4B6; padding: 1.25rem; border-radius: var(--radius-card); margin-bottom: 2rem; font-size: 0.9rem;">
            ⚠️ <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="/checkout" id="checkout-form" style="display: grid; grid-template-columns: 1fr 400px; gap: 3.5rem; align-items: start;">
        <input type="hidden" name="cart_data" id="hidden-cart-data">
        <input type="hidden" name="shipping_cost" id="hidden-shipping-cost" value="15.00">

        <!-- Columna Izquierda: Datos del Cliente y Envío -->
        <div style="display: flex; flex-direction: column; gap: 2rem;">
            <!-- 1. Contacto -->
            <section class="glass-card" style="padding: 2rem;">
                <h3 style="font-family: var(--font-luxury); font-size: 1.1rem; letter-spacing: 1.5px; text-transform: uppercase; color: var(--accent-gold); margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-glass); padding-bottom: 0.6rem;">
                    1. Datos de Contacto
                </h3>
                <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                    <div>
                        <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 0.35rem; font-weight: 600;">Nombre Completo *</label>
                        <input type="text" name="customer_name" required value="<?= htmlspecialchars($data['customer_name'] ?? ($user ? $user['name'] . ' ' . $user['lastname'] : '')) ?>" placeholder="Ej: Josuee Abrahan" style="width: 100%; background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.75rem 1rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.9rem;">
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 0.35rem; font-weight: 600;">Correo Electrónico *</label>
                            <input type="email" name="customer_email" required value="<?= htmlspecialchars($data['customer_email'] ?? ($user ? $user['email'] : '')) ?>" placeholder="cliente@gmail.com" style="width: 100%; background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.75rem 1rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.9rem;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 0.35rem; font-weight: 600;">Teléfono / WhatsApp *</label>
                            <input type="tel" name="customer_phone" required value="<?= htmlspecialchars($data['customer_phone'] ?? ($user ? $user['phone'] : '')) ?>" placeholder="+51 987 654 321" style="width: 100%; background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.75rem 1rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.9rem;">
                        </div>
                    </div>
                </div>
            </section>

            <!-- 2. Dirección con Autocompletado de Ubigeo Perú -->
            <section class="glass-card" style="padding: 2rem;">
                <h3 style="font-family: var(--font-luxury); font-size: 1.1rem; letter-spacing: 1.5px; text-transform: uppercase; color: var(--accent-gold); margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-glass); padding-bottom: 0.6rem;">
                    2. Dirección de Envío (Perú)
                </h3>
                
                <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                        <!-- Departamento con Datalist -->
                        <div>
                            <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 0.35rem; font-weight: 600;">Departamento *</label>
                            <input list="lista-departamentos" id="input-dept" name="shipping_department" required value="Cusco" placeholder="Ej: Cusco, Lima..." oninput="handleDeptChange(this.value)" style="width: 100%; background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.7rem 0.85rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.88rem;">
                            <datalist id="lista-departamentos">
                                <option value="Cusco">
                                <option value="Lima">
                                <option value="Arequipa">
                                <option value="La Libertad">
                                <option value="Puno">
                                <option value="Tacna">
                                <option value="Piura">
                                <option value="Lambayeque">
                                <option value="Junín">
                                <option value="Ica">
                                <option value="Áncash">
                                <option value="Ayacucho">
                                <option value="Cajamarca">
                                <option value="Huánuco">
                                <option value="San Martín">
                                <option value="Loreto">
                                <option value="Ucayali">
                                <option value="Callao">
                            </datalist>
                        </div>

                        <!-- Provincia con Datalist -->
                        <div>
                            <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 0.35rem; font-weight: 600;">Provincia *</label>
                            <input list="lista-provincias" id="input-prov" name="shipping_province" required value="Cusco" placeholder="Ej: Cusco, Lima..." oninput="handleProvChange(this.value)" style="width: 100%; background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.7rem 0.85rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.88rem;">
                            <datalist id="lista-provincias">
                                <option value="Cusco">
                                <option value="Urubamba">
                                <option value="Calca">
                                <option value="Anta">
                                <option value="Quispicanchi">
                                <option value="Canas">
                                <option value="Canchis">
                                <option value="Espinar">
                                <option value="La Convención">
                                <option value="Chumbivilcas">
                                <option value="Paucartambo">
                            </datalist>
                        </div>

                        <!-- Distrito con Datalist -->
                        <div>
                            <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 0.35rem; font-weight: 600;">Distrito *</label>
                            <input list="lista-distritos" id="input-dist" name="shipping_district" required value="San Jerónimo" placeholder="Ej: San Jerónimo, Wanchaq..." style="width: 100%; background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.7rem 0.85rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.88rem;">
                            <datalist id="lista-distritos">
                                <option value="San Jerónimo">
                                <option value="San Sebastián">
                                <option value="Wanchaq">
                                <option value="Santiago">
                                <option value="Cusco (Centro)">
                                <option value="Saylla">
                                <option value="Poroy">
                                <option value="Ccorca">
                            </datalist>
                        </div>
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 0.35rem; font-weight: 600;">Dirección Exacta (Av, Calle, N°, Dpto) *</label>
                        <input type="text" name="shipping_address" required placeholder="Ej: Av. de la Cultura 750, Dpto 301" style="width: 100%; background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.75rem 1rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.9rem;">
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 0.35rem; font-weight: 600;">Referencia de Entrega</label>
                        <input type="text" name="notes" placeholder="Frente al parque, portón negro..." style="width: 100%; background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 0.75rem 1rem; color: var(--text-primary); border-radius: var(--radius-btn); font-size: 0.88rem;">
                    </div>
                </div>
            </section>

            <!-- 3. Forma de Pago -->
            <section class="glass-card" style="padding: 2rem;">
                <h3 style="font-family: var(--font-luxury); font-size: 1.1rem; letter-spacing: 1.5px; text-transform: uppercase; color: var(--accent-gold); margin-bottom: 1.25rem; border-bottom: 1px solid var(--border-glass); padding-bottom: 0.6rem;">
                    3. Método de Pago
                </h3>
                <div style="display: flex; flex-direction: column; gap: 0.85rem;">
                    <label style="display: flex; align-items: center; gap: 0.75rem; background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 1rem 1.25rem; border-radius: var(--radius-btn); cursor: pointer;">
                        <input type="radio" name="payment_method" value="YAPE_PLIN" checked style="accent-color: var(--accent-gold); width: 18px; height: 18px;">
                        <div>
                            <strong style="display: block; font-size: 0.92rem;">Yape / Plin (Inmediato)</strong>
                            <span style="font-size: 0.78rem; color: var(--text-muted);">Transferencia móvil escaneando QR tras confirmar la orden.</span>
                        </div>
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.75rem; background: var(--bg-secondary); border: 1px solid var(--border-glass); padding: 1rem 1.25rem; border-radius: var(--radius-btn); cursor: pointer;">
                        <input type="radio" name="payment_method" value="TRANSFERENCIA_BCP" style="accent-color: var(--accent-gold); width: 18px; height: 18px;">
                        <div>
                            <strong style="display: block; font-size: 0.92rem;">Transferencia Bancaria (BCP / BBVA / Interbank)</strong>
                            <span style="font-size: 0.78rem; color: var(--text-muted);">Depósito directo a cuenta de la boutique.</span>
                        </div>
                    </label>
                </div>
            </section>
        </div>

        <!-- Columna Derecha: Resumen de la Orden -->
        <aside class="glass-card" style="padding: 2rem; position: sticky; top: 100px;">
            <h2 style="font-family: var(--font-luxury); font-size: 1.2rem; letter-spacing: 1.5px; text-transform: uppercase; color: var(--text-primary); padding-bottom: 1rem; border-bottom: 1px solid var(--border-glass); margin-bottom: 1.25rem;">
                Tu Pedido
            </h2>

            <div id="checkout-items-list" style="display: flex; flex-direction: column; gap: 0.75rem; max-height: 240px; overflow-y: auto; padding-right: 0.5rem; margin-bottom: 1.25rem; border-bottom: 1px solid var(--border-glass); padding-bottom: 1rem;">
            </div>

            <div style="display: flex; justify-content: space-between; font-size: 0.88rem; color: var(--text-secondary); margin-bottom: 0.6rem;">
                <span>Subtotal Modelos:</span>
                <strong id="chk-subtotal" style="color: var(--text-primary);">S/ 0.00</strong>
            </div>

            <div style="display: flex; justify-content: space-between; font-size: 0.88rem; color: var(--text-secondary); margin-bottom: 1.25rem;">
                <span>Flete de Envío Seguro:</span>
                <strong id="chk-shipping" style="color: var(--accent-gold);">S/ 25.00</strong>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: baseline; border-top: 1px solid var(--border-glass); padding-top: 1.25rem; font-size: 1.1rem; font-weight: 700;">
                <span>Total Final:</span>
                <span id="chk-total" style="font-family: var(--font-luxury); font-size: 1.9rem; color: var(--accent-gold);">S/ 0.00</span>
            </div>

            <button type="submit" style="width: 100%; background: var(--accent-gold); color: #090B0E; border: none; padding: 1rem; font-size: 0.85rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; border-radius: var(--radius-pill); cursor: pointer; margin-top: 1.75rem; box-shadow: 0 4px 20px var(--accent-glow);">
                CONFIRMAR Y ORDENAR PEDIDO &rarr;
            </button>
        </aside>
    </form>
</div>

<script>
const ubigeoData = {
    "Cusco": {
        "provincias": ["Cusco", "Urubamba", "Calca", "Anta", "Quispicanchi", "Canas", "Canchis", "Espinar", "La Convención", "Paucartambo"],
        "distritos": {
            "Cusco": ["San Jerónimo", "San Sebastián", "Wanchaq", "Santiago", "Cusco (Centro)", "Saylla", "Poroy", "Ccorca"],
            "Urubamba": ["Urubamba", "Ollantaytambo", "Maras", "Yucay", "Chinchero", "Machupicchu", "Huayllabamba"],
            "Calca": ["Calca", "Pisac", "San Salvador", "Lamay", "Coya", "Taray", "Lares", "Yanatile"]
        }
    },
    "Lima": {
        "provincias": ["Lima", "Barranca", "Cañete", "Huaral", "Huarochirí", "Huaura"],
        "distritos": {
            "Lima": ["Miraflores", "San Isidro", "Santiago de Surco", "La Molina", "San Borja", "Barranco", "Jesús María", "Magdalena", "Pueblo Libre", "Lince", "San Miguel", "Surquillo", "Lima Cercado", "Los Olivos", "San Juan de Lurigancho"]
        }
    },
    "Arequipa": {
        "provincias": ["Arequipa", "Camaná", "Caravelí", "Castilla", "Caylloma", "Islay"],
        "distritos": {
            "Arequipa": ["Arequipa (Centro)", "Yanahuara", "Cayma", "Cerro Colorado", "José Luis Bustamante y Rivero", "Paucarpata", "Socabaya", "Sachaca"]
        }
    },
    "La Libertad": {
        "provincias": ["Trujillo", "Ascope", "Chepén", "Pacasmayo", "Sánchez Carrión", "Virú"],
        "distritos": {
            "Trujillo": ["Trujillo (Centro)", "Víctor Larco Herrera", "Huanchaco", "La Esperanza", "El Porvenir", "Moche", "Salaverry"]
        }
    }
};

let currentShipping = 25.00;

function handleDeptChange(dept) {
    const provDatalist = document.getElementById("lista-provincias");
    const distDatalist = document.getElementById("lista-distritos");
    const provInput = document.getElementById("input-prov");
    const distInput = document.getElementById("input-dist");

    // Calcular flete según departamento (Lima = S/15, Provincia = S/25)
    currentShipping = (dept.trim().toLowerCase() === "lima" || dept.trim().toLowerCase() === "callao") ? 15.00 : 25.00;
    document.getElementById("hidden-shipping-cost").value = currentShipping.toFixed(2);
    document.getElementById("chk-shipping").textContent = `S/ ${currentShipping.toFixed(2)}`;
    updateTotals();

    if (ubigeoData[dept]) {
        provDatalist.innerHTML = ubigeoData[dept].provincias.map(p => `<option value="${p}">`).join("");
        provInput.value = ubigeoData[dept].provincias[0] || "";
        handleProvChange(provInput.value);
    }
}

function handleProvChange(prov) {
    const dept = document.getElementById("input-dept").value;
    const distDatalist = document.getElementById("lista-distritos");
    const distInput = document.getElementById("input-dist");

    if (ubigeoData[dept] && ubigeoData[dept].distritos[prov]) {
        distDatalist.innerHTML = ubigeoData[dept].distritos[prov].map(d => `<option value="${d}">`).join("");
        distInput.value = ubigeoData[dept].distritos[prov][0] || "";
    }
}

function updateTotals() {
    const sub = Cart.getSubtotal();
    const tot = sub + currentShipping;
    document.getElementById("chk-subtotal").textContent = `S/ ${sub.toFixed(2)}`;
    document.getElementById("chk-total").textContent = `S/ ${tot.toFixed(2)}`;
}

document.addEventListener("DOMContentLoaded", () => {
    const items = Cart.getItems();
    const listEl = document.getElementById("checkout-items-list");
    const hiddenData = document.getElementById("hidden-cart-data");

    if (items.length === 0) {
        window.location.href = "/cart";
        return;
    }

    hiddenData.value = JSON.stringify(items);

    listEl.innerHTML = items.map(item => `
        <div style="display: flex; justify-content: space-between; font-size: 0.85rem;">
            <div>
                <strong style="color: var(--text-primary); display:block;">${item.name}</strong>
                <span style="font-size: 0.72rem; color: var(--text-muted);">${item.quantity} x S/ ${item.price.toFixed(2)}</span>
            </div>
            <strong style="color: var(--text-primary); font-family: monospace;">S/ ${(item.price * item.quantity).toFixed(2)}</strong>
        </div>
    `).join("");

    handleDeptChange("Cusco");

    document.getElementById("checkout-form").addEventListener("submit", () => {
        localStorage.removeItem(Cart.storageKey);
    });
});
</script>