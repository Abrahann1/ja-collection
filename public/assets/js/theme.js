(function () {
    const savedTheme = localStorage.getItem("ja_theme") || "dark";
    document.documentElement.setAttribute("data-theme", savedTheme);

    window.toggleTheme = function () {
        const currentTheme = document.documentElement.getAttribute("data-theme") || "dark";
        const newTheme = currentTheme === "dark" ? "light" : "dark";
        document.documentElement.setAttribute("data-theme", newTheme);
        localStorage.setItem("ja_theme", newTheme);
        updateSwitches(newTheme);
    };

    function updateSwitches(theme) {
        const isDark = theme === "dark";
        document.querySelectorAll(".theme-switch").forEach(el => {
            if (isDark) {
                el.classList.add("is-dark");
            } else {
                el.classList.remove("is-dark");
            }
        });
    }

    document.addEventListener("DOMContentLoaded", () => {
        updateSwitches(savedTheme);
    });
})();