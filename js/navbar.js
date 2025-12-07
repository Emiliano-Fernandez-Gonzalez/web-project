document.addEventListener("DOMContentLoaded", () => {
    const hamburger = document.getElementById("hamburger");
    const mobileMenu = document.getElementById("mobileMenu");
    const overlay = document.getElementById("navOverlay");

    if (!hamburger || !mobileMenu || !overlay) return;

    hamburger.onclick = () => {
        hamburger.classList.toggle("open");
        mobileMenu.classList.toggle("open");
        overlay.classList.toggle("open");
    };

    overlay.onclick = () => {
        hamburger.classList.remove("open");
        mobileMenu.classList.remove("open");
        overlay.classList.remove("open");
    };
});