const Toast = {
    show(message, type = "success") {
        let container = document.getElementById("toast-container");
        if (!container) {
            container = document.createElement("div");
            container.id = "toast-container";
            container.style.cssText = "position: fixed; bottom: 2rem; right: 2rem; z-index: 9999; display: flex; flex-direction: column; gap: 0.75rem;";
            document.body.appendChild(container);
        }

        const toast = document.createElement("div");
        toast.className = "luxury-toast";
        toast.style.cssText = `
            background: var(--bg-card);
            backdrop-filter: blur(16px);
            border: 1px solid var(--border-accent);
            color: var(--text-primary);
            padding: 1rem 1.5rem;
            border-radius: var(--radius-card);
            box-shadow: 0 10px 30px rgba(0,0,0,0.5), 0 0 20px var(--accent-glow);
            font-size: 0.88rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            animation: slideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            font-family: var(--font-ui);
        `;

        const icon = type === "success" ? "✓" : "ℹ";
        const iconColor = type === "success" ? "var(--accent-gold)" : "#FFB74D";
        toast.innerHTML = `<span style="color: ${iconColor}; font-weight: 800; font-size: 1.1rem;">${icon}</span> <span>${message}</span>`;

        container.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = "0";
            toast.style.transform = "translateY(10px)";
            toast.style.transition = "all 0.3s ease";
            setTimeout(() => toast.remove(), 300);
        }, 3500);
    }
};