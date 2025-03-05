document.addEventListener("DOMContentLoaded", function () {
    let alert = document.querySelector(".alert");
    if (alert) {
        setTimeout(() => {
            alert.classList.add("fade");
            setTimeout(() => alert.remove(), 500);
        }, 3000);
    }
});
