
const menuToggle = document.getElementById("menuToggle");
const navLinks = document.getElementById("navLinks");
const themeToggle = document.getElementById("themeToggle");

// Menu trên điện thoại
if (menuToggle && navLinks) {
    menuToggle.addEventListener("click", function () {
        const isOpen = navLinks.classList.toggle("active");

        menuToggle.textContent = isOpen ? "✕" : "☰";
        menuToggle.setAttribute("aria-expanded", String(isOpen));
    });

    navLinks.querySelectorAll("a").forEach(function (link) {
        link.addEventListener("click", function () {
            navLinks.classList.remove("active");
            menuToggle.textContent = "☰";
            menuToggle.setAttribute("aria-expanded", "false");
        });
    });
}

// Chuyển chế độ sáng/tối
if (themeToggle) {
    themeToggle.addEventListener("click", function () {
        const darkMode = document.body.classList.toggle("dark-mode");

        themeToggle.textContent = darkMode ? "☀️" : "🌙";
        console.log("Chế độ hiện tại:", darkMode ? "Tối" : "Sáng");
    });
}
