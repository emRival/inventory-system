document.addEventListener("DOMContentLoaded", () => {
    if (!navigator.geolocation) {
        alert("Browser ini tidak mendukung Geolocation.");
        return;
    }

    navigator.geolocation.getCurrentPosition(
        function (position) {
            console.log("Akses lokasi diizinkan.");
            console.log("Latitude:", position.coords.latitude);
            console.log("Longitude:", position.coords.longitude);
        },
        function (error) {
            console.warn("Akses lokasi ditolak atau gagal.");
        }
    );
});
